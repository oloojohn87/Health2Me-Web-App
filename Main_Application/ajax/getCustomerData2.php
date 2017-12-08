<?php

require("environment_detailForLogin.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

function cleanquery($string)
{
  if (get_magic_quotes_gpc())
  {
  $string = stripslashes($string);
  }
  $string = mysql_real_escape_string($string);
  return $string;
}

$scope = cleanquery($_POST['scope']);
$period = cleanquery($_POST['period']);
$sortOrder = cleanquery($_POST['sortOrder']);
$sortField = cleanquery($_POST['sortField']);
$search_query = '';
if(isset($_POST['searchField']) && strlen($_POST['searchField']) > 0)
{
    $search_query = 'AND (Name like "%'.(cleanquery($_POST['searchField'])).'%" OR Surname like "%'.(cleanquery($_POST['searchField'])).'%" OR email like "%'.(cleanquery($_POST['searchField'])).'%" OR telefono like "%'.(cleanquery($_POST['searchField'])).'%")';
}
$currentPage = cleanquery($_POST['currentPage']);
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

$scopeQuery = "";
if($scope == 'NULL')
{
    $scopeQuery = "AND contract IS NULL";
}
else if($scope != 'Global')
{
    $scopeQuery = "AND contract='".$scope."'";
}

if($period == 1)
{
    // today
    $date = date("Y-m-d");
    $query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime like '".$date."%' ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField);
    $num_pages = ceil(mysql_num_rows($query) / 20);
    $query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime like '".$date."%' ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
    $result = array();
    while($row = mysql_fetch_assoc($query))
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
        array_push($result, $row);
    }

    
   //print_r(array("customers" => $result, "pages" => $num_pages));
   echo json_encode(array("customers" => $result, "pages" => $num_pages));   
    
    
}
else if($period == 2)
{
    // today
    $dateOfLastSunday = "'".date('Y-m-d',strtotime('last sunday'))." 00:00:00'";
    $todayDate = "'".date("Y-m-d")." 23:59:59'";
    
    $current_date = "";
    $count = 0;
    $query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$dateOfLastSunday." AND ".$todayDate." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField);
    $num_pages = ceil(mysql_num_rows($query) / 20);
    $query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$dateOfLastSunday." AND ".$todayDate." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
    $result = array();
    while($row = mysql_fetch_assoc($query))
    {
        $temp_date = new DateTime($row['SignUpDate']);
        $row['SignUpDate'] = $temp_date->format("F j, Y g:i A");
        array_push($result, $row);
    }
    echo json_encode(array("customers" => $result, "pages" => $num_pages));   
    
    
}
else if($period == 3)
{
    // today
    $firstDateOfMonth = "'".date("Y-m-01")." 00:00:00'";
    $today = "'".date("Y-m-d")." 23:59:59'";
    $query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDateOfMonth." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField);
    $num_pages = ceil(mysql_num_rows($query) / 20);
    $query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDateOfMonth." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
    $result = array();
    while($row = mysql_fetch_assoc($query))
    {
        $temp_date = new DateTime($row['SignUpDate']);
        $row['SignUpDate'] = $temp_date->format("F j, Y g:i A");
        array_push($result, $row);
    }
    echo json_encode(array("customers" => $result, "pages" => $num_pages));   
    
    
}
else if($period == 4)
{
    // today

    $firstDayOfYear = "'".date("Y-01-01")." 00:00:00'";
    $today = "'".date("Y-m-d")." 23:59:59'";


    $query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDayOfYear." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField);
    $num_pages = ceil(mysql_num_rows($query) / 20);
    $query = mysql_query("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDayOfYear." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
    $result = array();
    while($row = mysql_fetch_assoc($query))
    {
        $temp_date = new DateTime($row['SignUpDate']);
        $row['SignUpDate'] = $temp_date->format("F j, Y g:i A");
        array_push($result, $row);
    }
    echo json_encode(array("customers" => $result, "pages" => $num_pages));   
    
    
}
?>
