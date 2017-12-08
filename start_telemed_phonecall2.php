<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include("twilioPhoneClass.php");

if(!isset($_GET['stage'])){
echo "Program: Twilio Phone Script<p></p>Author: Kyle Austin<p></p>Code Timestamp: Dec. 29, 2014<p></p>";

echo "INIT CALL FORM</br>
Kyle Austin (patient): {ID=2158, Num=2147349964}</br>
Bruno Lima (doctor): {ID=2543, Num=9723756339}";

echo "<form method='post' action='start_telemed_phonecall2.php?stage=init-call' >
<input  type='text' name='pat_phone' placeholder='Patient Phone Number'>
<input  type='text' name='doc_phone' placeholder='Doctor Phone Number'>
<input  type='text' name='pat_id' placeholder='Patient ID'>
<input  type='text' name='doc_id' placeholder='Doctor ID'>
<input  type='text' name='pat_name' placeholder='Patient Name'>
<input  type='text' name='doc_name' placeholder='Doctor Name'>
<input  type='text' name='description' placeholder='Consult Description'>
Client:<select name='grant_access'>
<option value=''>Use Member's Credentials</option>
<option value='HTI-COL'>HTI-COL</option>
<option value='HTI-RIVA'>HTI-RIVA</option>
<option value='H2M'>H2M</option>
</select>
<input type='submit' value='Submit'>
</form> ";
}

//Build Class...
$phone_prompt = new twilioPhoneClass();
//INIT CALL////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['pat_phone'])  && isset($_GET['stage']) && $_GET['stage'] == 'init-call'){
	echo "INITIATING CALL!";
	//VARIABLE VALIDATION/
	if(isset($_POST["doc_phone"])){
		$doc_phone = $_POST["doc_phone"];
	}else{
		$doc_phone = 0;
	}
	
	if(isset($_POST['pat_id'])){
		$pat_id = $_POST['pat_id'];
	}else{
		$pat_id = 0;
	}
	
	if(isset($_POST['doc_id'])){
		$doc_id = $_POST['doc_id'];
	}else{
		$doc_id = 0;
	}
	
	if(isset($_POST['pat_name'])){
		$pat_name = $_POST['pat_name'];
	}else{
		$pat_name = 0;
	}
	
	if(isset($_POST['doc_name'])){
		$doc_name = $_POST['doc_name'];
	}else{
		$doc_name = 0;
	}
	
	if(isset($_POST['description'])){
		$description = $_POST['description'];
	}else{
		$description = "No Description";
	}
	
	if(isset($_POST['grant_access'])){
		$grant_access = $_POST['grant_access'];
	}else{
		$grant_access = '';
	}
	
	if(isset($_POST['override']) && $_POST['override'] == true)
	{
		$override = true;
	}else{
		$override = false;
	}
	
	if(isset($_POST['override_doctor_validation']) && $_POST['override_doctor_validation'] == true)
	{
		$override_doc_validation = true;
	}else{
		$override_doc_validation = false;
	}
	//VALIDATION END/
	
	//Initiate call...
	$phone_prompt->initiate_call($_POST["pat_phone"],$doc_phone, $pat_id, $doc_id, $pat_name, $doc_name, $description, $override, $override_doc_validation, $grant_access);
}
//REQ-DOC////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_GET['conference_id']) && isset($_GET['pat_name']) && isset($_GET['stage']) && $_GET['stage'] == 'req-doc'){
	//Request appointment prompt to doctor...
	$phone_prompt->request_appointment_from_doc($_GET['conference_id'], $_GET['pat_name'], $_GET['grant_access']);
}
//REQ-PAT////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_GET['grant_access']) && isset($_GET['info']) && isset($_GET['stage']) && $_GET['stage'] == 'req-pat'){
	//SETTERS
	$info = explode("-", $_GET['info']);
	$doctor_id = $info[0];
	$patient_id = $info[3];
	$recent_id = $info[4];
	$grant_access = $_GET['grant_access'];
	
	//Request appointment prompt for member...
	$phone_prompt->request_appointment_from_pat($doctor_id, $patient_id, $recent_id, $grant_access, $_GET['info']);
}
//REQ-PAT-STAGE2////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_GET['grant_access']) && isset($_GET['info']) && isset($_GET['stage']) && $_GET['stage'] == 'req-pat-stage2'){
	//SETTERS
	$info = explode("-", $_GET['info']);
	$doctor_id = $info[0];
	$patient_id = $info[3];
	$recent_id = $info[4];
	$grant_access = $_GET['grant_access'];
	
	if(isset($_REQUEST['Digits'])){
		//Request appointment prompt for member...
		$phone_prompt->request_appointment_from_pat_stage2($doctor_id, $patient_id, $recent_id, $_GET['info']);
	}else{
		//If no digits pressed repeat prompt
		$phone_prompt->request_appointment_from_pat($doctor_id, $patient_id, $recent_id, $grant_access, $_GET['info']);
	}
	

}
//APT-CALLBACK////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_GET['info']) && isset($_GET['stage']) && $_GET['stage'] == 'apt-callback'){
	//SETTERS
	$info = explode("-", $_GET['info']);
	$doctor_id = $info[0];
	$patient_id = $info[3];
	
	if(isset($_GET['RecordingUrl'])){
		$recording_url = $_POST['RecordingUrl'];
	}else{
		$recording_url = '';
	}
	
	//Callback script
	$phone_prompt->appointment_callback($recording_url, $doctor_id, $patient_id, $info);
}
//APT-LEAVE////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_GET['id']) && isset($_GET['stage']) && $_GET['stage'] == 'apt-leave'){
	//SETTERS
	$doctor_id = $_GET['id'];
	
	//Appointment leave call
	$phone_prompt->appointment_leave($doctor_id);
}
?>
