<?php
//Author: Kyle Austin
//Date: 12/2/14
//Purpose: To build a piggy back verification process for bank accounts.  
//Basically we charge two small amounts to their card and they verify those amounts.  This verifies that the card they used is indeed theirs...

require("environment_detail.php");
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

$pat_id = $_POST['user'];
$first_charge = $_POST['charge1'] * 100;
$second_charge = $_POST['charge2'] * 100;

$res = $con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
$res->bindValue(1, $pat_id, PDO::PARAM_INT);
$res->execute();

$pat_row = $res->fetch(PDO::FETCH_ASSOC);

if(($pat_row['charge1'] == $first_charge || $pat_row['charge1'] == $second_charge) && ($pat_row['charge2'] == $first_charge || $pat_row['charge2'] == $second_charge)){
	$query = $con->prepare("UPDATE usuarios SET cc_verified = 1 WHERE Identif = ?");
	$query->bindValue(1, $pat_id, PDO::PARAM_INT);
	$query->execute();
	echo "Verified";
}else{
	$query = $con->prepare("UPDATE usuarios SET charge1=null, charge2=null WHERE Identif = ?");
	$query->bindValue(1, $pat_id, PDO::PARAM_INT);
	$query->execute();
	echo "You have entered the incorrect amounts.";
}

    


?>
