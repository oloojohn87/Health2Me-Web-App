<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
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

$queUsu = $_GET['Usuario']; // Cadena de Búsqueda
$NReports = $_GET['NReports'];
$MEDID = $_GET['MEDID'];
//$queUsu = 32;
$queEmail = $_GET['Email']; // Cadena de Búsqueda
$IdUsFIXED = $_GET['IdUsFIXED']; // Cadena de Búsqueda

$result = $con->prepare("SELECT * FROM usuarios WHERE Surname like '%$queUsu%' AND email like ? AND IdUsFIXED like ? LIMIT 10");
$result->bindValue(1, '%'.$queEmail.'%', PDO::PARAM_STR);
$result->bindValue(2, '%'.$IdUsFIXED.'%', PDO::PARAM_STR);
$result->execute();

//$result = mysql_query("SELECT * FROM usuarios WHERE Surname like '%$queUsu%' LIMIT 10");

echo  '<table class="table table-mod" id="TablaPac">';
echo '<thead><tr id="FILA" class="CFILA"><th>Id-Fixed</th><th>Avatar</th><th>First Name</th><th>Last Name</th><th>User</th><th>Linked?</th></tr></thead>';
echo '<tbody>';

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

$email = $row['email'];
$Password = $row['Password'];

$default = "http://www.somewhere.com/homestar.jpg";
$size = 29;
$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
$hash = md5( strtolower( trim( $email ) ) );
$avat = 'identicon.php?size=29&hash='.$hash;

if ($Password=='')
{
$huerfano ='<i class="fam-control-eject"></i>';
}
else
{
$huerfano ='';
}

$IdUs= $row['Identif'];
echo '<tr class="CFILA" id="'.$row['Identif'].'"><td>'.$row['IdUsFIXED'].'</td>';
echo '<td><div class="avatar" style="border:none;  margin: 0 auto;"><img src="'.$avat.'" /></div></td>';
echo '<td>'.$row['Name'].'</td>';
echo '<td>'.$huerfano.' '.$row['Surname'].'</td>';
echo '<td>'.$row['email'].'</td>';

$result2 = $con->prepare("SELECT * FROM doctorslinkusers WHERE IdUs =? and IdMED = ? LIMIT 10");
$result2->bindValue(1, $IdUs, PDO::PARAM_INT);
$result2->bindValue(2, $MEDID, PDO::PARAM_INT);
$result2->execute();

$count=$result2->rowCount();
$row3 = $result2->fetch(PDO::FETCH_ASSOC);

if ($count>0)
{
	$estado = $row3['estado'];

	if ($estado == 1)
	{
		//echo '<td style="text-align:center;"><div class="fam-email-link" style="margin-right:10px;"></td></tr>';
				echo '<td style="text-align:left;"><i class="fam-email-link"></i> Waiting for Patient</td></tr>';

		}else
	{
		echo '<td style="text-align:center;"><div class="fam-accept" style="margin-right:10px;"></td></tr>';
		}
}else
{
	$resultD = $con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdPac =? and IdMED = ? LIMIT 10");
	$resultD->bindValue(1, $IdUs, PDO::PARAM_INT);
	$resultD->bindValue(2, $MEDID, PDO::PARAM_INT);
	$resultD->execute();
	
	$countD=$resultD->rowCount();
	$rowD = $resultD->fetch(PDO::FETCH_ASSOC);
	if ($countD>0)
	{
		$estado = $rowD['estado'];
		if ($estado == 1)
			{		
				echo '<td style="text-align:left;"><i class="fam-email-open-image"></i> Waiting for Referral</td></tr>';
			}
			else
			{
				echo '<td style="text-align:center;"><div class="fam-bullet-blue" style="margin-right:10px;"></td></tr>';
			}
	
	}else
	{
	
	echo '<td style="text-align:center;" ><div class="view-button" style="display: inline-block;"id="'.$IdUs.'"><a href="#" class="btn  btn-mini" >Link</a></div></td></tr>';
		
	}

}


//<div class="fam-accept" style="margin-right:10px;">
}

echo '</tbody></table>';    
    

?>