<?php

require('../environment_detail.php');

$doc = $_POST['doc'];
$pat = $_POST['pat'];

$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
$domain = $env_var_db['hardcode'];
$local = $env_var_db['local'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();

$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);

$pass = $row_enc['pass'];

$reps = $con->prepare('SELECT * FROM lifepin AS LP LEFT JOIN tipopin TP ON LP.Tipo = TP.Id WHERE LP.IdUsu = ?');
$reps->bindValue(1, $pat, PDO::PARAM_INT);
$reps->execute();

$res = array();

while($rep = $reps->fetch(PDO::FETCH_ASSOC))
{
    $image = decrypt_files($rep['RawImage'], $doc, $pass);
    array_push($res, array("id" => $rep['IdPin'], "image" => $image, "color" => $rep['Color'], "type" => $rep['NombreCorto'], "date" => date('M j, Y', strtotime($rep['Fecha'])), "file" => $rep['RawImage']));
}
               
echo json_encode($res);

function decrypt_files($rawimage, $queMed, $pass)
{
    $ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
    $extensionR = strtolower(substr($rawimage,strlen($rawimage)-3,3));
    if($extensionR=='jpg')
    {
        $extension='jpg';
    }elseif($extensionR == 'gif'){
        $extension = 'gif';
    }else{
        $extension='png';
    }
    $filename = $domain.'temp/'.$queMed.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extension;	
    if (!file_exists($filename))  
    {
        $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in ".$local."PackagesTH_Encrypted/".$ImageRaiz.".".$extension." -out ".$local."temp/".$queMed."/PackagesTH_Encrypted/".$ImageRaiz.".".$extension);
    }

    return $ImageRaiz.'.'.$extension;
}


?>