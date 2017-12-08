<?php
session_start();
set_time_limit(180);
// This functions copies exactly the code in function CreaTimeline, and replaces the code for the injection of timeline elements with the code for injection of "Reports Stream" html elements from the type desired
// Please look for string marked as ***** CODE VARIATION FROM CREATIMELINE ***** to find the replacements
error_reporting(1);
$ElementDOM = $_GET['ElementDOM'];
$EntryTypegroup = $_GET['EntryTypegroup'];
$Usuario = $_GET['Usuario'];
$MedID = $_GET['MedID'];
$limit=10;
$num_reports=$_GET['num_reports'];
$isDoctor = $_GET['isDoctor'];
//$pass = $_SESSION['decrypt'];
 
require("environment_detail.php");

 
 if($num_reports==0)
 {
	echo '<span class="label label-info" style="left:0px; margin-left:450px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#2E64FE">No Data Found</span>';
	return;
 }
 
 
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 $domain = $env_var_db['hardcode'];
 $local = $env_var_db['local'];


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

	if($EntryTypegroup==0)
	 {
		$limitString = ' LIMIT '.$limit;
	 }
	 else
	 {
		if($EntryTypegroup==5)
		{
			$limitString = '';
		}
		else
		{
			$query = $con->prepare("select * from tipopin where agrup=?");
			$query->bindValue(1, $EntryTypegroup, PDO::PARAM_INT);
			$res = $query->execute();
			
			$r = $query->fetch(PDO::FETCH_ASSOC);
			$str = '('.$r['Id'];
			while($r = $query->fetch(PDO::FETCH_ASSOC))
			{
				$str = $str.','.$r['Id'];
			}
			$str=$str.')';
			$limitString = ' AND tipo in '.$str;
		}
	 }





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


//             "media":"'$domaindev'/images/ReportsGeneric.png",


// ***** CODE VARIATION FROM CREATIMELINE *****
/*
$cadena='{
    "timeline":
    {
        "headline":"Health Events",
        "type":"default",
        "text":"<p>User Id.: '.$queUsu.'</p>",
        "asset": {
            "media":"'.$domain.'/images/ReportsGeneric.png", 
            "credit":"(c) health2.me",
            "caption":"Use side arrows for browsing"
        },
        "date": [               
        ';
*/
// ***** CODE VARIATION FROM CREATIMELINE *****



//getting IdPin for blind reports

//$blindReprtId=array();
//$blindReprtId=blindReports($MedID,$queUsu);
//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM BLOCKS ORDER BY FechaInput DESC");

