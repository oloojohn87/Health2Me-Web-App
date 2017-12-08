<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$tbl_name="doctors"; // Table name

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

$result2 = $con->prepare("SELECT DISTINCT userid FROM session_log WHERE DATEDIFF(NOW(), fecha) <=  90 AND usertype IS NULL ORDER BY fecha DESC"); 
$result2->execute();  
$count = 0;
$count=$result2->rowCount();

$cadena = '';
//$cadena.=',';    
$cadena.='
    {
        "Users":"'.$count.'"
    }';    
    
$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 

?>