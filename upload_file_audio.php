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
	$IdUsFIXED=0;			
	$confcode = $IdUsFIXED.md5(date('Ymdgisu'));
	$new_image_name = 'eML'.$confcode.'.wav';
	$filepath="Packages/".$new_image_name;

	move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
    //move_uploaded_file($_FILES['file']['tmp_name'], "../ext/wav/testes/" . $fname);
//}
    //$data = implode($_POST); //transforms the char array with the blob url to a string
    //$fname = "audio11" . ".wav";
	
	//$fromname='29';
	//$queId = '1165';
	//$fromname=$_POST['idmed'];
	$queId = $_GET['queId'];
	$fromname=$_GET['from'];
	
	//echo $queId ;
	$isaudio=1;

					//	echo "success" ;


						/*echo "<br>\n"; 
						echo "Checksum: "; */
						$checksum=md5_file($filepath);
						//echo $checksum; 
						//echo "<br>\n"; 
								
						//echo "<br>\n"; 	 
					//	echo "Fetching patient name";
					//	echo "<br>\n"; 
						$PatientDetails=getPatientName($queId);
						$IdUsu=$PatientDetails['Identif'];
						if($IdUsu==null)
							$IdUsu=0;
						$IdUsFIXED=$PatientDetails['IdUsFIXED'];
						$IdUsFIXEDNAME=$PatientDetails['IdUsFIXEDNAME'];
						
					//}
				
					
					
				
					 $Isql=$con->prepare("INSERT INTO lifepin SET NeedACTION = 1, IdEmail='1', FechaInput=NOW(), Fecha=NOW() , FechaEmail = Now() ,IdUsFIXED=?, IdUsFIXEDNAME=?, IdMedEmail = ?, RawImage=? , CANAL=5, ValidationStatus=8, EvRuPunt= 2, Evento=99, Tipo=30,checksum=?");
					 $Isql->bindValue(1, $IdUsFIXED, PDO::PARAM_INT);
					 $Isql->bindValue(2, $IdUsFIXEDNAME, PDO::PARAM_STR);
					 $Isql->bindValue(3, $fromname, PDO::PARAM_STR);
					 $Isql->bindValue(4, $new_image_name, PDO::PARAM_STR);
					 $Isql->bindValue(5, $checksum, PDO::PARAM_STR);
					 $q=$Isql->execute();
					
					if(!$q){
					die('PDO error on upload_file_audio.php');
					}
					
					
					$IdPin = $con->lastInsertId(); 
					echo "IdPin:".$IdPin;
					
 				
						/*$Isql="UPDATE lifepin SET IdEmail='1', RawImage='$new_image_name' , FechaInput=NOW(), ValidationStatus=8 , orig_filename ='$filename' WHERE IdPin='$IdPin'";
						$q = mysql_query($Isql);
						$IdPin = mysql_insert_id();*/
						// if successfully insert data into database, displays message "Successful". 
			/*			if($q){
			*				/*echo "Successful: Database Updated";
							echo "<br>\n"; 	
							echo "REGISTROS CAMBIADOS: ".mysql_affected_rows();
							echo "<br>\n"; 	*/
							
			/*				LogMov($IdUsFIXED, '0', 'webchannel', '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $filepath, 1);
				    	}
						else{
							/*echo "Error Updating Database ********";
							echo "<br>\n"; 	*/ 
		/*					LogMov($IdUsFIXED, $fromname, 'webchannel', '4', 'dB INSERTION', 'Error Updating Database ********' , $filepath, 1);
						}
							
							
							LogMov($IdUsFIXED, '0', 'webchannel', '2', 'File Local Save', $new_image_name, $filepath, 1);
							
				*/		
						
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
				echo "query: ".$query;
				echo "<br>";
				echo "count: ".$count1;
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					$row = $query->fetch(PDO::FETCH_ASSOC);
					//$i=$row['Identif'];
					return $row;
				}
		       
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
					$row = $query->fetch(PDO::FETCH_ASSOC);
					//$i=$row['Identif'];
					return $row;
				}
		       
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
				$result1 = $query->execute();

				$count1=$query->rowCount();
				echo "<br>";
				echo "query: ".$query;
				echo "<br>";
				echo "count: ".$count1;
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					$row = $query->fetch(PDO::FETCH_ASSOC);
					$i=$row['Id'];
				}
		       
			    $con = null;
			    return $i;
			   
	}	
		
####changes from cloud channel---Ends

?>
