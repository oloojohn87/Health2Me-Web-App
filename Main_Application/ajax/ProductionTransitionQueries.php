<?php
ini_set('max_execution_time', 3000);
error_reporting(E_ALL);
ini_set('display_errors', 1);
//THIS SCRIPT PULLS INFO FROM ONE ENVIORNMENT TO THE OTHER//
 require("environment_detail.php");
 require_once("displayExitClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 echo "RUNNING....";
 
 //THIS MAKES THE CONNECTION TO THE SYSTEM WE WANT TO ADD INFORMATION TO
 $con = new PDO('mysql:host=health2.me;dbname=monimed4;charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
																		
 //THIS MAKES A CONNECTION TO THE SYSTEM WE WANT TO PULL FROM																		
 $con2 = new PDO('mysql:host=health2.me;dbname=monimed;charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  
////////////////////USUARIOS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE usuarios");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM usuarios");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO usuarios SET 
Identif = ?, IdCreator = ?, IdInvite = ?, Sexo = ?, Orden = ?, Alias = ?, NombreUsu = ?, Password = ?, TempoPass = ?, Cloud = ?, UsuDropbox = ?, PasDropbox = ?, twitter = ?, FNac = ?, MedRequest = ?, email = ?, notify_email = ?, telefono = ?, urlimagen = ?, confirmcode = ?, Verificado = ?,
Terms = ?, GrantAccess = ?, TieneVALID = ?, TieneCLASS = ?, TieneBACKU = ?, TieneSTORA = ?, CardHH = ?, PassCard = ?, IdUsFIXED = ?, IdUsFIXEDNAME = ?, IdUsRESERV = ?, Name = ?, Surname = ?, FechaVer = ?, IPVALID = ?, salt = ?, mi = ?");
// pin_hash = ?, most_recent_doc = ?, current_calling_doctor = ?, timezone = ?, signUpDate = ?, typeOfPlan = ?, numberofPhoneCalls = ?, location = ?, stripe_id = ?, plan = ?, subsType = ?, ownerAcc = ?, relationship = ?, grant_access = ?");
$result2->bindValue(1, $row['Identif'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdCreator'], PDO::PARAM_INT);
$result2->bindValue(3, $row['IdInvite'], PDO::PARAM_INT);
$result2->bindValue(4, $row['Sexo'], PDO::PARAM_INT);
$result2->bindValue(5, $row['Orden'], PDO::PARAM_INT);
$result2->bindValue(6, $row['Alias'], PDO::PARAM_STR);
$result2->bindValue(7, $row['NombreUsu'], PDO::PARAM_STR);
$result2->bindValue(8, $row['Password'], PDO::PARAM_STR);
$result2->bindValue(9, $row['TempoPass'], PDO::PARAM_STR);
$result2->bindValue(10, $row['Cloud'], PDO::PARAM_STR);
$result2->bindValue(11, $row['UsuDropbox'], PDO::PARAM_STR);
$result2->bindValue(12, $row['PasDropbox'], PDO::PARAM_STR);
$result2->bindValue(13, $row['twitter'], PDO::PARAM_STR);
$result2->bindValue(14, $row['FNac'], PDO::PARAM_STR);
$result2->bindValue(15, $row['MedRequest'], PDO::PARAM_INT);
$result2->bindValue(16, $row['email'], PDO::PARAM_STR);
$result2->bindValue(17, $row['notify_email'], PDO::PARAM_INT);
$result2->bindValue(18, $row['telefono'], PDO::PARAM_INT);
$result2->bindValue(19, $row['urlimagen'], PDO::PARAM_STR);
$result2->bindValue(20, $row['confirmcode'], PDO::PARAM_STR);
$result2->bindValue(21, $row['Verificado'], PDO::PARAM_INT);
$result2->bindValue(22, $row['Terms'], PDO::PARAM_STR);
$result2->bindValue(23, $row['GrantAccess'], PDO::PARAM_STR);
$result2->bindValue(24, $row['TieneVALID'], PDO::PARAM_INT);
$result2->bindValue(25, $row['TieneCLASS'], PDO::PARAM_INT);
$result2->bindValue(26, $row['TieneBACKU'], PDO::PARAM_INT);
$result2->bindValue(27, $row['TieneSTORA'], PDO::PARAM_INT);
$result2->bindValue(28, $row['CardHH'], PDO::PARAM_INT);
$result2->bindValue(29, $row['PassCard'], PDO::PARAM_STR);
$result2->bindValue(30, $row['IdUsFIXED'], PDO::PARAM_INT);
$result2->bindValue(31, $row['IdUsFIXEDNAME'], PDO::PARAM_STR);
$result2->bindValue(32, $row['IdUsRESERV'], PDO::PARAM_STR);
$result2->bindValue(33, $row['Name'], PDO::PARAM_STR);
$result2->bindValue(34, $row['Surname'], PDO::PARAM_STR);
$result2->bindValue(35, $row['FechaVer'], PDO::PARAM_STR);
$result2->bindValue(36, $row['IPVALID'], PDO::PARAM_STR);
$result2->bindValue(37, $row['salt'], PDO::PARAM_STR);
$result2->bindValue(38, $row['mi'], PDO::PARAM_STR);
$result2->execute();
}*/
////////////////////USUARIOS[END]////////////////////////////

/////////////////////DOCTORS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE doctors");
//$result->execute();
/*																					
$result = $con2->prepare("SELECT * FROM doctors");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
echo $row['id']."</br>";
$result2 = $con->prepare("INSERT INTO doctors2 SET 
id = ?, IdMEDEmail = ?, Role = ?, IdMEDFIXED = ?, IdMEDFIXEDNAME = ?, IdMEDRESERV = ?, Email2 = ?, phone = ?, Name = ?, Surname = ?, ImageLogo = ?, DOB = ?, Gender = ?, OrderOB = ?, Verificado = ?, token = ?, Fecha = ?, IPVALID = ?, salt = ?, previlege = ?, notify_email = ?");
//rating = ?, hourly_rate = ?, speciality = ?, location = ?, telemed = ?, in_consultation = ?, consultation_pat = ?, cons_req_time = ?, rating_score = ?, telemed_type = ?, registered_code = ?, hospital_name = ?, hospital_addr = ?, timezone = ?, stripe_id = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdMEDEmail'], PDO::PARAM_STR);
$result2->bindValue(3, $row['Role'], PDO::PARAM_INT);
$result2->bindValue(4, $row['IdMEDFIXED'], PDO::PARAM_INT);
$result2->bindValue(5, $row['IdMEDFIXEDNAME'], PDO::PARAM_STR);
$result2->bindValue(6, $row['IdMEDRESERV'], PDO::PARAM_STR);
$result2->bindValue(7, $row['Email2'], PDO::PARAM_STR);
$result2->bindValue(8, $row['phone'], PDO::PARAM_INT);
$result2->bindValue(9, $row['Name'], PDO::PARAM_STR);
$result2->bindValue(10, $row['Surname'], PDO::PARAM_STR);
$result2->bindValue(11, $row['ImageLogo'], PDO::PARAM_STR);
$result2->bindValue(12, $row['DOB'], PDO::PARAM_STR);
$result2->bindValue(13, $row['Gender'], PDO::PARAM_INT);
$result2->bindValue(14, $row['OrderOB'], PDO::PARAM_INT);
$result2->bindValue(15, $row['Verificado'], PDO::PARAM_INT);
$result2->bindValue(16, $row['token'], PDO::PARAM_STR);
$result2->bindValue(17, $row['Fecha'], PDO::PARAM_STR);
$result2->bindValue(18, $row['IPVALID'], PDO::PARAM_STR);
$result2->bindValue(19, $row['salt'], PDO::PARAM_STR);
$result2->bindValue(20, $row['previlege'], PDO::PARAM_INT);
$result2->bindValue(21, $row['notify_email'], PDO::PARAM_INT);
$result2->execute();
}
echo "DOCTORS";*/
////////////////////DOCTORS[END]////////////////////////////

////////////////////ALLERGIES[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE allergies");
//$result->execute();
																								
/*$result = $con2->prepare("SELECT * FROM monimed.allergies");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
echo $row['IdPatient']."</br>";
$result2 = $con->prepare("INSERT INTO monimed4.allergies SET 
IdPatient = ?, IdAllergy = ?, Name = ?, Type = ?, DateRec = ?, Description = ?, del = ?");
$result2->bindValue(1, $row['IdPatient'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdAllergy'], PDO::PARAM_INT);
$result2->bindValue(3, $row['Name'], PDO::PARAM_STR);
$result2->bindValue(4, $row['Type'], PDO::PARAM_STR);
$result2->bindValue(5, $row['DateRec'], PDO::PARAM_STR);
$result2->bindValue(6, $row['Description'], PDO::PARAM_STR);
$result2->bindValue(7, $row['del'], PDO::PARAM_INT);
$result2->execute();
}*/
////////////////////ALLERGIES[END]////////////////////////////

////////////////////BASICEMRDATA[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE basicemrdata");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM monimed.basicemrdata");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO monimed4.basicemrdata SET 
IdPatient = ?, DOB = ?, Address = ?, Address2 = ?, City = ?, state = ?, country = ?, Notes = ?, fractures = ?, surgeries = ?, otherknown = ?, obstetric = ?, otherMed = ?, fatherAlive = ?, fatherCoD = ?, fatherAoD = ?, fatherRD = ?, motherAlive = ?, motherCoD = ?,
motherAoD = ?, motherRD = ?, siblingsRD = ?, phone = ?, insurance = ?");
// doctor_signed = ?, latest_update = ?, zip = ?, bloodType = ?, weight = ?, weightType = ?, height1 = ?, height2 = ?, heightType = ?");
$result2->bindValue(1, $row['IdPatient'], PDO::PARAM_INT);
$result2->bindValue(2, $row['DOB'], PDO::PARAM_STR);
$result2->bindValue(3, $row['Address'], PDO::PARAM_STR);
$result2->bindValue(4, $row['Address2'], PDO::PARAM_STR);
$result2->bindValue(5, $row['City'], PDO::PARAM_STR);
$result2->bindValue(6, $row['state'], PDO::PARAM_STR);
$result2->bindValue(7, $row['country'], PDO::PARAM_STR);
$result2->bindValue(8, $row['Notes'], PDO::PARAM_STR);
$result2->bindValue(9, $row['fractures'], PDO::PARAM_STR);
$result2->bindValue(10, $row['surgeries'], PDO::PARAM_STR);
$result2->bindValue(11, $row['otherknown'], PDO::PARAM_STR);
$result2->bindValue(12, $row['obstetric'], PDO::PARAM_STR);
$result2->bindValue(13, $row['otherMed'], PDO::PARAM_STR);
$result2->bindValue(14, $row['fatherAlive'], PDO::PARAM_INT);
$result2->bindValue(15, $row['fatherCoD'], PDO::PARAM_STR);
$result2->bindValue(16, $row['fatherAoD'], PDO::PARAM_INT);
$result2->bindValue(17, $row['fatherRD'], PDO::PARAM_STR);
$result2->bindValue(18, $row['motherAlive'], PDO::PARAM_INT);
$result2->bindValue(19, $row['motherCoD'], PDO::PARAM_STR);
$result2->bindValue(20, $row['motherAoD'], PDO::PARAM_INT);
$result2->bindValue(21, $row['motherRD'], PDO::PARAM_STR);
$result2->bindValue(22, $row['siblingsRD'], PDO::PARAM_STR);
$result2->bindValue(23, $row['phone'], PDO::PARAM_STR);
$result2->bindValue(24, $row['insurance'], PDO::PARAM_STR);
$result2->execute();
}*/
////////////////////BASICEMRDATA[END]////////////////////////////

////////////////////BLOCKS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE blocks");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM monimed.blocks");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO monimed4.blocks SET 
id = ?, NeedACTION = ?, IdEmail = ?, file1 = ?, file2 = ?, file3 = ?, FechaInput = ?, Fecha = ?, FechaEmail = ?, IdUsFIXED = ?, IdUsFIXEDNAME = ?, IdUsRESERV = ?, IdMEDEmail = ?, IdMedRESERV = ?, SignedUSER = ?, SignedUSERDate = ?, Canal = ?, ValidationStatus = ?, NextAction = ?,
EvRuPunt = ?, Evento = ?, Tipo = ?, Especialidad = ?, ICD = ?, IdUsu = ?, IdPin = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['NeedACTION'], PDO::PARAM_INT);
$result2->bindValue(3, $row['IdEmail'], PDO::PARAM_INT);
$result2->bindValue(4, $row['file1'], PDO::PARAM_STR);
$result2->bindValue(5, $row['file2'], PDO::PARAM_STR);
$result2->bindValue(6, $row['file3'], PDO::PARAM_STR);
$result2->bindValue(7, $row['FechaInput'], PDO::PARAM_STR);
$result2->bindValue(8, $row['Fecha'], PDO::PARAM_STR);
$result2->bindValue(9, $row['FechaEmail'], PDO::PARAM_STR);
$result2->bindValue(10, $row['IdUsFIXED'], PDO::PARAM_INT);
$result2->bindValue(11, $row['IdUsFIXEDNAME'], PDO::PARAM_STR);
$result2->bindValue(12, $row['IdUsRESERV'], PDO::PARAM_INT);
$result2->bindValue(13, $row['IdMEDEmail'], PDO::PARAM_STR);
$result2->bindValue(14, $row['IdMedRESERV'], PDO::PARAM_INT);
$result2->bindValue(15, $row['SignedUSER'], PDO::PARAM_INT);
$result2->bindValue(16, $row['SignedUSERDate'], PDO::PARAM_STR);
$result2->bindValue(17, $row['Canal'], PDO::PARAM_INT);
$result2->bindValue(18, $row['ValidationStatus'], PDO::PARAM_INT);
$result2->bindValue(19, $row['NextAction'], PDO::PARAM_STR);
$result2->bindValue(20, $row['EvRuPunt'], PDO::PARAM_INT);
$result2->bindValue(21, $row['Evento'], PDO::PARAM_INT);
$result2->bindValue(22, $row['Tipo'], PDO::PARAM_INT);
$result2->bindValue(23, $row['Especialidad'], PDO::PARAM_INT);
$result2->bindValue(24, $row['ICD'], PDO::PARAM_STR);
$result2->bindValue(25, $row['IdUsu'], PDO::PARAM_INT);
$result2->bindValue(26, $row['IdPin'], PDO::PARAM_INT);
$result2->execute();
}*/
////////////////////BLOCKS[END]////////////////////////////

////////////////////BPINVIEW[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE bpinview");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM monimed.bpinview");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO monimed4.bpinview SET 
id = ?, IDPIN = ?, Content = ?, DateTimeSTAMP = ?, VIEWIdUser = ?, VIEWIdMed = ?, VIEWIP = ?, MEDIO = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IDPIN'], PDO::PARAM_INT);
$result2->bindValue(3, $row['Content'], PDO::PARAM_STR);
$result2->bindValue(4, $row['DateTimeSTAMP'], PDO::PARAM_STR);
$result2->bindValue(5, $row['VIEWIdUser'], PDO::PARAM_INT);
$result2->bindValue(6, $row['VIEWIdMed'], PDO::PARAM_INT);
$result2->bindValue(7, $row['VIEWIP'], PDO::PARAM_STR);
$result2->bindValue(8, $row['MEDIO'], PDO::PARAM_INT);
$result2->execute();
}*/
////////////////////BPINVIEW[END]////////////////////////////

