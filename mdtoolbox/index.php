<html>
 <head>
  <title>E-Prescribe</title>
 </head>
 <body>
 <?php 
 require("../environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
 
 // Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
 ////////////////////////////////////////////////////////THIS HANDLES USER DATA/DOCTOR DATA///////////////////////////////////////////////////////////////////////////////////////////////////////////// 
  $member = $_GET['IdUsu'];
  $doc = $_GET['medid'];
  $cname = $_GET['cname'];
  $pname = $_GET['pname'];
  $address1 = $_GET['address1'];
  $address2 = $_GET['address2'];
  $city = $_GET['city'];
  $state = $_GET['state'];
  $zip = $_GET['zip'];
  $phone = $_GET['phone'];
  $fax = $_GET['fax'];
  $loc_id = $_GET['id'];
  $practice = $_GET['practice'];
  $practice_id = $_GET['practiceid'];
  
  
$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $member, PDO::PARAM_INT);
$result->execute();
  
$row = $result->fetch(PDO::FETCH_ASSOC);
  
$result2 = $con->prepare("SELECT * FROM doctors where id=?");
$result2->bindValue(1, $doc, PDO::PARAM_INT);
$result2->execute();
  
$row2 = $result2->fetch(PDO::FETCH_ASSOC);
  
  
  
  
  
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//classes and SOAP Client were auto generated using WSDL2PHP, an open source project: http://wsdl2php.sf.net

	// CHANGE the URL to be your assigned testing server or the live server once your app is ready to go live

	require_once 'rx.php';
	$client = new rx("https://test.mdtoolboxrx.net/rxws/rx.asmx?wsdl");
	
	$updateParams = new UpdateDataForScreens();
	
	//Setup account credentials (account/sub-account/authkeyh)
	$AccountObj = new Account();
	$updateParams->AccountObj = $AccountObj;
	
	// CHANGE --- Pass in your assigned account id and auth key for authentication
	$AccountObj->AccountId = 5091;
	$AccountObj->AccountAuthKey = "44CV-CR44-PT31-T41-G1Z4";	
	
	// pass in the practice id and practice name of your practice using your system
	// this can be any id/name that you have - if MDToolbox doesnt have this practice on file for your account
	// it will automatically upload it and start a new subaccount for your account
	$AccountObj->PracticeId=$practice_id;
	$AccountObj->PracticeName = $practice;
	
	//setup/update location(s) - this is where the prescriber(s) prescribe from (will be sent to the pharmacy)
	$LocationObj = new Location();
	$updateParams->Locations = array($LocationObj);
	
	$LocationObj->ID = $loc_id;
	$LocationObj->Addr1 = $address1;
	$LocationObj->Addr2 = $address2;
	$LocationObj->City = $city;
	$LocationObj->State = $state;
	$LocationObj->Zip=$zip;
	$LocationObj->ClinicName=$cname;
	$LocationObj->Phone=$phone;
	$LocationObj->Fax=$fax;
	$LocationObj->Current=true;
	
	/*$LocationObj->ID = $loc_id;
	$LocationObj->Addr1 = $address1;
	$LocationObj->Addr2 = $address2;
	$LocationObj->City = $city;
	$LocationObj->State = $state;
	$LocationObj->Zip=$zip;
	$LocationObj->ClinicName=$cname;
	$LocationObj->Phone=$phone;
	$LocationObj->Fax=$fax;
	$LocationObj->Current=true;*/
	
	//setup/update prescriber(s) - can pass all prescribers at the practice or current prescriber
	$PrescriberObj = new Prescriber();
	$updateParams->Prescribers = array($PrescriberObj);
	
	$PrescriberObj->ID=$row2['id'];
	$PrescriberObj->FirstName=$row2['Name'];
	$PrescriberObj->LastName=$row2['Surname'];
	$PrescriberObj->DEA=$row2['dea'];
	$PrescriberObj->NPI=$row2['npi'];
	$PrescriberObj->Email=$row2['IdMEDEmail'];
	$PrescriberObj->UserName=$row2['IdMEDEmail'];
	$PrescriberObj->UserId=$row2['id'];
	$PrescriberObj->Current=true;
	
	//patient to create/update - the patient the user wants to write prescriptions for
	$updateParams->PatientObj = new Patient();
	$updateParams->PatientObj->ID=$row['Identif'];
	$updateParams->PatientObj->FirstName=$row['Name'];
	$updateParams->PatientObj->LastName=$row['Surname'];
	
	if($row['Sexo'] == 1){
	$sex = 'M';
	}else{
	$sex = 'F';
	}
	
	$updateParams->PatientObj->Gender=$sex;
	
	if($row['FNac'] != '' && $row['FNac'] != '//'){
	$dob = $row['FNac'];
	}else{
	$dob = '1980-01-01';
	}
	
	$updateParams->PatientObj->DOB=$dob;
	
	//other patient info - optionally upload allergies, conditions, etc.
	$AllergyRecord = new AllergyRecord();
	$updateParams->PatientAllergies = array($AllergyRecord);
	
	$AllergyRecord->AllergyId=45;
	$AllergyRecord->AllergyName="Peanuts";
	$AllergyRecord->SeverityLevel=1;
		
	//if default values aren't passed in for certain fields
	// then Rx web service tries to convert empty strings into boolean, date/time, and int values.
	// You could provide permanent defaults in rx.php
	$updateParams->CheckPatEligibility = false;
	$updateParams->EligCheckEncounterDate ="2012-10-19";
	$AccountObj->UserPermissionLevel=0;	
	
	//call web service
	$result = $client->UpdateDataForScreens($updateParams);
	
	if($result == false){
	echo "<center>The information you entered for your account is incorrect.  Please double check the address of the clinic you are prescribing from and try again.</center>";
	}else{
	
	// CHANGE the URL to be your assigned testing server or the live server once your app is ready to go live
	echo "
	 <iframe src='https://test.mdtoolboxrx.net/rxtest/access1.aspx?code=" . 
	 $result->UpdateDataForScreensResult . 
	 "&aid=" .$AccountObj->AccountId .  
	 "&sid=" . $AccountObj->PracticeId . 
	 "&user=" . $PrescriberObj->UserName . 
	 "&did=" . $PrescriberObj->ID . 
	 "&lid=" . $LocationObj->ID . 
	 "&page=RX&pid=" . $updateParams->PatientObj->ID .
	 "&header=2&menu=1&theme=91Blue&post=".urlencode('http://beta.health2.me/callbackPrescribe.php')."' style='width:100%;height:600px;'></iframe>";
	 }
 ?> 

 </body>
</html>