<?php
//AUTHOR: Kyle Austin
//DATE: 01/07/15
//Purpose: Create class to consolidate many functions that are redundant within medical passport...

class medicalPassportClass{
	
	//PDO
	private $con;
	private $dbhost;
	private $dbname;
	private $dbuser;
	private $dbpass;
	
	//Translations
	public $translations;
	
	//Counter
	public $counter;
	
	//Msc Variables
	public $relative_type;
	public $slotage;
	public $slotrow;
	
	//Crypt
	private $crypt;
	
	//Member variables
	public $passport_id;
	public $member_name;
	public $member_surname;
	
	//Doctor variables
	public $med_id;
	
	//Ajax
	public $ajax;
	
	//RUN CONSTRUCT
	public function __construct($id, $med_id, $crypt, $ajax){
		require("../../environment_detail.php");
		
		$this->ajax = $ajax;
		
		if(!$ajax){
			echo '<link href="../../css/bootstrap.css" rel="stylesheet">
			<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
			<script src="../../js/jquery.min.js"></script>
			<script src="../../js/jquery-ui.min.js"></script>
			
			<!-- Le styles -->
			<link href="../../css/style.css" rel="stylesheet">
			<link href="../../css/bootstrap.css" rel="stylesheet">
			<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
			<link rel="stylesheet" href="../../css/jquery-ui-1.8.16.custom.css" media="screen"  />
			<link rel="stylesheet" href="../../css/fullcalendar.css" media="screen"  />
			<link rel="stylesheet" href="../../css/chosen.css" media="screen"  />
			<link rel="stylesheet" href="../../css/datepicker.css" >
			<link rel="stylesheet" href="../../css/colorpicker.css">
			<link rel="stylesheet" href="../../css/glisse.css?1.css">
			<link rel="stylesheet" href="../../css/jquery.jgrowl.css">
			<link rel="stylesheet" href="../../js/elfinder/css/elfinder.css" media="screen" />
			<link rel="stylesheet" href="../../css/jquery.tagsinput.css" />
			<link rel="stylesheet" href="../../css/demo_table.css" >
			<link rel="stylesheet" href="../../css/jquery.jscrollpane.css" >
			<link rel="stylesheet" href="../../css/validationEngine.jquery.css">
			<link rel="stylesheet" href="../../css/jquery.stepy.css" />
			<!--<link rel="stylesheet" type="text/css" href="../../css/googleAPIFamilyCabin.css">-->
			  <script type="text/javascript" src="../../js/42b6r0yr5470"></script>
			<link rel="stylesheet" href="../../css/icon/font-awesome.css">
		 <!--   <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet"> -->
			<link rel="stylesheet" href="../../css/bootstrap-responsive.css">
			<link rel="stylesheet" href="../../css/toggle-switch.css">
			<link rel="stylesheet" href="../../build/css/intlTelInput.css">';
			
			
				if ($_SESSION["CustomLook"]=="COL") { 
				echo '<link href="../../css/styleCol.css" rel="stylesheet">';
				}  
		echo '<!--    <link href="../../css/FamilyTree.css" rel="stylesheet">-->
			
			<script type="text/javascript" src="../../js/jquery.easing.1.3.js"></script>	
			<script type="text/javascript" src="../../js/modernizr.2.5.3.min.js"></script>	
			<!--<script src="../../js/sweet-alert.min.js"></script>-->
			
		 
			<!-- Le fav and touch icons -->
			<link rel="shortcut icon" href="../../images/icons/favicon.ico">';
		}
		

		//ADD TRANSLATIONS TO THIS ARRAY TO PUSH TRANSLATION CLASS WIDE
		if($_COOKIE["lang"] == 'th'){
				//Spanish
				$this->translations = array(
					'dob'=>'F. Nac. : ',
					'unknown'=>'Desconocido',
					'address'=>'Dirección:',
					'city'=>'Ciudad:',
					'zip'=>'C.P. : ',
					'phone'=>'Telefono:',
					'email'=>'Email :',
					'gender'=>'Sexo:',
					'height'=>'Altura:',
					'medications'=>'Medicamentos',
					'nomeds'=>'No tomar la medicación',
					'noentries'=>'No hay entradas',
					'famhistory'=>'Antecedentes Familiares',
					'me'=>'Yo',
					'vaccinationcalendar'=>'Calendario De Vacunación',
					'allergies'=>'Alergias',
					'personalhistory'=>'Historia Personal',
					'habits'=>'Hábitos',
					'perday'=>'Por Dia',
					'glassweek'=>'Vasos/Semana',
					'hourday'=>'Horas/Dia',
					'hourweek'=>'Horas/Semana',
					'cigs'=>'No Cigarrillos',
					'exercise'=>'No Ejercicio',
					'sleep'=>'No Sueño',
					'memberinformation'=>'Informacion del Miembro',
					'delete'=>'Bor',
					'digestive'=>'Digestivos',
					'familyhistory'=>'Historia Familiar',
					'allergyhistory'=>'Alergias Conocidas',
					'immuhistory'=>'Calendario De Vacunación',
					'deleteddiagnostics'=>'Diagnóstico Eliminados',
					'deletedmedications'=>'Medicamentos Eliminados',
					'deletedfamilyhistory'=>'Eliminado de Historia Familiar',
					'deletedimmunizations'=>'Inmunización Eliminados',
					'deletedallergies'=>'Alergias Eliminados',
					'nodatafound'=>'No se encontraron datos',
					'allergicto'=>'Alérgico a',
					'since'=>'desde',
					'ofage'=>'de edad',
					'environmental'=>'Ambiental',
					'foods'=>'Comidas',
					'drugs'=>'Medicamentos',
					'other'=>'Otros',
					'editing'=>'Edición',
					'diagnostics'=>'Historia Personal'
					
				);
			}else{
				//English
				$this->translations = array(
					'dob'=>'DOB : ',
					'unknown'=>'Unknown', 
					'address'=>'Address : ',
					'city'=>'City : ',
					'zip'=>'Zip : ',
					'phone'=>'Phone : ',
					'email'=>'Email :',
					'gender'=>'Gender : ',
					'height'=>'Height : ',
					'medications'=>'Medications',
					'nomeds'=>'Not Taking Medications',
					'noentries'=>'No Entries',
					'famhistory'=>'Family History',
					'me'=>'Me',
					'vaccinationcalendar'=>'Vaccination Calendar',
					'allergies'=>'Allergies',
					'personalhistory'=>'Personal History',
					'habits'=>'Habits',
					'perday'=>'Per Day',
					'glassweek'=>'Glasses/Week',
					'hourweek'=>'Hours/Week',
					'hourday'=>'Hours/Day',
					'cigs'=>'No Cigarettes',
					'exercise'=>'No Exercise',
					'sleep'=>'No Sleep',
					'memberinformation'=>'Member Information',
					'delete'=>'Del',
					'digestive'=>'Digestive',
					'familyhistory'=>'Family History',
					'allergyhistory'=>'Allergy History',
					'immuhistory'=>'Immunization History',
					'deleteddiagnostics'=>'Deleted Diagnostics',
					'deletedmedications'=>'Deleted Medications',
					'deletedfamilyhistory'=>'Deleted Family History',
					'deletedimmunizations'=>'Deleted Immunizations',
					'deletedallergies'=>'Deleted Allergies',
					'nodatafound'=>'No Data Found',
					'allergicto'=>'Allergic to',
					'since'=>'Since',
					'ofage'=>'of age',
					'environmental'=>'Environmental',
					'foods'=>'Foods',
					'drugs'=>'Drugs',
					'other'=>'Other',
					'editing'=>'Editing',
					'diagnostics'=>'Diagnostics'
				);
			}
			
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

		$this->passport_id = $id;
		$this->med_id = $med_id;
		$this->crypt = $crypt;
		
		$query = $this->con->prepare("SELECT * FROM usuarios WHERE Identif=?");
		$query->bindValue(1, $this->passport_id, PDO::PARAM_INT);
		$result = $query->execute();
		
		$row = $query->fetch(PDO::FETCH_ASSOC);
		
		$current_encoding = mb_detect_encoding($row['Name'], 'auto');
		$show_text = iconv($current_encoding, 'ISO-8859-1', $row['Name']);
		$this->member_name = $show_text;

		$current_encoding = mb_detect_encoding($row['Surname'], 'auto');
		$show_text2 = iconv($current_encoding, 'ISO-8859-1', $row['Surname']); 
		$this->member_surname = $show_text2;
	
	}
	
	//START CONTROLLER FUNCTIONS
	public function getBasicPatientInfo($display_type){
		$basicQuery = $this->con->prepare("SELECT FNac,name,surname,sexo,email FROM usuarios WHERE Identif=?");
		$basicQuery->bindValue(1, $this->passport_id, PDO::PARAM_INT);

		$ageQuery= $this->con->prepare("SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(dob)), '%Y')+0 AS age,DOB FROM basicemrdata where idpatient=?");
		$ageQuery->bindValue(1, $this->passport_id, PDO::PARAM_INT);

		$result = $basicQuery->execute();
		$count = $basicQuery->rowCount();
		if($count==1)
		{
			$row = $basicQuery->fetch(PDO::FETCH_ASSOC);
			
			$current_encoding = mb_detect_encoding($row['name'], 'auto');
			$show_text = iconv($current_encoding, 'ISO-8859-1', $row['name']);

			$current_encoding = mb_detect_encoding($row['surname'], 'auto');
			$show_text2 = iconv($current_encoding, 'ISO-8859-1', $row['surname']); 
			
			$name = $this->capitalizeFirst($show_text);
			$surname = $this->capitalizeFirst($show_text2);
			$sex = $row['sexo'];
			$UserEmail = $row['email'];	
			
			$result = $ageQuery->execute();
			$row2 = $ageQuery->fetch(PDO::FETCH_ASSOC);
			$age = $row2['age'];
			$dob = $row2['DOB'];
			$dob_object = new DateTime($dob);
			$readable_dob = $dob_object->format('F j, Y');
			$dob = explode(" ", $dob);
			$dob = $dob[0];
			
			//Image
			$fileName = "PatientImage/".$this->passport_id.".jpg";
			if(file_exists($fileName))
			{
				shell_exec('Decrypt_Image.bat PatientImage '.$this->passport_id.'.jpg '.$this->passport_id.' '.$this->crypt.' 2>&1');
				$file = "temp/".$this->passport_id."/".$this->passport_id.".jpg";
				$file = "PatientImage/".$this->passport_id.".jpg";  
				$style = "max-height: 80px; max-width:80px;";
			}
			else
			{
				$hash = md5( strtolower( trim( $UserEmail ) ) );
				$file = 'identicon.php?size=29&hash='.$hash;
				$style = "max-height: 80px; max-width:80px;";
			}
			
			
			if($display_type == 1){
				$page = '<div id="BasicInfo">';
			}else{
				$page = '<div id="BasicInfo" class="grid" class="grid span4" style="cursor:pointer;float:left;height:15%; margin-top:-2%; margin-left:1%; margin-right:1%;width:44%;display:table">';
			}
		
			$page .= '<div id="DivAv" style="float:left; width:60px; height:100%; ">
					<img src="../../'.$file.'" style="width:50px; margin-left:20%;margin-top:30%; float:left; font-size:18px;  border:1px solid #b0b0b0;"/>
				 </div>';   
			
		//DOB trans
		$show_text = $this->translations['dob'];

		$SToday = $readable_dob;
		if($_COOKIE["lang"] == 'th'){
		if(substr($SToday, 0, 3) == 'Jan'){
		$new_month = 'Ene '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Feb'){
		$new_month = 'Feb '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Mar'){
		$new_month = 'Mar '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Apr'){
		$new_month = 'Abr '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'May'){
		$new_month = 'May '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Jun'){
		$new_month = 'Jun '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Jul'){
		$new_month = 'Jul '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Aug'){
		$new_month = 'Ago '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Sep'){
		$new_month = 'Sep '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Oct'){
		$new_month = 'Oct '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Nov'){
		$new_month = 'Nov '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Dec'){
		$new_month = 'Dic '.substr($SToday, -8, 8);
		}
		}else{
		if(substr($SToday, 0, 3) == 'Jan'){
		$new_month = 'Jan '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Feb'){
		$new_month = 'Feb '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Mar'){
		$new_month = 'Mar '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Apr'){
		$new_month = 'Apr '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'May'){
		$new_month = 'May '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Jun'){
		$new_month = 'Jun '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Jul'){
		$new_month = 'Jul '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Aug'){
		$new_month = 'Aug '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Sep'){
		$new_month = 'Sep '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Oct'){
		$new_month = 'Oct '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Nov'){
		$new_month = 'Nov '.substr($SToday, -8, 8);
		}
		if(substr($SToday, 0, 3) == 'Dec'){
		$new_month = 'Dec '.substr($SToday, -8, 8);
		}
		}
			//Name	and DOB
			$page.= '<div style="float:left; height:100%; ">
						<div id="NombreComp" style="margin-top:10%;margin-left:10%;width:100%; color: rgba(34,174,255,1); font: bold 30px Arial, Helvetica, sans-serif; cursor: auto;">'.(htmlspecialchars($name)).' '.(htmlspecialchars($surname)).'</div>
						<span id="UDOB" style="color: rgba(84,188,0,1); font: bold 16px Arial, Helvetica, sans-serif; cursor: auto; margin-top:-5px;margin-left:10%;">'.$show_text.$new_month.'</span>
						<input type="hidden" id="RAWDOB" value="'.$dob.'" />
				 </div>';
			
			$page.= '<div style="float:right;height:100%;margin-left:10%;margin-right:5%;margin-top:10%; ">'.$this->getGenderIcon($sex).'
					  
					  <span id="NombreComp2" style="width:100%; color: rgba(34,174,255,1); font: bold 30px Arial, Helvetica, sans-serif; cursor: auto;">'.$age.'</span>
				 </div>';
			
			$page.= '<input id="MEDID" value="'.$this->med_id.'" style="float:left; width:25px; font-size:10px; display:none;">';
			$page.= '<input id="USERID" value="'.$this->passport_id.'" style="float:left; width:25px; font-size:10px; display:none;">';


		}
		else
		{
			$page = "Error";
		}
		
		$page .= '</div>';
		
		//MODAL WINDOW WITH JAVASCRIPT AT FOOT...
		$page .= '<!-- MODAL VIEW FOR ADDITIONAL INFO -->
				<div id="modalAdditionalInfo" style="overflow:visible;display:none;  padding:20px;">
					<div style="border:solid 1px #cacaca; margin-top:5px; padding:10px;">
					
						<table style="width:100%;background-color:white">
				<!--			<tr>
								<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Insurance: </span></td>
								<td style="width:80%"><input id="Insurance" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" lang="en" placeholder="Enter Insurance"></td>
							</tr>-->
							<tr>
								<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">DOB: </span></td>
								<td style="width:80%"><input id="DOB" type="date" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" lang="en" /></td>
							</tr>
							<tr>
								<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Gender: </span></td>
								<td style="width:80%">
									<select id="Gender" style="margin-top: 10px;width: 100%; float:left;font-size:14px;height:26px" lang="en">
										<option value="Male">Male</option>
										<option value="Female" selected>Female</option>
									</select>
								</td>
							</tr>
							<tr>
								<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Address: </span></td>
								<td style="width:80%"><input id="Address" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" lang="en" placeholder="Enter Address"></td>
							</tr>
							<tr>
								<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">City: </span></td>
								<td style="width:80%"><input id="City" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" lang="en" placeholder="Enter City"></td>
							</tr>
							<tr>
								<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Zip: </span></td>
								<td style="width:80%"><input id="Zip" class="numbersOnly" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" lang="en" placeholder="Enter Zip-Code"></td>
							</tr>
							<tr>
								<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Phone: </span></td>
								<td style="width:80%"><input id="Phone" type="tel" placeholder="e.g. +1 702 123 4567" style="margin-top: 10px;width: 100%; float:left;font-size:14px;height:25px"></td>
							</tr>
							<tr>
								<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Email: </span></td>
								<td style="width:80%"><input id="Email" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" lang="en" placeholder="Select Email"></td>
							</tr>
							<tr>
								<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Blood: </span></td>
								<td style="width:80%">
									<select id="blood_type" style="margin-top: 10px;width: 100%; float:left;font-size:14px;height:26px">
										<option value="none" lang="en">Not Specified</option>
										<option value="Apos">A+</option>
										<option value="Aneg">A-</option>
										<option value="Bpos">B+</option>
										<option value="Bneg">B-</option>
										<option value="ABpos">AB+</option>
										<option value="ABneg">AB-</option>
										<option value="Opos">O+</option>
										<option value="Oneg">O-</option>
									</select>
								</td>
							</tr>
							<tr>
								<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Weight: </span></td>
								<td style="width:80%">
									<input id="weight" type="text" style="margin-top: 10px;width: 58%; float:left;font-size:14px;height:25px" lang="en" placeholder="Enter Weight">
									<select id="weight_type" style="margin-top: 10px;width: 30%; margin-left: 5%; float:left;font-size:14px;height:26px">
										<option value="lb" lang="en">lb</option>
										<option value="kg" lang="en">kg</option>
									</select>
								</td>
							</tr>
							<tr>
								<td style="width:20%"><span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Height: </span></td>
								<td style="width:80%">
									<input id="height1" type="text" style="margin-top: 10px;width: 13%; float:left;font-size:14px;height:25px" lang="en">
									<div id="height1label" style="margin-top: 10px;width: 10%; float:left;font-size:14px;height:25px; padding-top: 5px; margin-left: 8px;" lang="en">
										ft
									</div>
									<input id="height2" type="text" style="margin-top: 10px;width: 13%; float:left;font-size:14px;height:25px" lang="en">
									<div id="height2label" style="margin-top: 10px;width: 10%; float:left;font-size:14px;height:25px; padding-top: 5px; margin-left: 8px;" lang="en">
										in
									</div>
									<select id="height_type" style="margin-top: 10px;width: 31%; margin-left: 5%; float:left;font-size:14px;height:26px">
										<option value="ft" lang="en">feet</option>
										<option value="m" lang="en">meters</option>
									</select>
								</td>
							</tr>
						</table>
					</div>
					<a id="ButtonUpdateAdditionalInfo" onclick="update_additional_info();" class="btn" style="width:50px; color:#22aeff; float:right; margin-top:10px;margin-bottom:10px"><span lang="en">Update</span></a>
					
				</div>
				<!-- END MODAL VIEW FOR ADDITIONAL INFO -->';
				if($display_type == 0){
				$page .= '<script src="../../build/js/intlTelInput.js"></script>
				<link rel="stylesheet" href="../../build/css/intlTelInput.css">
				<script type="text/javascript">
				
					var additional_info = $("#modalAdditionalInfo").dialog({bgiframe: true, width: 400, height: 450, autoOpen: false, modal: true,title:"'.$this->translations['memberinformation'].'"});
					$("#BasicInfo").live("click",function()	{
						//alert("clicked");
						fillAdditionalInfoDialogBox();
						
						additional_info.dialog("open");
					});
					
					function update_additional_info() {
		
						var phone = $("#Phone").val();
						//Start of new code by Pallab
						if(phone.length == 0)
						{
						   phone = "";
						}
						else
						{
							 if($("#Phone").intlTelInput("isValidNumber")==false)
								{
									alert("Invalid Phone Number");
									$("#Phone").focus();
									return;
								}
							 else
								{	
									//$("#Phone").val($("#Phone").val().replace(/-/g, "")); //remove dashes
									$("#Phone").val($("#Phone").val().replace(/\s+/g, "")); //remove spaces
								}
						
						}
						
						//End of new code by Pallab
						
						//Below phone code commented by Pallab
						//Start of comment
						/*if($("#Phone").intlTelInput("isValidNumber")==false)
						{
							alert("Invalid Phone Number");
							$("#Phone").focus();
							return;
						}
						else
						{	
							//$("#Phone").val($("#Phone").val().replace(/-/g, "")); //remove dashes
							$("#Phone").val($("#Phone").val().replace(/\s+/g, "")); //remove spaces
						}
						*/ //End fo comment

						if(validateEmail($("#Email").val())==false)
						{
							alert("Invalid Email");
							$("#Email").focus();
							return;
						}
						
						function validateEmail(email) { 
							if(email=="")
							{
								return true;
							}
						
							var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
							return re.test(email);
						}
	
						
						var insurance = $("#Insurance").val();
						var address = $("#Address").val();
						var city = $("#City").val();
						var state = $("#State").val();
						var zip = $("#Zip").val();
						
						
						
						 var  email = $("#Email").val();
						console.log("email is "+email);
						
						var gender = $("#Gender").val();
						var dob = $("#DOB").val();
						var blood_type = $("#blood_type").val();
						var weight = $("#weight").val();
						var weight_type = $("#weight_type").val();
						var height1 = $("#height1").val();
						var height2 = $("#height2").val();
						var height_type = $("#height_type").val();
						if(weight_type == "lb")
						{
							weight_type = 1;
						}
						else
						{
							weight_type = 2;
						}
						if(height_type == "ft")
						{
							height_type = 1;
						}
						else
						{
							height_type = 2;
						}
						

							if(getDOB4VaccCalc() != $("#DOB").val())
							{
							alert("You have altered your date of birth.  You may need to reimport your vaccination calendar to diplay the correct vaccinations based on your new age.");
							}
						
						
						//Start of new piece of code by Pallab
						if(email.length == 0)
						{
							
							var url = "medicalPassportUnit.php?data_type=updateBasicPatientInfo&ajax='.$_GET['ajax'].'&insurance="+insurance+"&address="+address+"&city="+city+"&state="+state+"&zip="+zip+"&phone="+phone+"&email="+email+"&gender="+gender+"&dob="+dob+"&blood_type="+blood_type+"&weight="+weight+"&weight_type="+weight_type+"&height1="+height1+"&height2="+height2+"&height_type="+height_type+"&IdUsu='.$this->passport_id.'";
								console.log(phone);
								alert(url);
								var Rectipo = LanzaAjax(url);
								//alert(Rectipo);
								getBasicInfo();
								getAdditionalInfo();
								$("#modalAdditionalInfo").dialog("close");
						}
						else
						{
								var IdUsu = '.$this->passport_id.';
								 $.post("medicalPassportUnit.php?data_type=validateEmail&ajax=false", {email: email, type: "0", IdUsu: '.$this->passport_id.'}, function(data, status)
								  {
								console.log(data);
										if(data == 1)
										{
											var url = "medicalPassportUnit.php?data_type=updateBasicPatientInfo&ajax='.$_GET['ajax'].'&insurance="+insurance+"&address="+address+"&city="+city+"&state="+state+"&zip="+zip+"&phone="+phone+"&email="+email+"&gender="+gender+"&dob="+dob+"&blood_type="+blood_type+"&weight="+weight+"&weight_type="+weight_type+"&height1="+height1+"&height2="+height2+"&height_type="+height_type+"&IdUsu='.$this->passport_id.'";
											//console.log(hold_dob);
											//alert(url);
											var Rectipo = LanzaAjax(url);
											//alert(Rectipo);
											getBasicInfo();
											getAdditionalInfo();
											$("#modalAdditionalInfo").dialog("close");
										}
										else if(data == 2)
										{
											alert("Invalid Email");
										}
										else
										{
											alert("Another user is already using this email");
										}
								  });
						}
					}
					
					function getBasicInfo()
					{
						var link = "medicalPassportUnit.php?data_type=getBasicPatientInfo&ajax=true&user=patient&IdUsu='.$this->passport_id.'";
					
					
						$.ajax({
						   url: link,
						   dataType: "html",
						   async: true,
						   success: function(data)
						   {
								$("#BasicInfo").html(data);
								var myElement = document.querySelector("#BasicInfo");
								myElement.style.display = "block";
							   var dob_display = document.querySelector("#UDOB");
							   dob_display.style.width = "200px";
								//alert("done");
						   }
						});

					
					}
					
					function getAdditionalInfo()
					{
						var link = "medicalPassportUnit.php?data_type=getAdditionalPatientInfo&ajax=true&IdUsu='.$this->passport_id.'";
					
					
						$.ajax({
						   url: link,
						   dataType: "html",
						   async: true,
						   success: function(data)
						   {
								$("#AdditionalInfo").html(data);
								var myElement = document.querySelector("#AdditionalInfo");
								myElement.style.display = "block";
						   }
						});

					
					}
					
					
				</script>';
		}
		
		if($display_type == 0){
			return $page;
		}else{
			echo $page;
		}
	//END BASIC PATIENT INFO
	}
	
	public function getAdditionalPatientInfo($display_type){
		$basicQuery = $this->con->prepare("select insurance,address,Address2,city,zip,doctor_signed,latest_update,bloodType,weight,weightType,height1,height2,heightType from basicemrdata where idpatient=?");
		$basicQuery->bindValue(1, $this->passport_id, PDO::PARAM_INT);


		$contactQuery = $this->con->prepare("select telefono,email,Sexo from usuarios where identif=?");
		$contactQuery->bindValue(1, $this->passport_id, PDO::PARAM_INT);


		$result=$basicQuery->execute();
		$count = $basicQuery->rowCount();



		if($count==1)
		{
			$row = $basicQuery->fetch(PDO::FETCH_ASSOC);
			$compAdress = $row['address'].' '.$row['Address2'];
			$insurance = $this->capitalizeFirst($this->getString($row['insurance']));
			$address = $this->getString(rtrim($compAdress));
			$city = $this->getString($row['city']);
			$zip = $this->getString($row['zip']);
			$doctor_signed = $row['doctor_signed'];
			$latest_update = $row['latest_update'];
			$blood_type = $row['bloodType'];
			$weight = $row['weight'];
			$weight_type = $row['weightType'];
			$height1 = $row['height1'];
			$height2 = $row['height2'];
			$height_type = $row['heightType'];
			$height1type = "ft";
			$height2type = "in";
			if($height_type == 2)
			{
				$height1type = "m";
				$height2type = "cm";
			}
			$blood_type_signed = str_replace("pos", "+", $blood_type);
			$blood_type_signed = str_replace("neg", "-", $blood_type_signed);
			$real_weight_type = 'lb';
			if($weight_type == 2)
			{
				$real_weight_type = 'kg';
			}
		}
		else
		{
			//Unknown trans
			$insurance = $this->translations['unknown'];
			$address = $this->translations['unknown'];
			$city = $this->translations['unknown'];
			$telefono = $this->translations['unknown'];
			$email = $this->translations['unknown'];
			$zip = $this->translations['unknown'];
		}


		$result=$contactQuery->execute();
		$row = $contactQuery->fetch(PDO::FETCH_ASSOC);

		$numbersOnly = preg_replace('/[^0-9,]|,[0-9]*$/','',$row['telefono']);
		//echo 'ERROR '.$numbersOnly;
		$numberOfDigits = strlen($numbersOnly);
		if ($numberOfDigits == 7) {
			$tele = substr($numbersOnly, 0, 3) . '-' . substr($numbersOnly, 3, 4);
		}elseif($numberOfDigits == 10){
			$tele = substr($numbersOnly, 0, 3) . '-' . substr($numbersOnly, 3, 3) . '-'.substr($numbersOnly, 6, 4);
		}elseif($numberOfDigits == 11){
			$tele = substr($numbersOnly, 0, 1) . ' ' . substr($numbersOnly, 1, 3) . '-'.substr($numbersOnly, 4, 3) . '-'.substr($numbersOnly, 7, 4);
		}else{
			$tele=null;
		}

		if ($tele!=null){
			$telefono = $this->getString('+'.$tele);
		}else
		//Unknown trans
		$telefono = $this->translations['unknown'];

		$gender = $row['Sexo'];
		if($gender == '0')
		{
			$gender = 'Female';
		}
		else if($gender == '1')
		{
			$gender = 'Male';
		}

		$email = $this->getString($row['email']);
		if($display_type == 1){
			$page = '<div id="AdditionalInfo"><div style="width: 60px; height: 100%; float: right;">';
		}else{
			$page = '<div id="AdditionalInfo" class="grid" class="grid span4" style="cursor:pointer;float:left;height:28.3%; margin-top:1%; margin-left:1%; margin-right:1%;width:44%;display:table"><div style="width: 60px; height: 100%; float: right;">';
		}
		
			   if($blood_type != 'none')
			   {
				$page .= '<div style="width: 47px; height: 35px; padding-top: 35px; margin-right: 20px; margin-top: .5%; float: right; background-image: url(images/blood_drop.png); background-size: 100% 100%; color: #FFF; text-align: center; font-size: 16px;">'.$blood_type_signed.'</div>';
			   }
				if($weight > 0)
			   {
					$scale_margin_top = "22px";
					if($blood_type == 'none')
					{
						$scale_margin_top = "-10px";
					}
				$page .= '<div style="width: 64px; height: 30px; padding-top: 30px; margin-right: 12px; margin-top: '.$scale_margin_top.'; float: right; background-image: url(images/scale.png); background-size: 100% 100%; color: #FFF; text-align: center; font-size: 16px;">'.$weight.' '.$real_weight_type.'</div>';
			   }
				$page .= '</div>';
				$page .= '<table style="background:white;margin-top:.5%;margin-left:2%;">';
							
							$page .= '<tr>
								<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
								//Address trans
								$page .= $this->translations['address'];
								$page .= '</span></td>
								<td><span style="font-size:18px;color:#54bc00" id="inp-Address">'.$address.'</span></td>
							</tr>
							<tr>
								<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
								//City trans
								$page .= $this->translations['city'];
								$page .= '</span></td>
								<td><span style="font-size:18px;color:#54bc00" id="inp-City">'.$city.'</span></td>
							</tr>
							<tr>
							
								<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
								//Zip trans
								$page .= $this->translations['zip'];
								$page .= '</span></td>
								<td><span style="font-size:18px;color:#54bc00" id="inp-Zip">'.$zip.'</span></td>
							</tr>
							<tr>
								<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
								//Phone trans
								$page .= $this->translations['phone'];
								$page .= '</span></td>
								<td><span style="font-size:18px;color:#54bc00" id="inp-Phone">'.$telefono.'</span></td>
							</tr>
							<tr>
								<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
								//Email trans
								$page .= $this->translations['email'];
								
								if(strlen($email) >= 25){
								$font_holder = '12';
								}else{
								$font_holder = '18';
								}
								$page .= '</span></td>
								<td><span style="font-size:'.$font_holder.'px;color:#54bc00" id="inp-Email">'.$email.'</span></td>
							</tr>
							<tr>
								<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
								//Gender trans
								$page .= $this->translations['gender'];
								$page .= '</span></td>
								<td><span style="font-size:18px;color:#54bc00" id="inp-Gender">'.$gender.'</span></td>
							</tr>
							<tr>
								<td><span style="font-size:18px;color:rgba(34,174,255,1)" lang="en">';
								//Height trans
								$page .= $this->translations['height'];
								$page .= '</span></td>
								<td><span style="font-size:18px;color:#54bc00" id="inp-GWeight">'.$height1.' '.$height1type.' '.$height2.' '.$height2type.'</span></td>
							</tr>
							<input type="hidden" id="BLOODTYPE" value="'.$blood_type.'" />
							<input type="hidden" id="WEIGHT" value="'.$weight.'" />
							<input type="hidden" id="WEIGHTTYPE" value="'.$weight_type.'" />
							<input type="hidden" id="HEIGHT1" value="'.$height1.'" />
							<input type="hidden" id="HEIGHT2" value="'.$height2.'" />
							<input type="hidden" id="HEIGHTTYPE" value="'.$height_type.'" />
							
						</table>		';
			
			
		$page .= '<input id="Adoctor_signed" value="'.$doctor_signed.'" style="width:20px; float:left; display:none;">';	
		$page .= '<input id="Alatest_update" value="'.$latest_update.'" style="width:120px; float:left; display:none;">';
		$page .= '</div>';
		//MODAL WINDOW WITH JAVASCRIPT AT FOOT...
		if($display_type == 0){
		$page .= '<!-- MODAL VIEW FOR ADDITIONAL INFO -->
		<script src="../../build/js/intlTelInput.js"></script>
				
				<script type="text/javascript">
				var additional_info = $("#modalAdditionalInfo").dialog({bgiframe: true, width: 400, height: 450, autoOpen: false, modal: true,title:"'.$this->translations['memberinformation'].'"});
					$("#AdditionalInfo").live("click",function()	{
						//alert("clicked");
						fillAdditionalInfoDialogBox();
						
						additional_info.dialog("open");
					});
					
					function fillAdditionalInfoDialogBox()
					{
						var insurance = $("#inp-Insurance").text();
						if(insurance=="Unknown")
						{
							insurance="";
						}
					
						var address = $("#inp-Address").text();
						if(address=="Unknown")
						{
							address="";
						}
						
						var city = $("#inp-City").text();
						if(city=="Unknown")
						{
							city="";
						}
						
						var zip = $("#inp-Zip").text();
						if(zip=="Unknown")
						{
							zip="";
						}
						
						var gender = $("#inp-Gender").text();
						
						var dob = $("#RAWDOB").val();

						
						var phone = $("#inp-Phone").text();
						if(phone=="Unknown")
						{
							phone="";
						}
						else
						{
							$("#Phone").intlTelInput("setNumber", phone);
						}
						
						var email = $("#inp-Email").text();
						if(email=="Unknown")
						{
							email="";
						}
						
						var blood_type = $("#BLOODTYPE").val();
						var weight = $("#WEIGHT").val();
						var weight_type = $("#WEIGHTTYPE").val();
						if(weight_type == 1)
						{
							weight_type = "lb";
						}
						else
						{
							weight_type = "kg";
						}
						
						var height1 = $("#HEIGHT1").val();
						var height2 = $("#HEIGHT2").val();
						var height_type = $("#HEIGHTTYPE").val();
						if(height_type == 1)
						{
							height_type = "ft";
						}
						else
						{
							height_type = "m";
						}
						if(height_type == "ft")
						{
							$("#height1label").text("ft");
							$("#height2label").text("in");
						}
						else
						{
							$("#height1label").text("m");
							$("#height2label").text("cm");
						}
						
						$("#Insurance").val(insurance);
						$("#Address").val(address);
						$("#City").val(city);
						$("#Zip").val(zip);
						//Phone is handled above
						$("#Email").val(email);
						$("#Gender").val(gender);
						$("#DOB").val(dob);
						$("#blood_type").val(blood_type);
						$("#blood_type").change();
						$("#weight").val(weight);
						$("#weight_type").val(weight_type);
						$("#weight_type").change();
						$("#height1").val(height1);
						$("#height2").val(height2);
						$("#height_type").val(height_type);
						$("#height_type").change();
					
					}
				</script>';
		}
		
		if($display_type == 0){
			return $page;
		}else{
			echo $page;
		}	
	}
	
