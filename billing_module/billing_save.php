<?php 
session_start();
require("../environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con) die('Could not connect: ' . mysql_error());

//DELETE THE SUBMIT BUTTON VALUE FROM $_POST
unset($_POST['insert']);

//DISTINGUISH TABLE BY REQUEST OF EACH PAGE
$table_name = $_POST['table'];
if ($table_name == 'billing_patients') { //WHEN BEING CALLED FROM patientForm.php  
    //DELETE THE CARRIER VALUES FROM $_POST  
    foreach(array('PTID', 'Carrier1', 'Carrier2', 'Carrier3', 'Carrier4', 'PV', 'LC', 'FC', 'refer', 'ATT', 'PCP') as &$v) {
        unset($_POST[$v]);
    }
    unset($v);    
}
if ($table_name == 'billing_insurCarriers') { //WHEN BEING CALLED FROM insuranceCompInfo.php 
    unset($_POST['ICID']);
}
if ($table_name == 'billing_isurPolicies') { //WHEN BEING CALLED FROM policyForm.php 
    unset($_POST['PID']);
}
if ($table_name == "billing_locationInfo") { //WHEN BEING CALLED FROM LocationForm.php 
    unset($_POST['LCID']);
}
if ($table_name == "billing_probInfo") { //WHEN BEING CALLED FROM probForm.php 
    unset($_POST['ProbID']);
    unset($_POST['PTID_hidden']);
}
if ($table_name == "billing_providerInfo") { //WHEN BEING CALLED FROM ProviderForm.php 
    unset($_POST['PVID']);
}
if ($table_name == "billing_employers") {
    unset($_POST['EPID']);
}

if ($table_name == "billing_transactions";)) {
    unset($_POST['LC']);
    unset($_POST['PV']);
    unset($_POST['Proc']);
    unset($_POST['LC']);
    if (empty($_POST['TID'])) unset($_POST['TID']);
}
if ($table_name == "billing_pay_adj") {    
    if (empty($_POST['PAID'])) unset($_POST['PAID']);
}

//GETTING ALL POSTED VALUES THROUGH HTMLENTITIES FUNCTION FOR SECURITY PUROPOSE
foreach($_POST as &$value) {
    $value = htmlentities($value);
}
unset($value);

//PARAMS
$field_name = $update_items = null;   
$i = 1;
$numCount = count($_POST);

foreach($_POST as $k => &$v) {
    //CATCH PHONE NUMBERS AND EXTRACT ONLY NUMBERS
    $search = array('(',')','-', ' ');
    if(strpos($k, 'PN') !== false || strpos(strtolower($k), 'phone') !== false || strpos($k, 'fax') !== false) $v = str_replace($search, "", $v);
    if ($i++ == $numCount) {
        $field_name .= $k; 
        $update_items .= $k.' = VALUES('.$k.')';
    }
    else {
        $field_name .= $k.', ';
        $update_items .= $k.' = VALUES('.$k.'), ';
    }
}
unset($v);
$place_holders = implode(',', array_fill(0, $numCount, '?'));

//USING A TRICK TO MAKE ASSOCIATIVE ARRAY $_POST TO NUMERIC NORMAL ARRAY $params
$post_array_buff = implode('~~~~~', $_POST); 
$params = explode('~~~~~', $post_array_buff);

$insertsql = "INSERT INTO $table_name ($field_name) VALUES ($place_holders) ON DUPLICATE KEY UPDATE $update_items";

$insertResult = $con->prepare($insertsql);    

try {
    $insertResult->execute($params);
} catch (PDOException $e) {
    echo $e->getMessage();
} finally {
    echo 'Successfully Saved!';
}

/*print_r($insertsql);
echo '';
print_r($params);

$insertResult = $con->prepare($insertsql); */ 
?>