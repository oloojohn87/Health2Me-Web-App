<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$MedID = $_GET['uniqueID'];
$EmailID=$_GET['EmailID'];
$result = $con->prepare("SELECT * FROM dropboxdetails WHERE ID=? ");
$result->bindValue(1, $MedID, PDO::PARAM_INT);
$result->execute();

$count = $result->rowCount();

if($count==1) {
 echo "You have already activated dropbox cloud Channel";
}
else if($count==0) 
 header("Location: $domain/CloudChannelAuth.php?uniqueID=$MedID&EmailID=$EmailID");
else 
 echo "There is error while activating dropbox. Please contact admin support!";
?>

<!DOCTYPE html>
<html lang="en">
<header>

<script type="text/javascript">
		setTimeout("window.close();", 10000);
</script>
<script type="text/javascript">
function closeWin()
	{
		window.close();
	}
</script>
</header>

<body>
	<input type="button" value="Close Window'" align="bottom" onclick="closeWin()" />
</body>
</html>
