<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 session_start();
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
																								
$queUsu = $_GET['searchString'];
$All=$_GET['All'];
$IdMed = $_GET['IdMed'];



$limitString = '';


if($queUsu==null or $queUsu==" " or $queUsu=="" or $queUsu==-111){
	$queUsu="";
	
}


if(isset($_GET['All']))
{
	$query = $con->prepare('select * from (SELECT distinct(IdUs) as idus FROM doctorslinkusers WHERE IdPin IS NULL and (Idmed=? or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= ?))) and Estado IN (2,null)
				UNION
				SELECT IdUs as idus FROM doctorslinkusers WHERE IdPin IS NOT NULL and (Idmed=? or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor=?))) and Estado IN (2,null) 
				UNION
				select distinct(IdPac) as idus from doctorslinkdoctors where idmed2= ? and estado=2
				)AS idusu ');
				$query->bindValue(1, $IdMed, PDO::PARAM_INT);
				$query->bindValue(2, $IdMed, PDO::PARAM_INT);
				$query->bindValue(3, $IdMed, PDO::PARAM_INT);
				$query->bindValue(4, $IdMed, PDO::PARAM_INT);
				$query->bindValue(5, $IdMed, PDO::PARAM_INT);
				
}
else
{
	$query = $con->prepare('select patientid as idus from probe where doctorid=?');
	$query->bindValue(1, $IdMed, PDO::PARAM_INT);
}









$count=0;

$result = $query->execute();
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

	$id = $row['idus'];
	$resultUSU = $con->prepare("SELECT * FROM usuarios WHERE Identif = ? and Surname like ?");
	$resultUSU->bindValue(1, $id, PDO::PARAM_INT);
	$resultUSU->bindValue(2, '%'.$queUsu.'%', PDO::PARAM_STR);
	$resultUSU->execute();
	$rowUSU =  $resultUSU->fetch(PDO::FETCH_ASSOC);
	
	if ($rowUSU['Surname']>'')
	{	
		$count++;
		//echo createRow($rowUSU['Identif'],$name,$rowUSU['email'],1);
		
	}

}
echo $count;
?>
