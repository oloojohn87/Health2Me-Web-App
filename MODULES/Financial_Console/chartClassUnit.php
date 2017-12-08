<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('chartClass.php');

if(isset($_GET['ajax'])){
	$ajax = 1;
}else{
	$ajax = 0;
}

$chart_type = 'simple';

$chart = new chartClass($ajax, $chart_type, 'chartClassUnit');

if(isset($_GET['ajax']) && $_GET['ajax'] == 'grab_financial_stats' && isset($_POST['period']) && isset($_POST['medid'])){
	
	$chart->grab_financial_ajax($_POST['period'],$_POST['medid']);
}

if(isset($_GET['ajax']) && $_GET['ajax'] == 'update_tracking_price' && isset($_POST['price']) && isset($_POST['medid'])){
	
	$chart->update_tracking_price_ajax($_POST['price'],$_POST['medid']);
}

if(isset($_GET['ajax']) && $_GET['ajax'] == 'update_consult_price' && isset($_POST['price']) && isset($_POST['medid'])){
	
	$chart->update_consult_price_ajax($_POST['price'],$_POST['medid']);
}

if(isset($_GET['ajax']) && $_GET['ajax'] == 'grab_financial_data' && isset($_GET['medid']) && isset($_GET['type']) && isset($_GET['period'])){
	
	if($_GET['type'] == 'outbound'){
		$chart->grab_financial_data_ajax($_GET['medid'], 'outbound', $_GET['period']);
	}else{
		$chart->grab_financial_data_ajax($_GET['medid'], 'inbound', $_GET['period']);
	}
}

if(isset($_GET['ajax']) && $_GET['ajax'] == 'grabTotals' && isset($_GET['medid']) && isset($_GET['type']) && isset($_GET['period'])){
	
	if($_GET['type'] == 'outbound'){
		$chart->calc_new_totals($_GET['medid'], 'outbound', $_GET['period']);
	}else{
		$chart->calc_new_totals($_GET['medid'], 'inbound', $_GET['period']);
	}
}

?>
