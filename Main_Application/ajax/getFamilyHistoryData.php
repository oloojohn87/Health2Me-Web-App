<?php
ini_set("display_errors", 0);
session_start(); 
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

//$queUsu = $_SESSION['UserID'];

if(isset($_GET['id'])){
	$queUsu = $_GET['id'];
}else{
	$queUsu = $_SESSION['UserID'];
}

$query = $con->prepare("SELECT * FROM p_family WHERE idpatient=?");
$query->bindValue(1, $queUsu, PDO::PARAM_INT);
$result = $query->execute();

$count = $query->rowCount();
$counter1 = 0 ;
$cadena = '';
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;

while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
if ($row2['deleted'] == 0)
	{
	
		$Id[$counter1] = $row2['id'];
		$relative[$counter1] = (htmlspecialchars($row2['relative']));
		$relativetype[$counter1] = (htmlspecialchars($row2['relativetype']));
		$disease[$counter1] = (htmlspecialchars($row2['disease']));
		$diseasegroup[$counter1] = (htmlspecialchars($row2['diseasegroup']));
		$ICD10[$counter1] = (htmlspecialchars($row2['ICD10']));
		$ICD9[$counter1] = (htmlspecialchars($row2['ICD9']));
		$atage[$counter1] = (htmlspecialchars($row2['atage']));
		$alive[$counter1] = (htmlspecialchars($row2['alive']));
		$deleted[$counter1] = $row2['deleted'];
		
		$doctor_signedP = (htmlspecialchars($row2['doctor_signed']));
		$latest_updateP = (htmlspecialchars($row2['latest_update']));
		if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}

	
		$counter1++;
	}
}
$query = $con->prepare("SELECT * FROM usuarios WHERE Identif=?");
$query->bindValue(1, $queUsu, PDO::PARAM_INT);
$result = $query->execute();


while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
	$Gender = (htmlspecialchars($row2['Sexo']));
}

echo '<style>';
echo 'div.FamRow{';
echo 'margin:0 auto; width:420px; height:35px; text-align:center; display:table-cell; vertical-align:middle;';
echo '  } ';
echo 'icon.si{';
echo '  display: inline-block; vertical-align: -webkit-baseline-middle; font-size: 18px; color:black;';
echo '  } ';
echo 'div.DiLabel{';
echo '  float:left; width: 30px; height:10px; line-height:8px; font-size:10px; background-color:red; color:white; text-align:center; margin-top:1px;';
echo '  } ';
echo 'div.DiLabelContainer{';
echo '  float:left; width: 30px; height:30px; border:0px solid #cacaca; margin-left:-5px;';
echo '  } ';
echo 'div.G{';
echo '  background-color:green;';
echo '  } ';
echo 'div.Female{';
echo '  float:left; margin:0 auto; border:1px solid #cacaca; width:20px; height:30px; border-radius:15px;';
echo '  } ';
echo 'div.Male{';
echo '  float:left; margin:0 auto; border:1px solid #cacaca; width:20px; height:30px;';
echo '  } ';
echo 'div.ML{';
echo '  border-color:#54bc00;';
echo '  } ';
echo 'div.PL{';
echo '  border-color:#22aeff;';
echo '  } ';
echo 'icon.ia{';
echo '  color:#e3e3e3;';
echo '  } ';
echo 'div.ia{';
echo '  border-color:#e3e3e3;';
echo '  } ';
echo 'div.separator{';
echo '  float:left; height:100%; margin-left:4px;';
echo '  } ';
echo 'div.separatorGroup{';
echo '  float:left; height:100%; margin-left:50px;';
echo '  } ';
echo '</style>';

echo '				<div id="FamilyInnerL" style="float:left; width:100%; height:100%; border:0px solid;">';
//echo '				<div  style="width:360px; height:16px; border:0px solid; text-align:center; background-color: #22aeff;color: white;line-height: 16px;">Family History</div>';
/*echo '				<div  style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #e74c3c; color: white;line-height: 20px;">';
	if($_COOKIE["lang"] == 'en'){
	echo 'Family History';
	}else{
	echo 'Antecedentes Familiares';
	}
	echo '</div>';*/
echo '				<div id="TableContainer" style="margin:0 auto; width:365px; margin-top:15px; border:0px solid #cacaca;">';
echo '					<table style="margin-top:5px;">';
// Grandparents Line
echo '						<tr style="padding:0px; margin:0px;" valign="middle">';
echo '							<td><div class="FamRow" style="">';
// Calculate dimension of grandparent box
$labelcount = 0;
$mgm = check('4');
if ($mgm[0] > -1) $labelcount++;
$mgp = check('3');
if ($mgp[0] > -1) $labelcount++;

