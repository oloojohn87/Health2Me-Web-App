<?php
require("environment_detail.php");
    $dbhost = $env_var_db['dbhost'];
    $dbname = $env_var_db['dbname'];
    $dbuser = $env_var_db['dbuser'];
    $dbpass = $env_var_db['dbpass'];

    $tbl_name="usuarios"; // Table name

		$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
		if (!$con){
			die('Could not connect: ' . mysql_error());
			}		

$IdDoc = 2806;
$queUsu = '%%';
$start = 0;
$numDisplay = 10;
?>
<form action="" method="post">
    <button type="submit" name="filter" value="">ALL</button>
    <button type="submit" name="filter" value="repUp">Reports</button>
    <button type="submit" name="filter" value="sumUp">Summary</button>
    <button type="submit" name="filter" value="msgUp">Messages</button>
    <button type="submit" name="filter" value="prbUp">Probes</button>
    <button type="submit" name="filter" value="apptUp">Appointments</button>
</form>

<?php    
    if($_POST['filter'] == 'repUp') {
        $Var = 'L.FechaInput, ';
        $orderVar = 'L.FechaInput DESC, ';
    }
    else if($_POST['filter'] == 'sumUp') {
        $Var = 'S.Timestamp, ';
        $orderVar = 'S.Timestamp DESC, ';
    }
    else if($_POST['filter'] == 'msgUp') {
        $Var = 'M.fecha, ';
        $orderVar = 'M.fecha DESC, ';
    }
    else if($_POST['filter'] == 'prbUp') {
        $Var = 'P.creationDate, ';
        $orderVar = 'P.creationDate DESC, ';
    }
    else if($_POST['filter'] == 'apptUp') {
        $Var = 'A.date_created, ';
        $orderVar = 'A.date_created DESC, ';
    }
    else {
        $orderVar = '';
        $Var = '';
    }
    $sql = "SELECT ".$Var."U.* FROM 
            usuarios U
                INNER JOIN 
            (SELECT DISTINCT IdUs AS id FROM doctorslinkusers WHERE IdMED = :idDocforDLU
            UNION
            SELECT DISTINCT IdPac AS id FROM doctorslinkdoctors WHERE IdMED2 = :idDocforDLD) DL
            ON U.Identif = DL.id";
    
    //FILTER KICK-IN
    if(isset($_POST['filter'])){
        if($_POST['filter'] == 'repUp') $sql .= " INNER JOIN
        	(SELECT DISTINCT IdUsu, FechaInput FROM lifepin WHERE IdMed = :idDocforRepUp AND (FechaInput BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) GROUP BY IdUsu) L ON DL.id = L.IdUsu";
        
        if($_POST['filter'] == 'sumUp') $sql .= " INNER JOIN
        (SELECT DISTINCT IdUsu, Timestamp FROM p_log WHERE (Timestamp BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) GROUP BY IdUsu) S ON DL.id = S.IdUsu";
        
        if($_POST['filter'] == 'msgUp') $sql .= " INNER JOIN
        (SELECT DISTINCT patient_id, fecha FROM message_infrastructureuser WHERE sender_id = :idDocforMsgUp AND status='new' AND (fecha BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) GROUP BY patient_id) M ON DL.id = M.patient_id";  
        
        if($_POST['filter'] == 'prbUp') $sql .= " INNER JOIN
        (SELECT DISTINCT patientID, creationDate FROM probe WHERE doctorPermission = 1 AND patientPermission = 1 AND scheduledEndDate IS NULL AND (creationDate BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) AND doctorID = :idDocforPrbUp GROUP BY patientID) P ON DL.id = P.patientID";  
        
        if($_POST['filter'] == 'apptUp') $sql .= " INNER JOIN
        (SELECT DISTINCT pat_id, date_created FROM appointments WHERE med_id = :idDocforApptUp AND (date_created BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) GROUP BY pat_id) A ON DL.id = A.pat_id";    
    }

    $sql .= " where U.Surname like :qSurName or U.Name like :qName or U.IdUsFIXEDNAME like :qFixName ORDER BY ".$orderVar."U.Surname ASC LIMIT :limit, ".$numDisplay;
    
    $result = $con->prepare($sql);

    
    
    $result->bindValue(':idDocforDLU', $IdDoc, PDO::PARAM_INT);
    $result->bindValue(':idDocforDLD', $IdDoc, PDO::PARAM_INT);
    //FILTER BINDVALUES
    if($_POST['filter'] == 'repUp') {
        $result->bindValue(':idDocforRepUp', $IdDoc, PDO::PARAM_INT);
    }
    else if($_POST['filter'] == 'msgUp') {
        $result->bindValue(':idDocforMsgUp', $IdDoc, PDO::PARAM_INT);
    }
    else if($_POST['filter'] == 'prbUp') {
        $result->bindValue(':idDocforPrbUp', $IdDoc, PDO::PARAM_INT);
    }
    else if($_POST['filter'] == 'apptUp') {
        $result->bindValue(':idDocforApptUp', $IdDoc, PDO::PARAM_INT);
    }
    $result->bindValue(':qSurName', $queUsu, PDO::PARAM_STR);
    $result->bindValue(':qName', $queUsu, PDO::PARAM_STR);
    $result->bindValue(':qFixName', $queUsu, PDO::PARAM_STR); 
    $result->bindValue(':limit', $start, PDO::PARAM_INT);
    

$result->execute();
unset($_POST['filter']);
$count = $result->rowCount();

while($view = $result->fetch(PDO::FETCH_ASSOC)) {
    echo('id: '.$view['Identif']);
         //.', Name: '.$view['Name'].', Surname: '.$view['Surname'].', fecha: '.$view['FechaInput']);
    echo '<br>';
}
echo 'input filter: ';
echo $_POST['filter'];
echo '<br>';
echo 'counts: ';
echo $count;
?>