////////////////////BRICK[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE brick");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM brick");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO brick SET 
Coment = ?, Fecha = ?, Hora = ?, IdEnf = ?, IdSint = ?, Estado = ?, Evolucion = ?, IdUs = ?, Id = ?, NombreUsu = ?, Etiq1 = ?, Etiq2 = ?, Etiq3 = ?, Etiq4 = ?, Etiq5 = ?, Etiq6 = ?, Etiq7 = ?, Etiq8 = ?, Etiq9 = ?, Etiq10 = ?, twitter= ?, Vistas = ?,
Link1 = ?, Link2 = ?, Link3 = ?, Vid1 = ?, Vid2 = ?, Vid3 = ?, Especialidad = ?, Aparato = ?, ICD = ?, Farmaco = ?, Tema = ?, urlimagen = ?, SoliMed = ?, REVISADA = ?, SAFE = ?");
$result2->bindValue(1, $row['Coment'], PDO::PARAM_STR);
$result2->bindValue(2, $row['Fecha'], PDO::PARAM_STR);
$result2->bindValue(3, $row['Hora'], PDO::PARAM_STR);
$result2->bindValue(4, $row['IdEnf'], PDO::PARAM_INT);
$result2->bindValue(5, $row['IdSint'], PDO::PARAM_INT);
$result2->bindValue(6, $row['Estado'], PDO::PARAM_INT);
$result2->bindValue(7, $row['Evolucion'], PDO::PARAM_INT);
$result2->bindValue(8, $row['IdUs'], PDO::PARAM_INT);
$result2->bindValue(9, $row['Id'], PDO::PARAM_INT);
$result2->bindValue(10, $row['NombreUsu'], PDO::PARAM_STR);
$result2->bindValue(11, $row['Etiq1'], PDO::PARAM_INT);
$result2->bindValue(12, $row['Etiq2'], PDO::PARAM_INT);
$result2->bindValue(13, $row['Etiq3'], PDO::PARAM_INT);
$result2->bindValue(14, $row['Etiq4'], PDO::PARAM_INT);
$result2->bindValue(15, $row['Etiq5'], PDO::PARAM_INT);
$result2->bindValue(16, $row['Etiq6'], PDO::PARAM_INT);
$result2->bindValue(17, $row['Etiq7'], PDO::PARAM_INT);
$result2->bindValue(18, $row['Etiq8'], PDO::PARAM_INT);
$result2->bindValue(19, $row['Etiq9'], PDO::PARAM_INT);
$result2->bindValue(20, $row['Etiq10'], PDO::PARAM_INT);
$result2->bindValue(21, $row['twitter'], PDO::PARAM_STR);
$result2->bindValue(22, $row['Vistas'], PDO::PARAM_INT);
$result2->bindValue(23, $row['Link1'], PDO::PARAM_STR);
$result2->bindValue(24, $row['Link2'], PDO::PARAM_STR);
$result2->bindValue(25, $row['Link3'], PDO::PARAM_STR);
$result2->bindValue(26, $row['Vid1'], PDO::PARAM_STR);
$result2->bindValue(27, $row['Vid2'], PDO::PARAM_STR);
$result2->bindValue(28, $row['Vid3'], PDO::PARAM_STR);
$result2->bindValue(29, $row['Especialidad'], PDO::PARAM_STR);
$result2->bindValue(30, $row['Aparato'], PDO::PARAM_STR);
$result2->bindValue(31, $row['ICD'], PDO::PARAM_STR);
$result2->bindValue(32, $row['Farmaco'], PDO::PARAM_STR);
$result2->bindValue(33, $row['Tema'], PDO::PARAM_STR);
$result2->bindValue(34, $row['urlimagen'], PDO::PARAM_STR);
$result2->bindValue(35, $row['SoliMed'], PDO::PARAM_STR);
$result2->bindValue(36, $row['REVISADA'], PDO::PARAM_INT);
$result2->bindValue(37, $row['SAFE'], PDO::PARAM_INT);
$result2->execute();
}
echo "BRICK";*/
////////////////////BRICK[END]////////////////////////////

