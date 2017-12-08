<?php
//Author: Kyle Austin
//Date: 05/04/15
//Purpose: To create class extension from userconstructclass

class configurationClass extends userConstructClass{
	
	public $QueEntrada = 1;
	
	function __construct(){
		parent::__construct();

		$filename = $this->local."DoctorImage/".$user->med_id.".jpg";
			
		if(!file_exists($filename))
		{
			$filename = $this->hardcode."PatientImage/defaultDP.jpg";
		}
		else $filename = $this->hardcode."DoctorImage/".$user->med_id.".jpg";

		$message = "";
		if(isset($_GET['mes']))
		{
			$message = urldecode($_GET['mes']);
		}
		$CustomLook = $_SESSION['CustomLook'];
		$timestamp = time();
	}
}
?>
