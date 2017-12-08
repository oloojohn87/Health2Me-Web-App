<?php

session_start();
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', 
               array(PDO::ATTR_EMULATE_PREPARES => false, 
                     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
              );

if (!$con) die('Could not connect: ' . mysql_error());

//THIS MODIFIES AJAX QUERY BASED ON $_GET DATA.....
if(isset($_GET['showroom'])){

    //PULLS GET DATA.........................
    $id = $_GET['id'];
    $date = $_GET['date'];
    $time = $_GET['time'];

    $query2 = $con->prepare("SELECT * FROM schedule_appointment WHERE room_id=? && time = ? && date = ?");
    $query2->bindValue(1, $id, PDO::PARAM_INT);
    $query2->bindValue(2, $time, PDO::PARAM_STR);
    $query2->bindValue(3, $date, PDO::PARAM_STR);
    $result = $query2->execute();

    $row2 = $query2->fetch(PDO::FETCH_ASSOC);

    $query3 = $con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
    $query3->bindValue(1, $row2['member_id'], PDO::PARAM_INT);
    $result = $query3->execute();

    $row3 = $query3->fetch(PDO::FETCH_ASSOC);

    //PULLS PATIENT IMAGE AND ATTACHES TO ROOM.......
    $fileName = "PatientImage/".$row2['member_id'].".jpg";
                 
    if(file_exists($fileName))
    {
        $file = "PatientImage/".$row2['member_id'].".jpg";
        $style = "max-height: 50px; max-width:50px;float:right;margin-right:5%;margin-top:4.5%;";
    }else{
        $file = "/PatientImage/defaultDP.jpg";
        $style = "max-height: 50px; max-width:50px;float:right;margin-right:5%;margin-top:4.5%;";
    }

    echo "<table class='table table-bordered'><tr>";

    //THIS ENCODES THE NAME AND SURNAME TO ACCOUNT FOR SPECIAL CHARACTERS......
    $current_encoding = mb_detect_encoding($row3['Name'], 'auto');
    $show_text = iconv($current_encoding, 'ISO-8859-1', $row3['Name']);

    $current_encoding = mb_detect_encoding($row3['Surname'], 'auto');
    $show_text2 = iconv($current_encoding, 'ISO-8859-1', $row3['Surname']); 

    if($row3['email'] == ''){
        $email_holder = ' Email Unknown';
    }else{
        $email_holder = $row3['email'];
    }

    if($row3['telefono'] == ''){
        $tel_holder = ' Phone Unknown';
    }else{
        $tel_holder = $row3['telefono'];
    }

    if($row2['status'] == 0){
        $status = 'Scheduled To Arrive';
    }elseif($row2['status'] == 1){
        $status = 'Appointment Canceled';
    }elseif($row2['status'] == 2){
        $status = 'Missed Appointment';
    }

    echo "<td style='height:150px;width:130px;'><span style='height:130px;width:130px;'></span><img src='".$file."' style='".$style."'>             <div style='margin-left:1%;'>
            <font size='1'>Member ID : ".$row2['member_id']."<p>
                Name : <b>".$show_text." ".$show_text2."</b><p>Phone : ".$tel_holder."<p>Email : ".$email_holder."<p>Status : ".$status."             </font>
          </div>
          </td></tr></table>";

}else{

    $id = $_GET['id'];
    $date = $_GET['date'];
    $time = $_GET['time'];

    $query = $con->prepare("SELECT * FROM schedule_rooms WHERE facility_id=?");
    $query->bindValue(1, $id, PDO::PARAM_INT);
    $result = $query->execute();

    echo "<table class='table table-bordered'>
            <tr><div style='float:left;' onclick='showTimeSlots();'>Timeslot:</div>
                <div style='float:left;' id='dateslot_display' onclick='showTimeSlots();' />
                <div style='float:left;' id='timeslot_display' onclick='showTimeSlots();' />
                <center>Rooms</center>
            </tr>";

    $count = $query->rowCount();
    $extra_boxes = 0;
    $holder = 0;
    if($count == 0){
        echo "<tr><td><center>There are currently no rooms added to this facility.</center></td></tr>";
    }else{
        while($row = $query->fetch(PDO::FETCH_ASSOC)){

            $query2 = $con->prepare("SELECT * FROM schedule_appointment WHERE room_id=? && time = ? && date = ?");
            $query2->bindValue(1, $row['id'], PDO::PARAM_INT);
            $query2->bindValue(2, $time, PDO::PARAM_STR);
            $query2->bindValue(3, $date, PDO::PARAM_STR);
            $result = $query2->execute();

            $row2 = $query2->fetch(PDO::FETCH_ASSOC);

            $query3 = $con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
            $query3->bindValue(1, $row2['member_id'], PDO::PARAM_INT);
            $result = $query3->execute();

            $row3 = $query3->fetch(PDO::FETCH_ASSOC);

            $fileName = "PatientImage/".$row2['member_id'].".jpg";
            if(file_exists($fileName))
            {
                $file = "PatientImage/".$row2['member_id'].".jpg";
                $style = "max-height: 50px; max-width:50px;float:right;margin-right:19%;margin-top:4.5%;";
            }else{
                $file = "/PatientImage/defaultDP.jpg";
                $style = "max-height: 50px; max-width:50px;float:right;margin-right:19%;margin-top:4.5%;";
            }

            if($row2['status'] == 0){
                $status = 'Scheduled To Arrive';
            }elseif($row2['status'] == 1){
                $status = 'Appointment Canceled';
            }elseif($row2['status'] == 2){
                $status = 'Missed Appointment';
            }

            if(($holder % 3) == 0) echo "<tr>";
            
            if($row2['member_id'] == '')
            {
                echo "<td onclick='createRoomDetails(".$row['id'].");' style='height:260px;width:260px;background-image:url(images/icons/room.png);background-repeat:no-repeat;'>
                <span style='height:260px;width:260px;'>".$row['id']." - ".$row['name']."</span>
                <div style='margin-left:7%;'><center>Room is available.</center></div>
                </td>";
                if($count == 1 && $extra_boxes == 0){
                    $extra_boxes = 1;
                    echo "<td style='height:260px;width:260px;'></td><td style='height:260px;width:260px;'></td>";
                }elseif($count == 2 && $extra_boxes == 0 && $holder >= 1){
                    $extra_boxes = 1;
                    echo "<td style='height:260px;width:260px;'></td>";
                }
            }else{

                $current_encoding = mb_detect_encoding($row3['Name'], 'auto');
                $show_text = iconv($current_encoding, 'ISO-8859-1', $row3['Name']);

                $current_encoding = mb_detect_encoding($row3['Surname'], 'auto');
                $show_text2 = iconv($current_encoding, 'ISO-8859-1', $row3['Surname']); 

                if($row3['email'] == ''){
                    $email_holder = ' Email Unknown';
                }else{
                    $email_holder = $row3['email'];
                }

                if($row3['telefono'] == ''){
                    $tel_holder = ' Phone Unknown';
                }else{
                    $tel_holder = $row3['telefono'];
                }

                echo "<td onclick='changeAppointment(".$row['id'].", ".$row2['member_id'].");' style='height:260px;width:260px;background-image:url(images/icons/room.png);background-repeat:no-repeat;'>
                <span style='height:260px;width:260px;'>".$row['id']." - ".$row['name']."</span>
                <img src='".$file."' style='".$style."'>
                <div style='margin-left:7%;'>Member ID : ".$row2['member_id']."<p>
                    Name : <b>".$show_text." ".$show_text2."</b><p>Phone : ".$tel_holder."<p>Email : ".$email_holder."<p>Status : ".$status."
                </div>
                </td>";
                
                if($count == 1 && $extra_boxes == 0){
                    $extra_boxes = 1;
                    echo "<td style='height:260px;width:260px;'></td><td style='height:260px;width:260px;'></td>";
                }elseif($count == 2 && $extra_boxes == 0 && $holder >= 1){
                    $extra_boxes = 1;
                    echo "<td style='height:260px;width:260px;'></td>";
                }
            }
            if(($holder % 3) == 2) echo "</tr>";
            $holder++;
        }
    }
    echo "</table>";
}
?>