////////////////////BRTERM[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE brterm");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM brterm");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO brterm SET 
Indice = ?, Id = ?, IdTermino = ?, TipoTermino = ?");
$result2->bindValue(1, $row['Indice'], PDO::PARAM_INT);
$result2->bindValue(2, $row['Id'], PDO::PARAM_INT);
$result2->bindValue(3, $row['IdTermino'], PDO::PARAM_INT);
$result2->bindValue(4, $row['TipoTermino'], PDO::PARAM_INT);
$result2->execute();
}
echo "BRTERM";*/
////////////////////BRTERM[END]////////////////////////////

////////////////////BTRACKER[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE btracker");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM btracker");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO btracker SET 
id = ?, IDBLOCK = ?, Content = ?, DateTimeSTAMP = ?, TimeSTAMP = ?, IdMEDEmail = ?, IdMEDRESERV = ?, IdUsFIXED = ?, IdUsFIXEDNAME = ?, IdUsRESERV = ?, Canal = ?, VIEWIdUser = ?, VIEWIdMed = ?, VIEWIP = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IDBLOCK'], PDO::PARAM_INT);
$result2->bindValue(3, $row['Content'], PDO::PARAM_STR);
$result2->bindValue(4, $row['DateTimeSTAMP'], PDO::PARAM_STR);
$result2->bindValue(5, $row['TimeSTAMP'], PDO::PARAM_STR);
$result2->bindValue(6, $row['IdMEDEmail'], PDO::PARAM_STR);
$result2->bindValue(7, $row['IdMEDRESERV'], PDO::PARAM_INT);
$result2->bindValue(8, $row['IdUsFIXED'], PDO::PARAM_INT);
$result2->bindValue(9, $row['IdUsFIXEDNAME'], PDO::PARAM_STR);
$result2->bindValue(10, $row['IdUsRESERV'], PDO::PARAM_INT);
$result2->bindValue(11, $row['Canal'], PDO::PARAM_INT);
$result2->bindValue(12, $row['VIEWIdUser'], PDO::PARAM_INT);
$result2->bindValue(13, $row['VIEWIdMed'], PDO::PARAM_INT);
$result2->bindValue(14, $row['VIEWIP'], PDO::PARAM_STR);
$result2->execute();
}
echo "BRTRACKER";*/
////////////////////BTRACKER[END]////////////////////////////

////////////////////CANCELED_REFERRALS[START]////////////////////////////REVISIT
//$result = $con->prepare("sdfgsdfg TABLE canceled_referrals");
//$result->execute();
/*
$result = $con2->prepare("SELECT * FROM canceled_referrals");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO canceled_referrals SET 
id = ?, IdMED = ?, IdMED2 = ?, IdPac = ?, FechaConfirm = ?, IPConfirm = ?, estado = ?, Confirm = ?, doctoremail = ?, attachments = ?, Archive = ?, Type = ?, Cancel_docid = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdMED'], PDO::PARAM_INT);
$result2->bindValue(3, $row['IdMED2'], PDO::PARAM_INT);
$result2->bindValue(4, $row['IdPac'], PDO::PARAM_INT);
$result2->bindValue(5, $row['FechaConfirm'], PDO::PARAM_STR);
$result2->bindValue(6, $row['IPConfirm'], PDO::PARAM_STR);
$result2->bindValue(7, $row['estado'], PDO::PARAM_INT);
$result2->bindValue(8, $row['Confirm'], PDO::PARAM_STR);
$result2->bindValue(9, $row['doctoremail'], PDO::PARAM_STR);
$result2->bindValue(10, $row['attachments'], PDO::PARAM_STR);
$result2->bindValue(11, $row['Archive'], PDO::PARAM_INT);
$result2->bindValue(12, $row['Type'], PDO::PARAM_INT);
$result2->bindValue(13, $row['Cancel_docid'], PDO::PARAM_INT);
$result2->execute();
}
echo "Cancelled_referrals";*/
////////////////////CANCELED_REFERRALS[END]////////////////////////////

////////////////////CENTROS[START]////////////////////////////REVISIT
//$result = $con->prepare("sdfgsdfg TABLE centros");
//$result->execute();
/*
$result = $con2->prepare("SELECT * FROM centros");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO centros SET 
id = ?, Identificador = ?, Nombre = ?, URL1 = ?, URL2 = ?, URL3 = ?, Imagen = ?, Longitud = ?, Latitud = ?, Direccion = ?, Ciudad = ?, Provincia = ?, Pais = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['Identificador'], PDO::PARAM_STR);
$result2->bindValue(3, $row['Nombre'], PDO::PARAM_STR);
$result2->bindValue(4, $row['URL1'], PDO::PARAM_STR);
$result2->bindValue(5, $row['URL2'], PDO::PARAM_STR);
$result2->bindValue(6, $row['URL3'], PDO::PARAM_STR);
$result2->bindValue(7, $row['Imagen'], PDO::PARAM_STR);
$result2->bindValue(8, $row['Longitud'], PDO::PARAM_STR);
$result2->bindValue(9, $row['Latitud'], PDO::PARAM_STR);
$result2->bindValue(10, $row['Direccion'], PDO::PARAM_STR);
$result2->bindValue(11, $row['Ciudad'], PDO::PARAM_STR);
$result2->bindValue(12, $row['Provincia'], PDO::PARAM_STR);
$result2->bindValue(13, $row['Pais'], PDO::PARAM_STR);
$result2->execute();
}
echo "CENTROS";*/
////////////////////CENTROS[END]////////////////////////////

////////////////////CERTIFICATIONS[START]////////////////////////////I THINK WE NEED TO KEEP THIS
//$result = $con->prepare("sdfgsdfg TABLE certifications");
//$result->execute();

////////////////////CERTIFICATIONS[END]////////////////////////////

