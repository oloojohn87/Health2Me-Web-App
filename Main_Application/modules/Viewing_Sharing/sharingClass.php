<?php
//AUTHOR: Kyle Austin
//DATE: 03/24/15
//Purpose: Create class to create a centralized location for viewing and sharing logic...

class sharingClass{
	
	//PDO
	private $con;
	private $dbhost;
	private $dbname;
	private $dbuser;
	private $dbpass;
	
	//View List
	private $view_list;
	public $view_list_json;
	
	//Pin List
	private $pin_list;
	public $pin_list_json;
	
	public function __construct(){
		require("../../environment_detail.php");
		
		//SET DB CONNECTION/
		$this->dbhost = $env_var_db["dbhost"];
		$this->dbname = $env_var_db["dbname"];
		$this->dbuser = $env_var_db["dbuser"];
		$this->dbpass = $env_var_db["dbpass"];
		
		//Make connection
		$this->con = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname.';charset=utf8', ''.$this->dbuser.'', ''.$this->dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		if (!$this->con)
		{
			die('Could not connect: ' . mysql_error());
		}
	}
	
	//THIS FUNCTION BUILDS ARRAY OF VIEWABLE PATIENTS AND OTHER PERTANENT DATA
	public function viewingList($doc_id, $string, $group, $limit, $offset){
		$user = $string;
		$IdMed = $doc_id;
		
		if($user==null or $user==" " or $user=="" or $user==-111){
			$user="";
		}else{
			$group = -1;
		}
		
		$limitquery='LIMIT '.$offset.','.$limit;
		
		$patients_array = array();
		$count=0;
		
		$query=$this->con->prepare("SELECT q.* FROM (
									(
										SELECT USU.* FROM usuarios USU 
										INNER JOIN (    
											SELECT DISTINCT A.idDoctor FROM doctorsgroups A 
											INNER JOIN (
												SELECT idGroup FROM doctorsgroups WHERE idDoctor=?
											) 
											B ON B.idGroup = A.idGroup
										) 
										DG ON DG.idDoctor = USU.IdCreator
									)
								UNION
									(
										SELECT USU.* FROM usuarios USU 
										INNER JOIN (
											SELECT IdPac FROM doctorslinkdoctors WHERE IdMED2=?
										) DLD ON DLD.IdPac = USU.Identif
									)
								UNION
									(
										SELECT USU.* FROM usuarios USU 
										INNER JOIN (
											SELECT IdUs FROM doctorslinkusers WHERE IdMED=?
										) DLU ON DLU.IdUs = USU.Identif
									)
								UNION
									(
										SELECT * FROM usuarios WHERE IdCreator = ?
									)
								)q WHERE q.Surname LIKE ? OR q.Name LIKE ? OR q.IdUsFIXEDNAME LIKE ? ".$limitquery);

		$query->bindValue(1, $IdMed, PDO::PARAM_INT);
		$query->bindValue(2, $IdMed, PDO::PARAM_INT);
		$query->bindValue(3, $IdMed, PDO::PARAM_INT);
		$query->bindValue(4, $IdMed, PDO::PARAM_INT);
		$query->bindValue(5, '%'.$user.'%', PDO::PARAM_STR);
		$query->bindValue(6, '%'.$user.'%', PDO::PARAM_STR);
		$query->bindValue(7, '%'.$user.'%', PDO::PARAM_STR);

		$query->execute();
		
		$data = array();
		$x = 0;
		
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				
				$current_encoding = mb_detect_encoding($row['Name'], 'auto');
				$UserName = iconv($current_encoding, 'ISO-8859-1', $row['Name']);

				$current_encoding = mb_detect_encoding($row['Surname'], 'auto');
				$UserSurname = iconv($current_encoding, 'ISO-8859-1', $row['Surname']); 

				$countPIN=0;
				$countactualPIN=0;
				$idEncontrado=$row['Identif'];
				$TotalPIN= $this->con->prepare("SELECT count(*) as numreports FROM lifepin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = ? and emr_old=0 ");
				$TotalPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
				$TotalPIN->execute();
			
				
				if($rowNUM=$TotalPIN->fetch(PDO::FETCH_ASSOC)){
					   $countPIN=$rowNUM['numreports'];
				}
			
			
			
				$dluPIN=$this->con->prepare("select * from doctorslinkusers where IdUs=? and IdMED=?");
				$dluPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
				$dluPIN->bindValue(2, $IdMed, PDO::PARAM_INT);
			
				

				$result = $dluPIN->execute();
				$dluqquery='';
				$num=$dluPIN->rowCount();
							
							//echo "report".$num;    
					
							$i=0;
							if($num>0){

								while($rowdlu = $dluPIN->fetch(PDO::FETCH_ASSOC)){
										$dluqquery='';
									
										if($rowdlu['IdPIN']==null){
										$dluqquery="UNION(select lp.* from lifepin lp INNER JOIN (select IdMED from doctorslinkusers where IdUs=? and IdMED=? and IdPIN IS NULL) dlu where dlu.IdMED=lp.IdMED and lp.IdUsu=?)";

										}else{
									
										if($rowdlu['IdPIN']!=null){
											$dluqquery="UNION(select lp.* from lifepin lp INNER JOIN (select * from doctorslinkusers where IdUs=? and IdMED=?) dlu where dlu.IdPIN=lp.IdPin and lp.IdUsu=?)";
										}
									
										}

								}
							}
			
			   //echo $dluqquery;
			
				$loadquery="(select LP.* from lifepin LP INNER JOIN 
				(
				(select A.idDoctor from doctorsgroups A INNER JOIN (select idGroup from doctorsgroups where idDoctor=?) B where B.idGroup=A.idGroup) 
				UNION 
				(select Id from doctors where Id=?) 
				UNION 
				(select IdMED from doctorslinkdoctors where IdMED2=? and IdPac=?)
				) 
				AB where LP.IdMed=AB.idDoctor and IdUsu=? and 
				(LP.markfordelete=0 or LP.markfordelete is null) and 
				(LP.IsPrivate=0 or LP.IsPrivate is null) and 
				NOT (LP.Tipo IN (select Id from tipopin where Agrup=9) and LP.IdMED!=?) 
				and 
				LP.emr_old=0)
				UNION
				(select * from lifepin where IdMED=? and IdUsu=? and (markfordelete=0 or markfordelete is null) and emr_old=0)".$dluqquery;
			
