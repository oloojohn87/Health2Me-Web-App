<?php

require("environment_detailForLogin.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

/*$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
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
    $search_query = 'AND (c.doctorName like "%'.$_POST['searchField'].'%" OR c.doctorSurname like "%'.$_POST['searchField'].'%" OR c.patientName like "%'.$_POST['searchField'].'%" OR c.patientSurname like "%'.$_POST['searchField'].'%")';
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
$consultationData = array();

if($period == 1) // Start of getting today consultation data
{
          $date = date("Y-m-d")."%";

          /*$query = mysql_query("select * from consults where contract != 'H2M' && contract is not null && DateTime like '".$date."%'".$scopeQuery." ".$search_query." ORDER BY ".$sortField);
          $num_pages = ceil(mysql_num_rows($query)/20);

          $query = mysql_query("select * from consults where contract != 'H2M' && contract is not null && DateTime like '".$date."%'".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");*/
    
            $query = "SELECT SQL_CALC_FOUND_ROWS c.consultationId, c.contract, c.DateTime, c.Patient, c.Doctor, c.Type, c.Length, c.Status, c.Data_File, c.Summary_PDF, c.Cost, c.lastActive, c.Doctor_Status, c.Patient_Status, c.description, d.Name AS doctorName, d.Surname AS doctorSurname, u.Name AS patientName, u.Surname AS patientSurname FROM consults c INNER JOIN doctors d ON d.id = c.Doctor ".$HTI_verify_query."INNER JOIN usuarios u ON u.Identif = c.Patient WHERE c.DateTime LIKE ? ".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ?,20";

          $result = $con->prepare($query);
        $result->bindValue(1, $date, PDO::PARAM_STR);
        //$result->bindValue(2, $sortField, PDO::PARAM_STR);
        $result->bindValue(2, $page, PDO::PARAM_INT);
        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

        $num_pages = ceil($con->query('SELECT FOUND_ROWS()')->fetchColumn() / 20);
    

        foreach($rows as &$row)
          {

            $temp_date = new DateTime($row['DateTime'] );
            $row['DateTime']  = $temp_date->format("M j, Y g:i A");
            $row['Summary_PDF'] = 'Packages_Encrypted/'.$row['Summary_PDF'];
            $row['Data_File'] = 'Packages_Encrypted/'.$row['Data_File'];
            array_push($consultationData, $row); 
        
              
          }
          
          echo json_encode(array("consultations" => $consultationData, "pages" => $num_pages));
}

if($period == 2)
{
          $dateOfLastSunday = date('Y-m-d',strtotime('last sunday'))." 00:00:00";
          $todayDate = date("Y-m-d")." 23:59:59";
           
          /*$query = mysql_query("select * from consults where contract != 'H2M' && contract is not null && (DateTime between ".$dateOfLastSunday." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField);
          $num_pages = ceil(mysql_num_rows($query)/20);
    
          $query = mysql_query("select * from consults where contract != 'H2M' && contract is not null && (DateTime between ".$dateOfLastSunday." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");*/
         
        $query = "SELECT SQL_CALC_FOUND_ROWS c.consultationId, c.contract, c.DateTime, c.Patient, c.Doctor, c.Type, c.Length, c.Status, c.Data_File, c.Summary_PDF, c.Cost, c.lastActive, c.Doctor_Status, c.Patient_Status, c.description, d.Name AS doctorName, d.Surname AS doctorSurname, u.Name AS patientName, u.Surname AS patientSurname FROM consults c INNER JOIN doctors d ON d.id = c.Doctor ".$HTI_verify_query."INNER JOIN usuarios u ON u.Identif = c.Patient WHERE c.DateTime BETWEEN ? AND ? ".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ?,20";
    
          $result = $con->prepare($query);
        $result->bindValue(1, $dateOfLastSunday, PDO::PARAM_STR);
        $result->bindValue(2, $todayDate, PDO::PARAM_STR);
        //$result->bindValue(3, $sortField, PDO::PARAM_STR);
        $result->bindValue(3, $page, PDO::PARAM_INT);
        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

        $num_pages = ceil($con->query('SELECT FOUND_ROWS()')->fetchColumn() / 20);

        foreach($rows as &$row)
          {

            $temp_date = new DateTime($row['DateTime'] );
            $row['DateTime']  = $temp_date->format("M j, Y g:i A");
            $row['Summary_PDF'] = 'Packages_Encrypted/'.$row['Summary_PDF'];
            $row['Data_File'] = 'Packages_Encrypted/'.$row['Data_File'];
            array_push($consultationData, $row); 
          }
          
          echo json_encode(array("consultations" => $consultationData, "pages" => $num_pages));
          
}

