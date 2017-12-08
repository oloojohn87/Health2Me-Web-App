<?php
ini_set("display_errors", 0);
session_start();


 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$UserID = $_GET['IdUsu'];
$pass=$_SESSION['decrypt'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));


if (!$con)
{
  die('Could not connect: ' . mysql_error());
}		

//$basicQuery = "select insurance,concat(address,' ',Address2) as address,city,zip from basicemrdata where idpatient=$UserID";
$basicQuery = $con->prepare("select insurance,address,Address2,city,state,zip,doctor_signed,latest_update,bloodType,weight,weightType,height1,height2,heightType from basicemrdata where idpatient=?");
$basicQuery->bindValue(1, $UserID, PDO::PARAM_INT);


$contactQuery = $con->prepare("select telefono,email,Sexo from usuarios where identif=?");
$contactQuery->bindValue(1, $UserID, PDO::PARAM_INT);


$result=$basicQuery->execute();
$count = $basicQuery->rowCount();



if($count==1)
{
	$row = $basicQuery->fetch(PDO::FETCH_ASSOC);
	$compAdress = $row['address'];
	$insurance = capitalizeFirst(getString($row['insurance']));
	$address = getString(rtrim($compAdress));
	$city = getString($row['city']);
	$state = getString($row['state']);
	$zip = getString($row['zip']);
	$doctor_signed = $row['doctor_signed'];
	$latest_update = $row['latest_update'];
    $blood_type = $row['bloodType'];
    $weight = $row['weight'];
    $weight_type = $row['weightType'];
    $height1 = $row['height1'];
    $height2 = $row['height2'];
    $height_type = $row['heightType'];
    $height1type = "ft";
    $height2type = "in";
    if($height_type == 2)
    {
        $height1type = "m";
        $height2type = "cm";
    }
    $blood_type_signed = str_replace("pos", "+", $blood_type);
    $blood_type_signed = str_replace("neg", "-", $blood_type_signed);
    $real_weight_type = 'lb';
    if($weight_type == 2)
    {
        $real_weight_type = 'kg';
    }
}
else
{
    if($_COOKIE["lang"] == 'th')
    {
        $insurance = 'Desconocido';
        $address = 'Desconocido';
        $city = 'Desconocido';
	$state = 'Desconocido';
        $telefono = 'Desconocido';
        $email = 'Desconocido';
        $zip = 'Desconocido';
    }
    else
    {
        $insurance = 'Unknown';
        $address = 'Unknown';
        $city = 'Unknown';
	$state = 'Unknown';
        $telefono = 'Unknown';
        $email = 'Unknown';
        $zip = 'Unknown';
    }
}


$result=$contactQuery->execute();
$row = $contactQuery->fetch(PDO::FETCH_ASSOC);

$numbersOnly = preg_replace('/[^0-9,]|,[0-9]*$/','',$row['telefono']);
//echo 'ERROR '.$numbersOnly;
$numberOfDigits = strlen($numbersOnly);
if ($numberOfDigits == 7) {
    $tele = substr($numbersOnly, 0, 3) . '-' . substr($numbersOnly, 3, 4);
}elseif($numberOfDigits == 10){
    $tele = substr($numbersOnly, 0, 3) . '-' . substr($numbersOnly, 3, 3) . '-'.substr($numbersOnly, 6, 4);
}elseif($numberOfDigits == 11){
    $tele = substr($numbersOnly, 0, 1) . ' ' . substr($numbersOnly, 1, 3) . '-'.substr($numbersOnly, 4, 3) . '-'.substr($numbersOnly, 7, 4);
}elseif($numberOfDigits > 11){
    $tele = substr($numbersOnly, 0);
}else{
    $tele=null;
}

if ($tele!=null){
    $telefono = getString('+'.$tele);
}else
if($_COOKIE["lang"] == 'th')
{
$telefono = 'Desconocido';
}
else
    {
    $telefono = 'Unknown';
}

$gender = $row['Sexo'];
if($gender == '0')
{
    $gender = 'Female';
}
else if($gender == '1')
{
    $gender = 'Male';
}