				 $viewablePIN=$this->con->prepare($loadquery);
			
			
					$viewablePIN->bindValue(1, $IdMed, PDO::PARAM_INT);
					$viewablePIN->bindValue(2, $IdMed, PDO::PARAM_INT);
					$viewablePIN->bindValue(3, $IdMed, PDO::PARAM_INT);
					$viewablePIN->bindValue(4, $idEncontrado, PDO::PARAM_INT);
					$viewablePIN->bindValue(5, $idEncontrado, PDO::PARAM_INT);
					$viewablePIN->bindValue(6, $IdMed, PDO::PARAM_INT);
					$viewablePIN->bindValue(7, $IdMed, PDO::PARAM_INT);
					$viewablePIN->bindValue(8, $idEncontrado, PDO::PARAM_INT);
					$viewablePIN->bindValue(9, $idEncontrado, PDO::PARAM_INT);
					$viewablePIN->bindValue(10, $IdMed, PDO::PARAM_INT);
					$viewablePIN->bindValue(11, $idEncontrado, PDO::PARAM_INT);
					$viewablePIN->execute();
			
				
				$hasfullaccess=0;
				if($viewablePIN->fetch(PDO::FETCH_ASSOC)){
					   $countactualPIN=$viewablePIN->rowCount();
					   $hasfullaccess=1;
				}
			
