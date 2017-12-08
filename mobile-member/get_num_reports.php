<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
require("../environment_detail.php");
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}		

$id = $_GET['id'];
$statement = $con->prepare('SELECT * FROM lifepin WHERE IdUsu = ? AND (markfordelete = 0 OR markfordelete is null) AND orig_filename not like "%Summary%" AND orig_filename not like "%Notes%"');
$statement->bindValue(1, $id, PDO::PARAM_INT);
$result = $statement->execute();
echo $statement->rowCount();

?>