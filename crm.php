<?php
require_once("environment_detailForLogin.php");

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
  
  if(isset($_GET['status']) && isset($_GET['status_id'])){
	$query2 = $con->prepare("UPDATE crm SET status = ? WHERE id = ?");
	$query2->bindValue(1, $_GET['status'], PDO::PARAM_STR);
	$query2->bindValue(2, $_GET['status_id'], PDO::PARAM_INT);
	$result2=$query2->execute();
  }
  
  echo "<link href='css/bootstrap.css' rel='stylesheet'>  ";
  
  echo "Filters: <a href='crm.php?filter=cb'><button class='btn btn-info'>Call Back</button></a><a href='crm.php?filter=lm'><button class='btn btn-success'>Left Message</button></a><a href='crm.php?filter=ni'><button class='btn btn-danger'>No Interest</button></a><a href='crm.php'><button class='btn btn-default'>All</button></a>";
  
  if(isset($_GET['filter'])){
	  $query = $con->prepare("select * from crm where phone_type LIKE ? && status = ?");
		$query->bindValue(1, '%Cell Phone%', PDO::PARAM_STR);
		$query->bindValue(2, $_GET['filter'], PDO::PARAM_STR);
		$result=$query->execute();
  }else{
	$query = $con->prepare("select * from crm where phone_type LIKE ?");
	$query->bindValue(1, '%Cell Phone%', PDO::PARAM_STR);
	$result=$query->execute();
  }

echo "<table class='table table-striped'>";
echo "<tr><th>Name</th><th>Location</th><th>Phone</th><th>Specialty</th><th>State</th><th>Actions</th></tr>";
while($row = $query->fetch(PDO::FETCH_ASSOC)){
	
	if($row['status'] == ''){
		$lm_button = 'btn btn-default';
		$ni_button = 'btn btn-default';
		$cb_button = 'btn btn-default';
	}elseif($row['status'] == 'lm'){
		$lm_button = 'btn btn-success';
		$ni_button = 'btn btn-default';
		$cb_button = 'btn btn-default';
	}elseif($row['status'] == 'ni'){
		$lm_button = 'btn btn-default';
		$ni_button = 'btn btn-danger';
		$cb_button = 'btn btn-default';
	}elseif($row['status'] == 'cb'){
		$lm_button = 'btn btn-default';
		$ni_button = 'btn btn-default';
		$cb_button = 'btn btn-info';
	}
	
	echo "<tr><td>".$row['name']."</td><td>".$row['address']."</td><td>".$row['phone']."</td><td>".$row['specialty']."</td><td>".$row['state']."</td><td><a href='crm.php?status_id=".$row['id']."&status=lm'><button class='".$lm_button."'>LM</button></a><a href='crm.php?status_id=".$row['id']."&status=ni'><button class='".$ni_button."'>NI</button></a><a href='crm.php?status_id=".$row['id']."&status=cb'><button class='".$cb_button."'>CB</button></a></td></tr>";
}

echo "</table>";
?>
