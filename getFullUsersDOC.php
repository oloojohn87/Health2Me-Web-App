<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 /*KYLE
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 /*KYLE
$tbl_name="doctors"; // Table name

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$queUsu = $_GET['Usuario'];
$Group=$_GET['Group'];
//$queUsu=null;
if($queUsu==null or $queUsu==" " or $queUsu=="" or $queUsu==-111){
	$queUsu="";
}
//$NReports = $_GET['NReports'];
$IdMed = $_GET['IdMed'];
//$queUsu = 32;

echo  '<table class="table table-mod" id="TablaPac">';
echo '<thead><tr id="FILA" class="CFILA"><th>Id-Fixed</th><th>First Name</th><th>Last Name</th><th>Username</th></tr></thead>';
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
	$resultPRE = mysql_query("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdMED= '$Iddoctor' and Estado=2");  */
	/*KYLE
	$sql_que="select Id from tipopin where Agrup=9";
	$res=mysql_query($sql_que);
	
	$privatetypes=array();
	$num1=0;
	while($rowpr=mysql_fetch_assoc($res)){
		$privatetypes[$num1]=$rowpr['Id'];
		$num1++;
   }

	
	


	$resultUSU = mysql_query("SELECT * FROM doctors WHERE (Surname like '%$queUsu%' or name like '%$queUsu%' or IdMEDFIXEDNAME like '%$queUsu%')");
	//$resultUSU = mysql_query("SELECT * FROM usuarios WHERE Identif = '$idEncontrado'");
	while($rowUSU = mysql_fetch_array($resultUSU)){
	if ($rowUSU['Surname']!='')
		{
		

		echo '<tr class="CFILA" id="'.$rowUSU['id'].'"><td>'.$rowUSU['IdMEDFIXED'].'</td>';
		echo '<td>'.$rowUSU['Name'].'</td>';
		echo '<td style="font-weight:bold;">'.$rowUSU['Surname'].'</td>';
		echo '<td>'.$rowUSU['IdMEDEmail'].'</td>';
//		echo '<td>'.$NReports.'</td></tr>';
	 }
	}
  
//}

//Retrive all patients from doctorslinkusers where Idpin is not null
/*	$resultPRE = mysql_query("SELECT IdUs, count(IdPin) FROM doctorslinkusers WHERE IdPin IS NOT NULL and (Idmed='$IdMed' or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor='$IdMed'))) and Estado IN (2,null) GROUP BY IdUs LIMIT 10");
	while ($rowPRE = mysql_fetch_array($resultPRE)) {

	$idEncontrado = $rowPRE['IdUs'];
	$countactualPIN=$rowPRE['count(IdPin)'];
	$resultUSU = mysql_query("SELECT * FROM usuarios WHERE Identif = '$idEncontrado' and (Surname like '%$queUsu%' or name like '%$queUsu%' or IdUsFIXEDNAME like '%$queUsu%')");
	//$resultUSU = mysql_query("SELECT * FROM usuarios WHERE Identif = '$idEncontrado'");
	$rowUSU = mysql_fetch_array($resultUSU);
	$countPIN=0;
	if ($rowUSU['Surname']!='')
		{
		if(!in_array($idEncontrado, $ArrayPacientes)){
		$ArrayPacientes[$numeral]=$idEncontrado;
		$numeral++;

		$resultPIN = mysql_query("SELECT * FROM lifepin WHERE (tipo not in (select Id from tipopin where Agrup=9) and isPrivate IN (NULL,0)) and (markfordelete IS NULL or markfordelete=0) and IdUsu = '$idEncontrado'");
		$countPIN=mysql_num_rows($resultPIN);
		$resultPRIVATE=mysql_query("SELECT idPin FROM lifepin WHERE (tipo in (select Id from tipopin where Agrup=9) or isPrivate=1) and IdUsu = '$idEncontrado'");
		while($rowP = mysql_fetch_array($resultPRIVATE)){
		$pin=$rowP['idPin'];
		$resultDLU=mysql_query("select Idpin FROM doctorslinkusers where Idpin='$pin' and IdUs='$idEncontrado' and estado=2");
		if(mysql_fetch_array($resultDLU)){
			$countPIN=$countPIN+1;
		}
		
		
		}
		//$actualPIN= mysql_query("SELECT * FROM lifepin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = '$idEncontrado' and (Idmed='$IdMed' or Idmed=0 or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed')) or IdMED IS NULL)");
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
		// Ver si el paciente tiene cuenta propia (viendo si tiene password)

		echo '<tr class="CFILA" id="'.$rowUSU['Identif'].'"><td>'.$rowUSU['IdUsFIXED'].'</td>';
		echo '<td>'.$rowUSU['Name'].'</td>';
		echo '<td style="font-weight:bold;"><a href="#"><i class="fam-resultset-next"></i>'.$rowUSU['Surname'].'  '.$medalla.'</a></td>';
		echo '<td>'.$rowUSU['email'].'</td>';
		echo '<td>'.$NReports.'</td></tr>';
	 }
	}
  }

$resultPRE=mysql_query(" select distinct(IdPac) from doctorslinkdoctors where idmed2= '$IdMed' and estado=2 LIMIT 10");
//$resultPRE = mysql_query("SELECT * FROM doctorslinkdoctors WHERE IdMED= '$IdMed'"); 
 // Acota los usuarios a los que se tiene acceso directoe
while ($rowPRE = mysql_fetch_array($resultPRE))
	{
		//$nuevoMed = $rowPRE['IdMED2'];
		//$resultPRE2 = mysql_query("SELECT * FROM doctorslinkusers WHERE IdMED= '$nuevoMed'");  // Acota los usuarios a los que se tiene acceso directo este médico
		//while ($rowPRE2 = mysql_fetch_array($resultPRE2)) {

			$idEncontrado = $rowPRE['IdPac'];
			$resultUSU = mysql_query("SELECT * FROM usuarios WHERE Identif = '$idEncontrado' AND Surname like '%$queUsu%' ");
			//$resultUSU = mysql_query("SELECT * FROM usuarios WHERE Identif = '$idEncontrado'");
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


			if($idEncontrado!=null){
				
				if (!in_array($idEncontrado, $ArrayPacientes))
					{
						$ArrayPacientes[$numeral]=$idEncontrado;
						$numeral++;

						$resultPIN = mysql_query("SELECT * FROM lifepin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = '$idEncontrado' and Tipo NOT IN (select Id from tipopin where Agrup=9)");
						$countPIN=mysql_num_rows($resultPIN);
						//$actualPIN= mysql_query("SELECT * FROM lifepin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = '$idEncontrado' and Tipo NOT IN (select Id from tipopin where Agrup=9) and (IdMED IN (select idmed from doctorslinkdoctors where idmed2= '$IdMed' and idmed NOT IN (select distinct(IdDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor='$IdMed'))) or IdMED IS NULL or Idmed=0)");
						$actualPIN= mysql_query("SELECT * FROM lifepin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = '$idEncontrado' and Tipo NOT IN (select Id from tipopin where Agrup=9) and (IdMED IN (select idmed from doctorslinkdoctors where idmed2= '$IdMed' and Idpac='$idEncontrado') or IdMED IN (select distinct(IdDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (select idmed from doctorslinkdoctors where idmed2= '$IdMed' and Idpac='$idEncontrado'))) or IdMED IS NULL or Idmed=0)");
						//$actualPIN= mysql_query("SELECT * FROM lifepin WHERE IdUsu = '$idEncontrado' and Tipo NOT IN (select Id from tipopin where Agrup=9) and (IdMED IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (select idmed from doctorslinkdoctors where idmed2= '$IdMed' and idmed NOT IN (select distinct(IdDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor='$IdMed'))))) or IdMED IS NULL)");
						$countactualPIN=mysql_num_rows($actualPIN);
						$NReports=$countactualPIN."/".$countPIN;

					echo '<tr class="CFILA" id="'.$rowUSU['Identif'].'"><td>'.$rowUSU['IdUsFIXED'].'</td>';
					echo '<td>'.$rowUSU['Name'].'</td>';
					echo '<td><a href="#">'.$rowUSU['Surname'].'  '.$medalla.'</a></td>';
					echo '<td>'.$rowUSU['email'].'</td>';
					echo '<td>'.$NReports.'</td></tr>';
				}
			}
			//}




}


//Ability to show all the patients. We have to filter out the rest of the patient
if($Group==0){

$sql_query = mysql_query("SELECT * FROM usuarios where Surname like '%$queUsu%' or name like '%$queUsu%' or IdUsFIXEDNAME like '%$queUsu%' LIMIT 10");
while ($rowUSU = mysql_fetch_array($sql_query))
	{
		//$nuevoMed = $rowPRE['IdMED2'];
		//$resultPRE2 = mysql_query("SELECT * FROM doctorslinkusers WHERE IdMED= '$nuevoMed'");  // Acota los usuarios a los que se tiene acceso directo este médico
		//while ($rowPRE2 = mysql_fetch_array($resultPRE2)) {

			$idEncontrado =$rowUSU['Identif'];
			//$resultUSU = mysql_query("SELECT * FROM usuarios WHERE Identif = '$idEncontrado' AND Surname like '%$queUsu%' ");
			//$resultUSU = mysql_query("SELECT * FROM usuarios WHERE Identif = '$idEncontrado'");
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

						$resultPIN = mysql_query("SELECT * FROM lifepin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = '$idEncontrado' and Tipo NOT IN (select Id from tipopin where Agrup=9)");
						$countPIN=mysql_num_rows($resultPIN);
						//$actualPIN= mysql_query("SELECT * FROM lifepin WHERE IdUsu = '$idEncontrado' and Tipo NOT IN (select Id from tipopin where Agrup=9) and (IdMED IN (select idmed from doctorslinkdoctors where idmed2= '$IdMed' and idmed NOT IN (select distinct(IdDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor='$IdMed'))) or IdMED IS NULL)");
						//$countactualPIN=mysql_num_rows($actualPIN);
						$countactualPIN=0;
						$NReports=$countactualPIN."/".$countPIN;

					echo '<tr class="CFILA" id="'.$rowUSU['Identif'].'"><td>'.$rowUSU['IdUsFIXED'].'</td>';
					echo '<td>'.$rowUSU['Name'].'</td>';
					echo '<td><a href="#">'.$rowUSU['Surname'].'  '.$medalla.'</a></td>';
					echo '<td>'.$rowUSU['email'].'</td>';
					echo '<td>'.$NReports.'</td></tr>';
				}
			}
			//}




	}
}*/

