<?php
/*KYLE
session_start();
// This functions copies exactly the code in function CreaTimeline, and replaces the code for the injection of timeline elements with the code for injection of "Reports Stream" html elements from the type desired
// Please look for string marked as ***** CODE VARIATION FROM CREATIMELINE ***** to find the replacements

$ElementDOM = $_GET['ElementDOM'];
$EntryTypegroup = $_GET['EntryTypegroup'];
$Usuario = $_GET['Usuario'];
$MedID = $_GET['MedID'];
$limit=12;
$pass = $_SESSION['decrypt'];
 require("environment_detail.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


/*KYLE
	 $tbl_name="usuarios"; // Table name
		
	 $link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
	 mysql_select_db("$dbname")or die("cannot select DB");	
		
		//$queUsu = $_GET['Usuario'];
		//$queMed = $_GET['IdMed'];
	 $queUsu = $Usuario;
	 $queMed = 0;
	
	 $ReportsDisplayed = 0;

     $sql="SELECT * FROM usuarios where Identif ='$queUsu'";
     $q = mysql_query($sql);
     $row=mysql_fetch_assoc($q);
     
     $Name = $row['Name'];
     $Surname = $row['Surname'];
     
     // Meter tipos en un Array
     $sql="SELECT * FROM tipopin";
     $q = mysql_query($sql);
     
     $Tipo[0]='N/A';
     while($row=mysql_fetch_assoc($q)){
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



$sql_query="select distinct(idDoctor) from doctorsgroups where idDoctor IN (select Idcreator FROM usuarios where Identif='$queUsu') or idGroup IN (select idGroup from doctorsgroups where idDoctor IN (select Idcreator FROM usuarios where Identif='$queUsu'))";
	$res=mysql_query($sql_query);
	
	$privateDoctorID=array();
	$num=0;
	while($rowp=mysql_fetch_assoc($res)){
		$privateDoctorID[$num]=$rowp['idDoctor'];
		$num++;
	}
	
	
$sql_que="select Id from tipopin where Agrup=9";
	$res=mysql_query($sql_que);
	
	$privatetypes=array();
	$num1=0;
	while($rowpr=mysql_fetch_assoc($res)){
		$privatetypes[$num1]=$rowpr['Id'];
		$num1++;
}
	
	$sql1="SELECT Idpin,Tipo FROM lifepin where (markfordelete IS NULL or markfordelete=0) and IdUsu ='$queUsu' and Tipo NOT IN (select Id from tipopin where Agrup=9) and IdMed!=0 and IdMed IS NOT NULL and IdMed!='$MedID' and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor='$MedID')) and IdMed NOT IN (select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu' and estado=2) and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu' and estado=2))) and IdMed NOT IN (select idmed2 from doctorslinkdoctors where idmed='$MedID' and IdPac='$queUsu' and estado=2) and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed2 from doctorslinkdoctors where idmed='$MedID' and IdPac='$queUsu' and estado=2)))";
	$q1=mysql_query($sql1);

	$size=0;
	$size1=0;
	$blindReportId=array();
	$PendingReportID=array();
 
	while($row1=mysql_fetch_assoc($q1)){
		
		$ReportId=$row1['Idpin'];
		$type=$row1['Tipo'];

		$query="SELECT estado from doctorslinkusers where IdMed='$MedID' and IdUs='$queUsu' and Idpin='$ReportId' ";
		$q11=mysql_query($query);
		if($rowes=mysql_fetch_assoc($q11)){
			$estad=$rowes['estado'];
			if($estad==1){
				$PendingReportID[$size1]=$ReportId;
				$size1++;
			}else if($estad==2){
				continue;
			}
		}
		$query1="select estado from doctorslinkdoctors where Idpac IN (select Idusu from lifepin where Idpin='$ReportId') and estado=1";
		$q111=mysql_query($query1);
		if($rowes=mysql_fetch_assoc($q111)){
				 $PendingReportID[$size1]=$ReportId;
				 $size1++;		 
		}else{
			if(!in_array($ReportId,$PendingReportID)){
			$blindReportId[$size]=$ReportId;
			$size++;
			}
		}
		
	}

    $sql_que="SELECT IdPin FROM lifepin WHERE markfordelete=1 and IdUsu='$queUsu'";
	$res=mysql_query($sql_que);
	
	$deletedreports=array();
	$num2=0;
	if($res){
	while($rowpr=mysql_fetch_assoc($res)){
		
		$deletedreports[$num2]=$rowpr['IdPin'];
		$num2++;
	}}else{
	$deletedreports[0]=0;
	}
	

	
//$result = mysql_query("SELECT * FROM lifepin WHERE IdUsu='$queUsu' and emr_old=0 ORDER BY Fecha ASC LIMIT ".$limit);
$result = mysql_query("SELECT * FROM lifepin WHERE IdUsu='$queUsu' and emr_old=0 ORDER BY Fecha ASC ");

$numero=mysql_num_rows($result) ;
$n=0;


$inyectHTML = '';

$isPDF=0;
while ($row = mysql_fetch_array($result))
{    
if ($EntryTypegroup == $TipoAgrup[$row['Tipo']] || $EntryTypegroup == 0 )
{
	$extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
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
		  }

if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};
//echo $Tipo[$indi];
//echo $Tipo[$indi];

//if (!$row['EvRuPunt']){$indi2 = 999;}else{$indi2 = $row['EvRuPunt'];}; 

     $Evento = $row['Evento'];
     $sqlE="SELECT * FROM usueventos where IdUsu ='$queUsu' and IdEvento ='$Evento' ";
     $qE = mysql_query($sqlE);
     $rowE=mysql_fetch_assoc($qE);
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
/*KYLE
$idp=$row['IdPin'];
$tp=$row['Tipo'];
$ip=$row['IsPrivate'];

if((in_array($tp,$privatetypes))||($ip==1)) {
	//$sql_p="select distinct(idDoctor) from doctorsgroups where idGroup IN(select idGroup from doctorsgroups where idDoctor IN (select idcreator from lifepin where idpin='$idp')) or idDoctor IN (select idcreator from lifepin where idpin='$idp')";
	$sql_p="select distinct(idDoctor) from doctorsgroups where idgroup in (select idgroup from doctorsgroups where idDoctor in (select idcreator from lifepin where idpin='$idp')) UNION (select idcreator from lifepin where idpin='$idp')";
	$q1=mysql_query($sql_p);
	if($q1){
	while($row11 = mysql_fetch_array($q1)){
	 if($row11['idDoctor']==$MedID){
		$hasPrivateAccess=true;
		break;
	 }
	}}
if(!$hasPrivateAccess){
	continue;
}
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
$sql_r="select distinct(idDoctor) from doctorsgroups where idgroup in (select idgroup from doctorsgroups where idDoctor in (select idcreator from lifepin where idpin='$idp')) UNION (select idcreator from lifepin where idpin='$idp')";
$q11=mysql_query($sql_r);
	//global $hasReadWriteAccess;
	while($row11 = mysql_fetch_array($q11)){
	 if($row11['idDoctor']==$MedID){
		$hasReadWriteAccess=1;  //This means user has read/write access
		//echo "<br>Read".$hasReadWriteAccess."</br>";
		break;
	 }
	}







//Read Access Check --- Ends
//**** HTML INJECTION --------------------

if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};

//$FechaNum = strtotime($row['Fecha']);
//$FechaBien = date ("M j, Y",$FechaNum);

$ReportsDisplayed++;               
/*$inyectHTML .=' 
        
         <div class="note2" id="'.$row['IdPin'].'" style="width:150px; height:250px; float:left; margin-right:10px; margin-bottom:20px; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);">
          <!--<div  id="'.$row['RawImage'].'" style="width:140px; height:180px; overflow: hidden"></div>-->
		    <!--<input type="checkbox" name="numbers[]" class="mc" value="0" id="checkcol"/><label for="checkcol"><span></span></label>-->
          	<div style="border: 1px solid blue; background-color: RGB(242,242,242); height:50px; width:148px; border: 1px solid RGB(223,223,223);">
			    <p style="float:left; height:30px; width:20px; padding:0px; margin:0px; margin-top:10px; margin-left:10px;"><i class="'.$TipoIcon[$indi].'" icon-large" style="color:RGB(111,111,111); font-size: 1.0em;"></i></p>
		  		<p class="queTIP" id="'.substr($Tipo[$indi],0,12).'" style="float:left; height:20px; width:90px; padding:0px; margin:0px; margin-top:0px; margin-left:10px; color:RGB(65,65,65); font-size:12px; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif; overflow: hidden;" >'.substr($Tipo[$indi],0,12).'</p>
		  		<!--<span class="label label-info" style="float:right; margin-top:10px; margin-right:10px; font-size:10px; text-shadow:none; text-decoration:none; font-weight:normal;">P R O</span>-->
		  		<p class="'.$FechaBien.'" style="float:left; margin:0px; margin-top:0px;  margin-left:10px; margin-top:-5px; color:RGB(165,165,165); font-size:9px; font-italic:true; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif;">'.$FechaBien.'</p>		
             		
		  		<span class="label label-info" style="float:right; margin-top:0px; margin-right:10px; font-size:10px; text-shadow:none; text-decoration:none; font-weight:normal; background-color:'.$TipoColor[$indi].'">'.$TipoAB[$indi].'</span>
		 		<p><i id="report-eye'.$row['IdPin'].'" class="icon-eye-open icon-large" style="float:left; margin-left:10px; margin-top:-5px; color:RGB(191,191,191); font-size: 0.8em;"></i></p>
		  		<p><i class="icon-share-alt icon-large" style="float:left; margin-left:10px;  margin-top:-5px; color:RGB(191,191,191); font-size: 0.8em;"></i></p>
				<p><i class="icon-trash icon-large deletebutton" style="float:left; margin-left:10px;  margin-top:-5px; color:RGB(191,191,191); font-size: 0.8em; cursor: pointer;" onmouseover="" ></i></p>
		   	</div>';
          
	 if(!in_array($row['IdPin'], $blindReportId)){
			 	if(!in_array($row['IdPin'], $PendingReportID)){
					if(!in_array($row['IdPin'], $deletedreports)){
                        if($row['isDicom'] == 1)
                        {
                            $inyectHTML .= '<div class="queIMG" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
							$inyectHTML .= '<img src="temp/DICOM_svg.png" alt="'.$hasReadWriteAccess.'" style="margin-top:0px;">'; 
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
							$inyectHTML .= '<img src="'.$selecURL.'" alt="'.$hasReadWriteAccess.'" style="margin-top:0px;">'; 
							$inyectHTML .= '</div>';
						}
					}else{
						$inyectHTML .= '<div class="queDEL" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
						$inyectHTML .= '<img src="'.$selecURL.'" alt="0" style="margin-top:0px;">'; 
						$inyectHTML .= '</div>';
					}
				}else{
					$inyectHTML .= '<div class="quePEN" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
			 		$inyectHTML .= '<img src="'.$domain.'/PackagesTH/lockedfile.png" alt="" style="margin-top:0px;opacity:0.7;">';
			 		$inyectHTML .= '</div>';
				}
			 }else{
			 	
			 		$inyectHTML .= '<div class="queBLD" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
			 		$inyectHTML .= '<img src="'.$domain.'/PackagesTH/lockedfile.png" alt="" style="margin-top:0px;opacity:0.7;">';
			 		$inyectHTML .= '</div>';
			 }
			 
			 $inyectHTML .= '<!-- <img src="'.$selecURL.'" alt="" style="margin-top:0px;"> -->';
			 
			 switch($row['CreatorType'])
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
			}
		 

		$inyectHTML .= '  <div style="border: 1px solid blue; background-color: '.$TipoColor[$indi].'; height:48px; width:148px; border: 1px solid #22aeff;">';

		if (!$row['EvRuPunt']){
			$indi = 999; 
			$salida=$EventoALFA;
		}else{
			$indi = $row['EvRuPunt']; 
			$salida=$Clase[$indi]; 
		}
		 
		$inyectHTML .= ' 
			  <p class="queEVE" id="'.$salida.'" style="text-align:center; margin-top:12px; color:white; font-size:10px;">'.$salida.'</p>
			  <p class="queEVE" id="'.$salida.'" style="text-align:center; margin-top:-15px; color:white; font-size:10px;">'.substr($origin,0,12).substr($hspl,0,15).')</p>
		</div>
            </div>
            <!--<div style="height:250px; float:left; margin-left:-10px; padding-left:0px;"><p class="css-vertical-text" style="font-size:10px; color:grey;">'.$FechaBien.'</p></div>-->

        ';
//**** HTML INJECTION --------------------
*/

}
}



echo $ReportsDisplayed;               

function decrypt_files($rawimage,$queMed,$pass )
{/*
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
	}
	
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
	$filename = 'temp/'.$queMed.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extension;	
	//echo $filename;
	if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";	
	}
	else 
	{
		$out = shell_exec('Decrypt.bat PackagesTH_Encrypted '.$ImageRaiz.'.'.$extension.' '.$queMed.' '.$pass);
		//die($out.' '.$ImageRaiz);
	}

*/
}

?>
