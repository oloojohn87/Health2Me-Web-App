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

$UserID = $_GET['IdUsu'];



$cigaretteIcon = "icon-magic";
$alcoholIcon = "icon-glass";
$exerciseIcon = "icon-heart";


$cigaretteColor = "rgba(34,174,255,1)";
$alcoholColor = "orange";
$exerciseColor = "#8000FF";






$Query= $con->prepare("select * from p_habits where idpatient=? ORDER BY id DESC LIMIT 1");
$Query->bindValue(1, $UserID, PDO::PARAM_INT);
$result=$Query->execute();

$count = $Query->rowCount();




if($count==0)
{
	echo '<div  style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #3498db; color: white;line-height: 20px;">';
	if($_COOKIE["lang"] == 'th'){
        echo 'Hábitos';
	}else{
        echo 'Habits';
	}
	echo '</div><center>';
	if($_COOKIE["lang"] == 'th'){
        echo 'No Hay Entradas';
	}else{
        echo 'No Entries';
	}
	echo '</center><p><center><img style="margin-top:75px;" width="75px" src="../../images/icons/general_user_error_icon.png" alt="No Data Icon"></center>';
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
echo '<input id="Hlatest_update" value="'.$latest_update.'" style="width:120px; float:left; display:none;">';	

if($_COOKIE["lang"] == 'th'){
    $perday = 'Por Dia';
    $glweek = 'Vasos/Semana';
    $hrweek = 'Horas/Semana';
    $hrday = 'Horas/Dia';
    $ciggy = 'No Cigarrillos';
    $exer = 'No Ejercicio';
    $slp = 'No Sueño';
}else{
    $perday = 'Per Day';
    $glweek = 'Glasses/Week';
    $hrweek = 'Hours/Week';
    $hrday = 'Hours/Day';
    $ciggy = 'No Cigarettes';
    $exer = 'No Exercise';
    $slp = 'No Sleep';

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


//echo '<div  style="width:245px; height:16px; border:0px solid; text-align:center; background-color: #22aeff;color: white;line-height: 16px;">Habits</div>';
echo '<div  style="width:245px; height:20px; border:0px solid; text-align:center; background-color: #3498db; color: white;line-height: 20px;">';
if($_COOKIE["lang"] == 'th'){
    echo 'Hábitos';
    $tobacco_holder = 'Cigarrillos';
    $sleep_holder = 'Sueño';
    $exercise_holder = 'Ejercicio';
}else{
    echo 'Habits';
    $tobacco_holder = 'Tobacco';
    $sleep_holder = 'Sleep';
    $exercise_holder = 'Exercise';
}
echo'</div>';

   
echo '<script src="js/jquery.fittext.js"></script>';
echo '<script src="js/H2M_Graphs.js"></script>';
echo '<script src="https://connect.humanapi.co/connect.js"></script>';

echo "<script>
console.log('human_api LOADED!');
var token_object;

var connectBtn = document.getElementById('connect-health-data-btn');
connectBtn.addEventListener('click', function(e) {
  var opts = {
    // grab this from the app settings page
    clientId: '39c0e6576652e5863fd455383d3a8ecd9d897910',
    // can be email or any other internal id of the user in your system
    clientUserId: '".$_GET['IdUsu']."',
    finish: function(err, sessionTokenObject) {
      // When user finishes health data connection to your app
      // `finish` function will be called.
      // `sessionTokenObject` object will have several fields in it.
      // You need to pass this `sessionTokenObject` object to your server
      // add `CLIENT_SECRET` to it and send `POST` request to the `https://user.humanapi.co/v1/connect/tokens` endpoint.
      // In return you will get `accessToken` for that user that can be used to query Human API.
      //alert(sessionTokenObject);
      console.log(sessionTokenObject);
      sessionTokenObject['clientSecret'] = 'a1ea2091f8ce065e7ca404787daf49d4e92a88eb';
      $.post('https://user.humanapi.co/v1/connect/tokens', sessionTokenObject)
		.done(function(data){
			console.log(data);
		});
		
    },
    close: function() {
      // do something here when user just closed popup
      // `close` callback function is optional
      console.log(token_object);
    }
  }
  HumanConnect.open(opts);
  console.log(token_object);
});


</script>";

echo '<div style="height:0px; width:100%; float:left;"></div>';
echo '<div id="container_smoking" style="float:left; margin:0 auto; border:0px solid #cacaca; margin-top:5px; width:120px; height:120px; position:relative;"></div>';
echo '<script>drawKnob($("#container_smoking"),"#54bc00",  20, '.$cigarettes.', "'.$tobacco_holder.'","smoking_svg",0,1); </script>';

echo '<div id="container_drinking" style="float:left; margin:0 auto; border:0px solid #cacaca; margin-top:5px; width:120px; height:120px; position:relative;"></div>';
echo '<script>drawKnob($("#container_drinking"),"#e74c3c",  30, '.$alcohol.', "Alcohol","drinking_svg",0,1); </script>';

echo '<div id="container_fitness" style="float:left; margin:0 auto; border:0px solid #cacaca; margin-top:5px; width:120px; height:120px; position:relative;"></div>';
echo '<script>drawKnob($("#container_fitness"),"#2c3e50",  15, '.$exercise.', "'.$exercise_holder.'","fitness_svg",0,1); </script>';

echo '<div id="container_sleep" style="float:left; margin:0 auto; border:0px solid #cacaca; margin-top:5px; width:120px; height:120px; position:relative;"></div>';
echo '<script>drawKnob($("#container_sleep"),"#18bc9c",  10, '.$sleep.', "'.$sleep_holder.'","sleeping_svg",0,1); </script>';

echo '<button id="connect-health-data-btn" style="width: 90%; height: 30px; border: 0px solid #FFF; outline: none; background-color: #22AEFF; color: #FFF; float: left; margin-left: 4%; margin-top:10px; font-size: 18px; border-radius: 5px;"><i class="icon-gears"></i><span style="font-size: 12px;">&nbsp;&nbsp;&nbsp;Connect Fitness Device</span></button>';
    
    
?>