//Commented- Debraj
/*$sql_query=$con->prepare("select distinct(idDoctor) from doctorsgroups where idDoctor IN (select Idcreator from usuarios where Identif=?) or idGroup IN (select idGroup from doctorsgroups where idDoctor IN (select Idcreator from usuarios where Identif=?))");
$sql_query->bindValue(1, $queUsu, PDO::PARAM_INT);
$sql_query->bindValue(2, $queUsu, PDO::PARAM_INT);
$res=$sql_query->execute();
	
	$privateDoctorID=array();
	$num=0;
	while($rowp=$sql_query->fetch(PDO::FETCH_ASSOC)){
		$privateDoctorID[$num]=$rowp['idDoctor'];
		$num++;
	}
    
    */
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
/*$sql1="SELECT Idpin,Tipo FROM LifePin where IdUsu ='$queUsu' and Tipo NOT IN (select Id from tipopin where Agrup=9) and IdMed IS NOT NULL and (IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$MedID'))) 
and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu'))))";
//and IdMed NOT IN (Select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu'))";*/
    //$sql1="SELECT Idpin,Tipo FROM LifePin where (markfordelete IS NULL or markfordelete=0)  and IdUsu ='$queUsu' and Tipo NOT IN (select Id from tipopin where Agrup=9) and IdMed!=0 and IdMed IS NOT NULL and IdMed!='$MedID' and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor='$MedID')) and IdMed NOT IN (select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu' and estado=2) and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu' and estado=2)))";
    $sql1=null;
    $blindReportId=array();
	$PendingReportID=array();
	if($isDoctor == 1)
    {
        
        
	

        if($EntryTypegroup==5)
        {
            $highlightedReports = array();
            $count=0;
            $query = $con->prepare("SELECT attachments FROM doctorslinkdoctors where Idpac =? and (IdMED=? or IdMED2=?) ");
            $query->bindValue(1, $Usuario, PDO::PARAM_INT);
            $query->bindValue(2, $MedID, PDO::PARAM_INT);
            $query->bindValue(3, $MedID, PDO::PARAM_INT);
            $result=$query->execute();

            while($row = $query->fetch(PDO::FETCH_ASSOC))
            {
                $attachmentString = $row['attachments'];
                $repID = explode(" ",$attachmentString);
                for($i=0;$i<count($repID);$i++)
                {
                    if(strlen($repID[$i])>0 && $repID[$i]!=0)
                    {
                        $highlightedReports[$count] = $repID[$i];		
                        $count++;
                    }
                }

            }

        }	 
    
    
    } 
        
        
    //else
   /* {
        $sql1=$con->prepare("SELECT IdPin,Tipo FROM lifepin WHERE IdUsu = ? AND Tipo NOT IN (select Id from tipopin where Agrup=9)");
        $sql1->bindValue(1, $queUsu, PDO::PARAM_INT);
        $q1=$sql1->execute();
    }*/

	
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

$docuserIdPin=array();

$dluquery='';
$hasfullaccess=0;

