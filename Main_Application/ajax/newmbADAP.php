<?php
define('INCLUDE_CHECK',1);
require "logger.php";

	 require("environment_detailForLogin.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
	$tbl_name="usuarios"; // Table name
					
	// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

// Grabación de un registro con fecha en la dB para comprobar la VITALIDAD
$IsqlX=$con->prepare("UPDATE vitalidad SET Fecha=NOW(), Programa = 'newmbADAP.php' WHERE IdProg = 1");
$qX = $IsqlX->execute();
// Grabación de un registro con fecha en la dB para comprobar la VITALIDAD

//Get Encryption Password
$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();

$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
$enc_pass=$row_enc['pass'];


   
//    $mailbox = imap_open("{imap.googlemail.com:993/ssl}INBOX", "newmedibank@googlemail.com", "ardiLLA98");
    //$mailbox = imap_open("{imap.googlemail.com:993/ssl}INBOX", "HUB@inmers.us", "mnibjb007");
	$mailbox = imap_open("{secure.emailsrvr.com:993/ssl}INBOX", "HUB@inmers.us", "mnibjb007");
    $mail = imap_search($mailbox, "ALL");

$mail_headers = imap_headerinfo($mailbox, $mail[0]);
    $subject = $mail_headers->subject;
    //echo $subject;
    $from = $mail_headers->fromaddress;
   
    
    //imap_setflag_full($mailbox, $mail[0], "\\Seen \\Flagged");
    $n= imap_num_msg($mailbox);
    
    
    echo "Start reading Repository...";
	echo "<br>\n"; 	    
    echo date(DATE_RFC822);
    echo "<br>\n"; 	    
    echo "Total Messages: ";
	echo $n;
	echo "<br>\n"; 	    
	
 
 /* try to connect */
$inbox = $mailbox;

/* grab emails */
//$emails = imap_search($inbox, 'FROM "jvinals@gmail.com"');

//$emails = imap_search($inbox, 'UNSEEN'); //Changed the UNSEEN from UNSEEN to NEW

$emails = array(); //New line added by Pallab because otherwise $emails as when initialized in below line it was only taking only 1 message as the paramter on RHS was an array and $emails was not defined as an array. To take all the values of the array from RHS, $emails has to be defined as an array
$emails = imap_search($inbox, 'UNSEEN');

//Below debug line added by Pallab
print_r($emails);
    echo "New Messages: ";
	echo count($emails);;
	echo "( ";
	echo $emails;
	echo " )";
	echo "<br>\n"; 	    


/* if emails are returned, cycle through each... */


if($emails) {											// PASA POR TODOS LOS EMAILS *NUEVOS* (UNSEEN) (Ojo: si están puestos como "read" o "seen" NO LOS VE

	echo "Analyzing... ";
	echo count($emails);
	echo " packages.";
	echo "<br>\n"; 	    


	
  /* begin output var */
  $output = '';

  /* put the newest emails on top */
  rsort($emails);



        // Aquí comienza a pasar por cada uno de los nuevos mensajes
    $order_message = 0;
    foreach($emails as $email_number) {					// BUCLE DE RECORRIDO DE LOS NUEVOS EMAILS (EMPIEZA AQUI)

	
	$order_message++;    
	
	echo "<br>\n"; 	    
	echo "<br>\n"; 	    
	echo "*******";
	echo "<br>\n"; 	    
	echo "Message ";
	echo $order_message;
	echo " of ";
	echo count($emails);
	echo " Order: ";
	echo $email_number;
	echo "<br>\n"; 	    
	
    $mail_headers = imap_headerinfo($mailbox, $email_number);
    $subject = $mail_headers->subject;							// SE ASIGNA: "SUBJECT"
    $date = $mail_headers->date;								// SE ASIGNA: "DATE"
    $from = $mail_headers->from;								// SE ASIGNA: "FROM" (OJO: ES UN ARRAY QUE HAY QUE PARSEAR)
    
    //Below line is commented by Pallab
   // $IdUsFIXED = substr($subject,0,10);
      
    //Below line inserted by Pallab    
    $IdUsu = substr($subject,19);
    settype($IdUsu, "integer"); //Changed this line from settype($IdUsFIXED, "integer") to settype($IdUsu, "integer")
        
        
    
    //Below lines commented by Pallab
    //Start of comment by Pallab
   /*$pos = strpos($subject, ' ');
    if (!$pos){											//No ha dejado espacio en blanco entre FIXED y FIXEDNAME
			$d=0;								//Find the first non-numeric
			$n=0;
			$quepos=0;
			while ($d==0)
				{
				$pnn = substr($subject,$n,1);
				if (!is_numeric($pnn))
					{
					$quepos=$n;
					$d=1;
					}
				$n++;
				if ($n > strlen($subject)){$d=1;}
				}
	    	$IdUsFIXEDNAME = substr($subject,$quepos);
    }else
    {
	    	$IdUsFIXEDNAME = substr($subject,$pos+1);
    }

*/
    
    //End of comment by Pallab
    
    foreach ($from as $id => $object) {
    	$fromNOM = $object->personal;							// SE ASIGNA: "FROM NAME" (PARSEADO DE "FROM")
    	$fromADD = $object->mailbox . "@" . $object->host;		// SE ASIGNA: "FROM MAILBOX" y "FROM HOST" (PARSEADO DE "FROM")
    	}
    $tiempo = $mail_headers->date;
    $dateId = strtotime($tiempo);
    $Msgno = $mail_headers->Msgno;   
    echo "*********************************************************";
	echo "<br>\n"; 	    
    echo "   Id: ";
    echo $Msgno;
	echo "<br>\n"; 	    
    echo "   IdUSFIXED: ";
    echo $IdUsFIXED;	
    echo "<br>\n"; 	    
    echo "   From: ";
    echo $fromNOM;
	echo "<br>\n"; 	    
    echo $fromADD;
	echo "<br>\n"; 	    
    echo "   Date: ";
    echo $tiempo;
	echo "<br>\n"; 	    
    echo "   DateID: ";
    echo $dateId;
	echo "<br>\n"; 	    
    echo "*********************************************************";
    echo "<br>\n"; 
    
    foreach ($from as $id => $object) {
    	$fromname = $object->personal;
    	$fromaddress = $object->mailbox . "@" . $object->host;
    	  echo "FROM name: ";
    	  echo $fromname;
    	  echo "<br>\n"; 	    
     	  echo "FROM address: ";
    	  echo $fromaddress;
    	  echo "<br>\n"; 	    
    	}
	    

    echo " Subject: ";
	echo $subject;
	echo "<br>\n"; 	    
	echo " Date: ";
	echo $date;
	echo "<br>\n"; 	    
	
	
 //echo ' ************************************************ INSERCION EN BASE DE DATOS ***********************************************************';
							    
 		echo 'INSERCION IdUsFIXED = '.$IdUsu;  //Changed this line from IdUsFIXED to $IdUsu
		echo "<br>\n"; 
							  
		echo 'INSERCION IdUsFIXED = '.intval($IdUsu);  //Changed this line from IdUsFIXED to $IdUsu
		echo "<br>\n"; 
		
		//BLOCKSLIFEPIN $Isql="INSERT INTO blocks SET NeedACTION = 1, IdEmail='$Msgno', FechaInput=NOW() , FechaEmail = '$date', IdUsFIXED='$IdUsFIXED', IdUsFIXEDNAME='$IdUsFIXEDNAME', IdMedEmail = '$fromaddress', Canal=1, ValidationStatus=99 ";
		//Start of comment by Pallab
               /* $Isql=$con->prepare("INSERT INTO lifepin SET NeedACTION = 1, IdEmail=?, FechaInput=NOW(), Fecha=NOW() , FechaEmail = ?, IdUsFIXED=?, IdUsFIXEDNAME=?, IdMedEmail = ?, CANAL=1, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=30 ");
		$Isql->bindValue(1, $Msgno, PDO::PARAM_INT);
		$Isql->bindValue(2, $date, PDO::PARAM_STR);
		$Isql->bindValue(3, $IdUsFIXED, PDO::PARAM_INT);
		$Isql->bindValue(4, $IdUsFIXEDNAME, PDO::PARAM_STR);
		$Isql->bindValue(5, $fromaddress, PDO::PARAM_STR);
		  
		$q = $Isql->execute();
		$IdPin = $con->lastInsertId();*/
                //End of comment by Pallab 
							   		
						
	// ********************************************
				        
				        /* get information specific to this email */
						$overview = imap_fetch_overview($inbox,$email_number,0);		// SE LEE "OVERVIEW" de este email
 					    $message = imap_fetchbody($inbox,$email_number,2);				// SE LEE "BODY" de este email
					    $structure = imap_fetchstructure($inbox,$email_number);			// SE LEE "STRUCTURE" de este email
					    //below debug line inserted by Pallab

                                            imap_setflag_full($inbox, $email_number, "\\Seen \\Flagged");  // Ya está visto (correcto o no), lo pongo a cero.
                        $USUOK = 1;
                        
					    $attachments = array();
					    if ($USUOK == 1){
					    //echo " ***************** PREVIO        ****************";
						//echo "Partes: ";
						//echo count($structure->parts);
					    if(isset($structure->parts) && count($structure->parts)) {		 /// Tiene ATTACHMENT	(COMIENZA EL "PARSEO" DE SU ESTRUCTURA PARA SACAR EL FICHERO)			    
						// Recorre el contenido del email por partes    	
						$Tamano = 0;
						$NumeroFic;	
                    
                        for($i = 0; $i < count($structure->parts); $i++) 
                        
                                                   {				 /// "PARSEO" DE LA ESTRUCTURA: Se asignan las partes de la estructura al array "$attachments", con un flag de attachment a CERO

                        
						       //Start of new piece of code by Pallab
                                                      //Below piece insert query was replaced from above and placed here because if it is as placed above then it will not make entry to lifepin for an email which has multiple attachments
                                                      //Below query commented by Pallab
        //$Isql=$con->prepare("INSERT INTO lifepin SET NeedACTION = 1, IdEmail=?, FechaInput=NOW(), Fecha=NOW() , FechaEmail = ?, IdUsFIXED=?, IdUsFIXEDNAME=?, IdMedEmail = ?, CANAL=1, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=30 ");
        
                                                   //New query added by Pallab
                                                 /*  $Isql=$con->prepare("INSERT INTO lifepin SET NeedACTION = 1, IdEmail=?, FechaInput=NOW(), Fecha=NOW() , FechaEmail = ?, IdUsu=?, IdMedEmail = ?, CANAL=1, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=30 ");
                                                   $Isql->bindValue(1, $Msgno, PDO::PARAM_INT);
                                                   $Isql->bindValue(2, $date, PDO::PARAM_STR);
                                                   $Isql->bindValue(3, $IdUsu, PDO::PARAM_INT); // Changed this line from $Isql->bindValue(3, $IdUsFIXED, PDO::PARAM_INT); to $Isql->bindValue(3, $IdUsu, PDO::PARAM_INT);
		                                           //$Isql->bindValue(4, $IdUsFIXEDNAME, PDO::PARAM_STR); //Commented this line by Pallab
                                                   $Isql->bindValue(4, $fromaddress, PDO::PARAM_STR);
 
                                                   $q = $Isql->execute();
                                                   $IdPin = $con->lastInsertId(); */
                                                      //End of new piece of code by Pallab
                                           


                                                	$attachments[$i] = array(
								'is_attachment' => false,
								'filename' => '',
								'name' => '',
								'attachment' => ''
							);
							
							$Tamano = $Tamano + $structure->parts[$i]->bytes;				// Se saca el tamaño en bytes de cada una de las partes de la estructura (por si resulta ser el fichero)
							
							/*
							echo "<br>\n"; 
							echo '///////////////////////////// ';
							echo 'i = ';
							echo $i;
							echo ' +++++ ';
							echo "TAMANO: ";
							echo $Tamano;
							echo "<br>\n"; 
							*/
							
							if($structure->parts[$i]->ifdparameters) {
								
                                                        //Log line added by Pallab
                                                        echo "in if(structure) ifdparameters block";
foreach($structure->parts[$i]->dparameters as $object) {	// El array de ESTRUCTURA TIENE 2 PARTES: "dparameters" y "parameters". AQUI SE PARSEA "dparameters"
									if(strtolower($object->attribute) == 'filename') {		// Aquí es donde se reconoce que el attachment SÍ ES UN FICHERO (FILENAME)
										$attachments[$i]['is_attachment'] = true;			//  ...Se asigna el flag de attachment a TRUE
										$attachments[$i]['filename'] = $object->value;		//  ...Se asigna el valor del fichero al objeto para copiarlo luego
									}
								}
							}
		
							if($structure->parts[$i]->ifparameters) {

                                                        //Log line inserted by Pallab
                                                        echo "in if(structure) ifparameters block";
foreach($structure->parts[$i]->parameters as $object1) {		// El array de ESTRUCTURA TIENE 2 PARTES: "dparameters" y "parameters". AQUI SE PARSEA "parameters"
									
                                                      //log line inserted by Pallab
                                                      echo "in foreach parameters object block";
                                              //echo "Object->Attribute is ".strtolower($object->attribute); //New line inserted by Pallab

if(strtolower($object1->attribute) == 'name') {			// Aquí es donde se reconoce que el attachment SÍ ES UN FICHERO (NAME)
										
                                                                        //Log line inserted by Pallab
                                                                        echo "In if block strtolower object attribute name";
                                                                                $attachments[$i]['is_attachment'] = true;			//  ...Se asigna el flag de attachment a TRUE
										$attachments[$i]['name'] = $object1->value;			//  ...Se asigna el valor del fichero al objeto para copiarlo luego
										$NumeroFic = $i;

							//echo " ***************** PASO ****************";
							//echo $attachments[$i]['name'] ;
							//echo "   i = ";
							//echo $i;
						    //echo "<br>\n"; 	    

									}
								}
							}
		
							if($attachments[$i]['is_attachment']) {																// Aquí se pasa el attachment al objeto físico (array attachments['attachment']) para base64
								//Log line added by Pallab
                                
                                echo "In Attachment block";
                                
                                $attachments[$i]['attachment'] = imap_fetchbody($mailbox, $email_number, $i+1);
								if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
									$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
									echo "Attachment base64_decode FOUND";
									echo "<br>\n"; 
									LogMov($IdUsFIXED, $fromname, $fromaddress, '1', 'Attachment', 'Attachment base64_decode FOUND', $subject, $email_number);

								}
								elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE							// Aquí se pasa el attachment al objeto físico (array attachments['attachment']) para QUOTED-P
									$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
									echo "Attachment quoted_printable_decode FOUND";
									echo "<br>\n"; 
									LogMov($IdUsFIXED, $fromname, $fromaddress, '1', 'Attachment', 'Attachment quoted_printable_decode FOUND', $subject, $email_number);
								}
							}else																								// Hay un attachment, PERO NO ES UN FICHERO VÁLIDO
							{
								echo "No attachment found or invalid attachment.";
								echo "<br>\n"; 
								LogMov($IdUsu, $fromname, $fromaddress, '1', 'Attachment', 'No attachment found or invalid attachment', $subject, $email_number);
                                continue;
						   
								//$Isql="INSERT INTO blocks SET FechaInput=NOW()";
							    //echo $Isql;
							    //$q = mysql_query($Isql);
							    //$IdPin = mysql_insert_id();
						   
                            }

                            
                              $Isql=$con->prepare("INSERT INTO lifepin SET NeedACTION = 1, IdEmail=?, FechaInput=NOW(), Fecha=NOW() , FechaEmail = ?, IdUsu=?, IdMedEmail = ?, CANAL=1, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=30 ");
                                                   $Isql->bindValue(1, $Msgno, PDO::PARAM_INT);
                                                   $Isql->bindValue(2, $date, PDO::PARAM_STR);
                                                   $Isql->bindValue(3, $IdUsu, PDO::PARAM_INT); // Changed this line from $Isql->bindValue(3, $IdUsFIXED, PDO::PARAM_INT); to $Isql->bindValue(3, $IdUsu, PDO::PARAM_INT);
		                                           //$Isql->bindValue(4, $IdUsFIXEDNAME, PDO::PARAM_STR); //Commented this line by Pallab
                                                   $Isql->bindValue(4, $fromaddress, PDO::PARAM_STR);
 
                                                   $q = $Isql->execute();
                                                   $IdPin = $con->lastInsertId();
//Start of new piece of code added by Pallab
                            echo "NumeroFic is ::".$NumeroFic;
	                        echo "FILE: ".$attachments[$NumeroFic]['name'];
 						    echo "<br>\n"; 
 						    
 						    $extension = substr($attachments[$NumeroFic]['name'],1+strpos($attachments[$NumeroFic]['name'], '.'),3);
 						    echo "EXTENSION : ".$extension;
 						    echo "<br>\n"; 
 						    																	// extraer cual es la EXTENSIÓN DEL ATTACHMENT (Para conservarla en adelante)
 						    
 						    																	// COMPROBAR EL ATTACHMENT PARA DECIDIR SI SE AÑADE O NO
 						    echo "SIZE: ";
 						    echo $Tamano;
 						    echo "<br>\n"; 
 						    if ($Tamano > 20000000){
	 						    echo "SIZE EXCEEDS 20 Mb. LIMIT";
	 						    echo "<br>\n"; 
 						    }else
 						    {								
 						// REQUISITOS BASICOS CORRECTOS *********************************************************************    
 						if(count($attachments)!=0){

	 					// GRABACIÓN DEL ATTACHMENT EN EL DIRECTORIO LOCAL PARA LUEGO HACER EL FTP
	 					$confcode = $IdUsFIXED.md5(date('Ymdgisu'));
	 					$new_image_name = 'eML'.$confcode.'.'.$extension;										// ASIGNACIÓN DE UN NOMBRE DE FICHERO ÚNICO

	 					echo " NEW FILE : ".$new_image_name;
 						echo "<br>\n"; 
 						    
//$new_image_name = 'PRUEBABORRAR.PDF';
//file_put_contents($new_image_name, $attachments[$NumeroFic]['attachment']);					// GRABACIÓN FÍSICA DEL FICHERO CONTENIDO EN EL ATTACHMENT EN EL SERVIDOR (PACKAGES)

 						$locFile = "Packages_Encrypted/";
					    file_put_contents($locFile.$new_image_name, $attachments[$NumeroFic]['attachment']);					// GRABACIÓN FÍSICA DEL FICHERO CONTENIDO EN EL ATTACHMENT EN EL SERVIDOR (PACKAGES)
						
						 							   										//Sección de Creación de un thumbnail desde el PDF  ********************
						$locFileTH = "PackagesTH_Encrypted/";
						$new_image_nameTH = 'eML'.$confcode.'.png';										
					    $cadenaConvert = 'convert "'.$locFile.$new_image_name.'" -colorspace RGB -geometry 200 "'.$locFileTH.$new_image_nameTH.'" 2>&1';
																							
						echo "<br>\n"; 
						echo "Thumbnail: ";
						echo $cadenaConvert;
						echo "<br>\n"; 
						
						echo "EXEC IMAGEMAGIK -----------";
						//$output = shell_exec('convert "rodilla2012rx.pdf" "rodilla2012.png" 2>&1');  // ESTO HA FUNCIONADO DESPUES DE REINICIAR EL SERVIDOR
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
                        shell_exec("rm temp/".$new_image_name);		 //Encrypt the report
                                            //Sección de Creación de un thumbnail desde el PDF  
					
						//Below line commented by Pallab
                       // $Isql=$con->prepare("UPDATE lifepin SET IdEmail=?, RawImage=? , FechaInput=NOW() , Fecha=NOW(), IdUsFIXED=?, ValidationStatus=8  WHERE IdPin=?");
                            
                        //New line added by Pallab
                        $Isql=$con->prepare("UPDATE lifepin SET IdEmail=?, RawImage=? , FechaInput=NOW() , Fecha=NOW(), ValidationStatus=8  WHERE IdPin=?");
						$Isql->bindValue(1, $Msgno, PDO::PARAM_INT);
						$Isql->bindValue(2, $new_image_name, PDO::PARAM_STR);
						//	$Isql->bindValue(3, $IdUsFIXED, PDO::PARAM_INT); //Commented by Pallab
						$Isql->bindValue(3, $IdPin, PDO::PARAM_INT);
						
						$q = $Isql->execute();
						$IdPin = $con->lastInsertId(); 
						// if successfully insert data into database, displays message "Successful". 
						if($q){
							echo "Successful: Database Updated";
							echo "<br>\n"; 	
							echo "REGISTROS CAMBIADOS: ".$Isql->rowCount();
							echo "<br>\n"; 	
							//  EnviaMail($eMailUsu,0, $IdUsu, $fromname, $fromaddress, $IdPin, $confirm_code);
							LogMov($IdUsu, $fromname, $fromaddress, '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $subject, $email_number);  //Replaced $IdUsFIXED by $IdUsu
								}
							else{
								echo "Error Updating Database ********";
								echo "<br>\n"; 	 
								LogMov($IdUsu, $fromname, $fromaddress, '4', 'dB INSERTION', 'Error Updating Database ********' , $subject, $email_number);  //Replaced $IdUsFIXED by $IdUsu
							}
							
					
					
						//echo "Connection attempt failed";
						//echo "<br>\n"; 
						LogMov($IdUsu, $fromname, $fromaddress, '2', 'File Local Save', $new_image_name, $subject, $email_number); //Replaced $IdUsFIXED by $IdUsu
					     
					     // FTP TRANSFER *****************************************************************************************	// TRANSFERENCIA DEL ARCHIVO AL SERVIDOR QUE TIENE LA BASE DE DATOS  (PinEXT)
						$server = "monimed.com"; //target server address or domain name from we wana download file
						$user = "moni8484"; //username on target server
						$pass = "ardiLLA98@"; //password on target server for Ftp
						$source = $locFile.$attachments[$NumeroFic]['name']; /*source file server which we wana download ,single file name refersfile is in Home/root*/
						//echo " ARCHIVO LOCAL : ";
						//echo $source;
		//Commented by Pallab for handling multiple attachments testing				$dest = '/PinEXT/local.pdf';//download file and store as local.tar
		//Commented by Pallab for handling multiple attachments testing				$mode = "FTP_BINARY";
						//$local_file = './Packages/windows.png';
						
//						$local_file = './Packages/'.$attachments[$NumeroFic]['name'];
//						$dest_file = $attachments[$NumeroFic]['name'] ;
	//Commented by Pallab for handling multiple attachments testing					$local_file = './Packages/'.$new_image_name;
	//Commented by Pallab for handling multiple attachments testing					$dest_file = $new_image_name ;

               //Start of comment by Pallab for disabling the FTP
				/*		$connection = ftp_connect($server, $port = 21);
						$login = ftp_login($connection, $user, $pass);
						ftp_pasv($connection, true);

						if (!$connection || !$login) { 
						
						die('Connection attempt failed!'); 
						//echo "Connection attempt failed";
						echo "<br>\n"; 
						LogMov($IdUsu, $fromname, $fromaddress, '3', 'File Upload', 'FTP Connection attempt failed', $subject, $email_number);
								
								imap_clearflag_full($inbox, $email_number,"\\Seen \\Flagged");   						//Ha dado error de FTP, así que lo volvemos a poner como "NO LEIDO" 
							
						}

						if (ftp_chdir($connection, "PinEXT")) {
						    //echo "Current directory is now: " . ftp_pwd($connection) . "\n";
						    //$upload = ftp_put($connection, $dest, $source, FTP_BINARY);
						    $upload = ftp_put($connection, $dest_file, $local_file, FTP_BINARY);
						    if (!$upload) { echo 'FTP upload failed!'; }else
						    {																	// EL FTP HA IDO BIEN.  YA SE PUEDE CREAR EL BLOCK ****************************
							    echo "File uploaded successfully";
							    echo "<br>\n"; 
							    echo "<br>\n"; 
							    LogMov($IdUsFIXED, $fromname, $fromaddress, '3', 'File Upload', 'FTP file UPLOADED SUCCESSFULLY', $subject, $email_number);
	 
	    
							    // Inserta el PIN en la Base de Datos
							    $confirm_code = md5(uniqid(rand()));
							    
							    //echo ' ************************************************ INSERCION 2 EN BASE DE DATOS ***********************************************************';
							    
							    echo 'UPDATE IdUsFIXED = '.$IdUsFIXED;
							    echo "<br>\n"; 
							    echo "IdPin = ";
							    echo $IdPin;
							    echo "<br>\n"; 
							    
							    echo 'UPDATE IdUsFIXED = '.intval($IdUsFIXED);
							    
							    //BLOCKSLIFEPIN $Isql="UPDATE blocks SET IdEmail='$Msgno', file1='$dest_file' , FechaInput=NOW() , IdUsFIXED='$IdUsFIXED', ValidationStatus=8  WHERE Id='$IdPin'";
							    $Isql=$con->prepare("UPDATE lifepin SET IdEmail=?, RawImage=? , FechaInput=NOW() , Fecha=NOW(), IdUsFIXED=?, ValidationStatus=8  WHERE IdPin=?");
								$Isql->bindValue(1, $Msgno, PDO::PARAM_INT);
								$Isql->bindValue(2, $dest_file, PDO::PARAM_STR);
								$Isql->bindValue(3, $IdUsFIXED, PDO::PARAM_INT);
								$Isql->bindValue(4, $IdPin, PDO::PARAM_INT);

							    $q = $Isql->execute();
							    $IdPin = $con->lastInsertId(); 
							    // if successfully insert data into database, displays message "Successful". 
							    if($q){
								    	echo "Successful: Database Updated";
								    	echo "<br>\n"; 	
								    	echo "REGISTROS CAMBIADOS: ".$Isql->rowCount();
								    	echo "<br>\n"; 	
								    	//  EnviaMail($eMailUsu,0, $IdUsu, $fromname, $fromaddress, $IdPin, $confirm_code);
								    	LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $subject, $email_number);

								    	}
								    	else{
									    echo "Error Updating Database ********";
								    	echo "<br>\n"; 	 
								    	LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'Error Updating Database ********' , $subject, $email_number);

								    	}
		    						}

    					} else { 
						    echo "Couldn't change directory\n";
						    echo "<br>\n"; 
						    LogMov($IdUsFIXED, $fromname, $fromaddress, '3', 'File Upload', 'FTP directory change failed', $subject, $email_number);

						    echo "<br>\n"; 	    
    					}
						ftp_close($connection); */
            //End of comment by Pallab 
						// ******************************************************************************************************
						     }  // CIERRE DEL IF COUNT ATTACHMENTS 
 						    }  // CIERRE DEL IF TAMANO > 2000
 						// END REQUISITOS BASICOS CORRECTOS *********************************************************************    

					       // imap_clearflag_full($inbox, $email_number, "\\Seen \\Flagged");

					      }		// CIERRE DEL IF ISSET() AND PARTS	(158)			
					     /* else
					      {
						      	echo "No attachment found";
								echo "<br>\n"; 
						LogMov($IdUsFIXED, $fromname, $fromaddress, '1', 'Attachment', 'No attachment found', $subject, $email_number);

					      } */

//End of new piece of code by Pallab 

					
	}
						
//Start of commenting by Pallab                                                
/*	echo "FILE: ".$attachments[$NumeroFic]['name'];
 						    echo "<br>\n"; 
 						    
 						    $extension = substr($attachments[$NumeroFic]['name'],1+strpos($attachments[$NumeroFic]['name'], '.'),3);
 						    echo "EXTENSION : ".$extension;
 						    echo "<br>\n"; 
 						    																									// extraer cual es la EXTENSIÓN DEL ATTACHMENT (Para conservarla en adelante)
 						    
 						    																									// COMPROBAR EL ATTACHMENT PARA DECIDIR SI SE AÑADE O NO
 						    echo "SIZE: ";
 						    echo $Tamano;
 						    echo "<br>\n"; 
 						    if ($Tamano > 20000000){
	 						    echo "SIZE EXCEEDS 20 Mb. LIMIT";
	 						    echo "<br>\n"; 
 						    }else
 						    {								
 						// REQUISITOS BASICOS CORRECTOS *********************************************************************    
 						if(count($attachments)!=0){

	 					// GRABACIÓN DEL ATTACHMENT EN EL DIRECTORIO LOCAL PARA LUEGO HACER EL FTP
	 					$confcode = $IdUsFIXED.md5(date('Ymdgisu'));
	 					$new_image_name = 'eML'.$confcode.'.'.$extension;										// ASIGNACIÓN DE UN NOMBRE DE FICHERO ÚNICO

	 					echo " NEW FILE : ".$new_image_name;
 						echo "<br>\n"; 
 						    
//$new_image_name = 'PRUEBABORRAR.PDF';
//file_put_contents($new_image_name, $attachments[$NumeroFic]['attachment']);					// GRABACIÓN FÍSICA DEL FICHERO CONTENIDO EN EL ATTACHMENT EN EL SERVIDOR (PACKAGES)

 						$locFile = "Packages_Encrypted/";
					    file_put_contents($locFile.$new_image_name, $attachments[$NumeroFic]['attachment']);					// GRABACIÓN FÍSICA DEL FICHERO CONTENIDO EN EL ATTACHMENT EN EL SERVIDOR (PACKAGES)
						
						 							   																			//Sección de Creación de un thumbnail desde el PDF  ********************
						$locFileTH = "PackagesTH_Encrypted/";
						$new_image_nameTH = 'eML'.$confcode.'.png';										
					    $cadenaConvert = 'convert "'.$locFile.$new_image_name.'" -colorspace RGB -geometry 200 "'.$locFileTH.$new_image_nameTH.'" 2>&1';
																																	
						echo "<br>\n"; 
						echo "Thumbnail: ";
						echo $cadenaConvert;
						echo "<br>\n"; 
						
						echo "EXEC IMAGEMAGIK -----------";
						//$output = shell_exec('convert "rodilla2012rx.pdf" "rodilla2012.png" 2>&1');  // ESTO HA FUNCIONADO DESPUES DE REINICIAR EL SERVIDOR
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
                        shell_exec("rm temp/".$new_image_name);		 //Encrypt the report
                                            //Sección de Creación de un thumbnail desde el PDF  
					
						$Isql=$con->prepare("UPDATE lifepin SET IdEmail=?, RawImage=? , FechaInput=NOW() , Fecha=NOW(), IdUsFIXED=?, ValidationStatus=8  WHERE IdPin=?");
						$Isql->bindValue(1, $Msgno, PDO::PARAM_INT);
						$Isql->bindValue(2, $new_image_name, PDO::PARAM_STR);
						$Isql->bindValue(3, $IdUsFIXED, PDO::PARAM_INT);
						$Isql->bindValue(4, $IdPin, PDO::PARAM_INT);
						
						$q = $Isql->execute();
						$IdPin = $con->lastInsertId(); 
						// if successfully insert data into database, displays message "Successful". 
						if($q){
							echo "Successful: Database Updated";
							echo "<br>\n"; 	
							echo "REGISTROS CAMBIADOS: ".$Isql->rowCount();
							echo "<br>\n"; 	
							//  EnviaMail($eMailUsu,0, $IdUsu, $fromname, $fromaddress, $IdPin, $confirm_code);
							LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $subject, $email_number);
								}
							else{
								echo "Error Updating Database ********";
								echo "<br>\n"; 	 
								LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'Error Updating Database ********' , $subject, $email_number);
							}
							
					
					
						//echo "Connection attempt failed";
						//echo "<br>\n"; 
						LogMov($IdUsFIXED, $fromname, $fromaddress, '2', 'File Local Save', $new_image_name, $subject, $email_number);
					     
					     // FTP TRANSFER *****************************************************************************************	// TRANSFERENCIA DEL ARCHIVO AL SERVIDOR QUE TIENE LA BASE DE DATOS  (PinEXT)
						$server = "monimed.com"; //target server address or domain name from we wana download file
						$user = "moni8484"; //username on target server
						$pass = "ardiLLA98@"; //password on target server for Ftp
						$source = $locFile.$attachments[$NumeroFic]['name']; /*source file server which we wana download ,single file name refersfile is in Home/root*/
						//echo " ARCHIVO LOCAL : ";
						//echo $source;
		//Commented by Pallab for handling multiple attachments testing				$dest = '/PinEXT/local.pdf';//download file and store as local.tar
		//Commented by Pallab for handling multiple attachments testing				$mode = "FTP_BINARY";
						//$local_file = './Packages/windows.png';
						
//						$local_file = './Packages/'.$attachments[$NumeroFic]['name'];
//						$dest_file = $attachments[$NumeroFic]['name'] ;
	//Commented by Pallab for handling multiple attachments testing					$local_file = './Packages/'.$new_image_name;
	//Commented by Pallab for handling multiple attachments testing					$dest_file = $new_image_name ;

               //Start of comment by Pallab for disabling the FTP
				/*		$connection = ftp_connect($server, $port = 21);
						$login = ftp_login($connection, $user, $pass);
						ftp_pasv($connection, true);

						if (!$connection || !$login) { 
						
						die('Connection attempt failed!'); 
						//echo "Connection attempt failed";
						echo "<br>\n"; 
						LogMov($IdUsu, $fromname, $fromaddress, '3', 'File Upload', 'FTP Connection attempt failed', $subject, $email_number);
								
								imap_clearflag_full($inbox, $email_number,"\\Seen \\Flagged");   						//Ha dado error de FTP, así que lo volvemos a poner como "NO LEIDO" 
							
						}

						if (ftp_chdir($connection, "PinEXT")) {
						    //echo "Current directory is now: " . ftp_pwd($connection) . "\n";
						    //$upload = ftp_put($connection, $dest, $source, FTP_BINARY);
						    $upload = ftp_put($connection, $dest_file, $local_file, FTP_BINARY);
						    if (!$upload) { echo 'FTP upload failed!'; }else
						    {																						 	// EL FTP HA IDO BIEN.  YA SE PUEDE CREAR EL BLOCK ****************************
							    echo "File uploaded successfully";
							    echo "<br>\n"; 
							    echo "<br>\n"; 
							    LogMov($IdUsFIXED, $fromname, $fromaddress, '3', 'File Upload', 'FTP file UPLOADED SUCCESSFULLY', $subject, $email_number);
	 
	    
							    // Inserta el PIN en la Base de Datos
							    $confirm_code = md5(uniqid(rand()));
							    
							    //echo ' ************************************************ INSERCION 2 EN BASE DE DATOS ***********************************************************';
							    
							    echo 'UPDATE IdUsFIXED = '.$IdUsFIXED;
							    echo "<br>\n"; 
							    echo "IdPin = ";
							    echo $IdPin;
							    echo "<br>\n"; 
							    
							    echo 'UPDATE IdUsFIXED = '.intval($IdUsFIXED);
							    
							    //BLOCKSLIFEPIN $Isql="UPDATE blocks SET IdEmail='$Msgno', file1='$dest_file' , FechaInput=NOW() , IdUsFIXED='$IdUsFIXED', ValidationStatus=8  WHERE Id='$IdPin'";
							    $Isql=$con->prepare("UPDATE lifepin SET IdEmail=?, RawImage=? , FechaInput=NOW() , Fecha=NOW(), IdUsFIXED=?, ValidationStatus=8  WHERE IdPin=?");
								$Isql->bindValue(1, $Msgno, PDO::PARAM_INT);
								$Isql->bindValue(2, $dest_file, PDO::PARAM_STR);
								$Isql->bindValue(3, $IdUsFIXED, PDO::PARAM_INT);
								$Isql->bindValue(4, $IdPin, PDO::PARAM_INT);

							    $q = $Isql->execute();
							    $IdPin = $con->lastInsertId(); 
							    // if successfully insert data into database, displays message "Successful". 
							    if($q){
								    	echo "Successful: Database Updated";
								    	echo "<br>\n"; 	
								    	echo "REGISTROS CAMBIADOS: ".$Isql->rowCount();
								    	echo "<br>\n"; 	
								    	//  EnviaMail($eMailUsu,0, $IdUsu, $fromname, $fromaddress, $IdPin, $confirm_code);
								    	LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $subject, $email_number);

								    	}
								    	else{
									    echo "Error Updating Database ********";
								    	echo "<br>\n"; 	 
								    	LogMov($IdUsFIXED, $fromname, $fromaddress, '4', 'dB INSERTION', 'Error Updating Database ********' , $subject, $email_number);

								    	}
		    						}

    					} else { 
						    echo "Couldn't change directory\n";
						    echo "<br>\n"; 
						    LogMov($IdUsFIXED, $fromname, $fromaddress, '3', 'File Upload', 'FTP directory change failed', $subject, $email_number);

						    echo "<br>\n"; 	    
    					}
						ftp_close($connection); */
            //End of comment by Pallab 
						// ******************************************************************************************************
						//Commented by Pallab for handling multiple attachments testing     }  // CIERRE DEL IF COUNT ATTACHMENTS 
 						 //Commented by Pallab for handling multiple attachments testing   }  // CIERRE DEL IF TAMANO > 2000
 						// END REQUISITOS BASICOS CORRECTOS *********************************************************************    

					       // imap_clearflag_full($inbox, $email_number, "\\Seen \\Flagged");

					   //Commented by Pallab for handling multiple attachments testing   }		// CIERRE DEL IF ISSET() AND PARTS	(158)			
					     //Commented by Pallab for handling multiple attachments testing else
					     //Commented by Pallab for handling multiple attachments testing {
					//Commented by Pallab for handling multiple attachments testing	      	echo "No attachment found";
					//Commented by Pallab for handling multiple attachments testing			echo "<br>\n"; 
					//Commented by Pallab for handling multiple attachments testing			LogMov($IdUsFIXED, $fromname, $fromaddress, '1', 'Attachment', 'No attachment found', $subject, $email_number);

					   //Commented by Pallab for handling multiple attachments testing   } */
					    //End of comment by Pallab  
					      }     // CIERRE DEL IF USUOK = 1          (262)

   
}

} 

