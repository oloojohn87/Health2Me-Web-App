<?php

set_time_limit(120);
define('INCLUDE_CHECK',1);
require "logger.php";




	 require("environment_detailForLogin.php");
	 $dbhost = $env_var_db['dbhost'];
	 $dbname = $env_var_db['dbname'];
	 $dbuser = $env_var_db['dbuser'];
	 $dbpass = $env_var_db['dbpass'];

	$tbl_name="usuarios"; // Table name
					
	// Connect to server and select databse.
	// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

echo "Ver 1.1";
echo "<br>\n"; 	
// Grabación de un registro con fecha en la dB para comprobar la VITALIDAD
$IsqlX=$con->prepare("UPDATE vitalidad SET Fecha=NOW(), Programa = 'CloudChannelPARSEBLOCKS.php' WHERE IdProg = 4");
$qX = $IsqlX->execute();

// Grabación de un registro con fecha en la dB para comprobar la VITALIDAD

// EnviaMail2('lapspart@me.com', 'Email de prueba EnviaMail2', 0, 9999, 'test@test.com', 'Cuenta de Test',8888, 9);  		 // PLANTILLA DE FUNCIÓN PARA ENVIAR E-MAIL CON SWIFTMAIL.
// LogBLOCK (1111, 'Contenido del LOG aqui' ,  'jvinals@gmail.com', 19660706, 19660808, 'javier&vinals', 999999, 1);    	 // PLANTILLA DE FUNCIÓN DE REGISTRO DEL LOG.


//BLOCKSLIFEPIN $q = mysql_query("SELECT * FROM blocks WHERE NeedACTION=1 ORDER BY id DESC ");
$q = $con->prepare("SELECT * FROM lifepin WHERE NeedACTION=1 and (CANAL=7 or CANAL=5) ORDER BY IdPin DESC ");
$q->execute();

$count=$q->rowCount();
echo "<br>\n"; 	
echo "N = ";
echo $count;
echo "<br>\n"; 	
echo "<br>\n"; 	
echo "<br>\n"; 	    
echo date(DATE_RFC822);
echo "<br>\n"; 








