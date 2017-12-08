<?php

require("environment_detail.php");
require("GetPatientsData.php");
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

$doc = $_GET['doc'];
$group = $_GET['group'];
$page = $_GET['page'];
$itemsPerPage = $_GET['itemsPerPage'];
$searchQuery = $_GET['searchQuery'];


$patients = new PatientsData($con, $doc, $searchQuery, $group, $page, $itemsPerPage);
$result = $patients->getPatients();

echo json_encode($result);



?>