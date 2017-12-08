<?php

 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

$medid = $_GET['docid'];

$result = $con->prepare("SELECT d.idgroup,g.Name,g.Address,g.ZIP from doctorsgroups d,groups g where d.idDoctor=? and d.idGroup=g.id");
$result->bindValue(1, $medid, PDO::PARAM_INT);
$result->execute();

echo  '<table class="table table-mod" id="grouptable" style="height:100px; width:600px; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
echo '<thead><tr id="FILA" class="CFILADoctor"><th style="width:100px;">Group Name</th><th style="width:200px;">Address</th><th style="width:60px;">ZIP</th></tr></thead>';
echo '<tbody>';

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

echo '<tr class="CFILADoctor" id="'.$row['idgroup'].'" style="height:10px; line-height:0;">';
echo '<td>'.$row['Name'].'<div style="float:right"><i id="GroupEdit_'.$row['idgroup'].'" class="CFILADoctorEdit icon-group" style="margin:0 auto; padding:2px; color:#54bc00;"></i><i id="CFILAEdit_'.$row['idgroup'].'" class="edit_group icon-edit" style="margin:0 auto; padding:2px; color:#54bc00;"></i></div></td>';
echo '<td>'.$row['Address'].'</td>';
echo '<td>'.$row['ZIP'].'</td>';
echo '</tr>';
}

echo '</tbody></table>';    
    

?>
