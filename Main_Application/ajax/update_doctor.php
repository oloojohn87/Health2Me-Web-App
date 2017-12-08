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

$doc_id = $_POST['doc_id'];

if(isset($_POST['new_pw']))
{
    $new_pw = $_POST['new_pw'];
    $hash = create_hash($new_pw);
    $hash_info = explode(":", $hash);
    //Commented by Pallabmysql_query("UPDATE doctors SET IdMEDRESERV='".$hash_info[3]."', salt='".$hash_info[2]."' WHERE id=".$doc_id);
  
    //Start of new code added by Pallab
    $query = $con->prepare("UPDATE doctors SET IdMEDRESERV=?, salt=? WHERE id=?");
    $query->bindValue(1,$hash_info[3],PDO::PARAM_STR);
    $query->bindValue(2,$hash_info[2],PDO::PARAM_STR);
    $query->bindValue(3,$doc_id,PDO::PARAM_INT);
    $query->execute();
    //End of new code added by Pallab
    
    echo $hash;
}
?>