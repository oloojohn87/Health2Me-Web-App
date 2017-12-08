<?
define('INCLUDE_CHECK',1);
require "functions.php";
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


// Passkey that got from link 
$passkey=$_GET['passkey'];
$UserId=$_GET['UserId'];
$origen=$_GET['origen'];

// Retrieve data from table where row that match this passkey 
$sql1=$con->prepare("SELECT * FROM lifepin WHERE confirmcode =?");
$sql1->bindValue(1, $passkey, PDO::PARAM_STR);


$result1=$sql1->execute();

// If successfully queried 
if($result1) {
// Count how many row has this passkey
$count=$sql1->rowCount();
// if found this passkey in our database, retrieve data from table "temp_members_db"
if($count==1){

while($row=$sql1->fetch(PDO::FETCH_ASSOC))
{	
//$q = mysql_query("SELECT * FROM usuarios");
$IdPin = $row['IdPin'];

//Metemos en la tabla BRICK en el campo urlimagen la urlimagen que hemos encontrado
$sqlV=$con->prepare("UPDATE lifepin SET approved = '1' WHERE IdPin = ?");
$sqlV->bindValue(1, $IdPin, PDO::PARAM_INT);


$resultV=$sqlV->execute();
echo "<h1>Thanks!. Your Health Report has been approved for insertion.</h1>";
echo "<h2>You can check your information now at MediBANK website.</h2>";
echo "<br />";
echo '<p><a href="http://www.newmedibank.com">Go to MediBANK</a></p>';

$sqlV=$con->prepare("UPDATE lifepin SET confirmcode = '*****' WHERE IdPin = ?");
$sqlV->bindValue(1, $IdPin, PDO::PARAM_INT);

$resultV=$sqlV->execute();

$mensTw="     User PIN VALIDATION: ".$IdPin." VERIFICADO";
$a = tuitea ("javierv44","SIGNUP CONTROL:".time().$mensTw,"2");

// ENVIO DE CORREO
$Sobre = 'Health Record Insertion Approved by User';
$Body = 'Approving User Id.: '.$UserId.' ';
//$Body .= '<h2>Origin.: '.$fro.'  ('.$froa.')</h2>';
$objetivo=$origen;
$successmail = mail($objetivo, $Sobre, $Body, "From: newMediBANK@gmail.com");
echo "eMail result: ";
echo $successmail;
// ENVIO DE CORREO


// REDIRECCIÓN AUTOMÁTICA
//print "<meta http-equiv=\"refresh\" content=\"0;URL=signuperror.php\">";
}
}
// if not found passkey, display message "Wrong Confirmation code" 
else {
echo "Codigo de Verificacion incorrecto.";
}



}
?>