<?php
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$tbl_name="license_loc_seals"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

if(isset($_GET['state']) && isset($_GET['country']))
{
    $loc_state = $_GET['state'];
    $loc_country = $_GET['country'];
    $loc_sql = $con->prepare("SELECT seal FROM license_loc_seals WHERE state = ? AND country = ?");
    $loc_sql->bindValue(1, $loc_state, PDO::PARAM_INT);
    $loc_sql->bindValue(2, $loc_country, PDO::PARAM_INT);
    $loc_sql->execute();
    $loc_row = $loc_sql->fetch(PDO::FETCH_ASSOC);
    $location_seal = $loc_row['seal'];

    echo $location_seal;
}
else
{
    
}
?>