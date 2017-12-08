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

$_SESSION['num_report']= 0;

/*if(isset($_SESSION['num_report']))
  unset($_SESSION['num_report']);*/

 require("environment_detail.php");

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

if($_SESSION['num_report']== 20) {

	 
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

    /*$sql="SELECT * FROM TipoPin";
     $q = mysql_query($sql);
     
     $Tipo[0]='N/A';
     while($row=mysql_fetch_assoc($q)){
     	$Tipo[$row['Id']]=$row['NombreEng'];
     	$TipoAB[$row['Id']]=$row['NombreCorto'];
     	$TipoColor[$row['Id']]=$row['Color'];
     	$TipoIcon[$row['Id']]=$row['Icon'];
     	$TipoAgrup[$row['Id']]=$row['Agrup'];
     }*/
     
     
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


 /*
 
$sql_query=$con->prepare("select distinct(idDoctor) from doctorsgroups where idDoctor IN (select Idcreator from usuarios where Identif=?) or idGroup IN (select idGroup from doctorsgroups where idDoctor IN (select Idcreator from usuarios where Identif=?))");
$sql_query->bindValue(1, $queUsu, PDO::PARAM_INT);
$sql_query->bindValue(2, $queUsu, PDO::PARAM_INT);
$res=$sql_query->execute();

	
	$privateDoctorID=array();
	$num=0;
	while($rowp = $sql_query->fetch(PDO::FETCH_ASSOC)){
		$privateDoctorID[$num]=$rowp['idDoctor'];
		$num++;
	}
	
	
$sql_que=$con->prepare("select Id from tipopin where Agrup=9");
	$res=$sql_que->execute();
	
	$privatetypes=array();
	$num1=0;
	while($rowpr = $sql_que->fetch(PDO::FETCH_ASSOC)){
		$privatetypes[$num1]=$rowpr['Id'];
		$num1++;
}
	
	$sql1=$con->prepare("SELECT Idpin,Tipo FROM lifepin where (markfordelete IS NULL or markfordelete=0) and IdUsu =
	? and Tipo NOT IN (select Id from tipopin where Agrup=9) and IdMed!=0 and IdMed IS NOT NULL and IdMed!=
	? and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor=
	?)) and IdMed NOT IN (select idmed from doctorslinkdoctors where idmed2=
	? and IdPac=
	? and estado=2) and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2=
	? and IdPac=
	? and estado=2))) and IdMed NOT IN (select idmed2 from doctorslinkdoctors where idmed=
	? and IdPac=
	? and estado=2) and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed2 from doctorslinkdoctors where idmed=
	? and IdPac=
	? and estado=2)))");
	$sql1->bindValue(1, $queUsu, PDO::PARAM_INT);
	$sql1->bindValue(2, $MedID, PDO::PARAM_INT);
	$sql1->bindValue(3, $MedID, PDO::PARAM_INT);
	$sql1->bindValue(4, $MedID, PDO::PARAM_INT);
	$sql1->bindValue(5, $queUsu, PDO::PARAM_INT);
	$sql1->bindValue(6, $MedID, PDO::PARAM_INT);
	$sql1->bindValue(7, $queUsu, PDO::PARAM_INT);
	$sql1->bindValue(8, $MedID, PDO::PARAM_INT);
	$sql1->bindValue(9, $queUsu, PDO::PARAM_INT);
	$sql1->bindValue(10, $MedID, PDO::PARAM_INT);
	$sql1->bindValue(11, $queUsu, PDO::PARAM_INT);
	$q1=$sql1->execute();
	

	$size=0;
	$size1=0;
	$blindReportId=array();
	$PendingReportID=array();
 
	while($row1 = $sql1->fetch(PDO::FETCH_ASSOC)){
		
		$ReportId=$row1['Idpin'];
		$type=$row1['Tipo'];

		$query=$con->prepare("SELECT estado from doctorslinkusers where IdMed=? and IdUs=? and Idpin=? ");
		$query->bindValue(1, $MedID, PDO::PARAM_INT);
		$query->bindValue(2, $queUsu, PDO::PARAM_INT);
		$query->bindValue(3, $ReportId, PDO::PARAM_INT);
		$q11=$query->execute();
		
		if($rowes = $query->fetch(PDO::FETCH_ASSOC)){
			$estad=$rowes['estado'];
			if($estad==1){
				$PendingReportID[$size1]=$ReportId;
				$size1++;
			}else if($estad==2){
				continue;
			}
		}
		$query1=$con->prepare("select estado from doctorslinkdoctors where Idpac IN (select Idusu from lifepin where Idpin=?) and estado=1");
		$query1->bindValue(1, $ReportId, PDO::PARAM_INT);
		$q111=$query1->execute();
		
		if($rowes = $query1->fetch(PDO::FETCH_ASSOC)){
				 $PendingReportID[$size1]=$ReportId;
				 $size1++;		 
		}else{
			if(!in_array($ReportId,$PendingReportID)){
			$blindReportId[$size]=$ReportId;
			$size++;
			}
		}
		
	}

   $sql_que=$con->prepare("SELECT IdPin FROM lifepin WHERE markfordelete=1 and IdUsu=?");
	$sql_que->bindValue(1, $queUsu, PDO::PARAM_INT);
	$res=$sql_que->execute();
	
	$deletedreports=array();
	$num2=0;
	if($res){
	while($rowpr = $sql_que->fetch(PDO::FETCH_ASSOC)){
		
		$deletedreports[$num2]=$rowpr['IdPin'];
		$num2++;
	}}else{
	$deletedreports[0]=0;
	}
    
    */
	

	
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
                  if(count($idpins) == 0 || in_array($row['IdPin'], $idpins)){
                        $ReportsDisplayed++; 
                  }
              }
        
        }

          $_SESSION['num_report']= $ReportsDisplayed;
          echo $ReportsDisplayed;  

    }
}

