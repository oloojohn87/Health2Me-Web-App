<?php

require("environment_detail.php");

$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}		

$search = '%'.$_POST['search'].'%';
$doc = $_POST['doc'];
$type = $_POST['type'];

$result = array();

if($type == 1)
{
    $query = $con->prepare("SELECT id,Name,Surname,IdMEDEmail FROM doctors WHERE (Name like ? OR Surname like ? OR IdMEDEmail like ?) AND id != ?");
    $query->bindValue(1, $search, PDO::PARAM_STR);
    $query->bindValue(2, $search, PDO::PARAM_STR);
    $query->bindValue(3, $search, PDO::PARAM_STR);
    $query->bindValue(4, $doc, PDO::PARAM_INT);
    $query->execute();
    while($row = $query->fetch(PDO::FETCH_ASSOC))
    {
        array_push($result, array("id" => $row['id'], "name" => $row['Name'], "surname" => $row['Surname'], "email" => $row['IdMEDEmail'], "type" => 1));
    }
}
else
{
    
    $query = $con->prepare("SELECT Identif,Name,Surname,email FROM usuarios WHERE Name like ? OR Surname like ? OR email like ?");
    $query->bindValue(1, $search, PDO::PARAM_STR);
    $query->bindValue(2, $search, PDO::PARAM_STR);
    $query->bindValue(3, $search, PDO::PARAM_STR);
    $query->execute();
    while($row = $query->fetch(PDO::FETCH_ASSOC))
    {
        array_push($result, array("id" => $row['Identif'], "name" => $row['Name'], "surname" => $row['Surname'], "email" => $row['email'], "type" => 2));
    }
}

echo json_encode($result);