if($isDoctor==1){
    
    
    $q=	$con->prepare("Select * from doctorslinkusers where IdMed=? and IdUs=?");
            $q->bindValue(1,$MedID, PDO::PARAM_INT);
            $q->bindValue(2, $queUsu, PDO::PARAM_INT);

            
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
                            $i++;
                        }
                }
            }
        
        else{
              $q=$con->prepare("Select IdCreator from usuarios USU INNER JOIN (select distinct(B.idDoctor) from doctorsgroups B INNER JOIN (select A.idGroup,A.idDoctor from doctorsgroups A where A.idDoctor=?) C where B.idGroup=C.idGroup) DG where USU.Identif=? and DG.idDoctor=USU.IdCreator");
              $q->bindValue(1,$MedID, PDO::PARAM_INT);
              $q->bindValue(2,$queUsu, PDO::PARAM_INT);
              $result = $q->execute();
              
              $n=$q->rowCount();
              //echo $n;
              if($n)$hasfullaccess=1;
              
            
        
        }
    
   // if($EntryTypegroup==0)
        //{
            /*$query=$con->prepare("SELECT * FROM lifepin WHERE IdUsu=? and (IdMed=? or IdMed is null) and emr_old=0 ORDER BY Fecha ASC ".$limitString);
            $query->bindValue(1, $queUsu, PDO::PARAM_INT);
            $query->bindValue(2, $MedID, PDO::PARAM_INT);*/
            //echo "LIMIT ".$offset.','.$limitString;
             
            
    
      if($hasfullaccess){
          
        //echo "has access";
          
        //$dluquery="";
        /*$q=$con->prepare("Select * from lifepin where IdUsu=? and (IdMED=0 or IdMED IS NULL) and emr_old=0 ".$tipoString." ORDER BY Fecha DESC LIMIT ".$offset.','.$limit);
         $q->bindValue(1, $queUsu, PDO::PARAM_INT);*/
          
           if($EntryTypegroup==0)
         {
         
                $query=$con->prepare("Select q.* from ((select * from lifepin LP INNER JOIN ((select distinct(B.idDoctor) from doctorsgroups B INNER JOIN (select A.idGroup,A.idDoctor from doctorsgroups A where A.idDoctor=?) C where B.idGroup=C.idGroup) UNION (select DLD.IdMED from doctorslinkdoctors DLD INNER JOIN (select distinct(B.idDoctor) from doctorsgroups B INNER JOIN (select A.idGroup,A.idDoctor from doctorsgroups A where A.idDoctor=?) C where B.idGroup=C.idGroup) DG where DG.idDoctor=DLD.IdMED2 and IdPac=? and estado=2)) SQ WHERE (SQ.idDoctor=LP.IdMED) and LP.IdUsu=? and (LP.markfordelete=0 or LP.markfordelete is null) and (LP.IsPrivate=0 or LP.IsPrivate is null) and NOT (LP.Tipo IN (select Id from tipopin where Agrup=9) and LP.IdMED!=?) and LP.emr_old=0) UNION (Select *, null as idDoctor from lifepin where IdUsu=? and (IdMED=0 or IdMED IS NULL) and emr_old=0)) q ORDER BY q.fecha DESC ".$limitString);

    
            $query->bindValue(1, $MedID, PDO::PARAM_INT);
            $query->bindValue(2, $MedID, PDO::PARAM_INT);
            $query->bindValue(3, $queUsu, PDO::PARAM_INT);
            $query->bindValue(4, $queUsu, PDO::PARAM_INT);
            $query->bindValue(5, $MedID, PDO::PARAM_INT);
            $query->bindValue(6, $queUsu, PDO::PARAM_INT);

           }else{
           
            $query=$con->prepare("Select q.* from ((select * from lifepin LP INNER JOIN ((select distinct(B.idDoctor) from doctorsgroups B INNER JOIN (select A.idGroup,A.idDoctor from doctorsgroups A where A.idDoctor=?) C where B.idGroup=C.idGroup) UNION (select DLD.IdMED from doctorslinkdoctors DLD INNER JOIN (select distinct(B.idDoctor) from doctorsgroups B INNER JOIN (select A.idGroup,A.idDoctor from doctorsgroups A where A.idDoctor=?) C where B.idGroup=C.idGroup) DG where DG.idDoctor=DLD.IdMED2 and IdPac=? and estado=2)) SQ WHERE (SQ.idDoctor=LP.IdMED) and LP.IdUsu=? and (LP.markfordelete=0 or LP.markfordelete is null) and (LP.IsPrivate=0 or LP.IsPrivate is null) and NOT (LP.Tipo IN (select Id from tipopin where Agrup=9) and LP.IdMED!=?) and LP.emr_old=0) UNION (Select *, null as idDoctor from lifepin where IdUsu=? and (IdMED=0 or IdMED IS NULL) and emr_old=0)) q ".$limitString." ORDER BY q.Fecha DESC LIMIT ".$limit);
            
            
            $query->bindValue(1, $MedID, PDO::PARAM_INT);
            $query->bindValue(2, $MedID, PDO::PARAM_INT);
            $query->bindValue(3, $queUsu, PDO::PARAM_INT);
            $query->bindValue(4, $queUsu, PDO::PARAM_INT);
            $query->bindValue(5, $MedID, PDO::PARAM_INT);
            $query->bindValue(6, $queUsu, PDO::PARAM_INT);
           
           
           }
          
      }else{
      
              if($EntryTypegroup==0)
              {
                  
                   $IdPins = implode(",",$docuserIdPin);

            //$in = join(',', array_fill(0, count($docuserIdPin), '?'));

                  $query=$con->prepare("SELECT * FROM lifepin WHERE IdPin IN ($IdPins) and emr_old=0 ORDER BY Fecha DESC ".$limitString);
              
              }else{
              
                     $IdPins = implode(",",$docuserIdPin);

                    $query=$con->prepare("SELECT * FROM lifepin WHERE IdPin IN ($IdPins) and emr_old=0 ".$limitString." ORDER BY Fecha DESC LIMIT ".$limit);
              }
         
           
                  
            
         
        }
      
      
            
        


}else{
    
    if($EntryTypegroup==0)
        {
            $query=$con->prepare("SELECT * FROM lifepin WHERE IdUsu=? and emr_old=0 and NOT (Tipo IN (select Id from tipopin where Agrup=9) and IdMED!=?) ORDER BY Fecha DESC ".$limitString);
        
            $query->bindValue(1, $queUsu, PDO::PARAM_INT);
            $query->bindValue(2, $MedID, PDO::PARAM_INT);
        }
        else
        {
            $query=$con->prepare("SELECT * FROM lifepin WHERE IdUsu=? and emr_old=0 and NOT (Tipo IN (select Id from tipopin where Agrup=9) and IdMED!=?) ".$limitString." ORDER BY Fecha DESC LIMIT ".$limit);
            $query->bindValue(1, $queUsu, PDO::PARAM_INT);
            $query->bindValue(2, $MedID, PDO::PARAM_INT);
        }




}	


