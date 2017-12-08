<?php

require("environment_detailForLogin.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

/*$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");*/

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

/*mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

function cleanquery($string)
{
  if (get_magic_quotes_gpc())
  {
  $string = stripslashes($string);
  }
  $string = mysql_real_escape_string($string);
  return $string;
}*/

$scope = htmlentities($_POST['scope']);
$period = htmlentities($_POST['period']);
$sortOrder = htmlentities($_POST['sortOrder']);
$sortField = htmlentities($_POST['sortField']);
$search_query = '';
if(isset($_POST['searchField']) && strlen($_POST['searchField']) > 0)
{
    $search_query = 'AND (u.Name like "%'.(htmlentities($_POST['searchField'])).'%" OR u.Surname like "%'.(htmlentities($_POST['searchField'])).'%" OR u.email like "%'.(htmlentities($_POST['searchField'])).'%" OR u.telefono like "%'.(htmlentities($_POST['searchField'])).'%")';
}
$currentPage = htmlentities($_POST['currentPage']);
$sortFields = explode(",", $sortField);
$sortField = "";
for($i = 0; $i < count($sortFields); $i++)
{
    $temp = $sortFields[$i]." ".$sortOrder;
    if($i < count($sortFields) - 1)
    {
        $temp .= ", ";
    }
    $sortField .= $temp;
    
}

$HTI_verify_query = '';
$scopeQuery = "";
if($scope == 'HTI_Global')
{
    $scopeQuery = " AND (c.contract LIKE 'HTI%' OR c.contract = 'Manual%20Launch')";
    $HTI_verify_query = "AND d.previlege = 3 ";
}
else if($scope == 'H2M')
{
    $scopeQuery = " AND (c.contract='".$scope."' OR c.contract IS NULL)";
}   
else if($scope != 'Global')
{
    $scopeQuery = " AND c.contract='".$scope."'";
    if(strpos($scope,'HTI') !== false) $HTI_verify_query = "AND d.previlege = 3 ";
}
$page = ($currentPage - 1) * 20;
$Result = array();

if($period == 1)
{
    // today
    $date = date("Y-m-d").'%';
    /*$query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime like '".$date."%' ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField);
    $num_pages = ceil(mysql_num_rows($query) / 20);
    $query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime like '".$date."%' ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
    $result = array();*/
    $query = "SELECT SQL_CALC_FOUND_ROWS u.Name,u.Surname,u.SignUpDate, count(c.consultationId) AS numberOfPhoneCalls,typeOfPlan,u.Identif FROM usuarios u INNER JOIN consults c ON u.Identif = c.Patient INNER JOIN doctors d ON d.id = c.Doctor ".$HTI_verify_query."WHERE c.DateTime LIKE ? ".$scopeQuery." ".$search_query." GROUP BY u.Identif ORDER BY ".$sortField." LIMIT ?,20";   
    
    
    $result = $con->prepare($query);
    $result->bindValue(1, $date, PDO::PARAM_STR);
    //$result->bindValue(2, $sortField, PDO::PARAM_STR);
    $result->bindValue(2, $page, PDO::PARAM_INT);
    $result->execute();
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    
    $num_pages = ceil($con->query('SELECT FOUND_ROWS()')->fetchColumn() / 20);
    
    foreach($rows as $row)
    {
        $temp_date = new DateTime($row["SignUpDate"]);
        $row["SignUpDate"] = $temp_date->format("F j, Y g:i A");
        if($row["numberOfPhoneCalls"] > 0){
		}else{
			$row["numberOfPhoneCalls"] = 0;
		}
		if($row["typeOfPlan"] == ""){
			$row["typeOfPlan"] = "UNKNOWN";
		}
        array_push($Result, $row);
    }

   //print_r(array("customers" => $result, "pages" => $num_pages));
   echo json_encode(array("customers" => $Result, "pages" => $num_pages));   
    
    
}
else if($period == 2)
{
    // today
    $dateOfLastSunday = date('Y-m-d',strtotime('last sunday'))." 00:00:00";
    $todayDate = date("Y-m-d")." 23:59:59";
    
    $current_date = "";
    $count = 0;
    /*$query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$dateOfLastSunday." AND ".$todayDate." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField);
    $num_pages = ceil(mysql_num_rows($query) / 20);
    $query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$dateOfLastSunday." AND ".$todayDate." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
    $result = array();*/
    
    $query = "SELECT SQL_CALC_FOUND_ROWS u.Name,u.Surname,u.SignUpDate, count(c.consultationId) AS numberOfPhoneCalls,typeOfPlan,u.Identif FROM usuarios u INNER JOIN consults c ON u.Identif = c.Patient INNER JOIN doctors d ON d.id = c.Doctor ".$HTI_verify_query."WHERE c.DateTime between ? AND ? ".$scopeQuery." ".$search_query." GROUP BY u.Identif ORDER BY ".$sortField." LIMIT ?,20";   
    
    
    $result = $con->prepare($query);
    $result->bindValue(1, $dateOfLastSunday, PDO::PARAM_STR);
    $result->bindValue(2, $todayDate, PDO::PARAM_STR);
    //$result->bindValue(3, $sortField, PDO::PARAM_STR);
    $result->bindValue(3, $page, PDO::PARAM_INT);
    $result->execute();
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    
    $num_pages = ceil($con->query('SELECT FOUND_ROWS()')->fetchColumn() / 20);
    
    foreach($rows as $row)
    {
        $temp_date = new DateTime($row['SignUpDate']);
        $row['SignUpDate'] = $temp_date->format("F j, Y g:i A");
        array_push($Result, $row);
    }
    echo json_encode(array("customers" => $Result, "pages" => $num_pages));   
    
    
}
else if($period == 3)
{
    // today
    $firstDateOfMonth = date("Y-m-01")." 00:00:00";
    $today = date("Y-m-d")." 23:59:59";
    /*$query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDateOfMonth." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField);
    $num_pages = ceil(mysql_num_rows($query) / 20);
    $query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDateOfMonth." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
    $result = array();*/
    
    $query = "SELECT SQL_CALC_FOUND_ROWS u.Name,u.Surname,u.SignUpDate, count(c.consultationId) AS numberOfPhoneCalls,typeOfPlan,u.Identif FROM usuarios u INNER JOIN consults c ON u.Identif = c.Patient INNER JOIN doctors d ON d.id = c.Doctor ".$HTI_verify_query."WHERE c.DateTime between ? AND ? ".$scopeQuery." ".$search_query." GROUP BY u.Identif ORDER BY ".$sortField." LIMIT ?,20";   
    
    
    $result = $con->prepare($query);
    $result->bindValue(1, $firstDateOfMonth, PDO::PARAM_STR);
    $result->bindValue(2, $today, PDO::PARAM_STR);
    //$result->bindValue(3, $sortField, PDO::PARAM_STR);
    $result->bindValue(3, $page, PDO::PARAM_INT);
    $result->execute();
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    
    $num_pages = ceil($con->query('SELECT FOUND_ROWS()')->fetchColumn() / 20);
    
    foreach($rows as $row)
    {
        $temp_date = new DateTime($row['SignUpDate']);
        $row['SignUpDate'] = $temp_date->format("F j, Y g:i A");
        array_push($Result, $row);
    }
    echo json_encode(array("customers" => $Result, "pages" => $num_pages));   
    
    
}
else if($period == 4)
{
    // today

    $firstDayOfYear = date("Y-01-01")." 00:00:00";
    $today = date("Y-m-d")." 23:59:59";

    /*$query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDayOfYear." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField);
    $num_pages = ceil(mysql_num_rows($query) / 20);
    $query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDayOfYear." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
    $result = array();*/
    
    $query = "SELECT SQL_CALC_FOUND_ROWS u.Name,u.Surname,u.SignUpDate, count(c.consultationId) AS numberOfPhoneCalls,typeOfPlan,u.Identif FROM usuarios u INNER JOIN consults c ON u.Identif = c.Patient INNER JOIN doctors d ON d.id = c.Doctor ".$HTI_verify_query."WHERE c.DateTime between ? AND ? ".$scopeQuery." ".$search_query." GROUP BY u.Identif ORDER BY ".$sortField." LIMIT ?,20";   
    
    $result = $con->prepare($query);
    $result->bindValue(1, $firstDayOfYear, PDO::PARAM_STR);
    $result->bindValue(2, $today, PDO::PARAM_STR);
    //$result->bindValue(3, $sortField, PDO::PARAM_STR);
    $result->bindValue(3, $page, PDO::PARAM_STR);
    $result->execute();
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    
    $num_pages = ceil($con->query('SELECT FOUND_ROWS()')->fetchColumn() / 20);
    
    foreach($rows as &$row)
    {
        $temp_date = new DateTime($row['SignUpDate']);
        $row['SignUpDate'] = $temp_date->format("F j, Y g:i A");
        //echo $row['SignUpDate'];
        array_push($Result, $row);
        //var_dump($Result);
    }

    /*echo $query;
    echo $firstDayOfYear.'<br>';
    echo $today.'<br>';
    echo $sortField.'<br>';
    echo $page.'<br>';
    echo $scopeQuery.'<br>';
    echo $search_query;*/
    
    
    echo json_encode(array("customers" => $Result, "pages" => $num_pages));   
    
    
}
else if($period == 5)
{
    // today

    $firstDayOfYear = date("1900-01-01")." 00:00:00";
    $today = date("Y-m-d")." 23:59:59";

    /*$query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDayOfYear." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField);
    $num_pages = ceil(mysql_num_rows($query) / 20);
    $query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDayOfYear." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
    $result = array();*/
    
    $query = "SELECT SQL_CALC_FOUND_ROWS u.Name,u.Surname,u.SignUpDate, count(c.consultationId) AS numberOfPhoneCalls,typeOfPlan,u.Identif FROM usuarios u INNER JOIN consults c ON u.Identif = c.Patient INNER JOIN doctors d ON d.id = c.Doctor ".$HTI_verify_query."WHERE c.DateTime between ? AND ? ".$scopeQuery." ".$search_query." GROUP BY u.Identif ORDER BY ".$sortField." LIMIT ?,20";   
    
    $result = $con->prepare($query);
    $result->bindValue(1, $firstDayOfYear, PDO::PARAM_STR);
    $result->bindValue(2, $today, PDO::PARAM_STR);
    //$result->bindValue(3, $sortField, PDO::PARAM_STR);
    $result->bindValue(3, $page, PDO::PARAM_STR);
    $result->execute();
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    
    $num_pages = ceil($con->query('SELECT FOUND_ROWS()')->fetchColumn() / 20);
    
    foreach($rows as &$row)
    {
        $temp_date = new DateTime($row['SignUpDate']);
        $row['SignUpDate'] = $temp_date->format("F j, Y g:i A");
        //echo $row['SignUpDate'];
        array_push($Result, $row);
        //var_dump($Result);
    }
    /*echo $firstDayOfYear.'<br>';
    echo $today.'<br>';
    echo $sortField.'<br>';
    echo $page.'<br>';
    echo $scopeQuery.'<br>';
    echo $search_query;*/
    
    
    echo json_encode(array("customers" => $Result, "pages" => $num_pages));   
    
    
}
?>