////////////////////CHANGING_PERSONAL_HISTORY[START]////////////////////////////REVISIT
//$result = $con->prepare("sdfgsdfg TABLE changing_personal_history");
//$result->execute();
/*
$result = $con2->prepare("SELECT * FROM changing_personal_history");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO changing_personal_history SET 
id = ?, idpatient = ?, height = ?, weight = ?, hbp = ?, lbp = ?, date_rec = ?, del = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['idpatient'], PDO::PARAM_INT);
$result2->bindValue(3, $row['height'], PDO::PARAM_STR);
$result2->bindValue(4, $row['weight'], PDO::PARAM_STR);
$result2->bindValue(5, $row['hbp'], PDO::PARAM_STR);
$result2->bindValue(6, $row['lbp'], PDO::PARAM_STR);
$result2->bindValue(7, $row['date_rec'], PDO::PARAM_STR);
$result2->bindValue(8, $row['del'], PDO::PARAM_INT);
$result2->execute();
}
echo "CHANGING_PERSONAL_HISTORY";*/
////////////////////CHANGING_PERSONAL_HISTORY[END]////////////////////////////
/*
////////////////////CONSULTS[START]////////////////////////////
$result = $con->prepare("sdfgsdfg TABLE consults");
$result->execute();

////////////////////CONSULTS[END]////////////////////////////
*/
////////////////////DECK[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE deck");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM deck");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO deck SET 
id = ?, IdDr = ?, IdPatient = ?, NamePatient = ?, Type = ?, Alert = ?, Time = ?, Date = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdDr'], PDO::PARAM_INT);
$result2->bindValue(3, $row['IdPatient'], PDO::PARAM_INT);
$result2->bindValue(4, $row['NamePatient'], PDO::PARAM_STR);
$result2->bindValue(5, $row['Type'], PDO::PARAM_INT);
$result2->bindValue(6, $row['Alert'], PDO::PARAM_INT);
$result2->bindValue(7, $row['Time'], PDO::PARAM_STR);
$result2->bindValue(8, $row['Date'], PDO::PARAM_STR);
$result2->execute();
}
echo "DECK";*/
////////////////////DECK[END]////////////////////////////
/*
////////////////////DOCTOR_CALLS[START]////////////////////////////
$result = $con->prepare("sdfgsdfg TABLE doctor_calls");
$result->execute();

////////////////////DOCTOR_CALLS[END]////////////////////////////
*/
////////////////////DOCTORSGROUPS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE doctorsgroups");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM doctorsgroups");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO doctorsgroups SET 
id = ?, idGroup = ?, idDoctor = ?, Fecha = ?, Role = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['idGroup'], PDO::PARAM_INT);
$result2->bindValue(3, $row['idDoctor'], PDO::PARAM_INT);
$result2->bindValue(4, $row['Fecha'], PDO::PARAM_STR);
$result2->bindValue(5, $row['Role'], PDO::PARAM_INT);
$result2->execute();
}
echo "DOCTORS GROUP";*/
////////////////////DOCTORSGROUPS[END]////////////////////////////

////////////////////DOCTORSLINKDOCTORS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE doctorslinkdoctors");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM doctorslinkdoctors");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO doctorslinkdoctors SET 
id = ?, IdMED = ?, IdMED2 = ?, IdPac = ?, Fecha = ?, FechaConfirm = ?, IPConfirm = ?, estado = ?, Confirm = ?, doctoremail = ?, attachments = ?, Archive = ?, Type = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdMED'], PDO::PARAM_INT);
$result2->bindValue(3, $row['IdMED2'], PDO::PARAM_INT);
$result2->bindValue(4, $row['IdPac'], PDO::PARAM_INT);
$result2->bindValue(5, $row['Fecha'], PDO::PARAM_STR);
$result2->bindValue(6, $row['FechaConfirm'], PDO::PARAM_STR);
$result2->bindValue(7, $row['IPConfirm'], PDO::PARAM_STR);
$result2->bindValue(8, $row['estado'], PDO::PARAM_INT);
$result2->bindValue(9, $row['Confirm'], PDO::PARAM_STR);
$result2->bindValue(10, $row['doctoremail'], PDO::PARAM_STR);
$result2->bindValue(11, $row['attachments'], PDO::PARAM_STR);
$result2->bindValue(12, $row['Archive'], PDO::PARAM_INT);
$result2->bindValue(13, $row['Type'], PDO::PARAM_INT);
$result2->execute();
}
echo "DOCTORSLINKDOCTORS";*/
////////////////////DOCTORSLINKDOCTORS[END]////////////////////////////

////////////////////DOCTORSLINKUSERS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE doctorslinkusers");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM doctorslinkusers");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO doctorslinkusers SET 
id = ?, IdMED = ?, IdUs = ?, Fecha = ?, IdPIN = ?, estado = ?, Confirm = ?, Tipo = ?, TemporaryEmail = ?, TemporaryPhone = ?, TempoPass = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdMED'], PDO::PARAM_INT);
$result2->bindValue(3, $row['IdUs'], PDO::PARAM_INT);
$result2->bindValue(4, $row['Fecha'], PDO::PARAM_STR);
$result2->bindValue(5, $row['IdPIN'], PDO::PARAM_INT);
$result2->bindValue(5, $row['estado'], PDO::PARAM_INT);
$result2->bindValue(6, $row['Confirm'], PDO::PARAM_STR);
$result2->bindValue(7, $row['Tipo'], PDO::PARAM_INT);
$result2->bindValue(8, $row['TemporaryEmail'], PDO::PARAM_STR);
$result2->bindValue(9, $row['TemporaryPhone'], PDO::PARAM_INT);
$result2->bindValue(10, $row['TempoPass'], PDO::PARAM_STR);
$result2->execute();
}
echo "DOCTORSLINKUSERS";*/
////////////////////DOCTORSLINKUSERS[END]////////////////////////////

////////////////////EMR_CONFIG[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE emr_config");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM emr_config");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO emr_config SET 
userid = ?, demographics = ?, personal = ?, family = ?, pastdx = ?, medications = ?, immunizations = ?, allergies = ?, name = ?, middle = ?, surname = ?, gender = ?, dob = ?, address = ?, address2 = ?, city = ?, state = ?, country = ?, notes = ?, fractures = ?,
surgeries = ?, otherknown = ?, obstetric = ?, othermed = ?, father = ?, mother = ?, siblings = ?, insurance = ?, phone = ?");
$result2->bindValue(1, $row['userid'], PDO::PARAM_INT);
$result2->bindValue(2, $row['demographics'], PDO::PARAM_INT);
$result2->bindValue(3, $row['personal'], PDO::PARAM_INT);
$result2->bindValue(4, $row['family'], PDO::PARAM_INT);
$result2->bindValue(5, $row['pastdx'], PDO::PARAM_INT);
$result2->bindValue(6, $row['medications'], PDO::PARAM_INT);
$result2->bindValue(7, $row['immunizations'], PDO::PARAM_INT);
$result2->bindValue(8, $row['allergies'], PDO::PARAM_INT);
$result2->bindValue(9, $row['name'], PDO::PARAM_INT);
$result2->bindValue(10, $row['middle'], PDO::PARAM_INT);
$result2->bindValue(11, $row['surname'], PDO::PARAM_INT);
$result2->bindValue(12, $row['gender'], PDO::PARAM_INT);
$result2->bindValue(13, $row['dob'], PDO::PARAM_INT);
$result2->bindValue(14, $row['address'], PDO::PARAM_INT);
$result2->bindValue(15, $row['address2'], PDO::PARAM_INT);
$result2->bindValue(16, $row['city'], PDO::PARAM_INT);
$result2->bindValue(17, $row['state'], PDO::PARAM_INT);
$result2->bindValue(18, $row['country'], PDO::PARAM_INT);
$result2->bindValue(19, $row['notes'], PDO::PARAM_INT);
$result2->bindValue(20, $row['fractures'], PDO::PARAM_INT);
$result2->bindValue(21, $row['surgeries'], PDO::PARAM_INT);
$result2->bindValue(22, $row['otherknown'], PDO::PARAM_INT);
$result2->bindValue(23, $row['obstetric'], PDO::PARAM_INT);
$result2->bindValue(24, $row['othermed'], PDO::PARAM_INT);
$result2->bindValue(25, $row['father'], PDO::PARAM_INT);
$result2->bindValue(26, $row['mother'], PDO::PARAM_INT);
$result2->bindValue(27, $row['siblings'], PDO::PARAM_INT);
$result2->bindValue(28, $row['insurance'], PDO::PARAM_INT);
$result2->bindValue(29, $row['phone'], PDO::PARAM_INT);
$result2->execute();
}
echo "EMRCONFIG";*/
////////////////////EMR_CONFIG[END]////////////////////////////

////////////////////ESMOV[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE esmov");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM esmov");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO esmov SET 
Id = ?, IdUsu = ?, Fecha = ?, TipoActiv = ?, Importe = ?, SaldoPrevio = ?");
$result2->bindValue(1, $row['Id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdUsu'], PDO::PARAM_INT);
$result2->bindValue(3, $row['Fecha'], PDO::PARAM_STR);
$result2->bindValue(4, $row['TipoActiv'], PDO::PARAM_INT);
$result2->bindValue(5, $row['Importe'], PDO::PARAM_INT);
$result2->bindValue(6, $row['SaldoPrevio'], PDO::PARAM_INT);
$result2->execute();
}
echo "ESMOV";*/
////////////////////ESMOV[END]////////////////////////////

////////////////////ESTAMP[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE estamp");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM estamp");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO estamp SET 
Id = ?, IdUsu = ?, Fecha = ?, TipoActiv = ?, Importe = ?, SaldoPrevio = ?, FechaAnt = ?");
$result2->bindValue(1, $row['Id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdUsu'], PDO::PARAM_INT);
$result2->bindValue(3, $row['Fecha'], PDO::PARAM_STR);
$result2->bindValue(4, $row['TipoActiv'], PDO::PARAM_INT);
$result2->bindValue(5, $row['Importe'], PDO::PARAM_INT);
$result2->bindValue(6, $row['SaldoPrevio'], PDO::PARAM_INT);
$result2->bindValue(7, $row['FechaAnt'], PDO::PARAM_STR);
$result2->execute();
}
echo "ESTAMP";*/
////////////////////ESTAMP[END]////////////////////////////