if($period == 3)
{
         $firstDateOfMonth = date("Y-m-01")." 00:00:00";
         $today = date("Y-m-d")." 23:59:59";
    
         /*$query = mysql_query("select * from consults where contract != 'H2M' && contract is not null && (DateTime between ".$firstDateOfMonth." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField);
         $num_pages = ceil(mysql_num_rows($query)/20);
    
         $query = mysql_query("select * from consults where contract != 'H2M' && contract is not null && (DateTime between ".$firstDateOfMonth." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");*/
    
        $query = "SELECT SQL_CALC_FOUND_ROWS c.consultationId, c.contract, c.DateTime, c.Patient, c.Doctor, c.Type, c.Length, c.Status, c.Data_File, c.Summary_PDF, c.Cost, c.lastActive, c.Doctor_Status, c.Patient_Status, c.description, d.Name AS doctorName, d.Surname AS doctorSurname, u.Name AS patientName, u.Surname AS patientSurname FROM consults c INNER JOIN doctors d ON d.id = c.Doctor ".$HTI_verify_query."INNER JOIN usuarios u ON u.Identif = c.Patient WHERE c.DateTime BETWEEN ? AND ? ".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ?,20";
            
         $result = $con->prepare($query);
        $result->bindValue(1, $firstDateOfMonth, PDO::PARAM_STR);
        $result->bindValue(2, $today, PDO::PARAM_STR);
        //$result->bindValue(3, $sortField, PDO::PARAM_STR);
        $result->bindValue(3, $page, PDO::PARAM_INT);
        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

        $num_pages = ceil($con->query('SELECT FOUND_ROWS()')->fetchColumn() / 20);

        foreach($rows as &$row)
          {

            $temp_date = new DateTime($row['DateTime'] );
            $row['DateTime']  = $temp_date->format("M j, Y g:i A");
            $row['Summary_PDF'] = 'Packages_Encrypted/'.$row['Summary_PDF'];
            $row['Data_File'] = 'Packages_Encrypted/'.$row['Data_File'];
            array_push($consultationData, $row);  
          }
          
          
          echo json_encode(array("consultations" => $consultationData, "pages" => $num_pages));
    
    
}

if($period == 4)
{
 
          $firstDayOfYear = date("Y-01-01")." 00:00:00";
          $today = date("Y-m-d")." 23:59:59";   

          /*$query = mysql_query("select * from consults where contract != 'H2M' && contract is not null && (DateTime between ".$firstDayOfYear." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField);
          $num_pages = ceil(mysql_num_rows($query)/20);
 
          $query = mysql_query("select * from consults where contract != 'H2M' && contract is not null && (DateTime between ".$firstDayOfYear." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");*/
    
        $query = "SELECT SQL_CALC_FOUND_ROWS c.consultationId, c.contract, c.DateTime, c.Patient, c.Doctor, c.Type, c.Length, c.Status, c.Data_File, c.Summary_PDF, c.Cost, c.lastActive, c.Doctor_Status, c.Patient_Status, c.description, d.Name AS doctorName, d.Surname AS doctorSurname, u.Name AS patientName, u.Surname AS patientSurname FROM consults c INNER JOIN doctors d ON d.id = c.Doctor ".$HTI_verify_query."INNER JOIN usuarios u ON u.Identif = c.Patient WHERE c.DateTime BETWEEN ? AND ? ".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ?,20";
    
         $result = $con->prepare($query);
        $result->bindValue(1, $firstDayOfYear, PDO::PARAM_STR);
        $result->bindValue(2, $today, PDO::PARAM_STR);
        //$result->bindValue(3, $sortField, PDO::PARAM_STR);
        $result->bindValue(3, $page, PDO::PARAM_INT);
        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

        $num_pages = ceil($con->query('SELECT FOUND_ROWS()')->fetchColumn() / 20);

        //var_dump($rows);
    
        foreach($rows as &$row)
          {
            $temp_date = new DateTime($row['DateTime']);
            $row['DateTime'] = $temp_date->format("M j, Y g:i A");
            $row['Summary_PDF'] = 'Packages_Encrypted/'.$row['Summary_PDF'];
            $row['Data_File'] = 'Packages_Encrypted/'.$row['Data_File'];
            array_push($consultationData, $row);
          }
    
            /*echo $firstDayOfYear.'<br>';
            echo $today.'<br>';
            echo $sortField.'<br>';
            echo $page.'<br>';
            echo $scopeQuery.'<br>';
            echo $search_query;*/
          
          echo json_encode(array("consultations" => $consultationData, "pages" => $num_pages));
}
    
?>
