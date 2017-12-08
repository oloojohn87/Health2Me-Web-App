<?php
session_start();
require('../environment_detail.php');

$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
$hardcode = $env_var_db['hardcode'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con) die ('Could not connect: ' .mysql_error());
                                                   
//$inputId = htmlentities($_POST['inputId']);

$search_term_query = "";
if(!empty(trim($_POST['query']))) {
    $query = "%".htmlentities($_POST['query'])."%";
    $search_term_query = " AND Name LIKE ? OR IdMEDEmail LIKE ?";
}

$searchsql = "SELECT id, phone, CONCAT(Name, ' ', Surname) AS name, IdMEDEmail AS email, hospital_name, location, speciality FROM doctors WHERE IdMEDEmail IS NOT NULL AND IdMEDEmail != 'was@qualys.com' AND Name IS NOT NULL AND TRIM(Name) != '' AND Name NOT LIKE '% %' AND Surname IS NOT NULL AND TRIM(Surname) != '' AND Surname NOT LIKE '% %'".$search_term_query." ORDER BY Surname DESC"; 

if(!$query) $searchsql .= " LIMIT 0, 20";                                      

$search_stmt = $con->prepare($searchsql);
                                                   
if($query) {
    $search_stmt->bindValue(1, $query, PDO::PARAM_STR);
    $search_stmt->bindValue(2, $query, PDO::PARAM_STR);
}
$search_stmt->execute();   
$search_result = $search_stmt->fetchAll(PDO::FETCH_ASSOC);

$json = json_encode($search_result);

echo $json;
?>                                                  
                                                   
                                                   
                                                   
                                                   