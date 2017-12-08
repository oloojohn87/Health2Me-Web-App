<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

	

$Idpin = $_GET['Idpin'];
$state = $_GET['state'];
$type= $_GET['type'];

if($state==0){
		$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
  
		$sql_que=$con->prepare("select Id from tipopin where Agrup=9");
			$res=$sql_que->execute();
			
			$privatetypes=array();
			$num1=0;
			while($rowpr = $sql_que->fetch(PDO::FETCH_ASSOC)){
				$privatetypes[$num1]=$rowpr['Id'];
				$num1++;
		}

		//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks WHERE id='$queBlock' ");
		$result = $con->prepare("SELECT Tipo,isPrivate FROM lifepin WHERE IdPin=? ");
		$result->bindValue(1, $Idpin, PDO::PARAM_INT);
		$result->execute();

		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

		$type = $row['Tipo'];
		$isPrivate=$row['isPrivate'];
		if(in_array($type,$privatetypes)){
		echo 'superprivate';			
		}else{
		  if($isPrivate==1){
		   echo 'private';
		  }else{
		  echo 'normal';
		  }
		}
		}
}else {
  if($state== 1){
		$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
		$sql_que=$con->prepare("update lifepin set isPrivate=? where idpin=?");
		$sql_que->bindValue(1, $type, PDO::PARAM_INT);
		$sql_que->bindValue(2, $Idpin, PDO::PARAM_INT);
		$res=$sql_que->execute();
		
		echo 'Success';
	}
}



?>