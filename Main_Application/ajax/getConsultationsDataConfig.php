

<?php
//Author: Pallab Hazarika
//Version : 1.0
//Updated By:

require("environment_detail.php");

$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$scope = "Global";
$period = $_POST['period'];
$group;
$interval;
$hourlyConsultationData = array();
$hourlyUserData = array();
$weeklyConsultationData = array();
$weeklyUserData = array();
$monthlyConsultationData = array();
$monthlyUserData = array();
$yearlyConsultationData = array();
$yearlyUserData = array();
$numUsers = 0;
$med_id = $_POST['medid'];

$distinctTodayUserCount;
$distinctWeekyUserCount;
$distinctMonthyUserCount;    
$distinctYearlyUserCount;


//Start of getting all global records for HTI
if($scope == "Global")
{
   
        //start of getting the hourly data if the selected data period is 'today'
    if($period == 1)
    {
      
        $curdate = date("Y-m-d");
        /*$query = mysql_query("SELECT Patient,datetime FROM consults WHERE DateTime like '".$curdate."%'");
        while($row = mysql_fetch_assoc($query))
        {
            $hour = intval(explode(":", explode(" ", $row['DateTime'])[1])[0]);
            $hourlyConsultationData[$hour] += 1;
        }
       */
        for($i=0;$i<=23;$i++)
        {
            $time = "";
            if($i < 10)
            {
                $time = "0";
            }
            $time .= $i;
            
            $query = $con->prepare("select consultationId from consults where DateTime between ? and ? AND Doctor = ?");
            $query->bindValue(1, $curdate." ".$time.":00:00", PDO::PARAM_STR);
            $query->bindValue(2, $curdate." ".$time.":59:59", PDO::PARAM_STR);
            $query->bindValue(3, $med_id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->rowCount();
            
            $query1 = $con->prepare("select distinct(patient) from consults where DateTime between ? and ? AND Doctor = ?");
            $query1->bindValue(1, $curdate." ".$time.":00:00", PDO::PARAM_STR);
            $query1->bindValue(2, $curdate." ".$time.":59:59", PDO::PARAM_STR);
            $query1->bindValue(3, $med_id, PDO::PARAM_INT);
            $query1->execute();
            $result1 = $query1->rowCount();           
           
            array_push($hourlyConsultationData,$result);
            array_push($hourlyUserData,$result1);
        }
        
        $query2 = $con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime LIKE '".date("Y-m-d")."%' AND Doctor = ?");
        $query2->bindValue(1, $med_id, PDO::PARAM_INT);
        $query2->execute();
        $numUsers = $query2->rowCount();     
    }
    
    //start of getting the weekly data if the selected data period is 'week'
    elseif($period == 2)
    {
       
        $dateOfLastSunday = date('Y-m-d',strtotime('last sunday'));
        $todayDate = date("Y-m-d");
        
        $current_date = "";
        $count = 0;
        
       while(strcmp($current_date, $todayDate) != 0)
       {
            
            $current_date = date("Y-m-d", strtotime("+".$count." day", strtotime($dateOfLastSunday)));

            $query = $con->prepare("select consultationId from consults where DateTime between ? and ? AND Doctor = ?");
            $query->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
            $query->bindValue(2, $todayDate." 23:59:59", PDO::PARAM_STR);
            $query->bindValue(3, $med_id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->rowCount();
            array_push($weeklyConsultationData,$result);
                    
            
            $query1 = $con->prepare("select distinct(patient) from consults where DateTime between ? and ? AND Doctor = ?");
            $query1->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
            $query1->bindValue(2, $todayDate." 23:59:59", PDO::PARAM_STR);
            $query1->bindValue(3, $med_id, PDO::PARAM_INT);
            $query1->execute();
            $result1 = $query1->rowCount();
            array_push($weeklyUserData,$result1);
            $count++;
        }
        
        $query2 = $con->prepare("SELECT DISTINCT(Patient) FROM consults WHERE DateTime BETWEEN ? AND ? AND Doctor = ?");
        $query2->bindValue(1, $dateOfLastSunday, PDO::PARAM_STR);
        $query2->bindValue(2, $todayDate, PDO::PARAM_STR);
        $query2->bindValue(3, $med_id, PDO::PARAM_INT);
        $query2->execute();
        $numUsers = $query2->rowCount();
    }
    
    //start of getting the monthy data if the selected data period is 'month'
    elseif($period == 3)
    {
        $firstDateOfMonth = date('Y-m-01');
        $todayDate = date("Y-m-d");
        $current_date = "";
        $count = 0;
        
        while(strcmp($current_date, $todayDate) != 0)
        {
            $current_date = date("Y-m-d", strtotime("+".$count." day", strtotime($firstDateOfMonth)));
            
            $query = $con->prepare("select consultationId from consults where DateTime between ? and ? AND Doctor = ?");
            $query->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
            $query->bindValue(2, $current_date." 23:59:59", PDO::PARAM_STR);
            $query->bindValue(3, $med_id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->rowCount();
            array_push($monthlyConsultationData,$result);
                    
            
            $query1 = $con->prepare("select distinct(patient) from consults where DateTime between ? and ? AND Doctor = ?");
            $query1->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
            $query1->bindValue(2, $current_date." 23:59:59", PDO::PARAM_STR);
            $query1->bindValue(3, $med_id, PDO::PARAM_INT);
            $query1->execute();
            $result1 = $query1->rowCount();
            array_push($monthlyUserData,$result1);
            $count++;
        }
        
        $query2 = $con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ? AND Doctor = ?");
        $query2->bindValue(1, $firstDateOfMonth, PDO::PARAM_STR);
        $query2->bindValue(2, $todayDate, PDO::PARAM_STR);
        $query2->bindValue(3, $med_id, PDO::PARAM_INT);
        $query2->execute();
        $numUsers = $query2->rowCount();
        
    }
    //end of getting the monthly data if the selected data period is 'month'
    
    //Start of getting the yearly data if the selected data period is 'year'              
    elseif($period == 4)
    {
        $firstDateOfYear = date('Y-01-01');
        $todayDate = date("Y-m-01");
        $current_date = "";
        $count = 0;
        
        while(strcmp($current_date, $todayDate) != 0)
        {
            $current_date = date("Y-m-d", strtotime("+".$count." month", strtotime($firstDateOfYear)));
            $compare_str = explode("-", $current_date);
            $compare_str = $compare_str[0]."-".$compare_str[1];
            
            $query = $con->prepare("select consultationId from consults where DateTime like ? AND Doctor = ?");
            $query->bindValue(1, $compare_str."%", PDO::PARAM_STR);
            $query->bindValue(2, $med_id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->rowCount();
            array_push($yearlyConsultationData,$result);
                    
            
            $query1 = $con->prepare("select distinct(patient) from consults where DateTime like ? AND Doctor = ?");
            $query1->bindValue(1, $compare_str."%", PDO::PARAM_STR);
            $query1->bindValue(2, $med_id, PDO::PARAM_INT);
            $query1->execute();
            $result1 = $query1->rowCount();
            array_push($yearlyUserData,$result1);
            $count++;
        }

        $query2 = $con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ? AND Doctor = ?");
        $query2->bindValue(1, $firstDateOfYear, PDO::PARAM_STR);
        $query2->bindValue(2, date("Y-m-d"), PDO::PARAM_STR);
        $query2->bindValue(3, $med_id, PDO::PARAM_INT);
        $query2->execute();
        $numUsers = $query2->rowCount();
        
    }
//end of getting the yearly data if the selected data period is 'year'
}//End of getting all global records for HTI


//start of getting the hourly data if the selected data period is 'today'
else
    
{
    $scope_str = " and contract = ?";
    if($scope == 'NULL')
        $scope_str = ' and contract IS NULL';
    if($period == 1)
    {

       
        $curdate = date("Y-m-d");
    /*$query = mysql_query("SELECT Patient,datetime FROM consults WHERE DateTime like '".$curdate."%'");
    while($row = mysql_fetch_assoc($query))
    {
        $hour = intval(explode(":", explode(" ", $row['DateTime'])[1])[0]);
        $hourlyConsultationData[$hour] += 1;
    }
   */
        for($i=0;$i<=23;$i++)
        {
            $time = "";
            if($i < 10)
            {
                $time = "0";
            }
            $time .= $i;
        
            $query = $con->prepare("select consultationId from consults where DateTime between ? and ? AND Doctor = ? ".$scope_str);
            $query->bindValue(1, $curdate." ".$time.":00:00", PDO::PARAM_STR);
            $query->bindValue(2, $curdate." ".$time.":59:59", PDO::PARAM_STR);
            $query->bindValue(3, $med_id, PDO::PARAM_INT);
            if($scope != 'NULL')
                $query->bindValue(3, $scope, PDO::PARAM_STR);
            $query->execute();
            $result = $query->rowCount();
            
            $query1 = $con->prepare("select distinct(patient) from consults where DateTime between ? and ? AND Doctor = ? ".$scope_str);
            $query1->bindValue(1, $curdate." ".$time.":00:00", PDO::PARAM_STR);
            $query1->bindValue(2, $curdate." ".$time.":59:59", PDO::PARAM_STR);
            $query1->bindValue(3, $med_id, PDO::PARAM_INT);
            if($scope != 'NULL')
                $query1->bindValue(3, $scope, PDO::PARAM_STR);
            $query1->execute();
            $result1 = $query1->rowCount();           
           
            array_push($hourlyConsultationData,$result);
            array_push($hourlyUserData,$result1);
        }
        
        $query2 = $con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime LIKE '".date("Y-m-d")."%' AND Doctor = ? ".$scope_str);
        if($scope != 'NULL'){
                $query1->bindValue(1, $med_id, PDO::PARAM_INT);
                $query2->bindValue(2, $scope, PDO::PARAM_STR);
			}else{
				$query1->bindValue(1, $med_id, PDO::PARAM_INT);
			}
        $query2->execute();
        $numUsers = $query2->rowCount();   
    }
    //End of getting the hourly data if the selected data period is 'today'
    
    //start of getting the weekly data if the selected data period is 'week'
    elseif($period == 2)
    {
       

        $dateOfLastSunday = date('Y-m-d',strtotime('last sunday'));
        $todayDate = date("Y-m-d");
        
        $current_date = "";
        $count = 0;
        
        while(strcmp($current_date, $todayDate) != 0)
        {
        
            $current_date = date("Y-m-d", strtotime("+".$count." day", strtotime($dateOfLastSunday)));
        
            $query = $con->prepare("select consultationId from consults where DateTime between ? and ? AND Doctor = ? ".$scope_str);
            $query->bindValue(1, $curdate." 00:00:00", PDO::PARAM_STR);
            $query->bindValue(2, $curdate." 23:59:59", PDO::PARAM_STR);
            $query->bindValue(3, $med_id, PDO::PARAM_INT);
            if($scope != 'NULL')
                $query->bindValue(4, $scope, PDO::PARAM_STR);
            $query->execute();
            $result = $query->rowCount();
            array_push($weeklyConsultationData,$result);
                    
            
            $query1 = $con->prepare("select distinct(patient) from consults where DateTime between ? and ? AND Doctor = ? ".$scope_str);
            $query1->bindValue(1, $curdate." 00:00:00", PDO::PARAM_STR);
            $query1->bindValue(2, $curdate." 23:59:59", PDO::PARAM_STR);
            $query1->bindValue(3, $med_id, PDO::PARAM_INT);
            if($scope != 'NULL')
                $query1->bindValue(4, $scope, PDO::PARAM_STR);
            $query1->execute();
            $result1 = $query1->rowCount();
            array_push($weeklyUserData,$result1);
            $count++;
        }
        
        $query2 = $con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ? AND Doctor = ? ".$scope_str);
        $query2->bindValue(1, $dateOfLastSunday, PDO::PARAM_STR);
        $query2->bindValue(2, $todayDate, PDO::PARAM_STR);
        $query2->bindValue(3, $med_id, PDO::PARAM_INT);
        if($scope != 'NULL')
                $query2->bindValue(4, $scope, PDO::PARAM_STR);
        $query2->execute();
        $numUsers = $query2->rowCount();

    }
    
    //start of getting the monthy data if the selected data period is 'month'
    elseif($period == 3)
    {

        $firstDateOfMonth = date('Y-m-01');
        $todayDate = date("Y-m-d");
        $current_date = "";
        $count = 0;

        while(strcmp($current_date, $todayDate) != 0)
        {
            $current_date = date("Y-m-d", strtotime("+".$count." day", strtotime($firstDateOfMonth)));
        
            $query = $con->prepare("select consultationId from consults where DateTime between ? and ? AND Doctor = ? ".$scope_str);
            $query->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
            $query->bindValue(2, $current_date." 23:59:59", PDO::PARAM_STR);
            $query->bindValue(3, $med_id, PDO::PARAM_INT);
            if($scope != 'NULL')
                $query->bindValue(4, $scope, PDO::PARAM_STR);
            $query->execute();
            $result = $query->rowCount();
            array_push($monthlyConsultationData,$result);
                    
            
            $query1 = $con->prepare("select distinct(patient) from consults where DateTime between ? and ? AND Doctor = ? ".$scope_str);
            $query1->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
            $query1->bindValue(2, $current_date." 23:59:59", PDO::PARAM_STR);
            $query1->bindValue(3, $med_id, PDO::PARAM_INT);
            if($scope != 'NULL')
                $query1->bindValue(4, $scope, PDO::PARAM_STR);
            $query1->execute();
            $result1 = $query1->rowCount();
            array_push($monthlyUserData,$result1);
            $count++;
        }
        
        $query2 = $con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ? AND Doctor = ? ".$scope_str);
        $query2->bindValue(1, $firstDateOfMonth, PDO::PARAM_STR);
        $query2->bindValue(2, $todayDate, PDO::PARAM_STR);
        $query2->bindValue(3, $med_id, PDO::PARAM_INT);
        if($scope != 'NULL')
                $query2->bindValue(4, $scope, PDO::PARAM_STR);
        $query2->execute();
        $numUsers = $query2->rowCount();
    }
    //end of getting the monthly data if the selected data period is 'month'
    
    //Start of getting the yearly data if the selected data period is 'year'              
    elseif($period == 4)
    {

        $firstDateOfYear = date('Y-01-01');
        $todayDate = date("Y-m-01");
        $current_date = "";
        $count = 0;
        
        while(strcmp($current_date, $todayDate) != 0)
        {
            $current_date = date("Y-m-d", strtotime("+".$count." month", strtotime($firstDateOfYear)));
            $compare_str = explode("-", $current_date);
            $compare_str = $compare_str[0]."-".$compare_str[1];
        
            $query = $con->prepare("select consultationId from consults where DateTime like ? AND Doctor = ? ".$scope_str);
            $query->bindValue(1, $compare_str."%", PDO::PARAM_STR);
            $query->bindValue(2, $med_id, PDO::PARAM_INT);
            if($scope != 'NULL')
                $query->bindValue(3, $scope, PDO::PARAM_STR);
            $query->execute();
            $result = $query->rowCount();
            array_push($yearlyConsultationData,$result);
                    
            
            $query1 = $con->prepare("select distinct(patient) from consults where DateTime like ? AND Doctor = ? ".$scope_str);
            $query1->bindValue(1, $compare_str."%", PDO::PARAM_STR);
            $query1->bindValue(2, $med_id, PDO::PARAM_INT);
            if($scope != 'NULL')
                $query1->bindValue(3, $scope, PDO::PARAM_STR);
            $query1->execute();
            $result1 = $query1->rowCount();
            array_push($yearlyUserData,$result1);
            $count++;
        }

        $query2 = $con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ? AND Doctor = ? ".$scope_str);
        $query2->bindValue(1, $firstDateOfYear, PDO::PARAM_STR);
        $query2->bindValue(2, date("Y-m-d"), PDO::PARAM_STR);
        $query2->bindValue(3, $med_id, PDO::PARAM_INT);
        if($scope != 'NULL')
                $query2->bindValue(4, $scope, PDO::PARAM_STR);
        $query2->execute();
        $numUsers = $query2->rowCount();
     }

}
//end of getting the yearly data if the selected data period is 'year'

if($period == 1)
{
 echo json_encode(array("consultations" => $hourlyConsultationData, "users" => $hourlyUserData, "numUsers" => $numUsers));
}

elseif($period == 2)
{
 echo json_encode(array("consultations" => $weeklyConsultationData, "users" => $weeklyUserData, "numUsers" => $numUsers));
}

elseif($period == 3)
{
 echo json_encode(array("consultations" => $monthlyConsultationData, "users" => $monthlyUserData, "numUsers" => $numUsers));
}
elseif($period == 4)
{
 echo json_encode(array("consultations" => $yearlyConsultationData, "users" => $yearlyUserData, "numUsers" => $numUsers));
}
?>
