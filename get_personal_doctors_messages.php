<?php

/*
 *  This file obtains all of the messages from a user's personal doctor
 */

require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$doc = $_POST['doctor'];
$pat = $_POST['patient'];
$tofrom = 'from';
if(isset($_POST['tofrom']))
{
    $tofrom = $_POST['tofrom'];
}

// if the user passed in a search query, add it to the mysql query
$search = "";
$search_query = '';
if(isset($_POST['search']) && strlen($_POST['search']) > 0)
{
    $search = $_POST['search'];
    $search_query = 'AND (content LIKE ? OR Subject LIKE ?)';
}

// obtain all of the messages from this doctor
$result = $con->prepare("SELECT * FROM message_infrastructureuser WHERE sender_id = ? AND patient_id = ? AND tofrom = ? ".$search_query." ORDER BY fecha DESC");
$result->bindValue(1, $doc, PDO::PARAM_INT);
$result->bindValue(2, $pat, PDO::PARAM_INT);
$result->bindValue(3, $tofrom, PDO::PARAM_STR);
if(strlen($search) > 0)
{
    // if the search query is set, bind it to the mysql query
    $result->bindValue(4, '%'.$search.'%', PDO::PARAM_STR);
    $result->bindValue(5, '%'.$search.'%', PDO::PARAM_STR);
}
$result->execute();

$result_array = array();

while($row = $result->fetch(PDO::FETCH_ASSOC))
{
    // reformat the date to be more readable
    $date = new DateTime($row['fecha']);
    $row['fecha'] = $date->format('M j, Y g:i A');
    
    // add entry to the result array
    array_push($result_array, $row);
}

echo json_encode($result_array);

?>