////////////////////ETIQUETAS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE etiquetas");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM etiquetas");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO etiquetas SET 
Id = ?, nombre = ?, Especialidad = ?, Aparato = ?, CIE_TEN = ?");
$result2->bindValue(1, $row['Id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['nombre'], PDO::PARAM_STR);
$result2->bindValue(3, $row['Especialidad'], PDO::PARAM_INT);
$result2->bindValue(4, $row['Aparato'], PDO::PARAM_INT);
$result2->bindValue(5, $row['CIE-10'], PDO::PARAM_STR);
$result2->execute();
}
echo "ETIQUETAS";*/
////////////////////ETIQUETAS[END]////////////////////////////

////////////////////ETTERM[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE etterm");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM etterm");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO etterm SET 
Indice = ?, Id = ?, IdTermino = ?, TipoTermino = ?");
$result2->bindValue(1, $row['Indice'], PDO::PARAM_INT);
$result2->bindValue(2, $row['Id'], PDO::PARAM_INT);
$result2->bindValue(3, $row['IdTermino'], PDO::PARAM_INT);
$result2->bindValue(4, $row['TipoTermino'], PDO::PARAM_INT);
$result2->execute();
}
echo "ETTERM";*/
////////////////////ETTERM[END]////////////////////////////

////////////////////EVENTS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE events");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM events");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO events SET 
id = ?, title = ?, start = ?, end = ?, allday = ?, userid = ?, parentid = ?, patient = ?, added_by = ?, gmt_start = ?, doctor_notify = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['title'], PDO::PARAM_STR);
$result2->bindValue(3, $row['start'], PDO::PARAM_STR);
$result2->bindValue(4, $row['end'], PDO::PARAM_STR);
$result2->bindValue(5, $row['allday'], PDO::PARAM_STR);
$result2->bindValue(6, $row['userid'], PDO::PARAM_INT);
$result2->bindValue(7, $row['parentid'], PDO::PARAM_INT);
$result2->bindValue(8, $row['patient'], PDO::PARAM_INT);
$result2->bindValue(9, $row['added_by'], PDO::PARAM_INT);
$result2->bindValue(10, $row['gmt_start'], PDO::PARAM_STR);
$result2->bindValue(11, $row['doctor_notify'], PDO::PARAM_STR);
$result2->execute();
}
echo "EVENTS";*/
////////////////////EVENTS[END]////////////////////////////

////////////////////EVOLUTIONS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE evolutions");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM evolutions");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO evolutions SET 
id = ?, message_date = ?, message = ?, userid = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['message_date'], PDO::PARAM_STR);
$result2->bindValue(3, $row['message'], PDO::PARAM_STR);
$result2->bindValue(4, $row['userid'], PDO::PARAM_INT);
$result2->execute();
}
echo "Evolucion";*/
////////////////////EVOLUTIONS[END]////////////////////////////

////////////////////FOLLOWS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE follows");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM follows");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO follows SET 
Id = ?, IdUsuario = ?, IdPregunta = ?, Dia = ?, Hora = ?");
$result2->bindValue(1, $row['Id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdUsuario'], PDO::PARAM_INT);
$result2->bindValue(3, $row['IdPregunta'], PDO::PARAM_INT);
$result2->bindValue(4, $row['Dia'], PDO::PARAM_STR);
$result2->bindValue(5, $row['Hora'], PDO::PARAM_STR);
$result2->execute();
}
echo "FOLLOWS";*/
////////////////////FOLLOWS[END]////////////////////////////

////////////////////GROUP_ROLES[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE group_roles");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM group_roles");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO group_roles SET 
id = ?, groupid = ?, Idmed = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['groupid'], PDO::PARAM_INT);
$result2->bindValue(3, $row['Idmed'], PDO::PARAM_INT);
$result2->execute();
}
echo "GROUP_ROLES";*/
////////////////////GROUP_ROLES[END]////////////////////////////

////////////////////GROUPS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE groups");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM groups");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO groups SET 
id = ?, Name = ?, Address = ?, ZIP = ?, City = ?, State = ?, Country = ?, owner = ?, refincharge = ?, image = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['Name'], PDO::PARAM_STR);
$result2->bindValue(3, $row['Address'], PDO::PARAM_STR);
$result2->bindValue(4, $row['ZIP'], PDO::PARAM_STR);
$result2->bindValue(5, $row['City'], PDO::PARAM_STR);
$result2->bindValue(6, $row['State'], PDO::PARAM_STR);
$result2->bindValue(7, $row['Country'], PDO::PARAM_STR);
$result2->bindValue(8, $row['owner'], PDO::PARAM_INT);
$result2->bindValue(9, $row['refincharge'], PDO::PARAM_INT);
$result2->bindValue(10, $row['image'], PDO::PARAM_STR);
$result2->execute();
}
echo "GROUPS";*/
////////////////////GROUPS[END]////////////////////////////

