<?php

require("environment_detail.php");
require("PasswordHash.php");
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

$user_id = $_POST['pat_id'];

if(isset($_POST['new_pw']))
{
    $new_pw = $_POST['new_pw'];
    $hash = create_hash($new_pw);
    $hash_info = explode(":", $hash);
    
    //Commented by Pallab as a part of PDO changes mysql_query("UPDATE usuarios SET IdUsRESERV='".$hash_info[3]."', salt='".$hash_info[2]."' WHERE Identif=".$user_id);
    $query = $con->prepare("UPDATE usuarios SET IdUsRESERV=?, salt=? WHERE Identif=?");
    $query->bindValue(1,$hash_info[3],PDO::PARAM_STR);
    $query->bindValue(2,$hash_info[2],PDO::PARAM_STR);
    $query->bindValue(3,$user_id,PDO::PARAM_INT);
    $query->execute();
    
    
    echo $hash;
}
?>