<?php
session_start();
require("../environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con) die('Could not connect: ' . mysql_error());

$query = htmlentities($_POST['query']);
$id = htmlentities($_POST['id']);

$ptid_hidden = htmlentities($_POST['ptid_hidden']);
$querysubjt = '';
//TO TAG THE CASE WITH MULTIPLE NAMES REQUIRED
$tag = false;
//TO FLAG THE PROB CASE
$probCase = false;
//TO FLAG PROCEDURE/DIAGNOSIS CODE CASE
$procCase = false;

if($id == "ProbID") {
    $name = "Prob AS name";
    $table = "billing_probInfo";
    $querysubjt_name = "PTID = ".$ptid_hidden." AND Prob";
    $probCase = true;
}
else if($id == "LCID") {  
    $name = "name";
    $table = "billing_locationInfo";
    $querysubjt_name = "name";
}
else if ($id == "PTID") {
    $name = "CONCAT(fname, ' ', mname, ' ', lname) AS name";
    $table = "billing_patients";
    $querysubjt_name = "fname LIKE ? OR mname LIKE ? OR lname";
    $tag = true;
}
else if ($id == "PVID") {
    $name = "CONCAT(fname, ' ', mname, ' ', lname) AS name";
    $table = "billing_providerInfo";
    $querysubjt_name = "fname LIKE ? OR mname LIKE ? OR lname";
    $tag = true;
}
else if ($id == "ICID") {
    $name = "ICname AS name";
    $table = "billing_insurCarriers";
    $querysubjt_name = "ICname";
}
else if ($id == "PID") {
    $name = "CONCAT(fname, ' ', mname, ' ', lname) AS name";   
    $table = "billing_insurPolicies";
    $querysubjt_name = "fname LIKE ? OR mname LIKE ? OR lname";
    $tag = true;
}
else if ($id == "EPID") {
    $name = "name";   
    $table = "billing_employers";
    $querysubjt_name = "name";
}
else if ($id == "PCID") {
    $name = "descr AS name";   
    $table = "billing_procedure_codes";
    $querysubjt_name = 'PCID LIKE ? OR descr ';
    $procCase = true;
}
else if ($id == "DGID") {
    $name = "descr AS name";   
    $table = "billing_diagnoses";
    $querysubjt_name = 'DGID LIKE ? OR descr ';
    $procCase = true;
}
else if ($id == "MCode") {
    $name = "descr AS name";   
    $table = "billing_modifiers";
    $querysubjt_name = 'MCode LIKE ? OR descr ';
    $procCase = true;
}

//flag FOR CALCULATING THE NUMBER OF NAMES' PARAMETERS FOR PDO
$flag = false;

if (is_numeric($query)) {   
    if ($probCase) $querysubjt = 'PTID = '.$ptid_hidden.' AND '.$id.' = ';
    else if ($procCase) {
        $query = '%'.$query.'%';
        $querysubjt = $querysubjt_name.' LIKE';
    }
    else $querysubjt = $id.' = ';
}
else {
    if ($tag) $flag = true;
    $query = '%'.$query.'%';
    $querysubjt = $querysubjt_name.' LIKE';
}

$searchsql = "SELECT $id, $name FROM $table WHERE $querysubjt ?";

$search_stmt = $con->prepare($searchsql);
$search_stmt->bindValue(1, $query);
if($flag || $procCase) {
    $search_stmt->bindValue(2, $query);
}
if ($flag) $search_stmt->bindValue(3, $query);
try {
    $search_stmt->execute();   
} catch (PDOException $e) {
    echo $e->getMessage();
}
$search_result = array();

while ($row = $search_stmt->fetch(PDO::FETCH_ASSOC)) {
    $item = new stdClass();
    $item->id = $row[$id];
    $item->name = $row['name']; 
    array_push($search_result, $item);
}

$json = json_encode($search_result);

echo $json;
?>