////////////////////IMAGENES[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE imagenes");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM imagenes");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO imagenes SET 
IdImagen = ?, IdUsuario = ?, NombreImagen = ?, Dia = ?, BORRAR = ?");
$result2->bindValue(1, $row['IdImagen'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdUsuario'], PDO::PARAM_INT);
$result2->bindValue(3, $row['NombreImagen'], PDO::PARAM_STR);
$result2->bindValue(4, $row['Dia'], PDO::PARAM_STR);
$result2->bindValue(5, $row['BORRAR'], PDO::PARAM_STR);
$result2->execute();
}
echo "IMAGENES";*/
////////////////////IMAGENES[END]////////////////////////////

////////////////////IMMUNIZATIONS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE immunizations");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM immunizations");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO immunizations SET 
IdPatient = ?, IdImmunization = ?, Name = ?, date = ?, age = ?, reaction = ?, del = ?");
$result2->bindValue(1, $row['IdPatient'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdImmunization'], PDO::PARAM_INT);
$result2->bindValue(3, $row['Name'], PDO::PARAM_STR);
$result2->bindValue(4, $row['date'], PDO::PARAM_STR);
$result2->bindValue(5, $row['age'], PDO::PARAM_INT);
$result2->bindValue(6, $row['reaction'], PDO::PARAM_STR);
$result2->bindValue(7, $row['del'], PDO::PARAM_INT);
$result2->execute();
}
echo "IMMUNIZATIONS";*/
////////////////////IMMUNIZATIONS[END]////////////////////////////

////////////////////LIFEPIN[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE lifepin");
//$result->execute();
																				
$result = $con2->prepare("SELECT * FROM lifepin");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){

/*
$result2 = $con->prepare("INSERT INTO lifepin SET 
IdUsu = ?, NumImages = ?, RawImage = ?, RawImage2 = ?, RawImage3 = ?, Fecha = ?, FechaInput = ?, EvRuPunt = ?, Evento = ?, Tipo = ?, Especialidad = ?, EspecialidadT = ?, ICD = ?, TextoRel = ?, confirmcode = ?, approved = ?, CENTRO = ?,
EMAILORIGEN = ?, CANAL = ?, NeedACTION = ?, IdEmail = ?, FechaEmail = ?, IdUsFIXED = ?, IdUsFIXEDNAME = ?, IdUsRESERV = ?, IdMedRESERV = ?, SignedUSER = ?, IdMed = ?, CreatorType = ?, IdCreator = ?, SignedUSERDate = ?, ValidationStatus = ?, NextAction = ?, Location = ?,
suggesteddate = ?, suggestedid = ?, suggesteddate1 = ?, vs = ?, fs = ?, a_s = ?, checksum = ?, IdMEDEmail = ?, orig_filename = ?, IsPrivate = ?, markfordelete = ?, emr_report = ?, emr_old = ?, isDicom = ?, nameScore = ?");
$result2->bindValue(1, $row['IdUsu'], PDO::PARAM_INT);
$result2->bindValue(2, $row['NumImages'], PDO::PARAM_INT);
$result2->bindValue(3, $row['RawImage'], PDO::PARAM_STR);
$result2->bindValue(4, $row['RawImage2'], PDO::PARAM_STR);
$result2->bindValue(5, $row['RawImage3'], PDO::PARAM_STR);
$result2->bindValue(6, $row['Fecha'], PDO::PARAM_STR);
$result2->bindValue(7, $row['FechaInput'], PDO::PARAM_STR);
$result2->bindValue(8, $row['EvRuPunt'], PDO::PARAM_INT);
$result2->bindValue(9, $row['Evento'], PDO::PARAM_INT);
$result2->bindValue(10, $row['Tipo'], PDO::PARAM_INT);
$result2->bindValue(11, $row['Especialidad'], PDO::PARAM_INT);
$result2->bindValue(12, $row['EspecialidadT'], PDO::PARAM_STR);
$result2->bindValue(13, $row['ICD'], PDO::PARAM_STR);
$result2->bindValue(14, $row['TextoRel'], PDO::PARAM_STR);
$result2->bindValue(15, $row['confirmcode'], PDO::PARAM_STR);
$result2->bindValue(16, $row['approved'], PDO::PARAM_INT);
$result2->bindValue(17, $row['CENTRO'], PDO::PARAM_STR);
$result2->bindValue(18, $row['EMAILORIGEN'], PDO::PARAM_STR);
$result2->bindValue(19, $row['CANAL'], PDO::PARAM_STR);
$result2->bindValue(20, $row['NeedACTION'], PDO::PARAM_INT);
$result2->bindValue(21, $row['IdEmail'], PDO::PARAM_STR);
$result2->bindValue(22, $row['FechaEmail'], PDO::PARAM_STR);
$result2->bindValue(23, $row['IdUsFIXED'], PDO::PARAM_INT);
$result2->bindValue(24, $row['IdUsFIXEDNAME'], PDO::PARAM_STR);
$result2->bindValue(25, $row['IdUsRESERV'], PDO::PARAM_INT);
$result2->bindValue(26, $row['IdMedRESERV'], PDO::PARAM_INT);
$result2->bindValue(27, $row['SignedUSER'], PDO::PARAM_INT);
$result2->bindValue(28, $row['IdMed'], PDO::PARAM_INT);
$result2->bindValue(29, $row['CreatorType'], PDO::PARAM_INT);
$result2->bindValue(30, $row['IdCreator'], PDO::PARAM_INT);
$result2->bindValue(31, $row['SignedUSERDate'], PDO::PARAM_STR);
$result2->bindValue(32, $row['ValidationStatus'], PDO::PARAM_INT);
$result2->bindValue(33, $row['NextAction'], PDO::PARAM_STR);
$result2->bindValue(34, $row['Location'], PDO::PARAM_INT);
$result2->bindValue(35, $row['suggesteddate'], PDO::PARAM_STR);
$result2->bindValue(36, $row['suggestedid'], PDO::PARAM_STR);
$result2->bindValue(37, $row['suggesteddate1'], PDO::PARAM_STR);
$result2->bindValue(38, $row['vs'], PDO::PARAM_INT);
$result2->bindValue(39, $row['fs'], PDO::PARAM_INT);
$result2->bindValue(40, $row['a_s'], PDO::PARAM_INT);
$result2->bindValue(41, $row['checksum'], PDO::PARAM_STR);
$result2->bindValue(42, $row['IdMEDEmail'], PDO::PARAM_STR);
$result2->bindValue(43, $row['orig_filename'], PDO::PARAM_STR);
$result2->bindValue(44, $row['IsPrivate'], PDO::PARAM_INT);
$result2->bindValue(45, $row['markfordelete'], PDO::PARAM_INT);
$result2->bindValue(46, $row['emr_report'], PDO::PARAM_INT);
$result2->bindValue(47, $row['emr_old'], PDO::PARAM_INT);
$result2->bindValue(48, $row['isDicom'], PDO::PARAM_INT);
$result2->bindValue(49, $row['nameScore'], PDO::PARAM_STR);
$result2->execute();
*/
}
echo "LIFEPIN";
////////////////////LIFEPIN[END]////////////////////////////

////////////////////LIKES[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE likes");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM likes");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO likes SET 
Id = ?, IdUsuario = ?, IdRespuesta = ?, Dia = ?, Hora = ?");
$result2->bindValue(1, $row['Id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdUsuario'], PDO::PARAM_INT);
$result2->bindValue(3, $row['IdRespuesta'], PDO::PARAM_INT);
$result2->bindValue(4, $row['Dia'], PDO::PARAM_STR);
$result2->bindValue(5, $row['Hora'], PDO::PARAM_STR);
$result2->execute();
}
echo "LIKES";*/
////////////////////LIKES[END]////////////////////////////

////////////////////LOGMBE[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE logmbe");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM logmbe");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO logmbe SET 
id = ?, UsId = ?, Fecha = ?, fromname = ?, fromaddress = ?, CodLog = ?, AlfaLog = ?, Contenido = ?, subject = ?, emailno = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['UsId'], PDO::PARAM_INT);
$result2->bindValue(3, $row['Fecha'], PDO::PARAM_STR);
$result2->bindValue(4, $row['fromname'], PDO::PARAM_STR);
$result2->bindValue(5, $row['fromaddress'], PDO::PARAM_STR);
$result2->bindValue(6, $row['CodLog'], PDO::PARAM_INT);
$result2->bindValue(7, $row['AlfaLog'], PDO::PARAM_STR);
$result2->bindValue(8, $row['Contenido'], PDO::PARAM_STR);
$result2->bindValue(9, $row['subject'], PDO::PARAM_STR);
$result2->bindValue(10, $row['emailno'], PDO::PARAM_STR);
$result2->execute();
}
echo "LOGMBE";*/
////////////////////LOGMBE[END]////////////////////////////

////////////////////MEDICATIONS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE medications");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM medications");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO medications SET 
IdPatient = ?, IdMedication = ?, Name = ?, DrugCode = ?, Dossage = ?, NumberDay = ?, datestart = ?, datestop = ?, del = ?");
$result2->bindValue(1, $row['IdPatient'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdMedication'], PDO::PARAM_INT);
$result2->bindValue(3, $row['Name'], PDO::PARAM_STR);
$result2->bindValue(4, $row['DrugCode'], PDO::PARAM_STR);
$result2->bindValue(5, $row['Dossage'], PDO::PARAM_STR);
$result2->bindValue(6, $row['NumberDay'], PDO::PARAM_INT);
$result2->bindValue(7, $row['datestart'], PDO::PARAM_STR);
$result2->bindValue(8, $row['datestop'], PDO::PARAM_STR);
$result2->bindValue(9, $row['del'], PDO::PARAM_INT);
$result2->execute();
}
echo "MEDICATIONS";*/
////////////////////MEDICATIONS[END]////////////////////////////

////////////////////MESSAGE_INFRASTRUCTUREUSER[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE message_infrastructureuser");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM message_infrastructureuser");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO message_infrastructureuser SET 
message_id = ?, Subject = ?, content = ?, tofrom = ?, sender_id = ?, receiver_id = ?, fecha = ?, status = ?, patient_id = ?, attachments = ?, inbox = ?, outbox = ?, connection_id = ?");
$result2->bindValue(1, $row['message_id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['Subject'], PDO::PARAM_STR);
$result2->bindValue(3, $row['content'], PDO::PARAM_STR);
$result2->bindValue(4, $row['tofrom'], PDO::PARAM_STR);
$result2->bindValue(5, $row['sender_id'], PDO::PARAM_INT);
$result2->bindValue(6, $row['receiver_id'], PDO::PARAM_INT);
$result2->bindValue(7, $row['fecha'], PDO::PARAM_STR);
$result2->bindValue(8, $row['status'], PDO::PARAM_STR);
$result2->bindValue(9, $row['patient_id'], PDO::PARAM_INT);
$result2->bindValue(10, $row['attachments'], PDO::PARAM_STR);
$result2->bindValue(11, $row['inbox'], PDO::PARAM_INT);
$result2->bindValue(12, $row['outbox'], PDO::PARAM_INT);
$result2->bindValue(13, $row['connection_id'], PDO::PARAM_INT);
$result2->execute();
}
echo "MESSAGE_INFRASTRURE";*/
////////////////////MESSAGE_INFRASTRUCTUREUSER[END]////////////////////////////

////////////////////MESSAGE_INFRASTRUCTURE[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE message_infrastructure");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM message_infrasture");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO message_infrasture SET 
message_id = ?, Subject = ?, content = ?, sender_id = ?, receiver_id = ?, fecha = ?, status = ?, patient_id = ?, attachments = ?, inbox = ?, outbox = ?, connection_id = ?, is_mobile = ?");
$result2->bindValue(1, $row['message_id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['Subject'], PDO::PARAM_STR);
$result2->bindValue(3, $row['content'], PDO::PARAM_STR);
$result2->bindValue(4, $row['sender_id'], PDO::PARAM_INT);
$result2->bindValue(5, $row['receiver_id'], PDO::PARAM_INT);
$result2->bindValue(6, $row['fecha'], PDO::PARAM_STR);
$result2->bindValue(7, $row['status'], PDO::PARAM_STR);
$result2->bindValue(8, $row['patient_id'], PDO::PARAM_INT);
$result2->bindValue(9, $row['attachments'], PDO::PARAM_STR);
$result2->bindValue(10, $row['inbox'], PDO::PARAM_INT);
$result2->bindValue(11, $row['outbox'], PDO::PARAM_INT);
$result2->bindValue(12, $row['connection_id'], PDO::PARAM_INT);
$result2->bindValue(13, $row['is_mobile'], PDO::PARAM_INT);
$result2->execute();
}
echo "message_infrastructure";*/
////////////////////MESSAGE_INFRASTRUCTURE[END]////////////////////////////

////////////////////MESSAGES[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE messages");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM messages");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO messages SET 
id = ?, Fecha = ?, ToId = ?, ToType = ?, ToEmail = ?, FromId = ?, FromEmail = ?, Subject = ?, Content = ?, Leido = ?, Importante = ?, Push = ?, Confirm = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['Fecha'], PDO::PARAM_STR);
$result2->bindValue(3, $row['ToId'], PDO::PARAM_INT);
$result2->bindValue(4, $row['ToType'], PDO::PARAM_INT);
$result2->bindValue(5, $row['ToEmail'], PDO::PARAM_STR);
$result2->bindValue(6, $row['FromId'], PDO::PARAM_INT);
$result2->bindValue(7, $row['FromEmail'], PDO::PARAM_STR);
$result2->bindValue(8, $row['Subject'], PDO::PARAM_STR);
$result2->bindValue(9, $row['Content'], PDO::PARAM_STR);
$result2->bindValue(10, $row['Leido'], PDO::PARAM_INT);
$result2->bindValue(11, $row['Importante'], PDO::PARAM_INT);
$result2->bindValue(12, $row['Push'], PDO::PARAM_INT);
$result2->bindValue(13, $row['Confirm'], PDO::PARAM_STR);
$result2->execute();
}
echo "MESSAGES";*/
////////////////////MESSAGES[END]////////////////////////////

////////////////////NOTES[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE notes");
//$result->execute();
////////////////////NOTES[END]////////////////////////////

////////////////////NOTIFICATION_CONFIG[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE notification_config");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM notification_config");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO notification_config SET 
id = ?, userid = ?, minutes = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['userid'], PDO::PARAM_INT);
$result2->bindValue(3, $row['minutes'], PDO::PARAM_INT);
$result2->execute();
}
echo "NOTIFICATIONS_CONFIG";*/
////////////////////NOTIFICATION_CONFIG[END]////////////////////////////