while($row = $q->fetch(PDO::FETCH_ASSOC))     										// Va pasando por todos los BLOCKS que están marcados con NeedACTION = 1 (Necesitan que se haga algo)
{

$ID = $row['IdPin'];
$EmailDOC = $row['IdMEDEmail'];
$IdUsFIXED = $row['IdUsFIXED']; 
$FechaEmail = $row['FechaEmail']; 

echo "Id = ";
echo $ID;
echo "<br>\n"; 	
echo "Email = ";
echo $EmailDOC;
echo "<br>\n"; 	
echo "<br>\n"; 	



// Se comprueba: [it checks]
//   1: Que esté bien el ORIGEN
//   2: Que esté bien el DESTINATARIO (PACIENTE/USER)
//   3: Que tenga un fichero correcto (PDF,JPG, VIDEO) DESCARGADO
//   4: Que esté ETIQUETADO y CLASIFICADO
// 1: That is right the ORIGIN
// 2: That is either the consignee (PATIENT / USER)
// 3: Have a correct file (PDF, JPG, VIDEO) DOWNLOADED
// 4: What is LABELLING and CLASIFICADOSe checks:

// COMPROBACIÓN (1)  ORIGEN **********************************************************************************************************************
$ComprobOrigen=-1;
$Origen = $row['IdMEDEmail'];
$q2 = $con->prepare("SELECT * FROM doctors WHERE IdMEDEmail = ? ");
$q2->bindValue(1, $Origen, PDO::PARAM_STR);
$q2->execute();

$count2 = $q2->rowCount();

$row2=$q2->fetch(PDO::FETCH_ASSOC);
$NameDoctor = $row2['Name'];
$SurnameDoctor = $row2['Surname'];
$IdMed = $row2['id'];

//-------------------------------------------------------------------------------------------------------------------------------------------
//Logger Information added by Ankit
$IdPin=$ID;
$content = "Report Uploaded";
$VIEWIdUser=0;   //left 0 because at this point we do not know about the user
$VIEWIdMed=$IdMed;
$ip="via dropbox";
$MEDIO=0;
LogBLOCKAMP ($IdPin, $content, $VIEWIdUser, $VIEWIdMed, $ip, $MEDIO);	
//-------------------------------------------------------------------------------------------------------------------------


if ($count2 > 1){
	$ComprobOrigen=-1;
	die ('MORE THAN 1 ACCOUNT FOR THIS EMAIL ADDRESS REGISTERED .... STOPPING HERE. PLEASE CHECK AND AUDIT TRACK.');
}

if ($count2 == 0){   																				// ORIGEN 1:A // EL MÉDICO NO ESTÁ EN LA BASE DE DATOS: Procedimiento de envío de link para que se de de alta.
$ComprobOrigen=0;
/*$message = 'Dear ';
$message.= $EmailDOC.' : ';
$message.= ' We have received a clinical information package at MediBANK from you regarding patient ';
$message.= '<span style="color: green;">';
$message.= $IdUsFIXED;
$message.= '<span style="color: black;">';
$message.= ' at ';
$message.= '<span style="color: green;">';
$message.= $FechaEmail;
$message.= '<span style="color: black;">';
$message.= '. In order to activate this information for you and for your patient, you need to sign up at MediBANK. Please follow this link to create your account: ';
$confirm_code=md5(uniqid(rand()));
$message.='http:/hp?passkey=$confirm_code';
*/
//#cloudchannel
//EnviaMail2($EmailDOC, $IdUsFIXED, 'MediBANK clinical validation system','Please sign up in MediBANK to access and validate your clinical record information.', $message, 0, 9999, 'Grace@health2.me', 'Inmers',$ID, 9);  		 	// ENVIA E-MAIL PARA QUE EL MEDICOI SE DE DE ALTA.
LogBLOCK ($ID, 'Doctor not active in database. Sent email with instructions to sign up', $EmailDOC, '', $IdUsFIXED, '', '', 1);           	// Registra en BTRACKER todo esto
//BLOCKSLIFEPIN $q3 = mysql_query("UPDATE blocks SET NeedACTION=0, ValidationStatus=2, NextAction='Doctor not active in database. Sent email with instructions to sign up.'  WHERE id='$ID'");   // PONE OTRA VEZ EL FLAG NeedACTION a CERO
$q3 = $con->prepare("UPDATE lifepin SET NeedACTION=0, ValidationStatus=2, NextAction='Doctor not active in database. Sent email with instructions to sign up.'  WHERE IdPin=?");   // PONE OTRA VEZ EL FLAG NeedACTION a CERO
$q3->bindValue(1, $ID, PDO::PARAM_INT);
$q3->execute();


echo '---:'.'Doctor not active in database. Sent email with instructions to sign up.';
echo "<br>\n"; 			
}
else
{																									// ORIGEN 1:B // EL MÉDICO ESTÁ EN LA BASE DE DATOS: Solo se añade el registro en BTRACKER
$ComprobOrigen=1;
LogBLOCK ($ID, 'Doctor acknowledged in MB Database.', $EmailDOC, '', $IdUsFIXED, '', '', 1);           // Registra en BTRACKER todo esto
//BLOCKSLIFEPIN $q3 = mysql_query("UPDATE blocks SET NeedACTION=0, ValidationStatus=0, NextAction='Doctor acknowledged in MB Database.' WHERE id='$ID'");   // PONE OTRA VEZ EL FLAG NeedACTION a CERO
$q3 = $con->prepare("UPDATE lifepin SET NeedACTION=0, IdMed=?, CreatorType=1, IdCreator=?, ValidationStatus=0, NextAction='Doctor acknowledged in MB Database.' WHERE IdPin=?");   // PONE OTRA VEZ EL FLAG NeedACTION a CERO
$q3->bindValue(1, $IdMed, PDO::PARAM_INT);
$q3->bindValue(2, $IdMed, PDO::PARAM_INT);
$q3->bindValue(3, $ID, PDO::PARAM_INT);
$q3->execute();

echo '---:'.'Doctor acknowledged in MB Database.';
echo "<br>\n"; 	


}

if ($ComprobOrigen<1) continue; //goto salida;    // -------------------------------> Importante: SI HAY DEFECTOS EN (1) SALTA DIRECTAMENTE AL FINAL DEL BUCLE

// COMPROBACIÓN (2)  DESTINATARIO (PACIENTE/USUARIO)   *******************************************************************************************
$ComprobDestino=-1;
$Destino = $row['IdUsFIXED'];
$DestinoNAME = $row['IdUsFIXEDNAME'];
$q2 = $con->prepare("SELECT * FROM usuarios WHERE IdUsFIXED = ? AND IdUsFIXEDNAME=? ");    
$q2->bindValue(1, $Destino, PDO::PARAM_INT);
$q2->bindValue(2, $DestinoNAME, PDO::PARAM_STR);
$q2->execute();

$count2 = $q2->rowCount();
$row2=$q2->fetch(PDO::FETCH_ASSOC);

$NameUsu = $row2['Name'];
$SurnameUsu = $row2['Surname'];


echo "<br>\n"; 	
$cadSQL ="SELECT * FROM usuarios WHERE IdUsFIXED = '$Destino' AND IdUsFIXEDNAME='$DestinoNAME' ";
echo $cadSQL;
echo "<br>\n"; 	

if ($count2 > 1){
	$ComprobDestino=-1;
	die ('MORE THAN 1 ACCOUNT FOR THIS USER .... TRIGGER CHECK.');
}

if ($count2==0){
$q3 = $con->prepare("SELECT * FROM usuarios WHERE IdUsFIXED = ?");    
$q3->bindValue(1, $Destino, PDO::PARAM_INT);
$q3->execute();

$count3 = $q3->rowCount();
if ($count3==0){
	$ComprobDestino = 0; 										// El Usuario NO ESTÁ EN LA BASE DE DATOS, NI SIQUIERA CON EL "FIXED"	
	LogBLOCK ($ID, 'Patient not present in MB Database. No previous records or wrong id.', $EmailDOC, '', $Destino, $DestinoNAME, '', 1);           // Registra en BTRACKER todo esto
//BLOCKSLIFEPIN 	$q3 = mysql_query("UPDATE blocks SET NeedACTION=0, ValidationStatus=2, NextAction='Patient not present in MB Database. No previous records or wrong id.'  WHERE id='$ID'");   // PONE OTRA VEZ EL FLAG NeedACTION a CERO
	$q3 = $con->prepare("UPDATE lifepin SET NeedACTION=0, ValidationStatus=2, NextAction='Patient not present in MB Database. No previous records or wrong id.'  WHERE IdPin=?");   // PONE OTRA VEZ EL FLAG NeedACTION a CERO
	$q3->bindValue(1, $ID, PDO::PARAM_INT);
	$q3->execute();
	
	echo '---:'.'Patient not present in MB Database. No previous records or wrong id.';
	echo "<br>\n"; 
}else
{
	$ComprobDestino = 1; 										// El Usuario ESTÁ EN LA BASE DE DATOS, PERO NO SE HA VALIDADO NUNCA (O LA CLAVE FIXEDNAME ESTÁ MAL)
	LogBLOCK ($ID, 'Patient FOUND in MB Database but no validation yet.', $EmailDOC, '', $Destino, $DestinoNAME, '', 1);           									// Registra en BTRACKER todo esto
//BLOCKSLIFEPIN	$q3 = mysql_query("UPDATE blocks SET NeedACTION=0, ValidationStatus=2, NextAction='Patient FOUND in MB Database but no validation yet.'  WHERE id='$ID'");   	// PONE OTRA VEZ EL FLAG NeedACTION a CERO
	$q3 = $con->prepare("UPDATE lifepin SET NeedACTION=0, ValidationStatus=3, NextAction='Patient FOUND in MB Database but no validation yet.'  WHERE IdPin=?");   	// PONE OTRA VEZ EL FLAG NeedACTION a CERO
	$q3->bindValue(1, $ID, PDO::PARAM_INT);
	$q3->execute();
	
	echo '---:'.'Patient FOUND in MB Database but no validation yet.';
	echo "<br>\n"; 
																// 		Situación A: No está el paciente creado
																// 		Situación B: La clave está mal o incompleta
		
}	
}
else
{
	$ComprobDestino = 2; 										// El Usuario ESTÁ EN LA BASE DE DATOS.- Enviar email de confirmación al paciente.
	$queIdUsu = $row2['Identif'];
	
	$PatientName = $row2['Name'];
	$PatientSurname = $row2['Surname'];
	$PatientEmail = $row2['email'];
	$PatientIdUsFIXED = $row2['IdUsFIXED'];
	$PatientIdUsFIXEDNAME = $row2['IdUsFIXEDNAME'];
	
	LogBLOCK ($ID, 'Patient Cleared: FIXED and FIXEDNAME found in MB Database.', $EmailDOC, '', $Destino, $DestinoNAME, '', 1);           									// Registra en BTRACKER todo esto
	//BLOCKSLIFEPIN $q3 = mysql_query("UPDATE blocks SET NeedACTION=0, ValidationStatus=0, NextAction='Patient Cleared: FIXED and FIXEDNAME found in MB Database.'  WHERE id='$ID'");   	// PONE OTRA VEZ EL FLAG NeedACTION a CERO
	$cadenaSql = $con->prepare("UPDATE lifepin SET NeedACTION=0, IdUsu = ?, ValidationStatus=4, NextAction='Patient Cleared: FIXED and FIXEDNAME found in MB Database.'  WHERE IdPin=?");
	$cadenaSql->bindValue(1, $queIdUsu, PDO::PARAM_INT);
	$cadenaSql->bindValue(2, $ID, PDO::PARAM_INT);
	$q3 = $cadenaSql->execute();
	$cadenaSql2 = "UPDATE lifepin SET NeedACTION=0, IdUsu = '$queIdUsu', ValidationStatus=4, NextAction='Patient Cleared: FIXED and FIXEDNAME found in MB Database.'  WHERE IdPin='$ID'";

	echo "<br>\n"; 
	echo "ACTUALIZACION DE INFORMACION (PATIENT CLEARED): ";
	echo "<br>\n"; 
	echo $cadenaSql2;
	echo "<br>\n"; 
	echo '---:'.'Patient Cleared: FIXED and FIXEDNAME found in MB Database.';
	echo "<br>\n"; 
	echo "REGISTROS CAMBIADOS: ".$cadenaSql->rowCount();
	echo "<br>\n"; 	

$message = 'Dear Dr. ';
$message.= $NameDoctor.' '.$SurnameDoctor.' ';
$message.= '( '.$EmailDOC.' ) : ';
$message.= ' We have received correctly your clinical information package regarding patient ';
$message.= '<span style="color: green;">';
$message.= $NameUsu.' '.$SurnameUsu;
$message.= '<span style="color: black;">';
$message.= ' ( ';
$message.= '<span style="color: green;">';
$message.= $IdUsFIXED;
$message.= '<span style="color: black;">';
$message.= ' ) at ';
$message.= '<span style="color: green;">';
$message.= $FechaEmail;
$message.= '<span style="color: black;">';
$message.= '. The information has been updated and is available at eMapLife. ';

//#CloudChannel
//EnviaMail2($EmailDOC, $IdUsFIXED,'eMapLife clinical validation system','Patient information added.', $message, 0, 9999, 'Grace@health2.me', 'Inmers',$ID, 9);  		 	// ENVIA E-MAIL PARA  EL MEDICO


$message = 'Dear ';
//$message.= $NameDoctor.' '.$SurnameDoctor.' ';
$message.= $PatientName.': ';
//$message.= '( '.$PatientIdUsFIXED.' ['.$PatientIdUsFIXEDNAME.']'.' ) : ';
$message.= ' Dr. '.$NameDoctor.' '.$SurnameDoctor.' added a new report to your Medical Record.';
$message.= '(Ids.: '.$PatientIdUsFIXED.' ['.$PatientIdUsFIXEDNAME.']'.' ) : ';
$message.= ' Date: ';
$message.= '<span style="color: green;">';
$message.= $FechaEmail;
$message.= '<span style="color: black;">';
$message.= '. The information has been updated and is available at eMapLife. ';

//#CloudChannel
//EnviaMail2($PatientEmail, $IdUsFIXED,'eMapLife clinical validation system','Patient information added.', $message, 0, 9999, 'Grace@health2.me', 'Inmers',$ID, 9);  		 	// ENVIA E-MAIL PARA EL PACIENTE


}




###setting the need action to 5 which means it has to update the patientID and Date######

$inputfile = $row['RawImage'];

if(!(empty($inputfile)&&empty($ID))){
$extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
if($extensionR=='wav'){
$cadenaSql112 = "INSERT INTO pending_audio SET Idpin=?,Audiofilename=?";
$cadenaSql11 = $con->prepare("INSERT INTO pending_audio SET Idpin=?,Audiofilename=?");
$cadenaSql11->bindValue(1, $ID, PDO::PARAM_INT);
$cadenaSql11->bindValue(2, $inputfile, PDO::PARAM_STR);

echo "<br>Insert data into pending audio<br>";
}else{
$cadenaSql112 = "INSERT INTO pending SET idpin=?,rawimage=?";
$cadenaSql11 = $con->prepare("INSERT INTO pending SET idpin=?,rawimage=?");
$cadenaSql11->bindValue(1, $ID, PDO::PARAM_INT);
$cadenaSql11->bindValue(2, $inputfile, PDO::PARAM_STR);

}

$q3 = $cadenaSql11->execute();
}else{
	$q3=FALSE;
}
if(!$q3){
	echo "Error Updating Database for Pending table";
	echo $cadenaSql112;
	echo "<br>";
}else {
	echo "Successfully updated Database for Pending table";
	echo "<br>";
}  // CIERRE DEL BUCLE DE RECORRIDO DE TODOS LOS REGISTROS QUE TIENEN FLAG NeedACTION = 1

salida:    // Se utilizan saltos directos desde las comprobaciones arriba para evitar duplicidades

}
####DATA EXTRACTION FROM TESERACT BEGIN#########
 /*                                                                    



	// Dates
//----------------------------------------------------------------------------------------
	$link0 = mysql_connect("$host", "$username", "$password")or die("cannot connect");

	mysql_select_db("$db_name")or die("cannot select DB");

	//echo "Connection Established";



	$suggesteddates = shell_exec('Lexer');

	$suggesteddates = chop($suggesteddates," ,");

	echo "<br>".$suggesteddates;

	$query = "update lifepin set suggesteddate='".$suggesteddates."' where idpin = ".$idp ; 

	//echo $query;

	mysql_query($query);

	echo "Updated Dates in Table";

	mysql_close($link0);

	//-----------------------------------------------------

	

	

	

	
	$link1 = mysql_connect("$host", "$username", "$password")or die("cannot connect");

	mysql_select_db("$db_name")or die("cannot select DB");

	//$query = "select creator_id,creator_type from test_file where fname='".$file_name."'";

	$query = "select creatortype,idcreator from lifepin where idpin = ".$idp;

	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

	mysql_close($link1);

	$creator_id = $row['idcreator'];

	$creator_type=$row['creatortype'];

	

	

	//$creator_id = $row['creator_id'];

	//$creator_type=$row['creator_type'];

	echo "<br><br>Suggested Names:<br>";

	if($creator_type == 1)

	{

		//echo "<br>Creator is a Doctor";

		

		

		//---------------------------Tokenize------------------------

		$filename = "c:/xampp/htdocs/ExtractedData.txt";

		$handle = fopen($filename, "r");

		$text = fread($handle, filesize($filename));

		fclose($handle);

		

		//echo "<br><br>Extracted Text : ".$text;

		$text = removeCommonWords($text);

		$link2 = mysql_connect("$host", "$username", "$password")or die("cannot connect");

		mysql_select_db("$db_name")or die("cannot select DB");

		$query = "update lifepin set textorel='".$text."' where idpin = ".$idp;

		mysql_query($query);
		
		mysql_close($link2);

		//echo "<br><br>Reduced Text : ".$text;

		$tokens = array();

		$token = strtok($text, " \n\t");

		

		while ($token != false)

		{

			array_push($tokens,$token);

			$token = strtok(" \n\t");

			if($token == '0')

				$token = strtok(" \n\t");

			//echo "<br>".$token;

		} 

		$link3 = mysql_connect("$host", "$username", "$password")or die("cannot connect");

		mysql_select_db("$db_name")or die("cannot select DB");

		
		$name1 = array();

		$no_tokens = count($tokens);

		//echo "<br>".$no_tokens;

		echo "<br><br><br>";

		//---------------------------Process each Token----------------

		for($i=0;$i<$no_tokens;$i++)

		{

			if(!strpos($tokens[$i],"'") or !strpos($tokens[$i],'"'))

			{

				/*$query = "select name,surname from test_patient p, test_doctor_patient dp ";

				$query=$query."where soundex(p.name) like soundex('".$tokens[$i]."') and p.patient_id = dp.patient_id and dp.doc_id =".$creator_id;

				$query=$query." UNION select name,surname from test_patient p, test_doctor_patient dp ";

				$query=$query." where levenshtein(p.name,'".$tokens[$i]."') in (0,1) and p.patient_id = dp.patient_id and dp.doc_id = ".$creator_id;*/

				
