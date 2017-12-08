<?php
    $mailbox = imap_open("{imap.googlemail.com:993/ssl}INBOX", "newmedibank@googlemail.com", "ardilla98");
    $mail = imap_search($mailbox, "ALL");
    $mail_headers = imap_headerinfo($mailbox, $mail[0]);
    $subject = $mail_headers->subject;
    echo $subject;
    $from = $mail_headers->fromaddress;
    echo "   From: ";
    echo $from;
    imap_setflag_full($mailbox, $mail[0], "\\Seen \\Flagged");
    $n= imap_num_msg($mailbox);
    echo " - Numero de mensajes: ";
	echo $n;
	    
 
 /* try to connect */
$inbox = $mailbox;

/* grab emails */
$emails = imap_search($inbox, 'FROM "jvinals@gmail.com"');

echo " - EMails operativos: ";
echo count($emails);

/* if emails are returned, cycle through each... */
if($emails) {

  /* begin output var */
  $output = '';

  /* put the newest emails on top */
  rsort($emails);




    foreach($emails as $email_number) {

	echo "<br>\n"; 	    
	echo "<br>\n"; 	    
	echo "<br>\n"; 	    
echo "PASO ";
echo $email_number;
echo " **** ";
	echo "<br>\n"; 	    

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
		
		echo " * ".$i." * ";
		echo " IFDP:";
		echo $structure->parts[$i]->ifdparameters;
		echo "--";
		echo " IFP:";
		echo $structure->parts[$i]->ifparameters;
		echo "--";
		
		if($structure->parts[$i]->ifdparameters) {
			foreach($structure->parts[$i]->dparameters as $object) {
				if(strtolower($object->attribute) == 'filename') {
					$attachments[$i]['is_attachment'] = true;
					$attachments[$i]['filename'] = $object->value;
echo "ES FILENAME";	echo $attachments[$i]['filename'];echo "<br>\n"; 	    
				}
			}
		}
		
		if($structure->parts[$i]->ifparameters) {
			foreach($structure->parts[$i]->parameters as $object) {
				if(strtolower($object->attribute) == 'name') {
					$attachments[$i]['is_attachment'] = true;
					$attachments[$i]['name'] = $object->value;
echo "ES NAME";	echo $attachments[$i]['name'];echo "<br>\n"; 	    
				}
			}
		}
		
		if($attachments[$i]['is_attachment']) {
			$attachments[$i]['attachment'] = imap_fetchbody($mailbox, $email_number, $i+1);
			if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
				$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
echo "ES ATTACHMENT BASE 64: ";	echo "<br>\n"; 	    
echo $attachments[$i]['name'];
$locFile = "Packages/";
file_put_contents($locFile.$attachments[$i]['name'], $attachments[$i]['attachment']);
			}
			elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
				$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
echo "ES ATTACHMENT PRINTABLE: ";	 echo "<br>\n"; 	    
echo $attachments[$i]['name'];
$locFile = "Packages/";
file_put_contents($locFile.$attachments[$i]['name'], $attachments[$i]['attachment']);
			}
		}
	}

//echo $attachments[0]['name'];
    if(count($attachments)!=0){


//        foreach($attachments as $at){

 //           if($at[is_attachment]==1){
//$locFile = "Packages/";
//                file_put_contents($locFile.$attachments[0]['name'], $attachments[0]['attachment']);

 //               }
//            }

        }



}
   
}

} 

/* close the connection */
 
 
    imap_close($mailbox);
 
 
 
?>