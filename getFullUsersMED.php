<!-- Create language switcher instance and set default language to en-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script src="/jquery-cookie-master/jquery.cookie.js"></script>
<script type="text/javascript">
	var lang = new Lang('en');
	window.lang.dynamic('th', 'jquery-lang-js-master/js/langpack/th.json');
	
	var langType = $.cookie('lang');

if(langType == 'th'){
var language = 'th';
}else{
var language = 'en';
}

if(langType == 'th'){
window.lang.change('th');
//alert('th');
}

if(langType == 'en'){
window.lang.change('en');
//alert('en');
}
</script>
<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 
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

$queUsu = $_GET['Usuario'];
$Group=$_GET['Group'];
//$queUsu=null;
if($queUsu==null or $queUsu==" " or $queUsu=="" or $queUsu==-111){
	$queUsu="";
}
//New code added by Pallab
else
{
  $Group = -1;
}
//$NReports = $_GET['NReports'];
$IdMed = $_GET['IdMed'];
//$queUsu = 32;

echo  '<table class="table table-mod" id="TablaPac">';
//Changed in below line - removed the Id-Fixed, changed Username to email and N.Reports to Number of Reports  and to show the total reports
echo '<thead><tr id="FILA" class="CFILA"><th lang="en">Identifier</th><th lang="en">First Name</th><th lang="en">Last Name</th><th lang="en">Username</th><th lang="en">Total Reports</th></tr></thead>';
echo '<tbody>';

//$ArrayPacientes = new SplFixedArray(99999);
$ArrayPacientes = array();
$numeral=0;

//$ArrayGroupDoctors = array();
//$i=0;
// Retrieve or look for patient who belongs to the group of the doctors