/*
				$q1 = "select name,surname from usuarios where identif in (select distinct(u.idus) from doctorslinkusers u,lifepin l where l.idcreator = u.idmed and l.idcreator =".$creator_id." ) ";

				$query = $q1." and soundex(name) like soundex('".$tokens[$i]."') UNION ".$q1. "and levenshtein(name,'".$tokens[$i]."') in (0,1)" ;

						

				//echo "<br><br>".$query;		

				

	

				$result = mysql_query($query);

				$count=mysql_num_rows($result);

				if($count>0)

				{

					while ($row = mysql_fetch_array($result))

					{

						$words_in_name = $row['name']." ".$row['surname'];

						//echo "<br>".$words_in_name;

						$no_words = find_number_of_tokens($words_in_name);

						$newstring = '';

						for($j=0;$j<$no_words;$j++)

						{

							if( $no_tokens > ($i+$j))

							{

								$newstring = $newstring." ".$tokens[$i+$j]; 

							}

							else

							{

								break;

							}

						}

					    //echo "---".$newstring;

						

						/*$query = "select p.patient_id as pid,concat(name,' ',surname) as suggestedName from test_patient p, test_doctor_patient dp where soundex(concat(p.name,' ',p.surname)) like soundex('".$newstring."') and p.patient_id = dp.patient_id and dp.doc_id =".$creator_id;

						$query = $query." UNION select p.patient_id as pid,concat(name,' ',surname) as suggestedName from test_patient p, test_doctor_patient dp where levenshtein(concat(p.name,' ',p.surname),'".$newstring."') in(0,1) and p.patient_id = dp.patient_id and dp.doc_id =".$creator_id;

							*/

						
