<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');

        $soapClient = new SoapClient("https://test.mdtoolboxrx.net/rxws/rx.asmx?wsdl"); 
    
        // Prepare SoapHeader parameters 
        /*$sh_param = array( 
                    'Username'    =>    '5091', 
                    'Password'    =>    '44CV-CR44-PT31-T41-G1Z4'); */
		  $sh_param = array( 
                    'AccountId'    =>    '5091', 
                    'AccountAuthKey'    =>    '44CV-CR44-PT31-T41-G1Z4',
					'PracticeId'    =>    '1234',
					'PracticeName'    =>    'My Practice',
					'UserId'    =>    '1234',
					'UserName'    =>    'Kyle Austin',
					'UserPermLevel'    =>    0,
					'UserFirstName'    =>    'Kyle',
					'UserLastName'    =>    'Austin',
					'UserPosition'    =>    'Nurse'
					);
        $headers = new SoapHeader('http://mdtoolboxrx.com/WebServiceTest', 'Account', $sh_param); 
    
        // Prepare Soap Client 
        $soapClient->__setSoapHeaders(array($headers)); 
    
        // Setup the RemoteFunction parameters 
        $ap_param = array( 
                    'Prescribers'     =>    '5091',
					'Locations'     =>    '44CV-CR44-PT31-T41-G1Z4',
					'PatientObj'     =>    '5091',
					'PatientMedications'     =>    '5091',
					'PatientAllergies'     =>    '5091',
					'PatientConditions'     =>    '5091',
					'PatientCurrentVitals'     =>    '5091',
					'CheckPatEligibility'     =>    true,
					'EligCheckEncounterDate'     =>    '5091',
					'EligCheckPrescriber'     =>    '5091',
					'AccountObj'     =>    '5091'
					); 
                    
        // Call RemoteFunction () 
        $error = 0; 
        try { 
            $info = $soapClient->__call("UpdateDataForScreens", array($ap_param)); 
        } catch (SoapFault $fault) { 
            $error = 1; 
            print(" 
            alert('Sorry, blah returned the following ERROR: ".$fault->faultcode."-".$fault->faultstring.". We will now take you back to our home page.'); 
            window.location = 'main.php'; 
            "); 
        } 
        
        if ($error == 0) {        
            $auth_num = $info->RemoteFunctionResult; 
            
            if ($auth_num < 0) { 
                

                // Setup the OtherRemoteFunction() parameters 
                $at_param = array( 
                            'amount'        => $irow['total_price'], 
                            'description'    => $description); 
            
                // Call OtherRemoteFunction() 
                $trans = $soapClient->__call("OtherRemoteFunction", array($at_param)); 
                $trans_result = $trans->OtherRemoteFunctionResult; 
            
                } else { 
                    // Record the transaction error in the database 
                
                // Kill the link to Soap 
                unset($soapClient); 
            } 
        } 
       
 
    
?>