/*$resultPRE1 = mysql_query("Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed')");

while($rowPRE1 = mysql_fetch_array($resultPRE1)){
	$Iddoctor=$rowPRE1['idDoctor'];
	// Retrieve all the patient details from doctorslinkusers table
	$resultPRE = mysql_query("SELECT distinct(IdUs) FROM DOCTORSlinkUSERS WHERE IdMED= '$Iddoctor' and Estado=2");  */
	$sql_que=$con->prepare("select Id from tipopin where Agrup=9");
	$res=$sql_que->execute();
	
	$privatetypes=array();
	$num1=0;
	while($rowpr = $sql_que->fetch(PDO::FETCH_ASSOC)){
		$privatetypes[$num1]=$rowpr['Id'];
		$num1++;
   }

	
	
	$resultPRE = $con->prepare("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and (Idmed=? or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= ?))) and Estado IN (2,null) LIMIT 1000");
	$resultPRE->bindValue(1, $IdMed, PDO::PARAM_INT);
	$resultPRE->bindValue(2, $IdMed, PDO::PARAM_INT);
	$resultPRE->execute();
	
	//$outalert="SELECT distinct(IdUs) FROM DOCTORSlinkUSERS WHERE IdPin IS NULL and (Idmed='$IdMed' or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed'))) and Estado IN (2,null) LIMIT 1000";
	//echo $outalert;

	while ($rowPRE = $resultPRE->fetch(PDO::FETCH_ASSOC)) {
	
	$idEncontrado = $rowPRE['IdUs'];
	$resultUSU = $con->prepare("SELECT * FROM usuarios WHERE Identif = ? and (Surname like ? or Name like ? or IdUsFIXEDNAME like ?)");
	$resultUSU->bindValue(1, $idEncontrado, PDO::PARAM_INT);
	$resultUSU->bindValue(2, '%'.$queUsu.'%', PDO::PARAM_STR);
	$resultUSU->bindValue(3, '%'.$queUsu.'%', PDO::PARAM_STR);
	$resultUSU->bindValue(4, '%'.$queUsu.'%', PDO::PARAM_STR);
	$resultUSU->execute();
	
	//$resultUSU = mysql_query("SELECT * FROM Usuarios WHERE Identif = '$idEncontrado'");
	$rowUSU = $resultUSU->fetch(PDO::FETCH_ASSOC);
	if ($rowUSU['Surname']!='')
		{
		//echo $rowUSU['Surname']."</br>";
		if(!in_array($idEncontrado, $ArrayPacientes)){
		$ArrayPacientes[$numeral]=$idEncontrado;
		$numeral++;

		$resultPIN = $con->prepare("SELECT * FROM lifepin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = ?");
		$resultPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
		$resultPIN->execute();
		
		$countPIN=$resultPIN->rowCount();
		
		$actualPIN= $con->prepare("SELECT * FROM lifepin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = ? and (Idmed=? or Idmed=0 or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= ?)) or IdMED IS NULL)");
		$actualPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
		$actualPIN->bindValue(2, $IdMed, PDO::PARAM_INT);
		$actualPIN->bindValue(3, $IdMed, PDO::PARAM_INT);
		$actualPIN->execute();
		
		$countactualPIN=$actualPIN->rowCount();
		//$NReports=$countactualPIN."/".$countPIN;
        $NReports= $countPIN;
		// Ver si el paciente tiene cuenta propia (viendo si tiene password)
		$PassPac = $rowUSU['IdUsRESERV'];
		if ($PassPac > ' ')
		{
			$medalla ='<i class="fam-award-star-gold-3"></i>';
		}
		else
		{
		$medalla ='';
		}
		// Ver si el paciente tiene cuenta propia (viendo si tiene password)
		
			$current_encoding = mb_detect_encoding($rowUSU['Name'], 'auto');
			$UserName = iconv($current_encoding, 'ISO-8859-1', $rowUSU['Name']);

			$current_encoding = mb_detect_encoding($rowUSU['Surname'], 'auto');
			$UserSurname = iconv($current_encoding, 'ISO-8859-1', $rowUSU['Surname']); 

	    echo '<tr class="CFILA" id="'.$rowUSU['Identif'].'"><td>'.$rowUSU['IdUsFIXED'].'</td>';
		echo '<td>'.$UserName.'</td>';
		echo '<td style="font-weight:bold;"><a href="#"><i class="fam-resultset-next"></i>'.$UserSurname.'  '.$medalla.'</a></td>';
		echo '<td>'.$rowUSU['email'].'</td>';
		echo '<td>'.$NReports.'</td></tr>';
	 }
	}
  }
//}

