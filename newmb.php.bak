<?php
define('INCLUDE_CHECK',1);
require "logger.php";
   
    $mailbox = imap_open("{imap.googlemail.com:993/ssl}INBOX", "newmedibank@googlemail.com", "ardiLLA98");
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

$emails = imap_search($inbox, 'UNSEEN');

    echo "New Messages: ";
	echo count($emails);;
	echo "( ";
	echo $emails;
	echo " )";
	echo "<br>\n"; 	    


/* if emails are returned, cycle through each... */
if($emails) {

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
    foreach($emails as $email_number) {
	
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
    $subject = $mail_headers->subject;
    $date = $mail_headers->date;
    $from = $mail_headers->from;
    
    foreach ($from as $id => $object) {
    	$fromNOM = $object->personal;
    	$fromADD = $object->mailbox . "@" . $object->host;
    	}
    $tiempo = $mail_headers->date;
    $dateId = strtotime($tiempo);
    $Msgno = $mail_headers->Msgno;   
    echo "*********************************************************";
	echo "<br>\n"; 	    
    echo "   Id: ";
    echo $Msgno;
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
	
	$USUOK = 0;
	$NumeroFic = -1;

	$Centro='';
	if (strpos($subject,'HC') !== FALSE) {
		$a = substr($subject,strpos($subject,'HC')+8,1);
			if ($a == '*')
			{
				$Centro = substr($subject,strpos($subject,'HC')+2,6);
				echo "-------";
				echo $Centro;
				echo "-------";
			}
	
	};
	
	$Espe='';
	if (strpos($subject,'ES') !== FALSE) {
		$a = substr($subject,strpos($subject,'ES')+8,1);
			if ($a == '*')
			{
				$Espe = substr($subject,strpos($subject,'ES')+2,6);
				echo "-------";
				echo $Espe;
				echo "-------";
			}
	
	};
	
	$Tipo='0';				
	if (strpos($subject,'TI') !== FALSE) {
		$a = substr($subject,strpos($subject,'TI')+3,1);
			if ($a == '*')
			{
				$Tipo = substr($subject,strpos($subject,'TI')+2,1);
				echo "-------";
				echo $Tipo;
				echo "-------";
			}
	
	};

	// BUSCA LOS IDENTIFICADORES DE USUARIO VÁLIDOS
	if (strpos($subject,'MB') !== FALSE) {
			$a = substr($subject,strpos($subject,'MB')+11,1);
			if ($a == '*')
			{ 
				echo "++ Message seems to be meaningful ++";
				echo "<br>\n"; 	    
				$PassCard = substr($subject,strpos($subject,'MB'),12);
				echo "PassCard = ";
				echo $PassCard;

				// BUSCA AL USUARIO DE ESTE PASSCARD EN LA BASE DE DATOS
					echo "<br>\n"; 	    
					echo "Now looking for user into database...";
					 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
					$tbl_name="usuarios"; // Table name
					
					// Connect to server and select databse.
					mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
					mysql_select_db("$dbname")or die("cannot select DB");
					
					
					// CONTROL DE SIGNIN POR TWITTER (BORRAR MAS ADELANTE) y quitar el require "functions.php" y el define('INCLUDE_CHECK',1)
					//////$mensTw="SIGN IN ".$myusername." ** pass: ".$mypassword;
					//////$a = tuitea ("javierv44","iPHONE APP:    CONTROL de Sign In:".time().$mensTw,"2");
					// CONTROL DE SIGNIN POR TWITTER (BORRAR MAS ADELANTE) y quitar el require "functions.php" y el define('INCLUDE_CHECK',1)
					
					$sql="SELECT * FROM $tbl_name WHERE PassCard='$PassCard'";
					$result=mysql_query($sql);
	
					$row = mysql_fetch_array( $result );
					//echo "Identificador: ".$row['Identif'];
	
					// Mysql_num_row is counting table row
					$count=mysql_num_rows($result);
					$USUOK = 0;
					
					if ($count == 0)
					{
						echo "<br>\n"; 	    
						echo "User ".$PassCard." not found in database.";
						LogMov('Unknown User', $fromname, $fromaddress, '0', 'Identif USER', 'Valid PassCard but USER NOT FOUND', $subject, $email_number);

						
					}else
					{
						echo "<br>\n"; 	    
						echo "User Id.: ";
						echo $row['Identif'];
						echo "<br>\n"; 	    
						
						$IdUsu = $row['Identif'];
						$eMailUsu = $row['email'];
						$USUOK = 1;
						LogMov($IdUsu, $fromname, $fromaddress, '0', 'Identif USER', 'Correct User LogIn', $subject, $email_number);

						// AÑADIR LA IMAGEN AL DIRECTORIO DE IMAGENES
						// AÑADIR EL PIN A LA BASE DE DATOS DE PINES
						
					}
				// *****************************************************
			}else
			{
				echo "Error parsing PassCard id (wrong exit)";
				LogMov('Unknown User', $fromname, $fromaddress, '0', 'Identif USER', 'Wrong exit in PassCard', $subject, $email_number);

			}
			
	}else
	{
				echo "Subject does not contain valid PassCard";
				LogMov('Unknown User', $fromname, $fromaddress, '0', 'Identif USER', 'No PassCard', $subject, $email_number);
		
	}
echo "<br>\n"; 	    
	
					
	// ********************************************
				        /* get information specific to this email */
						$overview = imap_fetch_overview($inbox,$email_number,0);
 					    $message = imap_fetchbody($inbox,$email_number,2);
					    $structure = imap_fetchstructure($inbox,$email_number);
					    imap_setflag_full($inbox, $email_number, "\\Seen \\Flagged");  // Ya está visto (correcto o no), lo pongo a cero.
                        
					    $attachments = array();
					    if ($USUOK == 1){
					    //echo " ***************** PREVIO        ****************";
						//echo "Partes: ";
						//echo count($structure->parts);
					    if(isset($structure->parts) && count($structure->parts)) {		  /// Tiene ATTACHMENT				    
						// Recorre el contenido del email por partes    	
						$Tamano = 0;
							
						for($i = 0; $i < count($structure->parts); $i++) {

							$attachments[$i] = array(
								'is_attachment' => false,
								'filename' => '',
								'name' => '',
								'attachment' => ''
							);
							
							$Tamano = $Tamano + $structure->parts[$i]->bytes;
							
							if($structure->parts[$i]->ifdparameters) {
								foreach($structure->parts[$i]->dparameters as $object) {
									if(strtolower($object->attribute) == 'filename') {
										$attachments[$i]['is_attachment'] = true;
										$attachments[$i]['filename'] = $object->value;
									}
								}
							}
		
							if($structure->parts[$i]->ifparameters) {
								foreach($structure->parts[$i]->parameters as $object) {
									if(strtolower($object->attribute) == 'name') {
										$attachments[$i]['is_attachment'] = true;
										$attachments[$i]['name'] = $object->value;
										$NumeroFic = $i;
							//echo " ***************** PASO ****************";
							//echo $attachments[$i]['name'] ;
							//echo "   i = ";
							//echo $i;
						    //echo "<br>\n"; 	    

									}
								}
							}
		
							if($attachments[$i]['is_attachment']) {
								$attachments[$i]['attachment'] = imap_fetchbody($mailbox, $email_number, $i+1);
								if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
									$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
									echo "Attachment base64_decode FOUND";
									echo "<br>\n"; 
									LogMov($IdUsu, $fromname, $fromaddress, '1', 'Attachment', 'Attachment base64_decode FOUND', $subject, $email_number);

								}
								elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
									$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
									echo "Attachment quoted_printable_decode FOUND";
									echo "<br>\n"; 
									LogMov($IdUsu, $fromname, $fromaddress, '1', 'Attachment', 'Attachment quoted_printable_decode FOUND', $subject, $email_number);
								}
							}else
							{
								echo "No attachment found or invalid attachment.";
								echo "<br>\n"; 
								LogMov($IdUsu, $fromname, $fromaddress, '1', 'Attachment', 'No attachment found or invalid attachment', $subject, $email_number);
						   
							}
						}
							echo "FILE: ".$attachments[$NumeroFic]['name'];
 						    echo "<br>\n"; 
 						    
 						    // COMPROBAR EL ATTACHMENT PARA DECIDIR SI SE AÑADE O NO
 						    echo "SIZE: ";
 						    echo $Tamano;
 						    echo "<br>\n"; 
 						    if ($Tamano > 2000000){
	 						    echo "SIZE EXCEEDS 2 Mb. LIMIT";
	 						    echo "<br>\n"; 
 						    }else
 						    {								
 						// REQUISITOS BASICOS CORRECTOS *********************************************************************    
 						if(count($attachments)!=0){

	 					// GRABACIÓN DEL ATTACHMENT EN EL DIRECTORIO LOCAL PARA LUEGO HACER EL FTP
	 					$new_image_name = 'eML'.$IdUsu.'9999'.md5(date('Ymdgisu')) .'.pdf';

 						$locFile = "Packages/";
					    //file_put_contents($locFile.$attachments[$NumeroFic]['name'], $attachments[$NumeroFic]['attachment']);
						file_put_contents($locFile.$new_image_name, $attachments[$NumeroFic]['attachment']);
						
						//echo "Connection attempt failed";
						//echo "<br>\n"; 
						LogMov($IdUsu, $fromname, $fromaddress, '2', 'PDF Local Save', $new_image_name, $subject, $email_number);
					     
					     // FTP TRANSFER *****************************************************************************************
						$server = "monimed.com"; //target server address or domain name from we wana download file
						$user = "moni8484"; //username on target server
						$pass = "ardiLLA98@"; //password on target server for Ftp
						$source = $locFile.$attachments[$NumeroFic]['name']; /*source file server which we wana download ,single file name refersfile is in Home/root*/
						//echo " ARCHIVO LOCAL : ";
						//echo $source;
						$dest = '/PinEXT/local.pdf';//download file and store as local.tar
						$mode = "FTP_BINARY";
						//$local_file = './Packages/windows.png';
						
//						$local_file = './Packages/'.$attachments[$NumeroFic]['name'];
//						$dest_file = $attachments[$NumeroFic]['name'] ;
						$local_file = './Packages/'.$new_image_name;
						$dest_file = $new_image_name ;

						$connection = ftp_connect($server, $port = 21);
						$login = ftp_login($connection, $user, $pass);
						ftp_pasv($connection, true);

						if (!$connection || !$login) { 
						
						die('Connection attempt failed!'); 
						//echo "Connection attempt failed";
						echo "<br>\n"; 
						LogMov($IdUsu, $fromname, $fromaddress, '3', 'PDF Upload', 'FTP Connection attempt failed', $subject, $email_number);
	
							
						}

						if (ftp_chdir($connection, "PinEXT")) {
						    //echo "Current directory is now: " . ftp_pwd($connection) . "\n";
						    //$upload = ftp_put($connection, $dest, $source, FTP_BINARY);
						    $upload = ftp_put($connection, $dest_file, $local_file, FTP_BINARY);
						    if (!$upload) { echo 'FTP upload failed!'; }else
						    {
							    echo "File uploaded successfully";
							    echo "<br>\n"; 
							    echo "<br>\n"; 
							    LogMov($IdUsu, $fromname, $fromaddress, '3', 'PDF Upload', 'FTP file UPLOADED SUCCESSFULLY', $subject, $email_number);
	 
	    
							    // Inserta el PIN en la Base de Datos
							    $confirm_code = md5(uniqid(rand()));
							    //$Isql="INSERT INTO lifepin SET FechaInput=NOW(),Fecha=NOW(), EvRuPunt=2, Evento=99, IdUsu='$IdUsu', CENTRO='$Centro', EspecialidadT='$Espe',  CANAL=E,   RawImage='$dest_file', NumImages=1, confirmcode='$confirm_code', approved = '0' ";
							    $Isql="INSERT INTO lifepin SET FechaInput=NOW(),Fecha=NOW(), EvRuPunt=2, Evento=99, IdUsu='$IdUsu', CENTRO='$Centro', EspecialidadT='$Espe',  CANAL='E',  Tipo = $Tipo, RawImage='$dest_file', NumImages=1, confirmcode='$confirm_code', approved = '0' ";
							    //echo $Isql;
							    $q = mysql_query($Isql);
							    $IdPin = mysql_insert_id();
							    // if successfully insert data into database, displays message "Successful". 
							    if($q){
								    	echo "Successful: Database Updated";
								    	echo "<br>\n"; 	
								    	EnviaMail($eMailUsu,0, $IdUsu, $fromname, $fromaddress, $IdPin, $confirm_code);
								    	LogMov($IdUsu, $fromname, $fromaddress, '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $subject, $email_number);

								    	//imap_clearflag_full($inbox, $email_number, "\\Seen \\Flagged");
								    	$headers = "From: newmedibank@gmail.com\r\n"."Reply-To: newmedibank@gmail.com\r\n";
								    	$cc = null;
								    	$bcc = null;
								    	$return_path = "newmedibank@gmail.com";

								    	//imap_mail("lapspart@me.com", "Added Report via email", "Esto es una prueba para comprobar que funciona el tema", $headers, $cc, $bcc, $return_path);

								    	}
								    	else{
									    echo "Error Updating Database ********";
								    	echo "<br>\n"; 	 
								    	LogMov($IdUsu, $fromname, $fromaddress, '4', 'dB INSERTION', 'Error Updating Database ********' , $subject, $email_number);

								    	}
		    						}

    					} else { 
						    echo "Couldn't change directory\n";
						    echo "<br>\n"; 
						    LogMov($IdUsu, $fromname, $fromaddress, '3', 'PDF Upload', 'FTP directory change failed', $subject, $email_number);

						    echo "<br>\n"; 	    
    					}
						ftp_close($connection); 
						// ******************************************************************************************************
											        }  // CIERRE DEL IF COUNT ATTACHMENTS 
 						    }  // CIERRE DEL IF TAMANO > 2000
 						// END REQUISITOS BASICOS CORRECTOS *********************************************************************    

					       // imap_clearflag_full($inbox, $email_number, "\\Seen \\Flagged");

					      }		// CIERRE DEL IF ISSET() AND PARTS	(158)			
					      else
					      {
						      	echo "No attachment found";
								echo "<br>\n"; 
								LogMov($IdUsu, $fromname, $fromaddress, '1', 'Attachment', 'No attachment found', $subject, $email_number);

					      }
					      
					      }     // CIERRE DEL IF USUOK = 1          (157)

   
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
  ->setUsername('newmedibank@gmail.com')
  ->setPassword('ardiLLA98');

$mailer = Swift_Mailer::newInstance($transporter);


// Create the message
$message = Swift_Message::newInstance()

  // Give the message a subject
  ->setSubject($Sobre)

  // Set the From address with an associative array
  ->setFrom(array('newMediBANK@gmail.com' => 'newMediBANK'))

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