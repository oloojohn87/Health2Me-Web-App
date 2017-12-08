<?php
class checkPatientsClass{

public $queUsu = '';
public $group = 1;
public $IdMed;
public $member;
public $status1 = '';
public $status2 = '';
public $status3 = '';
public $status4 = '';
public $rule1;
public $rule2;
public $rule3;
public $rule4;

public function setters($mem_id, $doc_id){
$this->member = $mem_id;
$this->IdMed = $doc_id;
}

public function checker(){
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	
  
  if(isset($_SESSION['UserID'])){
  $myID = $_SESSION['UserID'];
  }else{
  $myID = 0;
  }

	$is_member = 0;
  if($this->member == $myID){
//  $this->status1 = $_SESSION['UserID'];
  $this->status2 = 'true';
  //$this->rule2 = 'You have patient access as of '.date_format(date_create($rowPRE['Fecha']),'M d, Y');
  $is_member = 1;
	}else{
//	$this->status1 = $_SESSION['UserID'];
//  $this->status2 = 'true';
  }

 

$queUsu = $this->queUsu;
$Group = $this->group;
$IdMed = $this->IdMed;
$check_mem = $this->member;


	$resultUSU = $con->prepare("select * from usuarios where Identif=? and IdCreator=?");
	$resultUSU->bindValue(1, $check_mem, PDO::PARAM_INT);
	$resultUSU->bindValue(2, $IdMed, PDO::PARAM_INT);
	$resultUSU->execute();
	
	$rowUSU = $resultUSU->fetch(PDO::FETCH_ASSOC);
	

		if($rowUSU['Identif'] == $check_mem){
	    $this->status1 = 'true';
	    $this->rule1 = 'Created by you on '.date_format(date_create($rowUSU['signUpDate']),'M d, Y'); //->format('Y-m-d');
		}else{
		$this->status1 = 'false';
		}
	 
	
  
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  $resultPRE = $con->prepare("select * from doctorslinkusers where IdMed=? and IdUs=?");
	$resultPRE->bindValue(1, $IdMed, PDO::PARAM_INT);
	$resultPRE->bindValue(2, $check_mem, PDO::PARAM_INT);
	$resultPRE->execute();
	
	
	$holder = '';
	while ($rowPRE = $resultPRE->fetch(PDO::FETCH_ASSOC)) {

		if($rowPRE['IdUs'] == $check_mem){
		$this->status2 = 'true';       
		if($is_member == 0){ 
			$this->rule2 = 'Patient granted you access on '.date_format(date_create($rowPRE['Fecha']),'M d, Y');
		}else{
			$this->rule2 = '';
		}
		}else{
		$this->status2 = 'false';
		}
		
		
	 }
	
	
  
  
  
  
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  $resultPRE = $con->prepare("select * from doctorslinkdoctors where IdMED2= ? and IdPac=?");
	$resultPRE->bindValue(1, $IdMed, PDO::PARAM_INT);
	$resultPRE->bindValue(2, $check_mem, PDO::PARAM_INT);
	$resultPRE->execute();
	
	
	$holder = '';
	while ($rowPRE = $resultPRE->fetch(PDO::FETCH_ASSOC)) {

		if($rowPRE['IdPac'] == $check_mem){
		$this->status3 = 'true';
		$this->rule3 = 'Refered to you on '.date_format(date_create($rowPRE['Fecha']),'M d, Y');
		}else{
		$this->status3 = 'false';
		}
		
		
	 }
	
	
  
  
  
  
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
$resultPRE=$con->prepare("select * from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor=?)");
$resultPRE->bindValue(1, $IdMed, PDO::PARAM_INT);
$resultPRE->execute();


while ($rowPRE = $resultPRE->fetch(PDO::FETCH_ASSOC))
	{
			$idEncontrado = $rowPRE['idDoctor'];
			$resultUSU = $con->prepare("select * from doctors where id=?");
			$resultUSU->bindValue(1, $idEncontrado, PDO::PARAM_INT);
			$resultUSU->execute();
            $rowUSU = $resultUSU->fetch(PDO::FETCH_ASSOC);
	        $other_doctor_name = $rowUSU['Name'].' '.$rowUSU['Surname'];			
    
			$idEncontrado = $rowPRE['idDoctor'];
			$resultUSU = $con->prepare("select * from usuarios where Identif=? and IdCreator=?");
			$resultUSU->bindValue(1, $check_mem, PDO::PARAM_INT);
			$resultUSU->bindValue(2, $idEncontrado, PDO::PARAM_INT);
			$resultUSU->execute();
			
			$rowUSU = $resultUSU->fetch(PDO::FETCH_ASSOC);
			
			$resultUSU = $con->prepare("select * from doctorslinkdoctors where IdMED2= ? and IdPac=?");
			$resultUSU->bindValue(1, $idEncontrado, PDO::PARAM_INT);
			$resultUSU->bindValue(2, $check_mem, PDO::PARAM_INT);
			$resultUSU->execute();
			
			$rowUSU2 = $resultUSU->fetch(PDO::FETCH_ASSOC);
			
			$resultUSU = $con->prepare("select * from doctorslinkusers where IdMED=? and IdUs=?");
			$resultUSU->bindValue(1, $idEncontrado, PDO::PARAM_INT);
			$resultUSU->bindValue(2, $check_mem, PDO::PARAM_INT);
			$resultUSU->execute();
			
			$rowUSU3 = $resultUSU->fetch(PDO::FETCH_ASSOC);

			if($rowUSU['Identif'] == $check_mem or $rowUSU2['IdPac'] == $check_mem /*or $rowUSU3['IdUs'] == $check_mem*/ ){
			$this->status4 = 'true';
			$this->rule4 = 'You have access because someone in your group ('.$other_doctor_name.') has access.';
			break;
			}else{
			$this->status4 = 'false';
			}
			
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



}
public function display(){
echo $this->status1."1</br>".$this->status2."2</br>".$this->status3."3</br>".$this->status4."4";
}
}

?>