//Retrive all patients from doctorslinkusers where Idpin is not null
	$resultPRE = $con->prepare("SELECT IdUs, count(IdPin) FROM doctorslinkusers WHERE IdPin IS NOT NULL and (Idmed=? or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor=?))) and Estado IN (2,null) GROUP BY IdUs LIMIT 1000");
	$resultPRE->bindValue(1, $IdMed, PDO::PARAM_INT);
	$resultPRE->bindValue(2, $IdMed, PDO::PARAM_INT);
	$resultPRE->execute();
	
	while ($rowPRE = $resultPRE->fetch(PDO::FETCH_ASSOC)) {
	//echo $rowPRE['IdUs']."</br>";
	$idEncontrado = $rowPRE['IdUs'];
	$countactualPIN=$rowPRE['count(IdPin)'];
	$resultUSU = $con->prepare("SELECT * FROM usuarios WHERE Identif = ? and (Surname like ? or name like ? or IdUsFIXEDNAME like ?)");
	$resultUSU->bindValue(1, $idEncontrado, PDO::PARAM_INT);
	$resultUSU->bindValue(2, '%'.$queUsu.'%', PDO::PARAM_STR);
	$resultUSU->bindValue(3, '%'.$queUsu.'%', PDO::PARAM_STR);
	$resultUSU->bindValue(4, '%'.$queUsu.'%', PDO::PARAM_STR);
	$resultUSU->execute();
	
	//$resultUSU = mysql_query("SELECT * FROM Usuarios WHERE Identif = '$idEncontrado'");
	$rowUSU = $resultUSU->fetch(PDO::FETCH_ASSOC);
	$countPIN=0;
	if ($rowUSU['Surname']!='')
		{
		//echo $rowUSU['Surname'];
		if(!in_array($idEncontrado, $ArrayPacientes)){
		$ArrayPacientes[$numeral]=$idEncontrado;
		$numeral++;

		$resultPIN = $con->prepare("SELECT * FROM lifepin WHERE (tipo not in (select Id from tipopin where Agrup=9) and isPrivate IN (NULL,0)) and (markfordelete IS NULL or markfordelete=0) and IdUsu = ?");
		$resultPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
		$resultPIN->execute();
		
		$countPIN=$resultPIN->rowCount();
		
		$resultPRIVATE=$con->prepare("SELECT idPin FROM lifepin WHERE (tipo in (select Id from tipopin where Agrup=9) or isPrivate=1) and IdUsu = ?");
		$resultPRIVATE->bindValue(1, $idEncontrado, PDO::PARAM_INT);
		$resultPRIVATE->execute();
		
		while($rowP = $resultPRIVATE->fetch(PDO::FETCH_ASSOC)){
		$pin=$rowP['idPin'];
		
		$resultDLU=$con->prepare("select Idpin from doctorslinkusers where Idpin=? and IdUs=? and estado=2");
		$resultDLU->bindValue(1, $pin, PDO::PARAM_INT);
		$resultDLU->bindValue(2, $idEncontrado, PDO::PARAM_INT);
		$resultDLU->execute();
		
		if($resultDLU->fetch(PDO::FETCH_ASSOC)){
			$countPIN=$countPIN+1;
		}
		
		
		}
		//$actualPIN= mysql_query("SELECT * FROM LifePin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = '$idEncontrado' and (Idmed='$IdMed' or Idmed=0 or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed')) or IdMED IS NULL)");
		//$countactualPIN=mysql_num_rows($actualPIN);
		$NReports=$countactualPIN."/".$countPIN;

		// Ver si el paciente tiene cuenta propia (viendo si tiene password)
		$PassPac = $rowUSU['Password'];
		if ($PassPac > ' ')
		{
			$medalla ='<i class="fam-award-star-gold-3"></i>';
		}
		else
		{
		$medalla ='';
		}
			$current_encoding = mb_detect_encoding($rowUSU['Name'], 'auto');
			$UserName = iconv($current_encoding, 'ISO-8859-1', $rowUSU['Name']);

			$current_encoding = mb_detect_encoding($rowUSU['Surname'], 'auto');
			$UserSurname = iconv($current_encoding, 'ISO-8859-1', $rowUSU['Surname']); 

	    echo '<tr class="CFILA" id="'.$rowUSU['Identif'].'"><td>'.$rowUSU['IdUsFIXED'].'</td>';
		echo '<td>'.$UserName.'</td>';
		echo '<td style="font-weight:bold;"><a href="#"><i class="fam-resultset-next"></i>'.$UserSurname.'  '.$medalla.'</a></td>';
		echo '<td>'.$rowUSU['email'].'</td>';
		echo '<td>'.$NReports.'</td></tr>';
	 }
	}
  }

$resultPRE=$con->prepare(" select distinct(IdPac) from doctorslinkdoctors where idmed2= ? and estado=2 LIMIT 1000");
$resultPRE->bindValue(1, $IdMed, PDO::PARAM_INT);
$resultPRE->execute();

//$resultPRE = mysql_query("SELECT * FROM DOCTORSlinkDOCTORS WHERE IdMED= '$IdMed'"); 
 // Acota los usuarios a los que se tiene acceso directoe
