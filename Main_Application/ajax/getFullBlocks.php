<?php
session_start();
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

$queUsu = $_GET['Usuario'];
$queMed = $_GET['IdMed'];
$NumF = $_GET['NumF'];
$GetP = $_GET['GetP'];
$GetO = $_GET['GetO'];
$order = $_GET['GetOrder'];
$sort = $_GET['GetSort'];
$search= $_GET['GetSearch'];
$currpage=$_GET['Getcurrpage'];
$decrypt=$_GET['decrypt'];
$pass=$_SESSION['decrypt'];


if($search=="")
{
	$searchcriteria=" and ";
	//die("Nothing");
}
else
{
	$searchcriteria = " and idusfixedname like '%".$search."%' and ";
	//die($searchcriteria);
}

if($sort=="desc")
{
	//$img_tag = '<img src="images/descending.png" height="10" width="10"></img>';
	$img_tag = '<i class="icon-long-arrow-down"></i>';
	
	
}
else
{
	//$img_tag = '<img src="images/ascending.png" height="10" width="10"></img>';
	$img_tag = '<i class="icon-long-arrow-up"></i>';
}

switch($order)
{
	case 0 : $ord = "order by idpin ".$sort;
			 $table_head = '<th id="col8" width="18" style="text-align: center;">Next Action</th><th id="col0" width="1" style="text-align: center;background-color:#BDBDBD;" title="Click to Sort by ID">Id '.$img_tag.'</th><th id="col1" width="55" style="text-align: center;">Origin</th><th id="col2" width="20" style="text-align: center;" title="Click to Sort by Report Date">Report Date</th><th id="col3" width="20" style="text-align: center;" title="Click to Sort by Date Arrival">Date Arrival</th><th id="col4" width="20" style="text-align: center;" title="Click to Sort by Patient">Patient</th><th id="col5" width="20" style="text-align: center;" title="Click to Sort by Type">Type</th><th id="col6" width="18" style="text-align: center;">Parsing State</th><th id="col7" width="25" style="text-align: center;" title="Click to Sort by Channel">Channel</th></tr></thead>';
			break;

	case 1 : $ord = "order by fecha ".$sort;
			 $table_head = '<th id="col8" width="18" style="text-align: center;">Next Action</th><th id="col0" width="1" style="text-align: center;" title="Click to Sort by ID">Id</th><th id="col1" width="55" style="text-align: center;background-color:#BDBDBD;" title="Click to Sort by Origin">Origin '.$img_tag.'</th><th id="col2" width="20" style="text-align: center;">Report Date</th><th id="col3" width="20" style="text-align: center;" title="Click to Sort by Date Arrival">Date Arrival</th><th id="col4" width="20" style="text-align: center;" title="Click to Sort by Patient">Patient</th><th id="col5" width="20" style="text-align: center;" title="Click to Sort by Type">Type</th><th id="col6" width="18" style="text-align: center;">Parsing State</th><th id="col7" width="25" style="text-align: center;" title="Click to Sort by Channel">Channel</th></tr></thead>';
			break;

	case 2 : $ord = "order by fechainput ".$sort;
			$table_head = '<th id="col8" width="18" style="text-align: center;">Next Action</th><th id="col0" width="1" style="text-align: center;" title="Click to Sort by ID">Id</th><th id="col1" width="55" style="text-align: center;">Origin</th><th id="col2" width="20" style="text-align: center;background-color:#BDBDBD;" title="Click to Sort by Report Date">Report Date '.$img_tag.'</th><th id="col3" width="20" style="text-align: center;" title="Click to Sort by Date Arrival">Date Arrival</th><th id="col4" width="20" style="text-align: center;" title="Click to Sort by Patient">Patient</th><th id="col5" width="20" style="text-align: center;" title="Click to Sort by Type">Type</th><th id="col6" width="18" style="text-align: center;">Parsing State</th><th id="col7" width="25" style="text-align: center;" title="Click to Sort by Channel">Channel</th></tr></thead>';
			break;

	case 3 : //removed column UserID ... Hence this case will never be used
			 $ord = "order by idusu ".$sort;
			 $table_head = '<th id="col8" width="18" style="text-align: center;">Next Action</th><th id="col0" width="1" style="text-align: center;" title="Click to Sort by ID">Id</th><th id="col1" width="55" style="text-align: center;">Origin</th><th id="col2" width="20" style="text-align: center;" title="Click to Sort by Report Date">Report Date</th><th id="col3" width="20" style="text-align: center;background-color:#BDBDBD;" title="Click to Sort by Date Arrival">Date Arrival '.$img_tag.'</th><th id="col4" width="20" style="text-align: center;" title="Click to Sort by Patient">Patient</th><th id="col5" width="20" style="text-align: center;" title="Click to Sort by Type">Type</th><th id="col6" width="18" style="text-align: center;">Parsing State</th><th id="col7" width="25" style="text-align: center;" title="Click to Sort by Channel">Channel</th></tr></thead>';
			break;
			
	case 4 : $ord = "order by idusfixedname ".$sort;
			$table_head = '<th id="col8" width="18" style="text-align: center;">Next Action</th><th id="col0" width="1" style="text-align: center;" title="Click to Sort by ID">Id</th><th id="col1" width="55" style="text-align: center;">Origin</th><th id="col2" width="20" style="text-align: center;" title="Click to Sort by Report Date">Report Date</th><th id="col3" width="20" style="text-align: center;" title="Click to Sort by Date Arrival">Date Arrival</th><th id="col4" width="20" style="text-align: center;background-color:#BDBDBD;" title="Click to Sort by Patient">Patient '.$img_tag.'</th><th id="col5" width="20" style="text-align: center;" title="Click to Sort by Type">Type</th><th id="col6" width="18" style="text-align: center;">Parsing State</th><th id="col7" width="25" style="text-align: center;" title="Click to Sort by Channel">Channel</th></tr></thead>';
			break;
			
	case 5 : $ord = "order by tipo ".$sort;
			$table_head = '<th id="col8" width="18" style="text-align: center;">Next Action</th><th id="col0" width="1" style="text-align: center;" title="Click to Sort by ID">Id</th><th id="col1" width="55" style="text-align: center;">Origin</th><th id="col2" width="20" style="text-align: center;" title="Click to Sort by Report Date">Report Date</th><th id="col3" width="20" style="text-align: center;" title="Click to Sort by Date Arrival">Date Arrival</th><th id="col4" width="20" style="text-align: center;" title="Click to Sort by Patient">Patient</th><th id="col5" width="20" style="text-align: center;background-color:#BDBDBD;" title="Click to Sort by Type">Type '.$img_tag.'</th><th id="col6" width="18" style="text-align: center;">Parsing State</th><th id="col7" width="25" style="text-align: center;" title="Click to Sort by Channel">Channel</th></tr></thead>';
			break;
	
	case 7 : $ord = "order by canal ".$sort;
			$table_head = '<th id="col8" width="18" style="text-align: center;">Next Action</th><th id="col0" width="1" style="text-align: center;" title="Click to Sort by ID">Id</th><th id="col1" width="55" style="text-align: center;">Origin</th><th id="col2" width="20" style="text-align: center;" title="Click to Sort by Report Date">Report Date</th><th id="col3" width="20" style="text-align: center;" title="Click to Sort by Date Arrival">Date Arrival</th><th id="col4" width="20" style="text-align: center;" title="Click to Sort by Patient">Patient</th><th id="col5" width="20" style="text-align: center;" title="Click to Sort by Type">Type</th><th id="col6" width="18" style="text-align: center;">Parsing State</th><th id="col7" width="25" style="text-align: center;background-color:#BDBDBD;" title="Click to Sort by Channel">Channel '.$img_tag.'</th></tr></thead>';
			break;
			

	
	default : $ord = "order by idpin ".$sort;
			$table_head = '<th id="col8" width="18" style="text-align: center;">Next Action</th><th id="col0" width="1" style="text-align: center; background-color:#BDBDBD;" title="Click to Sort by ID">Id '.$img_tag.'</th><th id="col1" width="55" style="text-align: center;">Origin</th><th id="col2" width="20" style="text-align: center;" title="Click to Sort by Report Date">Report Date</th><th id="col3" width="20" style="text-align: center;" title="Click to Sort by Date Arrival">Date Arrival</th><th id="col4" width="20" style="text-align: center;" title="Click to Sort by Patient">Patient</th><th id="col5" width="20" style="text-align: center;" title="Click to Sort by Type">Type</th><th id="col6" width="18" style="text-align: center;">Parsing State</th><th id="col7" width="25" style="text-align: center;" title="Click to Sort by Channel">Channel</th></tr></thead>';
			break;
}

