<?php
session_start();
set_time_limit(600);
// This functions copies exactly the code in function CreaTimeline, and replaces the code for the injection of timeline elements with the code for injection of "Reports Stream" html elements from the type desired
// Please look for string marked as ***** CODE VARIATION FROM CREATIMELINE ***** to find the replacements

$ElementDOM = $_GET['ElementDOM'];
$EntryTypegroup = $_GET['EntryTypegroup'];
$Usuario = $_GET['Usuario'];
$MedID = $_GET['MedID'];
$idpins = array();
if(isset($_GET['idpins']))
{
    $idpins = explode("_", $_GET['idpins']);
}
$limit=12;
//$pass = $_SESSION['decrypt'];

$_SESSION['num_report']=0;

/*if(isset($_SESSION['num_report']))
  unset($_SESSION['num_report']);*/

 require("environment_detailForLogin.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


	 $tbl_name="usuarios"; // Table name
		
	 $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

//Changed from getting the password for decryption from session variable to retrieving it from database while implementing the feature of send button in userdashboard.php page
$result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$pass = $row['pass'];

//echo 'reached here';


//If highlighted reports are requested	 
if($EntryTypegroup==5)
{
	$query = $con->prepare("SELECT attachments FROM doctorslinkdoctors where Idpac =? and (IdMED=? or IdMED2=?) ");
	$query->bindValue(1, $Usuario, PDO::PARAM_INT);
	$query->bindValue(2, $MedID, PDO::PARAM_INT);
	$query->bindValue(3, $MedID, PDO::PARAM_INT);
	$result=$query->execute();
	
	$count=0;
	while($row = $query->fetch(PDO::FETCH_ASSOC))
	{
		$attachmentString = $row['attachments'];
		$repID = explode(" ",$attachmentString);
		for($i=0;$i<count($repID);$i++)
		{
			if(strlen($repID[$i])>0 && $repID[$i]!=0)
			{
				$count++;
			}
		}
		
	}
	echo $count;
	return;
}

if($_SESSION['num_report']==0) {

	 
		//$queUsu = $_GET['Usuario'];
		//$queMed = $_GET['IdMed'];
	 $queUsu = $Usuario;
	 $queMed = 0;
	
	 $ReportsDisplayed = 0;

     $sql=$con->prepare("SELECT * FROM usuarios where Identif =?");
	 $sql->bindValue(1, $queUsu, PDO::PARAM_INT);
	 $q = $sql->execute();
	 
     $row = $sql->fetch(PDO::FETCH_ASSOC);
     
     $Name = $row['Name'];
     $Surname = $row['Surname'];
     
     // Meter tipos en un Array
     $sql=$con->prepare("SELECT * FROM tipopin");
     $q = $sql->execute();
     
     $Tipo[0]='N/A';
     while($row = $sql->fetch(PDO::FETCH_ASSOC)){
     	$Tipo[$row['Id']]=$row['NombreEng'];
     	$TipoAB[$row['Id']]=$row['NombreCorto'];
     	$TipoColor[$row['Id']]=$row['Color'];
     	$TipoIcon[$row['Id']]=$row['Icon'];
     	$TipoAgrup[$row['Id']]=$row['Agrup'];
     }

   
     
     $Tipo[999]='N/A';
     // Meter clases en un Array
     $Clase[999]='Episode';
     $Clase[0]='Episode';
     $Clase[1]='Check or Preventive';
     $Clase[2]='Isolated Report';
     $Clase[3]='Drug Data';

	 $email = $row['email'];
     $hash = md5( strtolower( trim( $email ) ) );
	 $avat = 'identicon.php?size=50&hash='.$hash;



	

	
    //$result = mysql_query("SELECT * FROM LifePin WHERE IdUsu='$queUsu' and emr_old=0 ORDER BY Fecha ASC LIMIT ".$limit);
    $result = $con->prepare("SELECT * FROM lifepin WHERE IdUsu=? and emr_old=0 ORDER BY Fecha ASC ");
    $result->bindValue(1, $queUsu, PDO::PARAM_INT);
    $result->execute();

    $numero = $result->rowCount();
    $n=0;


    $inyectHTML = '';

    $isPDF=0;

    if($numero) {
        
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
        
              if ($EntryTypegroup == $TipoAgrup[$row['Tipo']] || $EntryTypegroup == 0 ){
                  if(count($idpins) == 0 || in_array($row['IdPin'], $idpins))
                  {
                        $ReportsDisplayed++; 
                  }
              }
        
        }

          $_SESSION['num_report']= $ReportsDisplayed;
          //echo $ReportsDisplayed;  
          echo '7';

    }
}



?>
