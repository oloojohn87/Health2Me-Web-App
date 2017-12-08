<?php

require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}	
$type = $_POST['type'];
$providerID = $_POST['PID'];
$date = $_POST['date'].'%';

/*if(isset($_POST['patientID'])) {
    $patientID = $_POST['patientID'];
    
    $sel = $con->prepare("SELECT U.Name, U.Surname, C.Length, C.Status, C.lastActive
    
                        FROM 
                        (
                            SELECT * FROM consults WHERE Patient = ? AND Doctor = ? AND Type = 'video'
                        ) C
                        LEFT JOIN usuarios U ON U.Identif = C.Patient
                        GROUP BY C.consultationId
                        ORDER BY C.lastActive");
    
    $sel->bindValue(1, $patientID, PDO::PARAM_INT);
    $sel->bindValue(2, $providerID, PDO::PARAM_INT);
    $sel->execute();
    $sel_row = $sel->fetch(PDO::FETCH_ASSOC);
    //unset($_GET['patientID']);
    
    $Name = ucfirst(substr($sel_row['Name'],0,1)).'. '.ucfirst($sel_row['Surname']);
    $Status = $sel_row['Status'];
    $Time = $sel_row['lastActive'];
    $LengthinMin = strval(floor($sel_row['Length']/60)).' min';
    
    echo '<div class="tooltipContent">
            <div class="tooltipName">
            patient '+$Name+
            '</div>
            <div class="tooltipStatus">
            '+$Status+'
            </div>
            <div class="tooltipDate">
            '+$Time+'
            </div>
            <div class="tooltipLength">
            time: '+$LengthinMin+' min.
            </div>        
    </div>';
    
}
else {*/
    $sql = "SELECT R.id,
            R.status,
            U.Identif,
            U.Name,
            U.Surname,
            /* BELOW IS THE TIMEZONE-AWARE TIME PART */
            /*SUBSTRING_INDEX(SUBSTRING_INDEX(ADDTIME(R.date, U.timezone), ' ', 1), ' ', -1) AS updated_date,
            TRIM(SUBSTR(ADDTIME(R.date, U.timezone), LOCATE(' ', ADDTIME(R.date, U.timezone)))) AS updated_time */

            SUBSTRING_INDEX(SUBSTRING_INDEX(R.date, ' ', 1), ' ', -1) AS updated_date,
            TRIM(SUBSTR(R.date, LOCATE(' ', R.date))) AS updated_time                           
        FROM 
            (
                SELECT * FROM reservation WHERE provider_id = ? AND date LIKE ?";
        if(!empty($type) && $type == 'waiting') $sql .= " AND status = 'WAITING'";

        $sql .= " ORDER BY date DESC
            ) R
        LEFT JOIN
            usuarios U ON U.Identif = R.patient_id
        ORDER BY R.date";


    $sel = $con->prepare($sql);

    $sel->bindValue(1, $providerID, PDO::PARAM_INT);
    $sel->bindValue(2, $date, PDO::PARAM_STR);
    $sel->execute();
    $result = array();
    while($sel_row = $sel->fetch(PDO::FETCH_ASSOC))
    {
        array_push($result, $sel_row);
    }

    echo json_encode($result);
//}
?>