########OLD CODE##########
// 1: Se meten los PACIENTES que han sido creados por este médico y los que han dado autorización expresa (tabla DOCTORSlinkUSERS)
/*$resultPRE = mysql_query("SELECT * FROM doctorslinkusers WHERE IdMED= '$IdMed'");  // Acota los usuarios a los que se tiene acceso directo
while ($rowPRE = mysql_fetch_array($resultPRE)) {

$idEncontrado = $rowPRE['IdUs'];
$resultUSU = mysql_query("SELECT * FROM usuarios WHERE Identif = '$idEncontrado' AND Surname like '%$queUsu%' ");
//$resultUSU = mysql_query("SELECT * FROM usuarios WHERE Identif = '$idEncontrado'");
$rowUSU = mysql_fetch_array($resultUSU);
if ($rowUSU['Surname']!='')
	{
	$ArrayPacientes[$numeral]=$idEncontrado;
	$numeral++;

	$resultPIN = mysql_query("SELECT * FROM lifepin WHERE IdUsu = '$idEncontrado' ");
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
$resultPRE = mysql_query("SELECT * FROM doctorslinkdoctors WHERE IdMED= '$IdMed'");  // Acota los usuarios a los que se tiene acceso directo
while ($rowPRE = mysql_fetch_array($resultPRE))
	{
		$nuevoMed = $rowPRE['IdMED2'];
		$resultPRE2 = mysql_query("SELECT * FROM doctorslinkusers WHERE IdMED= '$nuevoMed'");  // Acota los usuarios a los que se tiene acceso directo este médico
		while ($rowPRE2 = mysql_fetch_array($resultPRE2)) {

			$idEncontrado = $rowPRE2['IdUs'];
			$resultUSU = mysql_query("SELECT * FROM usuarios WHERE Identif = '$idEncontrado' AND Surname like '%$queUsu%' ");
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

					$resultPIN = mysql_query("SELECT * FROM lifepin WHERE IdUsu = '$idEncontrado' ");
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
    

?>
