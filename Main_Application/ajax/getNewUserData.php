<?php

require("environment_detailForLogin.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass") or die("cannot connect");
mysql_select_db("$dbname") or die("cannot select DB");

mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

    $scope = $_POST['scope'];
    $period = $_POST['period'];
    $sortOrder = $_POST['sortOrder'];
    $sortField = $_POST['sortField'];
    $currentPage = $_POST['currentPage'];
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
    $search_query = '';
    if(isset($_POST['searchField']) && strlen($_POST['searchField']) > 0)
    {
        $search_query = 'AND (Name like "%'.$_POST['searchField'].'%")';
    }

    $scopeQuery = "";
    if($scope != 'Global')
    {
        $scopeQuery = "AND contract='".$scope."'";
    }

    if($period == 1)
    {
        // today
        $date = date("Y-m-d");
        
        $query = mysql_query("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && signUpDate like '".$date."%' ".$search_query." ORDER BY ".$sortField);
        $num_pages = ceil(mysql_num_rows($query) / 20);
        
        $query = mysql_query("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && signUpDate like '".$date."%' ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
        
        $result = array();
        while($row = mysql_fetch_assoc($query))
        {
            
            array_push($result, $row);
        }
        echo json_encode(array("newusers" => $result, "pages" => $num_pages));   


    }


    else if($period == 2)
    {
        // today
        $dateOfLastSunday = "'".date('Y-m-d',strtotime('last sunday'))." 00:00:00'";
        $todayDate = "'".date("Y-m-d")." 23:59:59'";

        $current_date = "";
        $count = 0;
        
        $query = mysql_query("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && signUpDate between ".$dateOfLastSunday." AND ".$todayDate." ".$search_query." ORDER BY ".$sortField);
        $num_pages = ceil(mysql_num_rows($query) / 20);
        
        $query = mysql_query("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && signUpDate between ".$dateOfLastSunday." AND ".$todayDate." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
        
        $result = array();
        while($row = mysql_fetch_assoc($query))
        {
            
            array_push($result, $row);
        }
        
        echo json_encode(array("newusers" => $result, "pages" => $num_pages));   


    }

    else if($period == 3)
    {
        // today
        $firstDateOfMonth = "'".date("Y-m-01")."'";
        $today = "'".date("Y-m-d")." 23:59:59'";
        
        $query = mysql_query("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && (signUpDate between ".$firstDateOfMonth." AND ".$today.") ".$search_query." ORDER BY ".$sortField);
        $num_pages = ceil(mysql_num_rows($query) / 20);
        
        $query = mysql_query("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && (signUpDate between ".$firstDateOfMonth." AND ".$today.") ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
        $result = array();
        
        while($row = mysql_fetch_assoc($query))
        {
            
            array_push($result, $row);
        }
        
        echo json_encode(array("newusers" => $result, "pages" => $num_pages));   
     }

     else if($period == 4)
     {
    // today

        $firstDayOfYear = "'".date("Y-01-01")." 00:00:00'";
        $today = "'".date("Y-m-d")." 23:59:59'";

        $query = mysql_query("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && signUpDate between ".$firstDayOfYear." AND ".$today." ".$search_query." ORDER BY ".$sortField);
        $num_pages = ceil(mysql_num_rows($query) / 20);
        
        $query = mysql_query("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && signUpDate between ".$firstDayOfYear." AND ".$today." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
        $result = array();
        
        while($row = mysql_fetch_assoc($query))
        {
            
            array_push($result, $row);
        }
        
         echo json_encode(array("newusers" => $result, "pages" => $num_pages));   
    
    
    }


?>