if ($NumF == 1)
{
	$Limite = 30;
}
else
{
	$Limite = 15;
}
$offset= ($currpage-1)*$Limite;

if ($GetO == 0 and $GetP == 0)
{
	//die("SELECT idpin,idusu,FechaInput,fecha,t.abreviation as nombreeng,suggestedid,date(suggesteddate1) as suggesteddate,canal,idusfixed,idusfixedname,vs,fs,a_s,creatortype,idcreator FROM lifepin l,tipopin t WHERE l.tipo=t.id  $searchcriteria  idpin in (select distinct(idpin) from  lifepin where idmed='$queMed' or idmed in (select iddoctor from doctorsgroups where idgroup in (select idgroup from doctorsgroups where Iddoctor='$queMed')) ) $ord LIMIT $Limite");
	$result = $con->prepare("SELECT idpin,isDicom,rawimage,idusu,FechaInput,fecha,t.abreviation as nombreeng,Color,Icon,suggestedid,date(suggesteddate1) as suggesteddate,canal,idusfixed,idusfixedname,vs,fs,a_s,creatortype,idcreator FROM lifepin l,tipopin t WHERE (markfordelete IS NULL or markfordelete=0) and l.tipo=t.id$searchcriteria idpin in (select distinct(idpin) from  lifepin where idmed=? or idmed in (select iddoctor from doctorsgroups where idgroup in (select idgroup from doctorsgroups where Iddoctor=?)) ) $ord LIMIT $offset,$Limite");
	$result->bindValue(1, $queMed, PDO::PARAM_INT);
	$result->bindValue(2, $queMed, PDO::PARAM_INT);
	$result->execute();
	echo  '<table style="height:200px; overflow-y:hidden; table-layout: fixed;"  class="table table-mod" id="TablaPac">';
}
else if($GetO == 0 and $GetP == 1)
{
	//die("SELECT idpin,idusu,FechaInput,fecha,t.abreviation as nombreeng,suggestedid,date(suggesteddate1) as suggesteddate,canal,idusfixed,idusfixedname,vs,fs,a_s,creatortype,idcreator FROM lifepin l,tipopin t WHERE l.tipo=t.id $searchcriteria idpin in (select distinct(idpin) from  lifepin where idmed='$queMed' or idmed in (select iddoctor from doctorsgroups where idgroup in (select idgroup from doctorsgroups where Iddoctor='$queMed')) )and vs=0 $ord LIMIT $Limite");
	$result = $con->prepare("SELECT idpin,isDicom,rawimage,idusu,FechaInput,fecha,t.abreviation as nombreeng,Color,Icon,suggestedid,date(suggesteddate1) as suggesteddate,canal,idusfixed,idusfixedname,vs,fs,a_s,creatortype,idcreator FROM lifepin l,tipopin t WHERE (markfordelete IS NULL or markfordelete=0) and l.tipo=t.id$searchcriteria idpin in (select distinct(idpin) from  lifepin where idmed=? or idmed in (select iddoctor from doctorsgroups where idgroup in (select idgroup from doctorsgroups where Iddoctor=?)) )and vs=0 $ord LIMIT $offset,$Limite");
	$result->bindValue(1, $queMed, PDO::PARAM_INT);
	$result->bindValue(2, $queMed, PDO::PARAM_INT);
	$result->execute();
	echo  '<table style="height:200px; overflow-y:hidden; table-layout: fixed;"  class="table table-mod" id="TablaPac">';
}
else if($GetO == 1 and $GetP == 0)
{
	//die("SELECT idpin,idusu,FechaInput,fecha,t.abreviation as nombreeng,suggestedid,date(suggesteddate1) as suggesteddate,canal,idusfixed,idusfixedname,vs,fs,a_s,creatortype,idcreator FROM lifepin l,tipopin t WHERE l.tipo=t.id $searchcriteria IdMed='$queMed' $ord LIMIT $Limite");
	$result = $con->prepare("SELECT idpin,isDicom,rawimage,idusu,FechaInput,fecha,t.abreviation as nombreeng,Color,Icon,suggestedid,date(suggesteddate1) as suggesteddate,canal,idusfixed,idusfixedname,vs,fs,a_s,creatortype,idcreator FROM lifepin l,tipopin t WHERE (markfordelete IS NULL or markfordelete=0) and l.tipo=t.id$searchcriteria IdMed=? $ord LIMIT $offset,$Limite");
	$result->bindValue(1, $queMed, PDO::PARAM_INT);
	$result->execute();
	echo  '<table style="height:200px; overflow-y:hidden; table-layout: fixed;"  class="table table-mod" id="TablaPac">';
}
else
{
	//die("SELECT idpin,idusu,FechaInput,fecha,t.abreviation as nombreeng,suggestedid,date(suggesteddate1) as suggesteddate,canal,idusfixed,idusfixedname,vs,fs,a_s,creatortype,idcreator FROM lifepin l,tipopin t WHERE l.tipo=t.id $searchcriteria IdMed='$queMed' AND vs=0 $ord LIMIT $Limite");
	$result = $con->prepare("SELECT idpin,isDicom,rawimage,idusu,FechaInput,fecha,t.abreviation as nombreeng,Color,Icon,suggestedid,date(suggesteddate1) as suggesteddate,canal,idusfixed,idusfixedname,vs,fs,a_s,creatortype,idcreator FROM lifepin l,tipopin t WHERE (markfordelete IS NULL or markfordelete=0) and l.tipo=t.id$searchcriteria IdMed=? AND vs=0 $ord LIMIT $offset,$Limite");
	$result->bindValue(1, $queMed, PDO::PARAM_INT);
	$result->execute();
	echo  '<table style="height:200px; overflow-y:hidden; table-layout: fixed;"  class="table table-mod" id="TablaPac">';
}



