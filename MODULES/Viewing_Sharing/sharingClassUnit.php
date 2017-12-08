<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<script src='../../js/jquery.min.js'></script>
	  <script src='../../js/jquery-ui.min.js'></script>";

echo "<form action='sharingClassUnit.php' method='post'>
<input type='text' style='display:none;' id='mem-id' name='mem_id' placeholder='Member ID' />
<input type='text' style='display:none;' id='doc-id' name='doc_id' placeholder='Doctor ID' />
<input type='text' style='display:none;' id='string' name='string' placeholder='Search String' />

<div id='group-toggle' style='display:none;'><input type='radio' id='group-toggle1' name='group_toggle' value='0'>Exclude Group
<input type='radio' id='group-toggle2' name='group_toggle' value='1'>Include Group</br>
<input type='text' name='offset' placeholder='Offset' />
<input type='text' name='limit' placeholder='Limit' /></br>
<input type='radio' id='data-type-toggle' name='data_type_format' value='0'/>Var_Dump
<input type='radio' id='data-type-toggle' name='data_type_format' value='1'/>JSON
</div>

<div id='group-toggle-reports' style='display:none;'>
<input type='radio' id='data-type-toggle' name='data_type_format' value='0'/>Var_Dump
<input type='radio' id='data-type-toggle' name='data_type_format' value='1'/>JSON
</div>

<div id='group-toggle-access' style='display:none;'>
<input type='text' name='user_id' placeholder='User ID' />
<input type='text' name='id_pin' placeholder='Pin ID' />
<select name='access_type'>
<option value='view' />View
<option value='reshare' />Reshare
<option value='delete' />Delete
</select>
<input type='radio' name='is_doctor' value='0' />Not Doctor
<input type='radio' name='is_doctor' value='1' />Is Doctor
</div>

<input onclick='displayBoxes(0);' type='radio' name='data_type' value='0'>View doctors patients
<input onclick='displayBoxes(1);' type='radio' name='data_type' value='1'>View patients reports as doctor
<input style='display:none;' onclick='displayBoxes(3);' type='radio' name='data_type' value='3'><span style='display:none;'>View patients reports as member</span>
<input onclick='displayBoxes(2);' type='radio' name='data_type' value='2'>Check for access
<input id='submit' type='submit' style='display:none;' name='submit' value='Submit'>
</form>";

echo "<div id='instructions1' style='display:none;'>
Please enter the ID of the doctor to get a <b>FULL</b> list of all the doctors patients.  </br></br>Please be sure include the <b>offset</b> and <b>limit</b>.</br>You can specify <b>var_dump</b> or <b>json</b> depending on what you would like returned.
</div>";

echo "<div id='instructions2' style='display:none;'>
Please enter a doctor ID and member ID.  This will populate a full list of all the reports the doctor can see for that user.</br></br>You can specify <b>var_dump</b> or <b>json</b> depending on what you would like returned.
</div>";

echo "<div id='instructions3' style='display:none;'>
Please enter the id of the user in question.  The id can either be for a doctor or a member.  </br></br>Don't forget to specify if it <b>is a doctor</b> or is <b>not a doctor</b>.  </br>The <b>dropdown</b> will specify which type of access you would like to check.
</br></br>If <b>1</b> is returned then yes they have access.  If <b>0</b> is returned then they do not have access.
</div>";

include('sharingClass.php');

$sharing = new sharingClass();

//$sharing->viewingList(2641, '', 0, 100, 0);
//$sharing->displayList();
//$sharing->checkForAccess(2659, 0, 'delete', 29516);
//$sharing->buildLifeArray(2659, 2641);
//$sharing->displayPins();

if(isset($_POST['submit']) && $_POST['data_type'] == 0){
	$sharing->viewingList($_POST['doc_id'], $_POST['string'], $_POST['group_toggle'], $_POST['limit'], $_POST['offset']);
	if(isset($_POST['data_type_format']) && $_POST['data_type_format'] == 0){
		$sharing->displayList();
	}else{
		$sharing->displayListJSON();
	}
}

if(isset($_POST['submit']) && $_POST['data_type'] == 1){
	$sharing->buildLifeArray($_POST['mem_id'], $_POST['doc_id']);
	if(isset($_POST['data_type_format']) && $_POST['data_type_format'] == 0){
		$sharing->displayPins();
	}else{
		$sharing->displayPinsJSON();
	}
}

if(isset($_POST['submit']) && $_POST['data_type'] == 2){
	if(isset($_POST['is_doctor']) && $_POST['is_doctor'] == 1){
	$sharing->viewingList($_POST['user_id'], '', 1, 10000, 0);
	}
	$sharing->checkForAccess($_POST['user_id'], $_POST['is_doctor'], $_POST['access_type'], $_POST['id_pin']);
}

echo "<script>
function displayBoxes(box_type){
	if(box_type == 0){
		$('#doc-id').show();
		$('#string').show();
		$('#instructions1').show();
		$('#group-toggle').show();
		$('#submit').show();
	}
	
	if(box_type == 1){
		$('#doc-id').show();
		$('#mem-id').show();
		$('#instructions2').show();
		$('#group-toggle-reports').show();
		$('#submit').show();
	}
	
	if(box_type == 2){
		$('#group-toggle-access').show();
		$('#instructions3').show();
		$('#submit').show();
	}
}
</script>";

?>
