<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require("environment_detail.php");
require_once('push_server.php');
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

$lang = $_POST['lang'];
$user = $_POST['user'];
$type = $_POST['type'];

if($type == 1)
{
    // doctor
    $upd = $con->prepare("UPDATE doctors SET language = ? WHERE id = ?");
    $upd->bindValue(1, $lang, PDO::PARAM_STR);
    $upd->bindValue(2, $user, PDO::PARAM_INT);
    $upd->execute();
    echo 1;
}
else
{
    // patient
    $upd = $con->prepare("UPDATE usuarios SET language = ? WHERE Identif = ?");
    $upd->bindValue(1, $lang, PDO::PARAM_STR);
    $upd->bindValue(2, $user, PDO::PARAM_INT);
    $upd->execute();
}

?>