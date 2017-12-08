<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('makeNewUserKeyClass.php');

$med_id = $_SESSION['MEDID'];
$type = 'confirm';
$maker_type = 'doctor';
$owner = 2158;

$key = new makeNewUserKeyClass($med_id, $owner, $type, $maker_type);
echo $key->key;
?>