//Create the Table Header
echo '<thead><tr id="FILA" class="CFILA" ><th style="width:4px;text-align: center;" id="checkhead"><input type="checkbox" id="checkall"/><label style="margin-top:-4px;" for="checkall"><span></span></label></th>'.$table_head;
echo '<tbody>';

$i=0;
while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
{
	echo '<tr class="CFILA" id="'.$row['idpin'].'"> <td class="checktab" style="width:5px;text-align: center;"><input type="checkbox" id="checkcol'.$row['idpin'].'" name="cc"/><label style="margin-top:-4px;" for="checkcol'.$row['idpin'].'"><span></span></label></td>';
	//added for report image when hovered.
	$selecURL='';
	$extensionR='';
	$ImageRaiz='';
	if(isset($row['rawimage'])) {
	 $imageid=$row['rawimage'];
	

	
	    $extensionR = substr($row['rawimage'],strlen($row['rawimage'])-3,3);
		$ImageRaiz = substr($row['rawimage'],0,strlen($row['rawimage'])-4);
	
		
	if ($extensionR!='jpg')
			{
				$selecURL =$domain.'/temp/'.$queMed.'/Packages_Encrypted/'.$row['rawimage'];
			}
	}
	
	//echo '<td id="'.$selecURL.'" style="text-align: center; overflow:hidden;white-space:nowrap; width:3px;" report-image="report-image">'.$extensionR.'</td>';
	
	if($decrypt)
	{
		//Code added for decryption
		$filename = 'temp/'.$queMed.'/Packages_Encrypted/'.$row['rawimage'];
		if (file_exists($filename)) 
		{
			//do nothing
			//echo "The file $filename exists";
		}
		else 
		{
			//die('Decrypt.bat Packages_Encrypted '.$row['rawimage'].' '.$queMed .' '.$pass.' 2>&1');
			shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in PackagesTH_Encrypted/".$ImageRaiz.".".$extension." -out temp/".$queMed."/PackagesTH_Encrypted/".$ImageRaiz.".".$extension);
				//echo $out;
			//shell_exec('Decrypt.bat PackagesTH_Encrypted '.$ImageRaiz.'.png '.$queMed);
			//echo "The file $filename does not exist";
		}
	}
	
    
	$i++;
	$date = substr($row['FechaInput'],0,10);
	$year = strtok($date,"-");
	$month = strtok("-");
	$day = strtok(" ");
	$dt=$month."-".$day."-".$year;

	//echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; width:120px;" >'.$dt.'</td>';                 											//Date Arrival
	$hspl="";
	if($row['idcreator']==$queMed)
	{
		$origin="Me";
	}
	else
	{
		switch($row['creatortype'])
		{
			case 1 :$res1 = $con->prepare("select idmedfixedname from doctors where id=?");
					$res1->bindValue(1, $row['idcreator'], PDO::PARAM_INT);
					$res1->execute();
					
					$row1 = $res1->fetch(PDO::FETCH_ASSOC);
					$origin = $row1['idmedfixedname'];	
					$hspl="";
					$res22 = $con->prepare("select name from groups where id=(select idgroup from doctorsgroups where idDoctor=? LIMIT 1)");
					$res22->bindValue(1, $row['idcreator'], PDO::PARAM_INT);
					$res22->execute();
					
					$num_rows = $res22->rowCount();
					if($num_rows)
					{
						$row22 = $res22->fetch(PDO::FETCH_ASSOC);
						$hspl = '<span class="label label-success" width="30" style="left:0px; margin-left:0px; overflow:hidden;white-space:nowrap; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#FE9A2E;">'.$row22['name'].'</span>';
					}
					else
					{
						$hspl="";
					}
					break;
			default : $res1 = $con->prepare("select idusfixedname from usuarios where identif=?");
					$res1->bindValue(1, $row['idcreator'], PDO::PARAM_INT);
					$res1->execute();
			
					$row1 = $res1->fetch(PDO::FETCH_ASSOC);
					$origin = $row1['idusfixedname'];
					$hspl="";
					//die("case 2");
					break;
		}
	}
	/*echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; " ><span class="label label-success" width="10" style="left:0px; overflow:hidden;white-space:nowrap; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#FF8000;">'.$origin.'</span>'.'     '.$hspl.'</td>';                 */											//Origin
	
	//$tp = $row['tipo'];
	//$res = mysql_query("select nombreeng from tipopin where id=".$tp);
	
	//$count=mysql_num_rows($res);
	//if($count)
	//{
		//	$r = mysql_fetch_array($res);
	       $type=$row['nombreeng'];
		   $typecolor = $row['Color'];
		   $typeicon = $row['Icon'];
	//}
	//else
	//{
	  //     $type="Not Found";
	//}

	
	switch ($row['canal'])
	{
		case null:	$contCanal = '<span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">eMapLife&copy</span>';
					break;
		case '0':	//$contCanal = '<span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">eMapLife&copy iOs</span>';
					break;
		case '1':	$contCanal = '<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">SecMedMail&copy</span>';
					break;
		case '7':	$contCanal = '<span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">CloudChannel&copy</span>';
					break;
		case '8':	$contCanal = '<span class="label label-info"  style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;background-color:orange"">Dropzone&copy</span>';
					break;
		case '9':	$contCanal = '<span class="label label-info"  style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;background-color:red"">EMR&copy</span>';
		break;
		default:	$contCanal = '<span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">N/A</span>';
					break;
			
	}
    
    if ($row['isDicom'])
    {
        $contCanal = '<span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">Dicom</span>';
    }
	
	$sug_idCOL = "background-color: #f1f0f0; color: black;";
	
	
	
	if($row['vs']==0)
	{
		/*if($row['idusu'] == 0)
		  {
			$idusfixedname = "Not Found";
		  }
		  else
		  {
			$query = "select idusfixedname from usuarios where identif = ".$row['idusu'];
			$res = mysql_query($query);
			$rw = mysql_fetch_array($res);
			$idusfixedname = $rw['idusfixedname'];
		  }*/
		  
		  if($row['fecha']==null)
		  {
			$f_date=null;
		  }
		  else
		  {
			$f_date = substr($row['fecha'],0,10);
			if(substr($f_date,0,4) == '0000')
			{
		     $f_date = null;
			}
		  }
		  
		  	  
		  if($row['suggesteddate']==null)
		  {
			$a_date=null;
		  }
		  else
		  {
			$a_date = substr($row['suggesteddate'],0,10);
			if(substr($a_date,0,4) == '0000')
			{
		     $a_date = null;
			}
		  }
		  
		  $f_id = $row['idusu'];
		  $a_id = $row['suggestedid'];
		
	
		if($row['fs']==0 and $row['a_s']==0)  //if no parsing done whatsoever
		{
			$rep_date = "Not Available";
			$sug_id = -1;
			$sug_idCOL = "background-color: grey; color:white;";
			//$idusfixedname = "Not Available";
			$parsestatus = '<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:red;">Queued</span>';
			$nextaction = '<a href="javascript:void(0)"><span id="'.$selecURL.'" class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:red" report-image="report-image">Wait</span></a>';
		}
		else if($row['fs']==0 and $row['a_s']==1)   //if Automatic Parsing Done but   FileName Parsing could not be done
		{
			$flag=1;
			//Date
		    if($a_date != null)
			{
			   $rep_date = $a_date;
			}
			else
			{
			    $rep_date = "Not Found";
				$query = $con->prepare("update lifepin set fecha='0000-00-00' where idpin=?");
				$query->bindValue(1, $row['idpin'], PDO::PARAM_INT);
				$query->execute();
				
			   $flag=0;
			}
			
			//ID
			if($a_id != 0)
			{
				
			   $sug_id = $a_id;
			   //die($sug_id);
			   //$query = "select idusfixed,idusfixedname from usuarios where identif=".intval($a_id);
			   //$rw=mysql_query($query);
			   //die("******".$rw['idusfixed']."   ".$rw['idusfixedname']);
			   /*$query = "update lifepin set idusu=".$sug_id." where idpin=".$row['idpin'];
				mysql_query($query);
				   
			   $query = "update lifepin set idusfixed=(select idusfixed from usuarios where identif = ".$sug_id.") where idpin=".$row['idpin'];
				mysql_query($query);
					
				$query = "update lifepin set idusfixedname=(select idusfixedname from usuarios where identif = ".$sug_id.") where idpin=".$row['idpin'];
			    mysql_query($query);
				*/
					
			}
			else
			{
			    $sug_id = 0;
			    $flag=0;
			}
			
			$parsestatus = '<span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#2E64FE">Parsed</span>';
			if($flag==0)
			{
				$nextaction = '<a href="javascript:void(0)"><span id="'.$selecURL.'" class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#2E64FE" report-image="report-image">Classify</span></a>';
			}
			else
			{
				$nextaction = '<a href="javascript:void(0)"><span id="'.$selecURL.'" class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#2E9AFE" report-image="report-image">Validate</span></a>';
			}
	
		}
		else if($row['fs']==1 and $row['a_s']==0)   //if FileName PArsing Done but Automatic Parsing Pending
		{
			//Date
		    if($f_date != null)
			{
				$rep_date = $f_date;
			}
			else
			{
				$rep_date = "Unavailable";
				$query = $con->prepare("update lifepin set fecha='0000-00-00' where idpin=?");
				$query->bindValue(1, $row['idpin'], PDO::PARAM_INT);
				$query->execute();
			}
			
			//ID
			 if($f_id != 0)
			 {
				$sug_id = $f_id;
			 }
			 else
			 {
				$sug_id = -1;
				$sug_idCOL = "background-color: grey; color:white;";
			 }
			
			$parsestatus = '<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:red;">Queued</span>';
			$nextaction = '<a href="javascript:void(0)"><span id="'.$selecURL.'" class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:red" report-image="report-image">Wait</span></a>';
		}
		else   //if both types of Parsing done
		{
			//die($f_id.'  '.$a_id);
			$flag=1;
		     //Date
				
			if($f_date == null and $a_date != null)
			{
				$rep_date = $a_date;
				$query = con->prepare("update lifepin set fecha=? where idpin=?");
				$query->bindValue(1, $rep_date, PDO::PARAM_STR);
				$query->bindValue(2, $row['idpin'], PDO::PARAM_INT);
				$query->execute();
				
			}
			else if($f_date==null and $a_date==null)
			{
				$rep_date = "Not Found";
				$query = $con->prepare("update lifepin set fecha='0000-00-00' where idpin=?");
				$query->bindValue(1, $row['idpin'], PDO::PARAM_INT);
				$query->execute();
				$flag=0;
			}
			else if($f_date != null and $a_date == null)
		    {
				$rep_date = $f_date;
				$query = con->prepare("update lifepin set fecha=? where idpin=?");
				$query->bindValue(1, $rep_date, PDO::PARAM_STR);
				$query->bindValue(2, $row['idpin'], PDO::PARAM_INT);
				$query->execute();
			}
			else if($f_date === $a_date)
			{
			    $rep_date = $f_date;
			}
			else
			{
			    $rep_date = "Not Found";
				$query = $con->prepare("update lifepin set fecha='0000-00-00' where idpin=?");
				$query->bindValue(1, $row['idpin'], PDO::PARAM_INT);
				$query->execute();
				$flag=0;
			}
			
			
			if($f_id==0 and $a_id==0)   //if nothing found in either parsing
			{
				$sug_id = "Not Found";
				$idusfixedname = "Not Found";
				$flag=0;
				$query = $con->prepare("update lifepin set idusu=0 where idpin=?");
				$query->bindValue(1, $row['idpin'], PDO::PARAM_INT);
				$query->execute();
				
			}                              //if Filenameparsing found nothing but automatic parsing found something      
			else if($f_id==0 and $a_id!=0)
			{
				$sug_id = $a_id;
				/*$query = "update lifepin set idusu=".$sug_id." where idpin=".$row['idpin'];
				mysql_query($query);
				   
			   $query = "update lifepin set idusfixed=(select idusfixed from usuarios where identif = ".$sug_id.") where idpin=".$row['idpin'];
				mysql_query($query);
					
				$query = "update lifepin set idusfixedname=(select idusfixedname from usuarios where identif = ".$sug_id.") where idpin=".$row['idpin'];
			    mysql_query($query);*/
			}
			else if($f_id!=0 and $a_id==0)
			{
				$sug_id = $f_id;
				//die("Set to ".$sug_id);
				/*$query = "update lifepin set idusu=".$sug_id." where idpin=".$row['idpin'];
				mysql_query($query);
				   
			   $query = "update lifepin set idusfixed=(select idusfixed from usuarios where identif = ".$sug_id.") where idpin=".$row['idpin'];
				mysql_query($query);
					
				$query = "update lifepin set idusfixedname=(select idusfixedname from usuarios where identif = ".$sug_id.") where idpin=".$row['idpin'];
			    mysql_query($query);*/
			}
			else if($f_id!=0 and $a_id!=0)             //if both parsings found something
			{
				if($f_id===$a_id)		//if both findings are same, no problem
				{
					$sug_id = $f_id;
					/*$query = "update lifepin set idusu=".$sug_id." where idpin=".$row['idpin'];
					mysql_query($query);
				   
					$query = "update lifepin set idusfixed=(select idusfixed from usuarios where identif = ".$sug_id.") where idpin=".$row['idpin'];
					//echo $query;
					mysql_query($query);
					
					$query = "update lifepin set idusfixedname=(select idusfixedname from usuarios where identif = ".$sug_id.") where idpin=".$row['idpin'];
					mysql_query($query);
					*/
				}
				else					// in both finds, there is mismatch, suggest not found
				{
					$sug_id = "Not Found";
					$idusfixedname = "Not Found";
					$flag=0;
					$query = $con->prepare("update lifepin set idusu=0 where idpin=?");
					$query->bindValue(1, $row['idpin'], PDO::PARAM_INT);
					$query->execute();
				}
			}
			
			
			
			
			$parsestatus = '<span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#2E64FE">Parsed</span>';
		    if($flag==0)
			{
				$nextaction = '<a href="javascript:void(0)"><span id="'.$selecURL.'" class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#2E64FE" report-image="report-image">Classify</span></a>';
			}
			else
			{
				$nextaction = '<a href="javascript:void(0)"><span id="'.$selecURL.'" class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#2E9AFE" report-image="report-image">Validate</span></a>';
			}
			
			
		}
	
	
	} // end vs=0
	else   //if vs=1
	{
		$rep_date = substr($row['fecha'],0,10);
		$sug_id =   $row['idusu'];  	
		$idusfixedname =  $row['idusfixedname'];              
		$parsestatus = '<span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#31B404">Verified</span>';
	    $nextaction = '<a href="javascript:void(0)"><span span id="'.$selecURL.'" class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#31B404" report-image="report-image">OK</span></a>';
		
	
	
	}// end vs=1 
	
	if(substr($rep_date,0,3)!="Not")
	{
		$year=strtok($rep_date,"-");
		$month=strtok("-");
		$day = strtok("-");
		$rep_date = $month."-".$day."-".$year;
		
	}
	
	echo '<td id="nxtaction" style="text-align: center;">'.$nextaction.'</td>'; //Next Action
	//echo '<td id="'.$selecURL.'" style="text-align: center; overflow:hidden;white-space:nowrap; width:3px;" report-image="report-image">'.$extensionR.'</td>';c[-
	
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; width:120px;">'.$row['idpin'].'</td>';        //Idpin  
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; " ><span class="label label-success" width="10" style="left:0px; overflow:hidden;white-space:nowrap; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:#FF8000;">'.$origin.'</span>'.'     '.$hspl.'</td>';                 											//Origin
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; width:120px;" >'.$rep_date.'</td>';         //Report Date
    echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; width:120px;" >'.$dt.'</td>'; 
	//echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; width:120px; '.$sug_idCOL.';">'.$sug_id.'</td>';            //ID
	if($sug_id==-1)
	{
		$idusfixedname = "Not Available";
	}
	else if($sug_id==0)
	{
		$idusfixedname = "Not Found";
	}
	else
	{
		$q= $con->prepare("select idusfixedname from usuarios where identif=?");
		$q->bindValue(1, $sug_id, PDO::PARAM_INT);
		$res = $q->execute();
		
		$rw = $q->fetch(PDO::FETCH_ASSOC);
		$idusfixedname = $rw['idusfixedname'];
	}
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; width:120px;">'.$idusfixedname.'</td>';     //IDUSFixedName
	//echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; width:120px;">'.$type.'</td>';             
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; width:120px; color:'.$typecolor.';"><div style="width:20px; float:left;"><i class="'.$typeicon.'"></i></div><div style="width:70px; float:right;">'.$type.'</div></td>';		 //Type
	echo '<td style="text-align: center;">'.$parsestatus.'</td>';														  //Parse_status
	echo '<td style="text-align: center;">'.$contCanal.'</td>';														  //Channel
 		 													  
	
	
	




}//end while
echo '</tbody></table>'; 


?>
