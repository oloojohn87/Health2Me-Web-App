<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include('checkoutClass.php');

$user_id = $_GET['user_id'];
$med_id = $_GET['med_id'];
$includes = 'yes';

$checkout = new checkoutClass($user_id, $med_id, $includes);
?>
