<?php
    header('Content-type: text/xml');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
 
    echo '<Response>';
    $IdRef = $_GET['IdRef'];
	//$TempoPass = $_GET['TempoPass'];
	$TempoPass = 'InsertedbyCode';

	
 require("environment_detail.php");
 require("PasswordHash.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

//DEBUG  $file = 'handlecall.txt';
//DEBUG  $current = file_get_contents($file);

    # @start snippet
    $user_pushed = (int) $_REQUEST['Digits'];
    # @end snippet
 
 
    switch ($user_pushed){
	    case 0:	echo '<Say language="en" voice="man" >We have registered that you selected 0 (invitation rejected).</Say>';
	    		break;
	    case 1:	echo '<Say language="en" voice="man" >We have registered that you selected 1 (invitation accepted).</Say>';
	    		echo '<Say language="en" voice="man" >Please proceed to download the mobile application using the link that we emailed to you.</Say>';
	    		// Connect to server and select databse.
				
				
				// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

				//echo '<Say language="en" voice="man" >debugging..... ID ='.$IdRef.'</Say>';
	    		
				
				$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
				$result->bindValue(1, $IdRef, PDO::PARAM_INT);
				$result->execute();
				
				
				while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
					$TempoPass = $row['TempoPass'];
					echo '<Say language="en" voice="man" >Your temporary password is '.$TempoPass.'</Say>';
					
				}
				$hashresult = explode(":", create_hash($TempoPass));
				$IdUsRESERV= $hashresult[3];
				$additional_string=$hashresult[2];
				$q = $con->prepare("UPDATE usuarios SET Password = ?,IdUsRESERV = ?, TempoPass = '', salt=?  WHERE Identif ='$IdRef' ");   
				$q->bindValue(1, $IdUsRESERV, PDO::PARAM_STR);
				$q->bindValue(2, $IdUsRESERV, PDO::PARAM_STR);
				$q->bindValue(3, $additional_string, PDO::PARAM_STR);
				$q->bindValue(4, $IdRef, PDO::PARAM_INT);
				$q->execute();
				
	    		break;
	    default:	echo '<Say language="en" voice="man" >Please, allowed selections are only 0 or 1.</Say>';
	    			echo '<Say language="en" voice="man" >Use email to do your selection instead.</Say>';
	    			//echo '<Redirect>callback.php</Redirect>';
	    		break;
    }
 
    if ($user_pushed>0 && $user_pushed<2)
    {

/*
	    $host="54.243.39.237"; // Host name
	    $username="jvinals"; // Mysql username
	    $password="ardiLLA98"; // Mysql password
	    $db_name="monimed"; // Database name
					
	    // Connect to server and select databse.
	    $link = mysql_connect("$host", "$username", "$password")or die("cannot connect");
	    mysql_select_db("$db_name")or die("cannot select DB");

	    $sql="INSERT INTO protocolodata SET IdUser = '$IdPac', IdProtocolo = 1, Fecha=NOW(), Dato1='$user_pushed', Via=1  ";
	    $q = mysql_query($sql);   
*/

    }
    echo '<Say language="en" voice="woman">Thank you!. Your answer has been recorded.</Say>';
    echo '<Say language="en" voice="woman">Gretings from Health 2 Me.</Say>';
    echo '<Say language="en" voice="woman">GoodBye!</Say>';

    echo '</Response>';
?>