/*
						$q2 = "select identif as pid,concat(name,' ',surname) as suggestedName from usuarios where identif in (select distinct(u.idus) from doctorslinkusers u,lifepin l where l.idcreator = u.idmed and l.idcreator =".$creator_id." ) ";

						$query = $q2. " and soundex(concat(name,' ',surname)) like soundex('".$newstring."') UNION ".$q2. "and levenshtein(concat(name,' ',surname),'".$newstring."') in (0,1)" ;

								

						

						//echo "<br>".$query;

						$result1 = mysql_query($query);

						while($row1 = mysql_fetch_array($result1))

						{

							//echo "<br>".$row1['pid']."  --->  ".$row1['suggestedName'];

							$name1[$row1['pid']] = $row1['suggestedName'];

						}

					

					

					}

					

				}

			}

		}
		if(!empty($name1))
		{
			$suggestedid ='';

			foreach($name1 as $x=>$x_value)

			{

				$suggestedid = $suggestedid  .$x . " ,";

				echo "ID=" . $x . ", Name=" . $x_value;

				echo "<br>";

			}

			$suggestedid = chop($suggestedid,",");
		}
		else
		{
			$suggestedid = "No Suggestions !!!";
		}
		mysql_close($link3);
		
	}

	else

	{

		//echo "<br>Creator is a Patient";
		if($creator_id)
			$suggestedid = $creator_id;
		else
			$suggestedid = "No Suggestions !!!";
		
	}
	
		
	
	
	
	$link4 = mysql_connect("$host", "$username", "$password")or die("cannot connect");

	mysql_select_db("$db_name")or die("cannot select DB");

	$query = "update lifepin set suggestedid='".$suggestedid."' where idpin = ".$idp; 

	mysql_query($query);

	echo "Updated ID in table";

	mysql_close($link4);
}



function find_number_of_tokens($text)
{

		$tokens = array();

		$tok = strtok($text, " ");

		while ($tok != false)

		{

			array_push($tokens,$tok);

			$tok = strtok(" ");

		} 

		return count($tokens);

}

	

	

function removeCommonWords($input)
{

		$commonWords = array('a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','b','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','c','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','d','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','e','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','f','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','g','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','h','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','j','just','k','keep','keeps','kept','know','known','knows','l','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','m','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','n','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','o','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','p','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','q','que','quite','qv','r','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','s','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','t','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','w','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','x','y','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','z','zero',',');

	/*	return preg_replace('/\b('.implode('|',$commonWords).')\b/','',$input);

}

/*

					/*
// Connect to server and select databse.
$link = mysql_connect("$host", "$username", "$password")or die("cannot connect");
mysql_select_db("$db_name")or die("cannot select DB");
	

$q = mysql_query("SELECT * FROM lifepin WHERE NeedACTION=5 ORDER BY IdPin DESC ");

$count=mysql_num_rows($q);

while($row=mysql_fetch_assoc($q))     									
{


echo "Executing the data extraction process using teserract.....";
echo "<br>";

$ID1 = $row['IdPin'];
$inputfile = $row['RawImage']; 

//$output=system("cmd /c C:\\xampp\\htdocs\\DataExtraction\\ExtractText $inputfile");
$output = shell_exec("C:\\xampp\\htdocs\\ExtractText.bat $inputfile");  
echo"Output: ";
echo $output;
echo "<br>";
echo find_date_id($inputfile,$ID1);
echo "<br>";	

//$link11 = mysql_connect("$host", "$username", "$password")or die("cannot connect");
//mysql_select_db("$db_name")or die("cannot select DB");
$qry="update lifepin set NeedAction=0 where idpin = ".$ID1; 
$q11 = mysql_query($qry);
//mysql_close($link11);

}
*/