//createReport($query,$con);

//function createReport($query,$con){

	
//echo $query;
//if($hasfullaccess) $result = $query->execute();
//else $result =  $query->execute($ids);

$result = $query->execute();
	


$numero=$query->rowCount();
$n=0;

//$inyectHTML = '<div style="width: 100%; height: 250px; margin-top:10px; margin-left:10px;">';

//$inyectHTML = '<div id="ReportStream" style="width:2000px; height:355px; border:solid red;">';
$inyectHTML = '';

$isPDF=0;
while ($row = $query->fetch(PDO::FETCH_ASSOC))
{    

	// Commented by Pallab $extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
    $extensionR = strtolower(end(explode('.', $row['RawImage'])));

	//Commented by pallab $ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
    
    $ImageRaiz = reset(explode('.', $row['RawImage']));
    
/*	Start of commenting - $extensionR = end(explode('.', $row['RawImage'])); //Commented by Pallab during code conflict
//	$extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
	$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4); End of commenting */
//echo $EntryTypegroup.','.$TipoAgrup[$row['Tipo']].','.$row['IdPin'].'<br>';
if ($EntryTypegroup == $TipoAgrup[$row['Tipo']] || $EntryTypegroup == 0 || $EntryTypegroup == 5 )
{

	

	$type=$row['Tipo'];
	if($EntryTypegroup == 5)
	{
	
		if(!in_array($row['IdPin'], $highlightedReports))
		{
		$extensionR2 = strtolower(end(explode('.', $row['RawImage'])));
//		$extensionR2 = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
		$ImageRaiz2 = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
		
		
			continue;
		}
	}	
	
	
	if(!in_array($row['IdPin'], $blindReportId)){
				
		  //For private report functionality
		 /* if(in_array($type,$privatetypes)){
     		if(!in_array($MedID,$privateDoctorID)){
     				continue;
			}
		 }*/
		 if(!in_array($row['IdPin'], $deletedreports)){
		//echo "*********************************".$extensionR;
		  if($extensionR=='wav'){
				
					$selecURL ='Packages/'.$ImageRaiz.'.'.$extensionR;
					$selecURLAMP ='Packages/'.$ImageRaiz.'.'.$extensionR;
				
			}
             else if ($extensionR=='pdf')
            {
				$isPDF=1;
				decrypt_files($row['RawImage'],$MedID,$pass);
				///THERE IS A PROBLEM WITH IMAGERAIZ VARIABLE
				$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				
				$selecURLAMP =$domain.'temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
            }
             else if ($extensionR=='png')
				{
				decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				$selecURLAMP =$domain.'temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
				}
             else if($extensionR=='jpg'|| $extensionR=='JPG' || $extensionR=='jpeg' || $extensionR=='JPEG')
			{
                $extensionR ='jpg';
				decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR;
				//echo $selecURL;
				/*Adding changes to handle jpg files. It seems in some channel the jpg file are getting converted as png
				if(file_exists($domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR)){
					//echo $selecURL;
					//$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				}else{
					$selecURL=$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				}					
				//echo $selecURL; */	
				$selecURLAMP =$domain.'temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
			}
            // Added by Pallab for testing jpeg changes
             
             else if($extensionR=='pdf')
			{
				decrypt_files($row['RawImage'],$MedID,$pass);
				// Commented by Pallab -- replaced $extensionR by png $selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR;
				$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.'png';
                 
                 //echo $selecURL;
				//Adding changes to handle jpg files. It seems in some channel the jpg file are getting converted as png
				//if(file_exists($domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR)){
					//echo $selecURL;
					//$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				//}else{
					$selecURL=$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				//}					
				//echo $selecURL;	
				$selecURLAMP =$domain.'temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
			}// End of Added by Pallab
             else {
			if	( ($row['CANAL']==7||$row['CANAL']==5||$row['CANAL']== null)){
					if($isPDF==0){
						decrypt_files($row['RawImage'],$MedID,$pass);
						$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage'];
						$selecURLAMP =$domain.'temp/'.$MedID.'/Packages_Encrypted/'.$row['RawImage'];
					}
				
			} else {
				if($isPDF==0){
					$subtipo = substr($row['RawImage'], 3 , 2);
					if ($subtipo=='XX')  { decrypt_files($row['RawImage'],$MedID,$pass);$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage']; }
					else 
                    { 
                        
                       //Commented by Pallab $selecURL =$domain.'/eMapLife/PinImageSetTH/'.$row['RawImage']; 
                        $selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR;
                        
                    }
					// COMPROBACIÓN DE EXISTENCIA DEL ARCHIVO (PARA LOS CASOS DE EMAPLIFE iOS o ANDROID QUE TODAVIA NO GENERAN THUMBNAILS Y NO REFERENCIAN AL DIRECTORIO -TH
					$file = $selecURL;
					$file_headers = @get_headers($file);
					if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
						$exists = false;
						$selecURL =$domain.'temp/'.$queUsu.'/PackagesTH_Encrypted/'.$row['RawImage'];
					  }
					  else {
						  $exists = true;
						  }
					}
				}
			}
		}else{
		
            /* 
			$selecURL =$domain.'/images/deletedfile.png';
		    $selecURLAMP =$domain.'/images/deletedfile.png';
            */
		
		}
	}else{
                /*
				 $selecURL =$domain.'/images/lockedfile.png';
				 $selecURLAMP =$domain.'/images/lockedfile.png';
                 */
		  }

if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};
//echo $Tipo[$indi];
//echo $Tipo[$indi];

//if (!$row['EvRuPunt']){$indi2 = 999;}else{$indi2 = $row['EvRuPunt'];}; 

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
     }; 

