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
if (isset($_POST['ptid'])) {
    $ptid = htmlentities($_POST['ptid']);
    $searchsql = "SELECT * FROM billing_patients WHERE PTID = ?";
    $id = $ptid;
}
if (isset($_POST['icid'])) {
    $icid = htmlentities($_POST['icid']);
    $searchsql = "SELECT * FROM billing_insurCarriers WHERE ICID = ?";
    $id = $icid;
}
if (isset($_POST['pn'])) {
    $pn = htmlentities($_POST['pn']);
    $searchsql = "SELECT * FROM billing_insurPolicies WHERE PN = ?";
    $id = $pn;
}
if (isset($_POST['lcid'])) {
    $pn = htmlentities($_POST['lcid']);
    $searchsql = "SELECT * FROM billing_locationInfo WHERE LCID = ?";
    $id = $pn;
}
if (isset($_POST['probid'])) {
    $probid = htmlentities($_POST['probid']);
    $ptid = htmlentities($_POST['ptid']);
    $searchsql = "SELECT * FROM billing_probInfo WHERE ProbID = ? AND PTID = ?";
    $id = $probid;
}
if (isset($_POST['pvid'])) {
    $pvid = htmlentities($_POST['pvid']);
    $searchsql = "SELECT * FROM billing_providerInfo WHERE PVID = ?";
    $id = $pvid;
}
if (isset($_POST['pid'])) {
    $pid = htmlentities($_POST['pid']);
    $searchsql = "SELECT * FROM billing_insurPolicies WHERE PID = ?";
    $id = $pid;
}
if (isset($_POST['epid'])) {
    $epid = htmlentities($_POST['epid']);
    $searchsql = "SELECT * FROM billing_employers WHERE EPID = ?";
    $id = $epid;
}
if (isset($_POST['dgid'])) {
    $dgid = htmlentities($_POST['dgid']);
    $searchsql = "SELECT * FROM billing_diagnoses WHERE DGID = ?";
    $id = $dgid;
}
if (isset($_POST['ptid_trans_main'])) {
    $ptid = htmlentities($_POST['ptid_trans_main']);
    $searchsql = "SELECT PTID, fname, mname, lname, Carrier1ID, Carrier2ID, Carrier3ID, Carrier4ID, PBal, NPBal, UACredits, notes FROM billing_patients WHERE PTID = ?";
    $id = $ptid;
}
if (isset($_POST['pcid'])) {
    $pcid = htmlentities($_POST['pcid']);
    $searchsql = "SELECT * FROM billing_procedure_codes WHERE PCID = ?";
    $id = $pcid;
}
if (isset($_POST['mcode'])) {
    $mcode = htmlentities($_POST['mcode']);
    $searchsql = "SELECT * FROM billing_modifiers WHERE MCode = ?";
    $id = $mcode;
}
if (isset($_POST['ptid_trans'])) {
    $ptid = htmlentities($_POST['ptid_trans']);
    $searchsql = "SELECT t.TID, p.Prob, l.name AS LC, CONCAT(pv.fname, ' ', pv.mname, ' ', pv.lname) AS PV, pc.descr AS Proc, CONCAT(t.serviceDate, ' - ', t.SDThru) AS SDate, t.Amt, t.Units, t.totAmt FROM billing_transactions t LEFT JOIN billing_probInfo p ON t.ProbID = p.ProbID LEFT JOIN billing_locationInfo l ON t.LCID = l.LCID LEFT JOIN billing_providerInfo pv ON t.PVID = pv.PVID LEFT JOIN billing_procedure_codes pc ON t.ProcID = pc.PCID WHERE t.PTID = ? ORDER BY t.serviceDate DESC";
    // t.created DESC
    $id = $ptid;
}
if (isset($_POST['tid'])) {
    $tid = htmlentities($_POST['tid']);
    $searchsql = "SELECT * FROM billing_transactions WHERE TID = ?";
    $id = $tid;
}
if (isset($_POST['paid'])) {
    $paid = htmlentities($_POST['paid']);
    $searchsql = "SELECT * FROM billing_pay_adj WHERE PAID = ?";
    $id = $paid;
}

$search_stmt = $con->prepare($searchsql);
$search_stmt->bindValue(1, $id, PDO::PARAM_INT);
if(isset($_POST['probid'])) $search_stmt->bindValue(2, $ptid, PDO::PARAM_INT); 
$search_stmt->execute();   
if (isset($_POST['ptid_trans']) || isset($_POST['tid_pay_adj'])) $search_result = $search_stmt->fetchAll(PDO::FETCH_ASSOC);
else $search_result = $search_stmt->fetch(PDO::FETCH_ASSOC);

$json = json_encode($search_result);

echo $json;
?>