<?php

require("environment_detailForLogin.php");
require("push_server.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
include('probeLogicClass.php');
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

for($i = 1; $i <= 5; $i++)
{
    if(isset($_GET['question'.$i]))
    {
        $p_response = $con->prepare("INSERT INTO proberesponse SET probeID = ?, response = ?, question = ?, responseTime = NOW()");
        $p_response->bindValue(1, $_GET['probeID'], PDO::PARAM_INT);
        $p_response->bindValue(2, $_GET['question'.$i], PDO::PARAM_INT);
        $p_response->bindValue(3, $i, PDO::PARAM_INT);
        $p_response->execute();
    }
}


 ///////////////////////PROBE ALERT LOGIC////////////////////////////////////////
            $probe = $con->prepare("SELECT * FROM probe WHERE probeID = ?");
            $probe->bindValue(1, $_GET['probeID'], PDO::PARAM_INT);
            $probe->execute();
            $probe_row = $probe->fetch(PDO::FETCH_ASSOC);
            
            $probe_rules = $con->prepare("SELECT * FROM probe_alerts WHERE probe = ?");
			$probe_rules->bindValue(1, $_GET['probeID'], PDO::PARAM_INT);
			$probe_result = $probe_rules->execute();
			$user_id = $probe_row['patientID'];
			$med_id = $probe_row['doctorID'];
			
            // send a push notification to the doctor of the new message
            //$app_key = 'd869a07d8f17a76448ed';
            //$app_secret = '92f67fb5b104260bbc02';
            //$app_id = '51379';
            //$pusher = new Pusher($app_key, $app_secret, $app_id);
            $push = new Push();

            $pat = $con->prepare("SELECT Name,Surname FROM usuarios WHERE Identif = ?");
            $pat->bindValue(1, $probe_row['patientID'], PDO::PARAM_INT);
            $pat->execute();
            $pat_row = $pat->fetch(PDO::FETCH_ASSOC);

            $push->send($probe_row['doctorID'], 'notification', 'New Probe Response from '.$pat_row['Name'].' '.$pat_row['Surname'].' ');
            // send a push notification to the doctor of the new message



			while($probe_rules_row = $probe_rules->fetch(PDO::FETCH_ASSOC)){
			
				$question = $probe_rules_row['question'];
				$start_value = $probe_rules_row['start_value'];
				$expected_end_upper_date = $probe_rules_row['exp_day_1'];
				$expected_end_lower_date = $probe_rules_row['exp_day_2'];
				$probe_interval = $probe_row['probeInterval'];
				$expected_end_value = $probe_rules_row['exp_value'];
				$deviation_up = 1 + ($probe_rules_row['tolerance'] / 100);
				$deviation_down = 1 - ($probe_rules_row['tolerance'] / 100);
				$first_dev_alert = 1;
				$sub_dev_alert = 2;
				$lower_bound = 1;
				$lower_bound_repeat = 1;
				$upper_bound = 9;
				$upper_bound_repeat = 1;
				
				$probe_response_array = array();
                $probe_date_array = array();
				$probe_response = $con->prepare("SELECT * FROM proberesponse WHERE probeID = ? && question = ?");
				$probe_response->bindValue(1, $probe_row['probeID'], PDO::PARAM_INT);
				$probe_response->bindValue(2, $question, PDO::PARAM_INT);
				$probe_response_result = $probe_response->execute();
				
				$probe_response_holder = 'HOLDER';
				while($probe_response_row = $probe_response->fetch(PDO::FETCH_ASSOC)){
					$probe_response_array[] = $probe_response_row['response'];
                    $probe_date_array[] = $probe_response_row['responseTime'];
					$probe_response_holder = $probe_response_holder.$probe_response_row['response'];
				}
				
				$probe_logic = new probeLogicClass(
				$question,
				$start_value, 
				$expected_end_upper_date, 
				$expected_end_lower_date, 
				$probe_interval,  
				$expected_end_value, 
				$deviation_up, 
				$deviation_down, 
				$first_dev_alert, 
				$sub_dev_alert, 
				$lower_bound, 
				$lower_bound_repeat, 
				$upper_bound, 
				$upper_bound_repeat,
				$probe_response_array,
                $probe_date_array,
 				$user_id,
				$med_id,
                $_GET['probeID']);
				
				//$test = $con->prepare("INSERT INTO test_table SET text = ?");
				//$test->bindValue(1, ('question:'.$question.'startvalue:'.$start_value.'upperendincvalue:'.$expected_end_upper_date.'lowerendincvalue:'.$expected_end_lower_date.'probeinterval:'.$probe_interval.'expectedendvalue:'.$expected_end_value.'deviationup:'.$deviation_up.'deviationdown:'.$deviation_down.'ALERTCOUNT:'.$probe_logic->alert_holder.'RESPONSES:'.$probe_response_holder), PDO::PARAM_STR);
				//$result5 = $test->execute();
				
			}
            
            ///////////////////////////////////////////////////////////////////////////////

?>

<!DOCTYPE html>
<html lang="en"  class="body-error"><head>
    <meta charset="utf-8">
    <title>health2.me</title>
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    
    <link rel="apple-touch-icon" href="images/icon.png"/>
    
	<link rel="stylesheet" href="css/icon/font-awesome.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">



    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/icons/favicon.ico">
    
    <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37863944-1']);
  _gaq.push(['_setDomainName', 'health2.me']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
    
    
  </head>

  <body>


	<!--Header Start-->
	<div class="header" >
    		
           <a href="index.html" class="logo"><h1>I</h1></a>
           
           <div class="pull-right">
           
         
          
          </div>
    </div>
    <!--Header END-->
    

     
    <div class="error-bg" id="main_menu">
        <div class="error-s">
        <!--<div class="error-number">Health2me</div>-->
            <div style="width: 500px; height: 200px; border-radius: 15px; padding: 25px; background-color: #FFF;">
                <div style="text-align: center; font-size: 24px; color: #54BC02; height: 30px;">Health Information Updated</div>
                <div style="text-align: left; font-size: 16px; color: #777; margin-top: 10px; height: 50px; text-align: center;">
                    Thank you for updating your health information!
                </div>
                
                <div style="width: 40%; height: 40px; margin: auto;">
                    <button onclick="window.location='SignInUSER.php';" style="background-color: #54BC02; width: 100%; height: 40px; border-radius: 5px; color: #FFF; outline: 0px; border: 0px solid #FFF; font-size: 18px;">Sign In</button>
                </div>
            </div>
        </div>
    </div>

    
 


  </body>
</html>
