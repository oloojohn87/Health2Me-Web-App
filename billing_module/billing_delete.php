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
if (isset($_POST['SSN']) && !empty($_POST['SSN'])) { //WHEN BEING CALLED FROM billingForm.php  
    //DELETE THE CARRIER VALUES FROM $_POST  
    foreach(array('PTID', 'Carrier1', 'Carrier2', 'Carrier3', 'Carrier4', 'PV', 'LC', 'FC', 'refer', 'ATT', 'PCP') as &$v) {
        unset($_POST[$v]);
    }
    unset($v);    
    $table_name = "billing_patients";
}
if (isset($_POST['ICname']) && !empty($_POST['ICname'])) { //WHEN BEING CALLED FROM insuranceCompInfo.php 
    unset($_POST['ICID']);
    $table_name = "billing_insurCarriers";
}
if (isset($_POST['PN']) && !empty($_POST['PN'])) { //WHEN BEING CALLED FROM policyForm.php 
    unset($_POST['PID']);
    $table_name = "billing_insurPolicies";
}
if (isset($_POST['HCFAPlace']) && !empty($_POST['HCFAPlace'])) { //WHEN BEING CALLED FROM LocationForm.php 
    unset($_POST['LCID']);
    $table_name = "billing_locationInfo";
}
if (isset($_POST['Prob']) && !empty($_POST['Prob'])) { //WHEN BEING CALLED FROM probForm.php 
    unset($_POST['ProbID']);
    unset($_POST['PTID_hidden']);
    $table_name = "billing_probInfo";
}
if (isset($_POST['PVID']) && isset($_POST['SSNum']) && isset($_POST['UPIN'])) { //WHEN BEING CALLED FROM ProviderForm.php 
    unset($_POST['PVID']);
    $table_name = "billing_providerInfo";
}
if (isset($_POST['EPID']) && isset($_POST['notes'])) {
    unset($_POST['EPID']);
    $table_name = "billing_employers";
}
if (isset($_POST['PCID']) && isset($_POST['StdChrgAmt'])) {
    $table_name = "billing_procedure_codes";
}
if (isset($_POST['DGID']) && isset($_POST['AltProcCode2'])) {
    $table_name = "billing_diagnoses";
}
if (isset($_POST['MCode'])) {
    $table_name = "billing_modifiers";
}
if (isset($_POST['ProbID']) && isset($_POST['Units'])) {
    $table_name = "billing_transactions";
}
if (isset($_POST['PTID']) && isset($_POST['notes'])) {    
    $table_name = "billing_patients";
}
if (isset($_POST['paid']) && isset($_POST['delete'])) {    
    $id = 'PAID';
    $val = $_POST['paid'];
    $table_name = "billing_pay_adj";
}
if (isset($_POST['tid']) && isset($_POST['delete'])) { 
    $id = 'TID';
    $val = $_POST['tid'];
    $table_name = "billing_transactions";
}

//GETTING ALL POSTED VALUES THROUGH HTMLENTITIES FUNCTION FOR SECURITY PUROPOSE
foreach($_POST as &$value) {
    $value = htmlentities($value);
}
unset($value);

$deletesql = "DELETE FROM $table_name WHERE $id = ?";
$deleteStatement = $con->prepare($deletesql);    
$deleteStatement->bindValue(1, $val);

try {
    $deleteStatement->execute();
} catch (PDOException $e) {
    echo $e->getMessage();
} finally {
    echo 'Successfully Deleted!';
}

/*print_r($insertsql);
echo '';
print_r($params);

$insertResult = $con->prepare($insertsql); */ 
?>