<?php
if(isset($_POST['idDoc'])) {
// Connect to server and select databse.

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
																									
	$IdDoc = htmlentities($_POST['idDoc']);

	/*$result = $con->prepare("SELECT IdGroup FROM doctorsgroups WHERE IdDoctor = ?");
	$result->bindValue(1, $IdDoc, PDO::PARAM_INT);
	$result->execute();

	$row = $result->fetch(PDO::FETCH_ASSOC);
	$MyGroup = $row['IdGroup'];*/

	$TotMsg = 0;
	$TotUpDr = 0;
	$TotUpUs = 0;
    
    $sql = "SELECT idus.Identif, U_COUNT.count AS user_count, MES_COUNT.count AS mes_count, REP_COUNT.count AS rep_count, SUM_COUNT.count AS sum_count, CONS_COUNT.count AS cons_count, PROBE.probe_on, PROBE.probe_recent, CONSUL_COUNT.count AS consul_count from 
    
    /* Select all patients that this doctor has access to */
    (
	   SELECT Identif FROM usuarios WHERE IdCreator = ?
       
	       UNION
           
	   SELECT IdUs as Identif FROM doctorslinkusers WHERE IdMED = ?
       
	       UNION
           
	   SELECT IdPac as Identif FROM doctorslinkdoctors WHERE IdMED2 = ?
	) idus
    
        LEFT JOIN 
        
    /* Add to the result set whether each patient in the result set has a password or not */ 
    (SELECT Identif,COUNT(*) AS count FROM usuarios WHERE IdUsRESERV IS NOT NULL AND salt IS NOT NULL GROUP BY Identif) AS U_COUNT ON U_COUNT.Identif = idus.Identif
        
        LEFT JOIN
        
    /* Add to the result set the number of messages for each of the patients in the result set */    
    (SELECT m.patient_id, COUNT(*) AS count FROM message_infrastructureuser m WHERE m.sender_id = ? AND m.tofrom='to' AND m.sender_id IS NOT NULL AND m.status = 'new' AND (m.fecha BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) GROUP BY m.patient_id) AS MES_COUNT ON MES_COUNT.patient_id = idus.Identif
        
        LEFT JOIN
    
    /* Add to the result set the number of reports for each of the patients in the result set */
    (SELECT l.IdUsu,COUNT(*) AS count FROM lifepin l WHERE (l.FechaInput BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) GROUP BY l.IdUsu) AS REP_COUNT ON REP_COUNT.IdUsu = idus.Identif
        
        LEFT JOIN
        
    /* Add to the result set the number of summary edits for each of the patients in the result set */    
    (SELECT p.idUsu,COUNT(*) AS count FROM p_log p WHERE  (p.Timestamp BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) GROUP BY p.idUsu) AS SUM_COUNT ON SUM_COUNT.idUsu = idus.Identif
        
        LEFT JOIN
        
    /* Add to the result set the number of appointments for each of the patients in the result set */
    (SELECT a.pat_id,COUNT(*) AS count FROM appointments a WHERE a.med_id = ? AND (a.date_created BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) GROUP BY a.pat_id) AS CONS_COUNT ON CONS_COUNT.pat_id = idus.Identif
    
        LEFT JOIN
        
    /* Add to the result set whether each patient has a probe and if it is recent (within the past 30 days) */
    (SELECT patientID AS pat, BIT_OR(doctorPermission = 1 AND scheduledEndDate >= SYSDATE()) AS probe_on, BIT_OR(doctorPermission = 1 AND scheduledEndDate > SYSDATE() AND (creationDate BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())) AS probe_recent FROM probe WHERE doctorID = ? GROUP BY patientID) AS PROBE ON PROBE.pat = idus.Identif
        
        LEFT JOIN
        
    /* Add to the result set the number of consultations for each of the patients in the result set */
    (SELECT c.Patient, COUNT(consultationId) AS count FROM consults c WHERE c.Doctor = ? AND (c.lastActive BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) GROUP BY c.Patient) AS CONSUL_COUNT ON CONSUL_COUNT.Patient = idus.Identif";
    
	//Get Patients Total Data
	$totalQuery = $con->prepare($sql);
	$totalQuery->bindValue(1, $IdDoc, PDO::PARAM_INT);
	$totalQuery->bindValue(2, $IdDoc, PDO::PARAM_INT);
	$totalQuery->bindValue(3, $IdDoc, PDO::PARAM_INT);
    $totalQuery->bindValue(4, $IdDoc, PDO::PARAM_INT);
    $totalQuery->bindValue(5, $IdDoc, PDO::PARAM_INT);
    $totalQuery->bindValue(6, $IdDoc, PDO::PARAM_INT);
    $totalQuery->bindValue(7, $IdDoc, PDO::PARAM_INT);
	$totalQuery->bindValue(8, $IdDoc, PDO::PARAM_INT);

	$totalQuery->execute();

	$totalResult = $totalQuery->rowCount();
	$count=$totalResult;
	$UTotal =  $count;
	$UConn=0;
    $USum = 0;
    $UConsul = 0;
    $UAppoint = 0;
    $UNewActs = 0;
    $UTracked = 0;
    $UProbe = 0;
    $URecent = 0;
	$vitals_count = 0;

	while($row = $totalQuery->fetch(PDO::FETCH_ASSOC))
	{
        $PatientId = $row['Identif'];
        
        if($row['user_count'] == 1)
            $UConn++;
        
        if($row['mes_count'] > 0)
            $TotMsg++;
        
        if($row['rep_count'] > 0)
            $TotUpUs++;
        
        if($row['sum_count'] > 0)
            $USum++;
        
        if($row['cons_count'] > 0)
            $UAppoint++;
        
        if($row['consul_count'] > 0)
            $UConsul++;
        
        if($row['probe_on'] == 1)
            $UTracked++;
            
        if($row['probe_recent'] == 1)
            $UProbe++;

        if($row['mes_count'] > 0 || $row['rep_count'] > 0 || $row['sum_count'] > 0 || $row['cons_count'] > 0 || $row['consul_count'] > 0 || $row['probe_recent'] == 1) {
            $UNewActs += $row['mes_count'] + $row['rep_count'] + $row['sum_count'] + $row['cons_count'] + $row['consul_count'] + $row['probe_on'];
            $URecent++;
        }
    }
    $array = array('req_doc_mem_cnt' => $UTotal,
            'req_doc_rch_cnt'=> $UConn,
            'req_doc_nRch_cnt'=> $UTotal - $UConn,
            'trkCount'=> $UTracked,
            'repUp'=> $TotUpUs,
            'msgUp'=> $TotMsg,
            'prbUp'=> $UProbe,
            'sumUp'=> $USum,
            'apptUp'=> $UAppoint,
            'newActsUp'=> ($TotUpUs+$UProbe+$USum+$TotMsg+$UAppoint),
            'newPats' => $URecent
                  );
    $json = json_encode($array);
    echo $json;
} else {
    echo 'error: missing Doctor ID.';
}
?>
