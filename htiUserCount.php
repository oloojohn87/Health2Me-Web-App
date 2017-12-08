<?php
require("environment_detailForLogin.php");
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
  
  if($_GET['code'] != 'asegw4w4g34ghearhgaeh'){
	  die('Your acccess code is invalid.');
  }
  
  echo "<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=members'><button class='btn btn-default'>Members</button></a>
  <a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=doctors'><button class='btn btn-default'>Doctors</button></a>
  <a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=agents'><button class='btn btn-default'>Agents</button></a>

	";
  
  echo "<link href='css/bootstrap.css' rel='stylesheet'>";
  
  if(isset($_GET['usertype'])){
	  if($_GET['usertype'] == 'members'){

		echo "<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=members&filter=HTI-RIVA'><button style='margin-left:100px;' class='btn btn-default'>HTI-RIVA</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=members&filter=HTI-COL'><button class='btn btn-default'>HTI-COL</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=members&filter=HTI-24X7'><button class='btn btn-default'>HTI-24X7</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=members&filter=HTI-SPA'><button class='btn btn-default'>HTI-SPA</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=members&filter=HTI-CR'><button class='btn btn-default'>HTI-CR</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=members&filter=HTI-USA-ENG'><button class='btn btn-default'>HTI-USA-ENG</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=members&filter=HTI-USA-SPAN'><button class='btn btn-default'>HTI-USA-SPAN</button></a>";

		  if(!isset($_GET['filter'])){
			  $result = $con->prepare("SELECT * FROM usuarios WHERE (GrantAccess=? OR GrantAccess=? OR GrantAccess=? OR GrantAccess=? OR GrantAccess=? OR GrantAccess=? OR GrantAccess=?)");
			  $result->bindValue(1, 'HTI-RIVA', PDO::PARAM_STR);
			  $result->bindValue(2, 'HTI-COL', PDO::PARAM_STR);
			  $result->bindValue(3, 'HTI-24X7', PDO::PARAM_STR);
			  $result->bindValue(4, 'HTI-SPA', PDO::PARAM_STR);
			  $result->bindValue(5, 'HTI-CR', PDO::PARAM_STR);
	  		  $result->bindValue(6, 'HTI-USA-ENG', PDO::PARAM_STR);
	 		  $result->bindValue(7, 'HTI-USA-SPAN', PDO::PARAM_STR);
			  $result->execute();
			  $count = $result->rowCount();
		  }else{
			$result = $con->prepare("SELECT * FROM usuarios WHERE GrantAccess=?");
			  $result->bindValue(1, $_GET['filter'], PDO::PARAM_STR);
			  $result->execute();
			  $count = $result->rowCount();
		  }
		  echo "<div style='float:right;'>Total Members: ".$count."</div>";
		  echo "<table class='table table-striped'>
		  <tr><th>Member</th><th>Email</th><th>Access</th><th>Created On</th><th>Agent</th></tr>";
		  while($row = $result->fetch(PDO::FETCH_ASSOC)){
			  
			  $result2 = $con->prepare("SELECT * FROM agentslinkusers WHERE user=?");
			  $result2->bindValue(1, $row['Identif'], PDO::PARAM_INT);
			  $complete = $result2->execute();
			  $complete_count = $result2->rowCount();
			  
			  if($complete_count > 0){
				  $row2 = $result2->fetch(PDO::FETCH_ASSOC);
				  $result3 = $con->prepare("SELECT * FROM agents WHERE idagents=?");
				  $result3->bindValue(1, $row2['agent'], PDO::PARAM_INT);
				  $complete = $result3->execute();
				  $row3 = $result3->fetch(PDO::FETCH_ASSOC);
				  $agent_name = $row3['username'];
				  
			  }else{
				  $agent_name = 'N/A';
			  }
			  
			  echo "<tr><td>".$row['Name']." ".$row['Surname']."</td><td>".$row['email']."</td><td>".$row['GrantAccess']."</td><td>".$row['SignUpDate']."</td><td>".$agent_name."</td></tr>";
		  }
		  echo "</table>";
	  }
	  
	  if($_GET['usertype'] == 'doctors'){

		echo "<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=doctors&filter=HTICOL01'><button style='margin-left:100px;' class='btn btn-default'>HTICOL01</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=doctors&filter=HTIUSA01'><button class='btn btn-default'>HTIUSA01</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=doctors&filter=HTIMEX01'><button class='btn btn-default'>HTIMEX01</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=doctors&filter=HTICR01'><button class='btn btn-default'>HTICR01</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=doctors&filter=HTIECU01'><button class='btn btn-default'>HTIECU01</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=doctors&filter=HTIPR01'><button class='btn btn-default'>HTIPR01</button></a>";

		if(!isset($_GET['filter'])){
		  $result = $con->prepare("SELECT * FROM doctors WHERE previlege=? && (registered_code = ? OR registered_code = ? OR registered_code = ? OR registered_code = ? OR registered_code = ? OR registered_code = ?)");
		  $result->bindValue(1, 3, PDO::PARAM_INT);
		  $result->bindValue(2, 'HTICOL01', PDO::PARAM_STR);
		  $result->bindValue(3, 'HTIUSA01', PDO::PARAM_STR);
		  $result->bindValue(4, 'HTIMEX01', PDO::PARAM_STR);
		  $result->bindValue(5, 'HTICR01', PDO::PARAM_STR);
		  $result->bindValue(6, 'HTIECU01', PDO::PARAM_STR);
		  $result->bindValue(7, 'HTIPR01', PDO::PARAM_STR);
		  $result->execute();
		  $count = $result->rowCount();
		}else{
			$result = $con->prepare("SELECT * FROM doctors WHERE previlege=? && registered_code = ?");
		  $result->bindValue(1, 3, PDO::PARAM_INT);
		  $result->bindValue(2, $_GET['filter'], PDO::PARAM_STR);
		  $result->execute();
		  $count = $result->rowCount();
		}
		  echo "<div style='float:right;'>Total Doctors: ".$count."</div>";
		  echo "<table class='table table-striped'>
		  <tr><th>Doctor</th><th>Email</th><th>Registered Code</th></tr>";
		  while($row = $result->fetch(PDO::FETCH_ASSOC)){
			  echo "<tr><td>".$row['Name']." ".$row['Surname']."</td><td>".$row['IdMEDEmail']."</td><td>".$row['registered_code']."</td></tr>";
		  }
		  echo "</table>";
	  }
	  
	  if($_GET['usertype'] == 'agents'){

		echo "<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=agents&filter=1'><button style='margin-left:100px;' class='btn btn-default'>COLOMBIA</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=agents&filter=2'><button class='btn btn-default'>SPAIN</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=agents&filter=3'><button class='btn btn-default'>RIVACARE</button></a>
	<a href='htiUserCount.php?code=asegw4w4g34ghearhgaeh&usertype=agents&filter=0'><button class='btn btn-default'>NOT ASSIGNED</button></a>";

		if(!isset($_GET['filter'])){
		  $result = $con->prepare("SELECT * FROM agents");
		  $result->execute();
		  $count = $result->rowCount();
		}else{
		$result = $con->prepare("SELECT * FROM agents WHERE facility = ?");
		$result->bindValue(1, $_GET['filter'], PDO::PARAM_INT);
		  $result->execute();
		  $count = $result->rowCount();
		}
		  echo "<div style='float:right;'>Total Agents: ".$count."</div>";
		  echo "<table class='table table-striped'>
		  <tr><th>Agent</th><th>Email</th><th>Registered Count</th><th>Created On</th></tr>";
		  while($row = $result->fetch(PDO::FETCH_ASSOC)){
			  echo "<tr><td>".$row['username']."</td><td>".$row['email']."</td><td>".$row['t_registered']."</td><td>".$row['created_date']."</td></tr>";
		  }
		  echo "</table>";
	  }
  }
?>