					if($hasfullaccess==1){
							  $viewable=$this->con->prepare("Select count(*) as patreport from lifepin where (IdMed IS NULL or IdMed=0 or IdMed=?) and (markfordelete IS NULL or markfordelete=0) and IdUsu=?");
							  $viewable->bindValue(1, $idEncontrado, PDO::PARAM_INT);
							  $viewable->bindValue(2, $idEncontrado, PDO::PARAM_INT);
							  $viewable->execute();
							
								if($row=$viewable->fetch(PDO::FETCH_ASSOC))
									$numPIN=$row['patreport'];
				
							$countactualPIN+=$numPIN;          //If Idpin is null the doctor has full access to the patients
					  
					}
			
			
				//HIGHLIGHT THE QUERY TERM AT THE OUTPUT
				$user = strtolower($user);
				$UserName = strtolower($UserName);
				$UserSurname = strtolower($UserSurname);
					   
				$NReports=$countactualPIN."/".$countPIN;
				
				if(isset($row['IdUsFIXED'])){
					$fixed_id = $row['IdUsFIXED'];
				}else{
					$fixed_id = 'N/A';
				}
				
				if(isset($row['email'])){
					$email = $row['email'];
				}else{
					$email = 'Not Set';
				}
				
				$data[$x]['id'] = $idEncontrado;
				$data[$x]['fixed_id'] = $fixed_id;
				$data[$x]['name'] = $UserName;
				$data[$x]['surname'] = $UserSurname;
				$data[$x]['report1'] = $countactualPIN;
				$data[$x]['report2'] = $countPIN;
				$data[$x]['email'] = $email;
				
