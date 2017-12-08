<?php 
session_start();
require("../environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con) die('Could not connect: ' . mysql_error());


//DISTINGUISH TABLE BY REQUEST OF EACH PAGE
$table_name = null;

if (isset($_POST['tid'])) { 
    $id = 't.TID';
    $val = $_POST['tid'];
}

//GETTING ALL POSTED VALUES THROUGH HTMLENTITIES FUNCTION FOR SECURITY PUROPOSE
foreach($_POST as &$value) {
    $value = htmlentities($value);
}
unset($value);

$calsql = "INSERT INTO billing_transactions (TID, prevPay, prevAdj, balance) SELECT t.TID, IF(pa.adjustment = '', SUM(pa.payAmt), 0) AS prevPay, IF(pa.payment = '', SUM(pa.payAmt), 0) AS prevAdj, (t.totAmt - SUM(pa.payAmt)) AS balance FROM billing_transactions t LEFT JOIN billing_pay_adj pa ON t.TID = pa.TID WHERE $id = ? ON DUPLICATE KEY UPDATE prevPay = VALUES(prevPay), prevAdj = VALUES(prevAdj), balance = VALUES(balance)";

$calstmt = $con->prepare($calsql);
$calstmt->bindValue(1, $val);
$result = null;
try {
    $result = $calstmt->execute();
} catch (PDOException $e) {
    echo $e->getMessage();
} finally {
    echo $result;
}

/*print_r($insertsql);
echo '';
print_r($params);

$insertResult = $con->prepare($insertsql); */ 
?>