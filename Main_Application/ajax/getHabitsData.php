<?php
ini_set("display_errors", 0);
session_start(); 
 require("environment_detailForLogin.php");
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

if (isset($_GET['id'])) { 
	$UserID = $_GET['id'];
} else {
	$UserID = $_SESSION['UserID'];
}


$cigaretteIcon = "icon-magic";
$alcoholIcon = "icon-glass";
$exerciseIcon = "icon-heart";


$cigaretteColor = "rgba(34,174,255,1)";
$alcoholColor = "orange";
$exerciseColor = "#8000FF";






$Query= $con->prepare("select * from p_habits where idpatient=?");
$Query->bindValue(1, $UserID, PDO::PARAM_INT);
$result=$Query->execute();

$count = $Query->rowCount();




if($count==0)
{

	echo '<center>';
	if($_COOKIE["lang"] == 'en'){
	echo 'No Entries';
	}else{
	echo 'No Hay Entradas';
	}
	echo '</center><p><center><img style="margin-top:75px;" width="75px" src="images/icons/general_user_error_icon.png" alt="No Data Icon"></center>';
	echo '<input type="hidden" value="0" id="cig" ></input>  ';
	echo '<input type="hidden" value="0" id="alc" ></input>  ';
	echo '<input type="hidden" value="0" id="exer" ></input>  ';
	echo '<input type="hidden" value="0" id="slee" ></input>  ';
	echo '<input type="hidden" value="0" id="coff" ></input>  ';
	return;
}


$row = $Query->fetch(PDO::FETCH_ASSOC);

$cigarettes = $row['cigarettes'];
$alcohol =  $row['alcohol'];
$exercise =  $row['exercise'];	
$sleep =  $row['sleep'];
$coffee =  $row['coffee'];	


$doctor_signed = $row['doctor_signed'];
$latest_update = $row['latest_update'];

/*
$cigarettes = 5;
$alcohol =  25;
$exercise =  2;	
*/
echo '<input type="hidden" value="'.$cigarettes.'" id="cig" ></input>  ';
echo '<input type="hidden" value="'.$alcohol.'" id="alc" ></input>  ';
echo '<input type="hidden" value="'.$exercise.'" id="exer" ></input>  ';
echo '<input type="hidden" value="'.$sleep.'" id="slee" ></input>  ';
echo '<input type="hidden" value="'.$coffee.'" id="coff" ></input>  ';

echo '<input id="Hdoctor_signed" value="'.$doctor_signed.'" style="width:20px; float:left; display:none;">';	
echo '<input id="Hlatest_update" value="'.$latest_update.'" style="width:150px; float:left; display:none;">';	

if($_COOKIE["lang"] == 'en'){
	$perday = 'Per Day';
	$glweek = 'Glasses/Week';
	$hrweek = 'Hours/Week';
	$hrday = 'Hours/Day';
	$ciggy = 'No Cigarettes';
	$exer = 'No Exercise';
	$slp = 'No Sleep';
	}else{
	$perday = 'Por Dia';
	$glweek = 'Vasos/Semana';
	$hrweek = 'Horas/Semana';
	$hrday = 'Horas/Dia';
	$ciggy = 'No Cigarrillos';
	$exer = 'No Ejercicio';
	$slp = 'No Sueño';
	}

if($cigarettes > 0)
{
	$cigaretteLabel = $cigarettes.' '.$perday;
}
else
{
	$cigaretteLabel = $ciggy;
	//$cigarettes = 1;
}

if($alcohol > 0)
{
	$alcoholLabel = $alcohol.' '.$glweek;
}
else
{
	$alcoholLabel = 'No Alcohol';
	//$alcohol = 1;
}

if($exercise > 0)
{
	$exerciseLabel = $exercise.' '.$hrweek;
}
else
{
	$exerciseLabel = $exer;
	//$exercise = 1;
}

if($sleep > 0)
{
	$sleepLabel = $sleep.' '.$hrday;
}
else
{
	$sleepLabel = $slp;
	//$sleep = 1;
}

if($coffee > 0)
{
	$coffeeLabel = $coffee.' cups/Day';
}
else
{
	$coffeeLabel = 'No Coffee';
	//$coffee = 1;
}





if($cigarettes > 20)
{
	$cPercent = 100;
}
else
{
	$cPercent = $cigarettes/20*100;
}

if($alcohol > 50)
{
	$aPercent = 100;
}
else
{
	$aPercent = $alcohol/50*100;
}

if($exercise > 50)
{
	$ePercent = 100;
}
else
{
	$ePercent = $exercise/50*100;
}

if($sleep > 50)
{
	$sPercent = 100;
}
else
{
	$sPercent = $sleep/50*100;
}

if($coffee > 50)
{
	$coPercent = 100;
}
else
{
	$coPercent = $coffee/50*100;
}



   
echo '<script src="js/jquery.fittext.js"></script>';
echo '<script src="js/H2M_Graphs.js"></script>';

echo '<div style="height:0px; width:100%; float:left;"></div>';
echo '<div class="outer-container"><div id="container_smoking" class="habits_container" style=" height:150px; position:relative;display:inline-block;"></div>';
echo '<script>drawKnob($("#container_smoking"),"#54bc00",  20, '.$cigarettes.', "Tobacco","smoking_svg",0,1); </script></div>';

echo '<div class="outer-container"><div id="container_drinking" class="habits_container" style="border:0px solid #cacaca; margin-top:5px; height:150px; position:relative;"></div>';
echo '<script>drawKnob($("#container_drinking"),"#e74c3c",  30, '.$alcohol.', "Alcohol","drinking_svg",0,1); </script></div>';

echo '<div class="outer-container"><div id="container_fitness" class="habits_container" style="border:0px solid #cacaca; margin-top:5px;  height:150px; position:relative;"></div>';
echo '<script>drawKnob($("#container_fitness"),"#2c3e50",  15, '.$exercise.', "Exercise","fitness_svg",0,1); </script></div>';

echo '<div class="outer-container"><div id="container_sleep" class="habits_container" style="border:0px solid #cacaca; margin-top:5px; height:150px; position:relative;"></div>';
echo '<script>drawKnob($("#container_sleep"),"#18bc9c",  10, '.$sleep.', "Sleep","sleeping_svg",0,1); </script></div>';

 
?>
