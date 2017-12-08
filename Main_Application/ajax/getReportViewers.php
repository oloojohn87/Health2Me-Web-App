<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 
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

$queid = $_GET['id'];

//print_r("Type of queid:".gettype($queid));

$q=$con->prepare("select idusu,idcreator from lifepin where idpin=?");
$q->bindValue(1, $queid, PDO::PARAM_INT);
$res=$q->execute();

$row1 = $q->fetch(PDO::FETCH_ASSOC); //Changed $q->fetch to $res->fetch
$creator=$row1['idcreator'];
$patient = $row1['idusu'];


//$query1="select idmed from doctorslinkusers where  estado=2 and idpin=".$queid;
//$query2="select idDoctor from doctorsGroups where idGroup in (select idGroup from doctorsGroups where idDoctor=".$creator.")";
//$query3="select idmed2 from doctorslinkdoctors where idmed=".$creator." and idpac=".$patient." and estado=2";


$query= $con->prepare("select id,idmedfixedname from doctors where id in(select idmed from doctorslinkusers where  estado=2 and idpin=? 
UNION select idDoctor from doctorsgroups where idGroup in (select idGroup from doctorsgroups where idDoctor=?) 
UNION select idmed2 from doctorslinkdoctors where idmed=? and idpac=? and estado=2)"); //Changed doctorsGroups to doctorsgroups


$query->bindValue(1, $queid, PDO::PARAM_INT);
$query->bindValue(2, $creator, PDO::PARAM_INT);
$query->bindValue(3, $creator, PDO::PARAM_INT);
$query->bindValue(4, $patient, PDO::PARAM_INT);
$result = $query->execute();


echo  '<table class="table table-mod" id="TablaPacMODALViewers" style="height:200px; width:300px; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
echo '<thead><tr id="FILA1" class="CFILAMODAL"><th style="text-align: center;">Users with access to this report</th></tr></thead>';
echo '<tbody>';

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

	echo '<tr class="CFILAMODAL" id="'.$row['id'].'" style="height:10px; line-height:0;">';
		echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; width:120px;" >'.$row['idmedfixedname'].'</td>';
	

echo '</tr>';

}

echo '</tbody></table>';    
    

?>