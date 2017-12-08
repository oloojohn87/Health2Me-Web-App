<?php
session_start();
set_time_limit(180);
// This functions copies exactly the code in function CreaTimeline, and replaces the code for the injection of timeline elements with the code for injection of "Reports Stream" html elements from the type desired
// Please look for string marked as ***** CODE VARIATION FROM CREATIMELINE ***** to find the replacements

$ElementDOM = $_GET['ElementDOM'];
$EntryTypegroup = $_GET['EntryTypegroup'];
$Usuario = $_GET['Usuario'];
$MedID = $_GET['MedID'];
$isDoctor = 0;
$offset= $_GET['offset'];
$jump = $_GET['jump'];
//$limit=$offset+12;
$limit=$jump;
$pass = $_SESSION['decrypt'];
 
 
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
		
		
		
	if($EntryTypegroup==0)
	 {
		$tipoString = '';
	 }
	 else
	 {
		if($EntryTypegroup==5)
		{
			$tipoString = '';
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
			$tipoString = ' AND tipo in '.$str;
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

$sql_query=$con->prepare("select distinct(idDoctor) from doctorsgroups where idDoctor IN (select Idcreator from usuarios where Identif='$queUsu') or idGroup IN (select idGroup from doctorsgroups where idDoctor IN (select Idcreator from usuarios where Identif='$queUsu'))");
$sql_query->bindValue(1, $queUsu, PDO::PARAM_INT);
$sql_query->bindValue(2, $queUsu, PDO::PARAM_INT);
$res=$sql_query->execute();
	
	$privateDoctorID=array();
	$num=0;
	while($rowp=$sql_query->fetch(PDO::FETCH_ASSOC)){
		$privateDoctorID[$num]=$rowp['idDoctor'];
		$num++;
	}
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
    $deletedreports=array();
    if($isDoctor == 1)
    {
        $sql1=$con->prepare("SELECT IdPin,Tipo FROM lifepin where (markfordelete IS NULL or markfordelete=0) and IdUsu =
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
        $sql1->execute();
        $q1=$sql1->execute();
        
        //Start Added for doctor - Pallab
        
                        $size=0;
                    $size1=0;
                    //Commented by Pallab
                    //$blindReportId=array();
                    //$PendingReportID=array();

                    while($row1 = $sql1->fetch(PDO::FETCH_ASSOC)){

                        $ReportId=$row1['IdPin'];
                        $type=$row1['Tipo'];
                        /*if($type==null)
                            $type=-1;*/
                        /*if(in_array($type,$privatetypes)){
                            if(!in_array($MedID,$privateDoctorID)){
                                continue;
                            }
                        }*/
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
        
        //End Added for Doctor - Pallab
        
    }
    else
    {
        $sql1=$con->prepare("SELECT IdPin,Tipo FROM lifepin WHERE IdUsu = ? AND Tipo NOT IN (select Id from tipopin where Agrup=9)");
        $sql1->bindValue(1, $queUsu, PDO::PARAM_INT);
        $q1=$sql1->execute();
    }

        $sql_que=$con->prepare("SELECT IdPin FROM lifepin WHERE markfordelete=1 and IdUsu=?");
        $sql_que->bindValue(1, $queUsu, PDO::PARAM_INT);
        $res=$sql_que->execute();

        //$deletedreports=array();
        $num2=0;
        if($res){
        while($rowpr = $sql_que->fetch(PDO::FETCH_ASSOC)){

            $deletedreports[$num2]=$rowpr['IdPin'];
            $num2++;
        }}else{
        $deletedreports[0]=0;
        }

		
	
	
	
$q=	$con->prepare("SELECT * FROM lifepin WHERE IdUsu=? and emr_old=0 ".$tipoString." ORDER BY Fecha ASC LIMIT ".$offset.','.$limit);
$q->bindValue(1, $queUsu, PDO::PARAM_INT);

//echo $q;
//return;

$result = $q->execute();
//$result = mysql_query("SELECT * FROM LifePin WHERE IdUsu='$queUsu' and emr_old=0 ORDER BY Fecha ASC ");

$numero=$q->rowCount();
//echo $numero;
$n=0;

//$inyectHTML = '<div style="width: 100%; height: 250px; margin-top:10px; margin-left:10px;">';

//$inyectHTML = '<div id="ReportStream" style="width:2000px; height:355px; border:solid red;">';
$inyectHTML = '';

$isPDF=0;
while ($row = $q->fetch(PDO::FETCH_ASSOC))
{    
if ($EntryTypegroup == $TipoAgrup[$row['Tipo']] || $EntryTypegroup == 0 || $EntryTypegroup == 5 )
{
	$extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
	$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
	$type=$row['Tipo'];
	
	if($EntryTypegroup == 5)
	{
		if(!in_array($row['IdPin'], $highlightedReports))
		{
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
				
					$selecURL =$domain.'/Packages/'.$ImageRaiz.'.'.$extensionR;
					$selecURLAMP =$domain.'/Packages/'.$ImageRaiz.'.'.$extensionR;
				
			}else{
				
				if ($extensionR=='pdf')
				{
				$isPDF=1;
				decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL =$domain.'/temp/'.$ImageRaiz.'.png';
				$selecURLAMP =$domain.'/temp/'.$ImageRaiz.'.'.$extensionR;
				}
				
			}
			
			if($extensionR=='jpg'|| $extensionR=='JPG')
			{
				decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL =$domain.'/temp/'.$ImageRaiz.'.'.$extensionR;
				//echo $selecURL;
				//Adding changes to handle jpg files. It seems in some channel the jpg file are getting converted as png
				if(file_exists('temp/'.$ImageRaiz.'.'.$extensionR)){
					//echo $selecURL;
					//$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				}else{
					$selecURL=$domain.'/temp/'.$ImageRaiz.'.png';
				}					
				//echo $selecURL;	
				$selecURLAMP =$domain.'/temp/'.$ImageRaiz.'.'.$extensionR;
			}
			else {
			if	( ($row['CANAL']==7||$row['CANAL']==5)){
					if($isPDF==0){
						decrypt_files($row['RawImage'],$MedID,$pass);
						$selecURL =$domain.'/temp/'.$row['RawImage'];
						$selecURLAMP =$domain.'/temp/'.$row['RawImage'];
					}
				
			} else {
				if($isPDF==0){
					$subtipo = substr($row['RawImage'], 3 , 2);
					if ($subtipo=='XX')  { decrypt_files($row['RawImage'],$MedID,$pass);$selecURL =$domain.'/temp/'.$row['RawImage']; }
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

                                     <div class="note2" id="'.$row['IdPin'].'" style="width:150px; height:250px; float:left; margin-right:10px; margin-bottom:20px; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);">
                                      <!--<div  id="'.$row['RawImage'].'" style="width:140px; height:180px; overflow: hidden"></div>-->
                                        <!--<input type="checkbox" name="numbers[]" class="mc" value="0" id="checkcol"/><label for="checkcol"><span></span></label>-->
                                        <div style="border: 1px solid blue; background-color: RGB(242,242,242); height:50px; width:148px; border: 1px solid RGB(223,223,223);">
                                            <p style="float:left; height:30px; width:20px; padding:0px; margin-top:10px;"><i class="'.$TipoIcon[$indi].'" icon-large" style="color:RGB(111,111,111); font-size: 1.0em;"></i></p>
                                            <p class="queTIP" id="'.$queTIPValue.'" style="float:left; height:20px; width:120px; padding:0px; margin:0px; margin-top:0px; margin-left:5px; color:RGB(65,65,65); font-size:12px; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif; overflow: hidden;" >'.$queTIPValue.'</p>
                                            <!--<span class="label label-info" style="float:right; margin-top:10px; margin-right:10px; font-size:10px; text-shadow:none; text-decoration:none; font-weight:normal;">P R O</span>-->
                                            <p class="'.$FechaBien.'" style="float:left; margin:0px; margin-top:0px;  margin-left:10px; margin-top:-5px; color:RGB(165,165,165); font-size:9px; font-italic:true; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif;">'.$FechaBien.'</p>		

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
                        /*
						$inyectHTML .= '<div class="queDEL" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
						$inyectHTML .= '<img src="'.$selecURL.'" alt="0" style="margin-top:0px;">'; 
						$inyectHTML .= '</div>';*/
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
			 		$inyectHTML .= '</div>'; */
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
							$row22 = $res22->fetch(PDO::FETCH_ASSOC);
							$hspl = " (".$row22['name']."";
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
		 
	//IN below queEVE line changed substr($origin,0,12) to (0,20)	
     $inyectHTML .= ' 
			  <p class="queEVE" id="'.$salida.'" style="text-align:center; margin-top:12px; color:white; font-size:10px;">'.$salida.'</p>
			  <p class="queEVE" id="'.$salida.'" style="text-align:center; margin-top:-15px; color:white; font-size:10px;">'.substr($origin,0,20).substr($hspl,0,15).'</p>
		</div>
            </div>
           

        ';
//**** HTML INJECTION --------------------

         }}}

}
}
//
//$inyectHTML .= ' </div>';

//$WidthStream = $ReportsDisplayed * 165;
//$PARTIALinyectHTML = $inyectHTML;
//$PREinyectHTML = '<div id="ascroll" style="width: '.$WidthStream.'px; height: 260px; margin-top:10px; margin-left:10px;">';
//$PREinyectHTML = '<div id="ascroll" height: 260px; margin-top:10px; margin-left:10px;">';

//$inyectHTML = '';
//$inyectHTML = $PREinyectHTML.$PARTIALinyectHTML;
//echo 'here';
echo $inyectHTML;
//echo $ReportsDisplayed;
/*
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
                    "credit":"(r) Author: '.$email.' ('.$Name.' '.$Surname.')",
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

$countfile="jsondata2.txt";
$fp = fopen($countfile, 'w');
fwrite($fp, $cadena);
fclose($fp);
*/
// ***** CODE VARIATION FROM CREATIMELINE *****

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
	$filename = 'temp/'.$queMed.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extension;	
	//echo $filename;
	if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";	
	}
	else 
	{
		$out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in PackagesTH_Encrypted/".$ImageRaiz.".".$extension." -out temp/".$ImageRaiz.".".$extension);
		//die($out.' '.$ImageRaiz);
	}


}

?>
