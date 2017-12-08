<?php
    header('Content-type: text/xml');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
 
    echo '<Response>';
    $IdRef = $_GET['IdRef'];

	
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
	    case 0:	echo '<Say language="en" voice="man" >We have registered that you selected 0 (connection rejected).</Say>';
	    		break;
	    case 1:	echo '<Say language="en" voice="man" >We have registered that you selected 1 (connection accepted).</Say>';
	    		echo '<Say language="en" voice="man" >Please proceed to the link in your email inbox.</Say>';
	    		// Connect to server and select databse.
				// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

				$result = $con->prepare("SELECT * FROM doctorslinkusers where id=?");
				$result->bindValue(1, $IdRef, PDO::PARAM_INT);
				$result->execute();
				
				$countA=$result->rowCount();
				$rowA = $result->fetch(PDO::FETCH_ASSOC);
				
				//DEBUG  $current .= '************* NEW ENTRY Today = '.date("F j, Y, g:i a").'\n';
				//DEBUG  $current .= 'Result = '.$result.'\n';
				//DEBUG  file_put_contents($file, $current);
				//DEBUG  $current .= 'CountA = '.$countA.'\n';
				//DEBUG  file_put_contents($file, $current);


				$successC ='NO';
				if($countA==1){
					$successC ='YES';
					$IdEntry = $rowA['id'];
					
					$TEmail = $rowA['TemporaryEmail'];
					$TPhone = $rowA['TemporaryPhone'];
					$TempoPass = $rowA['TempoPass'];
					$TIdUs = $rowA['IdUs'];
					
					$hashresult = explode(":", create_hash($TempoPass));
					$IdUsRESERV= $hashresult[3];
					$additional_string=$hashresult[2];
					//DEBUG  $current .= '$IdEntry = '.$IdEntry.'\n';
					//DEBUG  file_put_contents($file, $current);

					//echo "********************* Entry = ".$IdEntry;
					$q = $con->prepare("UPDATE doctorslinkusers SET Confirm = '*******' , Fecha = NOW(), estado = '2' WHERE id =? ");   
					$q->bindValue(1, $IdEntry, PDO::PARAM_INT);
					$q->execute();
					
					
					$q = $con->prepare("UPDATE usuarios SET Password = ?,IdUsRESERV = ?, TempoPass = '', salt=?, email = ?, telefono = ? WHERE Identif =? ");   
					$q->bindValue(1, $IdUsRESERV, PDO::PARAM_STR);
					$q->bindValue(2, $IdUsRESERV, PDO::PARAM_STR);
					$q->bindValue(3, $additional_string, PDO::PARAM_STR);
					$q->bindValue(4, $TEmail, PDO::PARAM_STR);
					$q->bindValue(5, $TPhone, PDO::PARAM_STR);
					$q->bindValue(6, $TIdUs, PDO::PARAM_INT);
					$q->execute();
					
					//DEBUG  $current .= 'Update Query = '.$q.'\n';
					//DEBUG  file_put_contents($file, $current);
					
				}
				else
				{
						echo "Confirmation token not valid.";
						if ($Token!='1111') die;
				}
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