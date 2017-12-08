<?php
session_start();
require("../environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con) die('Could not connect: ' . mysql_error());

$id = null;

if (isset($_POST['tid_pay_adj'])) {
    $tid = htmlentities($_POST['tid_pay_adj']);
    $searchsql = "SELECT pa.PAID, CONCAT(pv.fname, ' ', pv.mname, ' ', pv.lname) AS doc, IF(pa.adjustment = '', 'Payment', 'Adjustment') AS trans, pa.date, pa.addDescr, pa.source, pa.payAmt, pa.TID FROM billing_pay_adj pa LEFT JOIN billing_transactions t ON pa.TID = t.TID LEFT JOIN billing_providerInfo pv ON t.PVID = pv.PVID WHERE pa.TID = ? ORDER BY pa.date DESC";
    $id = $tid;
}

$search_stmt = $con->prepare($searchsql);
$search_stmt->bindValue(1, $id, PDO::PARAM_INT);
$search_stmt->execute();   
$search_result = $search_stmt->fetchAll(PDO::FETCH_ASSOC);

$json = json_encode($search_result);

echo $json;
?>