while ($rowPRE = $resultPRE->fetch(PDO::FETCH_ASSOC))
	{
		//$nuevoMed = $rowPRE['IdMED2'];
		//$resultPRE2 = mysql_query("SELECT * FROM DOCTORSlinkUSERS WHERE IdMED= '$nuevoMed'");  // Acota los usuarios a los que se tiene acceso directo este médico
		//while ($rowPRE2 = mysql_fetch_array($resultPRE2)) {

			$idEncontrado = $rowPRE['IdPac'];
			$resultUSU = $con->prepare("SELECT * FROM usuarios WHERE Identif = ? AND Surname like ? ");
			$resultUSU->bindValue(1, $idEncontrado, PDO::PARAM_INT);
			$resultUSU->bindValue(2, '%'.$queUsu.'%', PDO::PARAM_STR);
			$resultUSU->execute();
			
			//$resultUSU = mysql_query("SELECT * FROM Usuarios WHERE Identif = '$idEncontrado'");
			$rowUSU = $resultUSU->fetch(PDO::FETCH_ASSOC);
			
			// Ver si el paciente tiene cuenta propia (viendo si tiene password)
			$PassPac = $rowUSU['Password'];
			if ($PassPac > ' ')
			{
				$medalla ='<i class="fam-award-star-gold-3"></i>';
				}
			else
				{
					$medalla ='';
			}
			// Ver si el paciente tiene cuenta propia (viendo si tiene password)


			if($idEncontrado!=null){
				
				if (!in_array($idEncontrado, $ArrayPacientes))
					{
						$ArrayPacientes[$numeral]=$idEncontrado;
						$numeral++;

						$resultPIN = $con->prepare("SELECT * FROM lifepin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = ? and Tipo NOT IN (select Id from tipopin where Agrup=9)");
						$resultPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
						$resultPIN->execute();
						
						$countPIN=$resultPIN->rowCount();
						//$actualPIN= mysql_query("SELECT * FROM LifePin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = '$idEncontrado' and Tipo NOT IN (select Id from tipopin where Agrup=9) and (IdMED IN (select idmed from doctorslinkdoctors where idmed2= '$IdMed' and idmed NOT IN (select distinct(IdDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor='$IdMed'))) or IdMED IS NULL or Idmed=0)");
						$actualPIN= $con->prepare("SELECT * FROM lifepin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = ? 
						and Tipo NOT IN (select Id from tipopin where Agrup=9) and (IdMED IN (select idmed from doctorslinkdoctors 
						where idmed2= ? and Idpac=?) or IdMED IN (select distinct(IdDoctor) from doctorsgroups 
						where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (select idmed from doctorslinkdoctors 
						where idmed2= ? and Idpac=?))) or IdMED IS NULL or Idmed=0)");
						$actualPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
						$actualPIN->bindValue(2, $IdMed, PDO::PARAM_INT);
						$actualPIN->bindValue(3, $idEncontrado, PDO::PARAM_INT);
						$actualPIN->bindValue(4, $IdMed, PDO::PARAM_INT);
						$actualPIN->bindValue(5, $idEncontrado, PDO::PARAM_INT);
						$actualPIN->execute();
						
						//$actualPIN= mysql_query("SELECT * FROM LifePin WHERE IdUsu = '$idEncontrado' and Tipo NOT IN (select Id from tipopin where Agrup=9) and (IdMED IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (select idmed from doctorslinkdoctors where idmed2= '$IdMed' and idmed NOT IN (select distinct(IdDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor='$IdMed'))))) or IdMED IS NULL)");
						$countactualPIN=$actualPIN->rowCount();
						$NReports=$countactualPIN."/".$countPIN;

			//Below Lines commented out by Pallab as there was a bug wherein extra blank rows where getting added below actual rows of data
              /*      echo '<tr class="CFILA" id="'.$rowUSU['Identif'].'"><td>'.$rowUSU['IdUsFIXED'].'</td>';
					echo '<td>'.$rowUSU['Name'].'</td>';
					echo '<td><a href="#">'.$rowUSU['Surname'].'  '.$medalla.'</a></td>';
					echo '<td>'.$rowUSU['email'].'</td>';
					echo '<td>'.$NReports.'</td></tr>'; */
				}
			}
			//}




}


