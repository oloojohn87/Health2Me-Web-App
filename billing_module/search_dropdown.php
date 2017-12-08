<?php
session_start();
require("../environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

//PREFIX FOR INSURANCE CARRIER'S PAYMENT RESPONSIBLE ORDER
$orderList = array('Primary', 'Secondary', 'Tertiary', 'Quarternary');
if (!$con) die('Could not connect: ' . mysql_error());

if(isset($_POST['page'])) {
    $page = $_POST['page'];
    $search_result = array();
    if ($page == 'personal_carrier') {
        $order_prefix = $orderList[intval($_POST['order'])];
        $searchsql = "SELECT ICID, ICname, address1, address2, city, state, zip FROM billing_insurCarriers where ICID LIKE ? OR ICname LIKE ?";
        $query = '%'.htmlentities($_POST['query']).'%';   
        $search_stmt = $con->prepare($searchsql);
        $search_stmt->bindValue(1, $query);
        $search_stmt->bindValue(2, $query);
        $search_stmt->execute();       
        while ($row = $search_stmt->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            $item->page = $page;
            $item->id = $row['ICID'];
            $item->name = $row['ICname'];
            $item->extension = $row['address1'].' '.$row['address2'].'<br>'.$row['city'].', '.$row['state'].', '.$row['zip'];
            $item->order = $order_prefix;
            array_push($search_result, $item);
        }
    }
    elseif ($page == 'location') {
        $searchsql = "SELECT LCID, name FROM billing_locationInfo where LCID LIKE ? OR name LIKE ?";
        $query = '%'.htmlentities($_POST['query']).'%';   
        $search_stmt = $con->prepare($searchsql);
        $search_stmt->bindValue(1, $query);
        $search_stmt->bindValue(2, $query);
        $search_stmt->execute();
        while ($row = $search_stmt->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            $item->page = $page;
            $item->id = $row['LCID'];
            $item->name = $row['name'];
            array_push($search_result, $item);
        }
    }
    elseif ($page == 'provider') {
        $searchsql = "SELECT PVID, fname, mname, lname FROM billing_providerInfo WHERE PVID LIKE ? OR fname LIKE ? OR mname LIKE ? OR lname LIKE ?";
        $query = '%'.htmlentities($_POST['query']).'%';   
        $search_stmt = $con->prepare($searchsql);
        $search_stmt->bindValue(1, $query);
        $search_stmt->bindValue(2, $query, PDO::PARAM_STR);
        $search_stmt->bindValue(3, $query, PDO::PARAM_STR);
        $search_stmt->bindValue(4, $query, PDO::PARAM_STR);
        $search_stmt->execute();
        while ($row = $search_stmt->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            $item->page = $page;
            $item->id = $row['PVID'];
            $item->name = $row['fname'].' '.$row['mname'].' '.$row['lname'];
            array_push($search_result, $item);
        }
    }
    elseif ($page == 'diagnosis') {
        $searchsql = "SELECT DGID, descr, AltProcCode1, AltProcCode2, AgeSpec, SexSpec FROM billing_diagnoses WHERE DGID LIKE ? OR descr LIKE ?";
        $query = '%'.htmlentities($_POST['query']).'%';   
        $search_stmt = $con->prepare($searchsql);
        $search_stmt->bindValue(1, $query);
        $search_stmt->bindValue(2, $query);
        $search_stmt->execute();
        while ($row = $search_stmt->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            $item->page = $page;
            $item->id = $row['DGID'];
            $item->name = $row['descr'];
            $item->extension = 'Alt: '.$row['AltProcCode1'].' '.$row['AltProcCode2'].', '.$row['AgeSpec'].' '.$row['SexSpec'];
            array_push($search_result, $item);
        }
    }
    elseif ($page == 'procedure') {
        $searchsql = "SELECT PCID, descr, StdChrgAmt FROM billing_procedure_codes WHERE PCID LIKE ? OR descr LIKE ?";
        $query = '%'.htmlentities($_POST['query']).'%';   
        $search_stmt = $con->prepare($searchsql);
        $search_stmt->bindValue(1, $query);
        $search_stmt->bindValue(2, $query);
        $search_stmt->execute();
        while ($row = $search_stmt->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            $item->page = $page;
            $item->id = $row['PCID'];
            $item->name = $row['descr'];
            $item->extension = $row['StdChrgAmt'];
            array_push($search_result, $item);
        }
    }
    elseif ($page == 'modifier') {
        $searchsql = "SELECT MCode, descr FROM billing_modifiers where MCode LIKE ? OR descr LIKE ?";
        $query = '%'.htmlentities($_POST['query']).'%';   
        $search_stmt = $con->prepare($searchsql);
        $search_stmt->bindValue(1, $query);
        $search_stmt->bindValue(2, $query);
        $search_stmt->execute();
        while ($row = $search_stmt->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            $item->page = $page;
            $item->id = $row['MCode'];
            $item->name = $row['descr'];
            array_push($search_result, $item);
        }
    }

    $json = json_encode($search_result);
    echo $json;
}
?>