<?php
//PDMST CLASS
//Author : Kyle Austin
//Date: 04/28/15
//Purpose: To create dynamic class that can be used for multiple companies...

class pdmst{
	private $con;
	
	private $pdmst_owner_type;
	private $pdmst_owner_query_modifier;
	private $pdmst_owner_query_modifier_contract;
	private $pdmst_access_array = array();
	
	function __construct($owner_type){
		$this->pdmst_owner_type = $owner_type;
		
		if($this->pdmst_owner_type == 3){
			$this->pdmst_owner_query_modifier = "GrantAccess = 'HTI-COL' OR GrantAccess = 'HTI-RIVA' OR GrantAccess = 'HTI-24X7' OR GrantAccess = 'HTI-SPA' OR GrantAccess = 'HTI-SPA' OR GrantAccess = 'HTI-PR' OR GrantAccess = 'HTI-CR' OR GrantAccess = 'HTI-MEX' OR GrantAccess = 'HTI-ECU'";
			$this->pdmst_owner_query_modifier_contract = "contract = 'HTI-COL' OR contract = 'HTI-RIVA' OR contract = 'HTI-24X7' OR contract = 'HTI-SPA' OR contract = 'HTI-SPA' OR contract = 'HTI-PR' OR contract = 'HTI-CR' OR contract = 'HTI-MEX' OR contract = 'HTI-ECU'";
		}elseif($this->pdmst_owner_type == 1){
			$this->pdmst_owner_query_modifier = "GrantAccess = 'H2M' OR GrantAccess is null";
			$this->pdmst_owner_query_modifier_contract = "contract = 'H2M' OR contract is null";
		}else{
			$this->pdmst_owner_query_modifier = '';
			$this->pdmst_owner_query_modifier_contract = '';
		}
		
		require("../../environment_detail.php");

		$this->dbhost = $env_var_db["dbhost"];
		$this->dbname = $env_var_db["dbname"];
		$this->dbuser = $env_var_db["dbuser"];
		$this->dbpass = $env_var_db["dbpass"];
		
		session_start();
		
		// Connect to server and select databse.
		$this->con = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname.';charset=utf8', ''.$this->dbuser.'', ''.$this->dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
		if (!$this->con){
			die('Could not connect: ' . mysql_error());
			}
	}
	
	public function consultation_user_data($pass_scope, $pass_period){

		$scope = $this->cleanquery($pass_scope);
		$period = $this->cleanquery($pass_period);
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
				for($i=0;$i<=23;$i++)
				{
					$time = "";
					if($i < 10)
					{
						$time = "0";
					}
					$time .= $i;
					
					$query = $this->con->prepare("select consultationId from consults where DateTime between ? and ?");
					$query->bindValue(1, $curdate." ".$time.":00:00", PDO::PARAM_STR);
					$query->bindValue(2, $curdate." ".$time.":59:59", PDO::PARAM_STR);
					$query->execute();
					$result = $query->rowCount();
					
					$query1 = $this->con->prepare("select distinct(patient) from consults where DateTime between ? and ?");
					$query1->bindValue(1, $curdate." ".$time.":00:00", PDO::PARAM_STR);
					$query1->bindValue(2, $curdate." ".$time.":59:59", PDO::PARAM_STR);
					$query1->execute();
					$result1 = $query1->rowCount();           
				   
					array_push($hourlyConsultationData,$result);
					array_push($hourlyUserData,$result1);
				}
				
				$query2 = $this->con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime LIKE '".date("Y-m-d")."%'");
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

					$query = $this->con->prepare("select consultationId from consults where DateTime between ? and ?");
					$query->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
					$query->bindValue(2, $current_date." 23:59:59", PDO::PARAM_STR);
					$query->execute();
					$result = $query->rowCount();
					array_push($weeklyConsultationData,$result);
							
					
					$query1 = $this->con->prepare("select distinct(patient) from consults where DateTime between ? and ?");
					$query1->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
					$query1->bindValue(2, $current_date." 23:59:59", PDO::PARAM_STR);
					$query1->execute();
					$result1 = $query1->rowCount();
					array_push($weeklyUserData,$result1);
					$count++;
				}
				