//Ability to show all the patients. We have to filter out the rest of the patient
if($Group==0){

$sql_query = $con->prepare("SELECT * FROM usuarios where Surname like ? or name like ? or IdUsFIXEDNAME like ? LIMIT 1000");
$sql_query->bindValue(1, '%'.$queUsu.'%', PDO::PARAM_STR);
$sql_query->bindValue(2, '%'.$queUsu.'%', PDO::PARAM_STR);
$sql_query->bindValue(3, '%'.$queUsu.'%', PDO::PARAM_STR);
$sql_query->execute();

while ($rowUSU = $sql_query->fetch(PDO::FETCH_ASSOC))
	{
		//$nuevoMed = $rowPRE['IdMED2'];
		//$resultPRE2 = mysql_query("SELECT * FROM DOCTORSlinkUSERS WHERE IdMED= '$nuevoMed'");  // Acota los usuarios a los que se tiene acceso directo este médico
		//while ($rowPRE2 = mysql_fetch_array($resultPRE2)) {

			$idEncontrado =$rowUSU['Identif'];
			//$resultUSU = mysql_query("SELECT * FROM Usuarios WHERE Identif = '$idEncontrado' AND Surname like '%$queUsu%' ");
			//$resultUSU = mysql_query("SELECT * FROM Usuarios WHERE Identif = '$idEncontrado'");
			//$rowUSU = mysql_fetch_array($resultUSU);
			
			// Ver si el paciente tiene cuenta propia (viendo si tiene password)
			$PassPac = $rowUSU['Password'];
			if ($PassPac > ' ')
			{
				$medalla ='<i class="fam-award-star-gold-3"></i>';
				}
			else
				{
					$medalla ='';
			}
			// Ver si el paciente tiene cuenta propia (viendo si tiene password)


			if($idEncontrado!=null){
				
				if (!in_array($idEncontrado, $ArrayPacientes))
					{
						$ArrayPacientes[$numeral]=$idEncontrado;
						$numeral++;

						$resultPIN = $con->prepare("SELECT * FROM lifepin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = ? and Tipo NOT IN (select Id from tipopin where Agrup=9)");
						$resultPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
						$resultPIN->execute();
						
						$countPIN=$resultPIN->rowCount();
						//$actualPIN= mysql_query("SELECT * FROM LifePin WHERE IdUsu = '$idEncontrado' and Tipo NOT IN (select Id from tipopin where Agrup=9) and (IdMED IN (select idmed from doctorslinkdoctors where idmed2= '$IdMed' and idmed NOT IN (select distinct(IdDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor='$IdMed'))) or IdMED IS NULL)");
						//$countactualPIN=mysql_num_rows($actualPIN);
						$countactualPIN=0;
						//$NReports=$countactualPIN."/".$countPIN;
                        $NReports= $countPIN;
						
						$current_encoding = mb_detect_encoding($rowUSU['Name'], 'auto');
						$UserName = iconv($current_encoding, 'ISO-8859-1', $rowUSU['Name']);

						$current_encoding = mb_detect_encoding($rowUSU['Surname'], 'auto');
						$UserSurname = iconv($current_encoding, 'ISO-8859-1', $rowUSU['Surname']); 
						
				//	echo '<tr class="CFILA" id="'.$rowUSU['Identif'].'"><td>'.$rowUSU['IdUsFIXED'].'</td>';
					echo '<td>'.$UserName.'</td>';
					echo '<td><a href="#">'.$UserSurname.'  '.$medalla.'</a></td>';
					echo '<td>'.$rowUSU['email'].'</td>';
					echo '<td>'.$NReports.'</td></tr>';
				}
			}
			//}




	}
}

