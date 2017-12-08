<?php

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

$search_query = '%'.$_POST['search'].'%';

$type = $_POST['type'];
$scope = $_POST['scope'];

$result = '';

if($type == 1)
{
    $val = 0;
    if($scope == 'NULL')
    {
        $val = 1;
    }
    else if($scope == 'HTI-COL')
    {
        $val = 3;
    }
    $scope_str = '';
    if($scope != 'Global')
    {
        $scope_str = ' AND previlege = ?';
    }
    // search all doctors
    $result = $con->prepare("SELECT id,Name,Surname,IdMEDEmail,phone,location,additional_licenses FROM doctors WHERE (IdMEDEmail LIKE ? OR CONCAT(Name, ' ', Surname) LIKE ?) AND Name NOT LIKE '%=%' AND Surname NOT LIKE '%=%'".$scope_str);
    $result->bindValue(1, $search_query, PDO::PARAM_STR);
    $result->bindValue(2, $search_query, PDO::PARAM_STR);
    if($scope != 'Global')
    {
        $result->bindValue(3, $val, PDO::PARAM_INT);
    }
    
}
else
{
    // search all patients
    $scope_str = '';
    if($scope != 'Global')
    {
        if($scope == 'NULL')
            $scope_str = ' AND GrantAccess IS NULL';
        else
            $scope_str = ' AND GrantAccess = ?';
    }
    $result = $con->prepare("SELECT Identif,Name,Surname,email,telefono,location FROM usuarios WHERE (email LIKE ? OR CONCAT(Name, ' ', Surname) LIKE ?) AND Name NOT LIKE '%=%' AND Surname NOT LIKE '%=%'".$scope_str);
    $result->bindValue(1, $search_query, PDO::PARAM_STR);
    $result->bindValue(2, $search_query, PDO::PARAM_STR);
    if($scope != 'Global' && $scope != 'NULL')
    {
        $result->bindValue(3, $scope, PDO::PARAM_STR);
    }
}
$result->execute();
$return = array();
while($row = $result->fetch(PDO::FETCH_ASSOC))
{
    array_push($return, $row);
}
echo json_encode($return);

?>
