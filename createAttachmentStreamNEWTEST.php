<?php
session_start();
// This is for creating a report attachment view of all the available reports for doctor referrals area. It outputs all the available reports with checkbox on each report to enable the user for selecting the reports.
require("environment_detail.php");
 
$ElementDOM = $_GET['ElementDOM'];
$EntryTypegroup = $_GET['EntryTypegroup'];
$Usuario = $_GET['Usuario'];
$MedID = $_GET['MedID'];
$pass = $_SESSION['decrypt'];
$ignorePrivate = -1;
if(isset($_GET['IGNPR']));
{
    $ignorePrivate = $_GET['IGNPR'];
}
$Reportids=empty($_GET['Reports'])   ? null : $_GET['Reports'];
$notaskingforattachments=false;
$ReportsDisplayed = 0;

if($Reportids==null)
$notaskingforattachments=true;


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
		
		//$queUsu = $_GET['Usuario'];
		//$queMed = $_GET['IdMed'];
	 $queUsu = $Usuario;
	 $queMed = 0;


     $sql = $con->prepare("SELECT * FROM usuarios where Identif = ?");
	 $sql->bindValue(1, $queUsu, PDO::PARAM_INT);
	 
     $q = $sql->execute();
     $row = $sql->fetch(PDO::FETCH_ASSOC);
     
     $Name = $row['Name'];
     $Surname = $row['Surname'];
     
     // Meter tipos en un Array
     $sql = $con->prepare("SELECT * FROM tipopin");
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

	
$blindReportId=array();
$PendingReportID=array();	
$deletedreports=array();
$privatetypes=array();

$docuserIdPin=array();

$dluquery='';
$hasfullaccess=0;