/* 
while ($row = $result->fetch(PDO::FETCH_ASSOC))
{    
if ($EntryTypegroup == $TipoAgrup[$row['Tipo']] || $EntryTypegroup == 0 )
{
    //commented for to optimise get number of reports-Debraj
   /* $extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
	$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
	$type=$row['Tipo'];
	if(!in_array($row['IdPin'], $blindReportId)){
				
		 if(!in_array($row['IdPin'], $deletedreports)){

		  if($extensionR=='wav'){
				
					$selecURL =$domain.'/Packages/'.$ImageRaiz.'.'.$extensionR;
					$selecURLAMP =$domain.'/Packages/'.$ImageRaiz.'.'.$extensionR;
				
			}else{
				
				if ($extensionR=='pdf')
				{
				$isPDF=1;
				//decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				$selecURLAMP =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
				}
				
			}
			
			if($extensionR=='jpg'|| $extensionR=='JPG')
			{
				//decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR;
				if(file_exists('temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR)){
				}else{
					$selecURL=$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				}					
				//echo $selecURL;	
				$selecURLAMP =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
			}
			else {
			if	( ($row['CANAL']==7||$row['CANAL']==5)){
					if($isPDF==0){
						//decrypt_files($row['RawImage'],$MedID,$pass);
						$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage'];
						$selecURLAMP =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$row['RawImage'];
					}
				
			} else {
				if($isPDF==0){
					$subtipo = substr($row['RawImage'], 3 , 2);
					if ($subtipo=='XX')  { decrypt_files($row['RawImage'],$MedID,$pass);$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage']; }
					else { $selecURL =$domain.'/eMapLife/PinImageSetTH/'.$row['RawImage']; }
					// COMPROBACIÓN DE EXISTENCIA DEL ARCHIVO (PARA LOS CASOS DE EMAPLIFE iOS o ANDROID QUE TODAVIA NO GENERAN THUMBNAILS Y NO REFERENCIAN AL DIRECTORIO -TH
					$file = $selecURL;
					$file_headers = @get_headers($file);
					if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
						$exists = false;
						$selecURL =$domain.'/eMapLife/PinImageSet/'.$row['RawImage'];
					  }
					  else {
						  $exists = true;
						  }
					}
				}
			}
		}else{
		
			$selecURL =$domain.'/images/deletedfile.png';
		    $selecURLAMP =$domain.'/images/deletedfile.png';
		
		}
	}else{
				 $selecURL =$domain.'/images/lockedfile.png';
				 $selecURLAMP =$domain.'/images/lockedfile.png';
		  } */ //commented for to optimise get number of reports-Debraj

//if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];}; //commented for to optimise get number of reports-Debraj
//echo $Tipo[$indi];
//echo $Tipo[$indi];

//if (!$row['EvRuPunt']){$indi2 = 999;}else{$indi2 = $row['EvRuPunt'];}; 

     /*
     $Evento = $row['Evento'];
     $sqlE=$con->prepare("SELECT * FROM usueventos where IdUsu =? and IdEvento =? ");
	 $sqlE->bindValue(1, $queUsu, PDO::PARAM_INT);
	 $sqlE->bindValue(2, $Evento, PDO::PARAM_INT);
	 $qE = $sqlE->execute();
	 
     $rowE = $sqlE->fetch(PDO::FETCH_ASSOC);
     $EventoALFA = $rowE['Nombre'];
     
     if (!$row['EvRuPunt']){
    	 $indi2 = 999; 
    	 $salida=$EventoALFA; 
     }else{
     	$indi2 = $row['EvRuPunt']; 
     	$salida=$Clase[$indi2]; 
     }; */  //commented for to optimise get number of reports-Debraj

