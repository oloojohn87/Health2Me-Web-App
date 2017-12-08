<?php

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

$doc = $_POST['doctor'];
$pat = $_POST['patient'];
$add = $_POST['add'];

if($add == 1)
{
    $delete = $con->prepare("DELETE FROM doctorslinkusers WHERE IdMED = ? AND IdUs = ?");
    $delete->bindValue(1, $doc, PDO::PARAM_INT);
    $delete->bindValue(2, $pat, PDO::PARAM_INT);
    $delete->execute();
    
    $lifepin = $con->prepare("SELECT DISTINCT IdPin FROM lifepin WHERE IdUsu = ? AND IdPin IS NOT NULL");
    $lifepin->bindValue(1, $pat, PDO::PARAM_INT);
    $lifepin->execute();
    
    $num_lifepin = $lifepin->rowCount();
    
    $idpins = null;
    if(strlen($_POST['idpins']) == 0)
    {
        $idpins = array();
    }
    else
    {
        $idpins = explode("_", $_POST['idpins']);
    }
    $num_files = count($idpins);
    
    if($num_files == 0)
    {
        $delete = $con->prepare("DELETE FROM doctorslinkusers WHERE IdMED = ? AND IdUs = ?");
        $delete->bindValue(1, $doc, PDO::PARAM_INT);
        $delete->bindValue(2, $pat, PDO::PARAM_INT);
        $delete->execute();
        echo 0;
    }
    else if($num_files >= $num_lifepin)
    {
        $insert = $con->prepare("INSERT INTO doctorslinkusers (IdUs, IdMED, IdPIN, Fecha, estado) VALUES (?, ?, NULL, NOW(), 1)");
        $insert->bindValue(1, $pat, PDO::PARAM_INT);
        $insert->bindValue(2, $doc, PDO::PARAM_INT);
        $insert->execute();
        echo $num_files;
    }
    else
    {   
        $query = "INSERT INTO doctorslinkusers (IdUs, IdMED, IdPIN, Fecha, estado) VALUES";
        
        for($i = 0; $i < $num_files; $i++)
        {
            if($i > 0)
                $query .= ", ";
            else
                $query .= " ";
            $query .= "(?,?,?,NOW(),1)";
        }

        if($num_files > 0)
        {
            $insert = $con->prepare($query);
            for($i = 0 ; $i < $num_files; $i++)
            {
                $insert->bindValue(($i * 3) + 1, $pat, PDO::PARAM_INT);
                $insert->bindValue(($i * 3) + 2, $doc, PDO::PARAM_INT);
                $insert->bindValue(($i * 3) + 3, $idpins[$i], PDO::PARAM_INT);
            }
            $insert->execute();
            echo $num_files;

        }
    }
}
else
{
    $delete = $con->prepare("DELETE FROM doctorslinkusers WHERE IdMED = ? AND IdUs = ?");
    $delete->bindValue(1, $doc, PDO::PARAM_INT);
    $delete->bindValue(2, $pat, PDO::PARAM_INT);
    $delete->execute();
    echo 0;
}
?>