				$x++;
		}
		
		$this->view_list = $data;
		$this->view_list_json = json_encode($data);
	}
	
	//THIS WILL DISPLAY ALL YOUR VIEWABLE PATIENTS
	public function displayList(){
		var_dump($this->view_list);
	}
	
	//THIS WILL DISPLAY ALL YOUR VIEWABLE PATIENTS
	public function displayListJSON(){
		echo $this->view_list_json;
	}
	
	//THIS WILL BUILD A LIFEPIN ARRAY FOR A USER
	public function buildLifeArray($user_id, $doc_id){
		$isDoctor = true;
		if($isDoctor){
			$life_query = 'hide_from_member = 1 OR hide_from_member = 0';
		}else{
			$life_query = 'hide_from_member = 0';
		}

		$data = array();
		
		$query=$this->con->prepare("select q.* from 

                 (
                    (

                             select LP.* from lifepin LP 
                             INNER JOIN 
                             (
                                   (
                                       select A.idDoctor from doctorsgroups A 
                                       INNER JOIN 
                                        (
                                           select idGroup from doctorsgroups where idDoctor=?
                                        ) 
                                       B where B.idGroup=A.idGroup
                                   )

                              UNION 
                                 (
                                     select A.idDoctor from doctorsgroups A 
                                     INNER JOIN 
                                     (
                                        select idGroup from doctorsgroups grp 
                                          INNER JOIN 
                                         (
                                             select IdMED from doctorslinkdoctors where IdMED2=? and IdPac=?
                                          )
                                        rd where grp.idDoctor=rd.IdMed
                                    ) B 
                                    where B.idGroup=A.idGroup
                                 ) 
                             UNION 
                                     (
                                         select Id from doctors where Id=?
                                     ) 
                             UNION 
                                    (
                                     select IdMED from doctorslinkdoctors where IdMED2=? and IdPac=?

                                    )
                             ) 
                             AB where LP.IdMed=AB.idDoctor AND (".$life_query." OR hide_from_member is null) 
                             and IdUsu=? 
                             and 
                             (
                                 LP.markfordelete=0 or LP.markfordelete is null
                             ) 
                             and 
                             (
                                LP.IsPrivate=0 or LP.IsPrivate is null
                             ) 
                             and 
                             NOT 
                             (LP.Tipo IN 
                                (
                                   select Id from tipopin where Agrup=9
                                ) 
                             and LP.IdMED!=?
                             )

                             and LP.emr_old=0

                     ) 
                     UNION 
                     (
                            select * from lifepin where  IdUsu=? 
                            and (markfordelete=0 or markfordelete is null) 
                            and (IsPrivate=0 or IsPrivate is null) 
                            and emr_old=0 
                     ) 
                     UNION 
                     (

                             select lp.* from lifepin lp 

                               INNER JOIN 

                             (
                                 select IdPin from doctorslinkusers where IdMED=? and IdUs=? and IdPIN IS NOT NULL

                             ) dlu where dlu.IdPIN=lp.IdPin 

                     )
                 ) 
                 q ORDER BY q.fecha DESC ");



            $query->bindValue(1, $doc_id, PDO::PARAM_INT);
            $query->bindValue(2, $doc_id, PDO::PARAM_INT);
            $query->bindValue(3, $user_id, PDO::PARAM_INT);
            $query->bindValue(4, $doc_id, PDO::PARAM_INT);
            $query->bindValue(5, $doc_id, PDO::PARAM_INT);
            $query->bindValue(6, $user_id, PDO::PARAM_INT);
            $query->bindValue(7, $user_id, PDO::PARAM_INT);
            $query->bindValue(8, $doc_id, PDO::PARAM_INT);
            $query->bindValue(9, $user_id, PDO::PARAM_INT);
            $query->bindValue(10, $doc_id, PDO::PARAM_INT);
            $query->bindValue(11, $user_id, PDO::PARAM_INT);
            
            $result = $query->execute();
			$numero=$query->rowCount();
			$x = 0;
			
			while ($row = $query->fetch(PDO::FETCH_ASSOC))
			{
				if($row['IdMed'] == null){
					$id_creator = 'Created By Member';
				}else{
					$id_creator = $row['IdMed'];
				}
				
				$data[$x]['id'] = $row['IdPin'];
				$data[$x]['raw'] = $row['RawImage'];
				$data[$x]['owner'] = $row['IdUsu'];
				$data[$x]['creator'] = $id_creator;
				$data[$x]['date'] = $row['Fecha'];
				$data[$x]['date_input'] = $row['FechaInput'];
				$data[$x]['ev_ru_punt'] = $row['EvRuPunt'];
				$data[$x]['event'] = $row['Evento'];
				$data[$x]['type'] = $row['Tipo'];
				$data[$x]['specialty'] = $row['Especialidad'];
				$data[$x]['channel'] = $row['CANAL'];
				$data[$x]['need_action'] = $row['NeedACTION'];
				$data[$x]['email_present'] = $row['IdEmail'];
				$data[$x]['email'] = $row['IdMEDEmail'];
				$data[$x]['email_date'] = $row['FechaEmail'];
				$data[$x]['fixed_id'] = $row['IdUsFIXED'];
				$data[$x]['fixed_name'] = $row['IdUsFIXEDNAME'];
				$data[$x]['validation_status'] = $row['ValidationStatus'];
				$data[$x]['next_action'] = $row['NextAction'];
				$data[$x]['vs'] = $row['vs'];
				$data[$x]['fs'] = $row['fs'];
				$data[$x]['as'] = $row['a_s'];
				$data[$x]['hash'] = $row['checksum'];
				$data[$x]['emr_report'] = $row['emr_report'];
				$data[$x]['hide_from_member'] = $row['hide_from_member'];
				$x++;
			}    
			
			$this->pin_list = $data;
			$this->pin_list_json = json_encode($data);
	}
	
	public function displayPins(){
		var_dump($this->pin_list);
	}
	
	public function displayPinsJSON(){
		echo $this->pin_list_json;
	}
	
	//THIS CHECKS FOR SHARING ACCESS BY TYPE
	public function checkForAccess($user_id, $doctor_flag, $type, $id_pin = null){
		
		if($type == 'reshare' || $type == 'view'){
			if($doctor_flag){
				echo $this->searchArrayPatients($user_id);
			}else{
				$query=$this->con->prepare("SELECT * FROM lifepin where IdPin = ? && IdUsu = ?");
				$query->bindValue(1, $id_pin, PDO::PARAM_INT);
				$query->bindValue(2, $user_id, PDO::PARAM_INT);
				$result = $query->execute();
				
				$row = $query->fetch(PDO::FETCH_ASSOC);
				
				if($row['hide_from_member'] == 1){
					echo 0;
				}else{
					echo 1;
				}
			}
		}elseif($type == 'delete' && $id_pin != null){
			if(!$doctor_flag){
				
				$query=$this->con->prepare("SELECT * FROM lifepin where IdPin = ?");
				$query->bindValue(1, $id_pin, PDO::PARAM_INT);
				$result = $query->execute();
				
				$row = $query->fetch(PDO::FETCH_ASSOC);
				
				$mem_id = $row['IdUsu'];
				
				if($mem_id == $user_id && $row['CreatorType'] == 0){
					$query=$this->con->prepare("SELECT * FROM doctorslinkdoctors where attachments LIKE ?");
					$query->bindValue(1, $id_pin, PDO::PARAM_INT);
					$result = $query->execute();
					
					$exists = 1;

					while ($row = $query->fetch(PDO::FETCH_ASSOC))
					{
						$attachments = explode(" ", $row['attachments']);

						$key = array_search($id_pin, $attachments);
						if($key == 'id'){
							$exists = 0;
						}
					}
					
					echo $exists;
					
				}else{
					echo 0;
				}
			}elseif($doctor_flag){
				require('../../getFullUsersMEDCLASS.php');
				
				$exists= 1;
				$checker = new checkPatientsClass();
				$doc_id = $user_id;
				
				$query=$this->con->prepare("SELECT * FROM lifepin where IdPin = ?");
				$query->bindValue(1, $id_pin, PDO::PARAM_INT);
				$result = $query->execute();
				
				$row = $query->fetch(PDO::FETCH_ASSOC);
				
				$mem_id = $row['IdUsu'];
				
				if($row['IdCreator'] != $doc_id){
					$exists = 0;
				}
				
				$checker->setters($mem_id, $doc_id);
				$checker->checker();

				if($checker->status1 != 'true' && $checker->status2 != 'true' && $checker->status3 != 'true' && $checker->status4 != 'true'){
					$exists = 0;
				}
				
				if($exists == 1){
					$query=$this->con->prepare("SELECT * FROM doctorslinkdoctors where attachments LIKE ?");
					$query->bindValue(1, $id_pin, PDO::PARAM_INT);
					$result = $query->execute();
					
					while ($row = $query->fetch(PDO::FETCH_ASSOC))
					{
						$attachments = explode(" ", $row['attachments']);

						$key = array_search($id_pin, $attachments);
						if($key == 'id'){
							$exists = 0;
						}
					}
					
					echo $exists;
				}else{
					echo 0;
				}

			}else{
				echo 0;
			}
		}
	}
	
	//THIS SEARCHES THE ACCESS ARRAY AND CHECKS IF USERID IS PRESENT
	private function searchArrayPatients($user_id){
		$exists = 0;
			foreach($this->view_list as $list){
				$key = array_search($user_id, $list);
				if($key == 'id'){
					$exists = 1;
				}
			}
			
			return $exists;
	}
	
	//THIS SEARCHES THE PIN ARRAY AND CHECKS IF DOCID IS PRESENT
	private function searchArrayPins($doc_id){
		$exists = 0;
			foreach($this->pin_list as $list){
				$key = array_search($doc_id, $list);
				if($key == 'id'){
					$exists = 1;
				}
			}
			
			return $exists;
	}
	
}
?>
