<?php

session_start();
$usuario = $_GET['usuario'];
$medid = $_GET['medid'];
$pass = $_SESSION['decrypt'];

$isDoctor=$_GET['isdoctor'];

CreaTimeline($usuario,$medid,$pass,$isDoctor);

function CreaTimeline($Usuario,$MedID,$pass,$isDoctor)
{
 require("environment_detail.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 $local = $env_var_db['hardcode'];
 $local2 = $env_var_db['local'];


	 $tbl_name="usuarios"; // Table name
    
    $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

	 //$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
	 //mysql_select_db("$dbname")or die("cannot select DB");	

		//$queUsu = $_GET['Usuario'];
		//$queMed = $_GET['IdMed'];
	 $queUsu = $Usuario;
	 $queMed = 0;

    
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


	
//             "media":"'$domaindev'/images/ReportsGeneric.png",


$cadena='{
    "timeline":
    {
        "headline":"Health Events",
        "type":"default",
        "text":"<p>User Id.: '.$queUsu.'</p>",
        "asset": {
            "media":"images/ReportsGeneric.png", 
            "credit":"(c) health2.me",
            "caption":"Use side arrows for browsing"
        },
        "date": [               
        ';



//getting IdPin for blind reports

//$blindReprtId=array();
//$blindReprtId=blindReports($MedID,$queUsu);
//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks ORDER BY FechaInput DESC");

/*$sql_query="select distinct(idDoctor) from doctorsgroups where idDoctor IN (select Idcreator FROM usuarios where Identif='$queUsu') or idGroup IN (select idGroup from doctorsgroups where idDoctor IN (select Idcreator FROM usuarios where Identif='$queUsu'))";*/
    
  /*  $sql_query="select distinct(idDoctor) from doctorsgroups dg INNER JOIN (select dd.idGroup from doctorsgroups dd where dd.idDoctor IN (select IdCreator from usuarios where Identif='$queUsu')) docgrp where dg.idGroup=docgrp.idGroup";
    
	$res=mysql_query($sql_query);

	$privateDoctorID=array();
	$num=0;
	while($rowp=mysql_fetch_assoc($res)){
		$privateDoctorID[$num]=$rowp['idDoctor'];
		$num++;
	} */
	/*if($privateDoctorID==null)
		$privateDoctorID[0]=$MedID;*/

    $sql_que=$con->prepare("select Id from tipopin where Agrup=9");
	$res=$sql_que->execute();
	
	$privatetypes=array();
	$num1=0;
    
	while($rowpr=$sql_que->fetch(PDO::FETCH_ASSOC)){
		$privatetypes[$num1]=$rowpr['Id'];
		$num1++;
}

    #####changes for blind report#########
/*$sql1="SELECT Idpin,Tipo FROM lifepin where IdUsu ='$queUsu' and Tipo NOT IN (select Id from tipopin where Agrup=9) and IdMed IS NOT NULL and (IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$MedID'))) 
and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu'))))";
//and IdMed NOT IN (Select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu'))";*/

//Changes for bidirectional permission to view report
    
$blindReportId=array();
$PendingReportID=array();	
$deletedreports=array();

$unlockedIdPins=array();

$docuserIdPin=array();
    
$queUsu=$Usuario;
//$MedID=$MedID;

//Change this to ==
if($isDoctor==1){
    
     // Check if the doctor is the creator of the patient by checking the creator field
           
                
              $q=$con->prepare("Select IdCreator from usuarios where Identif=? and IdCreator=?");
            
              $q->bindValue(1,$queUsu, PDO::PARAM_INT);
              $q->bindValue(2,$MedID, PDO::PARAM_INT);
                  
             
              $result = $q->execute();
              
              $n=$q->rowCount();
              //echo $n;
              if($n)$hasfullaccess=1;
              
    
              if($hasfullaccess!=1){
                
              $q=$con->prepare("Select IdCreator from usuarios USU INNER JOIN (select distinct(B.idDoctor) from ".$dbname.".doctorsgroups B INNER JOIN (select A.idGroup,A.idDoctor from ".$dbname.".doctorsgroups A where A.idDoctor=?) C where B.idGroup=C.idGroup) DG  where USU.Identif=? and DG.idDoctor=USU.IdCreator");
            
              $q->bindValue(1,$MedID, PDO::PARAM_INT);
              $q->bindValue(2,$MedID, PDO::PARAM_INT);
                  
             
              $result = $q->execute();
              
              $n=$q->rowCount();
              //echo $n;
              if($n)$hasfullaccess=1;
              }
    
              if($hasfullaccess!=1){
              
                $q=$con->prepare("select IdMED2 from ".$dbname.".doctorslinkdoctors where IdMED2=? and IdPac=? and estado=2");
                  
                 $q->bindValue(1,$MedID, PDO::PARAM_INT);
                 $q->bindValue(2,$queUsu, PDO::PARAM_INT);
              
                 $result = $q->execute();
              
                  $n=$q->rowCount();
                  //echo $n;
                  
                if($n)$hasfullaccess=1;
              }
                if($hasfullaccess!=1){
              
                $q=$con->prepare("select IdPin from ".$dbname.".lifepin where IdMed=? and IdUsu=? LIMIT 1");
                  
                 $q->bindValue(1,$MedID, PDO::PARAM_INT);
                 $q->bindValue(2,$queUsu, PDO::PARAM_INT);
              
                 $result = $q->execute();
              
                  $n=$q->rowCount();
                  //echo $n;
                  
                if($n)$hasfullaccess=1;
              }
    
             if($hasfullaccess!=1){
                    //Check the DLU table, if doctorlinkuser has entry for this patient with this doctor
                 $q=$con->prepare("(Select IdPIN from doctorslinkusers DLU INNER JOIN (select distinct(B.idDoctor) from ".$dbname.".doctorsgroups B INNER JOIN (select A.idGroup,A.idDoctor from ".$dbname.".doctorsgroups A where A.idDoctor=?) C where B.idGroup=C.idGroup) DG where DG.idDoctor=DLU.IdMed and DLU.IdUs=?)UNION(Select IdPIN from doctorslinkusers where IdMed=? and IdUs=?)");
                    $q->bindValue(1, $MedID, PDO::PARAM_INT);
                    $q->bindValue(2, $queUsu, PDO::PARAM_INT);
                    $q->bindValue(3,$MedID, PDO::PARAM_INT);
                    $q->bindValue(4, $queUsu, PDO::PARAM_INT);


                    $result = $q->execute();

                    $num=$q->rowCount();
                    $i=0;
                    if($num>0){

                        while($row = $q->fetch(PDO::FETCH_ASSOC)){
                                if($row['IdPIN']==null){
                                    $hasfullaccess=1;
                                    break;

                                }else{
                                    $docuserIdPin[$i]=$row['IdPIN'];
                                    $hasfullaccess=2;
                                    $i++;
                                }

                        }
                    }
             }
                
        
    
    //echo "access: " +$hasfullaccess;

    //This doctor has fullaccess
    if($hasfullaccess==1){
    
             $query=$con->prepare("select q.* from ((select LP.* from ".$dbname.".lifepin LP INNER JOIN ((select A.idDoctor from ".$dbname.".doctorsgroups A INNER JOIN (select idGroup from ".$dbname.".doctorsgroups where idDoctor=?) B where B.idGroup=A.idGroup) UNION (select Id from ".$dbname.".doctors where Id=?) UNION (select IdMED from ".$dbname.".doctorslinkdoctors where IdMED2=? and IdPac=?)) AB where LP.IdMed=AB.idDoctor and IdUsu=? and (LP.markfordelete=0 or LP.markfordelete is null) and (LP.IsPrivate=0 or LP.IsPrivate is null) and NOT (LP.Tipo IN (select Id from ".$dbname.".tipopin where Agrup=9) and LP.IdMED!=?) and LP.emr_old=0 ) UNION (select * from ".$dbname.".lifepin where (IdMED=0 or IdMED IS NULL) and IdUsu=? and (markfordelete=0 or markfordelete is null) and (IsPrivate=0 or IsPrivate is null) and emr_old=0 )) q ORDER BY q.fecha DESC ");
        
            
            $query->bindValue(1, $MedID, PDO::PARAM_INT);
            $query->bindValue(2, $MedID, PDO::PARAM_INT);
            $query->bindValue(3, $MedID, PDO::PARAM_INT);
            $query->bindValue(4, $queUsu, PDO::PARAM_INT);
            $query->bindValue(5, $queUsu, PDO::PARAM_INT);
            $query->bindValue(6, $MedID, PDO::PARAM_INT);
            $query->bindValue(7, $queUsu, PDO::PARAM_INT);
        
            $result = $query->execute();
            $numero=$query->rowCount();
            $i=0;
        
            while ($row = $query->fetch(PDO::FETCH_ASSOC))
            { 
                $unlockedIdPins[$i]=$row['IdPin'];
                $i++;
            }
        
             $IdPins = implode(",",$unlockedIdPins);
             //echo $IdPins;
             $que=$con->prepare("SELECT * FROM lifepin WHERE IdPin IN (".$IdPins.") and emr_old=0 and IdUsu=".$queUsu." ORDER BY Fecha DESC ");
             //$que->bindValue(1, $IdPins, PDO::PARAM_STR);
    
    }else if($hasfullaccess==2){
    
             $IdPins = implode(",",$docuserIdPin);
             $que=$con->prepare("SELECT * FROM lifepin WHERE IdPin IN (".$IdPins.") and emr_old=0 and IdUsu=".$queUsu." ORDER BY Fecha DESC ");
             //$query->bindValue(1, $queUsu, PDO::PARAM_INT);
    }else{
    
        //No access to any files
        
        
    
    
    }
        
        
        
        

}else{

        $que=$con->prepare("SELECT * FROM lifepin WHERE IdUsu=? and (markfordelete=0 or markfordelete is null) and emr_old=0 and Tipo NOT IN (select Id from tipopin where Agrup=9) ORDER BY Fecha DESC ");
        $que->bindValue(1, $queUsu, PDO::PARAM_INT);
        //$que->bindValue(2, $MedID, PDO::PARAM_INT);
}
	



//$result = mysql_query("SELECT * FROM lifepin WHERE IdUsu='$queUsu' and emr_old=0 ORDER BY Fecha DESC ");

$result = $que->execute();

$numero=$que->rowCount();
//$numero=mysql_num_rows($result) ;
$n=0;
$origin='';
while ($row = $que->fetch(PDO::FETCH_ASSOC))
{    
 
	$extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
	$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
	$type=$row['Tipo'];

	if(!in_array($row['IdPin'], $blindReportId)){

		  //For private report functionality
		 /* if(in_array($type,$privatetypes)){
     		if(!in_array($MedID,$privateDoctorID)){
     				continue;
			}
		 }*/

		 if(!in_array($row['IdPin'], $deletedreports)){
		  if ($extensionR!='jpg')
			{
				decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL =$local.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				$selecURLAMP =$local.'temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.$extensionR;
			}
			else {
			if($extensionR == 'jpg')
			{
				decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL =$local.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage'];
				$selecURLAMP =$local.'temp/'.$MedID.'/Packages_Encrypted/'.$row['RawImage'];
			}
			
			else if	($row['CANAL']==7){
				decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL =$local.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage'];
				$selecURLAMP =$local.'temp/'.$MedID.'/Packages_Encrypted/'.$row['RawImage'];
			} else {
				decrypt_files($row['RawImage'],$MedID,$pass);
				$subtipo = substr($row['RawImage'], 3 , 2);
				if ($subtipo=='XX')  {decrypt_files($row['RawImage'],$MedID,$pass); $selecURL =$local.'temp/'.$MedID.'/Packages_Encrypted/'.$row['RawImage']; }
				else { $selecURL =$local.'eMapLife/PinImageSetTH/'.$row['RawImage']; }
				// COMPROBACIÓN DE EXISTENCIA DEL ARCHIVO (PARA LOS CASOS DE EMAPLIFE iOS o ANDROID QUE TODAVIA NO GENERAN THUMBNAILS Y NO REFERENCIAN AL DIRECTORIO -TH
				$file = $selecURL;
				$file_headers = @get_headers($file);
				if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			  	  	$exists = false;
			  	  	$selecURL =$local.'eMapLife/PinImageSet/'.$row['RawImage'];
			  	  }
			  	  else {
				  	  $exists = true;
				  	  }
				}
			}
		}else{
			$selecURL =$local.'images/deletedfile.png';
		    $selecURLAMP =$local.'images/deletedfile.png';
		}
	}
    /*else{
				 $selecURL ='images/lockedfile.png';
				 $selecURLAMP ='images/lockedfile.png';
		  }
 */
if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};
//echo $Tipo[$indi];
//echo $Tipo[$indi];

//if (!$row['EvRuPunt']){$indi2 = 999;}else{$indi2 = $row['EvRuPunt'];}; 

     $Evento = $row['Evento'];
     $sqlE=$con->prepare("SELECT * FROM usueventos where IdUsu = ? && IdEvento = ? ");
	$sqlE->bindValue(1, $queUsu, PDO::PARAM_INT);
	$sqlE->bindValue(2, $Evento, PDO::PARAM_INT);
	$sqlE->execute();
	$rowE = $sqlE->fetch(PDO::FETCH_ASSOC);
     $EventoALFA = $rowE['Nombre'];
     
     if (!$row['EvRuPunt']){
    	 $indi2 = 999; 
    	 $salida=$EventoALFA; 
     }else{
     	$indi2 = $row['EvRuPunt']; 
     	$salida=$Clase[$indi2]; 
     }; 

if ($n>0) $cadena=$cadena.',';
$n++;

switch($row['CreatorType'])
			 {
				 case 1 :$res11 = $con->prepare("select idmedfixedname,idmedemail from doctors where id=?");
						$res11->bindValue(1, $row['IdCreator'], PDO::PARAM_INT);
						$res11->execute();
						$row11 = $res11->fetch(PDO::FETCH_ASSOC);
						$origin = $row11['idmedfixedname'];	
					    $email=$row11['idmedemail'];
						$res22 = $con->prepare("select name from groups where id=(select idgroup from doctorsgroups where idDoctor=? LIMIT 1)");
						$res22->bindValue(1, $row['IdCreator'], PDO::PARAM_INT);
						$res22->execute();
						
						$num_rows = $res22->rowCount();
						if($num_rows)
						{
							$row22=$res22->fetch(PDO::FETCH_ASSOC);
							$hspl = " (".$row22['name']."";
						}
						else
						{
							$hspl="";
				        }
						break;
				default : $res11 = $con->prepare("select idusfixedname,email from usuarios where identif=?");
						$res11->bindValue(1, $row['IdCreator'], PDO::PARAM_INT);
						$res11->execute();
						if ($row['IdCreator']) $row11 = $res11->fetch(PDO::FETCH_ASSOC);	
						$origin = $row11['idusfixedname'];
                        $email = $row11['email'];
						$hspl="";
						//die("case 2");
						break;
			}
		 

//$FechaFor =  date('j/n/y H:i:s',strtotime($row['Fecha']));
$FechaFor =  date('n/j/Y H:i:s',strtotime($row['Fecha']));

$cadena = $cadena.'
            {
                "startDate":"'.$FechaFor.'",
                "endDate":"'.$FechaFor.'",
                "headline":"'.$Tipo[$indi].'",
                "text":"<p>'.$salida.'</p>",
                "tag":"'.$salida.'",
                "asset": {
                    "media":"'.$selecURL.'",
                    "thumbnail":"'.$selecURL.'",
                    "credit":"(r) Author: '.$email.' ('.$origin.')",
                    "caption":""
                    }
            }
';


}

$cadena = $cadena.'
       ],
        "era": [
            {
                "startDate":"2013,12,10",
                "endDate":"2013,12,11",
                "headline":"Inmers Clinical Timeline",
                "text":"<p>Powered by eMapLife</p>"
            }

        ]
    }
}';

