<?php
    $mailbox = imap_open("{imap.googlemail.com:993/ssl}INBOX", "newmedibank@googlemail.com", "ardilla98");
    $mail = imap_search($mailbox, "ALL");
    $mail_headers = imap_headerinfo($mailbox, $mail[0]);
    $subject = $mail_headers->subject;
    //echo $subject;
    $from = $mail_headers->fromaddress;
    //echo "   From: ";
    //echo $from;
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
    echo " Subject: ";
	echo $subject;
	echo "<br>\n"; 	    
	echo " Date: ";
	echo $date;
	echo "<br>\n"; 	    
	
	// BUSCA LOS IDENTIFICADORES DE USUARIO VÁLIDOS
	if (strpos($subject,'MB') !== FALSE) {
			$a = substr($subject,strpos($subject,'MB')+15,1);
			if ($a == 'M')
			{ 
				echo "++ Message seems to be meaningful ++";
				echo "<br>\n"; 	    
				$PassCard = substr($subject,strpos($subject,'MB'),16);
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
					
					if ($count == 0)
					{
						echo "<br>\n"; 	    
						echo "User ".$PassCard." not found in database.";
						
					}else
					{
						echo "<br>\n"; 	    
						echo "User Id.: ";
						echo $row['Identif'];
						// AÑADIR LA IMAGEN AL DIRECTORIO DE IMAGENES
						// AÑADIR EL PIN A LA BASE DE DATOS DE PINES
						
					}
				// *****************************************************
			}else
			{
				echo "Not a valid PassCard (wrong exit)";
			}
			
	}else
	{
				echo "Subject does not contain valid PassCard";
		
	}
echo "<br>\n"; 	    
					
	// ********************************************
	
				        /* get information specific to this email */
						$overview = imap_fetch_overview($inbox,$email_number,0);
 					    $message = imap_fetchbody($inbox,$email_number,2);
					    $structure = imap_fetchstructure($inbox,$email_number);

					    $attachments = array();
					    if(isset($structure->parts) && count($structure->parts)) {

						for($i = 0; $i < count($structure->parts); $i++) {

							$attachments[$i] = array(
								'is_attachment' => false,
								'filename' => '',
								'name' => '',
								'attachment' => ''
							);
		
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
									}
								}
							}
		
							if($attachments[$i]['is_attachment']) {
								$attachments[$i]['attachment'] = imap_fetchbody($mailbox, $email_number, $i+1);
								if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
									$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
								}
								elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
									$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
								}
							}
						}

							echo "FILE: ".$attachments[0]['name'];
 						    echo "<br>\n"; 	    
 						 
 						 if(count($attachments)!=0){

// GRABACIÓN DEL ATTACHMENT EN EL DIRECTORIO LOCAL PARA LUEGO HACER EL FTP
 					$locFile = "Packages/";
					                file_put_contents($locFile.$attachments[0]['name'], $attachments[0]['attachment']);

// FTP TRANSFER *****************************************************************************************
$server = "monimed.com"; //target server address or domain name from we wana download file
$user = "moni8484"; //username on target server
$pass = "ardiLLA98@"; //password on target server for Ftp
$source = $locFile.$attachments[0]['name']; /*source file on the server which we wana download ,single file name refers that file is in Home/root*/
//echo " ARCHIVO LOCAL : ";
//echo $source;
$dest = '/PinEXT/local.pdf';//download file and store as local.tar
$mode = "FTP_BINARY";
//$local_file = './Packages/windows.png';
$local_file = './Packages/'.$attachments[0]['name'];
$dest_file = $attachments[0]['name'] ;

$connection = ftp_connect($server, $port = 21);
$login = ftp_login($connection, $user, $pass);
ftp_pasv($connection, true);

if (!$connection || !$login) { die('Connection attempt failed!'); }

if (ftp_chdir($connection, "PinEXT")) {
    //echo "Current directory is now: " . ftp_pwd($connection) . "\n";
    //$upload = ftp_put($connection, $dest, $source, FTP_BINARY);
    $upload = ftp_put($connection, $dest_file, $local_file, FTP_BINARY);
    if (!$upload) { echo 'FTP upload failed!'; }else
    {
	    echo "File uploaded succesfuly";
	    echo "<br>\n"; 	    

    }

} else { 
    echo "Couldn't change directory\n";
    echo "<br>\n"; 	    
}
ftp_close($connection); 
// ******************************************************************************************************
			//               }
 					//            }

					        }

					        imap_clearflag_full($mailbox, $email_number, "\\Seen \\Flagged");

					      }						


   
}

} 

/* close the connection */
 
 
    imap_close($mailbox);
 
?>