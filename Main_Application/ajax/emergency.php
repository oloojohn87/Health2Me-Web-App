<?php

//ini_set("display_errors",0);

require("environment_detailForLogin.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


//$user->telemedSetter();
//$QueEntrada = 1;
$userid = $_GET['id'];
$filename = "DoctorImage/".$userid.".jpg";
    
	if(!file_exists($filename))
	{
    	$filename = "PatientImage/defaultDP.jpg";
	}



$is_modal = false;
if(isset($_GET['modal']) && $_GET['modal'] == 1)
{
    $is_modal = true;
}

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                                                          
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

//select data from usarios table
$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $userid, PDO::PARAM_INT);
$result->execute();

$count = $result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);
$success ='NO';
//echo $count;
if($count==1){
	
    $UserID = $row['Identif'];
	$UserEmail= $row['email'];
    $UserName = $row['Name'];
    $UserSurname = $row['Surname'];
	$sex = $row['Sexo'];
    //$UserLogo = $row['ImageLogo'];
    $IdUsFIXED = $row['IdUsFIXED'];
    $IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
    $privilege=1;

}
//select data from basicemrdata
$result = $con->prepare("SELECT * FROM basicemrdata where idpatient=?");
$result->bindValue(1, $userid, PDO::PARAM_INT);
$result->execute();
$count = $result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);
if($count==1){
	
	$DOB = $row['DOB'];
	$phpdate = strtotime($DOB);
	$DOB = date( 'm-d-Y', $phpdate );
	$address = $row['Address'];
	$address2 = $row['Address2'];
	$city = $row['City'];
	$state = $row['state'];
	$country = $row['country'];
	$blood_type = $row['bloodType'];
}

//get allergy data
$query = $con->prepare("select * from p_immuno where deleted=0 and idpatient=? and AllerName !=''");
$query->bindValue(1, $userid, PDO::PARAM_INT);
$result = $query->execute();

$count=$query->rowCount();
$counter1 = 0; 

$cadena = '';
$rowCounter = 0;
$rowCounterAllergy = 0;
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;
$allergies_html ="";
$med_html="";
//intensity colors
$color[0] = "#ffe222";
$color[1] = "#ffc822";
$color[2] = "#ff9f22";
$color[3] = "#ff7522";
$color[4] = "#ff2222";
$color[5] = "#ff2222";
//code if there are no allergies
$no_allergies_html = '<div id="no-allergies">
							No Known Allergies 
						</div>';
$no_med_html = '<div id="no-allergies">
						Not Taking Medications
						</div>';

if ($count == 0) {
	$allergies_html = $no_allergies_html;
}

while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
	$Id[$counter1] = $row2['id'];
	$VaccCode[$counter1] = $row2['VaccCode'];
	$VaccName[$counter1] = $row2['VaccName'];
	$AllerCode[$counter1] = $row2['AllerCode'];
	$AllerName[$counter1] = $row2['AllerName'];
	$intensity[$counter1] = $row2['intensity'];
	$dateEvent[$counter1] = $row2['date'];
	$ageEvent[$counter1] = $row2['ageevent'];
	$deleted[$counter1] = $row2['deleted'];
	$exclaim = "";
	
	if (($row2['intensity'])>4) {
		$exclaim = "<span id='blink'>!</span>";
	}

	$allergies_html .="<div class='allergy-name' style='color:".$color[$row2['intensity']]."'>".$exclaim." ".$AllerName[$counter1]."</div>";	
}

//get medications info 
$query = $con->prepare("select * from p_medication where deleted=0 and idpatient=? ORDER BY numDays DESC");
$query->bindValue(1, $userid, PDO::PARAM_INT);
$result = $query->execute();

$med_count = $query->rowCount();
if ($med_count == 0) {
	$med_html = $no_med_html;
}
while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	$numDays = $row['numDays'];
	$drugname = $row['drugname'];
	$dossage = $row['dossage'];
	$med_html .= '<div class="drug-row" ><div class="drug-days" style="color:white;background-color:'.getColor($numDays).'";>'.$numDays.'</div>';
	$med_html .= '<div class="drug-div" style="color:grey">'.$drugname.'</div>';
	$med_html .= '<div class="drug-div" style="font-size:14px">'.$dossage.'</div></div>';
}

