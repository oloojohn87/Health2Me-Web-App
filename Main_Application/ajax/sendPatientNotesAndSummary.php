<?php

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

$doctorId = $_GET['doctorId'];
$userId = $_GET['userId'];
$language = $_GET['lang'];


$query = $con->prepare("select * from usuarios where Identif = ?");
$query->bindValue(1, $userId, PDO::PARAM_INT);
$query->execute();

$result = $query->fetch(PDO::FETCH_ASSOC);
$email = $result['email'];
if($email == '')$email = 'unknown@email.com';
$grant_acccess = $result['GrantAccess'];
if($grant_acccess == null) $grant_acccess = 'H2M';
$owner = $result['ownerAcc'];
if(strcmp(substr($email, strlen($email) - 4, 2), "-0") == 0)
{
    $query2 = $con->prepare("select email from usuarios where Identif = ?");
    $query2->bindValue(1, $owner, PDO::PARAM_INT);
    $query2->execute();
    $owner_row = $query2->fetch(PDO::FETCH_ASSOC);
    $email = $owner_row['email'];
}

//Getting the consultationId for the just ended consultation between doctor and patient
$query = $con->prepare("select consultationId,length,cost from consults where patient =? and doctor =? order by datetime desc limit 1");
$query->bindValue(1, $userId, PDO::PARAM_INT);
$query->bindValue(2, $doctorId, PDO::PARAM_INT);
$query->execute();

$result = $query->fetch(PDO::FETCH_ASSOC);
$consultationId = $result['consultationId'];
$length = $result['length'];

$minutes = floor($length / 60);
$seconds = $length % 60;
$dollars = strval(floor($_GET['cost'] / 100));

$cents = strval($_GET['cost'] % 100);
if($cents < 10)
{
    $cents = '0'.$cents;
}
$cost = '$'.$dollars.'.'.$cents;

//Getting the name and surname of the doctor

$query = $con->prepare("select name,surname from doctors where id =?");
$query->bindValue(1, $doctorId, PDO::PARAM_INT);
$query->execute();

$result = $query->fetch(PDO::FETCH_ASSOC);
$Var3 = $result['name']." ".$result['surname'];


$Var8 = 1;

require_once 'lib/swift_required.php';

