<?php
set_time_limit ( 300 );
require_once("Services/Twilio.php");
require("environment_detailForLogin.php");
require("PasswordHash.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

//$app_key = 'd869a07d8f17a76448ed';
//$app_secret = '92f67fb5b104260bbc02';
//$app_id = '51379';
//$pusher = new Pusher($app_key, $app_secret, $app_id);
$push = new Push();

$API_VERSION = '2010-04-01';
$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
// Instantiate a new Twilio Rest Client
$client = new Services_Twilio($AccountSid, $AuthToken);

if(((isset($_GET['Digits']) && $_GET['Digits'] == "0") || isset($_GET['not_me'])) && isset($_REQUEST['CallStatus']) && $_REQUEST['CallStatus'] == 'in-progress' )
{
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<Response>';
    if(!isset($_GET['not_me']))
    {
        $push->send('Telemedicine', 'ameridoc', 'Incoming Call Detected');
        //echo '<Say>Hello and Welcome to Health 2 me.</Say>';
        echo '<Say language="es-MX">Hola, y Bienvenido a LLama al Doctor.</Say>';
    }
    
    echo '<Gather timeout="10" action="http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc.php?login=1" method="GET" numDigits="12"><Say>Please enter the phone number associated with your account.</Say></Gather>';
    echo '</Response>';
}
else if(isset($_REQUEST['Digits']) && isset($_GET['login']) && isset($_REQUEST['CallStatus']) && $_REQUEST['CallStatus'] == 'in-progress' )
{
    $digits = $_REQUEST['Digits'];
    $phone = $_REQUEST['Digits'];
    //$pin = substr($_REQUEST['Digits'], strlen($_REQUEST['Digits']) - 4, 4);
    $verified = 0;
    
    // verify inputed data
    $result = $con->prepare("SELECT Identif,Name,Surname,pin_hash,GrantAccess FROM usuarios WHERE telefono LIKE ?");
	$result->bindValue(1, '%'.$phone.'%', PDO::PARAM_STR);
	$result->execute();
	
    $num = $result->rowCount();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    /*if($num > 0)
    {
        if(validate_password($pin, $row['pin_hash']))
        {
            $verified = 1;
            $pusher->trigger('Telemedicine', 'ameridoc', 'User '.$row['Name'].' '.$row['Surname'].' Validated');
        }
    }*/
    
    if($num > 0)
    {
		if($row['GrantAccess'] == 'HTI-COL'){
		echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Gather action="http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc.php?not_me=1" numDigits="1" timeout="3" method="GET"><say voice="alice" language="es-MX">Encontramos que esta cuenta pertenece a '.$row['Name'].' '.$row['Surname'].'.  Por favor, oprima el cero si no es usted.</say></Gather>';
        //echo '<Redirect method="GET">http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?id='.$row['Identif'].'</Redirect>';
        echo '<Gather timeout="6" action="http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc.php?login2='.$phone.'" method="GET" numDigits="4"><Say voice="alice" language="es-MX">Por favor, introduzca su numero PIN de cuatro dígitos.</Say></Gather>';
        echo '</Response>';
		}else{
		echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Gather action="http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc.php?not_me=1" numDigits="1" timeout="3" method="GET"><Say>We found this account belongs to '.$row['Name'].' '.$row['Surname'].'. Please press zero if this is not correct.</Say></Gather>';
        //echo '<Redirect method="GET">http://'.$_SERVER['HTTP_HOST'].'/ameridoc_connect.php?id='.$row['Identif'].'</Redirect>';
        echo '<Gather timeout="6" action="http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc.php?login2='.$phone.'" method="GET" numDigits="4"><Say>Please enter your four digit pin number.</Say></Gather>';
        echo '</Response>';
		}
		
        
    }
    else
    {
	if($row['GrantAccess'] == 'HTI-COL'){
		echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Say voice="alice" language="es-MX">Lo sentimos, no se encontró una cuenta con ese número de teléfono.</Say>';
        echo '<Redirect method="GET">http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc.php?not_me=1</Redirect>';
        echo '</Response>';
		}else{
		echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Say>Sorry, no account with that phone number was found.</Say>';
        echo '<Redirect method="GET">http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc.php?not_me=1</Redirect>';
        echo '</Response>';
		}
		
        
    }
}else if(isset($_REQUEST['Digits']) && isset($_GET['login2']) && isset($_REQUEST['CallStatus']) && $_REQUEST['CallStatus'] == 'in-progress' ){
    $digits = $_REQUEST['Digits'];
    //$phone = substr($_REQUEST['Digits'], 0, strlen($_REQUEST['Digits']) - 4);
    $pin = $_REQUEST['Digits'];
    $verified = 0;
    
    // verify inputed data
    $result = $con->prepare("SELECT Identif,Name,Surname,pin_hash,GrantAccess FROM usuarios WHERE telefono LIKE ?");
	$result->bindValue(1, '%'.$_GET['login2'].'%', PDO::PARAM_STR);
	$result->execute();
	
    $num = $result->rowCount();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if($num > 0)
    {
        if(validate_password($pin, $row['pin_hash']))
        {
            $verified = 1;
            $push->send('Telemedicine', 'ameridoc', 'User '.$row['Name'].' '.$row['Surname'].' Validated');
        }
    }
    if($verified == 1)
    {
	if($row['GrantAccess'] == 'HTI-COL'){
		echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Say voice="alice" language="es-MX">Numero de PIN aceptado.</Say>';
        echo '<Redirect method="GET">http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc_connect.php?id='.$row['Identif'].'</Redirect>';
        //echo '<Gather timeout="3" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc.php?login2='.$$phone.'" method="GET" numDigits="10"><Say>Please enter your four digit pin number.</Say></Gather>';
        echo '</Response>';
		}else{
		echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Say>Pin number accepted.</Say>';
        echo '<Redirect method="GET">http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc_connect.php?id='.$row['Identif'].'</Redirect>';
        //echo '<Gather timeout="3" action="http://'.$_SERVER['HTTP_HOST'].'/ameridoc.php?login2='.$$phone.'" method="GET" numDigits="10"><Say>Please enter your four digit pin number.</Say></Gather>';
        echo '</Response>';
		}
        
    }
    else
    {
	if($row['GrantAccess'] == 'HTI-COL'){
		echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Say voice="alice" language="es-MX">Lo sentimos, este numero pin no es correcto.</Say>';
        echo '<Redirect method="GET">http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc.php?not_me=1</Redirect>';
        echo '</Response>';
		}else{
		echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Say>Sorry, the pin number is not correct.</Say>';
        echo '<Redirect method="GET">http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc.php?not_me=1</Redirect>';
        echo '</Response>';
		}
		
        
    }
}else{
    $Phone = $_REQUEST['From'];
    
    $Phone = str_replace("+", "", $Phone);
    $result = $con->prepare("SELECT Identif,Name,Surname,GrantAccess FROM usuarios WHERE telefono=?");
	$result->bindValue(1, $Phone, PDO::PARAM_INT);
	$result->execute();
	
    $num = $result->rowCount();
    $is_in_system = 0;
    if($num > 0)
    {
        // caller is user in our system
        $push->send('Telemedicine', 'ameridoc', 'Incoming Call Detected');
        
        $is_in_system = 1;
        $row = $result->fetch(PDO::FETCH_ASSOC);
		
		if($row['GrantAccess'] == 'HTI-COL'){
		echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Gather timeout="5" action="http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc.php?not_me=1" method="GET" numDigits="1"><Say voice="alice" language="es-MX">Hola, '.$row['Name'].' '.$row['Surname'].', y bienvenidos a Llama al Doctor con Tecnología de Health 2 Mi. Si no es usted por favor marque cero.</Say></Gather>';
        echo '<Redirect method="GET">http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc_connect.php?id='.$row['Identif'].'</Redirect>';
        echo '</Response>';
		}else{
		$push->send('Telemedicine', 'ameridoc', 'User '.$row['Name'].' '.$row['Surname'].' Validated');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Gather timeout="5" action="http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc.php?not_me=1" method="GET" numDigits="1"><Say>Hello '.$row['Name'].' '.$row['Surname'].', and welcome to Health 2 me. If this is not you, please press zero.</Say></Gather>';
        echo '<Redirect method="GET">http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc_connect.php?id='.$row['Identif'].'</Redirect>';
        echo '</Response>';
		}
		
        
    }
    else
    {
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        if(!isset($_GET['not_me']))
        {
            $push->send('Telemedicine', 'ameridoc', 'Incoming Call Detected');
	         //echo '<Say>Hello and Welcome to Health 2 me.</Say>';
			 echo '<Say language="es-MX">Hola, y Bienvenido a LLama al Doctor.</Say>';
        }
        
		if($row['GrantAccess'] == 'HTI-COL'){
		echo '<Gather timeout="10" action="http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc.php?login=1" method="GET" numDigits="12"><Say voice="alice" language="es-MX">Por favor, introduzca el número de teléfono asociado a su cuenta.</Say></Gather>';
        echo '</Response>';
		}else{
		echo '<Gather timeout="10" action="http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc.php?login=1" method="GET" numDigits="12"><Say>Please enter the phone number associated with your account.</Say></Gather>';
        echo '</Response>';
		}
		
        
    }
}


?>