########OLD CODE##########
// 1: Se meten los PACIENTES que han sido creados por este médico y los que han dado autorización expresa (tabla DOCTORSlinkUSERS)
/*$resultPRE = mysql_query("SELECT * FROM DOCTORSlinkUSERS WHERE IdMED= '$IdMed'");  // Acota los usuarios a los que se tiene acceso directo
while ($rowPRE = mysql_fetch_array($resultPRE)) {

$idEncontrado = $rowPRE['IdUs'];
$resultUSU = mysql_query("SELECT * FROM Usuarios WHERE Identif = '$idEncontrado' AND Surname like '%$queUsu%' ");
//$resultUSU = mysql_query("SELECT * FROM Usuarios WHERE Identif = '$idEncontrado'");
$rowUSU = mysql_fetch_array($resultUSU);
if ($rowUSU['Surname']!='')
	{
	$ArrayPacientes[$numeral]=$idEncontrado;
	$numeral++;

	$resultPIN = mysql_query("SELECT * FROM LifePin WHERE IdUsu = '$idEncontrado' ");
	$countPIN=mysql_num_rows($resultPIN);
	$NReports=$countPIN;

	// Ver si el paciente tiene cuenta propia (viendo si tiene password)
	$PassPac = $rowUSU['Password'];
	if ($PassPac > ' ')
	{
		$medalla ='<i class="fam-award-star-gold-3"></i>';
	}
	else
	{
		$medalla ='';
	}
	// Ver si el paciente tiene cuenta propia (viendo si tiene password)

	echo '<tr class="CFILA" id="'.$rowUSU['Identif'].'"><td>'.$rowUSU['IdUsFIXED'].'</td>';
	echo '<td>'.$rowUSU['Name'].'</td>';
	echo '<td style="font-weight:bold;"><a href="#"><i class="fam-resultset-next"></i>'.$rowUSU['Surname'].'  '.$medalla.'</a></td>';
	echo '<td>'.$rowUSU['email'].'</td>';
	echo '<td>'.$NReports.'</td></tr>';
	}
}

// 2: Se meten los PACIENTES que han sido creados por OTROS médicos que a su vez HAN DADO AUTORIZACIÓN A este Médico
$resultPRE = mysql_query("SELECT * FROM DOCTORSlinkDOCTORS WHERE IdMED= '$IdMed'");  // Acota los usuarios a los que se tiene acceso directo
while ($rowPRE = mysql_fetch_array($resultPRE))
	{
		$nuevoMed = $rowPRE['IdMED2'];
		$resultPRE2 = mysql_query("SELECT * FROM DOCTORSlinkUSERS WHERE IdMED= '$nuevoMed'");  // Acota los usuarios a los que se tiene acceso directo este médico
		while ($rowPRE2 = mysql_fetch_array($resultPRE2)) {

			$idEncontrado = $rowPRE2['IdUs'];
			$resultUSU = mysql_query("SELECT * FROM Usuarios WHERE Identif = '$idEncontrado' AND Surname like '%$queUsu%' ");
			$rowUSU = mysql_fetch_array($resultUSU);
			
			// Ver si el paciente tiene cuenta propia (viendo si tiene password)
			$PassPac = $rowUSU['Password'];
			if ($PassPac > ' ')
			{
				$medalla ='<i class="fam-award-star-gold-3"></i>';
				}
			else
				{
					$medalla ='';
			}
			// Ver si el paciente tiene cuenta propia (viendo si tiene password)



			if (!in_array($idEncontrado, $ArrayPacientes))
				{
					$ArrayPacientes[$numeral]=$idEncontrado;
					$numeral++;

					$resultPIN = mysql_query("SELECT * FROM LifePin WHERE IdUsu = '$idEncontrado' ");
					$countPIN=mysql_num_rows($resultPIN);
					$NReports=$countPIN;

					echo '<tr class="CFILA" id="'.$rowUSU['Identif'].'"><td>'.$rowUSU['IdUsFIXED'].'</td>';
					echo '<td>'.$rowUSU['Name'].'</td>';
					echo '<td><a href="#">'.$rowUSU['Surname'].'  '.$medalla.'</a></td>';
					echo '<td>'.$rowUSU['email'].'</td>';
					echo '<td>'.$NReports.'</td></tr>';
				}
			}




	}
*/


echo '</tbody></table>';    

//echo $Group;
    

?>