// ***** CODE VARIATION FROM CREATIMELINE *****
//if ($n>0) $cadena=$cadena.',';
// ***** CODE VARIATION FROM CREATIMELINE *****
$n++;



//$FechaFor =  date('j/n/y H:i:s',strtotime($row['Fecha']));
$FechaFor =  date('n/j/Y H:i:s',strtotime($row['Fecha']));

// ***** CODE VARIATION FROM CREATIMELINE *****

//Report Privacy Check-- Starts
$hasPrivateAccess=false;

$idp=$row['IdPin'];
$tp=$row['Tipo'];
$ip=$row['IsPrivate'];


//Report Privacy Check --Ends
//Read Access Check ---Starts
$hasReadWriteAccess=0;  //This actually means user has only read access

$idp=$row['IdPin'];
//echo " idpin".$idp."</br>";

$sql_r=$con->prepare("(select distinct(B.idDoctor) from doctorsgroups B INNER JOIN (select A.idGroup,A.idDoctor from doctorsgroups A where A.idDoctor in (select idcreator from lifepin where idpin=?)) C where B.idGroup=C.idGroup) UNION (select idcreator from lifepin where idpin=?)");

    /*
$sql_r=$con->prepare("select distinct(idDoctor) from doctorsgroups where idgroup in (select idgroup from doctorsgroups where idDoctor in (select idcreator from lifepin where idpin=?)) UNION (select idcreator from lifepin where idpin=?)");*/
    
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