////////////////////ONGOING_SESSIONS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE ongoing_sessions");
//$result->execute();
////////////////////ONGOING_SESSIONS[END]////////////////////////////

////////////////////ONSCREENTRAY[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE onscreentray");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM onscreentray");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO onscreentray SET 
id = ?, DateTimeStamp = ?, IdUser = ?, IdDoctor = ?, IdDoctorADD = ?, MType = ?, Message = ?, IconText = ?, SubText = ?, MainText = ?, MColor = ?, MLink = ?, MRead = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['DateTimeStamp'], PDO::PARAM_STR);
$result2->bindValue(3, $row['IdUser'], PDO::PARAM_INT);
$result2->bindValue(4, $row['IdDoctor'], PDO::PARAM_INT);
$result2->bindValue(5, $row['IdDoctorADD'], PDO::PARAM_INT);
$result2->bindValue(6, $row['MType'], PDO::PARAM_INT);
$result2->bindValue(7, $row['Message'], PDO::PARAM_STR);
$result2->bindValue(8, $row['IconText'], PDO::PARAM_STR);
$result2->bindValue(9, $row['SubText'], PDO::PARAM_STR);
$result2->bindValue(10, $row['MainText'], PDO::PARAM_STR);
$result2->bindValue(11, $row['MColor'], PDO::PARAM_STR);
$result2->bindValue(12, $row['MLink'], PDO::PARAM_INT);
$result2->bindValue(13, $row['MRead'], PDO::PARAM_INT);
$result2->execute();
}
echo "ONSCREENTRAY";*/
////////////////////ONSCREENTRAY[END]////////////////////////////
/*
////////////////////P_DIAGNOSTICS[START]////////////////////////////
$result = $con->prepare("sdfgsdfg TABLE p_diagnostics");
$result->execute();
																								
////////////////////P_DIAGNOSTICS[END]////////////////////////////

////////////////////P_FAMILY[START]////////////////////////////
$result = $con->prepare("sdfgsdfg TABLE p_family");
$result->execute();
////////////////////P_FAMILY[END]////////////////////////////

////////////////////P_HABITS[START]////////////////////////////
$result = $con->prepare("sdfgsdfg TABLE p_habits");
$result->execute();
////////////////////P_HABITS[END]////////////////////////////

////////////////////P_IMMUNO[START]////////////////////////////
$result = $con->prepare("sdfgsdfg TABLE p_immuno");
$result->execute();
////////////////////P_IMMUNO[END]////////////////////////////

////////////////////P_MEDICATION[START]////////////////////////////
$result = $con->prepare("sdfgsdfg TABLE p_medication");
$result->execute();
////////////////////P_MEDICATION[END]////////////////////////////
*/
////////////////////PASTDX[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE pastdx");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM pastdx");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO pastdx SET 
IdPatient = ?, IdDX = ?, Name = ?, ICDCode = ?, DateStart = ?, DateStop = ?, del =?");
$result2->bindValue(1, $row['IdPatient'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdDX'], PDO::PARAM_INT);
$result2->bindValue(3, $row['Name'], PDO::PARAM_STR);
$result2->bindValue(4, $row['ICDCode'], PDO::PARAM_STR);
$result2->bindValue(5, $row['DateStart'], PDO::PARAM_STR);
$result2->bindValue(6, $row['DateStop'], PDO::PARAM_STR);
$result2->bindValue(7, $row['del'], PDO::PARAM_INT);
$result2->execute();
}
echo "PASTDX";*/
////////////////////PASTDX[END]////////////////////////////

////////////////////PENDING[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE pending");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM pending");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO pending SET 
idpin = ?, rawimage = ?");
$result2->bindValue(1, $row['idpin'], PDO::PARAM_INT);
$result2->bindValue(2, $row['rawimage'], PDO::PARAM_STR);
$result2->execute();
}
echo "PENDING";*/
////////////////////PENDING[END]////////////////////////////

////////////////////PENDING_AUDIO[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE pending_audio");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM pending_audio");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO pending_audio SET 
Idpin = ?, Audiofilename = ?");
$result2->bindValue(1, $row['Idpin'], PDO::PARAM_INT);
$result2->bindValue(2, $row['Audiofilename'], PDO::PARAM_STR);
$result2->execute();
}
echo "PENDING_AUDIO";*/
////////////////////PENDING_AUDIO[END]////////////////////////////

////////////////////PENDINGPROBES[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE pendingprobes");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM pendingprobes");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO pendingprobes SET 
probeID = ?, requestTime = ?");//, id = ?");
$result2->bindValue(1, $row['probeID'], PDO::PARAM_INT);
$result2->bindValue(2, $row['requestTime'], PDO::PARAM_STR);
$result2->execute();
}
echo "PENDING_PROBES";*/
////////////////////PENDINGPROBES[END]////////////////////////////

////////////////////PRINTREPORT[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE printreport");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM printreport");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO printreport SET 
Identif = ?, emailAut = ?, DueDate = ?, password = ?");
$result2->bindValue(1, $row['Identif'], PDO::PARAM_INT);
$result2->bindValue(2, $row['emailAut'], PDO::PARAM_STR);
$result2->bindValue(3, $row['DueDate'], PDO::PARAM_STR);
$result2->bindValue(4, $row['password'], PDO::PARAM_STR);
$result2->execute();
}
echo "PRINT_REPORT";*/
////////////////////PRINTREPORT[END]////////////////////////////

////////////////////PROBE[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE probe");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM probe");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO probe SET 
probeID = ?, doctorID = ?, patientID = ?, desiredTime = ?, timezone = ?, probeInterval = ?, doctorPermission = ?, patientPermission = ?, emailRequest = ?, phoneRequest = ?, smsRequest = ?, creationDate = ?");
$result2->bindValue(1, $row['probeID'], PDO::PARAM_INT);
$result2->bindValue(2, $row['doctorID'], PDO::PARAM_INT);
$result2->bindValue(3, $row['patientID'], PDO::PARAM_INT);
$result2->bindValue(4, $row['desiredTime'], PDO::PARAM_STR);
$result2->bindValue(5, $row['timezone'], PDO::PARAM_INT);
$result2->bindValue(6, $row['probeInterval'], PDO::PARAM_INT);
$result2->bindValue(7, $row['doctorPermission'], PDO::PARAM_INT);
$result2->bindValue(8, $row['patientPermission'], PDO::PARAM_INT);
$result2->bindValue(9, $row['emailRequest'], PDO::PARAM_INT);
$result2->bindValue(10, $row['phoneRequest'], PDO::PARAM_INT);
$result2->bindValue(11, $row['smsRequest'], PDO::PARAM_INT);
$result2->bindValue(12, $row['creationDate'], PDO::PARAM_STR);
$result2->execute();
}
echo "PROBE";*/
////////////////////PROBE[END]////////////////////////////

