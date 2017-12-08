<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
session_start();

include('medicalPassportClass.php');

$mem_id = $_GET['IdUsu'];
//$doc_id = $_SESSION['MEDID'];
$doc_id = $_GET['doc_id'];
$crypt = $_SESSION['decrypt'];
$ajax = $_GET['ajax'];

$passport = new medicalPassportClass($mem_id, $doc_id, $crypt, $ajax);

if(isset($_GET['data_type'])){
	if($_GET['data_type'] == 'getBasicPatientInfo'){
		$passport->getBasicPatientInfo(1);
	}elseif($_GET['data_type'] == 'getAdditionalPatientInfo'){
		$passport->getAdditionalPatientInfo(1);
	}elseif($_GET['data_type'] == 'updateBasicPatientInfo'){
		$passport->update_additional_info(1);
	}elseif($_GET['data_type'] == 'getMedicationInfo'){
		$passport->getMedicationInfo(1);
	}elseif($_GET['data_type'] == 'getFamilyHistoryInfo'){
		$passport->getFamilyHistoryInfo(1);
	}elseif($_GET['data_type'] == 'getImmunoInfo'){
		$passport->getImmunoAllergyInfo(1, 'immuno');
	}elseif($_GET['data_type'] == 'getAllergyInfo'){
		$passport->getImmunoAllergyInfo(1, 'allergy');
	}elseif($_GET['data_type'] == 'getDiagnosticInfo'){
		$passport->getDiagnosticInfo(1);
	}elseif($_GET['data_type'] == 'getDob'){
		$passport->get_dob();
	}elseif($_GET['data_type'] == 'validateEmail'){
		$passport->validate_email();
	}elseif($_GET['data_type'] == 'updateHabits'){
		$passport->update_habits();
	}elseif($_GET['data_type'] == 'createMedication'){
		$passport->create_medications();
	}elseif($_GET['data_type'] == 'createImmunoCalendar'){
		$passport->create_immuno_calendar();
	}elseif($_GET['data_type'] == 'createImmunoData'){
		$passport->create_immunodata();
	}elseif($_GET['data_type'] == 'createRelative'){
		$passport->create_relatives();
	}elseif($_GET['data_type'] == 'getHabitsInfo'){
		$passport->getHabitsInfo(1);
	}elseif($_GET['data_type'] == 'getVaccines'){
		$passport->getVaccines(1);
	}elseif($_GET['data_type'] == 'deleteVaccines'){
		$passport->delete_vaccines();
	}elseif($_GET['data_type'] == 'getRelative'){
		$passport->getRelative($ajax);
	}elseif($_GET['data_type'] == 'deleteRelative'){
		$passport->delete_relatives();
	}elseif($_GET['data_type'] == 'getMedications'){
		$passport->getMedications(1);
	}elseif($_GET['data_type'] == 'deleteMedications'){
		$passport->delete_medications();
	}elseif($_GET['data_type'] == 'getDiagnostics'){
		$passport->getDiagnostics(1);
	}elseif($_GET['data_type'] == 'createDiagnostics'){
		$passport->create_diagnostics();
	}elseif($_GET['data_type'] == 'deleteDiagnostics'){
		$passport->delete_diagnostics();
	}elseif($_GET['data_type'] == 'getPassport'){
		$passport->display_medical_passport();
	}
}

?>