$labelcount2 = 0;
$pgm = check('8');
if ($pgm[0] > -1) $labelcount2++;
$pgp = check('7');
if ($pgp[0] > -1) $labelcount2++;

$boxsize1 = (20*2) + (2*2) + 4 + (35*$labelcount) ;
$boxsize2 = (20*2) + (2*2) + 4 + (35*$labelcount2) ;
$bloksize =  50 + ($boxsize1 + $boxsize2);





echo '								<div style="margin:0 auto; width:'.$bloksize.'px; height: 100%;">';

//   	Maternal GRANDPARENTS
if ($mgm[0] == -1){

$subn = 0;
					while ($subn < sizeof($mgm)) {
					if(isset($alive)){
					if($alive[$mgm[$subn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					}else{
					$death_color = "background-color: white;";
					}
					$subn++;
					}

 	echo '		    						<div style="'.$death_color.'" class="Female ML ia" style=""><icon class="icon-female si ia"></div>';
}else 		{

					$subn = 0;
					while ($subn < sizeof($mgm)) {
					if(isset($alive)){
					if($alive[$mgm[$subn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					}else{
					$death_color = "background-color: white;";
					}
					$subn++;
					}
	
					echo '									<div style="'.$death_color.'" class="Female ML" style=""><icon class="icon-female si"></div>';
					echo '									<div class="DiLabelContainer">';
					$subn = 0;
					while ($subn < sizeof($mgm)) {  echo '<div class="DiLabel" style="background-color:'.SpeColor($diseasegroup[$mgm[$subn]]).'" title="'.$disease[$mgm[$subn]].' @ '.$atage[$mgm[$subn]].'">'.substr($disease[$mgm[$subn]],0,5).'</div> ';  $subn++;}
					echo '													</div>';
			};
echo '														<div class="separator"></div>';
if ($mgp[0] == -1){ 	

$subn = 0;
					while ($subn < sizeof($mgp)) {
					if(isset($alive)){
					if($alive[$mgp[$subn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					}else{
					$death_color = "background-color: white;";
					}
					$subn++;
					}


echo '									<div class="Male ML ia" style="'.$death_color.'"><icon class="icon-male si ia"></div>';
}else 		{

$subn = 0;
					while ($subn < sizeof($mgp)) {
					if(isset($alive)){
					if($alive[$mgp[$subn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					}else{
					$death_color = "background-color: white;";
					}
					$subn++;
					}

	
					echo '									<div class="Male ML" style="'.$death_color.'"><icon class="icon-male si"></div>';
					echo '									<div class="DiLabelContainer">';
					$subn = 0;
					while ($subn < sizeof($mgp)) {  echo '<div class="DiLabel" style="background-color:'.SpeColor($diseasegroup[$mgp[$subn]]).'" title="'.$disease[$mgp[$subn]].' @ '.$atage[$mgp[$subn]].'">'.substr($disease[$mgp[$subn]],0,5).'</div> ';  $subn++;}
					echo '													</div>';
			};

echo '									<div class="separatorGroup"></div>	';
//   	Paternal GRANDPARENTS
if ($pgm[0] == -1){ 

$subn = 0;
					while ($subn < sizeof($pgm)) {
					if(isset($alive)){
					if($alive[$pgm[$subn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					}else{
					$death_color = "background-color: white;";
					}
					$subn++;
					}

	echo '		    						<div class="Female PL ia" style="'.$death_color.'"><icon class="icon-female si ia"></div>';
}else 		{	

$subn = 0;
					while ($subn < sizeof($pgm)) {
					if(isset($alive)){
					if($alive[$pgm[$subn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					}else{
					$death_color = "background-color: white;";
					}
					$subn++;
					}

					echo '									<div class="Female PL" style="'.$death_color.'"><icon class="icon-female si"></div>';
					echo '									<div class="DiLabelContainer">';
					$subn = 0;
					while ($subn < sizeof($pgm)) {  echo '<div class="DiLabel" style="background-color:'.SpeColor($diseasegroup[$pgm[$subn]]).'" title="'.$disease[$pgm[$subn]].' @ '.$atage[$pgm[$subn]].'">'.substr($disease[$pgm[$subn]],0,5).'</div> ';  $subn++;}
					echo '													</div>';
			};
echo '														<div class="separator"></div>';
if ($pgp[0] == -1){

$subn = 0;
					while ($subn < sizeof($pgp)) {
					if(isset($alive)){
					if($alive[$pgp[$subn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					}else{
					$death_color = "background-color: white;";
					}
					$subn++;
					}

 	echo '									<div class="Male PL ia" style="'.$death_color.'"><icon class="icon-male si ia"></div>';
}else 		{

$subn = 0;
					while ($subn < sizeof($pgp)) {
					if(isset($alive)){
					if($alive[$pgp[$subn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					}else{
					$death_color = "background-color: white;";
					}
					$subn++;
					}
	
					echo '									<div class="Male PL" style="'.$death_color.'"><icon class="icon-male si"></div>';
					echo '									<div class="DiLabelContainer">';
					$subn = 0;
					while ($subn < sizeof($pgp)) {  echo '<div class="DiLabel" style="background-color:'.SpeColor($diseasegroup[$pgp[$subn]]).'" title="'.$disease[$pgp[$subn]].' @ '.$atage[$pgp[$subn]].'">'.substr($disease[$pgp[$subn]],0,5).'</div> ';  $subn++;}
					echo '													</div>';
			};

echo '								</div>';
echo '							</div></td>';
echo '						</tr>';
// End of Grandparents Line



// Parents Line
echo '						<tr style="padding:0px; margin:0px;" valign="middle">';
echo '							<td><div class="FamRow" >';

// Maternal Uncles/Aunts 
// Calculate dimension of Maternal U/A box
$labelcount = 0;
$labelcountU = 0;
$labelcountA = 0;
$mU = check('5');
if ($mU[0] > -1) $labelcountU = sizeof($mU);
$mA = check('6');
if ($mA[0] > -1) $labelcountA = sizeof($mA);
$labelcount = $labelcountU + $labelcountA;
$labelcount = 2; // Override calculation to set a fixed structure until we figure out how to make it better
//			BOX      				Border  	  			margin					LABEL
$boxsize1 = (20*$labelcount) + (2*$labelcount) +    (4*($labelcount-1)) +       (35*$labelcount) ;
$bloksize =  $boxsize1;
echo '								<div style="float:left; margin:0 auto; width:'.$bloksize.'px; height: 100%; style="overflow:auto;"">';
$nn = 0;
while ($nn < $labelcountU)
{	

					if($alive[$mU[$nn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					
	echo '<div class="Male ML" style="'.$death_color.'"><icon class="icon-male si"></div>';
	echo '<div class="DiLabelContainer">';
	echo '	<div class="DiLabel" style="background-color:'.SpeColor($diseasegroup[$mU[$nn]]).'" title="'.$disease[$mU[$nn]].' @ '.$atage[$mU[$nn]].'">'.substr($disease[$mU[$nn]],0,5).'</div> '; 
	echo '</div>';
	$nn++;
}
$nn = 0;
while ($nn < $labelcountA)
{	

					if($alive[$mA[$nn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}

	echo '<div class="Female ML" style="'.$death_color.'"><icon class="icon-female si"></div>';
	echo '<div class="DiLabelContainer">';
	echo '	<div class="DiLabel" style="background-color:'.SpeColor($diseasegroup[$mA[$nn]]).'" title="'.$disease[$mA[$nn]].' @ '.$atage[$mA[$nn]].'">'.substr($disease[$mA[$nn]],0,5).'</div> '; 
	echo '</div>';
	$nn++;
}
echo '								</div>';


// Calculate dimension of Parents box
$labelcount = 0;
$mo = check('2');
if ($mo[0] > -1) $labelcount++;
$po = check('1');
if ($po[0] > -1) $labelcount++;
$boxsize1 = (20*2) + (2*2) + 4 + (35*$labelcount) ;
$bloksize =  $boxsize1;
echo '								<div style="float:left; margin:0 auto; width:'.$bloksize.'px; height: 100%;">';
//   	MOTHER
if ($mo[0] == -1){

$subn = 0;
					while ($subn < sizeof($mo)) {
					if(isset($alive)){
					if($alive[$mo[$subn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					}else{
					$death_color = "background-color: white;";
					}
					$subn++;
					}

 	echo '		    						<div class="Female ML ia" style="'.$death_color.'"><icon class="icon-female si ia"></div>';
}else 		{	

$subn = 0;
while ($subn < sizeof($mo)) {
					if($alive[$mo[$subn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					$subn++;
					}

					echo '									<div class="Female ML" style="'.$death_color.'"><icon class="icon-female si"></div>';
					echo '									<div class="DiLabelContainer">';
					$subn = 0;
					while ($subn < sizeof($mo)) {  echo '<div class="DiLabel" style="background-color:'.SpeColor($diseasegroup[$mo[$subn]]).'" title="'.$disease[$mo[$subn]].' @ '.$atage[$mo[$subn]].'">'.substr($disease[$mo[$subn]],0,5).'</div> ';  $subn++;}
					echo '													</div>';
			};
echo '														<div class="separator"></div>';
if ($po[0] == -1){

$subn = 0;
while ($subn < sizeof($po)) {
					if(isset($alive)){
					if($alive[$po[$subn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					}else{
					$death_color = "background-color: white;";
					}
					$subn++;
					}

 	echo '									<div class="Male PL ia" style="'.$death_color.'"><icon class="icon-male si ia"></div>';
}else 		{	

$subn = 0;
while ($subn < sizeof($po)) {
					if($alive[$po[$subn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					$subn++;
					}

					echo '									<div class="Male PL" style="'.$death_color.'"><icon class="icon-male si"></div>';
					echo '									<div class="DiLabelContainer">';
					$subn = 0;
					while ($subn < sizeof($po)) {  echo '<div class="DiLabel" style="background-color:'.SpeColor($diseasegroup[$po[$subn]]).'" title="'.$disease[$po[$subn]].' @ '.$atage[$po[$subn]].'">'.substr($disease[$po[$subn]],0,5).'</div> ';  $subn++;}
					echo '													</div>';
			};
echo '								</div>';
// Paternal Uncles/Aunts 
// Calculate dimension of Paternal U/A box

$labelcount = 0;
$labelcountU = 0;
$labelcountA = 0;
$mU = check('9');
if ($mU[0] > -1) $labelcountU = sizeof($mU);
$mA = check('10');
if ($mA[0] > -1) $labelcountA = sizeof($mA);
$labelcount = $labelcountU + $labelcountA;
$labelcount = 2; // Override calculation to set a fixed structure until we figure out how to make it better
$boxsize1 = (20*$labelcount) + (2*$labelcount) +    (4*($labelcount-1)) +       (35*$labelcount) ;
$bloksize =  $boxsize1;
echo '								<div style="float:left; margin:0 auto; width:'.$bloksize.'px; height: 100%;">';
$nn = 0;
while ($nn < $labelcountU)
{	
if($alive[$mU[$nn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}

	echo '<div class="Male PL" style="'.$death_color.'"><icon class="icon-male si"></div>';
	echo '<div class="DiLabelContainer">';
	echo '	<div class="DiLabel" style="background-color:'.SpeColor($diseasegroup[$mU[$nn]]).'" title="'.$disease[$mU[$nn]].' @ '.$atage[$mU[$nn]].'">'.substr($disease[$mU[$nn]],0,5).'</div> '; 
	echo '</div>';
	$nn++;
}
$nn = 0;
while ($nn < $labelcountA)
{	
if($alive[$mU[$nn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}

	echo '<div class="Female PL" style="'.$death_color.'"><icon class="icon-female si"></div>';
	echo '<div class="DiLabelContainer">';
	echo '	<div class="DiLabel" style="background-color:'.SpeColor($diseasegroup[$mA[$nn]]).'" title="'.$disease[$mA[$nn]].' @ '.$atage[$mA[$nn]].'">'.substr($disease[$mA[$nn]],0,5).'</div> '; 
	echo '</div>';
	$nn++;
}
echo '								</div>';


echo '							</div></td>';
echo '						</tr>';
// End of Parents Line
// Own Generation Line
echo '						<tr style="padding:0px; margin:0px;" valign="middle">';
echo '							<td><div class="FamRow" style=";">';
// SISTERS
// Calculate dimension of SISTERS box
$labelcount = 0;
$labelcountS = 0;
$mS = check('12');
if ($mS[0] > -1) $labelcountS = sizeof($mS);
$labelcount = 2; // Override calculation to set a fixed structure until we figure out how to make it better
$boxsize1 = (20*$labelcount) + (2*$labelcount) +    (4*($labelcount-1)) +       (35*$labelcount) ;
$bloksize =  $boxsize1;
echo '								<div style="float:left; margin:0 auto; width:'.$bloksize.'px; height: 100%;">';
$nn = 0;
while ($nn < $labelcountS)
{	

if($alive[$mS[$nn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
	echo '<div class="Female" style="'.$death_color.'"><icon class="icon-female si"></div>';
	echo '<div class="DiLabelContainer">';
	echo '	<div class="DiLabel" style="background-color:'.SpeColor($diseasegroup[$mS[$nn]]).'" title="'.$disease[$mS[$nn]].' @ '.$atage[$mS[$nn]].'">'.substr($disease[$mS[$nn]],0,5).'</div> '; 
	echo '</div>';
	$nn++;
}
echo '								</div>';
// SISTERS
// ME
$boxsize1 = 20;
$bloksize =  $boxsize1;
if($_COOKIE["lang"] == 'en'){
$holder = 'Me';
}else{
$holder = 'Yo';
}
echo '								<div style="float:left; margin:0 auto; margin-left:25px; width:'.$bloksize.'px; height: 100%;">';
if ($Gender == 0)  echo '<div class="Female" style=""><icon class="icon-female si"></div>'; else  echo '<div class="Male" style=""><icon class="icon-male si"></div><div class="DiLabelContainer" style="height:10px;"><div class="DiLabel" style="background-color:grey;" title="'.$holder.'">'.$holder.'</div></div></div>'; 
echo '								</div>';
// ME
// BROTHERS
// Calculate dimension of BROTHERS box
$labelcount = 0;
$labelcountS = 0;
$mS = check('11');
if ($mS[0] > -1) $labelcountS = sizeof($mS);
$labelcount = 2; // Override calculation to set a fixed structure until we figure out how to make it better
$boxsize1 = (20*$labelcount) + (2*$labelcount) +    (4*($labelcount-1)) +       (35*$labelcount) ;
$bloksize =  $boxsize1;
echo '								<div style="float:left; margin:0 auto; margin-left:80px; width:'.$bloksize.'px; height: 100%;">';
$nn = 0;
while ($nn < $labelcountS)
{	
if($alive[$mS[$nn]] == 1){
					$death_color = "background-color: grey;";
					}else{
					$death_color = "background-color: white;";
					}
					
	echo '<div class="Male" style="'.$death_color.'"><icon class="icon-male si"></div>';
	echo '<div class="DiLabelContainer">';
	echo '	<div class="DiLabel" style="background-color:'.SpeColor($diseasegroup[$mS[$nn]]).'" title="'.$disease[$mS[$nn]].' @ '.$atage[$mS[$nn]].'">'.substr($disease[$mS[$nn]],0,5).'</div> '; 
	echo '</div>';
	$nn++;
}
echo '								</div>';
// BROTHERS


echo '							</div></td>';
echo '						</tr>';



echo '					</table>';
echo '					</div>';

echo '<input id="Fdoctor_signed" value="'.$doctor_signed.'" style="width:20px; float:left; display:none;">';	
echo '<input id="Flatest_update" value="'.$latest_update.'" style="width:120px; float:left; display:none;">';	




function check($relativename)
{
	$n = 0;
	$count = 0;
	$found[0] = -1;
	while ($n < ($GLOBALS['counter1'] ))
	{	
		$compar = $GLOBALS['relativetype'][$n];
		if ($compar == $relativename) 
		{
			$found[$count] = $n;
			$count++;
		} 
		$n++;
	}
	return $found;
}

function SpeColor($index)
{

	$acolor[0]='black';   			$aname[0]='n/a';
	$acolor[1]='#b36ae2';   		$aname[1]='Neuro';
	$acolor[2]='#b313e2';   		$aname[2]='Otolaryngo';
	$acolor[3]='#6fc040';  			$aname[3]='Endocrino';
	$acolor[4]='#f39019';   		$aname[4]='Digestive';
	$acolor[5]='#164f86';   		$aname[5]='Pneumo';
	$acolor[6]='#c82606';  			$aname[6]='Cardio';
	$acolor[7]='#c81654';   		$aname[7]='Uro';
	$acolor[8]='#167c86';   		$aname[8]='Repro';
	$acolor[9]='#899b57';   		$aname[9]='Dermo';
	$acolor[10]='#53585f'; 			$aname[10]='Onco';
	$acolor[11]='#289b9d';   		$aname[11]='Trauma';

return $acolor[$index];


}


?>