if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};

$FechaNum = strtotime($row['Fecha']);
$FechaBien = date ("M j, Y",$FechaNum);

$ReportsDisplayed++;               

//In below inyectHTML changed p class="queTIP" id value from $Tipo[$indi],0,12) to $queTIPValue as well the HTML content of the paragraph
$splittedString = explode("(",$Tipo[$indi]);
$queTIPValue = $splittedString[0];
 
    if(!in_array($row['IdPin'], $blindReportId)){
			 	if(!in_array($row['IdPin'], $PendingReportID)){
					if(!in_array($row['IdPin'], $deletedreports)){
                        
                                    $inyectHTML .=' 

                                             <div class="note2" id="'.$row['IdPin'].'" style="width:150px; height:250px;position:relative; float:left; margin-right:10px; margin-bottom:20px; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);">
                                              <!--<div  id="'.$row['RawImage'].'" style="width:140px; height:180px; overflow: hidden"></div>-->
                                                <!--<input type="checkbox" name="numbers[]" class="mc" value="0" id="checkcol"/><label for="checkcol"><span></span></label>-->
                                                <div style="border: 1px solid blue; background-color: RGB(242,242,242); height:50px; width:148px; border: 1px solid RGB(223,223,223);">
                                                    <p style="float:left; height:30px; width:20px; padding:0px; margin-top:10px;margin-left:10 px;"><i class="'.$TipoIcon[$indi].'" icon-large" style="color:RGB(111,111,111); font-size: 1.0em;"></i></p>

                                                    <p class="queTIP" id="'.$queTIPValue.'" style="float:left; height:20px; width:120px; padding:0px; margin:0px; margin-top:0px; margin-left:5px; color:RGB(65,65,65); font-size:12px; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif; overflow: hidden;" >'.$queTIPValue.'</p>
                                                    <!--<span class="label label-info" style="float:right; margin-top:10px; margin-right:10px; font-size:10px; text-shadow:none; text-decoration:none; font-weight:normal;">P R O</span>-->
                                                    <p class="'.$FechaBien.'" style="float:left; margin:0px; margin-top:0px; min-width: 55px; margin-left:10px; margin-top:-5px; color:RGB(165,165,165); font-size:9px; font-italic:true; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif;">'.$FechaBien.'</p>		

                                                    <span class="label label-info" style="float:right; margin-top:0px; margin-right:10px; font-size:10px; text-shadow:none; text-decoration:none; font-weight:normal; background-color:'.$TipoColor[$indi].'">'.$TipoAB[$indi].'</span>
                                                    <p><i id="report-eye'.$row['IdPin'].'" class="icon-eye-open icon-large" style="float:left; margin-left:15px; margin-top:-5px; color:RGB(191,191,191); font-size: 0.8em;"></i></p>
                                                    <p><i class="icon-share-alt icon-large" style="float:left; margin-left:10px;  margin-top:-5px; color:RGB(191,191,191); font-size: 0.8em;"></i></p>
                                                    <p><i class="icon-trash icon-large deletebutton" style="float:left; margin-left:10px;  margin-top:-5px; color:RGB(191,191,191); font-size: 0.8em; cursor: pointer;" onmouseover="" ></i></p>
                                                </div>';
                   
                 
          
	 if(!in_array($row['IdPin'], $blindReportId)){
			 	if(!in_array($row['IdPin'], $PendingReportID)){
					if(!in_array($row['IdPin'], $deletedreports)){
                        if($row['isDicom'] == 1)
                        {
                            $inyectHTML .= '<div class="queIMG" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
							$inyectHTML .= '<img src="/temp/DICOM_svg.png" alt="'.$hasReadWriteAccess.'" style="margin-top:0px;">'; 
							$inyectHTML .= '</div>';
                        }
						if($extensionR=='wav'){
						
							$inyectHTML .= '<div class="queIMG" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
							$inyectHTML .= '<img src="images/audiowav2.ico" alt="'.$hasReadWriteAccess.'" style="margin-top:0px;">';
							$inyectHTML .= '<audio controls style="position:relative;top: -85px;width: 45px;height: 30px;left: 50px;"><source src="'.$selecURL.'" type="audio/wav"> Your browser does not support the audio element.</audio>'; 
							$inyectHTML .= '</div>';
							
						}else if($extensionR=="MOV")
						{
							$inyectHTML .= '<div class="queIMG" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
							$inyectHTML .= '<img src="'.$selecURL.'" alt="'.$hasReadWriteAccess.'" style="margin-top:0px;">';
							//$inyectHTML .= '<audio controls style="width:45px;height:30px"><source src="'.$selecURL.'" type="audio/wav"> Your browser does not support the audio element.</audio>'; 
							//$inyectHTML .='<span style="background:transparent url(play_icon.gif) no-repeat scroll 0pt 50%;cursor:pointer;color:#000000;display:block;height:35px;position:absolute;text-align:center;text-decoration:none;	vertical-align:bottom;	width:34px;	opacity: 0.8;	left: 38px;	top: 68px;"></span>';
							$inyectHTML .='<img src="images/play.png" alt="NA" style="position:relative;left:50px;top:-35px;height:70px;width:70px"/>';
							$inyectHTML .= '</div>';
						}
												
						else{
							$inyectHTML .= '<div class="queIMG" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
							//THERE IS AN ERROR THE WAY THIS IS HANDLED
							$inyectHTML .= '<img src="'.$selecURL.'" alt="'.$hasReadWriteAccess.'" style="margin-top:0px;">'; 
							$inyectHTML .= '</div>';
						}
					}else{
                        /*
						$inyectHTML .= '<div class="queDEL" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
						$inyectHTML .= '<img src="'.$selecURL.'" alt="0" style="margin-top:0px;">'; 
						$inyectHTML .= '</div>'; */
					}
				}else{
                    /*
					$inyectHTML .= '<div class="quePEN" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
			 		$inyectHTML .= '<img src="'.$domain.'/PackagesTH/lockedfile.png" alt="" style="margin-top:0px;opacity:0.7;">';
			 		$inyectHTML .= '</div>';
                    */
				}
			 }else{
			 	
                    /*
			 		$inyectHTML .= '<div class="queBLD" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
			 		$inyectHTML .= '<img src="'.$domain.'/PackagesTH/lockedfile.png" alt="" style="margin-top:0px;opacity:0.7;">';
			 		$inyectHTML .= '</div>';
                    */
			 }
			 
			 //$inyectHTML .= '<!-- <img src="'.$selecURL.'" alt="" style="margin-top:0px;"> -->';
			 
			 switch($row['CreatorType'])
			 {
				 case 1 :$res11 = $con->prepare("select idmedfixedname from doctors where id=?");
						$res11->bindValue(1, $row['IdCreator'], PDO::PARAM_INT);
						$res11->execute();
						$row11 = $res11->fetch(PDO::FETCH_ASSOC);
						$origin = $row11['idmedfixedname'];	
					
						$res22 = $con->prepare("select name from groups where id=(select idgroup from doctorsgroups where idDoctor=? LIMIT 1)");
						$res22->bindValue(1, $row['IdCreator'], PDO::PARAM_INT);
						$res22->execute();
						
						$num_rows = $res22->rowCount();
						if($num_rows)
						{
							$row22=$res22->fetch(PDO::FETCH_ASSOC);
							$hspl = " (".$row22['name'].") ";
						}
						else
						{
							$hspl="";
							}
						break;
				default : $res11 = $con->prepare("select idusfixedname from usuarios where identif=?");
						$res11->bindValue(1, $row['IdCreator'], PDO::PARAM_INT);
						$res11->execute();
						if ($row['IdCreator']) $row11 = $res11->fetch(PDO::FETCH_ASSOC);	
						$origin = $row11['idusfixedname'];
						$hspl="";
						//die("case 2");
						break;
			}
		 

		$inyectHTML .= '  <div style="border: 1px solid blue; background-color: '.$TipoColor[$indi].'; height:48px; width:148px; border: 1px solid #22aeff;">';

		if (!$row['EvRuPunt']){
			$indi = 999; 
			$salida=$EventoALFA;
		}else{
			$indi = $row['EvRuPunt']; 
			$salida=$Clase[$indi]; 
		}
		 
        // Changed below by Pallab in 2nd line of class="queEVE" from substr($origin,0,12) to substr($origin,0,20)
		$inyectHTML .= ' 
			  <p class="queEVE" id="'.$salida.'" style="text-align:center; margin-top:12px; color:white; font-size:10px;">'.$salida.'</p>
			  <p class="queEVE" id="'.$salida.'" style="text-align:center; margin-top:-15px; color:white; font-size:10px;">'.substr($origin,0,20).substr($hspl,0,15).'</p>
		</div>
            </div>
        ';
                        
      }}}
