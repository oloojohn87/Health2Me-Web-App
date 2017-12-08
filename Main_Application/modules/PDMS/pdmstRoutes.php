<?php
//ROUTING FOR PDMST CLASS
include_once('pdmstClass.php');
if(isset($_GET['access'])){
	$access_privs = $_GET['access'];
}else{
	$access_privs = 2;
}
$pdmst = new pdmst($access_privs);

if(isset($_GET['route'])){
	$pass_scope = $_POST['scope'];
	$pass_period = $_POST['period'];
	$pass_order = $_POST['sortOrder'];
	$pass_field = $_POST['sortField'];
	$pass_search = $_POST['searchField'];
	$pass_page = $_POST['currentPage'];
	
	switch(true){
		case($_GET['route'] == 'consultation_user_data'):
			if(isset($_POST['scope']) && isset($_POST['period'])){
				$pdmst->consultation_user_data($_POST['scope'], $_POST['period']);
			}else{
				echo 'Call this function correctly stupid!';
			}
			break;
		case($_GET['route'] == 'customer_data'):
			if(isset($_POST['scope']) && isset($_POST['period'])){
				$pdmst->customer_data($pass_scope, $pass_period, $pass_order, $pass_field, $pass_search, $pass_page);
			}else{
				echo 'Call this function correctly stupid!';
			}
			break;
		case($_GET['route'] == 'consultation_data'):
			if(isset($_POST['scope']) && isset($_POST['period'])){
				$pdmst->consultation_data($pass_scope, $pass_period, $pass_order, $pass_field, $pass_search, $pass_page);
			}else{
				echo 'Call this function correctly stupid!';
			}
			break;
		case($_GET['route'] == 'doctors_data'):
			if(isset($_POST['scope']) && isset($_POST['period'])){
				$pdmst->doctors_data($pass_scope, $pass_period, $pass_order, $pass_field, $pass_search, $pass_page);
			}else{
				echo 'Call this function correctly stupid!';
			}
			break;
		case($_GET['route'] == 'new_user_data'):
			if(isset($_POST['scope']) && isset($_POST['period'])){
				$pdmst->new_user_data($pass_scope, $pass_period, $pass_order, $pass_field, $pass_search, $pass_page);
			}else{
				echo 'Call this function correctly stupid!';
			}
			break;
		case($_GET['route'] == 'pdmst_decrypt'):
			$pass_file = $_POST['file'];
			$pass_erase = $_POST['erase'];
			$pass_erase_file = $_POST['erase_file'];
			$pass_recording = $_POST['recording'];
			
			$pdmst->pdmst_decrypt($pass_file, $pass_erase, $pass_erase_file, $pass_recording);
			break;
		case($_GET['route'] == 'doctor_timeslots'):
			$pdmst->doctor_timeslots($pass_scope, $pass_period);
			break;
		default:
		echo 'You suck at calling this class!';
			break;
	}
}
?>
