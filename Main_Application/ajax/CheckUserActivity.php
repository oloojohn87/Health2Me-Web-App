<?php

//   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
ini_set('display_errors',1);
error_reporting(E_ALL);

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
 

$tbl_name="user_activity"; // Table name

$userid=$_GET['userid'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$result = $con->prepare("select * from user_activity where IdUser=? ");
$result->bindValue(1, $userid, PDO::PARAM_INT);
$result->execute();

$count = $result->rowCount();
$countvalid = 0;

while($row = $result->fetch(PDO::FETCH_ASSOC))
{
    $Status = $row['Status'];
    $Dismiss = $row['Dismiss'];
    if ($Dismiss != '1') 
    {
        
       // echo (' ---Dismiss = '.$Dismiss.' ----');
        
        $idActivity[$countvalid] = $row['id'];
        $statusActivity[$countvalid] = $row['Status'];
        $dateActivity[$countvalid] = $row['Date'];
        $datechangeActivity[$countvalid] = $row['DateChange'];
        $iddoctor[$countvalid] = $row['IdDoctor'];
    
        $resultDoc = $con->prepare("select * from doctors where id=? ");
        $resultDoc->bindValue(1, $iddoctor[$countvalid], PDO::PARAM_INT);
        $resultDoc->execute();
        $rowDoc = $resultDoc->fetch(PDO::FETCH_ASSOC);
        
        $emailDoc = $rowDoc['IdMEDEmail'];
        $nameDoc = $rowDoc['Name'];
        $surnameDoc = $rowDoc['Surname'];
        $doctorTo[$countvalid] = $rowDoc['IdMEDEmail'];
        if ($nameDoc >'' && $surnameDoc>'') $doctorTo[$countvalid] = 'Dr. '.' '.ucfirst($nameDoc).' '.ucfirst($surnameDoc);
        
        $countvalid++;
    }
}

echo $count;
echo '*'.$countvalid.'*';

$n = 0;
while ($n < $countvalid ){
    echo $idActivity[$n];
    echo '*';
    echo $dateActivity[$n];
    echo '*';
    echo $datechangeActivity[$n];
    echo '*';
    echo $iddoctor[$n];
    echo '*';
    echo $doctorTo[$n];
    echo '*';
    echo $statusActivity[$n];
    echo '*';
    $n++;   
}


?>