// ***** CODE VARIATION FROM CREATIMELINE *****
//if ($n>0) $cadena=$cadena.',';
// ***** CODE VARIATION FROM CREATIMELINE *****

    // $n++; //commented for to optimise get number of reports-Debraj



//$FechaFor =  date('j/n/y H:i:s',strtotime($row['Fecha']));
 // $FechaFor =  date('n/j/Y H:i:s',strtotime($row['Fecha'])); //commented for to optimise get number of reports-Debraj

// ***** CODE VARIATION FROM CREATIMELINE *****

//Report Privacy Check-- Starts
//$hasPrivateAccess=false; //commented for to optimise get number of reports-Debraj
/*if(isset($row['idPin']))
	$idp=$row['idPin'];
else
   $idp=0;
if(isset($row['Tipo']))
	$tp=$row['Tipo'];
else
	$tp=-1;
if(isset($row['IsPrivate']))
	$ip=$row['IsPrivate'];
else
	$ip=-1;*/
//$ip=$row['IsPrivate'];

    /*$idp=$row['IdPin'];
$tp=$row['Tipo'];
$ip=$row['IsPrivate'];*/ //commented for to optimise get number of reports-Debraj

    
/*if((in_array($tp,$privatetypes))||($ip==1)) {
	
	$sql_p=$con->prepare("select distinct(idDoctor) from doctorsgroups where idgroup in (select idgroup from doctorsgroups where idDoctor in (select idcreator from lifepin where idpin=?)) UNION (select idcreator from lifepin where idpin=?)");
	$sql_p->bindValue(1, $idp, PDO::PARAM_INT);
	$sql_p->bindValue(2, $idp, PDO::PARAM_INT);
	$q1=$sql_p->execute();
	
	if($q1){
	while($row11 = $sql_p->fetch(PDO::FETCH_ASSOC)){
	 if($row11['idDoctor']==$MedID){
		$hasPrivateAccess=true;
		break;
	 }
	}}
if(!$hasPrivateAccess){
	continue;
}
}*///commented for to optimise get number of reports-Debraj
    
//Report Privacy Check --Ends
//Read Access Check ---Starts
    
//$hasReadWriteAccess=0;  //This actually means user has only read access //commented for to optimise get number of reports-Debraj
    
    
//$sql_r="select Idmed2 from doctorslinkdoctors where (Idmed2 IN (select idcreator from lifepin where idpin='$idp') and IdPac IN (select idusu from lifepin where idpin='$idp') and estado=2";
//or Idmed IN (select distinct(idDoctor) from doctorsgroups where idgroup in (select idGroup from doctorsgroups where idDoctor in (select idcreator from lifepin where idpin='$idp')))) and IdPac IN (select idusu from lifepin where idpin='$idp') and estado=2";
//$sql_r="select distinct(idDoctor) from doctorsgroups where idGroup IN(select idGroup from doctorsgroup where idDoctor IN (select idcreator from lifepin where idpin='$idp')) UNION (select idcreator from lifepin where idpin='$idp')";
//echo "<br>Docid".$MedID;
// $idp=$row['IdPin']; //commented for to optimise get number of reports-Debraj

//echo " idpin".$idp."</br>";

    /*
$sql_r=$con->prepare("select distinct(idDoctor) from doctorsgroups where idgroup in (select idgroup from doctorsgroups where idDoctor in (select idcreator from lifepin where idpin='$idp')) UNION (select idcreator from lifepin where idpin='$idp')");
$sql_r->bindValue(1, $idp, PDO::PARAM_INT);
$sql_r->bindValue(2, $idp, PDO::PARAM_INT);
$q11=$sql_r->execute();
	//global $hasReadWriteAccess;
	while($row11 = $sql_r->fetch(PDO::FETCH_ASSOC)){
	 if($row11['idDoctor']==$MedID){
		$hasReadWriteAccess=1;  //This means user has read/write access
		//echo "<br>Read".$hasReadWriteAccess."</br>";
		break;
	 }
	}







//Read Access Check --- Ends
//**** HTML INJECTION --------------------

if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];}; */ //commented for to optimise get number of reports-Debraj


//$FechaNum = strtotime($row['Fecha']);
//$FechaBien = date ("M j, Y",$FechaNum);

 //echo 'reportnum:';

//commented for to optimise get number of reports-Debraj
// $ReportsDisplayed++;               


//}
//}


//$_SESSION['num_report']= $ReportsDisplayed;

//echo $ReportsDisplayed;               


?>