	public function getMedicationInfo($display_type){
		$query = $this->con->prepare("select * from p_medication where deleted=0 and idpatient=? ORDER BY numDays DESC");
		$query->bindValue(1, $this->passport_id, PDO::PARAM_INT);
		$result = $query->execute();

		$count = $query->rowCount();

		$query2 = $this->con->prepare("select * from p_medication where deleted=0 and drugname='No Medications' and idpatient=? ORDER BY numDays DESC");
		$query2->bindValue(1, $this->passport_id, PDO::PARAM_INT);
		$result2 = $query2->execute();

		$count2 = $query2->rowCount();

		if($display_type == 1){
			$page = '<div id="MedicationInfo">';
		}else{
			$page = '<div id="MedicationInfo" class="grid" class="grid span4" style="cursor:pointer; float:left; overflow:auto;position:relative; height:26.6%; margin-top:1%; margin-left:1%; margin-right:.4%; padding:.5%;width:29.5%;display:table;">';
		}
		if($count2 > 0 && $count != 1){
		$page .= '<center>';
			$page .=  '<table style="background-color:white;" id="TablaPac">';
			$page .= '<div style="width:315px; height:20px; border:0px solid; text-align:center; background-color: #18bc9c; color: white;line-height: 20px;" lang="en">';
			//Medications trans
			$page .= $this->translations['medications'];
			$page .= '</div>';
			$page .= '<tr><td style="background-color:#cacaca; text-align:center;width:315px; height:20px;color:white;"><span lang="en">';
			//No meds trans
			$page .= $this->translations['nomeds'];
			$page .= '</span></td></tr>';
			$page .= '</table>';
			$page .= '</center></div>';
			
			if($display_type == 0){
				return $page;
			}else{
				echo $page;
			}	
		}
		
		if($count==0 && $count2 == 0)
		{
			$page .= '<center>';
			$page .=  '<table style="background-color:white;" id="TablaPac">';
			$page .= '<div style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #18bc9c; color: white;line-height: 20px;" lang="en">';
			//Medication trans
			$page .= $this->translations['medications'];
			$page .= '</div>';
			$page .= '<tr><td><span><center>';
			//No entries trans
			$page .= $this->translations['noentries'];
			$page .= '</center></span><p><center><img width="75px" src="../../images/icons/general_user_error_icon.png" alt="No Data Icon"></center></td></tr>';
			$page .= '</table>';
			$page .= '</div></center>';
			$page .= '<!-- MODAL VIEW FOR MEDICATION -->
				<div id="modalMedication" style="display:none; text-align:center; padding:20px;">
					<div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Medications for </span>'.$this->member_name.' '.$this->member_surname.'</div>
					<div id="deletedMedications"><img class="btn" src="../../images/icons/trashicon.jpg" style="margin-left:25px;border:solid 1px #cacaca;" alt="Deleted Medications" width="15" height="15"></div>
					<div id="MedicationContainer" style="border:solid 1px #cacaca; height:150px; margin-top:30px; padding-top:5px; overflow:auto;">
					</div>
					<div style="border:solid 1px #cacaca; height:140px; margin-top:20px; padding:10px;">
						<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Drugname: </span>
						<input id="field_id" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;"><input id="DrugBox" type="text" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:25px" lang="en" placeholder="Enter Drug Name"><span id="results_count"></span>
						
						<div style="width:100%;"></div>
						<div style="padding:3px;height: 30px; ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px" lang="en">Dose (number of pills): </span>
							<input id="NumberPills" class="numbersOnly" type="text" style="width: 20px;float: left;margin-left: 10px;font-size:14px;height:25px;text-align:center" placeholder="x" />
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left: 10px" lang="en">pills per</span>
							<select id="Frequency" type="text" style="width: 85px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:27px"  />
								<option value="1" lang="en">Day</option>
								<option value="2" lang="en">Week</option>
								<option value="3" lang="en">Month</option>
								<option value="4" lang="en">Year</option>
								
							</select>
						</div>
						<div style="width:100%;"></div>
						<div style="padding:3px;height: 30px;margin-top: 5px ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px" lang="en">How long have you been taking this?: </span>
							<input id="NumberDays" type="text" style="width: 20px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:25px" placeholder="x" />
							<select id="Time" type="text" style="width: 85px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:27px"  />
								<option value="1" lang="en">Days</option>
								<option value="2" lang="en">Weeks</option>
								<option value="3" lang="en">Months</option>
								<option value="4" lang="en">Years</option>
								
							</select>
						</div>
						
						<a id="buttonNoDrugs" style="float:left; text-align:center; margin-left: 3px; margin-top: -5px; width: 150px; font-size:12px; height:17px;" class="btn" lang="en">Not Taking Any Drugs</a>

						<a id="ButtonAddMedication" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-110px;"><span lang="en">Add</span></a>
					</div>
				</div>
				<!--END MEDICATIONS MODAL-->
				<!-- Model for deleted medications -->
				<div id="modalDeletedMedications" style="display:none; text-align:center; padding:20px;">

					<div style="color:#22aeff; font-size:14px; text-align: right; width: 80%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Deleted medications for</span>'.$this->member_name.' '.$this->member_surname.'</div>
					<div><img src="../../images/icons/trashicon.jpg" style="margin-left:25px;" alt="Deleted Medications" width="30" height="30"></div>
					<div id="MedicationContainerDeleted" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
					</div>

				</div>
				<!--END MEDICATIONS DELETED MODAL-->';
				if($display_type == 0){
				$page .= '<script type="text/javascript">
				
					var medications_info = $("#modalMedication").dialog({bgiframe: true, width: 550, autoOpen: false, height: 470, modal: true,title:"'.$this->translations['medications'].'"});
					$("#MedicationInfo").live("click",function()	{
						//alert("clicked");
						getMedications()
						
						medications_info.dialog("open");
					});
					
					$("#buttonNoDrugs").click(function() {

					var r=confirm("This action will delete all previous medications and will prevent future medications from being displayed.  Is this really what you would like to do?");
						 if (r==true)
						 {
						var url = "medicalPassportUnit.php?data_type=createMedication&ajax=false&nomeds=yes&IdUsu='.$this->passport_id.'";
						var Rectipo = LanzaAjax(url);
						getMedications();	
					
						getMedicationInfo();
						}
								
					});
					
					var medications_deleted = $("#modalDeletedMedications").dialog({bgiframe: true, width: 400, height: 400, autoOpen: false, modal: true,title:"'.$this->translations['deletedmedications'].'"});
					$("#deletedMedications").live("click",function()	{
						getMedicationsDeleted();
						medications_deleted.dialog("open");
					  });
					  
					  function getMedications()
						{   
							var MedicationData;
							var queUrl ="medicalPassportUnit.php?data_type=getMedications&IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&ajax=false";
							console.log(queUrl);
							$.ajax(
							{
								url: queUrl,
								dataType: "json",
								async: false,
								success: function(data)
								{
									MedicationData = data.items;
								},
								error: function(xhr, textStatus, errorThrown){
								   alert(errorThrown);
								}
							});
						
							//console.log("MedicationData "+MedicationData);
							numMedications = MedicationData.length;
						
							var n = 0;
							var MedicationBox
							if (numMedications==0)
							{
							var translation51 = "";

							if(language == "th"){
							translation51 = "No se encontraron datos";
							}else if(language == "en"){
							translation51 = "No Data Found";
							}
								MedicationBox="<span>"+translation51+"</span>";
							}
							else
							{
								MedicationBox="";
							}
							
							while (n<numMedications){
								var del = MedicationData[n].deleted;
								var drugname = MedicationData[n].drugname;
								var frequency = MedicationData[n].frequency;
								var dossage = MedicationData[n].dossage;
								var rowid = MedicationData[n].id;	
								if(del==0)
								{
								MedicationBox += "<div class=\"InfoRow\">";
								
								
								var f=1;
								var d=1;
								
								if(frequency=="" || frequency==null)
								{
									f=0;
								}
								
								if(dossage=="" || dossage==null)
								{
									d=0;
								}
								
								var middlecolumn="Unknown";
								
								switch(f)
								{
									case 0:	switch(d)
											{
												case 0:middlecolumn="Unknown";
													   break;
												case 1:middlecolumn=dossage;
													   break;
											}
											break;
									
									case 1:switch(d)
											{
												case 0:middlecolumn=frequency;
													   break;
												case 1:middlecolumn=frequency + " for " + dossage;
													   break;
											}
											break;
								}
								
								
										MedicationBox += "<div style=\"width:160px; float:left; text-align:left;\"><span class=\"PatName\">"+drugname.substr(0,15)+"...</span></div>";
										MedicationBox += "<div style=\"width:170px; float:left; text-align:left; color:#22aeff;white-space: nowrap; overflow: hidden; text-overflow: ellipsis;\"><span class=\"DrName\" style=\"font-size: 12px;\">"+middlecolumn +"</span></div>";
										MedicationBox += "<div class=\"EditMedication\" id="+rowid+" style=\"width:60px; float:right;margin-right:10px;\"><a id="+rowid+" class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:green;\" lang=\"en\">Edit</a></div>";
										MedicationBox += "<div class=\"DeleteMedication\" id="+rowid+" style=\"width:60px; float:right;\"><a id="+rowid+" class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:red;\" lang=\"en\">'.$this->translations['delete'].'</a></div>";
									
								
								MedicationBox += "</div>";
								}
								n++;
							}
							$("#MedicationContainer").html(MedicationBox);
							//console.log(MedicationBox);
						}
						
						function getMedicationInfo()
						{
							var link = "medicalPassportUnit.php?data_type=getMedicationInfo&ajax=true&doc_id='.$this->med_id.'&IdUsu='.$this->passport_id.'";
						
						
							$.ajax({
							   url: link,
							   dataType: "html",
							   async: true,
							   success: function(data)
							   {
									$("#MedicationInfo").html(data);
									var myElement = document.querySelector("#MedicationInfo");
									myElement.style.display = "block";
							   }
							});

						
						}
						
						
						
						$("#ButtonAddMedication").click(function() {
							/*var idPatient = */
							var drugname = $("#DrugBox").val();
							var drugcode = $("#field_id").val();
							var frequency = "";
							var dossage = "";
							var numDays=-1;
							var factor=1;
							
							if(drugname=="")
							{
								alert("Enter Drugname");
								$("#DrugBox").focus();
								return;
								
							}
							
							if($("#NumberPills").val()=="")
							{
							   dossage="";
							}
							else
							{
							var translation5 = "";

							if(language == "th"){
							translation5 = " caps/";
							}else if(language == "en"){
							translation5 = " pills/";
							}
							  dossage=$("#NumberPills").val()+translation5 + getSelectedText("Frequency");
							}
							
							if($("#NumberDays").val()=="")
							{
							   frequency="";
							}
							else
							{
							  var unit = getSelectedText("Time");	
							  frequency=$("#NumberDays").val()+" " + unit;
							  
							  //Calculate number of days this medicine was taken
							  if(unit=="Days")
							  {
								factor=1;
							  }
							  else if(unit=="Weeks")
							  {
								factor=7;
							  }
							  else if(unit=="Months")
							  {
								factor=30;
							  }
							  else if(unit=="Years")
							  {
								factor=365;
							  }
							  numDays=parseInt($("#NumberDays").val())*factor;
									  
							}
							
							var url = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=createMedication&ajax=false&drugname="+drugname+"&drugcode="+drugcode+"&dossage="+dossage+"&frequency="+frequency+"&numDays="+numDays;
							//alert(url);
							//return;
							var Rectipo = LanzaAjax(url);
							console.log(url);
							getMedications();
						
							//clear data
							$("#DrugBox").val("");
							$("#NumberPills").val("");
							$("#NumberDays").val("");
							getMedicationInfo();
									
						});
						
						function getSelectedText(elementId) {
							var elt = document.getElementById(elementId);

							if (elt.selectedIndex == -1)
								return null;

							return elt.options[elt.selectedIndex].text;
						}
					  </script>';
				  }
			if($display_type == 0){
				return $page;
				exit();
			}else{
				echo $page;
				exit();
			}	
		}

		$page .= '<div  style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #18bc9c; color: white;line-height: 20px;" lang="en">';
			//Medications trans
			$page .= $this->translations['medications'];
			$page .= '</div>';

		$page .=  '<table style="background-color:white; margin-top:5px;" id="TablaPac">';

		$latest_update = '1975-01-01 00:00:00';
		$doctor_signed = -1;

		while($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			$numDays = $row['numDays'];
			$drugname = $row['drugname'];
			$dossage = $row['dossage'];
			
			$doctor_signedP = $row['doctor_signed'];
			$latest_updateP = $row['latest_update'];
			if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}
			
			echo '<tr>';
			if($numDays==-1)
			{
				$page .= '<td style="width:30px;height:16px; background-color:'.$this->getColor($numDays).';text-align:center"><span style="color:white;">n/a</span></td>';
			}
			else
			{
				$page .= '<td style="width:30px;height:18px; background-color:'.$this->getColor($numDays).';text-align:center"><span style="color:white;">'.$numDays.'</span></td>';
			}
			
			$page .= '<td><div style="width:150px; float:left; text-align:left;"><span style="margin-left:20px;" class="PatName">'.$drugname.'</span></div></td>';
			$page .= '<td><div style="float:left; text-align:left; color:#22aeff;font-size:10px;margin-left:20px"><span class="DrName" lang="en">'.$dossage.' </span></div></td>';
			$page .= '</tr>';

		}

		$page .= '</table>';

		$page .= '<input id="Mdoctor_signed" value="'.$doctor_signed.'" style="width:20px; float:left; display:none;">';	//display:none;
		$page .= '<input id="Mlatest_update" value="'.$latest_update.'" style="width:120px; float:left; display:none;">';	
		$page .= '</div>';
		//MODAL WINDOW WITH JAVASCRIPT AT FOOT...
		$page .= '<!-- MODAL VIEW FOR MEDICATION -->
				<div id="modalMedication" style="display:none; text-align:center; padding:20px;">
					<div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Medications for </span>'.$this->member_name.' '.$this->member_surname.'</div>
					<div id="deletedMedications"><img class="btn" src="../../images/icons/trashicon.jpg" style="margin-left:25px;border:solid 1px #cacaca;" alt="Deleted Medications" width="15" height="15"></div>
					<div id="MedicationContainer" style="border:solid 1px #cacaca; height:150px; margin-top:30px; padding-top:5px; overflow:auto;">
					</div>
					<div style="border:solid 1px #cacaca; height:140px; margin-top:20px; padding:10px;">
						<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Drugname: </span>
						<input id="field_id" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;"><input id="DrugBox" type="text" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:25px" lang="en" placeholder="Enter Drug Name"><span id="results_count"></span>
						
						<div style="width:100%;"></div>
						<div style="padding:3px;height: 30px; ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px" lang="en">Dose (number of pills): </span>
							<input id="NumberPills" class="numbersOnly" type="text" style="width: 20px;float: left;margin-left: 10px;font-size:14px;height:25px;text-align:center" placeholder="x" />
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left: 10px" lang="en">pills per</span>
							<select id="Frequency" type="text" style="width: 85px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:27px"  />
								<option value="1" lang="en">Day</option>
								<option value="2" lang="en">Week</option>
								<option value="3" lang="en">Month</option>
								<option value="4" lang="en">Year</option>
								
							</select>
						</div>
						<div style="width:100%;"></div>
						<div style="padding:3px;height: 30px;margin-top: 5px ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px" lang="en">How long have you been taking this?: </span>
							<input id="NumberDays" type="text" style="width: 20px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:25px" placeholder="x" />
							<select id="Time" type="text" style="width: 85px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:27px"  />
								<option value="1" lang="en">Days</option>
								<option value="2" lang="en">Weeks</option>
								<option value="3" lang="en">Months</option>
								<option value="4" lang="en">Years</option>
								
							</select>
						</div>
						
						<a id="buttonNoDrugs" style="float:left; text-align:center; margin-left: 3px; margin-top: -5px; width: 150px; font-size:12px; height:25px;" class="btn" lang="en">Not Taking Any Drugs</a>

						<a id="ButtonAddMedication" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-130px;"><span lang="en">Add</span></a>
					</div>
				</div>
				<!--END MEDICATIONS MODAL-->
				<!-- Model for deleted medications -->
				<div id="modalDeletedMedications" style="display:none; text-align:center; padding:20px;">

					<div style="color:#22aeff; font-size:14px; text-align: right; width: 80%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Deleted medications for </span>'.$this->member_name.' '.$this->member_surname.'</div>
					<div><img src="../../images/icons/trashicon.jpg" style="margin-left:25px;" alt="Deleted Medications" width="30" height="30"></div>
					<div id="MedicationContainerDeleted" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
					</div>

				</div>
				<!--END MEDICATIONS DELETED MODAL-->
				<!-- MODAL VIEW FOR EDITING MEDICATION -->
				<div id="editMedications" style="display:none; text-align:center; padding:20px;">
					<div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Edit Medications for </span>'.$this->member_name.' '.$this->member_surname.'</div>
					<div id="MedicationContainerEdit" style="border:solid 1px #cacaca; height:80px; margin-top:30px; padding-top:5px; overflow:auto;">
					</div>
					<div style="border:solid 1px #cacaca; height:140px; margin-top:20px; padding:10px;">
						<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Drugname: </span>
						<input id="field_id" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;"><input id="DrugBox2" type="text" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:25px" lang="en" placeholder="Enter Drug Name"><span id="results_count"></span>
						
						<div style="width:100%;"></div>
						<div style="padding:3px;height: 30px; ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px" lang="en">Dose (number of pills): </span>
							<input id="NumberPills2" class="numbersOnly" type="text" style="width: 20px;float: left;margin-left: 10px;font-size:14px;height:25px;text-align:center" placeholder="x" />
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left: 10px" lang="en">pills per</span>
							<select id="Frequency2" type="text" style="width: 85px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:27px"  />
								<option value="1" lang="en">Day</option>
								<option value="2" lang="en">Week</option>
								<option value="3" lang="en">Month</option>
								<option value="4" lang="en">Year</option>
								
							</select>
						</div>
						<div style="width:100%;"></div>
						<div style="padding:3px;height: 30px;margin-top: 5px ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px" lang="en">How long have you been taking this?: </span>
							<input id="NumberDays2" type="text" style="width: 20px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:25px" placeholder="x" />
							<select id="Time2" type="text" style="width: 85px;float: left;margin-left: 10px;text-align:center;font-size:14px;height:27px"  />
								<option value="1" lang="en">Days</option>
								<option value="2" lang="en">Weeks</option>
								<option value="3" lang="en">Months</option>
								<option value="4" lang="en">Years</option>
								
							</select>
						</div>
						

						<a id="ButtonEditMedication" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-130px;"><span lang="en">Edit</span></a>
					</div>
				</div>
				<!-- END MODAL VIEW FOR EDITING MEDICATION -->';
				if($display_type == 0){
				$page .= '<script type="text/javascript">
				
					var medications_info = $("#modalMedication").dialog({bgiframe: true, width: 550, autoOpen: false, height: 470, modal: true,title:"'.$this->translations['medications'].'"});
					$("#MedicationInfo").live("click",function()	{
						//alert("clicked");
						getMedications()
						
						medications_info.dialog("open");
					});
					
					$("#buttonNoDrugs").click(function() {

					var r=confirm("This action will delete all previous medications and will prevent future medications from being displayed.  Is this really what you would like to do?");
						 if (r==true)
						 {
						var url = "medicalPassportUnit.php?data_type=createMedication&ajax=false&nomeds=yes&IdUsu='.$this->passport_id.'";
						var Rectipo = LanzaAjax(url);
						getMedications();	
					
						getMedicationInfo();
						}
								
					});
					
					var medications_deleted = $("#modalDeletedMedications").dialog({bgiframe: true, width: 400, height: 400, autoOpen: false, modal: true,title:"'.$this->translations['deletedmedications'].'"});
					$("#deletedMedications").live("click",function()	{
						getMedicationsDeleted();
						medications_deleted.dialog("open");
					  });
					  
					  $(".DeleteMedication").live("click",function(){
							var myClass = $(this).attr("id"); 
							//alert (myClass);
							var cadena="medicalPassportUnit.php?data_type=deleteMedications&ajax=false&doc_id='.$this->med_id.'&id="+myClass;
							var RecTipo=LanzaAjax(cadena);
							getMedications();
							getMedicationInfo();

						});
						
					function getMedicationInfo()
						{
							var link = "medicalPassportUnit.php?data_type=getMedicationInfo&ajax='.$_GET['ajax'].'&doc_id='.$this->med_id.'&IdUsu='.$this->passport_id.'";
						
						
							$.ajax({
							   url: link,
							   dataType: "html",
							   async: true,
							   success: function(data)
							   {
									$("#MedicationInfo").html(data);
									var myElement = document.querySelector("#MedicationInfo");
									myElement.style.display = "block";
							   }
							});

						
						}
					
					function getMedications()
					{   
						var MedicationData;
						var queUrl ="medicalPassportUnit.php?data_type=getMedications&IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&ajax=false";
						console.log(queUrl);
						$.ajax(
						{
							url: queUrl,
							dataType: "json",
							async: false,
							success: function(data)
							{
								MedicationData = data.items;
							},
							error: function(xhr, textStatus, errorThrown){
							   alert(errorThrown);
							}
						});
					
						//console.log("MedicationData "+MedicationData);
						numMedications = MedicationData.length;
					
						var n = 0;
						var MedicationBox
						if (numMedications==0)
						{
						var translation51 = "";

						if(language == "th"){
						translation51 = "No se encontraron datos";
						}else if(language == "en"){
						translation51 = "No Data Found";
						}
							MedicationBox="<span>"+translation51+"</span>";
						}
						else
						{
							MedicationBox="";
						}
						
						while (n<numMedications){
							var del = MedicationData[n].deleted;
							var drugname = MedicationData[n].drugname;
							var frequency = MedicationData[n].frequency;
							var dossage = MedicationData[n].dossage;
							var rowid = MedicationData[n].id;	
							if(del==0)
							{
							MedicationBox += "<div class=\"InfoRow\">";
							
							
							var f=1;
							var d=1;
							
							if(frequency=="" || frequency==null)
							{
								f=0;
							}
							
							if(dossage=="" || dossage==null)
							{
								d=0;
							}
							
							var middlecolumn="Unknown";
							
							switch(f)
							{
								case 0:	switch(d)
										{
											case 0:middlecolumn="Unknown";
												   break;
											case 1:middlecolumn=dossage;
												   break;
										}
										break;
								
								case 1:switch(d)
										{
											case 0:middlecolumn=frequency;
												   break;
											case 1:middlecolumn=frequency + " for " + dossage;
												   break;
										}
										break;
							}
							
							
									MedicationBox += "<div style=\"width:160px; float:left; text-align:left;\"><span class=\"PatName\">"+drugname.substr(0,15)+"...</span></div>";
									MedicationBox += "<div style=\"width:170px; float:left; text-align:left; color:#22aeff;white-space: nowrap; overflow: hidden; text-overflow: ellipsis;\"><span class=\"DrName\" style=\"font-size: 12px;\">"+middlecolumn +"</span></div>";
									MedicationBox += "<div class=\"EditMedication\" id="+rowid+" style=\"width:60px; float:right;margin-right:10px;\"><a id="+rowid+" class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:green;\" lang=\"en\">Edit</a></div>";
									MedicationBox += "<div class=\"DeleteMedication\" id="+rowid+" style=\"width:60px; float:right;\"><a id="+rowid+" class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:red;\" lang=\"en\">'.$this->translations['delete'].'</a></div>";
								
							
							MedicationBox += "</div>";
							}
							n++;
						}
						$("#MedicationContainer").html(MedicationBox);
						//console.log(MedicationBox);
					}
					
					
					function getMedicationsDeleted()
					{
						var queUrl ="medicalPassportUnit.php?data_type=getMedications&IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&ajax=false";
						$.ajax(
						{
							url: queUrl,
							dataType: "json",
							async: false,
							success: function(data)
							{
								MedicationData = data.items;
							}
						});
					
						numMedications = MedicationData.length;
					
						var n = 0;
						var MedicationBox
						if (numMedications==0)
						{
						var translation51 = "";

						if(language == "th"){
						translation51 = "No se encontraron datos";
						}else if(language == "en"){
						translation51 = "No Data Found";
						}
							MedicationBox="<span>"+translation51+"</span>";
						}
						else
						{
							MedicationBox="";
						}
						
						while (n<numMedications){
							var del = MedicationData[n].deleted;
							var drugname = MedicationData[n].drugname;
							var frequency = MedicationData[n].frequency;
							var dossage = MedicationData[n].dossage;
							var rowid = MedicationData[n].id;	
							if(del==1)
							{
							MedicationBox += "<div class=\"InfoRow\">";
							
							
							var f=1;
							var d=1;
							
							if(frequency=="" || frequency==null)
							{
								f=0;
							}
							
							if(dossage=="" || dossage==null)
							{
								d=0;
							}
							
							var middlecolumn="Unknown";
							
							switch(f)
							{
								case 0:	switch(d)
										{
											case 0:middlecolumn="Unknown";
												   break;
											case 1:middlecolumn=dossage;
												   break;
										}
										break;
								
								case 1:switch(d)
										{
											case 0:middlecolumn=frequency;
												   break;
											case 1:middlecolumn=frequency + " for " + dossage;
												   break;
										}
										break;
							}
							
							
									MedicationBox += "<div style=\"width:100px; float:left; text-align:left;\"><span class=\"PatName\"><font size=\"0\">"+drugname+"</font></span></div>";
									MedicationBox += "<div style=\"width:150px; float:left; text-align:left; color:#22aeff;\"><span class=\"DrName\"><font size=\"0\">"+middlecolumn +" </font></span></div>";
									//MedicationBox += "<div class=\"DeleteMedication\" id="+rowid+" style=\"width:60px; float:right;\"><a id="+rowid+"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:red;\">'.$this->translations['delete'].'</a></div>";
								
							
							MedicationBox += "</div>";
							}
							n++;
						}
						$("#MedicationContainerDeleted").html(MedicationBox);
					
					}
					
					$("#ButtonAddMedication").click(function() {
						/*var idPatient = */
						var drugname = $("#DrugBox").val();
						var drugcode = $("#field_id").val();
						var frequency = "";
						var dossage = "";
						var numDays=-1;
						var factor=1;
						
						if(drugname=="")
						{
							alert("Enter Drugname");
							$("#DrugBox").focus();
							return;
							
						}
						
						if($("#NumberPills").val()=="")
						{
						   dossage="";
						}
						else
						{
						var translation5 = "";

						if(language == "th"){
						translation5 = " caps/";
						}else if(language == "en"){
						translation5 = " pills/";
						}
						  dossage=$("#NumberPills").val()+translation5 + getSelectedText("Frequency");
						}
						
						if($("#NumberDays").val()=="")
						{
						   frequency="";
						}
						else
						{
						  var unit = getSelectedText("Time");	
						  frequency=$("#NumberDays").val()+" " + unit;
						  
						  //Calculate number of days this medicine was taken
						  if(unit=="Days")
						  {
							factor=1;
						  }
						  else if(unit=="Weeks")
						  {
							factor=7;
						  }
						  else if(unit=="Months")
						  {
							factor=30;
						  }
						  else if(unit=="Years")
						  {
							factor=365;
						  }
						  numDays=parseInt($("#NumberDays").val())*factor;
								  
						}
						
						var url = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=createMedication&ajax=true&drugname="+drugname+"&drugcode="+drugcode+"&dossage="+dossage+"&frequency="+frequency+"&numDays="+numDays;
						//alert(url);
						//return;
						var Rectipo = LanzaAjax(url);
						console.log(url);
						getMedications();
					
						//clear data
						$("#DrugBox").val("");
						$("#NumberPills").val("");
						$("#NumberDays").val("");
						getMedicationInfo();
								
					});
					
					$(".EditMedication").live("click",function(){
					var translation22 = "medications eddit";
						var myClass = $(this).attr("id"); 
						//alert (myClass);
						$("#editMedications").dialog({bgiframe: true, width: 550, height: 380, modal: true,title:translation22});
						getMedicationsEdit(myClass);
					});
					
					function getMedicationsEdit(toShow)
					{
						var queUrl ="medicalPassportUnit.php?data_type=getMedications&IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&ajax=false";
						$.ajax(
						{
							url: queUrl,
							dataType: "json",
							async: false,
							success: function(data)
							{
								MedicationData = data.items;
							}
						});
					
						numMedications = MedicationData.length;
						var displayRow = $("#DiagnosticRow2").val(toShow);
						var n = 0;
						var MedicationBox
						if (numMedications==0)
						{
						var translation51 = "";

						if(language == "th"){
						translation51 = "No se encontraron datos";
						}else if(language == "en"){
						translation51 = "No Data Found";
						}
							MedicationBox="<span>"+translation51+"</span>";
						}
						else
						{
							MedicationBox="";
						}
						
						while (n<numMedications){
							var del = MedicationData[n].deleted;
							var drugname = MedicationData[n].drugname;
							var frequency = MedicationData[n].frequency;
							var dossage = MedicationData[n].dossage;
							var rowid = MedicationData[n].id;	
							
							
							
							if(del==0)
							{
							if(rowid == toShow){
							MedicationBox += "<div class=\"InfoRow\">";
							$("#DrugBox2").val(drugname);
							
							var f=1;
							var d=1;
							
							if(frequency=="" || frequency==null)
							{
								f=0;
							}
							
							if(dossage=="" || dossage==null)
							{
								d=0;
							}
							
							var middlecolumn="Unknown";
							
							switch(f)
							{
								case 0:	switch(d)
										{
											case 0:middlecolumn="Unknown";
												   break;
											case 1:middlecolumn=dossage;
												   break;
										}
										break;
								
								case 1:switch(d)
										{
											case 0:middlecolumn=frequency;
												   break;
											case 1:middlecolumn=frequency + " for " + dossage;
												   break;
										}
										break;
							}
							translatione1 = "";
								if(language == "th"){
						translatione1 = "Edición";
						}else if(language == "en"){
						translatione1 = "Editing";
						}
							
									MedicationBox += "<div style=\"width:180px; float:left; text-align:left;\"><span class=\"PatName\">"+drugname+"</span></div>";
									MedicationBox += "<div style=\"width:180px; float:left; text-align:left; color:#22aeff;\"><span class=\"DrName\">"+middlecolumn +" </span></div>";
									MedicationBox += "<div class=\"DeleteMedication\" id="+rowid+" style=\"width:60px; float:left;\">"+translatione1+"</div>";
								
							
							MedicationBox += "</div>";
							}
							}
							n++;
						}
						$("#MedicationContainerEdit").html(MedicationBox);
						var rowtrack = $("#DiagnosticRow2").val(toShow);
					
					}
					
					$("#ButtonEditMedication").click(function() {
						/*var idPatient = */
						var idrow = $("#DiagnosticRow2").val();
						var drugname = $("#DrugBox2").val();
						var drugcode = $("#field_id2").val();
						var frequency = "";
						var dossage = "";
						var numDays=-1;
						var factor=1;
				//		alert(idrow);
						
						if(drugname=="")
						{
							alert("Enter Drugname");
							$("#DrugBox2").focus();
							return;
							
						}
						
						if($("#NumberPills2").val()=="")
						{
						   dossage="";
						}
						else
						{
						  dossage=$("#NumberPills2").val()+" pills/" + getSelectedText("Frequency");
						}
						
						if($("#NumberDays2").val()=="")
						{
						   frequency="";
						}
						else
						{
						  var unit = getSelectedText("Time");	
						  frequency=$("#NumberDays2").val()+" " + unit;
						  
						  //Calculate number of days this medicine was taken
						  if(unit=="Days")
						  {
							factor=1;
						  }
						  else if(unit=="Weeks")
						  {
							factor=7;
						  }
						  else if(unit=="Months")
						  {
							factor=30;
						  }
						  else if(unit=="Years")
						  {
							factor=365;
						  }
						  numDays=parseInt($("#NumberDays2").val())*factor;
								  
						}
						
						var url = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=createMedication&ajax=false&drugname="+drugname+"&drugcode="+drugcode+"&dossage="+dossage+"&frequency="+frequency+"&numDays="+numDays+"&rowediter="+idrow;
						//alert(url);
						//return;
						var Rectipo = LanzaAjax(url);
						//console.log(url);
						getMedications();
					
						//clear data
						$("#DrugBox2").val("");
						$("#NumberPills2").val("");
						$("#NumberDays2").val("");
						getMedicationInfo();
						$("#editMedications").dialog("close");		
					});
					
					function getSelectedText(elementId) {
						var elt = document.getElementById(elementId);

						if (elt.selectedIndex == -1)
							return null;

						return elt.options[elt.selectedIndex].text;
					}
				</script>';
		}
		