if($notaskingforattachments){

    
     
            // Check if the doctor is the creator of the patient by checking the creator field
           
                
              $q=$con->prepare("Select IdCreator from usuarios where Identif=? and IdCreator=?");
            
              $q->bindValue(1,$queUsu, PDO::PARAM_INT);
              $q->bindValue(2,$MedID, PDO::PARAM_INT);
                  
             
              $result = $q->execute();
              
              $n=$q->rowCount();
              //echo $n;
              if($n)$hasfullaccess=1;
              
    
              if($hasfullaccess!=1){
                
              $q=$con->prepare("Select IdCreator from usuarios USU INNER JOIN (select distinct(B.idDoctor) from doctorsgroups B INNER JOIN (select A.idGroup,A.idDoctor from doctorsgroups A where A.idDoctor=?) C where B.idGroup=C.idGroup) DG where USU.Identif=? and DG.idDoctor=USU.IdCreator");
            
              $q->bindValue(1,$MedID, PDO::PARAM_INT);
              $q->bindValue(2,$MedID, PDO::PARAM_INT);
                  
             
              $result = $q->execute();
              
              $n=$q->rowCount();
              //echo $n;
              if($n)$hasfullaccess=1;
              }
    
              if($hasfullaccess!=1){
              
                $q=$con->prepare("select IdMED2 from doctorslinkdoctors where IdMED2=? and IdPac=? and estado=2");
                  
                 $q->bindValue(1,$MedID, PDO::PARAM_INT);
                 $q->bindValue(2,$queUsu, PDO::PARAM_INT);
              
                 $result = $q->execute();
              
                  $n=$q->rowCount();
                  //echo $n;
                  
                if($n)$hasfullaccess=1;
              }
                /*if($hasfullaccess!=1){
              
                $q=$con->prepare("select IdPin from lifepin where IdMed=? and IdUsu=? LIMIT 1");
                  
                 $q->bindValue(1,$MedID, PDO::PARAM_INT);
                 $q->bindValue(2,$queUsu, PDO::PARAM_INT);
              
                 $result = $q->execute();
              
                  $n=$q->rowCount();
                  //echo $n;
                  
                if($n)$hasfullaccess=1;
              }*/
    
            if($hasfullaccess!=1){
                    //Check the DLU table, if doctorlinkuser has entry for this patient with this doctor
                 //$q=$con->prepare("(Select IdPIN from doctorslinkusers DLU INNER JOIN (select distinct(B.idDoctor) from doctorsgroups B INNER JOIN (select A.idGroup,A.idDoctor from doctorsgroups A where A.idDoctor=?) C where B.idGroup=C.idGroup) DG where DG.idDoctor=DLU.IdMed and DLU.IdUs=?)UNION(Select IdPIN from doctorslinkusers where IdMed=? and IdUs=?)");
                
                $q=$con->prepare("Select IdPIN from doctorslinkusers where IdMed=? and IdUs=?");
                $q->bindValue(1, $MedID, PDO::PARAM_INT);
                $q->bindValue(2, $queUsu, PDO::PARAM_INT);
                //$q->bindValue(3, $MedID, PDO::PARAM_INT);
                //$q->bindValue(4, $queUsu, PDO::PARAM_INT);



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
                
        
   
             
            
      if($hasfullaccess==1){
          
        //echo "has access";
          
            /*
                 $q=$con->prepare("(select LP.* from lifepin LP INNER JOIN ((select A.idDoctor from doctorsgroups A INNER JOIN (select idGroup from doctorsgroups where idDoctor=?) B where B.idGroup=A.idGroup) UNION (select Id from doctors where Id=?) UNION (select IdMED from doctorslinkdoctors where IdMED2=? and IdPac=?)) AB where LP.IdMed=AB.idDoctor and IdUsu=? and (LP.markfordelete=0 or LP.markfordelete is null) and (LP.IsPrivate=0 or LP.IsPrivate is null) and NOT (LP.Tipo IN (select Id from tipopin where Agrup=9) and LP.IdMED!=?) and LP.emr_old=0) UNION (select * from lifepin where (IdMED=0 or IdMED IS NULL) and IdUsu=? and (markfordelete=0 or markfordelete is null) and (IsPrivate=0 or IsPrivate is null) and emr_old=0) UNION (select lp.* from lifepin lp INNER JOIN (select IdPin from doctorslinkusers where IdMED=? and IdUs=? and IdPIN IS NOT NULL) dlu where dlu.IdPIN=lp.IdPin and emr_old=0)");
          

            $q->bindValue(1, $MedID, PDO::PARAM_INT);
            $q->bindValue(2, $MedID, PDO::PARAM_INT);
            $q->bindValue(3, $MedID, PDO::PARAM_INT);
            $q->bindValue(4, $queUsu, PDO::PARAM_INT);
            $q->bindValue(5, $queUsu, PDO::PARAM_INT);
            $q->bindValue(6, $MedID, PDO::PARAM_INT);
            $q->bindValue(7, $queUsu, PDO::PARAM_INT);
            $q->bindValue(8, $MedID, PDO::PARAM_INT);
            $q->bindValue(9, $queUsu, PDO::PARAM_INT);
            
            */
          
          $q=$con->prepare("select q.* from 

                 (
                    (

                             select LP.* from lifepin LP 
                             INNER JOIN 
                             (
                                   (
                                       select A.idDoctor from doctorsgroups A 
                                       INNER JOIN 
                                        (
                                           select idGroup from doctorsgroups where idDoctor=?
                                        ) 
                                       B where B.idGroup=A.idGroup
                                   )

                              UNION 
                                 (
                                     select A.idDoctor from doctorsgroups A 
                                     INNER JOIN 
                                     (
                                        select idGroup from doctorsgroups grp 
                                          INNER JOIN 
                                         (
                                             select IdMED from doctorslinkdoctors where IdMED2=? and IdPac=?
                                          )
                                        rd where grp.idDoctor=rd.IdMed
                                    ) B 
                                    where B.idGroup=A.idGroup
                                 ) 
                             UNION 
                                     (
                                         select Id from doctors where Id=?
                                     ) 
                             UNION 
                                    (
                                     select IdMED from doctorslinkdoctors where IdMED2=? and IdPac=?

                                    )
                             ) 
                             AB where LP.IdMed=AB.idDoctor
                             and IdUsu=? 
                             and 
                             (
                                 LP.markfordelete=0 or LP.markfordelete is null
                             ) 
                             and 
                             (
                                LP.IsPrivate=0 or LP.IsPrivate is null
                             ) 
                             and 
                             NOT 
                             (LP.Tipo IN 
                                (
                                   select Id from tipopin where Agrup=9
                                ) 
                             and LP.IdMED!=?
                             )

                             and LP.emr_old=0

                     ) 
                     UNION 
                     (
                            select * from lifepin where (IdMED=0 or IdMED IS NULL) 

                            and IdUsu=? 
                            and (markfordelete=0 or markfordelete is null) 
                            and (IsPrivate=0 or IsPrivate is null) 
                            and emr_old=0 
                     ) 
                     UNION 
                     (

                             select lp.* from lifepin lp 

                               INNER JOIN 

                             (
                                 select IdPin from doctorslinkusers where IdMED=? and IdUs=? and IdPIN IS NOT NULL

                             ) dlu where dlu.IdPIN=lp.IdPin 

                     )
                 ) 
                 q ORDER BY q.fecha DESC");



            $q->bindValue(1, $MedID, PDO::PARAM_INT);
            $q->bindValue(2, $MedID, PDO::PARAM_INT);
            $q->bindValue(3, $queUsu, PDO::PARAM_INT);
            $q->bindValue(4, $MedID, PDO::PARAM_INT);
            $q->bindValue(5, $MedID, PDO::PARAM_INT);
            $q->bindValue(6, $queUsu, PDO::PARAM_INT);
            $q->bindValue(7, $queUsu, PDO::PARAM_INT);
            $q->bindValue(8, $MedID, PDO::PARAM_INT);
            $q->bindValue(9, $queUsu, PDO::PARAM_INT);
            $q->bindValue(10, $MedID, PDO::PARAM_INT);
            $q->bindValue(11, $queUsu, PDO::PARAM_INT);
          
      }else if($hasfullaccess==2){
      
             
                  
                   $IdPins = implode(",",$docuserIdPin);

                  $q=$con->prepare("SELECT *,distinct(IdPin) FROM lifepin WHERE IdPin IN ($IdPins) and emr_old=0  ORDER BY Fecha DESC");
              
            
           
                  
            
         
        }else{
      
            echo '<span class="label label-info" style="left:0px; margin-left:450px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#2E64FE">No Data Found</span>';
	        return;
      
       }
      
      
$result = $q->execute();


$numero=$q->rowCount();

$n=0;

//echo "numero:".$numero;


$inyectHTML = '';

$isPDF=0;

	

//$inyectHTML = '<div style="width: 500px; height: 500px; margin-top:10px; margin-left:10px;">';


while ($row = $q->fetch(PDO::FETCH_ASSOC))
{    
if ($EntryTypegroup == $TipoAgrup[$row['Tipo']] || $EntryTypegroup == 0 )
{
	$extensionR = strtolower(end(explode('.', $row['RawImage'])));

	$ImageRaiz = reset(explode('.', $row['RawImage']));
	$type=$row['Tipo'];
	if(!in_array($row['IdPin'], $blindReportId)){
				
		  //For private report functionality
		 /* if(in_array($type,$privatetypes)){
     		if(!in_array($MedID,$privateDoctorID)){
     				continue;
			}
		 }*/
		 if(!in_array($row['IdPin'], $deletedreports)){
		
		  if($extensionR=='wav'){				
                $selecURL =$domain.'/Packages/'.$ImageRaiz.'.'.$extensionR;
                $selecURLAMP =$domain.'/Packages/'.$ImageRaiz.'.'.$extensionR;				
          }
             elseif ($extensionR=='pdf')
				{
				$isPDF=1;
				decrypt_files($row['RawImage'],$MedID,$pass,$local);
				$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				$selecURLAMP =$domain.'temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
				}
             elseif ($extensionR=='png')
				{
				decrypt_files($row['RawImage'],$MedID,$pass,$local);
				$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				$selecURLAMP =$domain.'temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
				}
			elseif ($extensionR=='gif')
                {
                    decrypt_files($row['RawImage'],$MedID,$pass,$local);
                    ///THERE IS A PROBLEM WITH IMAGERAIZ VARIABLE
                    $selecURL = $domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.gif';

                    $selecURLAMP = $domain.'temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
                }				
			else if($extensionR=='jpg'|| $extensionR=='JPG' || $extensionR=='jpeg' || $extensionR=='JPEG')
			{
                $extensionR = 'jpg';
				decrypt_files($row['RawImage'],$MedID,$pass,$local);
				$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR;
				//echo $selecURL;
				/*Adding changes to handle jpg files. It seems in some channel the jpg file are getting converted as png
				if(file_exists('temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR)){
					//echo $selecURL;
					//$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				}else{
					$selecURL=$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				}					
				//echo $selecURL;	*/
				$selecURLAMP =$domain.'temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
			}
			else {
			if	($row['CANAL']==7){
				decrypt_files($row['RawImage'],$MedID,$pass,$local);
				$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage'];
				$selecURLAMP =$domain.'temp/'.$MedID.'/Packages_Encrypted/'.$row['RawImage'];
				
			} else {
				$subtipo = substr($row['RawImage'], 3 , 2);
				if ($subtipo=='XX')  { decrypt_files($row['RawImage'],$MedID,$pass,$local);$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage']; }
				else { $selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR; }
				// COMPROBACIÓN DE EXISTENCIA DEL ARCHIVO (PARA LOS CASOS DE EMAPLIFE iOS o ANDROID QUE TODAVIA NO GENERAN THUMBNAILS Y NO REFERENCIAN AL DIRECTORIO -TH
				$file = $selecURL;
				$file_headers = @get_headers($file);
				if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			  	  	$exists = false;
			  	  	$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR;
			  	  }
			  	  else {
				  	  $exists = true;
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
		  }

if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};
//echo $Tipo[$indi];
//echo $Tipo[$indi];

//if (!$row['EvRuPunt']){$indi2 = 999;}else{$indi2 = $row['EvRuPunt'];}; 

     $Evento = $row['Evento'];
     $sqlE=$con->prepare("SELECT * FROM usueventos where IdUsu = ? and IdEvento = ? ");
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
$idp=$row['IdPin'];
$tp=$row['Tipo'];
$ip=$row['IsPrivate'];

if((in_array($tp,$privatetypes))||($ip==1)) {
	//$sql_p="select distinct(idDoctor) from doctorsgroups where idGroup IN(select idGroup from doctorsgroups where idDoctor IN (select idcreator from lifepin where idpin='$idp')) or idDoctor IN (select idcreator from lifepin where idpin='$idp')";
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
        }
    }
/*if(!$hasPrivateAccess){
	continue;
}*/
    continue;
}
//Report Privacy Check --Ends
//Read Access Check ---Starts
$hasReadWriteAccess=0;  //This actually means user has only read access
//$sql_r="select Idmed2 from doctorslinkdoctors where (Idmed2 IN (select idcreator from lifepin where idpin='$idp') and IdPac IN (select idusu from lifepin where idpin='$idp') and estado=2";
//or Idmed IN (select distinct(idDoctor) from doctorsgroups where idgroup in (select idGroup from doctorsgroups where idDoctor in (select idcreator from lifepin where idpin='$idp')))) and IdPac IN (select idusu from lifepin where idpin='$idp') and estado=2";
//$sql_r="select distinct(idDoctor) from doctorsgroups where idGroup IN(select idGroup from doctorsgroup where idDoctor IN (select idcreator from lifepin where idpin='$idp')) UNION (select idcreator from lifepin where idpin='$idp')";
//echo "<br>Docid".$MedID;
$idp=$row['IdPin'];
//echo " idpin".$idp."</br>";
$sql_r=$con->prepare("select distinct(idDoctor) from doctorsgroups where idgroup in (select idgroup from doctorsgroups where idDoctor in (select idcreator from lifepin where idpin=?)) UNION (select idcreator from lifepin where idpin=?)");
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
//**** HTML INJECTION ------------------------ **** HTML INJECTION//

if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};

$FechaNum = strtotime($row['Fecha']);
$FechaBien = date ("M j, Y",$FechaNum);

if(!in_array($row['IdPin'], $blindReportId)){
			 	if(!in_array($row['IdPin'], $PendingReportID)){
					if(!in_array($row['IdPin'], $deletedreports)){
						
						if(isset($_GET['display_member']) && ($row['hide_from_member'] == 0 || $row['hide_from_member'] == null)){
							$button_checked = 'checked';
						}else{
							$button_checked = '';
						}
 
$ReportsDisplayed++;
$inyectHTML .=' 
        
         <div class="attachments" id="'.$row['IdPin'].'" style="width:150px; height:250px; float:left; margin-right:10px; margin-bottom:20px; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);">
           	<div style="border: 1px solid blue; background-color: RGB(200, 200, 200); height:50px; width:148px; border: 1px solid RGB(223,223,223);">
			    <p style="float:left; height:30px; width:20px; padding:0px; margin:0px; margin-top:5px; margin-left:10px;"><span><input type="checkbox" name="numbers[]" class="mc" value="0" id="reportcol'.$row['IdPin'].'" '.$button_checked.'/><label for="reportcol'.$row['IdPin'].'"><span></span></label></span><i class="'.$TipoIcon[$indi].'" icon-large" style="color:RGB(111,111,111); font-size: 1.0em;"></i></p>
		  		<p class="queTIP" id="'.substr($Tipo[$indi],0,12).'" style="float:left; height:20px; width:90px; padding:0px; margin:0px; margin-top:0px; margin-left:10px; color:RGB(65,65,65); font-size:12px; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif; overflow: hidden;" >'.substr($Tipo[$indi],0,12).'</p>
		  		<p class="'.$FechaBien.'" style="float:left; margin:0px; margin-top:0px;  margin-left:10px; margin-top:-5px; color:RGB(65,65,65); font-size:9px; font-italic:true; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif;">'.$FechaBien.'</p>		
             		
		  		<span class="label label-info" style="float:right; margin-top:0px; margin-right:10px; font-size:10px; text-shadow:none; text-decoration:none; font-weight:normal; background-color:'.$TipoColor[$indi].'">'.$TipoAB[$indi].'</span>
		 				
		   	</div>';
          
	 
						$inyectHTML .= '<div class="queIMG" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
						$inyectHTML .= '<img src="'.$selecURL.'" alt="'.$hasReadWriteAccess.'" style="margin-top:0px;">'; 
						$inyectHTML .= '</div>';
						 $inyectHTML .= '</div>';
					}else{
						/*$inyectHTML .= '<div class="queDEL" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
						$inyectHTML .= '<img src="'.$selecURL.'" alt="0" style="margin-top:0px;">'; 
						$inyectHTML .= '</div>';*/
					}
				}else{
					/*$inyectHTML .= '<div class="quePEN" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
			 		$inyectHTML .= '<img src="'.$domain.'/PackagesTH/lockedfile.png" alt="" style="margin-top:0px;opacity:0.7;">';
			 		$inyectHTML .= '</div>';*/
				}
			 }else{
			 	
			 		/*$inyectHTML .= '<div class="queBLD" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
			 		$inyectHTML .= '<img src="'.$domain.'/PackagesTH/lockedfile.png" alt="" style="margin-top:0px;opacity:0.7;">';
			 		$inyectHTML .= '</div>';*/
			 }
			 
			// $inyectHTML .= '<!-- <img src="'.$selecURL.'" alt="" style="margin-top:0px;"> -->';
			 
			/* switch($row['CreatorType'])
			 {
				 case 1 :$res11 = mysql_query("select idmedfixedname from doctors where id=".$row['IdCreator']);
						$row11 = mysql_fetch_array($res11);
						$origin = $row11['idmedfixedname'];	
					
						$res22 = mysql_query("select name from groups where id=(select idgroup from doctorsgroups where idDoctor=".$row['IdCreator']." LIMIT 1)");
						$num_rows = mysql_num_rows($res22);
						if($num_rows)
						{
							$row22=mysql_fetch_array($res22);
							$hspl = " (".$row22['name']."";
						}
						else
						{
							$hspl="";
							}
						break;
				default : $res11 = mysql_query("select idusfixedname from usuarios where identif=".$row['IdCreator']);
						if ($row['IdCreator']) $row11 = mysql_fetch_array($res11);	
						$origin = $row11['idusfixedname'];
						$hspl="";
						//die("case 2");
						break;
			}*/
		 

		/*$inyectHTML .= '  <div style="border: 1px solid blue; background-color: '.$TipoColor[$indi].'; height:48px; width:148px; border: 1px solid #22aeff;">';*/

		/*if (!$row['EvRuPunt']){
			$indi = 999; 
			$salida=$EventoALFA;
		}else{
			$indi = $row['EvRuPunt']; 
			$salida=$Clase[$indi]; 
		}*/
		 
		/*$inyectHTML .= ' 
			  <p class="queEVE" id="'.$salida.'" style="text-align:center; margin-top:12px; color:white; font-size:10px;">'.$salida.'</p>
			  <p class="queEVE" id="'.$salida.'" style="text-align:center; margin-top:-15px; color:white; font-size:10px;">'.substr($origin,0,12).substr($hspl,0,15).')</p>
		</div>*/
           // $inyectHTML .= '</div>';
//**** HTML INJECTION --------------------


}
}

//$inyectHTML .= ' </div>';

$WidthStream = $ReportsDisplayed * 165;
$PARTIALinyectHTML = $inyectHTML;
$PREinyectHTML = '<div style="width: '.$WidthStream.'px; height: 270px; margin-top:10px; margin-left:10px;">';

$inyectHTML = '';
$inyectHTML = $PREinyectHTML.$PARTIALinyectHTML;
$inyectHTML .= ' </div>';
echo $inyectHTML;   
    
}else
{

//$report= '(1226,1227)';
$reports=explode(" ", $Reportids);
$count=count($reports);
$i=0;
$inyectHTML = '<div style="width: 500px; height: 500px; margin-top:10px; margin-left:10px;">';
while($count>0)
{
//



$query=$con->prepare('SELECT * FROM lifepin WHERE IdPin=?');
$query->bindValue(1, $reports[$i], PDO::PARAM_INT);
$result = $query->execute();

$numero=$query->rowCount();
$n=0;
$row = $query->fetch(PDO::FETCH_ASSOC);
   
if ($EntryTypegroup == $TipoAgrup[$row['Tipo']] || $EntryTypegroup == 0 )
{
	$extensionR = strtolower(substr($row['RawImage'],strlen($row['RawImage'])-3,3));
	$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
	$type=$row['Tipo'];  
    if ($extensionR=='pdf')
				{
				$isPDF=1;
				decrypt_files($row['RawImage'],$MedID,$pass,$local);
				$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				$selecURLAMP =$domain.'temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
				}
             elseif ($extensionR=='png')
				{
				decrypt_files($row['RawImage'],$MedID,$pass,$local);
				$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				$selecURLAMP =$domain.'temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
				}
			elseif ($extensionR=='gif')
                {
                    decrypt_files($row['RawImage'],$MedID,$pass,$local);
                    ///THERE IS A PROBLEM WITH IMAGERAIZ VARIABLE
                    $selecURL = $domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.gif';

                    $selecURLAMP = $domain.'temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
                }				
			else if($extensionR=='jpg'|| $extensionR=='JPG' || $extensionR=='jpeg' || $extensionR=='JPEG')
			{
                $extensionR = 'jpg';
				decrypt_files($row['RawImage'],$MedID,$pass,$local);
				$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR;
				$selecURLAMP =$domain.'temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
			}
			else {
			if	($row['CANAL']==7){
				decrypt_files($row['RawImage'],$MedID,$pass,$local);
				$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage'];
				$selecURLAMP =$domain.'temp/'.$MedID.'/Packages_Encrypted/'.$row['RawImage'];
				
			} else {
				$subtipo = substr($row['RawImage'], 3 , 2);
				if ($subtipo=='XX')  { decrypt_files($row['RawImage'],$MedID,$pass,$local);$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage']; }
				else { $selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR; }
				// COMPROBACIÓN DE EXISTENCIA DEL ARCHIVO (PARA LOS CASOS DE EMAPLIFE iOS o ANDROID QUE TODAVIA NO GENERAN THUMBNAILS Y NO REFERENCIAN AL DIRECTORIO -TH
				$file = $selecURL;
				$file_headers = @get_headers($file);
				if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			  	  	$exists = false;
			  	  	$selecURL =$domain.'temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR;
			  	  }
			  	  else {
				  	  $exists = true;
				  	  }
				}
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

if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};

$FechaNum = strtotime($row['Fecha']);
$FechaBien = date ("M j, Y",$FechaNum);


$inyectHTML .=' 
        
         <div class="attachments" id="'.$row['IdPin'].'" style="width:150px; height:250px; float:left; margin-right:10px; margin-bottom:20px; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);">
           	<div style="border: 1px solid blue; background-color: RGB(200, 200, 200); height:50px; width:148px; border: 1px solid RGB(223,223,223);">';

if($ignorePrivate == 1)
{
$inyectHTML .='<p style="float:left; height:30px; width:20px; padding:0px; margin:0px; margin-top:5px; margin-left:10px;"><span><input type="checkbox" name="numbers[]" class="mc" value="0" id="reportcol'.$row['IdPin'].'"/><label for="reportcol'.$row['IdPin'].'"><span></span></label></span><i class="'.$TipoIcon[$indi].'" icon-large" style="color:RGB(111,111,111); font-size: 1.0em;"></i></p>';
}
else
{
        
$inyectHTML .=  ' <p style="float:left; height:30px; width:20px; padding:0px; margin:0px; margin-top:10px; margin-left:10px;"><i class="'.$TipoIcon[$indi].'" icon-large" style="color:RGB(111,111,111); font-size: 1.0em;"></i></p>';
}

$inyectHTML .= ' <p class="queTIP" id="'.substr($Tipo[$indi],0,12).'" style="float:left; height:20px; width:90px; padding:0px; margin:0px; margin-top:0px; margin-left:10px; color:RGB(65,65,65); font-size:12px; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif; overflow: hidden;" >'.substr($Tipo[$indi],0,12).'</p>
		  		<p class="'.$FechaBien.'" style="float:left; margin:0px; margin-top:0px;  margin-left:10px; margin-top:-5px; color:RGB(65,65,65); font-size:9px; font-italic:true; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif;">'.$FechaBien.'</p>		
             		
		  		<span class="label label-info" style="float:right; margin-top:0px; margin-right:10px; font-size:10px; text-shadow:none; text-decoration:none; font-weight:normal; background-color:'.$TipoColor[$indi].'">'.$TipoAB[$indi].'</span>
		 				
		   	</div>';
          
	 
						$inyectHTML .= '<div class="queIMG" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
						$inyectHTML .= '<img src="'.$selecURL.'" alt="" style="margin-top:0px;">'; 
						$inyectHTML .= '</div>';
						 $inyectHTML .= '</div>';


		}
	$count=$count-1;
	$i++;
	}
	$inyectHTML .= ' </div>';
    
    
echo $inyectHTML;
    
}




// ***** CODE VARIATION FROM CREATIMELINE *****

function decrypt_files($rawimage,$queMed,$pass,$local )
{
	$ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
	$extensionR = strtolower(end(explode('.', $rawimage)));
	
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
	}
	*/
	if($extensionR=='jpg')
	{
		//die("Found JPG Extension");
		$extension='jpg';
		//return;
	}
    else if($extensionR=='gif')
    {
        $extension='gif';
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
		$out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in ".$local."PackagesTH_Encrypted/".$ImageRaiz.".".$extension." -out ".$local."temp/".$queMed."/PackagesTH_Encrypted/".$ImageRaiz.".".$extension);
		//die($out.' '.$ImageRaiz);
	}


}

?>
