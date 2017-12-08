<?php
session_start();
require("../environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con) die('Could not connect: ' . mysql_error());

$id = htmlentities($_POST['id']);
$table = htmlentities($_POST['table']);
$query = htmlentities($_POST['query']);

if($id == "LCID") $name = "LCID AS id, name";
elseif($id == "PVID") $name = "PVID AS id, CONCAT(fname, ' ', mname, ' ', lname) AS name";
elseif($id == "ICID") $name = "ICID AS id, ICname AS name, address1, address2, city, state, zip";
elseif($id == "DGID") $name = "DGID AS id, descr AS name, AltProcCode1, AltProcCode2, AgeSpec";
elseif($id == "PBID") $name = "ProbID AS id, Prob AS name";
elseif($id == "PCID") $name = "PCID AS id, descr AS name";
elseif($id == "MCode") $name = "MCode AS id, descr AS name";

//IT IS FOR THE DIAGNOSIS CALLING FROM THE TRANSACTION PAGE
if($id == "ProbID") $searchsql = "SELECT CONCAT(b.diag1, ': ', d1.descr) AS 'diag1-tran', CONCAT(b.diag2, ': ', d2.descr) AS 'diag2-tran', CONCAT(b.diag3, ': ', d3.descr) AS 'diag3-tran', CONCAT(b.diag4, ': ', d4.descr) AS 'diag4-tran' FROM billing_probInfo AS b LEFT JOIN (SELECT DGID, descr FROM billing_diagnoses WHERE DGID = (SELECT diag1 FROM billing_probInfo WHERE $id = ?)) AS d1 ON b.diag1 = d1.DGID LEFT JOIN (SELECT DGID, descr FROM billing_diagnoses WHERE DGID = (SELECT diag2 FROM billing_probInfo WHERE $id = ?)) AS d2 ON b.diag2 = d2.DGID LEFT JOIN (SELECT DGID, descr FROM billing_diagnoses WHERE DGID = (SELECT diag3 FROM billing_probInfo WHERE $id = ?)) AS d3 ON b.diag3 = d3.DGID LEFT JOIN (SELECT DGID, descr FROM billing_diagnoses WHERE DGID = (SELECT diag4 FROM billing_probInfo WHERE $id = ?)) AS d4 ON b.diag4 = d4.DGID WHERE b.$id = ?";

else if($id == 'PBID') $searchsql = "SELECT $name FROM $table WHERE ProbID = ?";
else $searchsql = "SELECT $name FROM $table WHERE $id = ?";

$search_stmt = $con->prepare($searchsql);
$search_stmt->bindValue(1, $query, PDO::PARAM_INT);
if($id == "ProbID") {
    $search_stmt->bindValue(2, $query, PDO::PARAM_INT);
    $search_stmt->bindValue(3, $query, PDO::PARAM_INT);
    $search_stmt->bindValue(4, $query, PDO::PARAM_INT);
    $search_stmt->bindValue(5, $query, PDO::PARAM_INT);
}
$search_stmt->execute();   
$search_result = $search_stmt->fetch(PDO::FETCH_ASSOC);

$json = json_encode($search_result);

echo $json;
?>