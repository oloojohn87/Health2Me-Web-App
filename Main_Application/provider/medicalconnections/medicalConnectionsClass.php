<?php
//Author: Kyle Austin
//Date: 05/04/15
//Purpose: To create class extension from userconstructclass
include("../../../master_classes/userConstructClass.php");

class medicalConnectionsClass extends userConstructClass{
	
	public $NombreEnt = 'x';
	public $PasswordEnt = 'x';
	public $Acceso;
	public $MEDID;
	public $privilege;
	
	function __construct(){
		parent::__construct();
		
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
			// last request was more than 30 minutes ago
			session_unset();     // unset $_SESSION variable for the run-time 
			session_destroy();   // destroy session data in storage
			echo "<META http-equiv='refresh' content='0;URL=index.html'>";
		}
		
		$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

		$this->NombreEnt = $_SESSION['Nombre'];
		$this->PasswordEnt = $_SESSION['Password'];
		$this->Acceso = $_SESSION['Acceso'];
		$this->MEDID = $_SESSION['MEDID'];
		$this->privilege=$_SESSION['Previlege'];
		
        if ($this->Acceso != '23432') {
			//If wrong access exit...
			$this->exit_onfail(1);
        }
		
		$result = $this->con->prepare("SELECT * FROM doctors where id=?");
		$result->bindValue(1, $this->MEDID, PDO::PARAM_INT);
		$result->execute();

		$count = $result->rowCount();
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$success ='NO';
		if($count==1){
			$current_encoding = mb_detect_encoding($row['Name'], 'auto');
			$show_text = iconv($current_encoding, 'ISO-8859-1', $row['Name']);

			$current_encoding = mb_detect_encoding($row['Surname'], 'auto');
			$show_text2 = iconv($current_encoding, 'ISO-8859-1', $row['Surname']); 

			$success ='SI';
			$MedID = $row['id'];
			$MedUserEmail= (htmlspecialchars($row['IdMEDEmail']));
			$MedUserName = (htmlspecialchars($row['Name']));
			$MedUserSurname = (htmlspecialchars($row['Surname']));
			$MedUserLogo = $row['ImageLogo'];

		}
		else
		{
			$this->exit_onfail(2);
		}
        
        if ($_SESSION['CustomLook']=="COL") { 
            echo '<link href="../../../master_css/styleCol.css" rel="stylesheet">';
        }
		//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
		$result = $this->con->prepare("SELECT * FROM lifepin");
		$result->execute();
	}
}

?>
