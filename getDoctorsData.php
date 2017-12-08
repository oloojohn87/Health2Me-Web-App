<?php
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

/*$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

function cleanquery($string)
{
  if (get_magic_quotes_gpc())
  {
  $string = stripslashes($string);
  }
  $string = mysql_real_escape_string($string);
  return $string;
}*/

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

    $scope = htmlentities($_POST['scope']);
    $period = htmlentities($_POST['period']);
    $sortOrder = htmlentities($_POST['sortOrder']);
    $sortField = htmlentities($_POST['sortField']);
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
    $search_query = '';
    if(isset($_POST['searchField']) && strlen($_POST['searchField']) > 0)
    {
        $search_query = 'AND (c.doctorName like "%'.(htmlentities($_POST['searchField'])).'%")';
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
        if(strpos($scope, 'HTI') !== false) $HTI_verify_query = "AND d.previlege = 3 ";
    }
        
    $page = ($currentPage - 1) * 20;
    $Result = array();

    if($period == 1)
    {
        // today
        $date = date("Y-m-d")."%";
        
        /*$query = mysql_query("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE DateTime like '".$date."%' ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField);
        $num_pages = ceil(mysql_num_rows($query) / 20);
        
        $query = mysql_query("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE DateTime like '".$date."%' ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");*/
        
        
        
        $query = "SELECT SQL_CALC_FOUND_ROWS d.name,d.surname, count(c.consultationId) AS calls,count(distinct c.Patient) AS numberOfConsultedPatients, count(c.Data_File) AS reportsCreated, c.Doctor AS id FROM consults c INNER JOIN doctors d ON d.id = c.Doctor ".$HTI_verify_query."INNER JOIN usuarios u ON u.Identif = c.Patient WHERE c.DateTime LIKE ?".$scopeQuery." ".$search_query." GROUP BY c.Doctor ORDER BY ".$sortField." LIMIT ?,20";
        
        
        $result = $con->prepare($query);
        $result->bindValue(1, $date, PDO::PARAM_STR);
        //$result->bindValue(2, $sortField, PDO::PARAM_STR);
        $result->bindValue(2, $page, PDO::PARAM_INT);
        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

        $num_pages = ceil($con->query('SELECT FOUND_ROWS()')->fetchColumn() / 20);

        foreach($rows as $row)
        {     
            array_push($Result, $row);
        }
        echo json_encode(array("doctors" => $Result, "pages" => $num_pages));   


    }


    else if($period == 2)
    {
        // today
        $dateOfLastSunday = date('Y-m-d',strtotime('last sunday'))." 00:00:00";
        $todayDate = date("Y-m-d")." 23:59:59";

        $current_date = "";
        $count = 0;
        
        /*$query = mysql_query("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE DateTime between ".$dateOfLastSunday." AND ".$todayDate." ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField);
        $num_pages = ceil(mysql_num_rows($query) / 20);
        
        $query = mysql_query("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE DateTime between ".$dateOfLastSunday." AND ".$todayDate." ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
        
        $result = array();*/
        
        $query = "SELECT SQL_CALC_FOUND_ROWS d.name,d.surname, count(c.consultationId) AS calls,count(distinct c.Patient) AS numberOfConsultedPatients, count(c.Data_File) AS reportsCreated, c.Doctor AS id FROM consults c INNER JOIN doctors d ON d.id = c.Doctor ".$HTI_verify_query."INNER JOIN usuarios u ON u.Identif = c.Patient WHERE c.DateTime BETWEEN ? AND ?".$scopeQuery." ".$search_query." GROUP BY c.Doctor ORDER BY ".$sortField." LIMIT ?,20";
        
        
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
            array_push($Result, $row);
        }
        
        echo json_encode(array("doctors" => $Result, "pages" => $num_pages));   


    }

    else if($period == 3)
    {
        // today
        $firstDateOfMonth = date("Y-m-01");
        $today = date("Y-m-d")." 23:59:59";
        
        /*$query = mysql_query("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE (DateTime between ".$firstDateOfMonth." AND ".$today.") ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField);
        $num_pages = ceil(mysql_num_rows($query) / 20);
        
        $query = mysql_query("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE (DateTime between ".$firstDateOfMonth." AND ".$today.") ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
        $result = array();*/
        
        $query = "SELECT SQL_CALC_FOUND_ROWS d.name,d.surname, count(c.consultationId) AS calls,count(distinct c.Patient) AS numberOfConsultedPatients, count(c.Data_File) AS reportsCreated, c.Doctor AS id FROM consults c INNER JOIN doctors d ON d.id = c.Doctor ".$HTI_verify_query."INNER JOIN usuarios u ON u.Identif = c.Patient WHERE c.DateTime BETWEEN ? AND ?".$scopeQuery." ".$search_query." GROUP BY c.Doctor ORDER BY ".$sortField." LIMIT ?,20";
        
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
            array_push($Result, $row);
        }
        
        echo json_encode(array("doctors" => $Result, "pages" => $num_pages));   
     }

     else if($period == 4)
     {
    // today

        $firstDayOfYear = date("Y-01-01")." 00:00:00";
        $today = date("Y-m-d")." 23:59:59";


        /*$query = mysql_query("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE DateTime between ".$firstDayOfYear." AND ".$today." ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField);
        $num_pages = ceil(mysql_num_rows($query) / 20);
        
        $query = mysql_query("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE DateTime between ".$firstDayOfYear." AND ".$today." ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
        $result = array();*/
         
        $query = "SELECT SQL_CALC_FOUND_ROWS d.name,d.surname, count(c.consultationId) AS calls,count(distinct c.Patient) AS numberOfConsultedPatients, count(c.Data_File) AS reportsCreated, c.Doctor AS id FROM consults c INNER JOIN doctors d ON d.id = c.Doctor ".$HTI_verify_query."INNER JOIN usuarios u ON u.Identif = c.Patient WHERE c.DateTime BETWEEN ? AND ?".$scopeQuery." ".$search_query." GROUP BY c.Doctor ORDER BY ".$sortField." LIMIT ?,20";
        
        $result = $con->prepare($query);
        $result->bindValue(1, $firstDayOfYear, PDO::PARAM_STR);
        $result->bindValue(2, $today, PDO::PARAM_STR);
        //$result->bindValue(3, $sortField, PDO::PARAM_STR);
        $result->bindValue(3, $page, PDO::PARAM_INT);
        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

        $num_pages = ceil($con->query('SELECT FOUND_ROWS()')->fetchColumn() / 20);

        foreach($rows as $row)
        {
            array_push($Result, $row);
        }
        
         echo json_encode(array("doctors" => $Result, "pages" => $num_pages));   
    
    
    }


?>