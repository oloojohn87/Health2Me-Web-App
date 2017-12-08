<?php

require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

  function cleanquery($string)
{
  if (get_magic_quotes_gpc())
  {
  $string = stripslashes($string);
  }
  $string = mysql_real_escape_string($string);
  return $string;
}

$scope = "Global";
$medid = cleanquery($_POST['medid']);
$period = cleanquery($_POST['period']);
$sortOrder = cleanquery($_POST['sortOrder']);
$sortField = cleanquery($_POST['sortField']);
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

$search_query = '';
if(isset($_POST['searchField']) && strlen($_POST['searchField']) > 0)
{
    $search_query = 'AND (doctorName like "%'.$_POST['searchField'].'%" OR doctorSurname like "%'.$_POST['searchField'].'%" OR patientName like "%'.$_POST['searchField'].'%" OR patientSurname like "%'.$_POST['searchField'].'%")';
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


if($period == 1) // Start of getting today consultation data
{
          $date = date("Y-m-d");
          $doctorNameList = array();
          $patientNameList = array();
          $consultationData = array();

          $query = mysql_query("select * from consults where Doctor = ".$medid." AND DateTime like '".$date."%'".$scopeQuery." ".$search_query." ORDER BY ".$sortField);
          $num_pages = ceil(mysql_num_rows($query)/10);

          $query = mysql_query("select * from consults where Doctor = ".$medid." AND  DateTime like '".$date."%'".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 10).",10");

          while($result = mysql_fetch_array($query))
          {

            $temp_date = new DateTime($result['DateTime'] );
            $result['DateTime']  = $temp_date->format("M j, Y g:i A");
            $result['Summary_PDF'] = 'Packages_Encrypted/'.$result['Summary_PDF'];
            $result['Data_File'] = 'Packages_Encrypted/'.$result['Data_File'];
            array_push($consultationData, $result); 
        
              
          }
          
          echo json_encode(array("consultations" => $consultationData, "pages" => $num_pages));
}

if($period == 2)
{
          $dateOfLastSunday = "'".date('Y-m-d',strtotime('last sunday'))." 00:00:00'";
          $todayDate = "'".date("Y-m-d")." 23:59:59'";
          $doctorNameList = array();
          $patientNameList = array();
          $consultationData = array();
           
          $query = mysql_query("select * from consults where Doctor = ".$medid." AND  (DateTime between ".$dateOfLastSunday." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField);
          $num_pages = ceil(mysql_num_rows($query)/10);
    
          $query = mysql_query("select * from consults where Doctor = ".$medid." AND  (DateTime between ".$dateOfLastSunday." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 10).",10");
         
    
          while($result = mysql_fetch_array($query))
          {

            $temp_date = new DateTime($result['DateTime'] );
            $result['DateTime']  = $temp_date->format("M j, Y g:i A");
            $result['Summary_PDF'] = 'Packages_Encrypted/'.$result['Summary_PDF'];
            $result['Data_File'] = 'Packages_Encrypted/'.$result['Data_File'];
            array_push($consultationData, $result); 
          }
          
          echo json_encode(array("consultations" => $consultationData, "pages" => $num_pages));
          
}

if($period == 3)
{
         $firstDateOfMonth = "'".date("Y-m-01")." 00:00:00'";
         $todayDate = "'".date("Y-m-d")." 23:59:59'";
         
         $doctorNameList = array();
         $patientNameList = array();
         $consultationData = array();
         $query = mysql_query("select * from consults where Doctor = ".$medid." AND  (DateTime between ".$firstDateOfMonth." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField);
         $num_pages = ceil(mysql_num_rows($query)/10);
    
         $query = mysql_query("select * from consults where Doctor = ".$medid." AND  (DateTime between ".$firstDateOfMonth." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 10).",10");
            
         while($result = mysql_fetch_array($query))
          {

            $temp_date = new DateTime($result['DateTime'] );
            $result['DateTime']  = $temp_date->format("M j, Y g:i A");
            $result['Summary_PDF'] = 'Packages_Encrypted/'.$result['Summary_PDF'];
            $result['Data_File'] = 'Packages_Encrypted/'.$result['Data_File'];
            array_push($consultationData, $result);  
          }
          
          
          echo json_encode(array("consultations" => $consultationData, "pages" => $num_pages));
    
    
}

if($period == 4)
{
 
          $firstDayOfYear = "'".date("Y-01-01")." 00:00:00'";
          $todayDate = "'".date("Y-m-d")." 23:59:59'";
          $doctorNameList = array();
          $patientNameList = array();
          $consultationData = array();
    

          $query = mysql_query("select * from consults where Doctor = ".$medid." AND  (DateTime between ".$firstDayOfYear." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField);
          $num_pages = ceil(mysql_num_rows($query)/10);
 
          $query = mysql_query("select * from consults where Doctor = ".$medid." AND  (DateTime between ".$firstDayOfYear." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 10).",10");
    
         while($result = mysql_fetch_array($query))
          {

            $temp_date = new DateTime($result['DateTime'] );
            $result['DateTime'] = $temp_date->format("M j, Y g:i A");
            $result['Summary_PDF'] = 'Packages_Encrypted/'.$result['Summary_PDF'];
            $result['Data_File'] = 'Packages_Encrypted/'.$result['Data_File'];
            array_push($consultationData, $result);  
          }
          
          
          echo json_encode(array("consultations" => $consultationData, "pages" => $num_pages));
}
    
?>