function getGenderIcon($gender)
{
	$path='images/PassportIcons/';
	switch($gender)
	{
		case 0:return '<img src="'.$path.'female_blue.png" style="height:30px;width:30px;margin-top:-10px" title="Female"></img>';
		case 1:return '<img src="'.$path.'male_blue.png" style="height:30px;width:30px;margin-top:-10px" title="Male"></img>';
		default:return '<img src="" style="height:30px;width:30px;margin-top:-10px" title="Gender Not Stored"></img>';;
	
	}

}
function getColor($numDays)
{
	if($numDays==-1)
	{
		return '#BCF5A9';
	}
	else if($numDays>=0 and $numDays<=10)
	{
		return '#9FF781';	
	}
	else if($numDays>=11 and $numDays<=30)
	{
		return '#82FA58';	
	}
	else if($numDays>=31 and $numDays<=90)
	{
		return '#64FE2E';	
	}
	else if($numDays>=91 and $numDays<=180)
	{
		return '#74DF00';	
	}
	else if($numDays>=181 and $numDays<=365)
	{
		return '#5FB404';	
	}
	else if($numDays>=366 and $numDays<=999)
	{
		return '#4B8A08';	
	}
	else
	{
		return '#38610B';	
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    <script src="js/jquery.min.js"></script>
	<script src="js/h2m_emergency.js"></script>
</head>
	
<body id = "body" style="background: #FFF; width: 100%;">
	<link rel="stylesheet" href="css/h2m_emergency.css">
	 <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<div id="header"><img src="images/notes_leaf.png"> <span>Emergency Medical Information</span></div>
		<div id="info-block">
			<div id="member-photo">
				<img src="<?php echo $filename;?>">
			</div>
			<div id="user-data">
				<div id="user-name"><span ><?php echo $UserName." ".$UserSurname; ?></span><?php echo " ".getGenderIcon($sex);?></div>
				<div id="BasicInfo">
				<span><?php echo $address; ?></span>
				<span><?php if ($city != "") {
								echo $city.", ";
						} 
						echo $state; ?></span>
				<span><?php echo $country; ?></span>
				<span>DOB : <?php echo $DOB; ?></span>
				<span>Blood Type: <?php echo $blood_type; ?></span>

				</div>	
			</div>
	</div>	
	<div id="allergies" class="container">
	
		<div id="allergy-data" class="data-block">
			<div id="allergy-header" class="header">Allergies
			
			</div>	
			<div id="allergy-list">
				<?php echo $allergies_html;?>
			</div>	
			
		</div>	
	
	</div>
	<div id="medication" class="container">
		
		<div id="medication-data" class="data-block">
			<div id="medication-header" class="header">Medications
			
		</div>	
			<div class="data-list">
				<?php echo $med_html;?>
			</div>	
		</div>	
	
	</div>
	<div id="conditions" class="container">
		
		<div  class="data-block">
			<div id="conditions-header" class="header">Personal History
			</div>	
				
			<div id="conditions-data" class="data-list">			
				<div id="DiagnosticHistoryInfo"></div>
			</div>	
		</div>	
	
	</div>
	<div id="habits" class="container">
		<div  class="data-block">

		
			<div id="habits-header" class="header">Habits
			</div>	
			<div id="habits-data" class="data-list">
				<div id="HabitsInfo"></div>
			</div>	
		</div>	
	
	</div>
	<div id="family" class="container">
		
		<div id="family-data"  class="data-block">
			<div id="family-header" class="header">Family History
			</div>	
			<div id="family-data" class="data-list">
				<div id="FamilyHistoryInfo"></div>
			
		</div>	
	
	</div>
	</div>	

	<div id="vaccination" class="container">
		<div id="vaccination-data" class="data-block">
			<div id="vaccination-header" class="header">Vaccination Calendar
			</div>	
			<div id="vaccination-list"  class="data-list">
				<div id="ImmunizationAllergyInfo"></div>
			</div>
		</div>	
	</div>
	<div id="print-button" onclick="printDiv('body')">PRINT</div>			
	<input type="hidden" id="UserHidden" value="<?php echo $userid; ?>">

	
	
</body>	
</html>