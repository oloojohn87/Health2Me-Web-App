<?PHP
require("environment_detail.php");
require("PasswordHash.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

//$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$counter1 = 0;

// CONNECTION TO HTI EXTERNAL DATABASE
 $dbhost = '67.225.182.18';
 $dbname = 'doctor_web';
 $dbuser = 'doctor_web';
 $dbpass = 'dallas2013';

//$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	
// CONNECTION TO HTI EXTERNAL DATABASE



$result = mysql_query("SELECT * FROM pedidos"); // WHERE IdPatient = '$UserID'");
while ($row = mysql_fetch_array($result)) {

if ($row['sexo'] == 1) $gender=1; else $gender=0;

$newnombre = strtolower (str_replace(' ', '', $row['nombre']));
$newapellidos = strtolower (str_replace(' ', '', $row['apellidos']));


$IdUsFIXED = substr($row['fechaNacimiento'],0,4).substr($row['fechaNacimiento'],5,2).substr($row['fechaNacimiento'],8,2).$gender.'0';
$IdUsFIXEDNAME =$newnombre.'.'. $newapellidos;


$Users['FIXEDNAME'][$counter1]= $IdUsFIXEDNAME;         
$Users['FIXED'][$counter1]= $IdUsFIXED;         
$Users['NAME'][$counter1]= $row['nombre'];         
$Users['SURNAME'][$counter1]= $row['apellidos'];         
$Users['PHONE'][$counter1]= $row['telefono'];         
$Users['EMAIL'][$counter1]= $row['email'];         
$Users['DOB'][$counter1]= $row['fechaNacimiento'];         
$Users['GENDER'][$counter1]= $row['sexo'];         

$counter1++;
}


// Check IDs in H2M User Database
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

//$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$counter2=0;
$cadena = '';

$n=0;
while ($n < $counter1)
{
	$maxHit[$n] = 0;
	$minHit[$n] = 100;
	//$result = mysql_query("SELECT Identif,Name,Surname,email,telefono,IdUsFIXED,IdUsFIXEDNAME,IdUsRESERV,salt,pin_hash FROM usuarios"); // WHERE IdPatient = '$UserID'");
	$found[$n] = 0;
	$compfixed[$n] = 0;
	$compstring[$n]='';
	$result = mysql_query("SELECT * FROM usuarios"); // WHERE IdPatient = '$UserID'");
	while ($row = mysql_fetch_array($result)) {
		similar_text($Users['FIXEDNAME'][$n], $row['IdUsFIXEDNAME'], $percent); 
		if ($percent > $maxHit[$n])  { 
			$maxHit[$n] = $percent; 
			$hitname = $row['IdUsFIXEDNAME'];
		}	
		$percent2 = 0;	
		if ($maxHit[$n]>95)
		{
			// Check numeric IdUsFIXED
			similar_text($Users['FIXED'][$n], $row['IdUsFIXED'], $percent2); 
			if ($percent2 > 90) 
			{
				//echo ' MATCH  --'.$percent2.'--'.$Users['FIXED'][$n].' **  '.$row['IdUsFIXED'].'     -//    ';
				$found[$n] = 1;
				$compfixed[$n]=$percent2;
				$compstring[$n]=$row['IdUsFIXED'];
			}
		}
	}

	//$lev = levenshtein($Users['FIXEDNAME'][$n], $row['IdUsFIXEDNAME']);
	if ($found[$n] == 0)
	{
		/*
		echo ' WILL ADD '.$Users['FIXEDNAME'][$n].'                       Max Hit of '.$Users['FIXEDNAME'][$n].' IS : '.number_format($maxHit[$n], 2).' (Found in:'.$hitname.')';
		echo '</br>';
		$emailADD = '';
		*/		
	}		
	else
	{
		/*
		echo ' ***** ALREADY EXISTS ********** '.$Users['FIXEDNAME'][$n].'                       Max Hit of '.$Users['FIXEDNAME'][$n].' IS : '.number_format($maxHit[$n], 2).' (Found in:'.$hitname.')';
		echo '</br>';
		echo ' ***** ************** ********** FIXED CODE SIMILARITY = '.$compfixed[$n].'    SIMILAR FIXED FOUND = '.$compstring[$n];
		echo '</br>';
		*/
	}

	if ($n>0) $cadena.=',';    

	$confirm_code=md5(uniqid(rand()));
	
	$input = array("either", "eleven","else", "elsewhere", "empty", "enough", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "fifteen", "fify", "fill", "find", "fire", "first", "five",  "former", "formerly", "forty", "found", "four", "from", "front", "full", "further",  "give", "hasnt", "have");
	$input2 = array("Madrid","London","Paris","Rome","Tokio","Dallas","Portland","Chicago","Rabat","Jerusalem","Amman","Cairo","Athens","Oslo","Bogota","Ottawa","Beijing");

	$autopassword = substr($input[array_rand($input)],0,4).substr($input2[array_rand($input2)],0,4);
	$autopin = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
	
	//$hashresult = explode(":", create_hash($_POST['Password']));
	$hashresult = explode(":", create_hash($autopassword));
	$IdUsRESERV= $hashresult[3];
	$additional_string=$hashresult[2];
	$pin_hash = create_hash($autopin);

	$cadena.='
	{
        "ADD":"'.$found[$n].'",
        "SimName":"'.$maxHit[$n].'",
        "SimDOB":"'.$compfixed[$n].'",
        "name":"'.$Users['NAME'][$n].'",
		"surname":"'.$Users['SURNAME'][$n].'",
		"dob":"'.$Users['DOB'][$n].'",
		"gender":"'.$Users['GENDER'][$n].'",
        "IdUSFIXED":"'.$Users['FIXED'][$n].'",
        "IdUSFIXEDNAME":"'.$Users['FIXEDNAME'][$n].'",
        "PASS":"'.$autopassword.'",
        "PIN":"'.$autopin.'",
        "IdUSRESERV":"'.$IdUsRESERV.'",
        "salt":"'.$additional_string.'",
        "pin_hash":"'.$pin_hash.'",
        "email":"'.$Users['EMAIL'][$n].'",
		"phone":"'.$Users['PHONE'][$n].'"
    }';  


	$n++;
}

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 









?>