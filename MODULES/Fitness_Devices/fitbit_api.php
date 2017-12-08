<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_GET['user_id'];

require '../fitbitphp-master/fitbitphp.php';

$fitbit = new FitBitPHP('cd567983ef3d434488fe00f7b89c5f5f', 'b1f228c46b7f44edab503d307ab5610c');

$fitbit->initSession('http://dev.health2.me/MODULES/Fitness_Devices/fitbit_callback.php?user='.$user_id);
	
$xml = $fitbit->getProfile();

print_r($xml);
?>
