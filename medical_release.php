<?php

include("userConstructClass.php");
//$user = new userConstructClass();
//$user->pageLinks('UserDashboard.php');
session_start(); 
$UserID = $_SESSION['UserID'];
require_once("environment_detail.php");
require("PasswordHash.php");

 	$dbhost = $env_var_db['dbhost'];
 	$dbname = $env_var_db['dbname'];
 	$dbuser = $env_var_db['dbuser'];
 	$dbpass = $env_var_db['dbpass'];

	/*echo "Member Id ".$user->mem_id."<br>";
	echo "Patient Name : ".$_POST["patient_name"]."<br>";
	echo "Member Release : ".$_POST["member_name"]."<br>";
	echo "Member Street  : ".$_POST["member_street"]."<br>";
	echo "Member City : ".$_POST["city"]."<br>";
	echo "Member State : ".$_POST["state"]."<br>";
	echo "Member Zip : ".$_POST["zip"]."<br>";
	echo "Name : ".$_POST["name"]."<br>";
	echo "Social Security : ".$_POST["ssnum"]."<br>";
	echo "Date of birth : ".$_POST["dob"]."<br>";
	echo "Telephone : ".$_POST["telephone"]."<br>";
	echo "Sig Date : ".$_POST["sig-date"]."<br>";*/
	//echo "Record Type: ".$_POST["recordtype"]."<br>";

	//$member_id = $user->mem_id;
	$member_id = $_SESSION['UserID'];
	$doc_email  = $_POST["doc_email"];
	$patient_name  = $_POST["patient_name"];
	$member_name   = $_POST["member_name"];
	$member_street = $_POST["member_street"];
	$member_city   = $_POST["city"];
	$member_state  = $_POST["state"];
	$member_zip    = $_POST["zip"];
	//$patient_name  = $_POST["name"];
	//$patient_ssn   = $_POST["ssnum"];
	$patient_dob   = $_POST["dob"];
	$patient_phone  = $_POST["telephone"];
	$sig_date      = $_POST["sigdate"];
	$record_type   = $_POST["recordtype"];
	$doc_email  = $_POST["doc_email"];

	$hashresult = explode(":", create_hash($_POST['ssnum']));
	$patient_ssn= $hashresult[3];
	echo "Patient Ssn : ".$patient_ssn."<br>";

	// or the better way, using PHP filters
	$sig_img = filter_input(INPUT_POST, 'output', FILTER_UNSAFE_RAW);

	//INSERT DATA INTO DB
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
       
	if (!$con)
	{
    	die('Could not connect: ' . mysql_error());
	}	

$queryText = $con->prepare("INSERT INTO medical_release SET member_id=?,member_name=?,member_address1=?,member_city=?,member_zip=?,creation_time=NOW(),patient_phone=?,patient_name=?,patient_ssn=?,patient_dob=?,sig_date=?,sig_img=?,doc_email=?,record_type=?");

    $queryText->bindValue(1, $member_id, PDO::PARAM_INT);
    $queryText->bindValue(2, $member_name, PDO::PARAM_STR);
    $queryText->bindValue(3, $member_street, PDO::PARAM_STR);
    $queryText->bindValue(4, $member_city, PDO::PARAM_STR);
    $queryText->bindValue(5, $member_zip, PDO::PARAM_STR);
    $queryText->bindValue(6, $patient_phone, PDO::PARAM_INT);
	$queryText->bindValue(7, $patient_name, PDO::PARAM_STR);
	$queryText->bindValue(8, $patient_ssn, PDO::PARAM_STR);
	$queryText->bindValue(9, $patient_dob, PDO::PARAM_STR);
	$queryText->bindValue(10, $sig_date, PDO::PARAM_STR);
	$queryText->bindValue(11, $sig_img, PDO::PARAM_INT);
	$queryText->bindValue(12, $doc_email, PDO::PARAM_STR);
	$queryText->bindValue(12, $record_type, PDO::PARAM_STR);
    $result = $queryText->execute();

?>