				$query2 = $this->con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ?");
				$query2->bindValue(1, $dateOfLastSunday, PDO::PARAM_STR);
				$query2->bindValue(2, $todayDate, PDO::PARAM_STR);
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
					
					$query = $this->con->prepare("select consultationId from consults where DateTime between ? and ?");
					$query->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
					$query->bindValue(2, $current_date." 23:59:59", PDO::PARAM_STR);
					$query->execute();
					$result = $query->rowCount();
					array_push($monthlyConsultationData,$result);
							
					
					$query1 = $this->con->prepare("select distinct(patient) from consults where DateTime between ? and ?");
					$query1->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
					$query1->bindValue(2, $current_date." 23:59:59", PDO::PARAM_STR);
					$query1->execute();
					$result1 = $query1->rowCount();
					array_push($monthlyUserData,$result1);
					$count++;
				}
				
				$query2 = $this->con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ?");
				$query2->bindValue(1, $firstDateOfMonth, PDO::PARAM_STR);
				$query2->bindValue(2, $todayDate, PDO::PARAM_STR);
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
					
					$query = $this->con->prepare("select consultationId from consults where DateTime like ?");
					$query->bindValue(1, $compare_str."%", PDO::PARAM_STR);
					$query->execute();
					$result = $query->rowCount();
					array_push($yearlyConsultationData,$result);
							
					
					$query1 = $this->con->prepare("select distinct(patient) from consults where DateTime like ?");
					$query1->bindValue(1, $compare_str."%", PDO::PARAM_STR);
					$query1->execute();
					$result1 = $query1->rowCount();
					array_push($yearlyUserData,$result1);
					$count++;
				}

				$query2 = $this->con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ?");
				$query2->bindValue(1, $firstDateOfYear, PDO::PARAM_STR);
				$query2->bindValue(2, date("Y-m-d"), PDO::PARAM_STR);
				$query2->execute();
				$numUsers = $query2->rowCount();
				
			}
		//end of getting the yearly data if the selected data period is 'year'
		}//End of getting all global records for HTI


		//start of getting the hourly data if the selected data period is 'today'
		else
			
		{
			$scope_str = " and contract = ?";
			if($scope == 'H2M')
				$scope_str = ' and contract IS NULL';
			if($period == 1)
			{

			   
				$curdate = date("Y-m-d");
				for($i=0;$i<=23;$i++)
				{
					$time = "";
					if($i < 10)
					{
						$time = "0";
					}
					$time .= $i;
				
					$query = $this->con->prepare("select consultationId from consults where DateTime between ? and ?".$scope_str);
					$query->bindValue(1, $curdate." ".$time.":00:00", PDO::PARAM_STR);
					$query->bindValue(2, $curdate." ".$time.":59:59", PDO::PARAM_STR);
					if($scope != 'H2M')
						$query->bindValue(3, $scope, PDO::PARAM_STR);
					$query->execute();
					$result = $query->rowCount();
					
					$query1 = $this->con->prepare("select distinct(patient) from consults where DateTime between ? and ?".$scope_str);
					$query1->bindValue(1, $curdate." ".$time.":00:00", PDO::PARAM_STR);
					$query1->bindValue(2, $curdate." ".$time.":59:59", PDO::PARAM_STR);
					if($scope != 'H2M')
						$query1->bindValue(3, $scope, PDO::PARAM_STR);
					$query1->execute();
					$result1 = $query1->rowCount();           
				   
					array_push($hourlyConsultationData,$result);
					array_push($hourlyUserData,$result1);
				}
				
				$query2 = $this->con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime LIKE '".date("Y-m-d")."%'".$scope_str);
				if($scope != 'H2M')
						$query2->bindValue(1, $scope, PDO::PARAM_STR);
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
				
					$query = $this->con->prepare("select consultationId from consults where DateTime between ? and ?".$scope_str);
					$query->bindValue(1, $curdate." 00:00:00", PDO::PARAM_STR);
					$query->bindValue(2, $curdate." 23:59:59", PDO::PARAM_STR);
					if($scope != 'H2M')
						$query->bindValue(3, $scope, PDO::PARAM_STR);
					$query->execute();
					$result = $query->rowCount();
					array_push($weeklyConsultationData,$result);
							
					
					$query1 = $this->con->prepare("select distinct(patient) from consults where DateTime between ? and ?".$scope_str);
					$query1->bindValue(1, $curdate." 00:00:00", PDO::PARAM_STR);
					$query1->bindValue(2, $curdate." 23:59:59", PDO::PARAM_STR);
					if($scope != 'H2M')
						$query1->bindValue(3, $scope, PDO::PARAM_STR);
					$query1->execute();
					$result1 = $query1->rowCount();
					array_push($weeklyUserData,$result1);
					$count++;
				}
				
				$query2 = $this->con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ?".$scope_str);
				$query2->bindValue(1, $dateOfLastSunday, PDO::PARAM_STR);
				$query2->bindValue(2, $todayDate, PDO::PARAM_STR);
				if($scope != 'H2M')
						$query2->bindValue(1, $scope, PDO::PARAM_STR);
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
				
					$query = $this->con->prepare("select consultationId from consults where DateTime between ? and ?".$scope_str);
					$query->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
					$query->bindValue(2, $current_date." 23:59:59", PDO::PARAM_STR);
					if($scope != 'H2M')
						$query->bindValue(3, $scope, PDO::PARAM_STR);
					$query->execute();
					$result = $query->rowCount();
					array_push($monthlyConsultationData,$result);
							
					
					$query1 = $this->con->prepare("select distinct(patient) from consults where DateTime between ? and ?".$scope_str);
					$query1->bindValue(1, $current_date." 00:00:00", PDO::PARAM_STR);
					$query1->bindValue(2, $current_date." 23:59:59", PDO::PARAM_STR);
					if($scope != 'H2M')
						$query1->bindValue(3, $scope, PDO::PARAM_STR);
					$query1->execute();
					$result1 = $query1->rowCount();
					array_push($monthlyUserData,$result1);
					$count++;
				}
				
				$query2 = $this->con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ?".$scope_str);
				$query2->bindValue(1, $firstDateOfMonth, PDO::PARAM_STR);
				$query2->bindValue(2, $todayDate, PDO::PARAM_STR);
				if($scope != 'H2M')
						$query2->bindValue(3, $scope, PDO::PARAM_STR);
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
				
					$query = $this->con->prepare("select consultationId from consults where DateTime like ?".$scope_str);
					$query->bindValue(1, $compare_str."%", PDO::PARAM_STR);
					if($scope != 'H2M')
						$query->bindValue(2, $scope, PDO::PARAM_STR);
					$query->execute();
					$result = $query->rowCount();
					array_push($yearlyConsultationData,$result);
							
					
					$query1 = $this->con->prepare("select distinct(patient) from consults where DateTime like ?".$scope_str);
					$query1->bindValue(1, $compare_str."%", PDO::PARAM_STR);
					if($scope != 'H2M')
						$query1->bindValue(2, $scope, PDO::PARAM_STR);
					$query1->execute();
					$result1 = $query1->rowCount();
					array_push($yearlyUserData,$result1);
					$count++;
				}

				$query2 = $this->con->prepare("SELECT DISTINCT Patient FROM consults WHERE DateTime BETWEEN ? AND ?".$scope_str);
				$query2->bindValue(1, $firstDateOfYear, PDO::PARAM_STR);
				$query2->bindValue(2, date("Y-m-d"), PDO::PARAM_STR);
				if($scope != 'H2M')
						$query2->bindValue(3, $scope, PDO::PARAM_STR);
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
	}
	
	public function customer_data($pass_scope, $pass_period, $pass_order = null, $pass_field = null, $pass_search = null, $pass_page = null){

		//mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

		$scope = $this->cleanquery($pass_scope);
		$period = $this->cleanquery($pass_period);
		$sortOrder = $this->cleanquery($pass_order);
		$sortField = $this->cleanquery($pass_field);
		$search_query = '';
		if(isset($pass_field) && strlen($pass_field) > 0)
		{
			$search_query = 'AND (Name like "%'.($this->cleanquery($pass_field)).'%" OR Surname like "%'.($this->cleanquery($pass_field)).'%" OR email like "%'.($this->cleanquery($pass_field)).'%" OR telefono like "%'.($this->cleanquery($pass_field)).'%")';
		}
		$currentPage = $this->cleanquery($pass_page);
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
			$query = $this->con->prepare("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime like '".$date."%' ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField);
			$query->execute();
			$query_count = $query->rowCount();
			$num_pages = ceil($query_count / 20);
				  
			$query = $this->con->prepare("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime like '".$date."%' ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
			$query->execute();
			
			$result = array();
			while($row = $query->fetch(PDO::FETCH_ASSOC))
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
			$query = $this->con->prepare("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$dateOfLastSunday." AND ".$todayDate." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField);
			$query->execute();
			$query_count = $query->rowCount();
			$num_pages = ceil($query_count / 20);
			
			$query = $this->con->prepare("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$dateOfLastSunday." AND ".$todayDate." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
			$query->execute();
			
			$result = array();
			while($row = $query->fetch(PDO::FETCH_ASSOC))
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
			$query = $this->con->prepare("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDateOfMonth." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField);
			$query->execute();
			$query_count = $query->rowCount();
			$num_pages = ceil($query_count / 20);
			
			$query = $this->con->prepare("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDateOfMonth." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
			$query->execute();
			
			$result = array();
			while($row = $query->fetch(PDO::FETCH_ASSOC))
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


			$query = $this->con->prepare("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDayOfYear." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField);
			$query->execute();
			$query_count = $query->rowCount();
			$num_pages = ceil($query_count / 20);
			
			$query = $this->con->prepare("SELECT Name,Surname,SignUpDate,numberOfPhoneCalls,typeOfPlan,Identif FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && Identif IN (SELECT DISTINCT Patient FROM consults WHERE DateTime between ".$firstDayOfYear." AND ".$today." ".$scopeQuery.") ".$search_query."  ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
			$query->execute();
			
			$result = array();
			while($row = $query->fetch(PDO::FETCH_ASSOC))
			{
				$temp_date = new DateTime($row['SignUpDate']);
				$row['SignUpDate'] = $temp_date->format("F j, Y g:i A");
				array_push($result, $row);
			}
			echo json_encode(array("customers" => $result, "pages" => $num_pages));   
			
			
		}
	}
	
	public function consultation_data($pass_scope, $pass_period, $pass_order = null, $pass_field = null, $pass_search = null, $pass_page = null){

		//mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

		$scope = $this->cleanquery($pass_scope);
		$period = $this->cleanquery($pass_period);
		$sortOrder = $this->cleanquery($pass_order);
		$sortField = $this->cleanquery($pass_field);
		$currentPage = $this->cleanquery($pass_page);
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
		if(isset($pass_search) && strlen($pass_search) > 0)
		{
			$search_query = 'AND (doctorName like "%'.$pass_search.'%" OR doctorSurname like "%'.$pass_search.'%" OR patientName like "%'.$pass_search.'%" OR patientSurname like "%'.$pass_search.'%")';
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

				  $query = $this->con->prepare("select * from consults where contract != 'H2M' && contract is not null && DateTime like '".$date."%'".$scopeQuery." ".$search_query." ORDER BY ".$sortField);
				  $query->execute();
				  $query_count = $query->rowCount();
				  $num_pages = ceil($query_count / 20);

				  $query = $this->con->prepare("select * from consults where contract != 'H2M' && contract is not null && DateTime like '".$date."%'".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
				  $query->execute();

				  while($result = $query->fetch(PDO::FETCH_ASSOC))
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
				   
				  $query = $this->con->prepare("select * from consults where contract != 'H2M' && contract is not null && (DateTime between ".$dateOfLastSunday." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField);
				  $query->execute();
				  $query_count = $query->rowCount();
				  $num_pages = ceil($query_count / 20);
			
				  $query = $this->con->prepare("select * from consults where contract != 'H2M' && contract is not null && (DateTime between ".$dateOfLastSunday." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
				  $query->execute();
			
				  while($result = $query->fetch(PDO::FETCH_ASSOC))
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
				 $query = $this->con->prepare("select * from consults where contract != 'H2M' && contract is not null && (DateTime between ".$firstDateOfMonth." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField);
				 $query->execute();
				 $query_count = $query->rowCount();
				 $num_pages = ceil($query_count / 20);
			
				 $query = $this->con->prepare("select * from consults where contract != 'H2M' && contract is not null && (DateTime between ".$firstDateOfMonth." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
				 $query->execute();
					
				 while($result = $query->fetch(PDO::FETCH_ASSOC))
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
			

				  $query = $this->con->prepare("select * from consults where contract != 'H2M' && contract is not null && (DateTime between ".$firstDayOfYear." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField);
				  $query->execute();
				  $query_count = $query->rowCount();
				  $num_pages = ceil($query_count / 20);
		 
				  $query = $this->con->prepare("select * from consults where contract != 'H2M' && contract is not null && (DateTime between ".$firstDayOfYear." and ".$todayDate.") ".$scopeQuery." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
				  $query->execute();
					
				 while($result = $query->fetch(PDO::FETCH_ASSOC))
				  {

					$temp_date = new DateTime($result['DateTime'] );
					$result['DateTime'] = $temp_date->format("M j, Y g:i A");
					$result['Summary_PDF'] = 'Packages_Encrypted/'.$result['Summary_PDF'];
					$result['Data_File'] = 'Packages_Encrypted/'.$result['Data_File'];
					array_push($consultationData, $result);  
				  }
				  
				  
				  echo json_encode(array("consultations" => $consultationData, "pages" => $num_pages));
		}
	}

	public function doctors_data($pass_scope, $pass_period, $pass_order = null, $pass_field = null, $pass_search = null, $pass_page = null){

			$scope = $this->cleanquery($pass_scope);
			$period = $this->cleanquery($pass_period);
			$sortOrder = $this->cleanquery($pass_order);
			$sortField = $this->cleanquery($pass_field);
			$currentPage = $this->cleanquery($pass_page);
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
			if(isset($pass_search) && strlen($pass_search) > 0)
			{
				$search_query = 'AND (Name like "%'.(cleanquery($pass_search)).'%")';
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
				
				$query = $this->con->prepare("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE DateTime like '".$date."%' ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField);
				$query->execute();
				$query_count = $query->rowCount();
				$num_pages = ceil($query_count / 20);
				
				$query = $this->con->prepare("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE DateTime like '".$date."%' ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
				$query->execute();
				
				$result = array();
				while($row = $query->fetch(PDO::FETCH_ASSOC))
				{
					
					array_push($result, $row);
				}
				echo json_encode(array("doctors" => $result, "pages" => $num_pages));   


			}


			else if($period == 2)
			{
				// today
				$dateOfLastSunday = "'".date('Y-m-d',strtotime('last sunday'))." 00:00:00'";
				$todayDate = "'".date("Y-m-d")." 23:59:59'";

				$current_date = "";
				$count = 0;
				
				$query = $this->con->prepare("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE DateTime between ".$dateOfLastSunday." AND ".$todayDate." ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField);
				$query->execute();
				$query_count = $query->rowCount();
				$num_pages = ceil($query_count / 20);
				
				$query = $this->con->prepare("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE DateTime between ".$dateOfLastSunday." AND ".$todayDate." ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
				$query->execute();
				
				$result = array();
				while($row = $query->fetch(PDO::FETCH_ASSOC))
				{
					
					array_push($result, $row);
				}
				
				echo json_encode(array("doctors" => $result, "pages" => $num_pages));   


			}

			else if($period == 3)
			{
				// today
				$firstDateOfMonth = "'".date("Y-m-01")."'";
				$today = "'".date("Y-m-d")." 23:59:59'";
				
				$query = $this->con->prepare("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE (DateTime between ".$firstDateOfMonth." AND ".$today.") ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField);
				$query->execute();
				$query_count = $query->rowCount();
				$num_pages = ceil($query_count / 20);
				
				$query = $this->con->prepare("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE (DateTime between ".$firstDateOfMonth." AND ".$today.") ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
				$query->execute();
				$result = array();
				
				while($row = $query->fetch(PDO::FETCH_ASSOC))
				{
					
					array_push($result, $row);
				}
				
				echo json_encode(array("doctors" => $result, "pages" => $num_pages));   
			 }

			 else if($period == 4)
			 {
			// today

				$firstDayOfYear = "'".date("Y-01-01")." 00:00:00'";
				$today = "'".date("Y-m-d")." 23:59:59'";


				$query = $this->con->prepare("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE DateTime between ".$firstDayOfYear." AND ".$today." ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField);
				$query->execute();
				$query_count = $query->rowCount();
				$num_pages = ceil($query_count / 20);
				
				$query = $this->con->prepare("SELECT name,surname,calls,numberOfConsultedPatients,reportsCreated,id FROM doctors_calls WHERE id IN (SELECT DISTINCT doctor FROM consults WHERE DateTime between ".$firstDayOfYear." AND ".$today." ".$scopeQuery.") ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
				$query->execute();
				$result = array();
				
				while($row = $query->fetch(PDO::FETCH_ASSOC))
				{
					
					array_push($result, $row);
				}
				
				 echo json_encode(array("doctors" => $result, "pages" => $num_pages));   
			
			
			}
	}

	public function new_user_data($pass_scope, $pass_period, $pass_order = null, $pass_field = null, $pass_search = null, $pass_page = null){

		//mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

			$scope = $this->cleanquery($pass_scope);
			$period = $this->cleanquery($pass_period);
			$sortOrder = $this->cleanquery($pass_order);
			$sortField = $this->cleanquery($pass_field);
			$currentPage = $this->cleanquery($pass_page);
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
			if(isset($pass_search) && strlen($pass_search) > 0)
			{
				$search_query = 'AND (Name like "%'.$pass_search.'%")';
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
				
				$query = $this->con->prepare("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && signUpDate like '".$date."%' ".$search_query." ORDER BY ".$sortField);
				$query->execute();
				$query_count = $query->rowCount();
				$num_pages = ceil($query_count / 20);
				
				$query = $this->con->prepare("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && signUpDate like '".$date."%' ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
				$query->execute();
				
				$result = array();
				while($row = $query->fetch(PDO::FETCH_ASSOC))
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
				
				$query = $this->con->prepare("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && signUpDate between ".$dateOfLastSunday." AND ".$todayDate." ".$search_query." ORDER BY ".$sortField);
				$query->execute();
				$query_count = $query->rowCount();
				$num_pages = ceil($query_count / 20);
				
				$query = $this->con->prepare("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && signUpDate between ".$dateOfLastSunday." AND ".$todayDate." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
				$query->execute();
				
				$result = array();
				while($row = $query->fetch(PDO::FETCH_ASSOC))
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
				
				$query = $this->con->prepare("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && (signUpDate between ".$firstDateOfMonth." AND ".$today.") ".$search_query." ORDER BY ".$sortField);
				$query->execute();
				$query_count = $query->rowCount();
				$num_pages = ceil($query_count / 20);
				
				$query = $this->con->prepare("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && (signUpDate between ".$firstDateOfMonth." AND ".$today.") ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
				$query->execute();
				
				$result = array();
				while($row = $query->fetch(PDO::FETCH_ASSOC))
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

				$query = $this->con->prepare("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && signUpDate between ".$firstDayOfYear." AND ".$today." ".$search_query." ORDER BY ".$sortField);
				$query->execute();
				$query_count = $query->rowCount();
				$num_pages = ceil($query_count / 20);
				
				$query = $this->con->prepare("SELECT Identif,name,surname,telefono,email,signupdate FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null && signUpDate between ".$firstDayOfYear." AND ".$today." ".$search_query." ORDER BY ".$sortField." LIMIT ".(($currentPage - 1) * 20).",20");
				$query->execute();
				$result = array();
				
				while($row = $query->fetch(PDO::FETCH_ASSOC))
				{
					
					array_push($result, $row);
				}
				
				 echo json_encode(array("newusers" => $result, "pages" => $num_pages));   
			
			
			}
	}

	public function pdmst_decrypt($pass_file = null, $pass_erase = null, $pass_erase_file = null, $pass_recording = null){

		$path_parts = "";
		$erase = false;
		$erase_file = "";
		$is_recording = false;
		if(isset($pass_file))
		{
			$path_parts = pathinfo($pass_file);
		}
		if(isset($pass_erase))
		{
			$erase = true;
		}
		if(isset($pass_erase_file))
		{
			$erase_file = $pass_erase_file;
		}
		if(isset($pass_recording))
		{
			$is_recording = $pass_recording;
		}

		if($erase)
		{
			set_time_limit (27);
			sleep(25);
			echo unlink($erase_file);
		}
		else if($is_recording)
		{
			$enc_result = $this->con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
			$enc_result->execute();
			$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
			$enc_pass=$row_enc['pass'];
			$cmd = 'echo "'.$enc_pass.'" | openssl aes-256-cbc -pass stdin -d -in recordings/recordings/'.$path_parts['basename'].' -out temp/'.$path_parts['basename'].'.mp3';
			shell_exec($cmd);
			
			echo 'temp/'.$path_parts['basename'].'.mp3';
		}
		else
		{
			$enc_result = $this->con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
			$enc_result->execute();
			$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
			$enc_pass=$row_enc['pass'];
			$cmd = 'echo "'.$enc_pass.'" | openssl aes-256-cbc -pass stdin -d -in '.$path_parts['dirname'].'/'.$path_parts['basename'].' -out temp/'.$path_parts['basename'];
			shell_exec($cmd);
			
			echo 'temp/'.$path_parts['basename'];
		}
	}

	public function doctor_timeslots($pass_scope, $pass_period){

		$tempDate = date('Y-m-d');
		$period = $pass_period;
		$scope = $pass_scope;
		$dow = intval(date('N', strtotime($tempDate)));

		$num_slots = 96;
		if($period == 2)
		{
			$num_slots = 84;
		}
		else if($period == 3)
		{
			$num_slots = 124;
		}

		if($dow == 7)
		{
			$dow = 0;
		}

		$date = new DateTime($tempDate);
		if($dow > 0)
		{
			$date->sub(new DateInterval('P'.$dow.'D'));
		}
		$week = $date->format('Y-m-d');
		$result = '';
		if($period == 1)
		{
			$result = $this->con->prepare("SELECT * FROM timeslots WHERE week = ? AND week_day = ?");
			$result->bindValue(1, $week, PDO::PARAM_STR);
			$result->bindValue(2, $dow, PDO::PARAM_INT);
		}
		else if($period == 2)
		{
			$result = $this->con->prepare("SELECT * FROM timeslots WHERE week = ?");
			$result->bindValue(1, $week, PDO::PARAM_STR);
		}
		else
		{
			$month = date("Y-m");
			$result = $this->con->prepare("SELECT * FROM timeslots WHERE DATE_ADD(week, INTERVAL week_day DAY) LIKE ?");
			$result->bindValue(1, $month."%", PDO::PARAM_STR);
		}
		$result->execute();

		$result_array = array();

		$scope_search = false;
		if($scope != 'Global');
		{
			$val = 0;
			if($scope == 'NULL')
			{
				$val = 1;
			}
			else if($scope == 'HTI-COL')
			{
				$val = 3;
			}
			$scope_result = $this->con->prepare("SELECT id FROM doctors WHERE previlege = ?");
			$scope_result->bindValue(1, $this->pdmst_owner_type, PDO::PARAM_INT);
			$scope_result->execute();
			$scope_search = array();
			if($scope_result->rowCount() > 0)
			{
				while($scope_row = $scope_result->fetch(PDO::FETCH_ASSOC))
				{
					array_push($scope_search, $scope_row['id']);
				}
			}
			
			
		}

		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			if($scope == 'Global' || in_array($row['doc_id'], $scope_search))
			{
				if(array_key_exists($row['doc_id'], $result_array))
				{   
					$start_slot = 0;
					$end_slot = 0;
					$start = explode(":", $row['start_time']);
					$end = explode(":", $row['end_time']);
					if($period == 1)
					{
						$start_slot = (intval($start[0]) * 4) + floor(intval($start[1]) / 15);
						$end_slot = (intval($end[0]) * 4) + floor(intval($end[1]) / 15);
					}
					else if($period == 2)
					{
						$start_slot = (intval($row['week_day']) * 12) + floor(intval($start[0]) / 2);
						$end_slot = (intval($row['week_day']) * 12) + floor(intval($end[0]) / 2);
					}
					else
					{
						$month_day = new DateTime($row['week']);
						$month_day->add(new DateInterval('P'.$row['week_day'].'D'));
						$day = intval($month_day->format('d'));
						$start_slot = (($day - 1) * 4) + floor(intval($start[0]) / 6);
						$end_slot = (($day - 1) * 4) + floor(intval($end[0]) / 6);
					}


					for($x = $start_slot; $x <= $end_slot; $x++)
					{
						$result_array[ $row['doc_id'] ][$x] = 1;
					}
				}
				else
				{
					$slots = array();
					for($i = 0; $i < $num_slots; $i++)
					{
						array_push($slots, 0);
					}
					$start_slot = 0;
					$end_slot = 0;
					$start = explode(":", $row['start_time']);
					$end = explode(":", $row['end_time']);
					if($period == 1)
					{
						$start_slot = (intval($start[0]) * 4) + floor(intval($start[1]) / 15);
						$end_slot = (intval($end[0]) * 4) + floor(intval($end[1]) / 15);
					}
					else if($period == 2)
					{
						$start_slot = (intval($row['week_day']) * 12) + floor(intval($start[0]) / 2);
						$end_slot = (intval($row['week_day']) * 12) + floor(intval($end[0]) / 2);
					}
					else
					{
						$month_day = new DateTime($row['week']);
						$month_day->add(new DateInterval('P'.$row['week_day'].'D'));
						$day = intval($month_day->format('d'));
						$start_slot = (($day - 1) * 4) + floor(intval($start[0]) / 6);
						$end_slot = (($day - 1) * 4) + floor(intval($end[0]) / 6);
					}
					for($x = $start_slot; $x <= $end_slot; $x++)
					{
						$slots[$x] = 1;
					}

					$result_array[ $row['doc_id'] ] = $slots;
				}
			}
		}



		$keys = array_keys($result_array);

		if(count($keys) > 0)
		{

			$query = $this->con->prepare("SELECT id,Name,Surname FROM doctors WHERE id IN (".implode(",", $keys).")");
			$query->execute();
			while($key = $query->fetch(PDO::FETCH_ASSOC))
			{
				if(array_key_exists($key['id'], $result_array))
				{
					$result_array[ $key['Name'].' '.$key['Surname'] ] = $result_array[ $key['id'] ];
					unset($result_array[ $key['id'] ]);
				}
			}
		}

		echo json_encode($result_array);
	}

	private function cleanquery($string){
	  if (get_magic_quotes_gpc())
	  {
	  $string = stripslashes($string);
	  }

	  return $string;
	}
}
?>