$email = getString($row['email']);
    echo '<div style="width: 60px; height: 200px; float: right;">';
	   if($blood_type != 'none')
       {
        echo '<div style="width: 47px; height: 35px; padding-top: 35px; margin-right: 20px; margin-top: .5%; float: right; background-image: url(images/blood_drop.png); background-size: 100% 100%; color: #FFF; text-align: center; font-size: 16px;">'.$blood_type_signed.'</div>';
       }
        if($weight > 0)
       {
            $scale_margin_top = "22px";
            if($blood_type == 'none')
            {
                $scale_margin_top = "0px";
            }
        echo '<div style="width: 64px; height: 30px; padding-top: 30px; margin-right: 12px; margin-top: '.$scale_margin_top.'; float: right; background-image: url(images/scale.png); background-size: 100% 100%; color: #FFF; text-align: center; font-size: 16px;">'.$weight.' '.$real_weight_type.'</div>';
       }
        echo '</div>';
		echo '<table style="background:white;margin-top:.5%;margin-left:2%;">';
					/*echo '<tr>
						<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
						if($_COOKIE["lang"] == 'en'){
						echo 'Insurance : ';
						}else{
						echo 'Seguros:';
						}
						echo '</span></td>
						<td><span style="font-size:18px;color:#54bc00" id="inp-Insurance">'.$insurance.'</span></td>
					</tr>';*/
					
					echo '<tr>
						<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
						if($_COOKIE["lang"] == 'th'){
                            echo 'Dirección:';
						}
                        else{
                            echo 'Address : ';
						}
						echo '</span></td>
						<td><span style="font-size:18px;color:#54bc00" id="inp-Address">'.$address.'</span></td>
					</tr>
					<tr>
						<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
						if($_COOKIE["lang"] == 'th'){
                            echo 'Ciudad:';
						}else{
                            echo 'City : ';
						}
						echo '</span></td>
						<td><span style="font-size:18px;color:#54bc00" id="inp-City">'.$city.'</span></td>
					</tr>
					<tr>
					
						<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
						if($_COOKIE["lang"] == 'th'){
                            echo 'Estado : ';
						}else{
                            echo 'State : ';
						}
						echo '</span></td>
						<td><span style="font-size:18px;color:#54bc00" id="inp-State">'.$state.'</span></td>
					</tr>
					<tr>
						<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
						if($_COOKIE["lang"] == 'th'){
                            echo 'Telefono:';
						}else{
                            echo 'Phone : ';
						}
						echo '</span></td>
						<td><span style="font-size:18px;color:#54bc00" id="inp-Phone">'.$telefono.'</span></td>
					</tr>
					<tr>
						<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
						if($_COOKIE["lang"] == 'th'){
                            echo 'Email:';
						}else{
                            echo 'Email : ';
						}
						if(strlen($email) >= 25){
						$font_holder = '12';
						}else{
						$font_holder = '18';
						}
						echo '</span></td>
						<td><span style="font-size:'.$font_holder.'px;color:#54bc00" id="inp-Email">'.$email.'</span></td>
					</tr>
                    <tr>
						<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
						if($_COOKIE["lang"] == 'th'){
                            echo 'Sexo:';
						}else{
                            echo 'Gender : ';
						}
						echo '</span></td>
						<td><span style="font-size:18px;color:#54bc00" id="inp-Gender">'.$gender.'</span></td>
					</tr>
                    <tr>
						<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
						if($_COOKIE["lang"] == 'th'){
                            echo 'Altura:';
						}else{
                            echo 'Height : ';
						}
						echo '</span></td>
						<td><span style="font-size:18px;color:#54bc00" id="inp-GWeight">'.$height1.' '.$height1type.' '.$height2.' '.$height2type.'</span></td>
					</tr>
		<span style="font-size:18px;color:#54bc00;display:none;" id="inp-State">'.$state.'</span>
                    <input type="hidden" id="BLOODTYPE" value="'.$blood_type.'" />
                    <input type="hidden" id="WEIGHT" value="'.$weight.'" />
                    <input type="hidden" id="WEIGHTTYPE" value="'.$weight_type.'" />
                    <input type="hidden" id="HEIGHT1" value="'.$height1.'" />
                    <input type="hidden" id="HEIGHT2" value="'.$height2.'" />
                    <input type="hidden" id="HEIGHTTYPE" value="'.$height_type.'" />
		    <input type="hidden" id="STATE" value="'.$state.'" />
					
				</table>		';
	
	
echo '<input id="Adoctor_signed" value="'.$doctor_signed.'" style="width:20px; float:left; display:none;">';	
echo '<input id="Alatest_update" value="'.$latest_update.'" style="width:120px; float:left; display:none;">';	


function capitalizeFirst($string)
{
	return ucfirst(strtolower($string));
}

function getString($string)
{
	if($string==null || $string=='' || $string=='+' || preg_replace('/\s+/', '', $string) == ',')
	{
        if($_COOKIE["lang"] == 'th')
        {
            $holder = 'Desconocido';
        }
        else
        {
            $holder = 'Unknown';
        }
		return $holder;
	}
	else
	{
		return $string;
	}
}



?>

