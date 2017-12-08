<?php
//Author: Kyle Austin
//Date: 05/04/15
//Purpose: To create class extension from userconstructclass
include("../../../master_classes/userConstructClass.php");


class mainDashboardClass extends userConstructClass{
    
    public $ht_patients;
    public $np_waiting;
    public $np_active;
    public $np_completed;
	
	function __construct(){
		parent::__construct();
        
        $this->ht_patients = array();
        $this->np_waiting = 0;
        $this->np_active = 0;
        $this->np_completed = 0;
		
		if ($_SESSION['CustomLook']=='COL') {
			header('Location:MainDashboardHTI.php'); 
			echo "<html></html>";  // - Tell the browser there the page is done
			flush();               // - Make sure all buffers are flushed
			ob_flush();            // - Make sure all buffers are flushed
			exit;                  // - Prevent any more output from messing up the redirect
		}   
		
		if(!isset($_SESSION['MEDID'])){
			$url = 'index.html';
			header('Location: $url'); 
		}
	}
    
    public function getHTPatients()
    {
        $this->ht_patients = array();
        
        $today = date('Y-m-d').' 00:00:00';
        
        $pats = $this->con->prepare("SELECT U.Identif AS PatientId, R.date AS Date, R.provider_id AS NP, CONCAT(U.name, ' ', U.surname) AS PatientName, CONCAT(D.name, ' ', D.surname) AS NPName FROM usuarios U LEFT JOIN (SELECT patient_id, provider_id, date FROM reservation WHERE (date IS NULL OR date > ?)) R ON R.patient_id = U.Identif LEFT JOIN doctors D ON D.id = R.provider_id WHERE U.IdCreator = ? AND U.signUpDate >= ? ORDER BY U.signUpDate");
        $pats->bindValue(1, $today, PDO::PARAM_STR);
        $pats->bindValue(2, $this->med_id, PDO::PARAM_INT);
        $pats->bindValue(3, $today, PDO::PARAM_STR);
        $pats->execute();
        
        while($row = $pats->fetch(PDO::FETCH_ASSOC))
        {
            array_push($this->ht_patients, $row);
        }
    }
    
    public function getNPPatientQueueStatus()
    {
        
        $today = date('Y-m-d');
        
        $status = $this->con->prepare("SELECT status, COUNT(*) AS count FROM reservation WHERE provider_id = ? AND date BETWEEN ? AND ? GROUP BY status");
        $status->bindValue(1, $this->med_id, PDO::PARAM_INT);
        $status->bindValue(2, $today.' 00:00:00', PDO::PARAM_STR);
        $status->bindValue(3, $today.' 23:59:59', PDO::PARAM_STR);
        $status->execute();
        
        while($row = $status->fetch(PDO::FETCH_ASSOC))
        {
            if($row['status'] == 'ACTIVE')
                $this->np_active = intval($row['count']);
            else if($row['status'] == 'WAITING')
                $this->np_waiting = intval($row['count']);
            else if($row['status'] == 'COMPLETED')
                $this->np_completed = intval($row['count']);
        }
    }
}
?>
