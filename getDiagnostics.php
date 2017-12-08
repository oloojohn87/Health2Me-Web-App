<?php
 session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];



$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$queUsu = $_GET['IdUsu'];



$query = $con->prepare("SELECT id,dxname,dxcode,DATE_FORMAT(dxstart,'%b %Y') as dxstart,DATE_FORMAT(dxend,'%b %Y') as dxend,notes,deleted FROM p_diagnostics WHERE idpatient=?");
$query->bindValue(1, $queUsu, PDO::PARAM_INT);
$result = $query->execute();

$count = $query->rowCount();
$counter1 = 0 ;
$cadena = '';
while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {


if($_COOKIE["lang"] == 'th'){
if(substr($row2['dxstart'], 0, 3) == 'Jan'){
$new_month = 'Ene '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Feb'){
$new_month = 'Feb '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Mar'){
$new_month = 'Mar '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Apr'){
$new_month = 'Abr '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'May'){
$new_month = 'May '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Jun'){
$new_month = 'Jun '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Jul'){
$new_month = 'Jul '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Aug'){
$new_month = 'Ago '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Sep'){
$new_month = 'Sep '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Oct'){
$new_month = 'Oct '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Nov'){
$new_month = 'Nov '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Dec'){
$new_month = 'Dic '.substr($row2['dxstart'], 4, 4);
}
}else{
if(substr($row2['dxstart'], 0, 3) == 'Jan'){
$new_month = 'Jan '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Feb'){
$new_month = 'Feb '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Mar'){
$new_month = 'Mar '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Apr'){
$new_month = 'Apr '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'May'){
$new_month = 'May '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Jun'){
$new_month = 'Jun '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Jul'){
$new_month = 'Jul '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Aug'){
$new_month = 'Aug '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Sep'){
$new_month = 'Sep '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Oct'){
$new_month = 'Oct '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Nov'){
$new_month = 'Nov '.substr($row2['dxstart'], 4, 4);
}
if(substr($row2['dxstart'], 0, 3) == 'Dec'){
$new_month = 'Dec '.substr($row2['dxstart'], 4, 4);
}
}

$new_month2 = '';
if($_COOKIE["lang"] == 'th'){
if(substr($row2['dxend'], 0, 3) == 'Jan'){
$new_month2 = 'Ene '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Feb'){
$new_month2 = 'Feb '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Mar'){
$new_month2 = 'Mar '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Apr'){
$new_month2 = 'Abr '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'May'){
$new_month2 = 'May '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Jun'){
$new_month2 = 'Jun '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Jul'){
$new_month2 = 'Jul '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Aug'){
$new_month2 = 'Ago '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Sep'){
$new_month2 = 'Sep '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Oct'){
$new_month2 = 'Oct '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Nov'){
$new_month2 = 'Nov '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Dec'){
$new_month2 = 'Dic '.substr($row2['dxend'], 4, 4);
}
}else{
if(substr($row2['dxend'], 0, 3) == 'Jan'){
$new_month2 = 'Jan '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Feb'){
$new_month2 = 'Feb '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Mar'){
$new_month2 = 'Mar '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Apr'){
$new_month2 = 'Apr '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'May'){
$new_month2 = 'May '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Jun'){
$new_month2 = 'Jun '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Jul'){
$new_month2 = 'Jul '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Aug'){
$new_month2 = 'Aug '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Sep'){
$new_month2 = 'Sep '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Oct'){
$new_month2 = 'Oct '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Nov'){
$new_month2 = 'Nov '.substr($row2['dxend'], 4, 4);
}
if(substr($row2['dxend'], 0, 3) == 'Dec'){
$new_month2 = 'Dec '.substr($row2['dxend'], 4, 4);
}
}



	$Id[$counter1] = (htmlspecialchars($row2['id']));
	$dxName[$counter1] = (htmlspecialchars($row2['dxname']));
	$dxCode[$counter1] = (htmlspecialchars($row2['dxcode']));
	$sdate[$counter1] = $new_month;
	$edate[$counter1] = $new_month2;
	$notes[$counter1] = (htmlspecialchars($row2['notes']));
	$deleted[$counter1] = (htmlspecialchars($row2['deleted']));

	if ($counter1>0) $cadena.=',';    
	$cadena.='
    {
        "id":"'.$Id[$counter1].'",
        "dxname":"'.$dxName[$counter1].'",
        "dxcode":"'.$dxCode[$counter1].'",
        "sdate":"'.$sdate[$counter1].'",
        "edate":"'.$edate[$counter1].'",
		"notes":"'.$notes[$counter1].'",
        "deleted":"'.$deleted[$counter1].'"
        }';    

	$counter1++;
}

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 


?>