############DATA EXTRACTION FROM TESERACT END###############



/*function EnviaMail2($objetivo, $IdUsFIXED, $sobre, $message0, $message, $codExito, $IdU, $fro, $froa ,$quePIN, $queCodigo)
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

/*if ($codExito==0)
{
//LogMov($IdUsu, $fromname, $fromaddress, '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $subject, $email_number);
	$Sobre = $sobre;
	$Body = '<h1>'.$message0.'</h1>';
	$Body .= '<h2>Patient.: '.$IdUsFIXED.'</h2>';
	
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


}  */

function LogBLOCK ($IDBLOCK, $Content, $IdMEDEmail, $IdMEDRESERV, $IdUsFIXED, $IdUsFIXEDNAME, $IdUsRESERV , $Canal)
{
	$retorno = array();
	$MensajeError = "";

	$Fecha = date ('Y-m-d H:i:s');	

				require("environment_detail.php");
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


	$q = $con->prepare("INSERT INTO btracker SET  IDBLOCK =?, Content=?, DateTimeSTAMP = NOW(), TimeSTAMP = NOW(),  IdMEDEmail =?, IdMEDRESERV =?, IdUsFIXED = ?, IdUsFIXEDNAME = ?, IdUsRESERV =? , CANAL = ? ");
	$q->bindValue(1, $IDBLOCK, PDO::PARAM_INT);
	$q->bindValue(2, $Content, PDO::PARAM_STR);
	$q->bindValue(3, $IdMEDEmail, PDO::PARAM_STR);
	$q->bindValue(4, $IdMEDRESERV, PDO::PARAM_INT);
	$q->bindValue(5, $IdUsFIXED, PDO::PARAM_INT);
	$q->bindValue(6, $IdUsFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(7, $IdUsRESERV, PDO::PARAM_INT);
	$q->bindValue(8, $Canal, PDO::PARAM_INT);
	$q->execute();
	
/*
	$retorno[0]=$Cambio;					//PUNTOS SUMADOS AHORA
	$retorno[1]=$Cambio / 50;				//CREDITOS SUMADOS AHORA
	$retorno[2]= $NuevoSaldoPrevio;			//SALDO EN PUNTOS  	 ACTUAL
	$retorno[3]= $NumeroCreditos;			//SALDO EN CREDITOS  ACTUAL
	$retorno[4]= $SaldoPrev;				//SALDO EN PUNTOS  	 **ANTERIOR**
	$retorno[5]= $NumeroCreditosAnt;		//SALDO EN CREDITOS  **ANTERIOR**
	$retorno[6]= $FechaPrevia;				//FECHA DE LA ENTRADA ANTERIOR
	$retorno[7]= $MensajeError;				//MENSAJE
*/

	
return $retorno;
}



?>
