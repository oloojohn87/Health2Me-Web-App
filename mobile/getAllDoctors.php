<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("../environment_detail.php");
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="MESSAGES"; // Table name

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

//$setQuery = "SELECT * FROM ".$WhatDB." WHERE receiver_id=".$IdMED." AND DATEDIFF(NOW(), fecha) <=  60 AND status='new' ORDER BY fecha DESC";
//$setQuery = "SELECT * FROM ".$WhatDB." WHERE receiver_id=".$IdMED." ORDER BY fecha DESC LIMIT 10";
$result = mysql_query("SELECT * FROM doctors WHERE LENGTH(IdMEDEmail) > 0 AND LENGTH(IdMEDFIXEDNAME) > 4 AND phone!='8000000000' ORDER BY IdMEDFIXEDNAME");

$num_msg = 0;
$cadena = array();
$num_messages = mysql_num_rows($result);

while ($row = mysql_fetch_array($result)) {
    if(isset($row['IdMEDFIXEDNAME']) && $row['IdMEDFIXEDNAME'] != NULL && strlen($row['IdMEDFIXEDNAME']) > 0)
    {
        $sendername = $row['IdMEDFIXEDNAME'];
        $sendername = str_replace(".", " ", $sendername);
        array_push($cadena, $sendername);
    }
    if(isset($row['IdMEDEmail']) && $row['IdMEDEmail'] != NULL && strlen($row['IdMEDEmail']) > 0)
    {
        $mail = $row['IdMEDEmail'];
        array_push($cadena, $mail);
    }
    if(isset($row['phone']) && $row['phone'] != NULL && strlen($row['phone']) > 0)
    {
        $phone = $row['phone'];
        array_push($cadena, $phone);
    }

}

$encode = json_encode($cadena);
echo $encode; 




    
    

?>