$jsondata = json_encode($cadena);

//echo "***********************************************************************************";
//echo $cadena;
//echo "***********************************************************************************";

/*
                "startDate":"'.$row['Fecha'].'",
                "endDate":"'.$row['Fecha'].'",
                "headline":"'.$Tipo[$indi].'",
                "text":"<p>'.$Clase[$indi2].'</p>",
                "tag":"'.$Clase[$indi2].'",
                "asset": {
                    "media":"'.$selecURL.'",
                    "thumbnail":"'.$selecURL.'",
                    "credit":"Credit Name Goes Here",
                    "caption":"Caption text goes here"
                    }

*/

//$cadena = str_replace('\n','',$cadena);
//$cadena = str_replace('\r','',$cadena);
//$cadena = str_replace(' ','',$cadena);

$countfile="jsondata2.txt";
$fp = fopen($countfile, 'w');
fwrite($fp, $cadena);
fclose($fp);
//sleep(5);
}




function decrypt_files($rawimage,$queMed,$pass )
{
	$ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
	$extensionR = substr($rawimage,strlen($rawimage)-3,3);

	/*$filename = 'temp/'.$queMed.'/Packages_Encrypted/'.$rawimage;
	if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";
	}
	else 
	{
		shell_exec('Decrypt.bat Packages_Encrypted '.$rawimage.' '.$queMed .' 2>&1');
		//echo "PDF Generated";	
	}*/

	if($extensionR=='jpg')
	{
		//die("Found JPG Extension");
		$extension='jpg';
		//return;
	}
	else
	{
		$extension='png';
	}
	$filename = $local.'temp/'.$queMed.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extension;	
	//echo $filename;
	if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";	
	}
	else 
	{
		shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in ".$local2."PackagesTH_Encrypted/".$ImageRaiz.".".$extension." -out ".$local2."temp/".$queMed."/PackagesTH_Encrypted/".$ImageRaiz.".".$extension);
		//echo "Thumbnail Generated";
	}


}

?>
