<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="usuarios"; // Table name

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$queUsu = $_GET['Usuario'];
$NReports = $_GET['NReports'];
$MEDID = $_GET['MEDID'];
//$queUsu = 32;


$result = $con->prepare("SELECT * FROM doctors WHERE Surname like ? LIMIT 10");
$result->bindValue(1, '%'.$queUsu.'%', PDO::PARAM_INT);
$result->execute();


echo  '<table class="table table-mod" id="TablaPac">';
echo '<thead><tr id="FILA" class="CFILA"><th>Id-Fixed</th><th>Center</th><th></th><th>First Name</th><th>Last Name</th><th>Username</th><th>Linked?</th></tr></thead>';
echo '<tbody>';

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

$email = $row['IdMEDEmail'];
$default = "http://www.somewhere.com/homestar.jpg";
$size = 29;
$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
$hash = md5( strtolower( trim( $email ) ) );
$avat = 'identicon.php?size=29&hash='.$hash;

$ImgCentro = 'images/doctors/'.$row['ImageLogo'];

$IdUs= $row['id'];
echo '<tr class="CFILA" id="'.$row['id'].'"><td>'.$row['IdMEDEmail'].'</td>';
echo '<td><div class="avatar" style="border:none;  margin: 0 auto;"><img src="'.$ImgCentro.'" /></div></td>';
echo '<td><div class="avatar" style="border:none;  margin: 0 auto;"><img src="'.$avat.'" /></div></td>';
echo '<td>'.(htmlspecialchars($row['Name'])).'</td>';
echo '<td>'.(htmlspecialchars($row['Surname'])).'</td>';
echo '<td>'.(htmlspecialchars($row['IdMEDEmail'])).'</td>';

$result2 = $con->prepare("SELECT * FROM doctorslinkusers WHERE IdUs =? and IdMED = ? LIMIT 10");
$result2->bindValue(1, $IdUs, PDO::PARAM_INT);
$result2->bindValue(2, $MEDID, PDO::PARAM_INT);
$result2->execute();

$count=$result2->rowCount();
if ($count>0)
{
echo '<td style="text-align:center;"><div class="fam-accept" style="margin-right:10px;"></td></tr>';
}else
{
echo '<td style="text-align:center;" ><div class="view-button" style="display: inline-block;"id="'.$IdUs.'"><a href="#" class="btn  btn-mini" >Link</a></div></td></tr>';
}


//<div class="fam-accept" style="margin-right:10px;">
}

echo '</tbody></table>';    
    

?>