//**** HTML INJECTION --------------------


}
}

//$inyectHTML .= ' </div>';

//


//if($num_reports)
//{
//	$WidthStream = $num_reports * 160 + (count($highlightedReports)*5);
//}
//else
//{
	$WidthStream = ($ReportsDisplayed * 160);// + (count($highlightedReports)*5);
//}
//if($num_reports != $ReportsDisplayed)
if($num_reports > $ReportsDisplayed)
{
    $WidthStream += 60;
}
$PARTIALinyectHTML = $inyectHTML;
$PREinyectHTML = '<div id="ascroll" style="width: '.$WidthStream.'px; height: 260px; margin-top:10px; margin-left:10px;overflow-y:hidden">';
//$PREinyectHTML = '<div id="ascroll" height: 260px; margin-top:10px; margin-left:10px;">';

$inyectHTML = '';
$inyectHTML = $PREinyectHTML.$PARTIALinyectHTML;

//if($num_reports != $ReportsDisplayed)

 if($ReportsDisplayed==0)
 {
	echo '<span class="label label-info" style="left:0px; margin-left:450px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#2E64FE">No Data Found</span>';
	return;
 }

if($num_reports > $ReportsDisplayed)
{
    $inyectHTML .= '<button id="stream_load_indicator" style="margin-left: 15px; background-color: #FFF; border: 0px solid #FFF; border-radius: 7px; outline: 0px; height: 255px; width: 35px; color: #22AEFF; font-size: 22px; pointer: default; display: none;"><i class="icon-spinner icon-2x icon-spin" ></i></button>';
}
$inyectHTML .= '</div>';

echo $inyectHTML;

//}

// ***** CODE VARIATION FROM CREATIMELINE *****

function decrypt_files($rawimage,$queMed,$pass )
{
	$ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
	$extensionR = strtolower(substr($rawimage,strlen($rawimage)-3,3));
	
	/*$filename = $domain.'temp/'.$queMed.'/Packages_Encrypted/'.$rawimage;
	if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";
	}
	else 
	{
		shell_exec('Decrypt.bat Packages_Encrypted '.$rawimage.' '.$queMed .' 2>&1');
		//echo "PDF Generated";	
	}
	*/
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
	$filename = $domain.'temp/'.$queMed.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extension;	
	//echo $filename;
	if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";	
	}
	else 
	{
		$out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in ".$local."PackagesTH_Encrypted/".$ImageRaiz.".".$extension." -out ".$local."temp/".$queMed."/PackagesTH_Encrypted/".$ImageRaiz.".".$extension);
		//die($out.' '.$ImageRaiz);
	}


}

?>