		if($display_type == 0){
			return $page;
		}else{
			echo $page;
		}	
	}
	
	public function getFamilyHistoryInfo($display_type){
		$query = $this->con->prepare("SELECT * FROM p_family WHERE idpatient=?");
		$query->bindValue(1, $this->passport_id, PDO::PARAM_INT);
		$result = $query->execute();

		$count = $query->rowCount();
		$counter1 = 0 ;
		$cadena = '';
		$latest_update = '1975-01-01 00:00:00';
		$doctor_signed = -1;

		while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
		if ($row2['deleted'] == 0)
			{
			
				$Id[$counter1] = $row2['id'];
				$relative[$counter1] = (htmlspecialchars($row2['relative']));
				$relativetype[$counter1] = (htmlspecialchars($row2['relativetype']));
				$disease[$counter1] = (htmlspecialchars($row2['disease']));
				$diseasegroup[$counter1] = (htmlspecialchars($row2['diseasegroup']));
				$ICD10[$counter1] = (htmlspecialchars($row2['ICD10']));
				$ICD9[$counter1] = (htmlspecialchars($row2['ICD9']));
				$atage[$counter1] = (htmlspecialchars($row2['atage']));
				$alive[$counter1] = (htmlspecialchars($row2['alive']));
				$deleted[$counter1] = $row2['deleted'];
				
				$doctor_signedP = (htmlspecialchars($row2['doctor_signed']));
				$latest_updateP = (htmlspecialchars($row2['latest_update']));
				if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}

			
				$counter1++;
			}
		}
		$this->counter = $counter1;
		$this->relative_type = $relativetype;
		$query = $this->con->prepare("SELECT * FROM usuarios WHERE Identif=?");
		$query->bindValue(1, $this->passport_id, PDO::PARAM_INT);
		$result = $query->execute();


		while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
			$Gender = (htmlspecialchars($row2['Sexo']));
		}
		
		if($display_type == 1){
			$page = '<center><div id="FamilyHistoryInfo">';
		}else{
			$page = '<center><div id="FamilyHistoryInfo" class="grid" class="grid span4" style="cursor:pointer; position:relative; float:left;height:24%; margin-top:1%; margin-left:.4%;margin-right:.4%; padding:.5%;width:39.3%;display:table">';
		}

		$page .= '<style>';
		$page .= 'div.FamRow{';
		$page .= '  width:420px; height:35px; background-color:white; text-align:center; display:table-cell; vertical-align:middle;';
		$page .= '  } ';
		$page .= 'icon.si{';
		$page .= '  display: inline-block; vertical-align: -webkit-baseline-middle; font-size: 18px; color:black;';
		$page .= '  } ';
		$page .= 'div.DiLabel{';
		$page .= '  float:left; width: 30px; height:10px; line-height:8px; font-size:10px; background-color:red; color:white; text-align:center; margin-top:1px;';
		$page .= '  } ';
		$page .= 'div.DiLabelContainer{';
		$page .= '  float:left; width: 30px; height:30px; border:0px solid #cacaca; margin-left:-5px;';
		$page .= '  } ';
		$page .= 'div.G{';
		$page .= '  background-color:green;';
		$page .= '  } ';
		$page .= 'div.Female{';
		$page .= '  float:left; margin:0 auto; border:1px solid #cacaca; width:20px; height:30px; border-radius:15px;';
		$page .= '  } ';
		$page .= 'div.Male{';
		$page .= '  float:left; margin:0 auto; border:1px solid #cacaca; width:20px; height:30px;';
		$page .= '  } ';
		$page .= 'div.ML{';
		$page .= '  border-color:#54bc00;';
		$page .= '  } ';
		$page .= 'div.PL{';
		$page .= '  border-color:#22aeff;';
		$page .= '  } ';
		$page .= 'icon.ia{';
		$page .= '  color:#e3e3e3;';
		$page .= '  } ';
		$page .= 'div.ia{';
		$page .= '  border-color:#e3e3e3;';
		$page .= '  } ';
		$page .= 'div.separator{';
		$page .= '  float:left; height:100%; margin-left:4px;';
		$page .= '  } ';
		$page .= 'div.separatorGroup{';
		$page .= '  float:left; height:100%; margin-left:50px;';
		$page .= '  } ';
		$page .= '</style>';

		$page .= '				<div id="FamilyInnerL" style="float:left; width:100%; height:100%; border:0px solid;">';
		$page .= '				<div  style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #e74c3c; color: white;line-height: 20px;">';
			//Family history trans
			$page .= $this->translations['famhistory'];
			$page .= '</div>';
		$page .= '				<div id="TableContainer" style="width:365px; margin-top:15px; border:0px solid #cacaca;">';
		$page .= '					<table style="margin-top:5px; background-color:white;">';
		// Grandparents Line
		$page .= '						<tr style="padding:0px; margin:0px;" valign="middle">';
		$page .= '							<td><div class="FamRow" style="">';
		// Calculate dimension of grandparent box
		$labelcount = 0;
		$mgm = $this->check('4');
		if ($mgm[0] > -1) $labelcount++;
		$mgp = $this->check('3');
		if ($mgp[0] > -1) $labelcount++;

		$labelcount2 = 0;
		$pgm = $this->check('8');
		if ($pgm[0] > -1) $labelcount2++;
		$pgp = $this->check('7');
		if ($pgp[0] > -1) $labelcount2++;

		$boxsize1 = (20*2) + (2*2) + 4 + (35*$labelcount) ;
		$boxsize2 = (20*2) + (2*2) + 4 + (35*$labelcount2) ;
		$bloksize =  50 + ($boxsize1 + $boxsize2);





		$page .= '								<div style="margin:0 auto; width:'.$bloksize.'px; height: 100%;">';

		//   	Maternal GRANDPARENTS
		if ($mgm[0] == -1){

		$subn = 0;
							while ($subn < sizeof($mgm)) {
							if(isset($alive)){
							if($alive[$mgm[$subn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							}else{
							$death_color = "background-color: white;";
							}
							$subn++;
							}

			$page .= '		    						<div style="'.$death_color.'" class="Female ML ia" style=""><icon class="icon-female si ia"></div>';
		}else 		{

							$subn = 0;
							while ($subn < sizeof($mgm)) {
							if(isset($alive)){
							if($alive[$mgm[$subn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							}else{
							$death_color = "background-color: white;";
							}
							$subn++;
							}
			
							$page .= '									<div style="'.$death_color.'" class="Female ML" style=""><icon class="icon-female si"></div>';
							$page .= '									<div class="DiLabelContainer">';
							$subn = 0;
							while ($subn < sizeof($mgm)) {  $page .= '<div class="DiLabel" style="background-color:'.$this->SpeColor($diseasegroup[$mgm[$subn]]).'" title="'.$disease[$mgm[$subn]].' @ '.$atage[$mgm[$subn]].'">'.substr($disease[$mgm[$subn]],0,5).'</div> ';  $subn++;}
							$page .= '													</div>';
					};
		$page .= '														<div class="separator"></div>';
		if ($mgp[0] == -1){ 	

		$subn = 0;
							while ($subn < sizeof($mgp)) {
							if(isset($alive)){
							if($alive[$mgp[$subn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							}else{
							$death_color = "background-color: white;";
							}
							$subn++;
							}


		$page .= '									<div class="Male ML ia" style="'.$death_color.'"><icon class="icon-male si ia"></div>';
		}else 		{

		$subn = 0;
							while ($subn < sizeof($mgp)) {
							if(isset($alive)){
							if($alive[$mgp[$subn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							}else{
							$death_color = "background-color: white;";
							}
							$subn++;
							}

			
							$page .= '									<div class="Male ML" style="'.$death_color.'"><icon class="icon-male si"></div>';
							$page .= '									<div class="DiLabelContainer">';
							$subn = 0;
							while ($subn < sizeof($mgp)) {  $page .= '<div class="DiLabel" style="background-color:'.$this->SpeColor($diseasegroup[$mgp[$subn]]).'" title="'.$disease[$mgp[$subn]].' @ '.$atage[$mgp[$subn]].'">'.substr($disease[$mgp[$subn]],0,5).'</div> ';  $subn++;}
							$page .= '													</div>';
					};

		$page .= '									<div class="separatorGroup"></div>	';
		//   	Paternal GRANDPARENTS
		if ($pgm[0] == -1){ 

		$subn = 0;
							while ($subn < sizeof($pgm)) {
							if(isset($alive)){
							if($alive[$pgm[$subn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							}else{
							$death_color = "background-color: white;";
							}
							$subn++;
							}

			$page .= '		    						<div class="Female PL ia" style="'.$death_color.'"><icon class="icon-female si ia"></div>';
		}else 		{	

		$subn = 0;
							while ($subn < sizeof($pgm)) {
							if(isset($alive)){
							if($alive[$pgm[$subn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							}else{
							$death_color = "background-color: white;";
							}
							$subn++;
							}

							$page .= '									<div class="Female PL" style="'.$death_color.'"><icon class="icon-female si"></div>';
							$page .= '									<div class="DiLabelContainer">';
							$subn = 0;
							while ($subn < sizeof($pgm)) {  $page .= '<div class="DiLabel" style="background-color:'.$this->SpeColor($diseasegroup[$pgm[$subn]]).'" title="'.$disease[$pgm[$subn]].' @ '.$atage[$pgm[$subn]].'">'.substr($disease[$pgm[$subn]],0,5).'</div> ';  $subn++;}
							$page .= '													</div>';
					};
		$page .= '														<div class="separator"></div>';
		if ($pgp[0] == -1){

		$subn = 0;
							while ($subn < sizeof($pgp)) {
							if(isset($alive)){
							if($alive[$pgp[$subn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							}else{
							$death_color = "background-color: white;";
							}
							$subn++;
							}

			$page .= '									<div class="Male PL ia" style="'.$death_color.'"><icon class="icon-male si ia"></div>';
		}else 		{

		$subn = 0;
							while ($subn < sizeof($pgp)) {
							if(isset($alive)){
							if($alive[$pgp[$subn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							}else{
							$death_color = "background-color: white;";
							}
							$subn++;
							}
			
							$page .= '									<div class="Male PL" style="'.$death_color.'"><icon class="icon-male si"></div>';
							$page .= '									<div class="DiLabelContainer">';
							$subn = 0;
							while ($subn < sizeof($pgp)) {  $page .= '<div class="DiLabel" style="background-color:'.$this->SpeColor($diseasegroup[$pgp[$subn]]).'" title="'.$disease[$pgp[$subn]].' @ '.$atage[$pgp[$subn]].'">'.substr($disease[$pgp[$subn]],0,5).'</div> ';  $subn++;}
							$page .= '													</div>';
					};

		$page .= '								</div>';
		$page .= '							</div></td>';
		$page .= '						</tr>';
		// End of Grandparents Line



		// Parents Line
		$page .= '						<tr style="padding:0px; margin:0px;" valign="middle">';
		$page .= '							<td><div class="FamRow" >';

		// Maternal Uncles/Aunts 
		// Calculate dimension of Maternal U/A box
		$labelcount = 0;
		$labelcountU = 0;
		$labelcountA = 0;
		$mU = $this->check('5');
		if ($mU[0] > -1) $labelcountU = sizeof($mU);
		$mA = $this->check('6');
		if ($mA[0] > -1) $labelcountA = sizeof($mA);
		$labelcount = $labelcountU + $labelcountA;
		$labelcount = 2; // Override calculation to set a fixed structure until we figure out how to make it better
		//			BOX      				Border  	  			margin					LABEL
		$boxsize1 = (20*$labelcount) + (2*$labelcount) +    (4*($labelcount-1)) +       (35*$labelcount) ;
		$bloksize =  $boxsize1;
		$page .= '								<div style="float:left; margin:0 auto; width:'.$bloksize.'px; height: 100%; style="overflow:auto;"">';
		$nn = 0;
		while ($nn < $labelcountU)
		{	

							if($alive[$mU[$nn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							
			$page .= '<div class="Male ML" style="'.$death_color.'"><icon class="icon-male si"></div>';
			$page .= '<div class="DiLabelContainer">';
			$page .= '	<div class="DiLabel" style="background-color:'.$this->SpeColor($diseasegroup[$mU[$nn]]).'" title="'.$disease[$mU[$nn]].' @ '.$atage[$mU[$nn]].'">'.substr($disease[$mU[$nn]],0,5).'</div> '; 
			$page .= '</div>';
			$nn++;
		}
		$nn = 0;
		while ($nn < $labelcountA)
		{	

							if($alive[$mA[$nn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}

			$page .= '<div class="Female ML" style="'.$death_color.'"><icon class="icon-female si"></div>';
			$page .= '<div class="DiLabelContainer">';
			$page .= '	<div class="DiLabel" style="background-color:'.$this->SpeColor($diseasegroup[$mA[$nn]]).'" title="'.$disease[$mA[$nn]].' @ '.$atage[$mA[$nn]].'">'.substr($disease[$mA[$nn]],0,5).'</div> '; 
			$page .= '</div>';
			$nn++;
		}
		$page .= '								</div>';


		// Calculate dimension of Parents box
		$labelcount = 0;
		$mo = $this->check('2');
		if ($mo[0] > -1) $labelcount++;
		$po = $this->check('1');
		if ($po[0] > -1) $labelcount++;
		$boxsize1 = (20*2) + (2*2) + 4 + (35*$labelcount) ;
		$bloksize =  $boxsize1;
		$page .= '								<div style="float:left; margin:0 auto; width:'.$bloksize.'px; height: 100%;">';
		//   	MOTHER
		if ($mo[0] == -1){

		$subn = 0;
							while ($subn < sizeof($mo)) {
							if(isset($alive)){
							if($alive[$mo[$subn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							}else{
							$death_color = "background-color: white;";
							}
							$subn++;
							}

			$page .= '		    						<div class="Female ML ia" style="'.$death_color.'"><icon class="icon-female si ia"></div>';
		}else 		{	

		$subn = 0;
		while ($subn < sizeof($mo)) {
							if($alive[$mo[$subn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							$subn++;
							}

							$page .= '									<div class="Female ML" style="'.$death_color.'"><icon class="icon-female si"></div>';
							$page .= '									<div class="DiLabelContainer">';
							$subn = 0;
							while ($subn < sizeof($mo)) {  $page .= '<div class="DiLabel" style="background-color:'.$this->SpeColor($diseasegroup[$mo[$subn]]).'" title="'.$disease[$mo[$subn]].' @ '.$atage[$mo[$subn]].'">'.substr($disease[$mo[$subn]],0,5).'</div> ';  $subn++;}
							$page .= '													</div>';
					};
		$page .= '														<div class="separator"></div>';
		if ($po[0] == -1){

		$subn = 0;
		while ($subn < sizeof($po)) {
							if(isset($alive)){
							if($alive[$po[$subn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							}else{
							$death_color = "background-color: white;";
							}
							$subn++;
							}

			$page .= '									<div class="Male PL ia" style="'.$death_color.'"><icon class="icon-male si ia"></div>';
		}else 		{	

		$subn = 0;
		while ($subn < sizeof($po)) {
							if($alive[$po[$subn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							$subn++;
							}

							$page .= '									<div class="Male PL" style="'.$death_color.'"><icon class="icon-male si"></div>';
							$page .= '									<div class="DiLabelContainer">';
							$subn = 0;
							while ($subn < sizeof($po)) {  $page .= '<div class="DiLabel" style="background-color:'.$this->SpeColor($diseasegroup[$po[$subn]]).'" title="'.$disease[$po[$subn]].' @ '.$atage[$po[$subn]].'">'.substr($disease[$po[$subn]],0,5).'</div> ';  $subn++;}
							$page .= '													</div>';
					};
		$page .= '								</div>';
		// Paternal Uncles/Aunts 
		// Calculate dimension of Paternal U/A box

		$labelcount = 0;
		$labelcountU = 0;
		$labelcountA = 0;
		$mU = $this->check('9');
		if ($mU[0] > -1) $labelcountU = sizeof($mU);
		$mA = $this->check('10');
		if ($mA[0] > -1) $labelcountA = sizeof($mA);
		$labelcount = $labelcountU + $labelcountA;
		$labelcount = 2; // Override calculation to set a fixed structure until we figure out how to make it better
		$boxsize1 = (20*$labelcount) + (2*$labelcount) +    (4*($labelcount-1)) +       (35*$labelcount) ;
		$bloksize =  $boxsize1;
		$page .= '								<div style="float:left; margin:0 auto; width:'.$bloksize.'px; height: 100%;">';
		$nn = 0;
		while ($nn < $labelcountU)
		{	
		if($alive[$mU[$nn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}

			$page .= '<div class="Male PL" style="'.$death_color.'"><icon class="icon-male si"></div>';
			$page .= '<div class="DiLabelContainer">';
			$page .= '	<div class="DiLabel" style="background-color:'.$this->SpeColor($diseasegroup[$mU[$nn]]).'" title="'.$disease[$mU[$nn]].' @ '.$atage[$mU[$nn]].'">'.substr($disease[$mU[$nn]],0,5).'</div> '; 
			$page .= '</div>';
			$nn++;
		}
		$nn = 0;
		while ($nn < $labelcountA)
		{	
		if($alive[$mU[$nn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}

			$page .= '<div class="Female PL" style="'.$death_color.'"><icon class="icon-female si"></div>';
			$page .= '<div class="DiLabelContainer">';
			$page .= '	<div class="DiLabel" style="background-color:'.$this->SpeColor($diseasegroup[$mA[$nn]]).'" title="'.$disease[$mA[$nn]].' @ '.$atage[$mA[$nn]].'">'.substr($disease[$mA[$nn]],0,5).'</div> '; 
			$page .= '</div>';
			$nn++;
		}
		$page .= '								</div>';


		$page .= '							</div></td>';
		$page .= '						</tr>';
		// End of Parents Line
		// Own Generation Line
		$page .= '						<tr style="padding:0px; margin:0px;" valign="middle">';
		$page .= '							<td><div class="FamRow" style=";">';
		// SISTERS
		// Calculate dimension of SISTERS box
		$labelcount = 0;
		$labelcountS = 0;
		$mS = $this->check('12');
		if ($mS[0] > -1) $labelcountS = sizeof($mS);
		$labelcount = 2; // Override calculation to set a fixed structure until we figure out how to make it better
		$boxsize1 = (20*$labelcount) + (2*$labelcount) +    (4*($labelcount-1)) +       (35*$labelcount) ;
		$bloksize =  $boxsize1;
		$page .= '								<div style="float:left; margin:0 auto; width:'.$bloksize.'px; height: 100%;">';
		$nn = 0;
		while ($nn < $labelcountS)
		{	

		if($alive[$mS[$nn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
			$page .= '<div class="Female" style="'.$death_color.'"><icon class="icon-female si"></div>';
			$page .= '<div class="DiLabelContainer">';
			$page .= '	<div class="DiLabel" style="background-color:'.$this->SpeColor($diseasegroup[$mS[$nn]]).'" title="'.$disease[$mS[$nn]].' @ '.$atage[$mS[$nn]].'">'.substr($disease[$mS[$nn]],0,5).'</div> '; 
			$page .= '</div>';
			$nn++;
		}
		$page .= '								</div>';
		// SISTERS
		// ME
		$boxsize1 = 20;
		$bloksize =  $boxsize1;
		//Me trans
		$holder = $this->translations['me'];
		$page .= '								<div style="float:left; margin:0 auto; margin-left:25px; width:'.$bloksize.'px; height: 100%;">';
		if ($Gender == 0)  $page .= '<div class="Female" style=""><icon class="icon-female si"></div>'; else  $page .= '<div class="Male" style=""><icon class="icon-male si"></div><div class="DiLabelContainer" style="height:10px;"><div class="DiLabel" style="background-color:grey;" title="'.$holder.'">'.$holder.'</div></div></div>'; 
		$page .= '								</div>';
		// ME
		// BROTHERS
		// Calculate dimension of BROTHERS box
		$labelcount = 0;
		$labelcountS = 0;
		$mS = $this->check('11');
		if ($mS[0] > -1) $labelcountS = sizeof($mS);
		$labelcount = 2; // Override calculation to set a fixed structure until we figure out how to make it better
		$boxsize1 = (20*$labelcount) + (2*$labelcount) +    (4*($labelcount-1)) +       (35*$labelcount) ;
		$bloksize =  $boxsize1;
		$page .= '								<div style="float:left; margin:0 auto; margin-left:80px; width:'.$bloksize.'px; height: 100%;">';
		$nn = 0;
		while ($nn < $labelcountS)
		{	
		if($alive[$mS[$nn]] == 1){
							$death_color = "background-color: grey;";
							}else{
							$death_color = "background-color: white;";
							}
							
			$page .= '<div class="Male" style="'.$death_color.'"><icon class="icon-male si"></div>';
			$page .= '<div class="DiLabelContainer">';
			$page .= '	<div class="DiLabel" style="background-color:'.$this->SpeColor($diseasegroup[$mS[$nn]]).'" title="'.$disease[$mS[$nn]].' @ '.$atage[$mS[$nn]].'">'.substr($disease[$mS[$nn]],0,5).'</div> '; 
			$page .= '</div>';
			$nn++;
		}
		$page .= '								</div>';
		// BROTHERS


		$page .= '							</div></td>';
		$page .= '						</tr>';



		$page .= '					</table>';
		$page .= '					</div>';

		$page .= '<input id="Fdoctor_signed" value="'.$doctor_signed.'" style="width:20px; float:left; display:none;">';	
		$page .= '<input id="Flatest_update" value="'.$latest_update.'" style="width:120px; float:left; display:none;">';
		$page .= '</div></center>';
		//MODAL WINDOW WITH JAVASCRIPT AT FOOT...
		$page .= '<!-- MODAL VIEW FOR FAMILY HISTORY -->
				<div id="modalFamilyHistory" style="display:none; text-align:center; padding:20px;">
					<div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Family History for </span>'.$this->member_name.' '.$this->member_surname.'</div>
					<div id="deletedFamilyHistory"><img class="btn" src="../../images/icons/trashicon.jpg" style="margin-left:25px;border:solid 1px #cacaca;" alt="Deleted Medications" width="15" height="15"></div>
					<div id="RelativesContainer" style="border:solid 1px #cacaca; height:200px; margin-top:30px; padding-top:5px; overflow:auto;">
					</div>
					<div style="border:solid 1px #cacaca; height:100px; margin-top:20px; padding:10px;">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left: 5px" lang="en">Type of Relative</span>
							<select id="TypeRelative" type="text" style="width: 232px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px"  />
								<option value="1" lang="en">Father</option>
								<option value="2" lang="en">Mother</option>
								<option value="3" lang="en">Maternal Grandfather</option>
								<option value="4" lang="en">Maternal Grandmother</option>
								<option value="5" lang="en">Maternal Uncle</option>
								<option value="6" lang="en">Maternal Aunt</option>
								<option value="7" lang="en">Paternal Grandfather</option>
								<option value="8" lang="en">Paternal Grandmother</option>
								<option value="9" lang="en">Paternal Uncle</option>
								<option value="10" lang="en">Paternal Aunt</option>
								<option value="11" lang="en">Brother</option>
								<option value="12" lang="en">Sister</option>
								<!--<option value="13" lang="en">Maternal Brother</option>
								<option value="14" lang="en">Maternal Sister</option>
								<option value="15" lang="en">Paternal Brother</option>
								<option value="16" lang="en">Paternal Sister</option>
								<option value="17" lang="en">Son</option>
								<option value="18" lang="en">Daughter</option>-->
							</select>
							<div style="width:100%; float:left;"></div>
							
							<span style="float:left; text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Disease: </span>
			<!--				<input id="DiseaseName" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;">-->
							<input id="DiseaseName" type="text" style="margin-left:10px;width: 140px; float:left;font-size:14px;height:25px" lang="en" placeholder="Disease Name">
							
							<select id="DiseaseGroup" type="text" style="width: 120px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px" />
								<option value="1">Neuro</option>
								<option value="2">Otolaryngo</option>
								<option value="3">Endocrino</option>
								<option value="4">'.$this->translations['digestive'].'</option>
								<option value="5">Pneumo</option>
								<option value="6">Cardio</option>
								<option value="7">Uro</option>
								<option value="8">Repro</option>
								<option value="9">Dermo</option>
								<option value="10">Onco</option>
								<option value="11">Trauma</option>
							</select>

							<div style="width:100%; float:left;"></div>
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Age at Event: </span>
			<!--				<input id="AgeEvent" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;">-->
							<input id="AgeEvent" type="text" class="numbersOnly" style="margin-left:10px;width: 50px; float:left;font-size:14px;height:25px" lang="en" placeholder="Age">
			<!--			<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:25px;margin-right:5px;">Deceased</span>
						<input id="isDeadCheck" type="checkbox" style="float:left;height:23px;" name="isDeadCheck" value="1">
			-->
							<input id="ICD10" type="text" style="visibility: hidden;" ><input id="ICD9" type="text" style="visibility: hidden;" >

							<div style="width:100%;"></div>
				

						</div>            
						<a id="ButtonAddRelative" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-110px; margin-right:10px;"><span lang="en">Add</span></a>

				</div>
				<!--END FAMILY MODAL-->
				<!-- Model for deleted family history -->
	
				<div id="modalDeletedFamilyHistory" style="display:none; text-align:center; padding:20px;">

					<div style="color:#22aeff; font-size:14px; text-align: right; width: 80%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Deleted family history for </span>'.$this->member_name.' '.$this->member_surname.'</div>
					<div><img id="deletedFamilyHistory" src="../../images/icons/trashicon.jpg" style="margin-left:25px;" alt="Deleted Family History" width="30" height="30"></div>
					<div id="RelativesContainerDeleted" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
					</div>

				</div>
					<!-- END FAMILY DELETED MODAL-->
					<!-- MODAL VIEW FOR EDITING FAMILY HISTORY -->
					<div id="editFamilyHistoryModal" style="display:none; text-align:center; padding:20px;">

						<div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Edit Family History for </span>'.$this->member_name.' '.$this->member_surname.'</div>
						<div id="RelativesContainerEdit" style="border:solid 1px #cacaca; height:80px; margin-top:30px; padding-top:5px; overflow:auto;">
						</div>

						<div style="border:solid 1px #cacaca; height:100px; margin-top:20px; padding:10px;">

								<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left: 5px" lang="en">Type of Relative</span>
								<input id="DiagnosticRow2" type="hidden" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:25px">
								<select id="TypeRelative2" type="text" style="width: 232px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px"  />
									<option value="1" lang="en">Father</option>
									<option value="2" lang="en">Mother</option>
									<option value="3" lang="en">Maternal Grandfather</option>
									<option value="4" lang="en">Maternal Grandmother</option>
									<option value="5" lang="en">Maternal Uncle</option>
									<option value="6" lang="en">Maternal Aunt</option>
									<option value="7" lang="en">Paternal Grandfather</option>
									<option value="8" lang="en">Paternal Grandmother</option>
									<option value="9" lang="en">Paternal Uncle</option>
									<option value="10" lang="en">Paternal Aunt</option>
									<option value="11" lang="en">Brother</option>
									<option value="12" lang="en">Sister</option>
									<option value="13" lang="en">Maternal Brother</option>
									<option value="14" lang="en">Maternal Sister</option>
									<option value="15" lang="en">Paternal Brother</option>
									<option value="16" lang="en">Paternal Sister</option>
									<option value="17" lang="en">Son</option>
									<option value="18" lang="en">Daughter</option>
								</select>
								
								<div style="width:100%; float:left;"></div>

								<span style="float:left; text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Disease: </span>
				<!--				<input id="DiseaseName" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;">-->
								<input id="DiseaseName2" type="text" style="margin-left:10px;width: 140px; float:left;font-size:14px;height:25px" lang="en" placeholder="Disease Name">
								
								<select id="DiseaseGroup2" type="text" style="width: 120px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px" />
									<option value="1">Neuro</option>
									<option value="2">Otolaryngo</option>
									<option value="3">Endocrino</option>
									<option value="4">Digestive</option>
									<option value="5">Pneumo</option>
									<option value="6">Cardio</option>
									<option value="7">Uro</option>
									<option value="8">Repro</option>
									<option value="9">Dermo</option>
									<option value="10">Onco</option>
									<option value="11">Trauma</option>
								</select>

								<div style="width:100%; float:left;"></div>
								<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Age at Event: </span>
				<!--				<input id="AgeEvent" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;">-->
								<input id="AgeEvent2" type="text" class="numbersOnly" style="margin-left:10px;width: 50px; float:left;font-size:14px;height:25px" lang="en" placeholder="Age">
				<!--			<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:25px;margin-right:5px;">Deceased</span>
							<input id="isDeadCheck" type="checkbox" style="float:left;height:23px;" name="isDeadCheck" value="1">
				-->
								<input id="ICD102" type="text" style="visibility: hidden;" ><input id="ICD9" type="text" style="visibility: hidden;" >

								<div style="width:100%;"></div>
					

							</div>            
							<a id="ButtonEditRelative" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-110px; margin-right:10px;"><span lang="en">Edit</span></a>

					</div>
					<!-- END MODAL VIEW FOR EDITING FAMILY HISTORY -->';
					if($display_type == 0){
				$page .= '<script type="text/javascript">
				
					var family_info = $("#modalFamilyHistory").dialog({bgiframe: true, width: 550, autoOpen: false, height: 500, modal: true,title:"'.$this->translations['familyhistory'].'"});
					$("#FamilyHistoryInfo").live("click",function()	{
						//alert("clicked");
						getRelatives(1);
						
						family_info.dialog("open");
					});
					
					var family_deleted = $("#modalDeletedFamilyHistory").dialog({bgiframe: true, width: 400, height: 400, autoOpen: false, modal: true,title:"'.$this->translations['deletedfamilyhistory'].'"});
					$("#deletedFamilyHistory").live("click",function()	{
							getRelativesDeleted();
							family_deleted.dialog("open");
					 });
					 
					 var prev_click="";
					$(".DeleteRelative").live("click",function(){
						var myClass = $(this).attr("id"); 
						//alert (myClass);
						prev_click="delete";
						var cadena="medicalPassportUnit.php?data_type=deleteRelative&ajax=true&doc_id='.$this->med_id.'&id="+myClass;
						var RecTipo=LanzaAjax(cadena);
						getRelatives(1);
						getFamilyHistoryInfo();

					});
					
					$(".EditFamilyHistory").live("click",function(){
					var translation23 = "edit family histoyry";
						var myClass = $(this).attr("id"); 
						//alert (myClass);
						$("#editFamilyHistoryModal").dialog({bgiframe: true, width: 550, height: 380, modal: true,title:translation23});
						getRelativesEdit(myClass);
					});
					
					function getRelativesEdit(toShow)
					{
						var queUrl ="medicalPassportUnit.php?data_type=getRelative&IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&ajax='.$_GET['ajax'].'";
						$.ajax(
						{
							url: queUrl,
							dataType: "json",
							async: false,
							success: function(data)
							{
								RelativesData = data.items;
							}
						});
					
						numRelatives = RelativesData.length;
					
						var n = 0;
						var RelativesBox
						if (numRelatives==0)
						{
						var translation51 = "";

						if(language == "th"){
						translation51 = "No se encontraron datos";
						}else if(language == "en"){
						translation51 = "No Data Found";
						}
							RelativesBox="<span>"+translation51+"</span>";
						}
						else
						{
							RelativesBox="";
						}
						
						while (n<numRelatives){
							var del = RelativesData[n].deleted;
							var relativename = RelativesData[n].relative;
							var relativetype = RelativesData[n].relativetype;
							var disease = RelativesData[n].disease;
							var diseasegroup = RelativesData[n].diseasegroup;
							var ICD10 = RelativesData[n].ICD10;
							var ICD9 = RelativesData[n].ICD9;
							var ageevent = RelativesData[n].atage;
							var rowid = RelativesData[n].id;	
							
							

							var middlecolumn = disease + " @ "+ageevent;
							if(del==0)
							{
							if(rowid==toShow)
							{
							$("#DiseaseName2").val(disease);
							$("#AgeEvent2").val(ageevent);
							RelativesBox += "<div class=\"InfoRow\">";
							
									RelativesBox += "<div style=\"width:200px; float:left; text-align:left;\"><span class=\"PatName\">"+relativename+"</span></div>";
									RelativesBox += "<div style=\"width:140px; float:left; text-align:left; color:#22aeff;\"><span class=\"DrName\">"+middlecolumn +" </span></div>";
									RelativesBox += "<div class=\"DeleteRelative\" id="+rowid+" style=\"width:60px; float:left;height:30px;\">'.$this->translations['editing'].'</div>";
							
							
							RelativesBox += "</div>";
							}
							}
							n++;
						}
						$("#RelativesContainerEdit").html(RelativesBox);
						var rowtrack = $("#DiagnosticRow2").val(toShow);
						//alert (RelativesBox);
						
					}
					
					$("#ButtonEditRelative").click(function() {
						/*var idPatient = */
						var relativecodeI = $("#TypeRelative2").val();
						var relativename = $("#TypeRelative2 option:selected").text();
						var disease = $("#DiseaseName2").val();
						var diseasegroup = $("#DiseaseGroup2").val();
						var ICD10 = $("#ICD102").val();
						var ICD9 = $("#ICD92").val();
						var ageevent = $("#AgeEvent2").val();
						var idrow = $("#DiagnosticRow2").val();
						var isdeadcheck = $("input[name=\"isDeadCheck2\"]:checked").val();
				//		alert(idrow);

						if(relativename == "")
						{
							alert("Select Relative");
							$("#TypeRelative2").focus();
							return;			
						}

						if(disease == "")
						{
							alert("Select Disease");
							$("#DiseaseName2").focus();
							return;			
						}

						if(diseasegroup == "")
						{
							alert("Select Disease Group");
							$("#DiseaseGroup2").focus();
							return;			
						}

						if(ageevent == "")
						{
							alert("Select Age at the Event");
							$("#AgeEvent2").focus();
							return;			
						}
						
					
						
						var url = "medicalPassportUnit.php?data_type=createRelative&IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&ajax=true&relativename="+relativename+"&relativecode="+relativecodeI+"&diseasename="+disease+"&diseasegroup="+diseasegroup+"&ICD10="+ICD10+"&ICD9="+ICD9+"&ageevent="+ageevent+"&isdeadcheck="+isdeadcheck+"&rowediter="+idrow;
						//alert(url);
						//return;
						console.log(url);
						var Rectipo = LanzaAjax(url);
						getRelatives(0);
						
					
						//clear data
						$("#TypeRelative2").val("");
						$("#DiseaseName2").val("");
						$("#DiseaseGroup2").val("");
						$("#AgeEvent2").val("");
						$("#isDeadCheck2").val("");
						$("#ICD102").val("");
						$("#ICD92").val("");
						$("#DiagnosticRow2").val("");

						getFamilyHistoryInfo();
						$("#editFamilyHistoryModal").dialog("close");
					});
					
					function getFamilyHistoryInfo()
					{
						var IdUsu = "'.$this->passport_id.'";
						var link = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getFamilyHistoryInfo&ajax=true";
					
					
						$.ajax({
						   url: link,
						   dataType: "html",
						   async: true,
						   success: function(data)
						   {
								$("#FamilyHistoryInfo").html(data);
								var myElement = document.querySelector("#FamilyHistoryInfo");
								myElement.style.display = "block";
						   }
						});

					
					}
					
					$("#ButtonAddRelative").click(function() {
						/*var idPatient = */
						var relativecodeI = $("#TypeRelative").val();
						var relativename = $("#TypeRelative option:selected").text();
						var disease = $("#DiseaseName").val();
						var diseasegroup = $("#DiseaseGroup").val();
						var ICD10 = $("#ICD10").val();
						var ICD9 = $("#ICD9").val();
						var ageevent = $("#AgeEvent").val();
						var isdeadcheck = $("input[name=\"isDeadCheck\"]:checked").val();

						if(relativename == "")
						{
							alert("Select Relative");
							$("#TypeRelative").focus();
							return;			
						}

						if(disease == "")
						{
							alert("Select Disease");
							$("#DiseaseName").focus();
							return;			
						}

						if(diseasegroup == "")
						{
							alert("Select Disease Group");
							$("#DiseaseGroup").focus();
							return;			
						}

						if(ageevent == "")
						{
							alert("Select Age at the Event");
							$("#AgeEvent").focus();
							return;			
						}
						
					
						
						var url = "medicalPassportUnit.php?data_type=createRelative&IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&ajax=true&relativename="+relativename+"&relativecode="+relativecodeI+"&diseasename="+disease+"&diseasegroup="+diseasegroup+"&ICD10="+ICD10+"&ICD9="+ICD9+"&ageevent="+ageevent+"&isdeadcheck="+isdeadcheck;
						//alert(url);
						//return;
						console.log(url);
						var Rectipo = LanzaAjax(url);
						getRelatives(1);
						
					
						//clear data
						$("#TypeRelative").val("");
						$("#DiseaseName").val("");
						$("#DiseaseGroup").val("");
						$("#AgeEvent").val("");
						$("#isDeadCheck").val("");
						$("#ICD10").val("");
						$("#ICD9").val("");

						getFamilyHistoryInfo();
								
					});
					
					function getRelatives(ajax)
					{
						var queUrl ="medicalPassportUnit.php?data_type=getRelative&IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&ajax="+ajax;
						$.ajax(
						{
							url: queUrl,
							dataType: "json",
							async: false,
							success: function(data)
							{
								RelativesData = data.items;
							}
						});
					
						numRelatives = RelativesData.length;
					
						var n = 0;
						var RelativesBox
						if (numRelatives==0)
						{
						var translation51 = "";

						if(language == "th"){
						translation51 = "No se encontraron datos";
						}else if(language == "en"){
						translation51 = "No Data Found";
						}
							RelativesBox="<span>"+translation51+"</span>";
						}
						else
						{
							RelativesBox="";
						}
						
						while (n<numRelatives){
							var del = RelativesData[n].deleted;
							var relativename = RelativesData[n].relative;
							var relativetype = RelativesData[n].relativetype;
							var disease = RelativesData[n].disease;
							var diseasegroup = RelativesData[n].diseasegroup;
							var ICD10 = RelativesData[n].ICD10;
							var ICD9 = RelativesData[n].ICD9;
							var ageevent = RelativesData[n].atage;
							var rowid = RelativesData[n].id;	

							var middlecolumn = disease + " @ "+ageevent;
							if(del==0)
							{
							RelativesBox += "<div class=\"InfoRow\">";
							
							
									RelativesBox += "<div style=\"width:180px; float:left; text-align:left;\"><span class=\"PatName\">"+relativename+"</span></div>";
									RelativesBox += "<div style=\"width:150px; float:left; text-align:left; color:#22aeff;\"><span class=\"DrName\">"+middlecolumn +" </span></div>";
									RelativesBox += "<div class=\"EditFamilyHistory\" id="+rowid+" style=\"width:60px; float:right;height:30px;margin-right:10px;\"><a id="+rowid+"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:green;\" lang=\"en\">Edit</a></div>";
									RelativesBox += "<div class=\"DeleteRelative\" id="+rowid+" style=\"width:60px; float:right;height:30px;\"><a id="+rowid+"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:red;\" lang=\"en\">'.$this->translations['delete'].'</a></div>";
							
							
							RelativesBox += "</div>";
							}
							n++;
						}
						$("#RelativesContainer").html(RelativesBox);
						//alert (RelativesBox);
						
					}
					
					function getRelativesDeleted()
					{
						var queUrl ="medicalPassportUnit.php?data_type=getRelative&IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&ajax='.$_GET['ajax'].'";
						$.ajax(
						{
							url: queUrl,
							dataType: "json",
							async: false,
							success: function(data)
							{
								RelativesData = data.items;
							}
						});
					
						numRelatives = RelativesData.length;

						var n = 0;
						var RelativesBox
						if (numRelatives==0)
						{
							RelativesBox="<span>'.$this->translations['nodatafound'].'</span>";
						}
						else
						{
							RelativesBox="";
						}
						
						while (n<numRelatives){
							var del = RelativesData[n].deleted;
							var relativename = RelativesData[n].relative;
							var relativetype = RelativesData[n].relativetype;
							var disease = RelativesData[n].disease;
							var diseasegroup = RelativesData[n].diseasegroup;
							var ICD10 = RelativesData[n].ICD10;
							var ICD9 = RelativesData[n].ICD9;
							var ageevent = RelativesData[n].atage;
							var rowid = RelativesData[n].id;	

							var middlecolumn = disease + " @ "+ageevent;
							if(del==1)
							{
							RelativesBox += "<div class=\"InfoRow\">";
							
							
									RelativesBox += "<div style=\"width:150px; float:left; text-align:left;\"><span class=\"PatName\"><font size=\"0\">"+relativename+"</font></span></div>";
									RelativesBox += "<div style=\"width:100px; float:left; text-align:left; color:#22aeff;\"><span class=\"DrName\"><font size=\"0\">"+middlecolumn +" </font></span></div>";
							
							
							RelativesBox += "</div>";
							}
							n++;
						}
						$("#RelativesContainerDeleted").html(RelativesBox);
						//alert (RelativesBox);
						
					}
					
					function LanzaAjax (DirURL)
							{
								var RecTipo = "SIN MODIFICACIÓN";
								$.ajax(
								   {
								   url: DirURL,
								   dataType: "html",
								   async: false,
								   complete: function(){ //alert("Completed");
											},
								   success: function(data) {
											
									   //Below line added by Pallab for testing
									   console.log(data);
									   if (typeof data == "string") {
														RecTipo = data;
														}
											 }
									});
								return RecTipo;
							}
					
					
				</script>';	
		}
		
		if($display_type == 0){
			return $page;
		}else{
			echo $page;
		}
	}
	
	//Type indicates if it is an immunization or allergy(This function handles both...)--==WE SHOULD PROBABLY BREAK THIS INTO TWO MODULES==--
	public function getImmunoAllergyInfo($display_type, $type){
		
		$age[0] = 'B';   	$ageVal[0] = 0;
		$age[1] = '1m';		$ageVal[1] = 1;
		$age[2] = '2m';		$ageVal[2] = 2;
		$age[3] = '4m';		$ageVal[3] = 4;
		$age[4] = '6m';		$ageVal[4] = 6;
		$age[5] = '12m';	$ageVal[5] = 12;
		$age[6] = '15m';	$ageVal[6] = 15;
		$age[7] = '18m';	$ageVal[7] = 18;
		$age[8] = '2a';		$ageVal[8] = 2 * 12;		
		$age[9] = '3a';		$ageVal[9] = 3 * 12;
		$age[10] = '5a';	$ageVal[10] = 5 * 12;
		$age[11] = '8a';	$ageVal[11] = 8 * 12;
		$age[12] = '10a';	$ageVal[12] = 10 * 12;
		$age[13] = '12a';	$ageVal[13] = 12 * 12;
		$age[14] = '14a';	$ageVal[14] = 14 * 12;


		$query = $this->con->prepare("select * from p_immuno where deleted=0 and idpatient=?");
		$query->bindValue(1, $this->passport_id, PDO::PARAM_INT);
		$result = $query->execute();

		$count=$query->rowCount();
		$counter1 = 0; 

		$cadena = '';
		$rowCounter = 0;
		$rowCounterAllergy = 0;
		$latest_update = '1975-01-01 00:00:00';
		$doctor_signed = -1;

		while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
			$Id[$counter1] = $row2['id'];
			$VaccCode[$counter1] = $row2['VaccCode'];
			$VaccName[$counter1] = $row2['VaccName'];
			$AllerCode[$counter1] = $row2['AllerCode'];
			$AllerName[$counter1] = $row2['AllerName'];
			$intensity[$counter1] = $row2['intensity'];
			$dateEvent[$counter1] = $row2['date'];
			$ageEvent[$counter1] = $row2['ageevent'];
			$deleted[$counter1] = $row2['deleted'];

			$doctor_signedP = $row2['doctor_signed'];
			$latest_updateP = $row2['latest_update'];
			if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}


			$slotAge[$counter1] = 0;
			$slotRow[$counter1] = 0;

			//THIS IF STATEMENT CHECKS TO SEE IF THE ROW IS A VACCINE OR ELSE IT IS ALLERGY
			if ((($VaccCode[$counter1] != '') || ($VaccName[$counter1] != '')) && $type != 'allergy')   // --------------------------- This entry is a Vaccine
			{
				$measureAge[$counter1] = substr($ageEvent[$counter1], strlen($ageEvent[$counter1])-1,1);
				$numericAge[$counter1] = substr($ageEvent[$counter1], 0, strlen($ageEvent[$counter1])-1);

				if (strtoupper($measureAge[$counter1]) == 'Y')
				{
					$monthsAge[$counter1] = intval($numericAge[$counter1]) * 12;
				}else
				{
					$monthsAge[$counter1] = intval($numericAge[$counter1]);
				}

				$pointerEntry[$rowCounter] = $counter1;
				$n = 0;
				$closer = 9999999;
				while ($n <= 14)
				{
					$difference = abs($monthsAge[$counter1] - $ageVal[$n]);
					if ($difference < $closer)
					{
						$closer = $difference;
						$slotAge[$counter1] = $n;
					}
					$n++;
				}
				// Check if there is another entry of the same vaccine in other previous row
				$y = 0;
				$foundRow = 0;
				while ($y < $rowCounter)
				{
					if ($VaccCode[$counter1] == $VaccCode[$y])
					{
						$foundRow = 1;
						$slotRow[$counter1] = $y;
					}
					$y++;
				}
				// If previous Vaccine not found asign a new row
				if ($foundRow == 0)
				{
					$slotRow[$counter1] = $rowCounter;
					$rowCounter++;		
				}
				//echo 'Slot:  '.$slotAge[$counter1].'   Row: '.$slotRow[$counter1].' Value:  '.$VaccCode[$counter1].'  ----   ';				
			}
			else        // ------------------------------------------------------------------------ This entry is an Allergy
			{
				$pointerEntry[$rowCounterAllergy] = $counter1;
				//echo 'row ='.$rowCounterAllergy.'   Pointer = '.$counter1;
				if($type == 'allergy' && $row2['AllerCode'] != ''){
					$rowCounterAllergy++;
				}
			}


			$counter1++;
		}
		$this->slotrow = $slotRow;
		$this->slotage = $slotAge;
		$this->counter = $counter1;
		if($type == 'allergy'){
			if($display_type == 1){
				$page = '<div  id="AllergyInfo">';
			}else{
				$page = '<div id="AllergyInfo" class="grid" class="grid span4" style="cursor:pointer; float:left;height:22.7%; margin-top:1%; margin-left:.5%; margin-right:.5%; padding:0px; width:20.5%; display:inline-block;">';
			}
		}else{
			if($display_type == 1){
				$page = '<div id="ImmunizationAllergyInfo">';
			}else{
			$page = '<div id="ImmunizationAllergyInfo" class="grid" class="grid span4" style="cursor:pointer; float:left;height:23.1%; margin-top:1%; margin-left:1%; margin-right:.5%; padding:0px; width:50%; display:table">';
			}
		}

		$page .= '<style>';
		$page .= 'div.timecell{';
		$page .= '  background-color:#22aeff; margin:1px; text-align:center; color:white; font-size:12px;';
		$page .= '  } ';
		$page .= 'div.cellvacc{';
		$page .= '  font-size:10px; color:white; margin:0 auto; margin-bottom:1px; line-height: 12px; background-color:#cacaca; text-align:center; width:30px;';
		$page .= '  } ';
		$page .= 'div.cellaller{';
		$page .= '  font-size:12px; color:white; margin:0 auto; margin-bottom:1px; line-height: 14px; background-color:#cacaca; text-align:center; width:30px;';
		$page .= '  } ';
		$page .= 'td.Ttimecell{';
		$page .= '  padding:0px; margin:0px; text-align:center; background-color: white; ';
		$page .= '  } ';
		$page .= 'tr.AllerRow{';
		$page .= '  padding:0px; margin:0px; height:16px; text-align:center; background-color: white; ';
		$page .= '  } ';
		$page .= '</style>';

		if($type == 'immuno'){
			$page .= '				<div id="ImmunoInnerL" style="float:left; width:100%; border:0px solid;">';

			$page .= '				<div  style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #f39c12; color: white;line-height: 20px;">';
			//Vaccination calendar trans
			$page .= $this->translations['vaccinationcalendar'];
			$page .= '</div>';
		}else{
			$page .= '<div style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #24CCC1; color: white;line-height: 20px;">';
			//Allergies trans
			$page .= $this->translations['allergies'];
			$page .= '</div>';
		}
		

		$page .= '					<table style="margin-top:5px;">';
		$page .= '						<tr style="padding:0px; margin:0px;">';
		$n = 0;				
		//LOOOPS THROUGH VACCINES AND CHECKS FOR COLOR AND AGE OF VACCINATION
		while ($n < 15  && $type != 'allergy')
		{
			$wide = 30 + (($n * 70) / 14);
			$lightness = 57 + (($n * 33) / 14);
			$newcolor = 'hsl(202, 100%, '.$lightness.'%)';
			$page .= '							<td class="Ttimecell" style="width:'.$wide.'px;"><div class="timecell" style="background-color:'.$newcolor.'">'.$age[$n].'</td>';		
			$n++;
		};
		
		
		$page .= '							';
		$page .= '						</tr>';
		$m = 0;

		//DISPLAYS THE VACCINES IN THE VACCINE BOX
		while ($m < 7)
		{
			$maxlight = 50;
			$minlight = 10;
			$lightness = $minlight + (($m * ($maxlight-$minlight)) / 5);		
			$newcolor = 'hsl(108, 100%, '.$lightness.'%)';
			//$page .= $m.' ---- ';
			$page .= '						<tr style="padding:0px; margin:0px;">';
			$n = 0;
			while ($n < 15)
			{
				$filled = $this->checkSlot($m,$n);
				if ($filled > -1 && $type != 'allergy') 
				{
					$page .= '<td class="Ttimecell" style="width:20px;"><div class="cellvacc" style="background-color: '.$newcolor.'" title="'.$VaccName[$filled].'">'.substr($VaccCode[$filled],0,4).'</div></td>';
				}
				elseif($type != 'allergy')
				{
					$page .= '<td class="Ttimecell" style="width:20px;"><div class="cellvacc" style="background-color: white;"></div></td>';
				}
				$n++;
			}
			$page .= '						</tr>';
			$m++;
		}


		$page .= '					</table>';
		

		if($type == 'allergy'){
			$page .= '					<table style="margin-top:0px;width:100%;">';
		}else{
			$page .= '</div>';
		}
		$m = 0;
		


		if($rowCounterAllergy == 0 && $type == 'allergy'){
			$page .= '<tr class="AllerRow">
		<td class="Ttimecell" style="width:40px;"><div class="cellaller" style="width:95%;"><center>';
			//No entries trans
			$page .= $this->translations['noentries'];
			$page .= '</center></div><p><center><img width="75px" src="../../images/icons/general_user_error_icon.png" alt="No Data Icon"></center></td>
		</tr>';
		}
		
				

		//DISPLAYS THE ALLERGIES IN THE ALLERGY BOX
		while ($m <= ($rowCounterAllergy-1) && $type == 'allergy')
		{
			$iconR = "";
			if ($intensity[$pointerEntry[$m]]>3) 
				$iconR = '-webkit-animation: glow 2s linear infinite;';
			$titleAl = '';
			if ($intensity[$pointerEntry[$m]] == 4) 
				$titleAl = 'Very Strong Intensity Allergy'; 
			if ($intensity[$pointerEntry[$m]] == 5) 
				$titleAl = 'EXTREME Intensity Allergy'; 
			$maxlight = 50;
			$minlight = 20;
			$lightness = $minlight + (($intensity[$pointerEntry[$m]] * ($maxlight-$minlight)) / 5);		
			$newcolor = 'hsl(0, 100%, '.$lightness.'%)';
			if($AllerName[$pointerEntry[$m]] != "")
			{

				if($_COOKIE["lang"] == 'th')
				{
					if($AllerName[$pointerEntry[$m]] == 'Environmental')
					{
						$aller_title = 'Ambiental';
						$aller_display = substr($aller_title,0,8);
					}
					elseif($AllerName[$pointerEntry[$m]] == 'Foods')
					{
						$aller_title = 'Comidas';
						$aller_display = substr($aller_title,0,8);
					}
					elseif($AllerName[$pointerEntry[$m]] == 'Drugs')
					{
						$aller_title = 'Medicamentos';
						$aller_display = substr($aller_title,0,8);
					}
					elseif($AllerName[$pointerEntry[$m]] == 'Other')
					{
						$aller_title = 'Otros';
						$aller_display = substr($aller_title,0,8);
					}
					elseif($AllerName[$pointerEntry[$m]] == 'Nothing')
					{
						$aller_title = 'Nada';
						$aller_display = substr($aller_title,0,8);
					}
					else
					{
						$aller_title = $AllerName[$pointerEntry[$m]];
						$aller_display = substr($AllerName[$pointerEntry[$m]],0,8);
					}
				}
				else
				{
					$aller_title = $AllerName[$pointerEntry[$m]];
					$aller_display = substr($AllerName[$pointerEntry[$m]],0,8);
				}

				$page .= '						<tr class="AllerRow">';
				$page .= '							<td class="Ttimecell" style="width:98%;"><div class="cellaller" style="'.$iconR.'background-color:'.$newcolor.'; width:100%;margin-left:5px;margin-right:5px;" title="'.$aller_title.'">'.$aller_display.'</div></td>';			
				$page .= '							<td class="Ttimecell" style="width:20px;" title="'.$titleAl.'">';
				$page .= '						</tr>';
			}

			$m++;

		}
		$page .= '<input id="Idoctor_signed" value="'.$doctor_signed.'" style="width:20px; float:left; display:none;">';	
		$page .= '<input id="Ilatest_update" value="'.$latest_update.'" style="width:120px; float:left; display:none;">';	
		$page .= '</div>';
		//MODAL WINDOW WITH JAVASCRIPT AT FOOT...
		if($type == 'allergy'){
			$page .= '<!-- MODAL VIEW FOR ALLERGY -->
					<div id="modalAllergy" style="display:none; text-align:center; padding:20px;">

						<div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Known allergies for <span>'.$this->member_name.' '.$this->member_surname.'</div>
						<div id="deletedAllergy"><img class="btn" src="../../images/icons/trashicon.jpg" style="margin-left:25px;border:solid 1px #cacaca;" alt="Deleted Medications" width="15" height="15"></div>
						<div id="AllergyContainer" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
						</div>

						<div style="border:solid 1px #cacaca; height:140px; margin-top:20px; padding:10px;">

								 
								

								

								<select id="AllergyCode" type="text" style="width: 210px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px"  />
									<option value="" lang="en">Allergy Codes</option>
									<option value="FOO,Foods" lang="en">Foods</option>
									<option value="DRU,Drugs" lang="en">Drugs</option>
									<option value="ENV,Environmental" lang="en">Environmental</option>
									<option value="OTH, Other" lang="en">Other</option>
								</select>
								<input id="AllergyName" type="text" style="margin-left:10px;width: 140px; float:left;font-size:14px;height:25px" lang="en" placeholder="Allergy Name">

								<div style="width:100%; float:left; text-align:center; height:10px; "></div>
								
								<div style="padding:3px;height: 30px;margin-top: 5px ">
									<span style="float: left; text-align:center; font-size:14px; " lang="en">Date: </span>
									<input id="dateEventAll" type="date" style="margin-left:10px; width: 150px; float:left; font-size:14px; height:25px">
								</div>
								<!--<input id="ageEventAll" type="text" style="margin-left:10px;width: 140px; float:left;font-size:14px;height:25px" lang="en" placeholder="Age of Outbreak">-->

								<div style="width:100%; float:left; text-align:center; height:10px; "></div>
									
								<span style="margin-left:5px;float: left; text-align:center; font-size:14px; " lang="en">Intensity </span>
								<div id="intensity-slider" style="float:left; margin-left:25px; width:200px;"></div>					
								<span  id="intensitylabel" style="float:left; text-align:center; font-size:12px; margin-top:0px; margin-left:10px"><font style="color:hsl(0, 100%, 20%);" lang="en">Very Low</font></span>
								<input id="intensity" type="text" style="float:left; text-align:center; margin-left: 20px; margin-top: -5px; width: 30px; font-size:12px; height:25px; visibility:hidden;">
								<a id="buttonNoAllergies" style="float:left; text-align:center; margin-left: 5px; margin-top: -5px; width: 150px; font-size:12px; height:17px;" class="btn" lang="en">No Known Allergies</a>


							</div>            
							<a id="ButtonAddAllergy" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-140px; margin-right:19px;"><span lang="en">Add</span></a>

					</div>
					<!-- Model for deleted allergies -->
	
					<div id="modalDeletedAllergy" style="display:none; text-align:center; padding:20px;">

						<div style="color:#22aeff; font-size:14px; text-align: right; width: 80%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Deleted allergies for </span>'.$this->member_name.' '.$this->member_surname.'</div>
						<div><img id="deletedAllergy" src="../../images/icons/trashicon.jpg" style="margin-left:25px;" alt="Deleted Allergies" width="30" height="30"></div>
						<div id="AllergyContainerDeleted" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
						</div>

					</div>
					<!-- MODAL VIEW FOR EDITING ALLERGY -->
					<div id="editAllergyModal" style="display:none; text-align:center; padding:20px;">

						<div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Edit known allergies for <span>'.$this->member_name.' '.$this->member_surname.'</div>
						<div id="AllergyContainer2" style="border:solid 1px #cacaca; height:80px; margin-top:30px; padding-top:5px; overflow:auto;">
						</div>

						<div style="border:solid 1px #cacaca; height:140px; margin-top:20px; padding:10px;">

								 
								

								
								<input id="DiagnosticRow2" type="hidden" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:25px">
								<select id="AllergyCode2" type="text" style="width: 210px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px"  />
									<option value="" lang="en">Allergy Codes</option>
									<option value="FOO,Foods" lang="en">Foods</option>
									<option value="DRU,Drugs" lang="en">Drugs</option>
									<option value="ENV,Environmental" lang="en">Environmental</option>
									<option value="OTH, Other" lang="en">Other</option>
								</select>
								<input id="AllergyName2" type="text" style="margin-left:10px;width: 140px; float:left;font-size:14px;height:25px" lang="en" placeholder="Allergy Name">

								<div style="width:100%; float:left; text-align:center; height:10px; "></div>
								
								<div style="padding:3px;height: 30px;margin-top: 5px ">
									<span style="float: left; text-align:center; font-size:14px; " lang="en">Date: </span>
									<input id="dateEventAll2" type="date" style="margin-left:10px; width: 150px; float:left; font-size:14px; height:25px">
								</div>
								<!--<input id="ageEventAll2" type="text" style="margin-left:10px;width: 140px; float:left;font-size:14px;height:25px" lang="en" placeholder="Age of Outbreak">-->

								<div style="width:100%; float:left; text-align:center; height:10px; "></div>
									
								<span style="margin-left:5px;float: left; text-align:center; font-size:14px; ">Intensity </span>
								<div id="intensity-slider2" style="float:left; margin-left:25px; width:200px;"></div>					
								<span  id="intensitylabel2" style="float:left; text-align:center; font-size:12px; margin-top:0px; margin-left:10px"><font style="color:hsl(0, 100%, 20%);" lang="en">Very Low</font></span>
								<input id="intensity2" type="text" style="float:left; text-align:center; margin-left: 20px; margin-top: -5px; width: 30px; font-size:12px; height:25px; visibility:hidden;">


							</div>            
							<a id="ButtonEditAllergy" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-140px; margin-right:19px;"><span lang="en">Edit</span></a>
					</div>
					<!-- END MODAL VIEW FOR EDITING ALLERGY -->';
		}else{
			$page .= '<!-- MODAL VIEW FOR VACCINE INFO -->
						<!-- MODAL VIEW FOR IMMUNIZATION -->
						<div id="modalImmunizationAllergy" style="display:none; text-align:center; padding:20px;">

							<div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Immunization for </span>'.$this->member_name.' '.$this->member_surname.'</div>
							<div id="deletedImmunizations"><img class="btn" src="../../images/icons/trashicon.jpg" style="margin-left:25px;border:solid 1px #cacaca;" alt="Deleted Medications" width="15" height="15"></div>
							<div id="VaccinesContainer" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
							</div>

							<div style="border:solid 1px #cacaca; height:120px; margin-top:20px; padding:10px;">

									 <select id="VaccineCode" type="text" style="width: 200px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px"  />
										<option value="" lang="en">Vaccine Codes</option>
										<option value="CHOLERA,Cholera">CHOLERA..........Cholera</option>
										<option value="DIP,Diphtheria vaccine">Dip..........Diphtheria vaccine</option>
										<option value="DT,Tetanus and diphtheria toxoid childrens" dose">DT..........Tetanus and diphtheria toxoid childrens" dose</option>
										<option value="DTAP,Diphtheria and tetanus toxoid with acellular pertussis vaccine">DTaP..........Diphtheria and tetanus toxoid with acellular pertussis vaccine</option>
										<option value="DTAPHEPBIPV,Diphtheria and Tetanus and Pertussis and Hepatitis B and Polio">DTaPHepBIPV..........Diphtheria and Tetanus and Pertussis and Hepatitis B and Polio</option>
										<option value="DTAPHEPIPV,Diphtheria and tetanus toxoid with acellular pertussis, HepB and IPV vaccine">DTaPHepIPV..........Diphtheria and tetanus toxoid with acellular pertussis, HepB and IPV vaccine</option>
										<option value="DTAPHIB,Diphtheria and tetanus toxoid with acellular pertussis and Hib vaccine">DTaPHib..........Diphtheria and tetanus toxoid with acellular pertussis and Hib vaccine</option>
										<option value="DTAPHIBHEP,Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine">DTaPHibHep..........Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine</option>
										<option value="DTAPHIBHEPB,Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine">DTaPHibHepB..........Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine</option>
										<option value="DTAPHIBHEPIPV,Hexavalent diphtheria, tetanus toxoid with acellular pertussis, Hib, hepatitis B and IPV vaccine">DTaPHibHepIPV..........Hexavalent diphtheria, tetanus toxoid with acellular pertussis, Hib, hepatitis B and IPV vaccine</option>
										<option value="DTAPHIBIPV,Diphtheria and tetanus toxoid with acellular pertussis, Hib and IPV vaccine">DTaPHibIPV..........Diphtheria and tetanus toxoid with acellular pertussis, Hib and IPV vaccine</option>
										<option value="DTAPIPV,Diphtheria and tetanus toxoid with acellular pertussis, and IPV vaccine">DTaPIPV..........Diphtheria and tetanus toxoid with acellular pertussis, and IPV vaccine</option>
										<option value="DTIPV,Diphtheria and tetanus toxoid vaccine and IPV">DTIPV..........Diphtheria and tetanus toxoid vaccine and IPV</option>
										<option value="DTP,Diptheria and tetanus toxoid with pertussis vaccine">DTP..........Diptheria and tetanus toxoid with pertussis vaccine</option>
										<option value="DTPHIBIPV,Pentavalent diphtheria and tetanus toxoid with pertussis, Hib and IPV vaccine">DTPHibIPV..........Pentavalent diphtheria and tetanus toxoid with pertussis, Hib and IPV vaccine</option>
										<option value="DTWP,Diphtheria and tetanus toxoid with whole cell pertussis vaccine">DTwP..........Diphtheria and tetanus toxoid with whole cell pertussis vaccine</option>
										<option value="DTWPHEP,Diphtheria and tetanus toxoid with whole cell pertussis and HepB vaccine">DTwPHep..........Diphtheria and tetanus toxoid with whole cell pertussis and HepB vaccine</option>
										<option value="DTWPHIB,Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine">DTwPHib..........Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine</option>
										<option value="DTWPHIB,Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine">DTwPHiB..........Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine</option>
										<option value="DTWPHIBHEP,Diphtheria and tetanus toxoid with whole cell pertussis, Hib and HepB vaccine">DTwPHibHep..........Diphtheria and tetanus toxoid with whole cell pertussis, Hib and HepB vaccine</option>
										<option value="DTWPHIBHEPB,Diphtheria and Tetanus and Pertussis and Haemophilus influenzae and Hepatitis B">DTwPHibHepB..........Diphtheria and Tetanus and Pertussis and Haemophilus influenzae and Hepatitis B</option>
										<option value="DTWPHIBIPV,Diphtheria and tetanus toxoid with whole cell pertussis, Hib and IPV vaccine">DTwPHibIPV..........Diphtheria and tetanus toxoid with whole cell pertussis, Hib and IPV vaccine</option>
										<option value="DTWPIPV,Diphtheria and tetanus toxoid with whole cell pertussis, and IPV vaccine">DTwPIPV..........Diphtheria and tetanus toxoid with whole cell pertussis, and IPV vaccine</option>
										<option value="HEPA,Hepatitis A vaccine">HepA..........Hepatitis A vaccine</option>
										<option value="HEPAHEPB,Hepatitis A, Hepatitis B vaccine">HepAHepB..........Hepatitis A, Hepatitis B vaccine</option>
										<option value="HEPB,Hepatitis B vaccine">HepB..........Hepatitis B vaccine</option>
										<option value="HFRS,">HFRS..........Hemorrhagic fever with renal syndrome</option>
										<option value="HIB,Hemorrhagic fever with renal syndrome">HIB..........Haemophilus influenzae type b vaccine</option>
										<option value="HIB,Haemophilus influenzae type b vaccine">Hib..........Haemophilus influenzae type b vaccine</option>
										<option value="HIBMENC,Haemophilus influenza type b, Meningococcal C vaccine">HibMenC..........Haemophilus influenza type b, Meningococcal C vaccine</option>
										<option value="HPV,Human Papillomavirus vaccine">HPV..........Human Papillomavirus vaccine</option>
										<option value="INFLUENZA,Influenza">Influenza..........Influenza</option>
										<option value="IPV,Inactivated polio vaccine">IPV..........Inactivated polio vaccine</option>
										<option value="JAPENC,Japanese encephalitis">JapEnc..........Japanese encephalitis</option>
										<option value="MEASLES,Measles vaccine">Measles..........Measles vaccine</option>
										<option value="MENA,Meningococcal A">MenA..........Meningococcal A</option>
										<option value="MENAC,Meningococcal AC">MenAC..........Meningococcal AC</option>
										<option value="MENACW,Meningococcal ACW">MenACW..........Meningococcal ACW</option>
										<option value="MENACWY,">MenACWY..........Meningococcal ACWY</option>
										<option value="MENBC,Meningococcal ACWY">MenBC..........Meningococcal group B &amp; group C vaccine</option>
										<option value="MENC_CONJ,Meningococcal group B &amp; group C vaccine">MenC_conj..........Meningococcal C conjugate vaccine</option>
										<option value="MM,Measles and mumps vaccine<">MM..........Measles and mumps vaccine</option>
										<option value="MMR,Measles mumps and rubella vaccine">MMR..........Measles mumps and rubella vaccine</option>
										<option value="MMRV,Measles, mumps, rubella and varicella vaccine">MMRV..........Measles, mumps, rubella and varicella vaccine</option>
										<option value="MR,Measles and rubella vaccine">MR..........Measles and rubella vaccine</option>
										<option value="MUMPS,Mumps vaccine">Mumps..........Mumps vaccine</option>
										<option value="OPV,Oral polio vaccine">OPV..........Oral polio vaccine</option>
										<option value="PNEUMO_CONJ,Pneumococcal conjugate vaccine">Pneumo_conj..........Pneumococcal conjugate vaccine</option>
										<option value="PNEUMO_PS,Pneumococcal polysaccharide vaccine">Pneumo_ps..........Pneumococcal polysaccharide vaccine</option>
										<option value="RABIES,Rabies vaccine">Rabies..........Rabies vaccine</option>
										<option value="ROTAVIRUS,Rotavirus vaccine">Rotavirus..........Rotavirus vaccine</option>
										<option value="RUBELLA,Rubella vaccine">Rubella..........Rubella vaccine</option>
										<option value="TBE,Tick borne encephalitis">TBE..........Tick borne encephalitis</option>
										<option value="TD,Tetanus and diphtheria toxoid for older children / adults">Td..........Tetanus and diphtheria toxoid for older children / adults</option>
										<option value="TDAP,Tetanus and diphtheria toxoids and acellular pertussis vaccine">TdaP..........Tetanus and diphtheria toxoids and acellular pertussis vaccine</option>
										<option value="TDAPIPV,Tetanus and diphtheria toxoids and acellular pertussis vaccine IPV">TdaPIPV..........Tetanus and diphtheria toxoids and acellular pertussis vaccine IPV</option>
										<option value="TDIPV,Tetanus and diphtheria toxoid for older children / adults with inactivated Polio vaccine">TdIPV..........Tetanus and diphtheria toxoid for older children / adults with inactivated Polio vaccine</option>
										<option value="TT,Tetanus toxoid">TT..........Tetanus toxoid</option>
										<option value="TYPHOID,Typhoid fever vaccine">Typhoid..........Typhoid fever vaccine</option>
										<option value="TYPHOIDHEPA,Typhoid fever and Hepatitis A vaccine">TyphoidHepA..........Typhoid fever and Hepatitis A vaccine</option>
										<option value="VARICELLA,Varicella vaccine">Varicella..........Varicella vaccine</option>
										<option value="VITAMINA,Vitamin A supplementation">VitaminA..........Vitamin A supplementation</option>
										<option value="YF,Yellow fever vaccine">YF..........Yellow fever vaccine</option>
										<option value="ZOSTER,Varicella vaccine">Zoster..........Varicella vaccine</option>
									</select>
									<input id="VaccineName" type="text" style="margin-left:10px;width: 150px; float:left;font-size:14px;height:25px" lang="en" placeholder="Vaccine Code">

									<div style="width:10%; float:left; text-align:center; margin-bottom:10px; margin-left:10px;"></div>


									<div style="width:100%; float:left; text-align:center; height:10px; "></div>
									
									<div style="padding:3px;height: 30px;margin-top: 5px ">
										<span style="float: left; text-align:center; font-size:14px; " lang="en">Date: </span>
										<input id="dateEvent" type="date" style="margin-left:10px; width: 139px; float:left; font-size:14px; height:25px">
									</div>
									<!--<input id="ageEventVac" type="text" style="margin-left:10px;width: 150px; float:left;font-size:14px;height:25px" lang="en" placeholder="Vaccination Age">-->

									<div style="width:100%; float:left; text-align:center; height:10px; "></div>
										
									

								</div>            
								<a id="ButtonAddVaccine" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-130px; margin-right:19px;"><span lang="en">Add</span></a>
								<select id="VaccineCalendarImport" type="text" style="width: 200px;float: left; margin-left: 15px;text-align:center;margin-top:-45px;font-size:14px;height:27px"  />
								<option value="" lang="en">Import Vacc. Calendar</option>
								<option value="USA">United States</option>
								<option value="COL">Colombia</option>
								<option value="SPA">Spain</option>
								</select>
								<a id="ButtonAddVaccineCalendar" class="btn" style="height: 17px; width:135px; color:#22aeff; float:right; margin-top:-45px; margin-right:118px;"><span lang="en">Import Calendar</span></a>

						</div>
						<!-- END MODAL VIEW FOR IMMUNO INFO -->
						<!-- Model for deleted immunization -->
	
						<div id="modalDeletedImmunizations" style="display:none; text-align:center; padding:20px;">

							<div style="color:#22aeff; font-size:14px; text-align: right; width: 80%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Deleted immunizations for </span>'.$this->member_name.' '.$this->member_surname.'</div>
							<div><img id="deletedImmunizations" src="../../images/icons/trashicon.jpg" style="margin-left:25px;" alt="Deleted Immunizations" width="30" height="30"></div>
							<div id="VaccinesContainerDeleted" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
							</div>

						</div>
						<!-- END Model for deleted immunization -->
						<!-- MODAL VIEW FOR EDITING IMMUNIZATION -->
						<div id="editVaccineModal" style="display:none; text-align:center; padding:20px;">

							<div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Edit Immunization for </span>'.$this->member_name.' '.$this->member_surname.'</div>
							<div id="VaccinesContainer2" style="border:solid 1px #cacaca; height:80px; margin-top:30px; padding-top:5px; overflow:auto;">
							</div>

							<div style="border:solid 1px #cacaca; height:120px; margin-top:20px; padding:10px;">
							<input id="DiagnosticRow2" type="hidden" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:25px">
									 <select id="VaccineCode2" type="text" style="width: 200px;float: left; margin-left: 5px;text-align:center;font-size:14px;height:27px"  />
										<option value="">Vaccine Codes</option>
										<option value="CHOLERA,Cholera">CHOLERA..........Cholera</option>
										<option value="DIP,Diphtheria vaccine">Dip..........Diphtheria vaccine</option>
										<option value="DT,Tetanus and diphtheria toxoid childrens\' dose">DT..........Tetanus and diphtheria toxoid childrens\' dose</option>
										<option value="DTAP,Diphtheria and tetanus toxoid with acellular pertussis vaccine">DTaP..........Diphtheria and tetanus toxoid with acellular pertussis vaccine</option>
										<option value="DTAPHEPBIPV,Diphtheria and Tetanus and Pertussis and Hepatitis B and Polio">DTaPHepBIPV..........Diphtheria and Tetanus and Pertussis and Hepatitis B and Polio</option>
										<option value="DTAPHEPIPV,Diphtheria and tetanus toxoid with acellular pertussis, HepB and IPV vaccine">DTaPHepIPV..........Diphtheria and tetanus toxoid with acellular pertussis, HepB and IPV vaccine</option>
										<option value="DTAPHIB,Diphtheria and tetanus toxoid with acellular pertussis and Hib vaccine">DTaPHib..........Diphtheria and tetanus toxoid with acellular pertussis and Hib vaccine</option>
										<option value="DTAPHIBHEP,Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine">DTaPHibHep..........Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine</option>
										<option value="DTAPHIBHEPB,Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine">DTaPHibHepB..........Diphtheria and tetanus toxoid with acellular pertussis, Hib and HepB vaccine</option>
										<option value="DTAPHIBHEPIPV,Hexavalent diphtheria, tetanus toxoid with acellular pertussis, Hib, hepatitis B and IPV vaccine">DTaPHibHepIPV..........Hexavalent diphtheria, tetanus toxoid with acellular pertussis, Hib, hepatitis B and IPV vaccine</option>
										<option value="DTAPHIBIPV,Diphtheria and tetanus toxoid with acellular pertussis, Hib and IPV vaccine">DTaPHibIPV..........Diphtheria and tetanus toxoid with acellular pertussis, Hib and IPV vaccine</option>
										<option value="DTAPIPV,Diphtheria and tetanus toxoid with acellular pertussis, and IPV vaccine">DTaPIPV..........Diphtheria and tetanus toxoid with acellular pertussis, and IPV vaccine</option>
										<option value="DTIPV,Diphtheria and tetanus toxoid vaccine and IPV">DTIPV..........Diphtheria and tetanus toxoid vaccine and IPV</option>
										<option value="DTP,Diptheria and tetanus toxoid with pertussis vaccine">DTP..........Diptheria and tetanus toxoid with pertussis vaccine</option>
										<option value="DTPHIBIPV,Pentavalent diphtheria and tetanus toxoid with pertussis, Hib and IPV vaccine">DTPHibIPV..........Pentavalent diphtheria and tetanus toxoid with pertussis, Hib and IPV vaccine</option>
										<option value="DTWP,Diphtheria and tetanus toxoid with whole cell pertussis vaccine">DTwP..........Diphtheria and tetanus toxoid with whole cell pertussis vaccine</option>
										<option value="DTWPHEP,Diphtheria and tetanus toxoid with whole cell pertussis and HepB vaccine">DTwPHep..........Diphtheria and tetanus toxoid with whole cell pertussis and HepB vaccine</option>
										<option value="DTWPHIB,Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine">DTwPHib..........Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine</option>
										<option value="DTWPHIB,Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine">DTwPHiB..........Diphtheria and tetanus toxoid with whole cell pertussis and Hib vaccine</option>
										<option value="DTWPHIBHEP,Diphtheria and tetanus toxoid with whole cell pertussis, Hib and HepB vaccine">DTwPHibHep..........Diphtheria and tetanus toxoid with whole cell pertussis, Hib and HepB vaccine</option>
										<option value="DTWPHIBHEPB,Diphtheria and Tetanus and Pertussis and Haemophilus influenzae and Hepatitis B">DTwPHibHepB..........Diphtheria and Tetanus and Pertussis and Haemophilus influenzae and Hepatitis B</option>
										<option value="DTWPHIBIPV,Diphtheria and tetanus toxoid with whole cell pertussis, Hib and IPV vaccine">DTwPHibIPV..........Diphtheria and tetanus toxoid with whole cell pertussis, Hib and IPV vaccine</option>
										<option value="DTWPIPV,Diphtheria and tetanus toxoid with whole cell pertussis, and IPV vaccine">DTwPIPV..........Diphtheria and tetanus toxoid with whole cell pertussis, and IPV vaccine</option>
										<option value="HEPA,Hepatitis A vaccine">HepA..........Hepatitis A vaccine</option>
										<option value="HEPAHEPB,Hepatitis A, Hepatitis B vaccine">HepAHepB..........Hepatitis A, Hepatitis B vaccine</option>
										<option value="HEPB,Hepatitis B vaccine">HepB..........Hepatitis B vaccine</option>
										<option value="HFRS,">HFRS..........Hemorrhagic fever with renal syndrome</option>
										<option value="HIB,Hemorrhagic fever with renal syndrome">HIB..........Haemophilus influenzae type b vaccine</option>
										<option value="HIB,Haemophilus influenzae type b vaccine">Hib..........Haemophilus influenzae type b vaccine</option>
										<option value="HIBMENC,Haemophilus influenza type b, Meningococcal C vaccine">HibMenC..........Haemophilus influenza type b, Meningococcal C vaccine</option>
										<option value="HPV,Human Papillomavirus vaccine">HPV..........Human Papillomavirus vaccine</option>
										<option value="INFLUENZA,Influenza">Influenza..........Influenza</option>
										<option value="IPV,Inactivated polio vaccine">IPV..........Inactivated polio vaccine</option>
										<option value="JAPENC,Japanese encephalitis">JapEnc..........Japanese encephalitis</option>
										<option value="MEASLES,Measles vaccine">Measles..........Measles vaccine</option>
										<option value="MENA,Meningococcal A">MenA..........Meningococcal A</option>
										<option value="MENAC,Meningococcal AC">MenAC..........Meningococcal AC</option>
										<option value="MENACW,Meningococcal ACW">MenACW..........Meningococcal ACW</option>
										<option value="MENACWY,">MenACWY..........Meningococcal ACWY</option>
										<option value="MENBC,Meningococcal ACWY">MenBC..........Meningococcal group B &amp; group C vaccine</option>
										<option value="MENC_CONJ,Meningococcal group B &amp; group C vaccine">MenC_conj..........Meningococcal C conjugate vaccine</option>
										<option value="MM,Measles and mumps vaccine<">MM..........Measles and mumps vaccine</option>
										<option value="MMR,Measles mumps and rubella vaccine">MMR..........Measles mumps and rubella vaccine</option>
										<option value="MMRV,Measles, mumps, rubella and varicella vaccine">MMRV..........Measles, mumps, rubella and varicella vaccine</option>
										<option value="MR,Measles and rubella vaccine">MR..........Measles and rubella vaccine</option>
										<option value="MUMPS,Mumps vaccine">Mumps..........Mumps vaccine</option>
										<option value="OPV,Oral polio vaccine">OPV..........Oral polio vaccine</option>
										<option value="PNEUMO_CONJ,Pneumococcal conjugate vaccine">Pneumo_conj..........Pneumococcal conjugate vaccine</option>
										<option value="PNEUMO_PS,Pneumococcal polysaccharide vaccine">Pneumo_ps..........Pneumococcal polysaccharide vaccine</option>
										<option value="RABIES,Rabies vaccine">Rabies..........Rabies vaccine</option>
										<option value="ROTAVIRUS,Rotavirus vaccine">Rotavirus..........Rotavirus vaccine</option>
										<option value="RUBELLA,Rubella vaccine">Rubella..........Rubella vaccine</option>
										<option value="TBE,Tick borne encephalitis">TBE..........Tick borne encephalitis</option>
										<option value="TD,Tetanus and diphtheria toxoid for older children / adults">Td..........Tetanus and diphtheria toxoid for older children / adults</option>
										<option value="TDAP,Tetanus and diphtheria toxoids and acellular pertussis vaccine">TdaP..........Tetanus and diphtheria toxoids and acellular pertussis vaccine</option>
										<option value="TDAPIPV,Tetanus and diphtheria toxoids and acellular pertussis vaccine IPV">TdaPIPV..........Tetanus and diphtheria toxoids and acellular pertussis vaccine IPV</option>
										<option value="TDIPV,Tetanus and diphtheria toxoid for older children / adults with inactivated Polio vaccine">TdIPV..........Tetanus and diphtheria toxoid for older children / adults with inactivated Polio vaccine</option>
										<option value="TT,Tetanus toxoid">TT..........Tetanus toxoid</option>
										<option value="TYPHOID,Typhoid fever vaccine">Typhoid..........Typhoid fever vaccine</option>
										<option value="TYPHOIDHEPA,Typhoid fever and Hepatitis A vaccine">TyphoidHepA..........Typhoid fever and Hepatitis A vaccine</option>
										<option value="VARICELLA,Varicella vaccine">Varicella..........Varicella vaccine</option>
										<option value="VITAMINA,Vitamin A supplementation">VitaminA..........Vitamin A supplementation</option>
										<option value="YF,Yellow fever vaccine">YF..........Yellow fever vaccine</option>
										<option value="ZOSTER,Varicella vaccine">Zoster..........Varicella vaccine</option>
									</select>
									<input id="VaccineName2" type="text" style="margin-left:10px;width: 150px; float:left;font-size:14px;height:25px" lang="en" placeholder="Vaccine Code">

									<div style="width:10%; float:left; text-align:center; margin-bottom:10px; margin-left:10px;"></div>


									<div style="width:100%; float:left; text-align:center; height:10px; "></div>
									
									<div style="padding:3px;height: 30px;margin-top: 5px ">
										<span style="float: left; text-align:center; font-size:14px; " lang="en">Date: </span>
										<input id="dateEvent2" type="date" style="margin-left:10px; width: 139px; float:left; font-size:14px; height:25px">
									</div>
									<!--<input id="ageEventVac2" type="text" style="margin-left:10px;width: 150px; float:left;font-size:14px;height:25px" lang="en" placeholder="Vaccination Age">-->

									<div style="width:100%; float:left; text-align:center; height:10px; "></div>
										
									

								</div>            
								<a id="ButtonEditVaccine" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-130px; margin-right:19px;"><span lang="en">Edit</span></a>

						</div>
						<!-- END MODAL VIEW FOR EDITING IMMUNIZATION -->';
		}
					
				if($type == 'allergy' && $display_type == 0){
					$page .= '<script type="text/javascript">
					
						var allergy_info = $("#modalAllergy").dialog({bgiframe: true, width: 550, autoOpen: false, height: 500, modal: true,title:"'.$this->translations['allergyhistory'].'"});
						$("#AllergyInfo").live("click",function()	{
						console.log("allergy");
							getAllergy();
							allergy_info.dialog("open");
						});
						
						$("#buttonNoAllergies").click(function() {

						var r=confirm("This action will delete all previous allergies and will prevent future allergies from being displayed.  Is this really what you would like to do?");
							 if (r==true)
							 {
							var url = "medicalPassportUnit.php?data_type=createImmunoData&ajax=false&noallergies=yes&IdUsu='.$this->passport_id.'";
							var Rectipo = LanzaAjax(url);
							getAllergy(0);	
						
							getAllergyInfo();
									}
						});
						
						var allergy_deleted = $("#modalDeletedAllergy").dialog({bgiframe: true, width: 400, height: 400, autoOpen: false, modal: true,title:"'.$this->translations['deletedallergies'].'"});
						  $("#deletedAllergy").live("click",function()	{
								getAllergyDeleted();
								allergy_deleted.dialog("open");
						  });
						  
						  //Deleted Allergy
						$(".DeleteAllergy").live("click",function(){
							var myClass = $(this).attr("id"); 
							//alert (myClass);
							var cadena="medicalPassportUnit.php?data_type=deleteVaccines&ajax=true&doc_id='.$this->med_id.'&id="+myClass;
							var RecTipo=LanzaAjax(cadena);
							getAllergy();
							getAllergyInfo();

						});
						
						function getAllergyInfo()
						{
							var link = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getAllergyInfo&ajax=true";
						
						
							$.ajax({
							   url: link,
							   dataType: "html",
							   async: true,
							   success: function(data)
							   {
									$("#AllergyInfo").html(data);
									var myElement = document.querySelector("#AllergyInfo");
									myElement.style.display = "block";
							   }
							});

						
						}
							
							function getDOB4VaccCalc()
							{
								var queUrl ="medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getDob&ajax=true";
								$.ajax(
								{
									url: queUrl,
									dataType: "json",
									async: false,
									success: function(data)
									{
										DOBData2 = data.items;
									}
								});
							
								numDOB = DOBData2.length;
								return DOBData2[0].dob;
								
							}
						
						function getAllergy()
						{
							var queUrl ="medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getVaccines&ajax=false";
							$.ajax(
							{
								url: queUrl,
								dataType: "json",
								async: false,
								success: function(data)
								{
									VaccinesData = data.items;
								}
							});
						
							numVaccines = VaccinesData.length;
						
							var n = 0;
							var VaccinesBox
							if (numVaccines==0)
							{
							var translation51 = "";

							if(language == "th"){
							translation51 = "No se encontraron datos";
							}else if(language == "en"){
							translation51 = "No Data Found";
							}
								VaccinesBox="<span>"+translation51+"</span>";
							}
							else
							{
								VaccinesBox="";
							}
							
							today = new Date();
							eventd = new Date(2010,05,01); // remember this is equivalent to 06 01 2010
							dob = new Date(2003,8,11); // remember this is equivalent to 06 01 2010

							a = calcDate(eventd,dob)
							
							while (n<numVaccines){
								var del = VaccinesData[n].deleted;
								var VaccCode = VaccinesData[n].VaccCode;
								var VaccName = VaccinesData[n].VaccName;
								var AllerCode = VaccinesData[n].AllerCode;
								var AllerName = VaccinesData[n].AllerName;
								var intensity = VaccinesData[n].intensity;
								var dateEvent = VaccinesData[n].dateEvent;
								var ageEvent = VaccinesData[n].ageEvent;

								var rowid = VaccinesData[n].id;	
					   
								if (VaccName == "")
								{
								var translation31 = "";
								var translation32 = "";
								var translation33 = "";

							if(AllerName == "Environmental"){
							AllerName = "'.$this->translations['environmental'].'";
							} else if(AllerName == "Foods"){
							AllerName = "'.$this->translations['foods'].'";
							} else if(AllerName == "Drugs"){
							AllerName = "'.$this->translations['drugs'].'";
							} else if(AllerName == "Other"){
							AllerName = "'.$this->translations['other'].'";
							}
							
									var isAllergy = 1;
									var leftcolumn = "'.$this->translations['allergicto'].' "+AllerName;
									var middlecolumn = "'.$this->translations['since'].' "+ageEvent+" '.$this->translations['ofage'].'";
								
							if(del==0)
								{
								VaccinesBox += "<div class=\"InfoRow\">";
										
								
										VaccinesBox += "<div style=\"width:210px; float:left; text-align:left;\"><span class=\"PatName\">"+leftcolumn+"</span></div>";
										VaccinesBox += "<div style=\"width:140px; float:left; text-align:left; color:#22aeff;\"><span class=\"DrName\">"+middlecolumn +" </span></div>";
										VaccinesBox += "<div class=\"EditAllergy\" id="+rowid+" style=\"width:60px; float:right;margin-right:10px;\"><a id="+rowid+"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:green;\" lang=\"en\">Edit</a></div>";
										VaccinesBox += "<div class=\"DeleteAllergy\" id="+rowid+" style=\"width:60px; float:right;\"><a id="+rowid+"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:red;\" lang=\"en\">'.$this->translations['delete'].'</a></div>";
									
								
								VaccinesBox += "</div>";
								}
								}
								n++;
							}
							$("#AllergyContainer").html(VaccinesBox);
							//alert (RelativesBox);
							
						}
						
						$(".EditAllergy").live("click",function(){
						var translation25 = "edit allergig";
							var myClass = $(this).attr("id"); 
							//alert (myClass);
							$("#editAllergyModal").dialog({bgiframe: true, width: 550, height: 380, modal: true,title:translation25});
							getAllergiesEdit(myClass);
						});
						
						function getAllergiesEdit(toShow)
						{
							var queUrl ="medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getVaccines&ajax=true";
							$.ajax(
							{
								url: queUrl,
								dataType: "json",
								async: false,
								success: function(data)
								{
									VaccinesData = data.items;
								}
							});
						
							numVaccines = VaccinesData.length;
						
							var n = 0;
							var VaccinesBox
							if (numVaccines==0)
							{
							var translation51 = "";

							if(language == "th"){
							translation51 = "No se encontraron datos";
							}else if(language == "en"){
							translation51 = "No Data Found";
							}
								VaccinesBox="<span>"+translation51+"</span>";
							}
							else
							{
								VaccinesBox="";
							}
							
							today = new Date();
							eventd = new Date(2010,05,01); // remember this is equivalent to 06 01 2010
							dob = new Date(2003,8,11); // remember this is equivalent to 06 01 2010

							a = calcDate(eventd,dob)
							
							while (n<numVaccines){
								var del = VaccinesData[n].deleted;
								var VaccCode = VaccinesData[n].VaccCode;
								var VaccName = VaccinesData[n].VaccName;
								var AllerCode = VaccinesData[n].AllerCode;
								var AllerName = VaccinesData[n].AllerName;
								var intensity = VaccinesData[n].intensity;
								var dateEvent = VaccinesData[n].dateEvent;
								var ageEvent = VaccinesData[n].ageEvent;

								var rowid = VaccinesData[n].id;	
					   
								if (VaccName == "")
								{
								
							if(AllerName == "Environmental"){
							AllerName = "'.$this->translations['environmental'].'";
							} else if(AllerName == "Foods"){
							AllerName = "'.$this->translations['foods'].'";
							} else if(AllerName == "Drugs"){
							AllerName = "'.$this->translations['drugs'].'";
							} else if(AllerName == "Other"){
							AllerName = "'.$this->translations['other'].'";
							}
							
									var isAllergy = 1;
									var leftcolumn = "'.$this->translations['allergicto'].' "+AllerName;
									var middlecolumn = "'.$this->translations['since'].' "+ageEvent+" '.$this->translations['ofage'].'";
								
					if(del==0)
								{
								if(toShow == rowid){
								$("#AllergyName2").val(AllerName);
								
								VaccinesBox += "<div class=\"InfoRow\">";
										
								
										VaccinesBox += "<div style=\"width:210px; float:left; text-align:left;\"><span class=\"PatName\">"+leftcolumn+"</span></div>";
										VaccinesBox += "<div style=\"width:140px; float:left; text-align:left; color:#22aeff;\"><span class=\"DrName\">"+middlecolumn +" </span></div>";
										VaccinesBox += "<div class=\"DeleteAllergy\" id="+rowid+" style=\"width:60px; float:left;\">'.$this->translations['editing'].'</div>";
									
								
								VaccinesBox += "</div>";
								}
								}
								}
								n++;
							}
							$("#AllergyContainer2").html(VaccinesBox);
							//alert (RelativesBox);
							var rowtrack = $("#DiagnosticRow2").val(toShow);
						}
						
						$("#ButtonEditAllergy").click(function() {
							var idrow = $("#DiagnosticRow2").val();
								var value = $("#VaccineCode2 option:selected").text();
								var value = $("#VaccineCode2 option:selected").val();
								var split = value.split(",");
								var v1 = split[0];
								var v2 = split[1];		
								var VaccCode = v1;
								var VaccName = $("#VaccineName2").val();
						//		alert(idrow);

								var value = $("#AllergyCode2 option:selected").text();
								var value = $("#AllergyCode2 option:selected").val();
								var split = value.split(",");
								var v1 = split[0];
								var v2 = split[1];		
								var AllerCode = v1;
								var AllerName = $("#AllergyName2").val();

								if(AllerName == "")
								{
									alert("Select Allergy");
									$("#VaccineCode2").focus();
									return;			
								}

								var intensity = $("#intensity2").val();
								var dateEvent = $("#dateEventAll2").val();
								var ageEvent = $("#ageEventAll2").val();

								if(ageEvent == "" && dateEvent == "")
								{
									alert("Select Age at the time of Event");
									$("#AgeEvent2").focus();
									return;			
								}
								
								var dob = getDOB4VaccCalc();
								
								var url = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=createImmunoData&ajax=1&VaccCode="+VaccCode+"&VaccName="+VaccName+"&AllerCode="+AllerCode+"&AllerName="+AllerName+"&intensity="+intensity+"&dateEvent="+dateEvent+"&ageEvent="+ageEvent+"&dob="+dob+"&rowediter="+idrow;
								var Rectipo = LanzaAjax(url);
								getAllergy(0);		
							
								//clear data
								$("#VaccCode2").val("");
								$("#VaccName2").val("");
								$("#AllerCode2").val("");
								$("#AllerName2").val("");
								$("#intensity2").val("");
								$("#intensitylabel2").val("");
								$("#dateEvent2").val("");
								$("#ageEvent2").val("");
								$("#DiagnosticRow2").val("");

								getAllergyInfo();
								$("#editAllergyModal").dialog("close");
							});
						
						$("#ButtonAddAllergy").click(function() {
							/*var value = $("#VaccineCode option:selected").text();
							var value = $("#VaccineCode option:selected").val();
							var split = value.split(",");
							var v1 = split[0];
							var v2 = split[1];*/		
							var VaccCode = "";
							var VaccName = "";

							var value = $("#AllergyCode option:selected").text();
							var value = $("#AllergyCode option:selected").val();
							var split = value.split(",");
							var v1 = split[0];
							var v2 = split[1];		
							var AllerCode = v1;
							var AllerName = $("#AllergyName").val();

							if(AllerName == "")
							{
								alert("Select Allergy");
								$("#VaccineCode").focus();
								return;			
							}

							var intensity = $("#intensity").val();
							var dateEvent = $("#dateEventAll").val();
							var ageEvent = $("#ageEventAll").val();

							if(ageEvent == "" && dateEvent == "")
							{
								alert("Select Age at the time of Event");
								$("#AgeEvent").focus();
								return;			
							}
							
							var dob = getDOB4VaccCalc();
							
							var url = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=createImmunoData&ajax=true&VaccCode="+VaccCode+"&VaccName="+VaccName+"&AllerCode="+AllerCode+"&AllerName="+AllerName+"&intensity="+intensity+"&dateEvent="+dateEvent+"&ageEvent="+ageEvent+"&dob="+dob;
							var Rectipo = LanzaAjax(url);
							getAllergy(0);		
						
							//clear data
							$("#VaccCode").val("");
							$("#VaccName").val("");
							$("#AllerCode").val("");
							$("#AllerName").val("");
							$("#intensity").val("");
							$("#intensitylabel").val("");
							$("#dateEvent").val("");
							$("#ageEvent").val("");

							getAllergyInfo();
									
						});
						
						function getAllergyDeleted()
						{
							var queUrl ="medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getVaccines&ajax=true";
							$.ajax(
							{
								url: queUrl,
								dataType: "json",
								async: false,
								success: function(data)
								{
									VaccinesData = data.items;
								}
							});
						
							numVaccines = VaccinesData.length;
						
							var n = 0;
							var VaccinesBox
							if (numVaccines==0)
							{
							var translation51 = "";

							if(language == "th"){
							translation51 = "No se encontraron datos";
							}else if(language == "en"){
							translation51 = "No Data Found";
							}
								VaccinesBox="<span>"+translation51+"</span>";
							}
							else
							{
								VaccinesBox="";
							}
							
							today = new Date();
							eventd = new Date(2010,05,01); // remember this is equivalent to 06 01 2010
							dob = new Date(2003,8,11); // remember this is equivalent to 06 01 2010

							a = calcDate(eventd,dob)
							
							while (n<numVaccines){
								var del = VaccinesData[n].deleted;
								var VaccCode = VaccinesData[n].VaccCode;
								var VaccName = VaccinesData[n].VaccName;
								var AllerCode = VaccinesData[n].AllerCode;
								var AllerName = VaccinesData[n].AllerName;
								var intensity = VaccinesData[n].intensity;
								var dateEvent = VaccinesData[n].dateEvent;
								var ageEvent = VaccinesData[n].ageEvent;

								var rowid = VaccinesData[n].id;	
					   
								if (VaccName == "")
								{
									var isAllergy = 1;
									var leftcolumn = "Allergic to "+AllerName;
									var middlecolumn =" since "+ageEvent+" of age";
								
					if(del==1)
								{
								VaccinesBox += "<div class=\"InfoRow\">";
										
								
										VaccinesBox += "<div style=\"width:175px; float:left; text-align:left;\"><span class=\"PatName\"><font size=\"0\">"+leftcolumn+"</font></span></div>";
										VaccinesBox += "<div style=\"width:125px; float:left; text-align:left; color:#22aeff;\"><span class=\"DrName\"><font size=\"0\">"+middlecolumn +" </font></span></div>";
								//		VaccinesBox += "<div class=\"DeleteAllergy\" id=\"\"+rowid+\"\" style=\"width:60px; float:right;\"><a id=\"\"+rowid+\"\"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:red;\">'.$this->translations['delete'].'</a></div>";
									
								
								VaccinesBox += "</div>";
								}
								}
								n++;
							}
							$("#AllergyContainerDeleted").html(VaccinesBox);
							//alert (RelativesBox);
							
						}
						
						function calcDate(date1,date2) {
							var diff = Math.floor(date1.getTime() - date2.getTime());
							var day = 1000* 60 * 60 * 24;
						
							var days = Math.floor(diff/day);
							var months = Math.floor(days/31);
							var years = Math.floor(months/12);
						
							var message = date2.toDateString();
							message += " was "
							message += days + " days " 
							message += months + " months "
							message += years + " years ago \n"
								
							var cadena = Array();
							cadena[0] = days;
							cadena[1] = months;
							cadena[2] = years;
							
							return cadena;
							
							}
							
							function LanzaAjax (DirURL)
							{
								var RecTipo = "SIN MODIFICACIÓN";
								$.ajax(
								   {
								   url: DirURL,
								   dataType: "html",
								   async: false,
								   complete: function(){ //alert("Completed");
											},
								   success: function(data) {
											
									   //Below line added by Pallab for testing
									   console.log(data);
									   if (typeof data == "string") {
														RecTipo = data;
														}
											 }
									});
								return RecTipo;
							}';
				}elseif($display_type == 0){
					$page .= '<script type="text/javascript">
					
						var vaccine_info = $("#modalImmunizationAllergy").dialog({bgiframe: true, width: 550, autoOpen: false, height: 500, modal: true,title:"'.$this->translations['immuhistory'].'"});
						$("#ImmunizationAllergyInfo").live("click",function()	{
						//console.log("immu");
							getVaccines();
							vaccine_info.dialog("open");
						});
						
						$(".EditVaccine").live("click",function(){
						var translation24 = "edit Vacccine";
							var myClass = $(this).attr("id"); 
							//alert (myClass);
							$("#editVaccineModal").dialog({bgiframe: true, width: 550, height: 380, modal: true,title:translation24});
							getVaccinesEdit(myClass);
						});
						
						var vaccine_deleted = $("#modalDeletedImmunizations").dialog({bgiframe: true, width: 400, height: 400, autoOpen: false, modal: true,title:"'.$this->translations['deletedimmunizations'].'"});
						$("#deletedImmunizations").live("click",function()	{
								getVaccinesDeleted();
								vaccine_deleted.dialog("open");
						  });
						  
						  $("#ButtonAddVaccineCalendar").click(function() {
								var value = $("#VaccineCalendarImport option:selected").text();
								var value = $("#VaccineCalendarImport option:selected").val();
								dob = getDOB4VaccCalc();

								var url = "medicalPassportUnit.php?data_type=createImmunoCalendar&ajax='.$_GET['ajax'].'&country="+value+"&dob="+dob+"&IdUsu='.$this->passport_id.'";
								var Rectipo = LanzaAjax(url);
								getVaccines();	
										
							
								//clear data
								$("#VaccineCalendarImport").val("");
								getImmunoAllergyInfo();
								getAllergyInfo();
										
							});
						  
						  function getVaccinesEdit(toShow)
							{
								var queUrl ="medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getVaccines&ajax=true";
								$.ajax(
								{
									url: queUrl,
									dataType: "json",
									async: false,
									success: function(data)
									{
										VaccinesData = data.items;
									}
								});
							
								numVaccines = VaccinesData.length;
							
								var n = 0;
								var VaccinesBox
								if (numVaccines==0)
								{
									VaccinesBox="<span>'.$this->translations['nodatafound'].'</span>";
								}
								else
								{
									VaccinesBox="";
								}
								
								today = new Date();
								eventd = new Date(2010,05,01); // remember this is equivalent to 06 01 2010
								dob = new Date(2003,8,11); // remember this is equivalent to 06 01 2010

								a = calcDate(eventd,dob)
								
								while (n<numVaccines){
									var del = VaccinesData[n].deleted;
									var VaccCode = VaccinesData[n].VaccCode;
									var VaccName = VaccinesData[n].VaccName;
									var AllerCode = VaccinesData[n].AllerCode;
									var AllerName = VaccinesData[n].AllerName;
									var intensity = VaccinesData[n].intensity;
									var dateEvent = VaccinesData[n].dateEvent;
									var ageEvent = VaccinesData[n].ageEvent;

									var rowid = VaccinesData[n].id;	
									
									

									if (VaccName != "")
									{
										var isAllergy = 0;
										var leftcolumn = VaccCode;
										var middlecolumn = "at "+ ageEvent+" of age";


									if(del==0)
									{
									if(toShow == rowid){
									$("#VaccineName2").val(VaccName);
									$("#VaccineCode2").val(VaccCode);
									VaccinesBox += "<div class=\"InfoRow\">";
											
											VaccinesBox += "<div style=\"width:180px; float:left; text-align:left;\"><span class=\"PatName\">"+leftcolumn+"</span></div>";
											VaccinesBox += "<div style=\"width:160px; float:left; text-align:left; color:#22aeff;\"><span class=\"DrName\">"+middlecolumn +" </span></div>";
											VaccinesBox += "<div class=\"DeleteVaccine\" id="+rowid+" style=\"width:60px; float:left;\">'.$this->translations['editing'].'</div>";
									
									
									VaccinesBox += "</div>";
									}
									}
									}
									n++;
							
								}
								$("#VaccinesContainer2").html(VaccinesBox);
								//alert (RelativesBox);
								var rowtrack = $("#DiagnosticRow2").val(toShow);
							}
							
							$("#ButtonEditVaccine").click(function() {
								var idrow = $("#DiagnosticRow2").val();
								var value = $("#VaccineCode2 option:selected").text();
								var value = $("#VaccineCode2 option:selected").val();
								var split = value.split(",");
								var v1 = split[0];
								var v2 = split[1];		
								var VaccCode = v1;
								var VaccName = $("#VaccineName2").val();
						//		alert(idrow);

								var value = $("#AllergyCode option:selected").text();
								var value = $("#AllergyCode option:selected").val();
								var split = value.split(",");
								var v1 = split[0];
								var v2 = split[1];		
								var AllerCode = v1;
								var AllerName = $("#AllergyName").val();

								if(VaccName == "")
								{
									alert("Select Vaccine");
									$("#VaccineCode2").focus();
									return;			
								}

								var intensity = $("#intensity").val();
								var dateEvent = $("#dateEvent2").val();
								var ageevent = $("#ageEventVac2").val();

								
								if(ageevent == "" && dateEvent == "")
								{
									alert("Select Age at the time of Event");
									$("#AgeEvent2").focus();
									return;			
								}
								
								
								var dob = getDOB4VaccCalc();
								
								var url = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=createImmunoData&ajax=true&VaccCode="+VaccCode+"&VaccName="+VaccName+"&AllerCode="+AllerCode+"&AllerName="+AllerName+"&intensity="+intensity+"&dateEvent="+dateEvent+"&ageEvent="+ageevent+"&dob="+dob+"&rowediter="+idrow;
								var Rectipo = LanzaAjax(url);
								getVaccines();		
							
								//clear data
								$("#VaccCode").val("");
								$("#VaccName").val("");
								$("#AllerCode").val("");
								$("#AllerName").val("");
								$("#intensity").val("");
								$("#intensitylabel").val("");
								$("#dateEvent").val("");
								$("#ageEvent").val("");
								$("#DiagnosticRow2").val("");

								getImmunoAllergyInfo();
								$("#editVaccineModal").dialog("close");
							});
						  
						  $("#ButtonAddVaccine").click(function() {
								var value = $("#VaccineCode option:selected").text();
								var value = $("#VaccineCode option:selected").val();
								var split = value.split(",");
								var v1 = split[0];
								var v2 = split[1];		
								var VaccCode = v1;
								var VaccName = $("#VaccineName").val();

								/*var value = $("#AllergyCode option:selected").text();
								var value = $("#AllergyCode option:selected").val();
								var split = value.split(",");
								var v1 = split[0];
								var v2 = split[1];	*/	
								var AllerCode = "";
								var AllerName = "";
						//		alert(VaccName);
								if(VaccCode == "" && VaccName == "")
								{
									alert("Select Vaccine or Input Vaccine");
									$("#VaccineCode").focus();
									return;			
								}

								var intensity = $("#intensity").val();
								var dateEvent = $("#dateEvent").val();
								var ageevent = $("#ageEventVac").val();

								
								if(ageevent == "" && dateEvent == "")
								{
									alert("Select Age at the time of Event");
									$("#AgeEvent").focus();
									return;			
								}
								
								
								var dob = getDOB4VaccCalc();
								
								console.log("Vacc Code: " + VaccCode + ", Vacc Name: " + VaccName + ", Date: " + dateEvent + ", DOB: " + dob);
								
								var url = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=createImmunoData&ajax=true&VaccCode="+VaccCode+"&VaccName="+VaccName+"&AllerCode="+AllerCode+"&AllerName="+AllerName+"&intensity="+intensity+"&dateEvent="+dateEvent+"&ageEvent="+ageevent+"&dob="+dob;
								var Rectipo = LanzaAjax(url);
								getVaccines();		
							
								//clear data
								$("#VaccCode").val("");
								$("#VaccName").val("");
								$("#AllerCode").val("");
								$("#AllerName").val("");
								$("#intensity").val("");
								$("#intensitylabel").val("");
								$("#dateEvent").val("");
								$("#ageEvent").val("");

								getImmunoAllergyInfo();
										
							});
						  
						  $(".DeleteVaccine").live("click",function(){
							var myClass = $(this).attr("id"); 
							//alert (myClass);
							var cadena="medicalPassportUnit.php?data_type=deleteVaccines&ajax='.$_GET['ajax'].'&doc_id='.$this->med_id.'&id="+myClass;
							var RecTipo=LanzaAjax(cadena);
							getVaccines();
							getImmunoAllergyInfo();

						});
						
						function getImmunoAllergyInfo()
						{
							var link = "medicalPassportUnit.php?data_type=getImmunoInfo&ajax=true&doc_id='.$this->med_id.'&IdUsu='.$this->passport_id.'";
						
							$.ajax({
							   url: link,
							   dataType: "html",
							   async: true,
							   success: function(data)
							   {
									$("#ImmunizationAllergyInfo").html(data);
									var myElement = document.querySelector("#ImmunizationAllergyInfo");
									//$("#H2M_SpinA").css("visibility","hidden");
									myElement.style.display = "block";
							   }
							});

						
						}
						  
						function getVaccines()
						{
							var queUrl ="medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getVaccines&ajax=true";
							$.ajax(
							{
								url: queUrl,
								dataType: "json",
								async: false,
								success: function(data)
								{
									VaccinesData = data.items;
								}
							});
						
							numVaccines = VaccinesData.length;
						
							var n = 0;
							var VaccinesBox
							if (numVaccines==0)
							{
							var translation51 = "";

							if(language == "th"){
							translation51 = "No se encontraron datos";
							}else if(language == "en"){
							translation51 = "No Data Found";
							}
								VaccinesBox="<span>"+translation51+"</span>";
							}
							else
							{
								VaccinesBox="";
							}
							
							today = new Date();
							eventd = new Date(2010,05,01); // remember this is equivalent to 06 01 2010
							dob = new Date(2003,8,11); // remember this is equivalent to 06 01 2010

							a = calcDate(eventd,dob)
							
							while (n<numVaccines){
								var del = VaccinesData[n].deleted;
								var VaccCode = VaccinesData[n].VaccCode;
								var VaccName = VaccinesData[n].VaccName;
								var AllerCode = VaccinesData[n].AllerCode;
								var AllerName = VaccinesData[n].AllerName;
								var intensity = VaccinesData[n].intensity;
								var dateEvent = VaccinesData[n].dateEvent;
								var ageEvent = VaccinesData[n].ageEvent;

								var rowid = VaccinesData[n].id;	

								if (VaccName != "")
								{
									var isAllergy = 0;
									var leftcolumn = VaccCode;
									var middlecolumn = "at "+ ageEvent+" of age";
								

								if(del==0)
								{
								VaccinesBox += "<div class=\"InfoRow\">";
										
								
										VaccinesBox += "<div style=\"width:180px; float:left; text-align:left;\"><span class=\"PatName\">"+leftcolumn+"</span></div>";
										VaccinesBox += "<div style=\"width:150px; float:left; text-align:left; color:#22aeff;\"><span class=\"DrName\">"+middlecolumn +" </span></div>";
										VaccinesBox += "<div class=\"EditVaccine\" id="+rowid+" style=\"width:60px; float:right;margin-right:10px;\"><a id="+rowid+"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:green;\" lang=\"en\">Edit</a></div>";
										VaccinesBox += "<div class=\"DeleteVaccine\" id="+rowid+" style=\"width:60px; float:right;\"><a id="+rowid+"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:red;\" lang=\"en\">'.$this->translations['delete'].'</a></div>";
								
								
								VaccinesBox += "</div>";
								}
								}
								n++;
						
							}
							$("#VaccinesContainer").html(VaccinesBox);
							//alert (RelativesBox);
							
						}
						
						function getVaccinesDeleted()
						{
							var queUrl ="medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getVaccines&ajax=true";
							$.ajax(
							{
								url: queUrl,
								dataType: "json",
								async: false,
								success: function(data)
								{
									VaccinesData = data.items;
								}
							});
						
							numVaccines = VaccinesData.length;
						
							var n = 0;
							var VaccinesBox
							if (numVaccines==0)
							{
							var translation51 = "";

							if(language == "th"){
							translation51 = "No se encontraron datos";
							}else if(language == "en"){
							translation51 = "No Data Found";
							}
								VaccinesBox="<span>"+translation51+"</span>";
							}
							else
							{
								VaccinesBox="";
							}
							
							today = new Date();
							eventd = new Date(2010,05,01); // remember this is equivalent to 06 01 2010
							dob = new Date(2003,8,11); // remember this is equivalent to 06 01 2010

							a = calcDate(eventd,dob)
							
							while (n<numVaccines){
								var del = VaccinesData[n].deleted;
								var VaccCode = VaccinesData[n].VaccCode;
								var VaccName = VaccinesData[n].VaccName;
								var AllerCode = VaccinesData[n].AllerCode;
								var AllerName = VaccinesData[n].AllerName;
								var intensity = VaccinesData[n].intensity;
								var dateEvent = VaccinesData[n].dateEvent;
								var ageEvent = VaccinesData[n].ageEvent;

								var rowid = VaccinesData[n].id;	

								if (VaccName != "")
								{
									var isAllergy = 0;
									var leftcolumn = VaccCode;
									var middlecolumn = "at "+ ageEvent+" of age";
								

								if(del==1)
								{
								VaccinesBox += "<div class=\"InfoRow\">";
										
								
										VaccinesBox += "<div style=\"width:150px; float:left; text-align:left;\"><span class=\"PatName\"><font size=\"0\">"+leftcolumn+"</font></span></div>";
										VaccinesBox += "<div style=\"width:150px; float:left; text-align:left; color:#22aeff;\"><span class=\"DrName\"><font size=\"0\">"+middlecolumn +" </font></span></div>";								
								
								VaccinesBox += "</div>";
								}
								}
								n++;
						
							}
							$("#VaccinesContainerDeleted").html(VaccinesBox);
							//alert (RelativesBox);
							
						}
						
						function calcDate(date1,date2) {
							var diff = Math.floor(date1.getTime() - date2.getTime());
							var day = 1000* 60 * 60 * 24;
						
							var days = Math.floor(diff/day);
							var months = Math.floor(days/31);
							var years = Math.floor(months/12);
						
							var message = date2.toDateString();
							message += " was "
							message += days + " days " 
							message += months + " months "
							message += years + " years ago \n"
								
							var cadena = Array();
							cadena[0] = days;
							cadena[1] = months;
							cadena[2] = years;
							
							return cadena;
							
							}
							
							function LanzaAjax (DirURL)
							{
								var RecTipo = "SIN MODIFICACIÓN";
								$.ajax(
								   {
								   url: DirURL,
								   dataType: "html",
								   async: false,
								   complete: function(){ //alert("Completed");
											},
								   success: function(data) {
											
									   //Below line added by Pallab for testing
									   console.log(data);
									   if (typeof data == "string") {
														RecTipo = data;
														}
											 }
									});
								return RecTipo;
							}';
				}
						
					
				$page .= '</script>';	
		
		if($display_type == 0){
			return $page;
		}else{
			echo $page;
		}
	}
	
	public function getDiagnosticInfo($display_type){
		$Query= $this->con->prepare("select id,DATE_FORMAT(dxstart,'%b %Y') as sdate,dxstart,dxname,dxcode,notes,doctor_signed,latest_update from p_diagnostics where idpatient=?  and deleted=0 order by dxstart desc");
		$Query->bindValue(1, $this->passport_id, PDO::PARAM_INT);
		$result=$Query->execute();
		$page = '';
		$count = $Query->rowCount();

		if($count==0)
		{
			$page .= '<div  style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #2c3e50; color: white;line-height: 20px;">';
			//Personal history trans
			$page .= $this->translations['personalhistory'];
			$page .= '</div><center>';
			//No entries trans
			$page .= $this->translations['noentries'];
			$page .= '</center></span><p><center><img width="75px" src="../../images/icons/general_user_error_icon.png" alt="No Data Icon"></center>';
			$page .= '<!-- MODAL VIEW FOR DIAGNOSTICS -->
				<div id="modalDiagnostics" style="display:none; text-align:center; padding:20px;">
					<div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Diagnostics for </span> '.$this->member_name.' '.$this->member_surname.'</div>
					<div id="deletedDiagnostics"><img class="btn" src="../../images/icons/trashicon.jpg" style="margin-left:25px;border:solid 1px #cacaca;" alt="Deleted Medications" width="15" height="15"></div>
					<div id="DiagnosticContainer" style="border:solid 1px #cacaca; height:150px; margin-top:30px; padding-top:5px; overflow:auto;">
					</div>
					<div style="border:solid 1px #cacaca;  margin-top:20px; padding:10px;height:240px">
						<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Diagnostic Name: </span>
						<input id="field_id1" type="text" style="width: 1px; margin-top:5px;margin-left:10px; visibility:hidden;"><input id="DiagnosticBox" type="text" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:25px" lang="en" placeholder="Enter Diagnostic Name">
						<!--<input id="ICD_id" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;">-->
						
						<!--if($isPatient != 1){
						echo "<input id="ICDBox" type="text" style="margin-left:10px;width: 60px; float:right;font-size:14px;height:25px" placeholder="ICD 10">";
						}else{
						echo "<input id="ICDBox" type="hidden" style="margin-left:10px;width: 60px; float:left;font-size:14px;height:25px" placeholder="ICD 10">";
						}--> 
						
						
						<div style="padding:3px;height: 30px;margin-top: 5px ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Start Date: </span>
							<input id="DiagnosticStart" type="date" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:25px">
						</div>
						<div style="width:100%;"></div>
						<div style="padding:3px;height: 30px;margin-top: 5px ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">End Date: </span>
							<input id="DiagnosticEnd" type="date" style="margin-left:18px;width: 200px; float:left;font-size:14px;height:25px">
						</div>		
						<div style="width:100%;"></div>
						<div style="padding:3px;height: 30px;margin-top: 5px ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Notes: </span>
							<textarea rows="3" col="550" id="DiagnosticNotes" style="margin-left:38px;float:left;font-size:14px;width:250px"></textarea>
						</div>		
						
						<a id="ButtonAddDiagnostic" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-54px;"><span lang="en">Add</span></a>
						<a id="buttonNoDiagnostics" style="float:left; text-align:center; margin-left: 5px; width: 150px; font-size:12px; height:17px;" class="btn" lang="en">No Incidents to Report</a>
					</div>
				</div>
				<!-- END DIAGNOSTIC MODAL-->
				<!-- Model view for deleted diagnostics -->
				<div id="modalDeletedDiagnostics" style="display:none; text-align:center; padding:20px;">

					<div style="color:#22aeff; font-size:14px; text-align: right; width: 80%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Deleted diagnostics for </span>'.$this->member_name.' '.$this->member_surname.'</div>
					<div><img id="deletedDiagnostics" src="../../images/icons/trashicon.jpg" style="margin-left:25px;" alt="Deleted Diagnostics" width="30" height="30"></div>
					<div id="DiagnosticContainerDeleted" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
					</div>

					</div>
				<!--END MODAL DELETED DIAGNOSTICS-->';
				if($display_type == 0){
			$page .= '<script type="text/javascript">
					var diagnostic_info = $("#modalDiagnostics").dialog({bgiframe: true, width: 550, autoOpen: false, height: 570, modal: true,title:"'.$this->translations['diagnostics'].'"});
					$("#DiagnosticHistoryInfo").live("click",function()	{
						//alert("clicked");
						getDiagnostics();
						
						diagnostic_info.dialog("open");
					});
					
					var diagnostic_deleted = $("#modalDeletedDiagnostics").dialog({bgiframe: true, width: 400, height: 400, autoOpen: false, modal: true,title:"'.$this->translations['diagnosticsdeleted'].'"});
					$("#deletedDiagnostics").live("click",function()	{
						getDiagnosticsDeleted();
						diagnostic_deleted.dialog("open");
					  });
					  
					var prev_click="";
					$(".DeleteDiagnostic").live("click",function(){
						var myClass = $(this).attr("id"); 
						//alert (myClass);
						prev_click="delete";
						var cadena="medicalPassportUnit.php?data_type=deleteDiagnostics&ajax='.$_GET['ajax'].'&doc_id='.$this->med_id.'&id="+myClass;
						var RecTipo=LanzaAjax(cadena);
						getDiagnostics(0);
						getDiagnosticHistoryInfo();

					});
					
					function getDiagnostics(toShow)
					{
						//alert(toShow);
						var queUrl ="medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getDiagnostics&ajax=true";
						$.ajax(
						{
							url: queUrl,
							dataType: "json",
							async: false,
							success: function(data)
							{
								DiagnosticData = data.items;
							}
						});
					
						numDiagnostics = DiagnosticData.length;
					
						var n = 0;
						var DiagnosticBox;
						if (numDiagnostics==0)
						{
						var translation51 = "";

						if(language == "th"){
						translation51 = "No se encontraron datos";
						}else if(language == "en"){
						translation51 = "No Data Found";
						}
							DiagnosticBox="<span>"+translation51+"</span>";
						}
						else
						{
							DiagnosticBox="";
						}
						
						while (n<numDiagnostics){
							var del = DiagnosticData[n].deleted;
							var dxname = DiagnosticData[n].dxname;
							var dxcode = DiagnosticData[n].dxcode;
							var sdate = DiagnosticData[n].sdate;
							var edate = DiagnosticData[n].edate;	
							var notes = DiagnosticData[n].notes;
							var rowid = DiagnosticData[n].id;	
				if(del==0)
							{			
							DiagnosticBox += "<div class=\"InfoRow DiagnosticRow\" id="+rowid+">";
							
							var middleColumn = sdate;
							
							if(edate.length>0)
							{
								middleColumn = middleColumn + "-" + edate;
							}
							
							if(notes.length==0)
							{
								notes = "No Notes Recorded";
							}	
							
							
							if(rowid==toShow)
							{
								DiagnosticBox += "<div style=\"width:10px; float:left; text-align:left;\"><i class=\"icon-chevron-down\"></i></div>";
							}
							
							
							DiagnosticBox += "<div style=\"width:140px; float:left; text-align:left;cursor:pointer\"><span class=\"PatName\" style=\"white-space:nowrap\">"+dxname.substr(0, 15)+"...</span></div>";
							DiagnosticBox += "<div style=\"width:190px; float:left; text-align:center; color:#22aeff;\"><span class=\"DrName\">"+middleColumn +" </span></div>";
							DiagnosticBox += "<div class=\"EditDiagnostic\" id="+rowid+" style=\"width:60px; float:right;margin-right:10px;\"><a id="+rowid+"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:green;\" lang=\"en\">Edit</a></div>";
							DiagnosticBox += "<div class=\"DeleteDiagnostic\" id="+rowid+" style=\"width:60px; float:right;\"><a id="+rowid+"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:red;\" lang=\"en\">'.$this->translations['delete'].'</a></div>";
							//console.log(DiagnosticBox);
														
							DiagnosticBox += "</div>";
							var temp_var = "Note"+rowid;
							DiagnosticBox += "<div class=\"InfoRow NotesRow\" id=\""+temp_var+"\"";
							if(rowid==toShow)
							{
								DiagnosticBox += ">";
							}
							else
							{
								DiagnosticBox += " style=\"display:none\">";
							}
							
							if(del==0)
							{
								DiagnosticBox += "<div style=\"width:100%; float:left; text-align:left;\">"+notes+"</div>";
							}
							else
							{
								DiagnosticBox += "<div style=\"width:100%; float:left; text-align:left;\"><strike>"+notes+"</strike></div>";
							}
							DiagnosticBox += "</div>";
							}
							n++;
						}
						$("#DiagnosticContainer").html(DiagnosticBox);
				
					}
					
					function getDiagnosticsDeleted(toShow)
						{
							//alert(toShow);
							var queUrl ="medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getDiagnostics&ajax=true";
							$.ajax(
							{
								url: queUrl,
								dataType: "json",
								async: false,
								success: function(data)
								{
									DiagnosticData = data.items;
								}
							});
						
							numDiagnostics = DiagnosticData.length;
						
							var n = 0;
							var DiagnosticBox;
							if (numDiagnostics==0)
							{
							var translation51 = "";

							if(language == "th"){
							translation51 = "No se encontraron datos";
							}else if(language == "en"){
							translation51 = "No Data Found";
							}
								DiagnosticBox="<span>"+translation51+"</span>";
							}
							else
							{
								DiagnosticBox="";
							}
							
							while (n<numDiagnostics){
								var del = DiagnosticData[n].deleted;
								var dxname = DiagnosticData[n].dxname.substr(0, 30);
								var dxcode = DiagnosticData[n].dxcode;
								var sdate = DiagnosticData[n].sdate;
								var edate = DiagnosticData[n].edate;	
								var notes = DiagnosticData[n].notes;
								var rowid = DiagnosticData[n].id;	
								if(del==1)
								{			
									DiagnosticBox += "<div class=\"InfoRow DiagnosticRow\" id="+rowid+">";
									var middleColumn = sdate;
									
									if(edate.length>0)
									{
										middleColumn = middleColumn + "-" + edate;
									}
									
									if(notes.length==0)
									{
										notes = "No Notes Recorded";
									}	
									
									if(rowid==toShow)
									{
										DiagnosticBox += "<div style=\"width:10px; float:left; text-align:left;\"><i class=\"icon-chevron-down\"></i></div>";
									}
									
									DiagnosticBox += "<div style=\"width:175px; float:left; text-align:left;cursor:pointer\"><span class=\"PatName\" style=\"white-space:nowrap\"><font size=\"1\">"+dxname.substr(0, 30)+"</font></span></div>";
									DiagnosticBox += "<div style=\"width:125px; float:left; text-align:center; color:#22aeff;\"><span class=\"DrName\"><font size=\"1\">"+middleColumn +" </font></span></div>";
									DiagnosticBox += "</div>";
								}
								n++;
							}
							$("#DiagnosticContainerDeleted").html(DiagnosticBox);
							//adjustHeights(".PatName");
						}
						
						$("#ButtonAddDiagnostic").click(function() {
							var dxname = $("#DiagnosticBox").val();
					//		var dxcode = $("#field_id1").val();
							var dxcode = $("#ICDBox").val();
							var sdate = $("#DiagnosticStart").val();
							var edate = $("#DiagnosticEnd").val();
							var notes= $("#DiagnosticNotes").val();;
							
							
							if(dxname == "")
							{
								alert("Enter Diagnostic Name");
								$("#DiagnosticBox").focus();
								return;
							}
							
							if(sdate == "")
							{
								alert("Enter Start Date");
								$("#DiagnosticStart").focus();
								return;
							}
							
							//console.log(dxname + "   " + dxcode + "   " + sdate + "     " + edate + "        " + notes);
							
							var url = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=createDiagnostics&ajax=false&dxname="+dxname+"&dxcode="+dxcode+"&sdate="+sdate+"&edate="+edate+"&notes="+notes;
							console.log(url);
							var Rectipo = LanzaAjax(url);
							//alert(Rectipo);
							
							getDiagnostics(1);    //Refresh the table on the popup
						
							//clear data
							$("#DiagnosticBox").val("");
							$("#DiagnosticStart").val("");
							$("#DiagnosticEnd").val("");
							$("#DiagnosticNotes").val("");
							getDiagnosticHistoryInfo();   //Refresh the div on mainpage
							
						});
						
						
						
						function getDiagnosticHistoryInfo()
						{
							var link = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getDiagnosticInfo&ajax=true";
						
						
							$.ajax({
							   url: link,
							   dataType: "html",
							   async: true,
							   success: function(data)
							   {
									$("#DiagnosticHistoryInfo").html(data);
									var myElement = document.querySelector("#DiagnosticHistoryInfo");
									myElement.style.display = "block";
							   }
							});

						
						}
						
						function LanzaAjax (DirURL)
						{
							var RecTipo = "SIN MODIFICACIÓN";
							$.ajax(
							   {
							   url: DirURL,
							   dataType: "html",
							   async: false,
							   complete: function(){ //alert("Completed");
										},
							   success: function(data) {
										
								   //Below line added by Pallab for testing
								   console.log(data);
								   if (typeof data == "string") {
													RecTipo = data;
													}
										 }
								});
							return RecTipo;
						}
					</script>';
				}
			if($display_type == 0){
				return $page;
			}else{
				echo $page;
			}	
		}else{

		$count=0;
		$latest_update = '1975-01-01 00:00:00';
		$doctor_signed = -1;

		while($row = $Query->fetch(PDO::FETCH_ASSOC))
		{
			$dates[$count] = $row['sdate'];
			$datesource[$count] = $row['dxstart'];
			$icd[$count] = (htmlspecialchars($row['dxcode']));
			$surgery[$count] = (htmlspecialchars($row['dxname']));
			$ids[$count] = $row['id'];
			$notes[$count] = (htmlspecialchars($row['notes']));
			
			$doctor_signedP = $row['doctor_signed'];
			$latest_updateP = $row['latest_update'];
			if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}

			$count++;
		}

		$HeightContainer = 230;

		$page .= '<style>';
		$page .= 'div.DataSlot{';
		$page .= '  float:left; height:30px; width:276px; border:1px solid #cacaca; border-radius:5px; margin:3px; margin-left:0px; color:#22aeff; ';
		$page .= '  } ';
		$page .= 'div.DataSlot:hover{';
		$page .= '  color:#54bc00; ';
		$page .= '  } ';
		$page .= 'div.DataSlotMin{';
		$page .= '  float:left; height:10px; width:5px; margin-top:14px; border:0px solid #cacaca; border-radius:10px; border-top-right-radius:0px; border-bottom-right-radius:0px;  border-right:0px; color:#22aeff; background-color:#2c3e50;';
		$page .= '  } ';
		$page .= 'div.Section1{';
		$page .= '  float:left; width: 95px; height:100%; border:0px solid #cacaca; margin-top: 2px;';
		$page .= '  } ';
		$page .= 'div.LSection1{';
		$page .= '  float:left; width: 35px; font-size: 30px; font-weight:bold; margin-top:3px; margin-left:2px;';
		$page .= '  } ';
		$page .= 'div.RSection1{';
		$page .= '  float:left; width: 50px; font-size:20px; font-weight:bold; color:black; margin-top:3px; margin-left:5px; margin: 0px;padding: 0px;padding-top: 2px;';
		$page .= '  } ';
		$page .= 'div.ICDCode{';
		$page .= '  float:left; width:100%; height; 15px; font-weight:bold; font-size: 12px; margin:0px; margin-left:2px; margin-top:1px; padding: 0px;line-height: 12px;-webkit-font-smoothing: antialiased;';
		$page .= '  } ';
		$page .= 'div.DateLab{';
		$page .= '  float:left; width:100%; height; 15px; color:grey; font-weight: normal; font-size: 10px; margin:0px; margin-left:2px; margin-top:-2px;  padding: 0px;line-height: 12px;-webkit-font-smoothing: antialiased;';
		$page .= '  } ';
		$page .= 'div.Section2{';
		$page .= '  float:left; width: 160px; height: 90%; border:0px solid #cacaca; margin-top: 2px;display: table;';
		$page .= '  } ';
		$page .= 'p.DisText{';
		$page .= '  line-height: 12px; color: grey;display: table-cell;vertical-align: middle;';
		$page .= '  } ';
		$page .= '</style>';


		$page .= '<div  style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #2c3e50; color: white;line-height: 20px;">';
			//Personal History trans
			$page .= $this->translations['personalhistory'];
			$page .= '</div>';

		$page .= '<div id="TimelineContainer" 		style="width:100%; height:'.$HeightContainer.'px; margin-top:18px; border:0px solid #cacaca;">';
		$page .= '	<div id="ColDataLabels"     	style="float:left; width:100px; height:100%; border:0px solid #cacaca; position:relative;">';

		$pixelLength = $HeightContainer-5;
		$SToday = date("M Y");
		if($_COOKIE["lang"] == 'th'){
		if(substr($SToday, 0, 3) == 'Jan'){
		$new_month = 'Ene '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Feb'){
		$new_month = 'Feb '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Mar'){
		$new_month = 'Mar '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Apr'){
		$new_month = 'Abr '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'May'){
		$new_month = 'May '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Jun'){
		$new_month = 'Jun '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Jul'){
		$new_month = 'Jul '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Aug'){
		$new_month = 'Ago '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Sep'){
		$new_month = 'Sep '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Oct'){
		$new_month = 'Oct '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Nov'){
		$new_month = 'Nov '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Dec'){
		$new_month = 'Dic '.substr($SToday, 4, 4);
		}
		}else{
		if(substr($SToday, 0, 3) == 'Jan'){
		$new_month = 'Jan '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Feb'){
		$new_month = 'Feb '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Mar'){
		$new_month = 'Mar '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Apr'){
		$new_month = 'Apr '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'May'){
		$new_month = 'May '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Jun'){
		$new_month = 'Jun '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Jul'){
		$new_month = 'Jul '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Aug'){
		$new_month = 'Aug '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Sep'){
		$new_month = 'Sep '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Oct'){
		$new_month = 'Oct '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Nov'){
		$new_month = 'Nov '.substr($SToday, 4, 4);
		}
		if(substr($SToday, 0, 3) == 'Dec'){
		$new_month = 'Dec '.substr($SToday, 4, 4);
		}
		}
		$page .= '<div class="DataLab" style="float:right; margin-right:5px; width: 100%; position:absolute; top:-10px; font-size:12px; color:#22aeff;text-align: right;">'.$new_month.'</div>';	

		if($_COOKIE["lang"] == 'th'){
		if(substr($dates[$count-1], 0, 3) == 'Jan'){
		$new_month = 'Ene '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Feb'){
		$new_month = 'Feb '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Mar'){
		$new_month = 'Mar '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Apr'){
		$new_month = 'Abr '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'May'){
		$new_month = 'May '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Jun'){
		$new_month = 'Jun '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Jul'){
		$new_month = 'Jul '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Aug'){
		$new_month = 'Ago '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Sep'){
		$new_month = 'Sep '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Oct'){
		$new_month = 'Oct '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Nov'){
		$new_month = 'Nov '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Dec'){
		$new_month = 'Dic '.substr($dates[$count-1], 4, 4);
		}
		}else{
		if(substr($dates[$count-1], 0, 3) == 'Jan'){
		$new_month = 'Jan '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Feb'){
		$new_month = 'Feb '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Mar'){
		$new_month = 'Mar '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Apr'){
		$new_month = 'Apr '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'May'){
		$new_month = 'May '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Jun'){
		$new_month = 'Jun '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Jul'){
		$new_month = 'Jul '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Aug'){
		$new_month = 'Aug '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Sep'){
		$new_month = 'Sep '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Oct'){
		$new_month = 'Oct '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Nov'){
		$new_month = 'Nov '.substr($dates[$count-1], 4, 4);
		}
		if(substr($dates[$count-1], 0, 3) == 'Dec'){
		$new_month = 'Dec '.substr($dates[$count-1], 4, 4);
		}
		}

		$page .= '<div class="DataLab" style="float:right; margin-right:5px; width: 100%; position:absolute; top:'.($pixelLength-10).'px; font-size:12px; color:#22aeff;text-align: right;">'.$new_month.'</div>';	

		$n = 0;
		$daysLength = $this->daysOld($datesource[$count-1]);
		$pixelLength = $HeightContainer-5;
		$displayCount[0] = 0;
		$displayCount[1] = $pixelLength;
		$dcount = 2;
		while ($n < $count)
		{	
			$days = $this->daysOld($datesource[$n]);
			$dateHeight = ($pixelLength * $days)/$daysLength;
			
			$rn = 0;
			$display = 1;
			while ($rn < $dcount)
			{
				if (abs($dateHeight - $displayCount[$rn]) < 20) $display = 0;  // don't display
				$rn++;
			}

			if ($display == 1) 
			{
			
			if($_COOKIE["lang"] == 'th'){
		if(substr($dates[$n], 0, 3) == 'Jan'){
		$new_month = 'Ene '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Feb'){
		$new_month = 'Feb '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Mar'){
		$new_month = 'Mar '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Apr'){
		$new_month = 'Abr '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'May'){
		$new_month = 'May '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Jun'){
		$new_month = 'Jun '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Jul'){
		$new_month = 'Jul '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Aug'){
		$new_month = 'Ago '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Sep'){
		$new_month = 'Sep '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Oct'){
		$new_month = 'Oct '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Nov'){
		$new_month = 'Nov '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Dec'){
		$new_month = 'Dic '.substr($dates[$n], 4, 4);
		}
		}else{
		if(substr($dates[$n], 0, 3) == 'Jan'){
		$new_month = 'Jan '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Feb'){
		$new_month = 'Feb '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Mar'){
		$new_month = 'Mar '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Apr'){
		$new_month = 'Apr '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'May'){
		$new_month = 'May '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Jun'){
		$new_month = 'Jun '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Jul'){
		$new_month = 'Jul '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Aug'){
		$new_month = 'Aug '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Sep'){
		$new_month = 'Sep '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Oct'){
		$new_month = 'Oct '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Nov'){
		$new_month = 'Nov '.substr($dates[$n], 4, 4);
		}
		if(substr($dates[$n], 0, 3) == 'Dec'){
		$new_month = 'Dec '.substr($dates[$n], 4, 4);
		}
		}
				$page .= '<div class="DataLab" style="float:right; margin-right:5px; width: 100%; position:absolute; top:'.($dateHeight-10).'px; font-size:12px; color:#22aeff;text-align: right;">'.$new_month.'</div>';	
				$displayCount[$dcount] = $dateHeight;
				$dcount++;
			}

			$n++;
		}


		$page .= '	</div>';// Closing of ColDataLabels

		$page .= '	<div id="ColTimelineGraph"     	style="float:left; width:30px; height:100%; border:1px solid #cacaca; border-radius:3px; position:relative;">';
		$HeightLabel = 32 + 3+ 3;
		$n = 0;
		$daysLength = $this->daysOld($datesource[$count-1]);
		$pixelLength = $HeightContainer-5;
		while ($n < $count)
		{	
			$days = $this->daysOld($datesource[$n]);
			$dateHeight = ($pixelLength * $days)/$daysLength;
			$page .= '<div class="ELine" style="height: 0px; width: 100%; border: 1px solid grey; position:absolute; left: -1px; top:'.$dateHeight.'px;"></div>';	     $n++;
		}


		$page .= '	</div>';// Closing of ColTimelineGraph
		$page .= '	<div id="ColConnectingLines"   	style="float:left; width:50px; height:230px; border:0px solid #cacaca; position:relative;">';

		$n = 0;
		$daysLength = $this->daysOld($datesource[$count-1]);
		$pixelLength = $HeightContainer-5;
		while ($n < $count)
		{	
			$days = $this->daysOld($datesource[$n]);
			$dateHeight = ($pixelLength * $days)/$daysLength;
			$x1 = 0;
			$y1 = $dateHeight+1;
			$x2 = 50;
			$y2 = ($HeightLabel * ($n+1))-($HeightLabel/2);
			$returned =  $this->degL($x1,$y1,$x2,$y2);
			$Len = $returned[0];
			$Alf = $returned[1];

			$page .= '<div class="ELine" style="height: 0px; width:'.$Len.'px; border-top: 1px solid lightgrey; position:absolute; left: 0px; top:'.$y1.'px; transform:rotate('.$Alf.'deg); -ms-transform:rotate('.$Alf.'deg); -webkit-transform:rotate('.$Alf.'deg); -webkit-transform-origin: 0% 0%;"></div>';	    
		$n++;
		}


		$page .= '	</div>'; // Closing of ColConnectingLines
		$page .= '	<div id="ColDataSlots"   		style="float:left; width:290px; height:230px; border:0px solid #cacaca;">';

		$n = 0;
		while ($n < $count)
		{
			$fIndex = sprintf("%02d", $n);
			$date = date_create($dates[$n]);
			$dateA = date_format($date, 'M Y');
			
			if($_COOKIE["lang"] == 'th'){
		if(substr($dateA, 0, 3) == 'Jan'){
		$new_month = 'Ene '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Feb'){
		$new_month = 'Feb '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Mar'){
		$new_month = 'Mar '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Apr'){
		$new_month = 'Abr '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'May'){
		$new_month = 'May '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Jun'){
		$new_month = 'Jun '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Jul'){
		$new_month = 'Jul '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Aug'){
		$new_month = 'Ago '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Sep'){
		$new_month = 'Sep '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Oct'){
		$new_month = 'Oct '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Nov'){
		$new_month = 'Nov '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Dec'){
		$new_month = 'Dic '.substr($dateA, 4, 4);
		}
		}else{
		if(substr($dateA, 0, 3) == 'Jan'){
		$new_month = 'Jan '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Feb'){
		$new_month = 'Feb '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Mar'){
		$new_month = 'Mar '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Apr'){
		$new_month = 'Apr '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'May'){
		$new_month = 'May '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Jun'){
		$new_month = 'Jun '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Jul'){
		$new_month = 'Jul '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Aug'){
		$new_month = 'Aug '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Sep'){
		$new_month = 'Sep '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Oct'){
		$new_month = 'Oct '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Nov'){
		$new_month = 'Nov '.substr($dateA, 4, 4);
		}
		if(substr($dateA, 0, 3) == 'Dec'){
		$new_month = 'Dec '.substr($dateA, 4, 4);
		}
		}

		 // echo '<style>div.SemiBall {width:0px; }; </style>';
			if (strlen($icd[$n]) > 1 ) $ballcolor = '#2c3e50'; else $ballcolor = '#22aeff';
			$page .= '<div class="DataSlotMin" style="background-color:'.$ballcolor.';"></div>';		
			$page .= '		<div class="DataSlot" style="float:left;">';
			$page .= '			<div class="Section1">';
			$page .= '          	<div class="LSection1"><font size="5">'.$fIndex.'</font></div>';
			$page .= '          	<div class="RSection1">';
			$page .= '					<div class="ICDCode">'.substr($icd[$n],0,7).'</div>';
			$page .= '					<div class="DateLab">'.$new_month.'</div>';
			$page .= '				</div>';
			$page .= '			</div>';
			$page .= '			<div class="Section2">';
			$page .= '				<p class="DisText">'.substr($surgery[$n],0,30).'</p>';
			$page .= '			</div>';
			$page .= '		</div>';	
			
			$n++;
		}

		$page .= '	</div>'; // Closing of ColDataSlots
		$page .= '</div>';  // Closing of TimelineContainer

		$page .= '<input id="Ddoctor_signed" value="'.$doctor_signed.'" style="width:20px; float:left; display:none;">';	
		$page .= '<input id="Dlatest_update" value="'.$latest_update.'" style="width:120px; float:left; display:none;">';
	}
		//MODAL WINDOW WITH JAVASCRIPT AT FOOT...
		$page .= '<!-- MODAL VIEW FOR DIAGNOSTICS -->
				<div id="modalDiagnostics" style="display:none; text-align:center; padding:20px;">
					<div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Diagnostics for </span> '.$this->member_name.' '.$this->member_surname.'</div>
					<div id="deletedDiagnostics"><img class="btn" src="../../images/icons/trashicon.jpg" style="margin-left:25px;border:solid 1px #cacaca;" alt="Deleted Medications" width="15" height="15"></div>
					<div id="DiagnosticContainer" style="border:solid 1px #cacaca; height:150px; margin-top:30px; padding-top:5px; overflow:auto;">
					</div>
					<div style="border:solid 1px #cacaca;  margin-top:20px; padding:10px;height:240px">
						<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Diagnostic Name: </span>
						<input id="field_id1" type="text" style="width: 1px; margin-top:5px;margin-left:10px; visibility:hidden;"><input id="DiagnosticBox" type="text" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:25px" lang="en" placeholder="Enter Diagnostic Name">
						<!--<input id="ICD_id" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;">-->
						
						<!--if($isPatient != 1){
						echo "<input id="ICDBox" type="text" style="margin-left:10px;width: 60px; float:right;font-size:14px;height:25px" placeholder="ICD 10">";
						}else{
						echo "<input id="ICDBox" type="hidden" style="margin-left:10px;width: 60px; float:left;font-size:14px;height:25px" placeholder="ICD 10">";
						}--> 
						
						
						<div style="padding:3px;height: 30px;margin-top: 5px ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Start Date: </span>
							<input id="DiagnosticStart" type="date" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:25px">
						</div>
						<div style="width:100%;"></div>
						<div style="padding:3px;height: 30px;margin-top: 5px ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">End Date: </span>
							<input id="DiagnosticEnd" type="date" style="margin-left:18px;width: 200px; float:left;font-size:14px;height:25px">
						</div>		
						<div style="width:100%;"></div>
						<div style="padding:3px;height: 30px;margin-top: 5px ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Notes: </span>
							<textarea rows="3" col="550" id="DiagnosticNotes" style="margin-left:38px;float:left;font-size:14px;width:250px"></textarea>
						</div>		
						
						<a id="ButtonAddDiagnostic" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-54px;"><span lang="en">Add</span></a>
						<a id="buttonNoDiagnostics" style="float:left; text-align:center; margin-left: 5px; width: 150px; font-size:12px; height:17px;" class="btn" lang="en">No Incidents to Report</a>
					</div>
				</div>
				<!-- END DIAGNOSTIC MODAL-->
				<!-- Model view for deleted diagnostics -->
				<div id="modalDeletedDiagnostics" style="display:none; text-align:center; padding:20px;">

					<div style="color:#22aeff; font-size:14px; text-align: right; width: 80%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Deleted diagnostics for </span>'.$this->member_name.' '.$this->member_surname.'</div>
					<div><img id="deletedDiagnostics" src="../../images/icons/trashicon.jpg" style="margin-left:25px;" alt="Deleted Diagnostics" width="30" height="30"></div>
					<div id="DiagnosticContainerDeleted" style="border:solid 1px #cacaca; height:180px; margin-top:30px; padding-top:5px; overflow:auto;">
					</div>

					</div>
				<!--END MODAL DELETED DIAGNOSTICS-->
				<!-- MODAL VIEW FOR Editing DIAGNOSTICS -->
				<div id="editDiagnostics" style="display:none; text-align:center; padding:20px;">
					<div style="color:#22aeff; font-size:14px; text-align: right; width: 70%; height: 10px; float: left; padding-bottom: 10px;"> <span lang="en">Edit Diagnostics for </span>'.$this->member_name.' '.$this->member_surname.'</div>
					
					<div id="DiagnosticContainerEdit" style="border:solid 1px #cacaca; height:100px; margin-top:30px; padding-top:5px; overflow:auto;">
					</div>
					<div style="border:solid 1px #cacaca;  margin-top:20px; padding:10px;height:240px">
						<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Diagnostic Name: </span>
						<input id="field_id1" type="text" style="width: 1px; margin-top:5px;margin-left:10px; visibility:hidden;"><input id="DiagnosticBox2" type="text" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:25px" lang="en" placeholder="Enter Diagnostic Name">
						<input id="DiagnosticRow2" type="hidden" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:25px">
						<!--<input id="ICD_id" type="text" style="width: 40px; margin-top:5px;margin-left:10px; visibility:hidden;">-->';
						
						if($isPatient != 1){
						$page .=  '<input id="ICDBox2" type="text" style="margin-left:10px;width: 60px; float:left;font-size:14px;height:25px" placeholder="ICD 10">';
						}else{
						$page .=  '<input id="ICDBox2" type="hidden" style="margin-left:10px;width: 60px; float:left;font-size:14px;height:25px" placeholder="ICD 10">';
						} 

						$page .=  '<div style="width:100%;"></div>
						<div style="padding:3px;height: 30px;margin-top: 5px ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Start Date: </span>
							<input id="DiagnosticStart2" type="date" style="margin-left:10px;width: 200px; float:left;font-size:14px;height:25px">
						</div>
						<div style="width:100%;"></div>
						<div style="padding:3px;height: 30px;margin-top: 5px ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">End Date: </span>
							<input id="DiagnosticEnd2" type="date" style="margin-left:18px;width: 200px; float:left;font-size:14px;height:25px">
						</div>		
						<div style="width:100%;"></div>
						<div style="padding:3px;height: 30px;margin-top: 5px ">
							<span style="float: left;text-align:center;margin-top: 5px;font-size:14px;margin-left:5px" lang="en">Notes: </span>
							<textarea rows="3" col="550" id="DiagnosticNotes2" style="margin-left:38px;float:left;font-size:14px;width:250px"></textarea>
						</div>		
						
						<a id="ButtonEditDiagnostic" class="btn" style="height: 80px; width:50px; color:#22aeff; float:right; margin-top:-110px;"><span lang="en">Edit</span></a>
						
					</div>
				</div>
				<!--END MODAL VIEW FOR Editing DIAGNOSTICS -->';
				
				if($display_type == 0){
				$page .= '<script type="text/javascript">
					var diagnostic_info = $("#modalDiagnostics").dialog({bgiframe: true, width: 550, autoOpen: false, height: 570, modal: true,title:"'.$this->translations['diagnostics'].'"});
					$("#DiagnosticHistoryInfo").live("click",function()	{
						//alert("clicked");
						getDiagnostics();
						
						diagnostic_info.dialog("open");
					});
					
					var diagnostic_deleted = $("#modalDeletedDiagnostics").dialog({bgiframe: true, width: 400, height: 400, autoOpen: false, modal: true,title:"'.$this->translations['diagnosticsdeleted'].'"});
					$("#deletedDiagnostics").live("click",function()	{
						getDiagnosticsDeleted();
						diagnostic_deleted.dialog("open");
					  });
					  
					var prev_click="";
					$(".DeleteDiagnostic").live("click",function(){
						var myClass = $(this).attr("id"); 
						//alert (myClass);
						prev_click="delete";
						var cadena="medicalPassportUnit.php?data_type=deleteDiagnostics&ajax='.$_GET['ajax'].'&doc_id='.$this->med_id.'&id="+myClass;
						var RecTipo=LanzaAjax(cadena);
						getDiagnostics(1);
						getDiagnosticHistoryInfo();

					});
					
					$("#buttonNoDiagnostics").click(function() {

					var r=confirm("This action will delete all previous diagnostics and will prevent future diagnostics from being displayed.  Is this really what you would like to do?");
						 if (r==true)
						 {
						var url = "medicalPassportUnit.php?data_type=createDiagnostics&ajax=false&nodiag=yes&IdUsu='.$this->passport_id.'";
						var Rectipo = LanzaAjax(url);
						getDiagnostics(1);	
					
						getDiagnosticHistoryInfo();
						}
								
					});
					
					function getDiagnostics(toShow)
					{
						//alert(toShow);
						var queUrl ="medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getDiagnostics&ajax=true";
						$.ajax(
						{
							url: queUrl,
							dataType: "json",
							async: false,
							success: function(data)
							{
								DiagnosticData = data.items;
							}
						});
					
						numDiagnostics = DiagnosticData.length;
					
						var n = 0;
						var DiagnosticBox;
						if (numDiagnostics==0)
						{
						var translation51 = "";

						if(language == "th"){
						translation51 = "No se encontraron datos";
						}else if(language == "en"){
						translation51 = "No Data Found";
						}
							DiagnosticBox="<span>"+translation51+"</span>";
						}
						else
						{
							DiagnosticBox="";
						}
						
						while (n<numDiagnostics){
							var del = DiagnosticData[n].deleted;
							var dxname = DiagnosticData[n].dxname;
							var dxcode = DiagnosticData[n].dxcode;
							var sdate = DiagnosticData[n].sdate;
							var edate = DiagnosticData[n].edate;	
							var notes = DiagnosticData[n].notes;
							var rowid = DiagnosticData[n].id;	
				if(del==0)
							{			
							DiagnosticBox += "<div class=\"InfoRow DiagnosticRow\" id="+rowid+">";
							
							var middleColumn = sdate;
							
							if(edate.length>0)
							{
								middleColumn = middleColumn + "-" + edate;
							}
							
							if(notes.length==0)
							{
								notes = "No Notes Recorded";
							}	
							
							
							if(rowid==toShow)
							{
								DiagnosticBox += "<div style=\"width:10px; float:left; text-align:left;\"><i class=\"icon-chevron-down\"></i></div>";
							}
							
							
							DiagnosticBox += "<div style=\"width:140px; float:left; text-align:left;cursor:pointer\"><span class=\"PatName\" style=\"white-space:nowrap\">"+dxname.substr(0, 15)+"...</span></div>";
							DiagnosticBox += "<div style=\"width:190px; float:left; text-align:center; color:#22aeff;\"><span class=\"DrName\">"+middleColumn +" </span></div>";
							DiagnosticBox += "<div class=\"EditDiagnostic\" id="+rowid+" style=\"width:60px; float:right;margin-right:10px;\"><a id="+rowid+"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:green;\" lang=\"en\">Edit</a></div>";
							DiagnosticBox += "<div class=\"DeleteDiagnostic\" id="+rowid+" style=\"width:60px; float:right;\"><a id="+rowid+"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:red;\" lang=\"en\">'.$this->translations['delete'].'</a></div>";
							//console.log(DiagnosticBox);
														
							DiagnosticBox += "</div>";
							var temp_var = "Note"+rowid;
							DiagnosticBox += "<div class=\"InfoRow NotesRow\" id=\""+temp_var+"\"";
							if(rowid==toShow)
							{
								DiagnosticBox += ">";
							}
							else
							{
								DiagnosticBox += " style=\"display:none\">";
							}
							
							if(del==0)
							{
								DiagnosticBox += "<div style=\"width:100%; float:left; text-align:left;\">"+notes+"</div>";
							}
							else
							{
								DiagnosticBox += "<div style=\"width:100%; float:left; text-align:left;\"><strike>"+notes+"</strike></div>";
							}
							DiagnosticBox += "</div>";
							}
							n++;
						}
						$("#DiagnosticContainer").html(DiagnosticBox);
				
					}
					
					$("#ButtonAddDiagnostic").click(function() {
							var dxname = $("#DiagnosticBox").val();
					//		var dxcode = $("#field_id1").val();
							var dxcode = $("#ICDBox").val();
							var sdate = $("#DiagnosticStart").val();
							var edate = $("#DiagnosticEnd").val();
							var notes= $("#DiagnosticNotes").val();;
							
							
							if(dxname == "")
							{
								alert("Enter Diagnostic Name");
								$("#DiagnosticBox").focus();
								return;
							}
							
							if(sdate == "")
							{
								alert("Enter Start Date");
								$("#DiagnosticStart").focus();
								return;
							}
							
							//console.log(dxname + "   " + dxcode + "   " + sdate + "     " + edate + "        " + notes);
							
							var url = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=createDiagnostics&ajax=false&dxname="+dxname+"&dxcode="+dxcode+"&sdate="+sdate+"&edate="+edate+"&notes="+notes;
							console.log(url);
							var Rectipo = LanzaAjax(url);
							//alert(Rectipo);
							
							getDiagnostics(1);    //Refresh the table on the popup
						
							//clear data
							$("#DiagnosticBox").val("");
							$("#DiagnosticStart").val("");
							$("#DiagnosticEnd").val("");
							$("#DiagnosticNotes").val("");
							getDiagnosticHistoryInfo();   //Refresh the div on mainpage
							
						});
					
					function getDiagnosticHistoryInfo()
					{
						var link = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getDiagnosticInfo&ajax=true";
					
					
						$.ajax({
						   url: link,
						   dataType: "html",
						   async: true,
						   success: function(data)
						   {
								//var optionBox = "<div class="grid" id="DiagnosticEditBox" style="display:none;opacity:0.8;cursor:pointer;float:right;position:absolute;margin-top:-20px;margin-left:420px;background-color:grey;font-size:15px">"+"<i class="icon-edit" style="float:right;padding:5px"> Edit </i></div>";
								//$("#DiagnosticHistoryInfo").html(optionBox+data);
								$("#DiagnosticHistoryInfo").html(data);
								var myElement = document.querySelector("#DiagnosticHistoryInfo");
								myElement.style.display = "block";
						   }
						});

					
					}
					
					function LanzaAjax (DirURL)
					{
					var RecTipo = "SIN MODIFICACIÓN";
					$.ajax(
					   {
					   url: DirURL,
					   dataType: "html",
					   async: false,
					   complete: function(){ //alert("Completed");
								},
					   success: function(data) {
								
						   //Below line added by Pallab for testing
						   console.log(data);
						   if (typeof data == "string") {
											RecTipo = data;
											}
								 }
						});
					return RecTipo;
					}
					
					$(".EditDiagnostic").live("click",function(){
						var myClass = $(this).attr("id"); 
						//alert (myClass);
						var translation21 = "edit diagnotstics";
						$("#editDiagnostics").dialog({bgiframe: true, width: 550, height: 510, modal: true,title:translation21});
						getDiagnosticsEdit(myClass);
					});
					
					$("#ButtonEditDiagnostic").click(function() {
							var idrow = $("#DiagnosticRow2").val();
							var dxname = $("#DiagnosticBox2").val();
					//		var dxcode = $("#field_id1").val();
							var dxcode = $("#ICDBox2").val();
							var sdate = $("#DiagnosticStart2").val();
							var edate = $("#DiagnosticEnd2").val();
							var notes= $("#DiagnosticNotes2").val();;
					//		alert(idrow);
							
							
							if(dxname == "")
							{
								alert("Enter Diagnostic Name");
								$("#DiagnosticBox2").focus();
								return;
							}
							
							if(sdate == "")
							{
								alert("Enter Start Date");
								$("#DiagnosticStart2").focus();
								return;
							}
							
							//console.log(dxname + "   " + dxcode + "   " + sdate + "     " + edate + "        " + notes);
							
							var url = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=createDiagnostics&ajax=true&dxname="+dxname+"&dxcode="+dxcode+"&sdate="+sdate+"&edate="+edate+"&notes="+notes+"&rowediter="+idrow;
							console.log(url);
							var Rectipo = LanzaAjax(url);
							//alert(Rectipo);
							
							getDiagnostics(0);    //Refresh the table on the popup
						
							//clear data
							$("#DiagnosticBox2").val("");
							$("#DiagnosticRow2").val("");
							$("#DiagnosticStart2").val("");
							$("#DiagnosticEnd2").val("");
							$("#DiagnosticNotes2").val("");
							getDiagnosticHistoryInfo();   //Refresh the div on mainpage
							$("#editDiagnostics").dialog("close");
						});
						
						function getDiagnosticsEdit(toShow)
						{
							//alert(toShow);
							var queUrl ="medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getDiagnostics&ajax=true";
							$.ajax(
							{
								url: queUrl,
								dataType: "json",
								async: false,
								success: function(data)
								{
									DiagnosticData = data.items;
								}
							});
							numDiagnostics = DiagnosticData.length;
						
							var n = 0;
							var DiagnosticBox;
							if (numDiagnostics==0)
							{
							var translation51 = "";

							if(language == "th"){
							translation51 = "No se encontraron datos";
							}else if(language == "en"){
							translation51 = "No Data Found";
							}
								DiagnosticBox="<span>"+translation51+"</span>";
							}
							else
							{
								DiagnosticBox="";
							}
							
							while (n<numDiagnostics){
								var del = DiagnosticData[n].deleted;
								var dxname = DiagnosticData[n].dxname;
								var dxcode = DiagnosticData[n].dxcode;
								var sdate = DiagnosticData[n].sdate;
								var edate = DiagnosticData[n].edate;	
								var notes = DiagnosticData[n].notes;
								var rowid = DiagnosticData[n].id;	
								
								
					if(del==0)
								{		
					if(rowid==toShow)
								{			
								DiagnosticBox += "<div class=\"InfoRow DiagnosticRow\" id="+rowid+">";
								$("#DiagnosticBox2").val(dxname);
								$("#DiagnosticStart2").val(sdate);
								$("#DiagnosticEnd2").val(edate);
								$("#DiagnosticNotes2").val(notes);
								
								
								var middleColumn = sdate;
								
								if(edate.length>0)
								{
									middleColumn = middleColumn + "-" + edate;
								}
								
								if(notes.length==0)
								{
									notes = "No Notes Recorded";
								}	
								
								
								
									DiagnosticBox += "<div style=\"width:10px; float:left; text-align:left;\"><i class=\"icon-chevron-down\"></i></div>";
								
								translatione1 = "";
									if(language == "th"){
							translatione1 = "Edición";
							}else if(language == "en"){
							translatione1 = "Editing";
							}
									  
										DiagnosticBox += "<div style=\"width:140px; float:left; text-align:left;cursor:pointer\"><span class=\"PatName\" style=\"white-space:nowrap\">"+dxname.substr(0, 15)+"...</span></div>";
										DiagnosticBox += "<div style=\"width:210px; float:left; text-align:center; color:#22aeff;\"><span class=\"DrName\">"+middleColumn +" </span></div>";
										DiagnosticBox += "<div class=\"EditDiagnostic\" id="+rowid+" style=\"width:60px; float:left;margin-right:20px;\">"+translatione1+"</div>";
								}				
								
								DiagnosticBox += "</div>";
								
								var temp_var = "Note"+rowid;
								DiagnosticBox += "<div class=\"InfoRow NotesRow\" id=\""+temp_var+"\"";
								if(rowid==toShow)
								{
									DiagnosticBox += ">";
								}
								else
								{
									DiagnosticBox += " style=\"display:none\">";
								}
								
								if(del==0)
								{
									DiagnosticBox += "<div style=\"width:100%; float:left; text-align:left;\">"+notes+"</div>";
								}
								else
								{
									DiagnosticBox += "<div style=\"width:100%; float:left; text-align:left;\"><strike>"+notes+"</strike></div>";
								}
								DiagnosticBox += "</div>";
								}
								n++;
							}
							$("#DiagnosticContainerEdit").html(DiagnosticBox);
							var rowtrack = $("#DiagnosticRow2").val(toShow);
							//adjustHeights(".PatName");
						}
					
					function getDiagnostics(toShow)
					{
						//alert(toShow);
						var queUrl ="medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getDiagnostics&ajax=true";
						$.ajax(
						{
							url: queUrl,
							dataType: "json",
							async: false,
							success: function(data)
							{
								DiagnosticData = data.items;
							}
						});
					
						numDiagnostics = DiagnosticData.length;
					
						var n = 0;
						var DiagnosticBox;
						if (numDiagnostics==0)
						{
						var translation51 = "";

						if(language == "th"){
						translation51 = "No se encontraron datos";
						}else if(language == "en"){
						translation51 = "No Data Found";
						}
							DiagnosticBox="<span>"+translation51+"</span>";
						}
						else
						{
							DiagnosticBox="";
						}
						
						while (n<numDiagnostics){
							var del = DiagnosticData[n].deleted;
							var dxname = DiagnosticData[n].dxname;
							var dxcode = DiagnosticData[n].dxcode;
							var sdate = DiagnosticData[n].sdate;
							var edate = DiagnosticData[n].edate;	
							var notes = DiagnosticData[n].notes;
							var rowid = DiagnosticData[n].id;	
				if(del==0)
							{			
							DiagnosticBox += "<div class=\"InfoRow DiagnosticRow\" id="+rowid+">";
							
							var middleColumn = sdate;
							
							if(edate.length>0)
							{
								middleColumn = middleColumn + "-" + edate;
							}
							
							if(notes.length==0)
							{
								notes = "No Notes Recorded";
							}	
							
							
							if(rowid==toShow)
							{
								DiagnosticBox += "<div style=\"width:10px; float:left; text-align:left;\"><i class=\"icon-chevron-down\"></i></div>";
							}
							
							
							DiagnosticBox += "<div style=\"width:140px; float:left; text-align:left;cursor:pointer\"><span class=\"PatName\" style=\"white-space:nowrap\">"+dxname.substr(0, 15)+"...</span></div>";
							DiagnosticBox += "<div style=\"width:190px; float:left; text-align:center; color:#22aeff;\"><span class=\"DrName\">"+middleColumn +" </span></div>";
							DiagnosticBox += "<div class=\"EditDiagnostic\" id="+rowid+" style=\"width:60px; float:right;margin-right:10px;\"><a id="+rowid+"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:green;\" lang=\"en\">Edit</a></div>";
							DiagnosticBox += "<div class=\"DeleteDiagnostic\" id="+rowid+" style=\"width:60px; float:right;\"><a id="+rowid+"  class=\"btn\" style=\"height: 15px; padding-top: 0px; margin-top: -5px; color:red;\" lang=\"en\">'.$this->translations['delete'].'</a></div>";
							//console.log(DiagnosticBox);
														
							DiagnosticBox += "</div>";
							
							var temp_var = "Note"+rowid;
							DiagnosticBox += "<div class=\"InfoRow NotesRow\" id=\""+temp_var+"\"";
							if(rowid==toShow)
							{
								DiagnosticBox += ">";
							}
							else
							{
								DiagnosticBox += " style=\"display:none\">";
							}
							
							if(del==0)
							{
								DiagnosticBox += "<div style=\"width:100%; float:left; text-align:left;\">"+notes+"</div>";
							}
							else
							{
								DiagnosticBox += "<div style=\"width:100%; float:left; text-align:left;\"><strike>"+notes+"</strike></div>";
							}
							DiagnosticBox += "</div>";
							}
							n++;
						}
						$("#DiagnosticContainer").html(DiagnosticBox);
				
					}
					
					function getDiagnosticsDeleted(toShow)
						{
							//alert(toShow);
							var queUrl ="medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getDiagnostics&ajax=true";
							$.ajax(
							{
								url: queUrl,
								dataType: "json",
								async: false,
								success: function(data)
								{
									DiagnosticData = data.items;
								}
							});
						
							numDiagnostics = DiagnosticData.length;
						
							var n = 0;
							var DiagnosticBox;
							if (numDiagnostics==0)
							{
							var translation51 = "";

							if(language == "th"){
							translation51 = "No se encontraron datos";
							}else if(language == "en"){
							translation51 = "No Data Found";
							}
								DiagnosticBox="<span>"+translation51+"</span>";
							}
							else
							{
								DiagnosticBox="";
							}
							
							while (n<numDiagnostics){
								var del = DiagnosticData[n].deleted;
								var dxname = DiagnosticData[n].dxname.substr(0, 30);
								var dxcode = DiagnosticData[n].dxcode;
								var sdate = DiagnosticData[n].sdate;
								var edate = DiagnosticData[n].edate;	
								var notes = DiagnosticData[n].notes;
								var rowid = DiagnosticData[n].id;	
								if(del==1)
								{			
									DiagnosticBox += "<div class=\"InfoRow DiagnosticRow\" id="+rowid+">";
									var middleColumn = sdate;
									
									if(edate.length>0)
									{
										middleColumn = middleColumn + "-" + edate;
									}
									
									if(notes.length==0)
									{
										notes = "No Notes Recorded";
									}	
									
									if(rowid==toShow)
									{
										DiagnosticBox += "<div style=\"width:10px; float:left; text-align:left;\"><i class=\"icon-chevron-down\"></i></div>";
									}
									
									DiagnosticBox += "<div style=\"width:175px; float:left; text-align:left;cursor:pointer\"><span class=\"PatName\" style=\"white-space:nowrap\"><font size=\"1\">"+dxname.substr(0, 30)+"</font></span></div>";
									DiagnosticBox += "<div style=\"width:125px; float:left; text-align:center; color:#22aeff;\"><span class=\"DrName\"><font size=\"1\">"+middleColumn +" </font></span></div>";
									DiagnosticBox += "</div>";
								}
								n++;
							}
							$("#DiagnosticContainerDeleted").html(DiagnosticBox);
							//adjustHeights(".PatName");
						}
				</script>';	
		}
		
		if($display_type == 0){
			return $page;
		}else{
			echo $page;
		}	
	}
	
	public function getHabitsInfo($display_type){
		$cigaretteIcon = "icon-magic";
		$alcoholIcon = "icon-glass";
		$exerciseIcon = "icon-heart";

		$cigaretteColor = "rgba(34,174,255,1)";
		$alcoholColor = "orange";
		$exerciseColor = "#8000FF";

		$Query= $this->con->prepare("select * from p_habits where idpatient=?");
		$Query->bindValue(1, $this->passport_id, PDO::PARAM_INT);
		$result=$Query->execute();

		$count = $Query->rowCount();
		
		if($display_type == 1){
			$page = '<div  id="HabitsInfo">';
		}else{
			$page = '<div  id="HabitsInfo" class="grid" class="grid span4" style="cursor:pointer; float:right;height:48%; margin-top:1%;  margin-right:1%; margin-left:.4%; padding:.5%;width:24%;display:table">';
		}
		if($count==0)
		{
			$page .= '<div  style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #3498db; color: white;line-height: 20px;">';
			//Habits trans
			$page .= $this->translations['habits'];
			$page .= '</div><center>';
			//No entries trans
			$page .= $this->translations['noentries'];
			$page .= '</center><p><center><img style="margin-top:75px;" width="75px" height="100%" src="../../images/icons/general_user_error_icon.png" alt="No Data Icon"></center>';
			$page .= '<input type="hidden" value="0" id="cig" ></input>  ';
			$page .= '<input type="hidden" value="0" id="alc" ></input>  ';
			$page .= '<input type="hidden" value="0" id="exer" ></input>  ';
			$page .= '<input type="hidden" value="0" id="slee" ></input>  ';
			$page .= '<input type="hidden" value="0" id="coff" ></input>  ';
			$page = '</div>';
			return;
		}


		$row = $Query->fetch(PDO::FETCH_ASSOC);

		$cigarettes = $row['cigarettes'];
		$alcohol =  $row['alcohol'];
		$exercise =  $row['exercise'];	
		$sleep =  $row['sleep'];
		$coffee =  $row['coffee'];	

		$doctor_signed = $row['doctor_signed'];
		$latest_update = $row['latest_update'];

		if($display_type == 1){
			$page .= '<script>
			$("#cig").val('.$cigarettes.');
			</script>
			<script src="../../js/jquery.min.js"></script>
			<script src="../../js/jquery-ui.min.js"></script>
			<link rel="stylesheet" href="../../css/jquery-ui-1.8.16.custom.css" media="screen"  />';
		}else{
			$page .= '<input type="hidden" value="'.$cigarettes.'" id="cig" ></input>  ';
			$page .= '<input type="hidden" value="'.$alcohol.'" id="alc" ></input>  ';
			$page .= '<input type="hidden" value="'.$exercise.'" id="exer" ></input>  ';
			$page .= '<input type="hidden" value="'.$sleep.'" id="slee" ></input>  ';
			$page .= '<input type="hidden" value="'.$coffee.'" id="coff" ></input>  ';

			$page .= '<input id="Hdoctor_signed" value="'.$doctor_signed.'" style="width:20px; float:left; display:none;">';	
			$page .= '<input id="Hlatest_update" value="'.$latest_update.'" style="width:120px; float:left; display:none;">';
		}	

			$perday = $this->translations['perday'];
			$glweek = $this->translations['glassweek'];
			$hrweek = $this->translations['hourweek'];
			$hrday = $this->translations['hourday'];
			$ciggy = $this->translations['cigs'];
			$exer = $this->translations['exercise'];
			$slp = $this->translations['sleep'];

		if($cigarettes > 0)
		{
			$cigaretteLabel = $cigarettes.' '.$perday;
		}
		else
		{
			$cigaretteLabel = $ciggy;
		}

		if($alcohol > 0)
		{
			$alcoholLabel = $alcohol.' '.$glweek;
		}
		else
		{
			$alcoholLabel = 'No Alcohol';
		}

		if($exercise > 0)
		{
			$exerciseLabel = $exercise.' '.$hrweek;
		}
		else
		{
			$exerciseLabel = $exer;
		}

		if($sleep > 0)
		{
			$sleepLabel = $sleep.' '.$hrday;
		}
		else
		{
			$sleepLabel = $slp;
		}

		if($coffee > 0)
		{
			$coffeeLabel = $coffee.' cups/Day';
		}
		else
		{
			$coffeeLabel = 'No Coffee';
		}





		if($cigarettes > 20)
		{
			$cPercent = 100;
		}
		else
		{
			$cPercent = $cigarettes/20*100;
		}

		if($alcohol > 50)
		{
			$aPercent = 100;
		}
		else
		{
			$aPercent = $alcohol/50*100;
		}

		if($exercise > 50)
		{
			$ePercent = 100;
		}
		else
		{
			$ePercent = $exercise/50*100;
		}

		if($sleep > 50)
		{
			$sPercent = 100;
		}
		else
		{
			$sPercent = $sleep/50*100;
		}

		if($coffee > 50)
		{
			$coPercent = 100;
		}
		else
		{
			$coPercent = $coffee/50*100;
		}


		//$page .= '<div  style="width:245px; height:16px; border:0px solid; text-align:center; background-color: #22aeff;color: white;line-height: 16px;">Habits</div>';
		$page .= '<div  style="100%; height:20px; border:0px solid; text-align:center; background-color: #3498db; color: white;line-height: 20px;">';
		//Habits trans
		$page .= $this->translations['habits'];
		$page .='</div>';

		   
		$page .= '<script src="../../js/jquery.fittext.js"></script>';
		$page .= '<script src="../../js/H2M_Graphs.js"></script>';

		$page .= '<div style="height:0px; width:100%; float:left;"></div>';
		$page .= '<div id="container_smoking" style="float:left; margin:0 auto; border:0px solid #cacaca; margin-top:5px; width:50%; height:120px; position:relative;"></div>';
		$page .= '<script>drawKnob($("#container_smoking"),"#54bc00",  20, '.$cigarettes.', "Tobacco","smoking_svg",0,1); </script>';

		$page .= '<div id="container_drinking" style="float:left; margin:0 auto; border:0px solid #cacaca; margin-top:5px; width:50%; height:120px; position:relative;"></div>';
		$page .= '<script>drawKnob($("#container_drinking"),"#e74c3c",  30, '.$alcohol.', "Alcohol","drinking_svg",0,1); </script>';

		$page .= '<div id="container_fitness" style="float:left; margin:0 auto; border:0px solid #cacaca; margin-top:5px; width:50%; height:120px; position:relative;"></div>';
		$page .= '<script>drawKnob($("#container_fitness"),"#2c3e50",  15, '.$exercise.', "Exercise","fitness_svg",0,1); </script>';

		$page .= '<div id="container_sleep" style="float:left; margin:0 auto; border:0px solid #cacaca; margin-top:5px; width:50%; height:120px; position:relative;"></div>';
		$page .= '<script>drawKnob($("#container_sleep"),"#18bc9c",  10, '.$sleep.', "Sleep","sleeping_svg",0,1); </script>';

		$page .= '<button id="add_device" style="width: 90%; height: 30px; border: 0px solid #FFF; outline: none; background-color: #22AEFF; color: #FFF; float: left; margin-left: 4%; margin-top:10px; font-size: 18px; border-radius: 5px;"><i class="icon-gears"></i><span style="font-size: 12px;">&nbsp;&nbsp;&nbsp;Connect Fitness Device</span></button>';

		$page .= '</div>';		
		
		//MODAL WINDOW WITH JAVASCRIPT AT FOOT...
		$page .= '<!-- MODAL VIEW FOR HABITS INFO -->
					<div id="modalHabitsInfo" style="overflow:visible;display:none;  padding:20px;">
						<div style="border:solid 1px #cacaca; margin-top:5px; padding:10px;">
						
							<table style="width:100%;background-color:white">
								<tr>
									<td style="width:15%"><span style="float: left;text-align:center;font-size:12px;margin-left:5px" lang="en">Cigarettes: </span></td>
									<td style="width:50%"><div id="cigarette-slider" style="margin-left:10px"></div></td>
									<td style="width:35%"><input id="cigarettes" type="text" style=";text-align:center;margin-left: 5px;margin-top: 10px;width: 25%; float:left;font-size:12px;height:25px"><span style="float: left;text-align:center;font-size:10px;margin-top: 10px;margin-left:5px" lang="en">Per Day</span></td>
								</tr>
								<tr>
									<td style="width:15%"><span style="float: left;text-align:center;font-size:12px;margin-left:5px" lang="en">Alcohol: </span></td>
									<td style="width:50%"><div id="alcohol-slider" style="margin-left:10px;"></div></td>
									<td style="width:35%"><input id="alcohol" type="text" style=";text-align:center;margin-left: 5px;margin-top: 10px;width: 25%; float:left;font-size:12px;height:25px"><span style="float: left;text-align:center;font-size:10px;margin-top: 10px;margin-left:5px" lang="en">Glasses/Week</span></td>
								</tr>
								<tr>
									<td style="width:15%"><span style="float: left;text-align:center;font-size:12px;margin-left:5px" lang="en">Exercise: </span></td>
									<td style="width:50%"><div id="exercise-slider" style="margin-left:10px;"></div></td>
									<td style="width:35%"><input id="exercise" type="text" style=";text-align:center;margin-left: 5px;margin-top: 10px;width: 25%; float:left;font-size:12px;height:25px"><span style="float: left;text-align:center;font-size:10px;margin-top: 10px;margin-left:5px" lang="en">Hours/Week</span></td>
								</tr>
								<tr>
									<td style="width:15%"><span style="float: left;text-align:center;font-size:12px;margin-left:5px" lang="en">Sleep: </span></td>
									<td style="width:50%"><div id="sleep-slider" style="margin-left:10px;"></div></td>
									<td style="width:35%"><input id="sleep" type="text" style=";text-align:center;margin-left: 5px;margin-top: 10px;width: 25%; float:left;font-size:12px;height:25px"><span style="float: left;text-align:center;font-size:10px;margin-top: 10px;margin-left:5px" lang="en">Hours/Day</span></td>
								</tr>
								<!--<tr>
									<td style="width:20%"><span style="float: left;text-align:center;font-size:12px;margin-left:5px">Coffee: </span></td>
									<td style="width:50%"><div id="coffee-slider" style="margin-left:10px;"></div></td>
									<td style="width:30%"><input id="coffee" type="text" style=";text-align:center;margin-left: 5px;margin-top: 10px;width: 25%; float:left;font-size:12px;height:25px"><span style="float: left;text-align:center;font-size:12px;margin-top: 10px;margin-left:5px">hours/Week</span></td>
								</tr>-->  
							</table>
						</div>
						<a id="ButtonUpdateHabitsInfo" class="btn" style="width:50px; color:#22aeff; float:right; margin-top:10px;margin-bottom:10px"><span lang="en">Add</span></a>
						
					</div>
					<!-- END MODAL VIEW FOR HABITS INFO -->';
				if($display_type == 0){
				$page .= '<script type="text/javascript">
				$("#ButtonUpdateHabitsInfo").click(function() {
		
					var cigarettes = $("#cigarettes").val();
					var alcohol = $("#alcohol").val();
					var exercise = $("#exercise").val();
					var sleep = $("#sleep").val();
			//		var coffee = $("#coffee").val();
					//alert(cigarettes + "  " + alcohol + "   " + exercise);
					var url = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=updateHabits&ajax=false&cigarette="+cigarettes+"&alcohol="+alcohol+"&exercise="+exercise+"&sleep="+sleep;
					console.log(url);
					var RecTipo = LanzaAjax(url);
					//alert(RecTipo);
					getHabitsInfo();
					$("#modalHabitsInfo").dialog("close");
				});
				
				function getHabitsInfo()
				{
					var link = "medicalPassportUnit.php?IdUsu='.$this->passport_id.'&doc_id='.$this->med_id.'&data_type=getHabitsInfo&ajax=true";
				
				
					$.ajax({
					   url: link,
					   dataType: "html",
					   async: true,
					   success: function(data)
					   {
							$("#HabitsInfo").html(data);
							console.log("datahere"+data);
							//var myElement = document.querySelector("#HabitsInfo");
							//myElement.style.display = "block";
					   }
					});

				
				}
				
				
				var habits_info = $("#modalHabitsInfo").dialog({bgiframe: true, width: 500, autoOpen: false, height: 450, modal: true,title:"'.$this->translations['habits'].'"});
					$("#HabitsInfo").live("click",function()	{
				//		alert("clicked");
						
						$("#cigarette-slider").slider( "value", $("#cig").val() );	
						$("#alcohol-slider").slider( "value", $("#alc").val() );	
						$("#exercise-slider").slider( "value", $("#exer").val() );
						$("#sleep-slider").slider( "value", $("#slee").val() );
				//		$("#coffee-slider").slider( "value", $("#coff").val() );		
							
						$( "#cigarettes" ).val( $( "#cigarette-slider" ).slider( "value" ) );	
						$( "#alcohol" ).val( $( "#alcohol-slider" ).slider( "value" ) );	
						$( "#exercise" ).val( $( "#exercise-slider" ).slider( "value" ) );
						$( "#sleep" ).val( $( "#sleep-slider" ).slider( "value" ) );
				//		$( "#coffee" ).val( $( "#coffee-slider" ).slider( "value" ) );
						
						habits_info.dialog("open");
						
					});
						
					$( "#cigarette-slider" ).slider({
					  range: "min",
					  value: 0,
					  min: 0,
					  max: 20,
					  slide: function( event, ui ) {
						$( "#cigarettes" ).val( ui.value );
					  }
					});
					
					
					$("#cigarettes").keyup(function () { 
						this.value = this.value.replace(/[^0-9\.]/g,"");
						$("#cigarette-slider").slider( "value", this.value );	
					});
					
					
					$( "#alcohol-slider" ).slider({
					  range: "min",
					  value: 0,
					  min: 0,
					  max: 50,
					  slide: function( event, ui ) {
						$( "#alcohol" ).val( ui.value );
					  }
					});
					
					
					$("#alcohol").keyup(function () { 
						this.value = this.value.replace(/[^0-9\.]/g,"");
						$("#alcohol-slider").slider( "value", this.value );	
					});
					
					$( "#exercise-slider" ).slider({
					  range: "min",
					  value: 0,
					  min: 0,
					  max: 50,
					  slide: function( event, ui ) {
						$( "#exercise" ).val( ui.value );
					  }
					});
					
					
					$("#exercise").keyup(function () { 
						this.value = this.value.replace(/[^0-9\.]/g,"");
						$("#exercise-slider").slider( "value", this.value );	
					});
					
					$( "#sleep-slider" ).slider({
					  range: "min",
					  value: 8,
					  min: 0,
					  max: 24,
					  slide: function( event, ui ) {
						$( "#sleep" ).val( ui.value );
					  }
					});
					
					
					$("#sleep").keyup(function () { 
						this.value = this.value.replace(/[^0-9\.]/g,"");
						$("#sleep-slider").slider( "value", this.value );	
					});
					//THIS SLIDER BREAKS THE AJAX QUERY FOR SOME REASON
				/*	( "#coffee-slider" ).slider({
					  range: "min",
					  value: 0,
					  min: 0,
					  max: 50,
					  slide: function( event, ui ) {
						$( "#coffee" ).val( ui.value );
					  }
					});
					
					
					$("#coffee").keyup(function () { 
						this.value = this.value.replace(/[^0-9\.]/g,"");
						$("#coffee-slider").slider( "value", this.value );	
					});*/
					
					function LanzaAjax (DirURL)
						{
							var RecTipo = "SIN MODIFICACIÓN";
							$.ajax(
							   {
							   url: DirURL,
							   dataType: "html",
							   async: false,
							   complete: function(){ //alert("Completed");
										},
							   success: function(data) {
										
								   //Below line added by Pallab for testing
								   console.log(data);
								   if (typeof data == "string") {
													RecTipo = data;
													}
										 }
								});
							return RecTipo;
						}
				</script>';	
		}
		if($display_type == 0){
			return $page;
		}else{
			echo $page;
		}	
	}
	
	public function getMedications($display_type){
		$query = $this->con->prepare("SELECT * FROM p_medication WHERE idpatient=?");
		$query->bindValue(1, $this->passport_id, PDO::PARAM_INT);


		$result = $query->execute();
		$count=$query->rowCount();
		$counter1 = 0 ;
		$cadena = '';
		while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
			$Id[$counter1] = $row2['id'];
			$DrugName[$counter1] = $row2['drugname'];
			$DrugId[$counter1] = $row2['drugcode'];
			$frequency[$counter1] = $row2['frequency'];
			$dossage[$counter1] = $row2['dossage'];
			$deleted[$counter1] = $row2['deleted'];

			if ($counter1>0) $cadena.=',';    
			$cadena.='
			{
				"id":"'.$Id[$counter1].'",
				"drugname":"'.$DrugName[$counter1].'",
				"drugcode":"'.$DrugId.'",
				"dossage":"'.$frequency[$counter1].'",
				"frequency":"'.$dossage[$counter1].'",
				"deleted":"'.$deleted[$counter1].'"
				}';    

			$counter1++;
		}

		$encode = json_encode($cadena);
		if($display_type == 0){
			return '{"items":['.($cadena).']}'; 
		}else{
			echo '{"items":['.($cadena).']}'; 
		}	 
	}
	
	public function getDiagnostics($display_type){
		$query = $this->con->prepare("SELECT id,dxname,dxcode,DATE_FORMAT(dxstart,'%b %Y') as dxstart,DATE_FORMAT(dxend,'%b %Y') as dxend,notes,deleted FROM p_diagnostics WHERE idpatient=?");
		$query->bindValue(1, $this->passport_id, PDO::PARAM_INT);
		$result = $query->execute();

		$count = $query->rowCount();
		$counter1 = 0 ;
		$cadena = '';
		while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {


		if($_COOKIE["lang"] == 'th'){
		if(substr($row2['dxstart'], 0, 3) == 'Jan'){
		$new_month = 'Ene '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Feb'){
		$new_month = 'Feb '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Mar'){
		$new_month = 'Mar '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Apr'){
		$new_month = 'Abr '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'May'){
		$new_month = 'May '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Jun'){
		$new_month = 'Jun '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Jul'){
		$new_month = 'Jul '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Aug'){
		$new_month = 'Ago '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Sep'){
		$new_month = 'Sep '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Oct'){
		$new_month = 'Oct '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Nov'){
		$new_month = 'Nov '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Dec'){
		$new_month = 'Dic '.substr($row2['dxstart'], 4, 4);
		}
		}else{
		if(substr($row2['dxstart'], 0, 3) == 'Jan'){
		$new_month = 'Jan '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Feb'){
		$new_month = 'Feb '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Mar'){
		$new_month = 'Mar '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Apr'){
		$new_month = 'Apr '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'May'){
		$new_month = 'May '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Jun'){
		$new_month = 'Jun '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Jul'){
		$new_month = 'Jul '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Aug'){
		$new_month = 'Aug '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Sep'){
		$new_month = 'Sep '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Oct'){
		$new_month = 'Oct '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Nov'){
		$new_month = 'Nov '.substr($row2['dxstart'], 4, 4);
		}
		if(substr($row2['dxstart'], 0, 3) == 'Dec'){
		$new_month = 'Dec '.substr($row2['dxstart'], 4, 4);
		}
		}

		$new_month2 = '';
		if($_COOKIE["lang"] == 'th'){
		if(substr($row2['dxend'], 0, 3) == 'Jan'){
		$new_month2 = 'Ene '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Feb'){
		$new_month2 = 'Feb '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Mar'){
		$new_month2 = 'Mar '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Apr'){
		$new_month2 = 'Abr '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'May'){
		$new_month2 = 'May '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Jun'){
		$new_month2 = 'Jun '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Jul'){
		$new_month2 = 'Jul '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Aug'){
		$new_month2 = 'Ago '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Sep'){
		$new_month2 = 'Sep '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Oct'){
		$new_month2 = 'Oct '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Nov'){
		$new_month2 = 'Nov '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Dec'){
		$new_month2 = 'Dic '.substr($row2['dxend'], 4, 4);
		}
		}else{
		if(substr($row2['dxend'], 0, 3) == 'Jan'){
		$new_month2 = 'Jan '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Feb'){
		$new_month2 = 'Feb '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Mar'){
		$new_month2 = 'Mar '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Apr'){
		$new_month2 = 'Apr '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'May'){
		$new_month2 = 'May '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Jun'){
		$new_month2 = 'Jun '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Jul'){
		$new_month2 = 'Jul '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Aug'){
		$new_month2 = 'Aug '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Sep'){
		$new_month2 = 'Sep '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Oct'){
		$new_month2 = 'Oct '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Nov'){
		$new_month2 = 'Nov '.substr($row2['dxend'], 4, 4);
		}
		if(substr($row2['dxend'], 0, 3) == 'Dec'){
		$new_month2 = 'Dec '.substr($row2['dxend'], 4, 4);
		}
		}



			$Id[$counter1] = (htmlspecialchars($row2['id']));
			$dxName[$counter1] = (htmlspecialchars($row2['dxname']));
			$dxCode[$counter1] = (htmlspecialchars($row2['dxcode']));
			$sdate[$counter1] = $new_month;
			$edate[$counter1] = $new_month2;
			$notes[$counter1] = (htmlspecialchars($row2['notes']));
			$deleted[$counter1] = (htmlspecialchars($row2['deleted']));

			if ($counter1>0) $cadena.=',';    
			$cadena.='
			{
				"id":"'.$Id[$counter1].'",
				"dxname":"'.$dxName[$counter1].'",
				"dxcode":"'.$dxCode[$counter1].'",
				"sdate":"'.$sdate[$counter1].'",
				"edate":"'.$edate[$counter1].'",
				"notes":"'.$notes[$counter1].'",
				"deleted":"'.$deleted[$counter1].'"
				}';    

			$counter1++;
		}

		$encode = json_encode($cadena);
		if($display_type == 0){
			return '{"items":['.($cadena).']}'; 
		}else{
			echo '{"items":['.($cadena).']}'; 
		}
	}
	
	public function getVaccines($display_type){
		$query = $this->con->prepare("SELECT * FROM p_immuno WHERE idpatient=?");
		$query->bindValue(1, $this->passport_id, PDO::PARAM_INT);
		$result = $query->execute();

		$count = $query->rowCount();
		$counter1 = 0 ;
		$cadena = '';
		while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
			$Id[$counter1] = $row2['id'];
			$VaccCode[$counter1] = $row2['VaccCode'];
			$VaccName[$counter1] = $row2['VaccName'];
			$AllerCode[$counter1] = $row2['AllerCode'];
			$AllerName[$counter1] = $row2['AllerName'];
			$intensity[$counter1] = $row2['intensity'];
			$dateEvent[$counter1] = $row2['date'];
			$ageEvent[$counter1] = $row2['ageevent'];
			$deleted[$counter1] = $row2['deleted'];

			if ($counter1>0) $cadena.=',';    
			$cadena.='
			{
				"id":"'.$Id[$counter1].'",
				"VaccCode":"'.$VaccCode[$counter1].'",
				"VaccName":"'.$VaccName[$counter1].'",
				"AllerCode":"'.$AllerCode[$counter1].'",
				"AllerName":"'.$AllerName[$counter1].'",
				"intensity":"'.$intensity[$counter1].'",
				"dateEvent":"'.$dateEvent[$counter1].'",
				"ageEvent":"'.$ageEvent[$counter1].'",
				"deleted":"'.$deleted[$counter1].'"
				}';    

			$counter1++;
		}

		$encode = json_encode($cadena);
		if($display_type == 0){
			return '{"items":['.($cadena).']}'; 
		}else{
			echo '{"items":['.($cadena).']}'; 
		}
	}
	
	public function getRelative($display_type){
		$query = $this->con->prepare("SELECT * FROM p_family WHERE idpatient=?");
		$query->bindValue(1, $this->passport_id, PDO::PARAM_INT);
		$result = $query->execute();

		$count = $query->rowCount();
		$counter1 = 0 ;
		$cadena = '';
		while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
			$Id[$counter1] = $row2['id'];
			$relative[$counter1] = $row2['relative'];
			$disease[$counter1] = $row2['disease'];
			$diseasegroup[$counter1] = $row2['diseasegroup'];
			$ICD10[$counter1] = $row2['ICD10'];
			$ICD9[$counter1] = $row2['ICD9'];
			$atage[$counter1] = $row2['atage'];
			$alive[$counter1] = $row2['alive'];
			$deleted[$counter1] = $row2['deleted'];

			if ($counter1>0) $cadena.=',';    
			$cadena.='
			{
				"id":"'.$Id[$counter1].'",
				"relative":"'.$relative[$counter1].'",
				"disease":"'.$disease[$counter1].'",
				"diseasegroup":"'.$diseasegroup[$counter1].'",
				"ICD10":"'.$ICD10[$counter1].'",
				"ICD9":"'.$ICD9[$counter1].'",
				"atage":"'.$atage[$counter1].'",
				"alive":"'.$alive[$counter1].'",
				"deleted":"'.$deleted[$counter1].'"
				}';    

			$counter1++;
		}

		$encode = json_encode($cadena);
		if($display_type == 0){
			return '{"items":['.($cadena).']}'; 
		}else{
			echo '{"items":['.($cadena).']}'; 
		}	 
	}
	
	
	////////////////////////////////////////////////////START PRIVATE FUNCTIONS//////////////////////////////////////////
	private function capitalizeFirst($string)
	{
		return ucfirst(strtolower($string));
	}
	
	private function getGenderIcon($gender)
	{
		$path='images/PassportIcons/';
		switch($gender)
		{
			case 0:return '<img src="../../'.$path.'female_blue.png" style="height:30px;width:30px;margin-top:-10px" title="Female"></img>';
			case 1:return '<img src="../../'.$path.'male_blue.png" style="height:30px;width:30px;margin-top:-10px" title="Male"></img>';
			default:return '<img src="" style="height:30px;width:30px;margin-top:-10px" title="Gender Not Stored"></img>';;
		
		}

	}
	
	private function getString($string)
	{
		if($string==null || $string=='' || $string=='+' || preg_replace('/\s+/', '', $string) == ',')
		{

			$holder = $this->translations['unknown'];
			
			return $holder;
		}
		else
		{
			return $string;
		}
	}
	
	private function getColor($numDays)
	{
		if($numDays==-1)
		{
			return '#BCF5A9';
		}
		else if($numDays>=0 and $numDays<=10)
		{
			return '#9FF781';	
		}
		else if($numDays>=11 and $numDays<=30)
		{
			return '#82FA58';	
		}
		else if($numDays>=31 and $numDays<=90)
		{
			return '#64FE2E';	
		}
		else if($numDays>=91 and $numDays<=180)
		{
			return '#74DF00';	
		}
		else if($numDays>=181 and $numDays<=365)
		{
			return '#5FB404';	
		}
		else if($numDays>=366 and $numDays<=999)
		{
			return '#4B8A08';	
		}
		else
		{
			return '#38610B';	
		}
	}

	private function SpeColor($index)
	{
		$acolor[0]='black';   			$aname[0]='n/a';
		$acolor[1]='#b36ae2';   		$aname[1]='Neuro';
		$acolor[2]='#b313e2';   		$aname[2]='Otolaryngo';
		$acolor[3]='#6fc040';  			$aname[3]='Endocrino';
		$acolor[4]='#f39019';   		$aname[4]='Digestive';
		$acolor[5]='#164f86';   		$aname[5]='Pneumo';
		$acolor[6]='#c82606';  			$aname[6]='Cardio';
		$acolor[7]='#c81654';   		$aname[7]='Uro';
		$acolor[8]='#167c86';   		$aname[8]='Repro';
		$acolor[9]='#899b57';   		$aname[9]='Dermo';
		$acolor[10]='#53585f'; 			$aname[10]='Onco';
		$acolor[11]='#289b9d';   		$aname[11]='Trauma';

	return $acolor[$index];
	}

	private function check($relativename)
	{
		$n = 0;
		$count = 0;
		$found[0] = -1;
		while ($n < ($this->counter ))
		{	
			$compar = $this->relative_type[$n];
			if ($compar == $relativename) 
			{
				$found[$count] = $n;
				$count++;
			} 
			$n++;
		}
		return $found;
	}

	private function checkSlot ($row, $slot)
	{
		$x = 0;
		$found = -1;
		while ($x <= ($this->counter-1))
		{
			if (($this->slotage[$x] == $slot)  && ($this->slotrow[$x] == $row)) $found = $x; 
			$x++;
		}
		return $found;
	}

	private function degL($x1,$y1,$x2,$y2)
	{
		$a = $x2 - $x1;
		$b = $y2 - $y1;
		$L = sqrt(pow($a,2) + pow($b,2));
		
		$alfa = asin($b/$L);
		
		$solved[0] = $L;
		$solved[1] = rad2deg($alfa);
		
		return $solved;
	}

	private function daysOld($entryDate)
	{
		$ts2 = strtotime($entryDate);
		$today = strtotime(date("Y-m-d H:i:s"));	

		$seconds_diff = $today - $ts2 ;	
		$days = floor($seconds_diff/3600/24);	
		$years = floor($days/365);	
		
		return $days;
	}

	private function addNewBlankLine($number)
	{
		for($i=0;$i<$number;$i++)
		{
			echo '<tr>';
			echo '<td style="width:30px;text-align:center"><img src="../../images/line.jpg" style="height:20px;"></img></td>';
			 
			echo '<td></td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '</tr>';

		}

	}

	private function addNewBallLine($id,$dt,$icd,$surgery)
	{
		$rowid = $id;
		$notesid = "Note".$id;
		echo '<tr>';
		echo '<td style="width:30px;text-align:center"><i class="icon-circle"></i></td>';
		echo '<td style="width:70px;text-align:left">'.$dt.'</td>';
		echo '<td style="width:50px;text-align:left">'.$icd.'</td>';
		echo '<td style="cursor:pointer;width:100px;text-align:left;color:red" class="Surgery" id='.$rowid.'>'.$surgery.'</td>';
		echo '</tr>';
		
		
	}

	private function addNewLine($id,$dt,$icd,$surgery)
	{
		$rowid = $id;
		$notesid = "Note".$id;
		echo '<tr>';
		echo '<td style="width:30px;text-align:center"><img src="../../images/line.jpg" style="height:20px;"></img></td>';
		echo '<td style="width:70px;text-align:left">'.$dt.'</td>';
		echo '<td style="width:50px;text-align:left">'.$icd.'</td>';
		echo '<td style="cursor:pointer;width:100px;text-align:left;color:red" class="Surgery" id='.$rowid.'>'.$surgery.'</td>';
		echo '</tr>';
	}

	private function cleanPhoneNumber($phone)
	{
		return preg_replace("/[^0-9]/", "",$phone);
	}
	//AJAX CALL ONLY
	public function delete_diagnostics(){
	$id = $_GET['id'];

	if($_SESSION['isPatient']==1)
	{
		$edited=0;
	}
	else
	{
		$edited = $this->med_id;
	}



	$query = $this->con->prepare("update p_diagnostics set latest_update = now(), deleted=1,edited=? where id=?"); //Added latest_update = now() by Pallab to fix the delete ribbon issue, earlier latest_update not being there deleted items do not come up in the latest items list when considering items for deletion
	$query->bindValue(1, $edited, PDO::PARAM_INT);
	$query->bindValue(2, $id, PDO::PARAM_INT);
	$result = $query->execute();

	$count = $query->rowCount();
	echo  $count;
	}

	public function create_diagnostics(){
		$idpatient = $this->passport_id; //Changing it to $_GET['IdUsu'] from $_SESSION['UserID'] to fix no data found on DiagnosticsCOntainer Pallab
		$iddoctor = $this->med_id;

		//if ($iddoctor == $idpatient) $idauthor = -1; else $idauthor = $iddoctor;
		if ($iddoctor > 0) $idauthor = $iddoctor; else $idauthor = -1; 

		$dxname = $_GET['dxname'];
		$dxcode = $_GET['dxcode'];
		$sdate = $_GET['sdate'].'-01';
		$edate = $_GET['edate'];
		$notes = $_GET['notes'];
		$idrow = $_GET['rowediter'];

		if(isset($_GET['rowediter'])){
		$query = $this->con->prepare("UPDATE p_diagnostics SET deleted=1 WHERE id=?");
		$query->bindValue(1, $idrow, PDO::PARAM_INT);
		$result = $query->execute();
		}


		if(isset($_GET['nodiag']) && $_GET['nodiag'] == 'yes'){
		$query = $this->con->prepare("DELETE FROM p_diagnostics WHERE idpatient=? and deleted=0");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$result = $query->execute();

		$dxname = 'No History to Report';
		$dxcode = 'No History to Report';
		}

		if(!isset($_GET['nodiag'])){
		$query = $this->con->prepare("DELETE FROM p_diagnostics WHERE idpatient=? and deleted=0 and dxname='No History to Report'");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($edate!=null or $edate!='')
		{
			$edate = $edate.'-01';
		}
		else
		{
			$edate=null;
		}


		if($_SESSION['isPatient']==1)
		{
			$edited=0;
		}
		else
		{
			$edited = $this->med_id;
		}


		echo $sdate;

		$query = $this->con->prepare("INSERT INTO p_diagnostics SET idpatient=?,dxname=?,dxcode=?,dxstart=?,dxend=?,notes=?,edited=?,latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $dxname, PDO::PARAM_STR);
		$query->bindValue(3, $dxcode, PDO::PARAM_STR);
		$query->bindValue(4, $sdate, PDO::PARAM_STR);
		$query->bindValue(5, $edate, PDO::PARAM_STR);
		$query->bindValue(6, $notes, PDO::PARAM_STR);
		$query->bindValue(7, $edited, PDO::PARAM_INT);
		$query->bindValue(8, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
	}

	public function delete_medications(){
		$id = $_GET['id'];
		
		//if($_SESSION['isPatient']==1)
		//{
		//	$edited=0;
		//}
		//else
		//{
			$edited = $this->med_id;
		//}



		$query = $this->con->prepare("update p_medication set latest_update = now(),deleted=1,edited=? where id=?"); //Added latest_update = now() by Pallab to fix the delete ribbon issue, earlier latest_update not being there deleted items do not come up in the latest items list when considering items for deletion
		$query->bindValue(1, $edited, PDO::PARAM_INT);
		$query->bindValue(2, $id, PDO::PARAM_INT);
		$result = $query->execute();

		$count = $query->rowCount();
		echo $count;
	}

	public function create_medications(){
		$idpatient = $this->passport_id;
		$iddoctor = $this->med_id;
		//if ($iddoctor == $idpatient) $idauthor = -1; else $idauthor = $iddoctor;
		if ($iddoctor > 0) $idauthor = $iddoctor; else $idauthor = -1; 

		echo "Author ".$idauthor;

		$drugname = $_GET['drugname'];
		$drugcode = $_GET['drugcode'];
		$frequency = $_GET['frequency'];
		$dossage = $_GET['dossage'];
		$numDays = $_GET['numDays'];
		$idrow = $_GET['rowediter'];

		if(isset($_GET['rowediter'])){
		$query = $this->con->prepare("UPDATE p_medication SET deleted=1 WHERE id=?");
		$query->bindValue(1, $idrow, PDO::PARAM_INT);
		$result = $query->execute();
		}


		if(isset($_GET['nomeds']) && $_GET['nomeds'] == 'yes'){
		$query = $this->con->prepare("DELETE FROM p_medication WHERE idpatient=? and deleted=0");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$result = $query->execute();

		$drugname = "No Medications";
		$drugcode = "No Medications";
		}

		if(!isset($_GET['nomeds'])){
		$query = $this->con->prepare("DELETE FROM p_medication WHERE idpatient=? and deleted=0 and drugname='No Medications'");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//if($_SESSION['isPatient']==1)
		//{
		//	$edited=0;
		//}
		//else
		//{
			$edited = $this->med_id;
		//}

		//,latest_update=NOW(),doctor_signed='$idauthor'

		$query = $this->con->prepare("INSERT INTO p_medication SET idpatient=?,drugname=?,drugcode=?,frequency=?,dossage=?,edited=?,numDays=?,latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $drugname, PDO::PARAM_STR);
		$query->bindValue(3, $drugcode, PDO::PARAM_STR);
		$query->bindValue(4, $frequency, PDO::PARAM_STR);
		$query->bindValue(5, $dossage, PDO::PARAM_STR);
		$query->bindValue(6, $edited, PDO::PARAM_INT);
		$query->bindValue(7, $numDays, PDO::PARAM_INT);
		$query->bindValue(8, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();

		$query = $this->con->prepare("INSERT INTO p_log SET IdUsu = ?, IdAuthor = ?, Description = ?, Timestamp = NOW()");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$query->bindValue(3, "Medications - Added ".$drugname, PDO::PARAM_STR);
		$query->execute();
	}

	public function delete_relatives(){
		$id = $_GET['id'];

		//if($_SESSION['isPatient']==1)
		//{
		//	$edited=0;
		//}
		//else
		//{
			$edited = $this->med_id;
		//}



		$query = $this->con->prepare("update p_family set latest_update = now(), deleted=1,edited=? where id=?"); //Added latest_update = now() by Pallab to fix the delete ribbon issue, earlier latest_update not being there deleted items do not come up in the latest items list when considering items for deletion
		$query->bindValue(1, $edited, PDO::PARAM_INT);
		$query->bindValue(2, $id, PDO::PARAM_INT);
		$result = $query->execute();

		$count = $query->rowCount();
		echo $count;
	}
	
	public function create_relatives(){
		$idpatient = $this->passport_id;
		$iddoctor = $this->med_id;

		if ($iddoctor > 0) $idauthor = $iddoctor; else $idauthor = -1; 

		$relativename = $_GET['relativename'];
		$relativecode = $_GET['relativecode'];
		$diseasename = $_GET['diseasename'];
		$diseasegroup = $_GET['diseasegroup'];
		$ICD10 = empty($_GET['ICD10']) ? 0: $_GET['ICD10'];
		$ICD9 = empty($_GET['ICD9']) ? 0: $_GET['ICD9'];
		$ageevent = $_GET['ageevent'];
		$dateevent = $_GET['dateevent'];
		$idrow = $_GET['rowediter'];

		if(isset($_GET['rowediter'])){
		$query = $this->con->prepare("UPDATE p_family SET deleted=1 WHERE id=?");
		$query->bindValue(1, $idrow, PDO::PARAM_INT);
		$result = $query->execute();
		}

		$query = $this->con->prepare("INSERT INTO p_family SET idpatient=?,relative=?,relativetype=?,disease=?,diseasegroup=?,ICD10=?,ICD9=?,atage=?,latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $relativename, PDO::PARAM_STR);
		$query->bindValue(3, $relativecode, PDO::PARAM_INT);
		$query->bindValue(4, $diseasename, PDO::PARAM_STR);
		$query->bindValue(5, $diseasegroup, PDO::PARAM_INT);
		$query->bindValue(6, $ICD10, PDO::PARAM_INT);
		$query->bindValue(7, $ICD9, PDO::PARAM_INT);
		$query->bindValue(8, $ageevent, PDO::PARAM_INT);
		$query->bindValue(9, $idauthor, PDO::PARAM_INT);

		$result = $query->execute();;
		echo $result;

	}

	public function update_habits(){
		$idpatient = $this->passport_id;
		$iddoctor = $this->med_id;

		if ($iddoctor > 0) $idauthor = $iddoctor; else $idauthor = -1; 

		$cigarette = $_GET['cigarette'];
		$alcohol = $_GET['alcohol'];
		$exercise = $_GET['exercise'];
		$sleep = $_GET['sleep'];

		//echo "Cigarette : ".$cigarette."<br>";
		//echo "Alcohol : ".$alcohol."<br>";
		//echo "Exercise : ".$exercise."<br>";
		//echo "Sleep : ".$sleep."<br>";

		//if($_SESSION['isPatient']==1)
		//{
		//	$edited=0;
		//}
		//else
		//{
			$edited = $this->med_id;
		//}

		if($sleep == 0){
		$sleep = 8;
		}

		$query = $this->con->prepare("SELECT * FROM p_habits where idpatient=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$result = $query->execute();

		$count = $query->rowCount();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		echo "count:".$count."<br>";

		if($count==0)
			{
				$query = $this->con->prepare("INSERT INTO p_habits SET idpatient=?,cigarettes=?,alcohol=?,exercise=?,sleep=?,edited=?,latest_update=NOW(),doctor_signed=?");
				$query->bindValue(1, $idpatient, PDO::PARAM_INT);
				$query->bindValue(2, $cigarette, PDO::PARAM_INT);
				$query->bindValue(3, $alcohol, PDO::PARAM_INT);
				$query->bindValue(4, $exercise, PDO::PARAM_INT);
				$query->bindValue(5, $sleep, PDO::PARAM_INT);
				$query->bindValue(6, $edited, PDO::PARAM_INT);
				$query->bindValue(7, $idauthor, PDO::PARAM_INT);
				$result = $query->execute();
			
			}
			else
			{
				 $query = $this->con->prepare("UPDATE p_habits SET idpatient=?,cigarettes=?,alcohol=?,exercise=?,sleep=?,edited=?,latest_update=NOW(),doctor_signed=? where idpatient=?");
				 $query->bindValue(1, $idpatient, PDO::PARAM_INT);
				 $query->bindValue(2, $cigarette, PDO::PARAM_INT);
				 $query->bindValue(3, $alcohol, PDO::PARAM_INT);
				 $query->bindValue(4, $exercise, PDO::PARAM_INT);
				 $query->bindValue(5, $sleep, PDO::PARAM_INT);
				 $query->bindValue(6, $edited, PDO::PARAM_INT);
				 $query->bindValue(7, $idauthor, PDO::PARAM_INT);
				 $query->bindValue(8, $idpatient, PDO::PARAM_INT);
				 $result = $query->execute();
			}
	}
	
	public function delete_vaccines(){
		$id = $_GET['id'];

		//if($_SESSION['isPatient']==1)
		//{
		//	$edited=0;
		//}
		//else
		//{
			$edited = $this->med_id;
		//}



		$query = $this->con->prepare("update p_immuno set latest_update = now(), deleted=1,edited=? where id=?"); //Added latest_update = now() by Pallab to fix the delete ribbon issue, earlier latest_update not being there deleted items do not come up in the latest items list when considering items for deletion
		$query->bindValue(1, $edited, PDO::PARAM_INT);
		$query->bindValue(2, $id, PDO::PARAM_INT);
		$result = $query->execute();

		$count = $query->rowCount();
		echo $count;
	}
	
	public function create_immunodata(){
		$idpatient = 0;
		if(isset($this->passport_id))
		{
			$idpatient = $this->passport_id;
		}
		else
		{
			$idpatient = $_SESSION['UserID'];
		}
		$iddoctor = $this->med_id;

		if ($iddoctor > 0) $idauthor = $iddoctor; else $idauthor = -1; 

		$VaccCode = $_GET['VaccCode'];
		$VaccName = $_GET['VaccName'];
		$AllerCode = $_GET['AllerCode'];
		$AllerName = $_GET['AllerName'];
		$intensity = $_GET['intensity'];
		$dateEvent = $_GET['dateEvent'];
		$ageEvent = $_GET['ageEvent'];
		$dateEvent = $dateEvent.'-01';
		$dob = '';
		if(isset($_GET['dob']) && $_GET['dob'] != NULL && strlen($_GET['dob']) > 0)
		{
			$dob = $_GET['dob'];
		}
		else
		{
			$fetch_dob = $this->con->prepare("SELECT DOB FROM basicemrdata WHERE IdPatient = ?");
			$fetch_dob->bindValue(1, $idpatient, PDO::PARAM_INT);
			$fetch_dob->execute();
			if($fetch_dob->rowCount() > 0)
			{
				$row = $fetch_dob->fetch(PDO::FETCH_ASSOC);
				$dob = $row['DOB'];
				if(strlen($dob) > 10)
					$dob = substr($dob, 0, 10); // exclude time
			}
			else
			{
				$dob = '1970-01-01';
			}
		}


		$idrow = $_GET['rowediter'];

		if(isset($_GET['rowediter'])){
			$query = $this->con->prepare("UPDATE p_immuno SET deleted=1 WHERE id=?");
			$query->bindValue(1, $idrow, PDO::PARAM_INT);
			$result = $query->execute();
		}


		if(isset($_GET['noallergies']) && $_GET['noallergies'] == 'yes'){
			$query = $this->con->prepare("DELETE FROM p_immuno WHERE idpatient=? and deleted=0");
			$query->bindValue(1, $idpatient, PDO::PARAM_INT);
			$result = $query->execute();

			$ageEvent = 0;
			$AllerName = "Nothing";
			$AllerCode = "Nothing";
		}

		if(!isset($_GET['noallergies'])){
			$query = $this->con->prepare("DELETE FROM p_immuno WHERE idpatient=? and deleted=0 and AllerName='Nothing'");
			$query->bindValue(1, $idpatient, PDO::PARAM_INT);
			$result = $query->execute();
		}

		if(isset($_GET['dateEvent']) && $_GET['dateEvent'] != "")
		{
			$years = substr($dateEvent,0,4);
			$months = substr($dateEvent,5,2);
			$days = substr($dateEvent,8,2);

			$dob_years = substr($dob,0,4);
			$dob_months = substr($dob,5,2);
			$dob_days = substr($dob,8,2);

			$bday = new DateTime(''.$days.'.'.$months.'.'.$years.'');//dd.mm.yyyy
			$today = new DateTime(''.$dob_days.'.'.$dob_months.'.'.$dob_years.''); // Current date
			$diff = $today->diff($bday);


			if($diff->y > 0)
			{
				$year_hold = $diff->y." y";
			}
			else
			{
				$year_hold = $diff->m." m";
			}

			$ageEvent = $year_hold;
		}
		else
		{
			$ageEvent = $ageEvent." y";
		}


		if($VaccName != ""){
			$VaccCode = $VaccName;
		}

		if($AllerCode == ''){
			$AllerCode = substr($AllerName,0,4);
		}

		if($ageEvent == '' or $ageEvent == 0){
			$ageEvent = '0 y';
		}

		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode=?,VaccName=?,AllerCode=?,AllerName=?,intensity=?,date=?,ageevent=?,latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $VaccCode, PDO::PARAM_STR);
		$query->bindValue(3, $VaccCode, PDO::PARAM_STR);
		$query->bindValue(4, $AllerCode, PDO::PARAM_STR);
		$query->bindValue(5, $AllerName, PDO::PARAM_STR);
		$query->bindValue(6, $intensity, PDO::PARAM_INT);
		$query->bindValue(7, $dateEvent, PDO::PARAM_STR);
		$query->bindValue(8, $ageEvent, PDO::PARAM_STR);
		$query->bindValue(9, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
	}

	public function create_immuno_calendar(){
		require("../../NotificationClass.php");
		
		$notifications = new Notifications();
		
		$idpatient = $this->passport_id;
		$iddoctor = $this->med_id;

		if ($iddoctor > 0) $idauthor = $iddoctor; else $idauthor = -1; 

		$countryCode = $_GET['country'];
		$currentAge = $_GET['dob'];

		$dob = '';
		if(isset($_GET['dob']) && $_GET['dob'] != NULL && strlen($_GET['dob']) > 0)
		{
			$dob = $_GET['dob'];
		}
		else
		{
			$fetch_dob = $this->con->prepare("SELECT DOB FROM basicemrdata WHERE IdPatient = ?");
			$fetch_dob->bindValue(1, $idpatient, PDO::PARAM_INT);
			$fetch_dob->execute();
			if($fetch_dob->rowCount() > 0)
			{
				$row = $fetch_dob->fetch(PDO::FETCH_ASSOC);
				$dob = $row['DOB'];
				if(strlen($dob) > 10)
					$dob = substr($dob, 0, 10); // exclude time
			}
			else
			{
				$dob = '1970-01-01';
			}
		}

		$years = substr($dob,0,4);
		$months = substr($dob,5,2);
		$days = substr($dob,8,2);

		$bday = new DateTime(''.$days.'.'.$months.'.'.$years.'');//dd.mm.yyyy
			 $today = new DateTime('00:00:00'); // Current date
			 $diff = $today->diff($bday);
		//     printf('%d years, %d month, %d days', $diff->y, $diff->m, $diff->d);
		//	 var_dump($diff);

		//echo $diff->y."</br>";
		//echo $diff->m."</br>";
		//echo $diff->d;


		$result = null;
		//THIS IS FOR USA VACCINATION CALENDAR/////////////////////////////////////////////////////////////////////////////////////////////
		if($countryCode == "USA"){

		$query = $this->con->prepare("UPDATE p_immuno SET deleted=1 WHERE idpatient=? && VaccCode != ''");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$result = $query->execute();


		//This adds vaccines at birth
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPB',VaccName='HEPB',ageevent='0 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();

		//This adds vaccines at 1 month
		if($diff->y > 0 or $diff->m >= 1){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPB',VaccName='HEPB',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//These add vaccines at 2 months
		if($diff->y > 0 or $diff->m >= 2){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='ROTAVIRUS',VaccName='ROTAVIRUS',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 2){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAP',VaccName='DTAP',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 2){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HIB',VaccName='HIB',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 2){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 2){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='IPV',VaccName='IPV',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//These adds vaccines at 4 months
		if($diff->y > 0 or $diff->m >= 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='ROTAVIRUS',VaccName='ROTAVIRUS',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HIB',VaccName='HIB',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='IPV',VaccName='IPV',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//These add vaccines for 6 months
		if($diff->y > 0 or $diff->m >= 6){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPB',VaccName='HEPB',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 6){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAP',VaccName='DTAP',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 6){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 6){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='IPV',VaccName='IPV',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 6){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='INFLUENZA',VaccName='INFLUENZA',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//These add vaccines for 12 months
		if($diff->y >= 1 or $diff->m >= 12){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HIB',VaccName='HIB',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 1 or $diff->m >= 12){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 1 or $diff->m >= 12){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MMR',VaccName='MMR',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 1 or $diff->m >= 12){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='VARICELLA',VaccName='VARICELLA',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 1 or $diff->m >= 12){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPA',VaccName='HEPA',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//This adds vaccines for 15 months
		if($diff->y >= 2 or ($diff->y >= 1 && $diff->m >= 3)){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAP',VaccName='DTAP',ageevent='15 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//This adds vaccines for 2 years
		if($diff->y >= 2){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='INFLUENZA',VaccName='INFLUENZA',ageevent='2 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//These add vaccines for 4 years
		if($diff->y >= 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAP',VaccName='DTAP',ageevent='4 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='IPV',VaccName='IPV',ageevent='4 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MMR',VaccName='MMR',ageevent='4 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='VARICELLA',VaccName='VARICELLA',ageevent='4 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//These add vaccines for 11 years
		if($diff->y >= 11){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAP',VaccName='DTAP',ageevent='11 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 11){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MENC_CONJ',VaccName='MENC_CONJ',ageevent='11 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 11){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HPV',VaccName='HPV',ageevent='11 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//This add vaccines for 13 years
		if($diff->y >= 13){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MENC_CONJ',VaccName='MENC_CONJ',ageevent='13 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//These add vaccines for 19 years
		if($diff->y >= 19){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='19 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 29){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='29 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 39){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='39 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 49){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='49 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 59){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='59 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 69){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='69 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 60){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MENC_CONJ',VaccName='MENC_CONJ',ageevent='60 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		}

		//THIS IS FOR COLOMBIA VACCINATION CALENDAR/////////////////////////////////////////////////////////////////////////////////////////////
		if($countryCode == "COL"){

		$query = $this->con->prepare("DELETE FROM p_immuno WHERE idpatient=? && VaccCode != ''");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$result = $query->execute();

		//HEPB
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPB',VaccName='HEPB',ageevent='0 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();



		//ROTAVIRUS
		if($diff->y > 0 or $diff->m >= 2){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='ROTAVIRUS',VaccName='ROTAVIRUS',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='ROTAVIRUS',VaccName='ROTAVIRUS',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//ORAL POLIO VACCINE
		if($diff->y > 0 or $diff->m >= 2){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='OPV',VaccName='OPV',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='OPV',VaccName='OPV',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 6){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='OPV',VaccName='OPV',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 2 or ($diff->y >= 1 && $diff->m >= 6)){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='OPV',VaccName='OPV',ageevent='18 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 5){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='OPV',VaccName='OPV',ageevent='5 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//Pneumo_conj
		if($diff->y > 0 or $diff->m >= 2){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}
		if($diff->y >= 1 or $diff->m >= 12){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//DT
		if($diff->y > 0 or $diff->m >= 2){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DT',VaccName='DT',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DT',VaccName='DT',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 6){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DT',VaccName='DT',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 2 or ($diff->y >= 1 && $diff->m >= 6)){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DT',VaccName='DT',ageevent='18 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 5){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DT',VaccName='DT',ageevent='5 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//DTwP
		if($diff->y >= 2 or ($diff->y >= 1 && $diff->m >= 6)){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTWP',VaccName='DTWP',ageevent='18 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 5){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTWP',VaccName='DTWP',ageevent='5 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//Yellow Fever
		if($diff->y >= 1){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='YF',VaccName='YF',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//MMR
		if($diff->y >= 1 or $diff->m >= 12){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MMR',VaccName='MMR',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 5){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MMR',VaccName='MMR',ageevent='5 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//HEPA
		if($diff->y >= 1 or $diff->m >= 12){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPA',VaccName='HEPA',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		}


		//THIS IS FOR SPAIN VACCINATION CALENDAR/////////////////////////////////////////////////////////////////////////////////////////////
		if($countryCode == "SPA"){

		$query = $this->con->prepare("DELETE FROM p_immuno WHERE idpatient=? && VaccCode != ''");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$result = $query->execute();

		//HEPB
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPB',VaccName='HEPB',ageevent='0 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();

		//MenC_conj
		if($diff->y > 0 or $diff->m >= 2){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MENC_CONJ',VaccName='MENC_CONJ',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//DTaPHibHepIPV
		if($diff->y > 0 or $diff->m >= 2){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAPHIBHEPIPV',VaccName='DTAPHIBHEPIPV',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MENC_CONJ',VaccName='MENC_CONJ',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//DTaPHiblPV
		if($diff->y > 0 or $diff->m >= 4){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAPHIBIPV',VaccName='DTAPHIBIPV',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y > 0 or $diff->m >= 6){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAPHIBHEPIPV',VaccName='DTAPHIBHEPIPV',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 1 or $diff->m >= 12){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MENC_CONJ',VaccName='MENC_CONJ',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//MMR
		if($diff->y >= 1 or $diff->m >= 12){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MMR',VaccName='MMR',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 2 or ($diff->y >= 1 && $diff->m >= 6)){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAPHIBIPV',VaccName='DTAPHIBIPV',ageevent='18 m',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 3){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MMR',VaccName='MMR',ageevent='3 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//Tdap
		if($diff->y >= 6){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TDAP',VaccName='TDAP',ageevent='6 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//VARICELLA
		if($diff->y >= 10){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='VARICELLA',VaccName='VARICELLA',ageevent='10 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//HPV
		if($diff->y >= 11){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HPV',VaccName='HPV',ageevent='11 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 12){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='VARICELLA',VaccName='VARICELLA',ageevent='12 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 12){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HPV',VaccName='HPV',ageevent='12 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		if($diff->y >= 13){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HPV',VaccName='HPV',ageevent='13 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//TD
		if($diff->y >= 14){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='14 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}

		//INFLUENZA
		if($diff->y >= 64){
		$query = $this->con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='INFLUENZA',VaccName='INFLUENZA',ageevent='64 y',latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
		}


		}

		if($result != null)
		{
			if($_SESSION['BOTHID'] == $_SESSION['MEDID'])
			{
				// doctor
				$notifications->add('SUMEDT', $iddoctor, true, $idpatient, false, null);
			}
			else
			{
				$pers_doc = $this->con->prepare("SELECT personal_doctor FROM usuarios WHERE Identif = ?");
				$pers_doc->bindValue(1, $idpatient, PDO::PARAM_INT);
				$pers_doc->execute();
				$pers_doc_row = $pers_doc->fetch(PDO::FETCH_ASSOC);
				if($pers_doc_row['personal_doctor'] != NULL)
				{
					$notifications->add('SUMEDT', $idpatient, false, $pers_doc_row['personal_doctor'], true, null);
				}
			}
		}
	}
	
	public function get_dob(){
		$queUsu = $this->passport_id;

		$query = $this->con->prepare("SELECT * FROM basicemrdata WHERE IdPatient=?");
		$query->bindValue(1, $queUsu, PDO::PARAM_INT);
		$result = $query->execute();

		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$DOB = substr($row['DOB'],0,10);
		}

		$cadena='
			{
				"id":"'.$queUsu.'",
				"dob":"'.$DOB.'"
				}';    


		$encode = json_encode($cadena);
		echo '{"items":['.($cadena).']}'; 
	}

	public function update_additional_info(){
		require("../../NotificationClass.php");
		
		$notifications = new Notifications();
		
		$idpatient = $this->passport_id;
		$iddoctor = $this->med_id;
		
		$insurance = $_GET['insurance'];
		$address = $_GET['address'];
		$city = $_GET['city'];
		$state = $_GET['state'];
		$zip = $_GET['zip'];
		$telefono = $this->cleanPhoneNumber($_GET['phone']);
		$email = $_GET['email'];
		$sexo  = $_GET['gender'];
		$blood_type = $_GET['blood_type'];
		$weight = strval($_GET['weight']);
		$weight_type = intval($_GET['weight_type']);
		$height1 = intval($_GET['height1']);
		$height2 = intval($_GET['height2']);
		$height_type = intval($_GET['height_type']);
		if($sexo == 'Male')
		{
			$sexo = 1;
		}
		else
		{
			$sexo = 0;
		}

		$dob = $_GET['dob'].' 00:00:00';

		$checkRow = $this->con->prepare("SELECT * FROM basicemrdata WHERE IdPatient=? ");
		$checkRow->bindValue(1, $idpatient, PDO::PARAM_INT);
		$resultRow = $checkRow->execute();

		if($checkRow->rowCount() > 0){

		$queryText = $this->con->prepare("Update basicemrdata SET insurance=?, address=?,City=?, state=?, zip=?,latest_update=NOW(),doctor_signed=?,DOB=?,bloodType=?,weight=?,weightType=?,height1=?,height2=?,heightType=?  where IdPatient=? ");
		$queryText->bindValue(1, $insurance, PDO::PARAM_STR);
		$queryText->bindValue(2, $address, PDO::PARAM_STR);
		$queryText->bindValue(3, $city, PDO::PARAM_STR);
		$queryText->bindValue(4, $state, PDO::PARAM_STR);
		$queryText->bindValue(5, $zip, PDO::PARAM_STR);
		$queryText->bindValue(6, $idauthor, PDO::PARAM_INT);
		$queryText->bindValue(7, $dob, PDO::PARAM_STR);
		$queryText->bindValue(8, $blood_type, PDO::PARAM_STR);
		$queryText->bindValue(9, $weight, PDO::PARAM_STR);
		$queryText->bindValue(10, $weight_type, PDO::PARAM_INT);
		$queryText->bindValue(11, $height1, PDO::PARAM_INT);
		$queryText->bindValue(12, $height2, PDO::PARAM_INT);
		$queryText->bindValue(13, $height_type, PDO::PARAM_INT);
		$queryText->bindValue(14, $idpatient, PDO::PARAM_INT);
		$result = $queryText->execute();


		}else{ 

			$queryText = $this->con->prepare("INSERT INTO basicemrdata SET IdPatient=?,insurance=?,address=?,City=?, state=?, zip=?,latest_update=NOW(),doctor_signed=?,DOB=?,bloodType=?,weight=?,weightType=?,height1=?,height2=?,heightType=?");
			$queryText->bindValue(1, $idpatient, PDO::PARAM_INT);
			$queryText->bindValue(2, $insurance, PDO::PARAM_STR);
			$queryText->bindValue(3, $address, PDO::PARAM_STR);
			$queryText->bindValue(4, $city, PDO::PARAM_STR);
			$queryText->bindValue(5, $state, PDO::PARAM_STR);
			$queryText->bindValue(6, $zip, PDO::PARAM_STR);
			$queryText->bindValue(7, $idauthor, PDO::PARAM_INT);
			$queryText->bindValue(8, $dob, PDO::PARAM_STR);
			$queryText->bindValue(9, $blood_type, PDO::PARAM_STR);
			$queryText->bindValue(10, $weight, PDO::PARAM_STR);
			$queryText->bindValue(11, $weight_type, PDO::PARAM_INT);
			$queryText->bindValue(12, $height1, PDO::PARAM_INT);
			$queryText->bindValue(13, $height2, PDO::PARAM_INT);
			$queryText->bindValue(14, $height_type, PDO::PARAM_INT);

			$result = $queryText->execute();
		} 

		$queryText = $this->con->prepare("Update usuarios SET telefono=?, email=?, Sexo=? where Identif=? ");
		$queryText->bindValue(1, $telefono, PDO::PARAM_STR);
		$queryText->bindValue(2, $email, PDO::PARAM_STR);
		$queryText->bindValue(3, $sexo, PDO::PARAM_INT);
		$queryText->bindValue(4, $idpatient, PDO::PARAM_INT);

		$result = $queryText->execute();

		if (!$queryText) 
		{ 
			$error = "MySQL error \n<br>When executing:<br>\n$queryText\n<br>"; 
			echo $error;
		} 

		if($_SESSION['BOTHID'] == $_SESSION['MEDID'])
		{
			// doctor
			$notifications->add('SUMEDT', $iddoctor, true, $idpatient, false, null);
		}
		else
		{
			$pers_doc = $this->con->prepare("SELECT personal_doctor FROM usuarios WHERE Identif = ?");
			$pers_doc->bindValue(1, $idpatient, PDO::PARAM_INT);
			$pers_doc->execute();
			$pers_doc_row = $pers_doc->fetch(PDO::FETCH_ASSOC);
			if($pers_doc_row['personal_doctor'] != NULL)
			{
				$notifications->add('SUMEDT', $idpatient, false, $pers_doc_row['personal_doctor'], true, null);
			}
		}
	}
	
	public function validate_email(){
		$email = $_POST['email'];
		$type = $_POST['type'];
		$idpatient = $this->passport_id;
		$iddoctor = $this->med_id;

		$result = 0;

		if($at = strstr($email, '@'))
		{
			if($dot = strstr($at, '.'))
			{
				$result = 1;
			}
			else
			{
				$result = 2;
			}
		}
		else
		{
			$result = 2;
		}

		if($result == 1)
		{
			if($type == '0')
			{
				// patients
				
				$query = $this->con->prepare("SELECT Identif FROM usuarios WHERE email = ? AND Identif != ?");
				$query->bindValue(1, $email, PDO::PARAM_STR);
				$query->bindValue(2, $idpatient, PDO::PARAM_INT);
				$query->execute();
				$count = $query->rowCount();
				if($count == 0)
				{
					$result = 1;
				}
				else
				{
					$result = 0;
				}
			}
			else if($type == '1')
			{
				// doctors
				
				$query = $this->con->prepare("SELECT id FROM doctors WHERE IdMEDEmail = ? AND id <> ?");
				$query->bindValue(1, $email, PDO::PARAM_STR);
				$query->bindValue(2, $iddoctor, PDO::PARAM_INT);
				$query->execute();
				$count = $query->rowCount();
				if($count == 0)
				{
					$result = 1;
				}
				else
				{
					$result = 0;
				}
			}
		}

		echo $result;
	}
	//////////////////////////////////////////////START MODEL//////////////////////////////////////////////
	
	public function display_medical_passport(){
		require_once("../../displayExitClass.php");
		
		$NombreEnt = $_SESSION['Nombre'];
		$PasswordEnt = $_SESSION['Password'];
		$MEDID = $_SESSION['BOTHID'];
		if (isset($_SESSION['UserID'])) $UserID = $_SESSION['UserID']; else  $UserID = -1;
		if( isset($this->passport_id) ) {$UserID = $this->passport_id;} 
		$Acceso = $_SESSION['Acceso'];
		$privilege=$_SESSION['Previlege'];
		if ($Acceso != '23432')
		{
		//$exit_display = new displayExitClass();

		//$exit_display->displayFunction(1);
		//die;
		}

		if(isset($SESSION['MEDID'])){
		unset($_SESSION['UserID']);
		}

		$is_modal = false;
		if(isset($_GET['modal']) && $_GET['modal'] == 1)
		{
			$is_modal = true;
		}

		$result = $this->con->prepare("SELECT * FROM usuarios where Identif=?");
		$result->bindValue(1, $UserID, PDO::PARAM_INT);
		$result->execute();

		$count = $result->rowCount();
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$success ='NO';
		if($count==1){
			
			$UserID = $row['Identif'];
			$UserEmail= $row['email'];
			$UserName = $row['Name'];
			$UserSurname = $row['Surname'];
			$IdUsFIXED = $row['IdUsFIXED'];
			$IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
			$privilege=1;
		} 
		else
		{
		//$exit_display = new displayExitClass();
		//$exit_display->displayFunction(3);
		//die;
		}

		$isPatient=0;
		if ($MEDID == $UserID) $isPatient=1;
		
		
		echo '<!DOCTYPE html>
		<html id ="html" lang="en" style="background: #F9F9F9;"><head>
			<meta charset="utf-8">
			<title>Inmers - Center Management Console</title>
			<link rel="icon" type="image/icons" href="../../favicon.ico"/>
			
			<!-- Create language switcher instance and set default language to en-->
		<script src="../../jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
		<script src="../../jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
		<script type="text/javascript">
		var lang = new Lang("en");
			window.lang.dynamic("th", "../../jquery-lang-js-master/js/langpack/th.json");

		function delete_cookie( name ) {
		  document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:01 GMT;";
		}

		function pageRefresh(){
		location.reload();
		}

		//alert($.cookie("lang"));

		var langType = $.cookie("lang"); //Changed this $.cookie("lang") to "langCookie"

		if(langType == "th"){
		var language = "th";
		}else{
		var language = "en";
		}
		console.log("Language: " + language);

		if(langType == "th"){
		setTimeout(function(){
		//window.lang.change("th");
		lang.change("th");
		console.log("th");
		}, 10000); //Changed the value from 5000 to 10000
		}


		if(langType == "en"){
		setTimeout(function(){
		//window.lang.change("en");
		lang.change("en");
		console.log("en");
		}, 10000); //Changed the value from 5000 to 10000
		}
			
		</script>
			
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="">
			<meta name="author" content="">

			<!-- Le styles -->
			<link href="../../css/style.css" rel="stylesheet">
			<link href="../../css/bootstrap.css" rel="stylesheet">
			<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
			<link rel="stylesheet" href="../../css/jquery-ui-1.8.16.custom.css" media="screen"  />
			<link rel="stylesheet" href="../../css/fullcalendar.css" media="screen"  />
			<link rel="stylesheet" href="../../css/chosen.css" media="screen"  />
			<link rel="stylesheet" href="../../css/datepicker.css" >
			<link rel="stylesheet" href="../../css/colorpicker.css">
			<link rel="stylesheet" href="../../css/glisse.css?1.css">
			<link rel="stylesheet" href="../../css/jquery.jgrowl.css">
			<link rel="stylesheet" href="../../js/elfinder/css/elfinder.css" media="screen" />
			<link rel="stylesheet" href="../../css/jquery.tagsinput.css" />
			<link rel="stylesheet" href="../../css/demo_table.css" >
			<link rel="stylesheet" href="../../css/jquery.jscrollpane.css" >
			<link rel="stylesheet" href="../../css/validationEngine.jquery.css">
			<link rel="stylesheet" href="../../css/jquery.stepy.css" />
			<!--<link rel="stylesheet" type="text/css" href="../../css/googleAPIFamilyCabin.css">-->
			  <script type="text/javascript" src="../../js/42b6r0yr5470"></script>
			<link rel="stylesheet" href="../../css/icon/font-awesome.css">
		 <!--   <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet"> -->
			<link rel="stylesheet" href="../../css/bootstrap-responsive.css">
			<link rel="stylesheet" href="../../css/toggle-switch.css">
			<link rel="stylesheet" href="../../build/css/intlTelInput.css">';
			
			
				if ($_SESSION["CustomLook"]=="COL") { 
				echo '<link href="../../css/styleCol.css" rel="stylesheet">';
				}  
		echo '<!--    <link href="../../css/FamilyTree.css" rel="stylesheet">-->
			
			<script type="text/javascript" src="../../js/jquery.easing.1.3.js"></script>	
			<script type="text/javascript" src="../../js/modernizr.2.5.3.min.js"></script>	
			<!--<script src="../../js/sweet-alert.min.js"></script>-->
			

		 
			<!-- Le fav and touch icons -->
			<link rel="shortcut icon" href="../../images/icons/favicon.ico">
				<style>
				.ui-progressbar {
				position: relative;
				}
				.progress-label {
				position: absolute;
				left: 50%;
				top: 4px;
				font-weight: bold;
				text-shadow: 1px 1px 0 #fff;
				}
				.ui-dialog{
					overflow:visible;
				}
				.ui-datepicker-trigger{
					margin-left:-125px;
				}
			</style>
			<style>
			#overlay {
			  background-color: none;
			  position: auto;
			  top: 0; right: 0; bottom: 0; left: 0;
			  opacity: 1.0; /* also -moz-opacity, etc. */
			  
			}
			#messagecontent {
			  white-space: pre-wrap;   
			}
			</style>
			  <style>
				#progressbar .ui-progressbar-value {
				background-color: #ccc;
				}
			  </style>
			<style>
				.intl-tel-input{
					width:100%;
					margin-top:10px;
				}
			</style>
			  
			 <style>
				html, body { height: 100%; }
				body .modal {
				  width: 70%;
				  left: 15%;
				  margin-left:auto;
				  margin-right:auto; 
				}
				.modal-body {
					height:1000px;
				}
				.InfoRow{
					border:solid 1px #cacaca; 
					height:20px; 
					padding:5px;
					margin-left:5px;
					margin-right:5px;
					margin-top:-1px;	
				}
				.PatName{
					margin-left:10px;
					font-size:14px;
					color:grey;	
				}
				body .ui-autocomplete {
				  background-color:white;
				  color:#22aeff;
				  /* font-family to all */
				}
			</style> 
			  <style>
		  #draggable {
			width: 30px;
			height: 10px;
		  }
		  
		  #dropable {
			width: 30px;
			height: 10px;
		  }
		  </style>
			<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(["_setAccount", "UA-37863944-1"]);
		  _gaq.push(["_setDomainName", "health2.me"]);
		  _gaq.push(["_trackPageview"]);

		  (function() {
			var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
			ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
			var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>

		  </head>

		  <body id = "body"'; 
		  if($is_modal){ echo 'style="background: #FFF; width: 1000px; height: 690px;overflow: hidden;"';} else { echo 'style="background: #F9F9F9;"'; }
		  echo '>
		<input type="hidden" id="NombreEnt" value="'.$NombreEnt.'">
		<input type="hidden" id="PasswordEnt" value="'.$PasswordEnt.'">
		<input type="hidden" id="UserHidden">

			  
			<!--Header Start-->';
			  if($is_modal){ echo "<!--"; }
			echo '<div class="header" >
					<a href="../../index.html" class="logo"><h1>Health2me</h1></a>
					<div class="pull-right">
					  
							  
				   <div class="btn-group pull-right" >
				   
					<a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
					  <span class="name-user"><strong lang="en">Welcome</strong>'.$UserName.' '.$UserSurname.'</span>'; 
					 
					 $hash = md5( strtolower( trim( $UserEmail ) ) );
					 $avat = "identicon.php?size=29&hash=".$hash;
						
					  echo '<span class="avatar" style="background-color:WHITE;"><img src="../../'.$avat.'" alt="" ></span> 
					  <span class="caret"></span>
					</a>
					<div class="dropdown-menu" id="prof_dropdown">
					<div class="item_m"><span class="caret"></span></div>
					<ul class="clear_ul" >
					<li>';
					 if ($privilege==1)
							echo '<a href="../../UserDashboard.php">';
						   else if($privilege==2)
							echo '<a href="../../patients.php">';
					 
					echo '<i class="icon-globe"></i> <span lang="en">Home</span></a></li>
					  
					  <li><a href="../../medicalConfiguration.php" lang="en"><i class="icon-cog"></i> Settings</a></li>
					  <li><a href="../../logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
					</ul>
					</div>
				  </div>
				  
				  </div>
			</div>';
			if($is_modal){ echo ""; } 
			echo '<!--Header END-->
			
			 
			<!--Content Start-->';
			
					if(!$is_modal)
					{
						echo '<div id="content" style="background: #F9F9F9; padding-left:0px;">';
					}
					else
					{
						echo '<div id="content" style="background: #FFF; padding-left:0px; width: 1000px; height: 660px;">';
					}
			
			echo '<div class="ribbon-banner" style="text-align:center;" id="ribbon-verified" href = "#"></div> <!-- Added style of text-align:center element by Pallab to fix the text alignment of verified ribbon. This was to be done manually because the class ribbon-banner though it is getting loaded, but text-align:center property is getting overridden is missing at run time-->
			
			<div id="verifyBanner"></div>
			
			

			
					 
			 
			 <!--CONTENT MAIN START-->';
			  
				if($is_modal)
				{
					echo '<div style="width:1000px; height:660px; padding: 0px; padding-top: 30px;">';
				}
				else
				{
					echo '<div class="content">';
					echo '<div class="grid" class="grid span4" style="width:68%; height:630px; margin: 0 auto; margin-top:30px; padding-top:30px;">';
				} 
		
					//$this->getHabitsInfo();
					echo '
						'.$this->getBasicPatientInfo(0).'

					<div  id="DiagnosticHistoryInfo" class="grid" class="grid span4" style="cursor:pointer; overflow:auto;float:right; width:50%; height:45.5%; margin-top:-2%;  margin-right:1%; padding:.5%; display:table; position:relative;">
						'.$this->getDiagnosticInfo(0).'
					</div>
						'.$this->getAdditionalPatientInfo(0).'

					
						'.$this->getMedicationInfo(0).'

						'.$this->getFamilyHistoryInfo(0).'	

						'.$this->getHabitsInfo(0).'		

						'.$this->getImmunoAllergyInfo(0, "immuno").'

					
						'.$this->getImmunoAllergyInfo(0, "allergy").'
					
					 
				</div>
			 </div>
			 <!--CONTENT MAIN END-->';
	}
}

?>
