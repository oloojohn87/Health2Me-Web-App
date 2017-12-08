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
$NReports = $_GET['NReports'];
//$IdMed = $_GET['IdMed'];
//$queUsu = 32;


$result = $con->prepare("SELECT * FROM usuarios WHERE Surname like ?");
$result->bindValue(1, '%'.$queUsu.'%', PDO::PARAM_STR);
$result->execute();

echo  '<table class="table table-mod" id="TablaPac">';
echo '<thead><tr id="FILA" class="CFILA"><th>Id-Fixed</th><th>First Name</th><th>Last Name</th><th>Username</th><th>N. Reports</th></tr></thead>';
echo '<tbody>';

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

echo '<tr class="CFILA" id="'.$row['Identif'].'"><td>'.$row['IdUsFIXED'].'</td>';
echo '<td>'.$row['Name'].'</td>';
echo '<td>'.$row['Surname'].'</td>';
echo '<td>'.$row['email'].'</td>';
echo '<td>'.$NReports.'</td></tr>';

}

echo '</tbody></table>';    
    

?>