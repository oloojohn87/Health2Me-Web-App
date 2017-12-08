<?php
//Author: Kyle Austin
//Date: 05/04/15
//Purpose: To create class extension from userconstructclass
include("../../../master_classes/userConstructClass.php");

class userDashboardClass extends userConstructClass{
	
	public $session_privilege;
	public $current_calling_doctor_name;
	public $rowD;
	public $NameDoctor;
	public $SurnameDoctor;
	public $DoctorEmail;
	public $access = '';
	public $original_access = '';
	public $original_user;
	public $user_accts = array();
	public $qrcodeResults;


	function __construct(){
		parent::__construct();
		
		include "../../../../lib/classes/infographics.php";  
		
		if ($_SESSION['CustomLook']=='COL') {
			header('Location:../../UserDashboardHTI.php');
			echo "<html></html>";  // - Tell the browser there the page is done
			flush();               // - Make sure all buffers are flushed
			ob_flush();            // - Make sure all buffers are flushed
			exit;                  // - Prevent any more output from messing up the redirect
		}  
		
		$this->session_privilege = $_SESSION['Previlege'];
		
		if(isset($this->member_current_calling_doctor))
		{
			$doc_res = $this->con->prepare("SELECT Name,Surname FROM doctors WHERE id=?");
			$doc_res->bindValue(1, $this->member_current_calling_doctor, PDO::PARAM_INT);
			$doc_res->execute();
			
			$doc_row = $doc_res->fetch(PDO::FETCH_ASSOC);
			$this->current_calling_doctor_name = $doc_row['Name'].' '.$doc_row['Surname'];
		}
		
		$resultD = $this->con->prepare("SELECT * FROM doctors where id=?");
		$resultD->bindValue(1, $this->member_id_creator, PDO::PARAM_INT);
		$resultD->execute();
		
		$this->rowD = $resultD->fetch(PDO::FETCH_ASSOC);
		$this->NameDoctor = (htmlspecialchars($rowD['Name']));
		$this->SurnameDoctor = (htmlspecialchars($rowD['Surname']));
		$this->DoctorEmail = (htmlspecialchars($rowD['IdMEDEmail']));
		
		if(isset($_SESSION['Original_User']))
		{
			$this->original_user = $_SESSION['Original_User'];
		}
		if(isset($_SESSION['Original_User_Access']))
		{
			$this->original_access = $_SESSION['Original_User_Access'];
		}
		if(isset($this->member_subs_type) && $this->member_subs_type != null)
		{
			$this->access = $this->member_subs_type;
		}
		if($plan == 'FAMILY' && isset($this->member_owner_account))
		{
			$resultD = $con->prepare("SELECT Name,Surname,Identif,email,relationship,subsType,grant_access,floor(datediff(curdate(),DOB) / 365) as age FROM usuarios U INNER JOIN basicemrdata B where U.ownerAcc = ? AND U.Identif != ? AND U.Identif = B.IdPatient");
			$resultD->bindValue(1, $this->member_owner_account, PDO::PARAM_INT);
			$resultD->bindValue(2, $this->mem_id, PDO::PARAM_INT);
			$resultD->execute();
			while($rowAcct = $resultD->fetch(PDO::FETCH_ASSOC))
			{
				$inf = array("Name" => $rowAcct['Name'].' '.$rowAcct['Surname'], "ID" => $rowAcct['Identif'], "email" => $rowAcct['email'], "age" => $rowAcct['age'], "Relationship" => $rowAcct['relationship'], "access" => $rowAcct['subsType'], "grant_access" => $rowAcct['grant_access']);
				array_push($this->user_accts, $inf);
			}
		}

		$basefilename = $this->mem_id.'.png';
		$qrdata = $domain."/emergency.php?id=".$this->mem_id;
		 
		$qrcode = new H2M_Qrcode();
		$this->qrcodeResults = $qrcode->create($basefilename, $qrdata);
	}
	
	public function generate_calender($dow, $format){
		$today = new DateTime('now'); 
		$today_dow = intval($today->format('N'));
		
		if($today_dow > $dow)
		{
			$date_interval = new DateInterval('P'.strval((7 - $today_dow) + $dow).'D');
			$today->add($date_interval);
		}
		else if($today_dow < $dow)
		{
			$date_interval = new DateInterval('P'.strval($dow - $today_dow).'D');
			$today->add($date_interval);
			
		}
		
		$today = $today->format($format);
		
		return $today;
	}
	
}
?>