/* close the connection */
 

    imap_close($mailbox);

//EnviaMail('lapspart@me.com');


function EnviaMail($objetivo, $codExito, $IdU, $fro, $froa ,$quePIN, $queCodigo)
{
require_once 'lib/swift_required.php';

if ($codExito==0)
{
//LogMov($IdUsu, $fromname, $fromaddress, '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $subject, $email_number);
	$Sobre = 'Health Record Insertion Requested';
	$Body = '<h1>User Id.: '.$IdU.'</h1>';
	$Body .= '<h2>Origin.: '.$fro.'  ('.$froa.')</h2>';
	
	$confirm_code = $queCodigo;
	
	$message = "To APPROVE the insertion of this report on your Health Record please click the link to PROCEED: \r\n";
    $message.= "http://www.monimed.com/MBconfirmation.php?passkey=$confirm_code&UserId=$IdU&origen=$froa";

    $Body.='<h2>';
    $Body.= $message;
    $Body.='</h2>';

}
else
{
}


$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('HUB@health2.me')
  ->setPassword('mnibjb007');

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

  // And optionally an alternative body
//  ->addPart('<q>Here is the message itself</q>', 'text/html')
  ->addPart($Body, 'text/html')

  // Optionally add any attachments
 // ->attach(Swift_Attachment::fromPath('my-document.pdf'))
  ;


$result = $mailer->send($message);


}  
?>