////////////////////PROBERESPONSE[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE proberesponse");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM proberesponse");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO proberesponse SET 
probeID = ?, response = ?, responseTime = ?");
$result2->bindValue(1, $row['probeID'], PDO::PARAM_INT);
$result2->bindValue(2, $row['response'], PDO::PARAM_INT);
$result2->bindValue(3, $row['responseTime'], PDO::PARAM_STR);
$result2->execute();
}
echo "PROBE_RESPONSE";*/
////////////////////PROBERESPONSE[END]////////////////////////////

////////////////////PROCESSED_STATUS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE processed_status");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM processed_status");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO processed_status SET 
idpin = ?, status = ?");
$result2->bindValue(1, $row['idpin'], PDO::PARAM_INT);
$result2->bindValue(2, $row['status'], PDO::PARAM_STR);
$result2->execute();
}
echo "PROCESSED_STATUS";*/
////////////////////PROCESSED_STATUS[END]////////////////////////////

////////////////////REFERRAL_STAGE[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE referral_stage");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM referral_stage");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO referral_stage SET 
referral_id = ?, stage = ?, datevisit = ?");
$result2->bindValue(1, $row['referral_id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['stage'], PDO::PARAM_INT);
$result2->bindValue(3, $row['datevisit'], PDO::PARAM_STR);
$result2->execute();
}
echo "REFERRAL_STAGE";*/
////////////////////REFERRAL_STAGE[END]////////////////////////////

////////////////////RESP[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE resp");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM resp");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO resp SET 
Id = ?, Id_seg = ?, IdPregunta = ?, IdAutor = ?, NombreAutor = ?, Texto = ?, Hora = ?, Dia = ?, twitter = ?, esMed = ?, urlimagen = ?");
$result2->bindValue(1, $row['Id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['Id_seg'], PDO::PARAM_INT);
$result2->bindValue(3, $row['IdPregunta'], PDO::PARAM_INT);
$result2->bindValue(4, $row['IdAutor'], PDO::PARAM_INT);
$result2->bindValue(5, $row['NombreAutor'], PDO::PARAM_STR);
$result2->bindValue(6, $row['Texto'], PDO::PARAM_STR);
$result2->bindValue(7, $row['Hora'], PDO::PARAM_STR);
$result2->bindValue(8, $row['Dia'], PDO::PARAM_STR);
$result2->bindValue(9, $row['twitter'], PDO::PARAM_STR);
$result2->bindValue(10, $row['esMed'], PDO::PARAM_INT);
$result2->bindValue(11, $row['urlimagen'], PDO::PARAM_STR);
$result2->execute();
}
echo "RESP";*/
////////////////////RESP[END]////////////////////////////

////////////////////SENTPROBELOG[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE sentprobelog");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM sentprobelog");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO sentprobelog SET 
id = ?, probeID = ?, method = ?, requestTime = ?");//, sid = ?, result = ?, emergency = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['probeID'], PDO::PARAM_INT);
$result2->bindValue(3, $row['method'], PDO::PARAM_INT);
$result2->bindValue(4, $row['requestTime'], PDO::PARAM_STR);
$result2->execute();
}
echo "SENTPROBELOG";*/
////////////////////SENTPROBELOG[END]////////////////////////////

////////////////////SESSION_LOG[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE session_log");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM session_log");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO session_log SET 
id = ?, userid = ?, action = ?, fecha = ?, ip = ?, usertype = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['userid'], PDO::PARAM_INT);
$result2->bindValue(3, $row['action'], PDO::PARAM_STR);
$result2->bindValue(4, $row['fecha'], PDO::PARAM_STR);
$result2->bindValue(5, $row['ip'], PDO::PARAM_STR);
$result2->bindValue(6, $row['usertype'], PDO::PARAM_INT);
$result2->execute();
}
echo "SESSION_LOG";*/
////////////////////SESSION_LOG[END]////////////////////////////
/*
////////////////////TEMPORARY_DOCTOR_ACCESS[START]////////////////////////////
$result = $con->prepare("sdfgsdfg TABLE temporary_doctor_access");
$result->execute();
////////////////////TEMPORARY_DOCTOR_ACCESS[END]////////////////////////////

////////////////////TIMESLOTS[START]////////////////////////////
$result = $con->prepare("sdfgsdfg TABLE timeslots");
$result->execute();
////////////////////TIMESLOTS[END]////////////////////////////
*/
////////////////////TIPOPIN[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE tipopin");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM tipopin");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO tipopin SET 
Id = ?, Group2 = ?, GroupName = ?, Agrup = ?, NombreEng = ?, abreviation = ?, Side = ?, Color = ?, Icon = ?, NombreCorto = ?, NombreCortoEsp = ?, Nombre = ?, Clasificacion = ?");
$result2->bindValue(1, $row['Id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['Group'], PDO::PARAM_INT);
$result2->bindValue(3, $row['GroupName'], PDO::PARAM_STR);
$result2->bindValue(4, $row['Agrup'], PDO::PARAM_INT);
$result2->bindValue(5, $row['NombreEng'], PDO::PARAM_STR);
$result2->bindValue(6, $row['abreviation'], PDO::PARAM_STR);
$result2->bindValue(7, $row['Side'], PDO::PARAM_INT);
$result2->bindValue(8, $row['Color'], PDO::PARAM_STR);
$result2->bindValue(9, $row['Icon'], PDO::PARAM_STR);
$result2->bindValue(10, $row['NombreCorto'], PDO::PARAM_STR);
$result2->bindValue(11, $row['NombreCortoEsp'], PDO::PARAM_STR);
$result2->bindValue(12, $row['Nombre'], PDO::PARAM_STR);
$result2->bindValue(13, $row['Clasificacion'], PDO::PARAM_INT);
$result2->execute();
}
echo "TIPOPIN";*/
////////////////////TIPOPIN[END]////////////////////////////
/*
////////////////////USER_ACTIVITY[START]////////////////////////////
$result = $con->prepare("sdfgsdfg TABLE user_activity");
$result->execute();
////////////////////USER_ACTIVITY[END]////////////////////////////
*/
////////////////////USER_TIMEZONE_CONFIG[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE user_timezone_config");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM user_timezone_config");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO user_timezone_config SET 
userid = ?, timez = ?");
$result2->bindValue(1, $row['userid'], PDO::PARAM_INT);
$result2->bindValue(2, $row['timez'], PDO::PARAM_INT);
$result2->execute();
}
echo "USER_TIMEZONE";*/
////////////////////USER_TIMEZONE_CONFIG[END]////////////////////////////

////////////////////USERLOG[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE userlog");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM userlog");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO userlog SET 
name = ?, idUs = ?, ACCION = ?, Password = ?, date = ?, time = ?, success = ?, mobile = ?, Id = ?");
$result2->bindValue(1, $row['name'], PDO::PARAM_STR);
$result2->bindValue(2, $row['idUs'], PDO::PARAM_INT);
$result2->bindValue(3, $row['ACCION'], PDO::PARAM_STR);
$result2->bindValue(4, $row['Password'], PDO::PARAM_STR);
$result2->bindValue(5, $row['date'], PDO::PARAM_STR);
$result2->bindValue(6, $row['time'], PDO::PARAM_STR);
$result2->bindValue(7, $row['success'], PDO::PARAM_STR);
$result2->bindValue(8, $row['mobile'], PDO::PARAM_INT);
$result2->bindValue(9, $row['Id'], PDO::PARAM_INT);
$result2->execute();
}
echo "USERLOG";*/
////////////////////USERLOG[END]////////////////////////////

////////////////////USUEVENTOS[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE usueventos");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM usueventos");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO usueventos SET 
Id = ?, IdUsu = ?, IdEvento = ?, Nombre = ?");
$result2->bindValue(1, $row['Id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdUsu'], PDO::PARAM_INT);
$result2->bindValue(3, $row['IdEvento'], PDO::PARAM_INT);
$result2->bindValue(4, $row['Nombre'], PDO::PARAM_STR);
$result2->execute();
}
echo "USUEVENTOS";*/
////////////////////USUEVENTOS[END]////////////////////////////

////////////////////VITALIDAD[START]////////////////////////////
//$result = $con->prepare("sdfgsdfg TABLE vitalidad");
//$result->execute();
/*																								
$result = $con2->prepare("SELECT * FROM vitalidad");
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
$result2 = $con->prepare("INSERT INTO vitalidad SET 
id = ?, IdProg = ?, Programa = ?, Fecha = ?");
$result2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result2->bindValue(2, $row['IdProg'], PDO::PARAM_INT);
$result2->bindValue(3, $row['Programa'], PDO::PARAM_STR);
$result2->bindValue(4, $row['Fecha'], PDO::PARAM_STR);
$result2->execute();
}
echo "VITALIDAD";
////////////////////VITALIDAD[END]////////////////////////////
*/


																								
?>