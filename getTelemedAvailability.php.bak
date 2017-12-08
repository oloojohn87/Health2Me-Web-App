<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
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
																								
if(isset($_POST['set_on']) && isset($_POST['med_id']))
{
    $result = $con->prepare("UPDATE doctors SET telemed=? WHERE id=?");
	$result->bindValue(1, $_POST['set_on'], PDO::PARAM_INT);
	$result->bindValue(2, $_POST['med_id'], PDO::PARAM_INT);
	$result->execute();
	
}
else if(isset($_POST['get_telemed_info']) && $_POST['get_telemed_info'] == 1)
{
	$result = $con->prepare("SELECT speciality,location,hourly_rate FROM doctors WHERE id=?");
	$result->bindValue(1, $_POST['med_id'], PDO::PARAM_INT);
	$result->execute();
	
    $row = $result->fetch(PDO::FETCH_ASSOC);
    echo json_encode(array("speciality" => $row['speciality'], "location" => $row['location'], "rate" => $row['hourly_rate']));
}
else
{

    $query = $con->prepare("SELECT id,telemed,in_consultation FROM doctors ORDER BY surname, name");
    $result = $query->execute();
    $data = array();
    $symbols = array(":", "-", " ");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
    {
        if($row["telemed"] == 1)
        {
            //$query3 = 'SELECT userid FROM ongoing_sessions WHERE userid='.$row["id"];
            //$result3 = mysql_query($query3);
            //$num = mysql_num_rows($result3);
            $online = false;
            if(/*$num > 0 && */$row['in_consultation'] <= 0)
            {
                $online = true;
            }
            $data[($row["id"])] = $online;
        }
    }
    echo json_encode($data);
}


    
    

?>