$Content = '';
if($grant_access == 'HTI-COL' || $grant_access == 'HTI-PR' || $grant_access == 'HTI-SPA' || $grant_access == 'HTI-ECU' || $grant_access == 'HTI-MEX' || $grant_access == 'HTI-CR')
{
    $Content = file_get_contents('templates/sendPatientNotesAndSummary_template_hti_es.html');
    $Content = str_replace("**Var3**",$Var3,$Content);
    $Content = str_replace("**Var8**",$Var8,$Content);
    $Content = str_replace("**Var9**",$consultationId,$Content);
    $Content = str_replace("**Var10**",$domain,$Content);
    $Content = str_replace("**Var1**",$minutes,$Content);
    $Content = str_replace("**Var2**",$seconds,$Content);
    $Content = str_replace("**Var5**",date('F j, Y'),$Content);
    $Content = str_replace("**Var11**",(language == 'th' ? "Su orientación con" : "Your consultation with"),$Content);
    $Content = str_replace("**Var12**",(language == 'th' ? "ha terminado" : "has ended"),$Content);
    $Content = str_replace("**Var13**",(language == 'th' ? "Médico" : "Doctor"),$Content);
    $Content = str_replace("**Var14**",(language == 'th' ? "Duración" : "Length"),$Content);
    $Content = str_replace("**Var15**",(language == 'th' ? "minutos" : "minutes"),$Content);
    $Content = str_replace("**Var16**",(language == 'th' ? "segundos" : "seconds"),$Content);
    $Content = str_replace("**Var17**",(language == 'th' ? "Fecha" : "Date"),$Content);
    $Content = str_replace("**Var18**",(language == 'th' ? "Por favor, mantenga este mensaje en sus archivos. A continuación encontrará un enlace para acceder a las notas y recomendaciones relacionadas con su orientación." : "Please keep this email for your records. Below you will find a link to access the summary and notes related to your consultation."),$Content);
    $Content = str_replace("**Var19**",(language == 'th' ? "Gracias por usar el servicio Llama al Doctor." : "Thank you for using Health2Me!"),$Content);
    $Content = str_replace("**Var20**",(language == 'th' ? "Fecha" : "Date"),$Content);
    $Content = str_replace("**Var21**",(language == 'th' ? "Notas y Resumen" : "Notes and Summary"),$Content);
}
else if($grant_access == 'HTI-RIVA')
{
    $Content = file_get_contents('templates/sendPatientNotesAndSummary_template_riva.html');
    $Content = str_replace("**Var3**",$Var3,$Content);
    $Content = str_replace("**Var8**",$Var8,$Content);
    $Content = str_replace("**Var9**",$consultationId,$Content);
    $Content = str_replace("**Var10**",$domain,$Content);
    $Content = str_replace("**Var1**",$minutes,$Content);
    $Content = str_replace("**Var2**",$seconds,$Content);
    $Content = str_replace("**Var5**",date('F j, Y'),$Content);
    $Content = str_replace("**Var11**",(language == 'th' ? "Su orientación con" : "Your consultation with"),$Content);
    $Content = str_replace("**Var12**",(language == 'th' ? "ha terminado" : "has ended"),$Content);
    $Content = str_replace("**Var13**",(language == 'th' ? "Médico" : "Doctor"),$Content);
    $Content = str_replace("**Var14**",(language == 'th' ? "Duración" : "Length"),$Content);
    $Content = str_replace("**Var15**",(language == 'th' ? "minutos" : "minutes"),$Content);
    $Content = str_replace("**Var16**",(language == 'th' ? "segundos" : "seconds"),$Content);
    $Content = str_replace("**Var17**",(language == 'th' ? "Fecha" : "Date"),$Content);
    $Content = str_replace("**Var18**",(language == 'th' ? "Por favor, mantenga este mensaje en sus archivos. A continuación encontrará un enlace para acceder a las notas y recomendaciones relacionadas con su orientación." : "Please keep this email for your records. Below you will find a link to access the summary and notes related to your consultation."),$Content);
    $Content = str_replace("**Var19**",(language == 'th' ? "Gracias por usar el servicio Llama al Doctor." : "Thank you for using Health2Me!"),$Content);
    $Content = str_replace("**Var20**",(language == 'th' ? "Fecha" : "Date"),$Content);
    $Content = str_replace("**Var21**",(language == 'th' ? "Notas y Resumen" : "Notes and Summary"),$Content);
}
else
{

    $Content = file_get_contents('templates/sendPatientNotesAndSummary_template.html');
    $Content = str_replace("**Var3**",$Var3,$Content);
    $Content = str_replace("**Var8**",$Var8,$Content);
    $Content = str_replace("**Var9**",$consultationId,$Content);
    $Content = str_replace("**Var10**",$domain,$Content);
    $Content = str_replace("**Var1**",$minutes,$Content);
    $Content = str_replace("**Var2**",$seconds,$Content);
    $Content = str_replace("**Var4**",$cost,$Content);
    $Content = str_replace("**Var5**",date('F j, Y'),$Content);
    $Content = str_replace("**Var11**",(language == 'th' ? "Su orientación con" : "Your consultation with"),$Content);
    $Content = str_replace("**Var12**",(language == 'th' ? "ha terminado" : "has ended"),$Content);
    $Content = str_replace("**Var13**",(language == 'th' ? "Médico" : "Doctor"),$Content);
    $Content = str_replace("**Var14**",(language == 'th' ? "Duración" : "Length"),$Content);
    $Content = str_replace("**Var15**",(language == 'th' ? "minutos" : "minutes"),$Content);
    $Content = str_replace("**Var16**",(language == 'th' ? "segundos" : "seconds"),$Content);
    $Content = str_replace("**Var17**",(language == 'th' ? "Fecha" : "Date"),$Content);
    $Content = str_replace("**Var18**",(language == 'th' ? "Por favor, mantenga este mensaje en sus archivos. A continuación encontrará un enlace para acceder a las notas y recomendaciones relacionadas con su orientación." : "Please keep this email for your records. Below you will find a link to access the summary and notes related to your consultation."),$Content);
    $Content = str_replace("**Var19**",(language == 'th' ? "Gracias por usar el servicio Llama al Doctor." : "Thank you for using Health2Me!"),$Content);
    $Content = str_replace("**Var20**",(language == 'th' ? "Fecha" : "Date"),$Content);
    $Content = str_replace("**Var21**",(language == 'th' ? "Notas y Resumen" : "Notes and Summary"),$Content);
    
}
 
 
 $body = $Content;

 $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('dev.health2me@gmail.com')
  ->setPassword('health2me');

$mailer = Swift_Mailer::newInstance($transporter);


$message = Swift_Message::newInstance()
  ->setSubject('View Your Notes And Summary')
  ->setFrom(array('hub@inmers.us' => 'Dr '.$Var3.' Session Review'))
  ->setTo(array($email))
  ->setBody($body, 'text/html')
  ;

$result = $mailer->send($message);

?>
