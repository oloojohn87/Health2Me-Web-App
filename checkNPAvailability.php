<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$user = $_POST['id'];
$date = $_POST['date'];
$time = $_POST['time'];	

$new_time = strtotime($time);

$time_obj = new DateTime("@$new_time");
$military_time = $time_obj->format('H:i:s');

$previous_sunday = date('Y-m-d', strtotime($date.'last sunday'));

$first_date = strtotime($previous_sunday);
$second_date = strtotime($date);
$date_diff = $second_date - $first_date;
$day_of_week = floor($date_diff/(60*60*24));

$end_plus_fifteen = strtotime("+15 minutes",strtotime($military_time));

$time_obj = new DateTime("@$end_plus_fifteen");
$end_time_calced = $time_obj->format('H:i:s');

$result2 = $con->prepare("SELECT 
	d.id as doc_id, 
	d.grant_access as grant_access, 
	d.Name as doc_name,
	d.Surname as doc_surname,
	t.id as slot_id, 
	t.doc_id as slot_doc_id, 
	t.week as week, 
	t.start_time as start_time, 
	t.end_time as end_time, 
	t.week_day as week_day, 
	t.timezone as timezone 
	FROM doctors d 
	RIGHT JOIN timeslots t 
	ON d.id = t.doc_id 
	WHERE grant_access = 'CATA'
	&& week = ?
	&& ((CAST(? AS time) BETWEEN `start_time` AND `end_time`) OR start_time = ? OR end_time = ?) 
	&& (start_time != ? && end_time != ?)
	&& scheduled_member is null
	&& week_day = ? 
	LIMIT 1"); 
$result2->bindValue(1, $previous_sunday, PDO::PARAM_STR);
$result2->bindValue(2, $military_time, PDO::PARAM_STR);
$result2->bindValue(3, $military_time, PDO::PARAM_STR);
$result2->bindValue(4, $end_time_calced, PDO::PARAM_STR);
$result2->bindValue(5, $end_time_calced, PDO::PARAM_STR);
$result2->bindValue(6, $military_time, PDO::PARAM_STR);
$result2->bindValue(7, $day_of_week, PDO::PARAM_INT);
$result2->execute();  

$count = $result2->rowCount();

if($count == 0){
	$result3 = $con->prepare("SELECT 
		d.id as doc_id, 
		d.grant_access as grant_access, 
		d.Name as doc_name,
		d.Surname as doc_surname,
		t.id as slot_id, 
		t.doc_id as slot_doc_id, 
		t.week as week, 
		t.start_time as start_time, 
		t.end_time as end_time, 
		t.week_day as week_day, 
		t.timezone as timezone 
		FROM doctors d 
		RIGHT JOIN timeslots t 
		ON d.id = t.doc_id 
		WHERE grant_access = 'CATA'
		&& week = ?
		&& scheduled_member is null
		&& week_day = ? 
		LIMIT 5"); 
	$result3->bindValue(1, $previous_sunday, PDO::PARAM_STR);
	$result3->bindValue(2, $day_of_week, PDO::PARAM_INT);
	$result3->execute(); 
	$count2 = $result3->rowCount();
}

$cadena = '
	    {
	        "DocID":0
	    }';

if($count == 0 && $count2 > 0){
	while($row3 = $result3->fetch(PDO::FETCH_ASSOC)){
		$cadena .= ',{"Start":"'.$row3['start_time'].'", "End":"'.$row3['end_time'].'"}';
	}
}

while($row = $result2->fetch(PDO::FETCH_ASSOC)){

	$end_minus_fifteen = strtotime("-15 minutes",strtotime($row['end_time']));

	$time_obj_minus = new DateTime("@$end_minus_fifteen");
	$end_time_sub = $time_obj_minus->format('H:i:s');

	if($row['start_time'] == $military_time && $row['end_time'] == $end_time_calced){
		$update = $con->prepare("UPDATE timeslots SET scheduled_member = ? WHERE id = ?");
		$update->bindValue(1, $user, PDO::PARAM_INT);
		$update->bindValue(2, $row['slot_id'], PDO::PARAM_INT);
		$update->execute(); 

	}elseif($row['start_time'] == $military_time && $row['end_time'] != $end_time_calced){
		$delete = $con->prepare("DELETE FROM timeslots WHERE id = ?");
		$delete->bindValue(1, $row['slot_id'], PDO::PARAM_INT);
		$delete->execute();

		$insert = $con->prepare("INSERT INTO timeslots SET scheduled_member = ?, start_time = ?, end_time = ?, week = ?, week_day = ?, timezone = ?, doc_id = ?");
		$insert->bindValue(1, $user, PDO::PARAM_INT);
		$insert->bindValue(2, $military_time, PDO::PARAM_STR);
		$insert->bindValue(3, $end_time_calced, PDO::PARAM_STR);
		$insert->bindValue(4, $row['week'], PDO::PARAM_STR);
		$insert->bindValue(5, $row['week_day'], PDO::PARAM_STR);
		$insert->bindValue(6, $row['timezone'], PDO::PARAM_STR);
		$insert->bindValue(7, $row['slot_doc_id'], PDO::PARAM_STR);
		$insert->execute(); 

		$insert = $con->prepare("INSERT INTO timeslots SET start_time = ?, end_time = ?, week = ?, week_day = ?, timezone = ?, doc_id = ?");
		$insert->bindValue(1, $end_time_calced, PDO::PARAM_STR);
		$insert->bindValue(2, $row['end_time'], PDO::PARAM_STR);
		$insert->bindValue(3, $row['week'], PDO::PARAM_STR);
		$insert->bindValue(4, $row['week_day'], PDO::PARAM_STR);
		$insert->bindValue(5, $row['timezone'], PDO::PARAM_STR);
		$insert->bindValue(6, $row['slot_doc_id'], PDO::PARAM_STR);
		$insert->execute(); 


	}elseif($row['start_time'] != $military_time && $row['end_time'] == $end_time_calced){

		$delete = $con->prepare("DELETE FROM timeslots WHERE id = ?");
		$delete->bindValue(1, $row['slot_id'], PDO::PARAM_INT);
		$delete->execute();

		$insert = $con->prepare("INSERT INTO timeslots SET scheduled_member = ?, start_time = ?, end_time = ?, week = ?, week_day = ?, timezone = ?, doc_id = ?");
		$insert->bindValue(1, $user, PDO::PARAM_INT);
		$insert->bindValue(2, $military_time, PDO::PARAM_STR);
		$insert->bindValue(3, $end_time_calced, PDO::PARAM_STR);
		$insert->bindValue(4, $row['week'], PDO::PARAM_STR);
		$insert->bindValue(5, $row['week_day'], PDO::PARAM_STR);
		$insert->bindValue(6, $row['timezone'], PDO::PARAM_STR);
		$insert->bindValue(7, $row['slot_doc_id'], PDO::PARAM_STR);
		$insert->execute(); 

		$insert = $con->prepare("INSERT INTO timeslots SET start_time = ?, end_time = ?, week = ?, week_day = ?, timezone = ?, doc_id = ?");
		$insert->bindValue(1, $row['start_time'], PDO::PARAM_STR);
		$insert->bindValue(2, $end_time_sub, PDO::PARAM_STR);
		$insert->bindValue(3, $row['week'], PDO::PARAM_STR);
		$insert->bindValue(4, $row['week_day'], PDO::PARAM_STR);
		$insert->bindValue(5, $row['timezone'], PDO::PARAM_STR);
		$insert->bindValue(6, $row['slot_doc_id'], PDO::PARAM_STR);
		$insert->execute(); 

	}else{
		$delete = $con->prepare("DELETE FROM timeslots WHERE id = ?");
		$delete->bindValue(1, $row['slot_id'], PDO::PARAM_INT);
		$delete->execute(); 

		$insert = $con->prepare("INSERT INTO timeslots SET scheduled_member = ?, start_time = ?, end_time = ?, week = ?, week_day = ?, timezone = ?, doc_id = ?");
		$insert->bindValue(1, $user, PDO::PARAM_INT);
		$insert->bindValue(2, $military_time, PDO::PARAM_STR);
		$insert->bindValue(3, $end_time_calced, PDO::PARAM_STR);
		$insert->bindValue(4, $row['week'], PDO::PARAM_STR);
		$insert->bindValue(5, $row['week_day'], PDO::PARAM_STR);
		$insert->bindValue(6, $row['timezone'], PDO::PARAM_STR);
		$insert->bindValue(7, $row['slot_doc_id'], PDO::PARAM_STR);
		$insert->execute();

		$insert = $con->prepare("INSERT INTO timeslots SET start_time = ?, end_time = ?, week = ?, week_day = ?, timezone = ?, doc_id = ?");
		$insert->bindValue(1, $row['start_time'], PDO::PARAM_STR);
		$insert->bindValue(2, $military_time, PDO::PARAM_STR);
		$insert->bindValue(3, $row['week'], PDO::PARAM_STR);
		$insert->bindValue(4, $row['week_day'], PDO::PARAM_STR);
		$insert->bindValue(5, $row['timezone'], PDO::PARAM_STR);
		$insert->bindValue(6, $row['slot_doc_id'], PDO::PARAM_STR);
		$insert->execute(); 

		$insert = $con->prepare("INSERT INTO timeslots SET start_time = ?, end_time = ?, week = ?, week_day = ?, timezone = ?, doc_id = ?");
		$insert->bindValue(1, $end_time_calced, PDO::PARAM_STR);
		$insert->bindValue(2, $row['end_time'], PDO::PARAM_STR);
		$insert->bindValue(3, $row['week'], PDO::PARAM_STR);
		$insert->bindValue(4, $row['week_day'], PDO::PARAM_STR);
		$insert->bindValue(5, $row['timezone'], PDO::PARAM_STR);
		$insert->bindValue(6, $row['slot_doc_id'], PDO::PARAM_STR);
		$insert->execute(); 

	}



	$cadena='
	    {
	        "DocID":"'.$row['slot_doc_id'].'", "DocSurname":"'.$row['doc_surname'].'", "DocName":"'.$row['doc_name'].'", "Start":"'.$military_time.'", "Week":"'.$row['week'].'", "End":"'.$end_time_calced.'", "Week_Day":"'.$row['week_day'].'", "Date_Appt":"'.$date.'", "Display_Time":"'.$time.'"
	    }';

}
    
$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 

?>