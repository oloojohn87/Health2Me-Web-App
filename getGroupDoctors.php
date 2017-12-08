<?php

 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$queUsu = $_GET['Doctor'];

//$queUsu = 29;


//$result = mysql_query("SELECT Name, Surname from doctors where id IN (select idDoctor FROM doctorsgroups WHERE idGroup IN (select idGroup from doctorsgroups where idDoctor='$queUsu')) ");

$result = $con->prepare("SELECT idDoctor FROM doctorsgroups WHERE idGroup IN (SELECT idGroup FROM doctorsgroups WHERE idDoctor=?)");
$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->execute();

echo '<select id="doclist" style="margin-top:-5px;">';

while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
{
    $result2 = $con->prepare("SELECT Name, Surname FROM doctors WHERE id=?");
	$result2->bindValue(1, $row['idDoctor'], PDO::PARAM_INT);
	$result2->execute();
	
    $row2 = $result2->fetch(PDO::FETCH_ASSOC);
    if(isset($row2['Name']) && isset($row2['Surname']))
    {
        $name= $row2['Name'];
        $surname= $row2['Surname'];
        echo '<option value="'.$name.' '.$surname.'">'.$name.' '.$surname.'</option>';
    }
  
 // echo $name.' '.$surname;
  //echo '<br>';
}

echo '</select>';
    

?>