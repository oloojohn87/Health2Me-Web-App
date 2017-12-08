<?php
session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$MEDID = $_SESSION['MEDID'];
$UserID = $_SESSION['UserID'];
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];
if ($Acceso != '23432')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}

					// Connect to server and select databse.
//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$result = mysql_query("SELECT * FROM usuarios where Identif='$UserID'");
$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);
$success ='NO';
if($count==1){
	$success ='SI';
	/*
    $MedID = $row['id'];
	$MedUserEmail= $row['IdMEDEmail'];
	$MedUserName = $row['Name'];
	$MedUserSurname = $row['Surname'];
	$MedUserLogo = $row['ImageLogo'];
	$IdMedFIXED = $row['IdMEDFIXED'];
	$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
*/
    $UserID = $row['Identif'];
	$UserEmail= $row['email'];
    $UserName = $row['Name'];
    $UserSurname = $row['Surname'];
    $UserPhone = $row['telefono'];
    //$UserLogo = $row['ImageLogo'];
    $IdUsFIXED = $row['IdUsFIXED'];
    $IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
    $privilege=1;

    $IdDoctor = $row['IdInvite'];
    $resultD = mysql_query("SELECT * FROM doctors where id='$IdDoctor'");
	$rowD = mysql_fetch_array($resultD);
    $NameDoctor = $rowD['Name'];
    $SurnameDoctor = $rowD['Surname'];
	$DoctorEmail = $rowD['IdMEDEmail'];
    //$MedUserRole = $row['Role'];
	//if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' ';
    
       

    
    
}
else
{
echo "USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}


//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = mysql_query("SELECT * FROM lifepin");

?>
<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="css/datepicker.css" >
    <link rel="stylesheet" href="css/colorpicker.css">
    <link rel="stylesheet" href="css/glisse.css?1.css">
    <link rel="stylesheet" href="css/jquery.jgrowl.css">
    <link rel="stylesheet" href="js/elfinder/css/elfinder.css" media="screen" />
    <link rel="stylesheet" href="css/jquery.tagsinput.css" />
    <link rel="stylesheet" href="css/demo_table.css" >
    <link rel="stylesheet" href="css/jquery.jscrollpane.css" >
    <link rel="stylesheet" href="css/validationEngine.jquery.css">
    <link rel="stylesheet" href="css/jquery.stepy.css" />
    <link rel="stylesheet" type="text/css" href=href="css/googleAPIFamilyCabin.css">
      <script type="text/javascript" src="js/42b6r0yr5470"></script>
	<link rel="stylesheet" href="css/icon/font-awesome.css">
 <!--   <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
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

  <body style="background: #F9F9F9;">
      
      
      
      <!-- MODAL VIEW TO FIND DOCTOR -->
    <div id="find_doctor_modal" title="Find Doctor" style="display:none; text-align:center; padding:20px;">
        <div id="Talk_Section_1" style="display: block;">
            <!--<input type="text" style="width: 90%; margin-top: 15px; margin-bottom: 15px; height: 20px; color: #CACACA; padding: 5px;" placeholder="Search for a doctor..." value="" />-->
            <style>
                .recent_doctor_button{
                        padding: 3px; 
                        width: 80%; 
                        margin: auto; 
                        color: #22aeff; 
                        background-color: #FBFBFB; 
                        height: 25px; 
                        border: 1px solid #CFCFCF;
                        outline: 0px;
                    }
                .recent_doctor_button_selected{
                        border: 1px solid #22aeff;
                        background-color: #22aeff; 
                        color: #FFF;
                        padding: 3px; 
                        width: 80%; 
                        margin: auto; 
                        height: 25px;
                        outline: 0px;
                    }
                .find_doctor_button
                {
                    width: 100px;
                    height: 30px;
                    border-radius: 7px;
                    font-size: 14px;
                    color: #FFFFFF;
                    border: 0px solid #FFF;
                    float: right;
                    margin-top: 3px;
                    margin-left: 10px;
                    outline: 0px;
                }
                .square_blue_button
                {
                    width: 110px;
                    height: 110px;
                    border-radius: 7px;
                    font-size: 14px;
                    color: #FFFFFF;
                    background-color: #22aeff;
                    border: 0px solid #FFF;
                    outline: 0px;
                    margin-top: 55px;
                    margin-left: 15px;
                    margin-right: 15px;
                    
                }
                .step_circle
                {
                    background-color: #909090;
                    padding-top: 5px;
                    padding-left: 2px;
                    width: 28px;
                    height: 25px;
                    border: 1px solid #909090;
                    border-radius: 15px;
                    color: #FFFFFF;
                    font-weight: bold;
                    float: left;
                    font-size: 12px;
                    <!--margin-right: 10px;-->
                }
                .step_bar
                {
                    background-color: #909090;
                    margin-top: 14px;
                    width: 10px;
                    height: 3px;
                    border: 1px solid #909090;
                    float: left;
                }
                .lit
                {
                    background-color: #52D859;
                    border: 1px solid #52D859;
                }
            </style>
            <div style="width: 100%; height: 35px; margin-top: -5px; margin-left: -5px;">
                <div id="step_circle_1" class="step_circle lit">1</div>
                    <div id="step_bar_1" class="step_bar"></div>
                <div id="step_circle_2" class="step_circle">2</div>
                    <div id="step_bar_2" class="step_bar"></div>
                <div id="step_circle_3" class="step_circle">3</div>
                    <div id="step_bar_3" class="step_bar"></div>
                <div id="step_circle_4" class="step_circle">4</div>
                    <div id="step_bar_4" class="step_bar"></div>
                <div id="step_circle_5" class="step_circle">5</div>
                    <div id="step_bar_5" class="step_bar"></div>
                <div id="step_circle_6" class="step_circle"><i class="icon-ok" style="font-size: 20px;"></i></div>
            </div>
            <div id="find_doctor_container" style="width: 100%; margin-top: 10px; height: 250px;">
                <div stlye="width: 100%; height: 250px;" id="find_doctor_main">
                    <button id="find_doctor_now_button" class="square_blue_button">
                        <div style="margin-bottom: -8px;"><i class="icon-bolt" style="font-size: 40px;"></i></div>
                        <br/>Call Now
                    </button>
                    <button id="find_doctor_my_doctors_button" class="square_blue_button">
                        <div style="margin-bottom: -8px;"><i class="icon-user-md" style="font-size: 40px;"></i></div>
                        <br/>My Doctors
                    </button>
                    <button id="find_doctor_appointment_button" class="square_blue_button">
                        <div style="margin-bottom: -8px;"><i class="icon-calendar" style="font-size: 40px;"></i></div>
                        <br/>Appointment
                    </button>
                </div>
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_appointment_1">
                    <div style="width: 100%; height: 140px;">
                        <p>Which area will you be calling from?</p>
                        <div class="formRow" style="margin-left: 50px;">
                            <label>Country: </label>
                            <div class="formRight">
                                <select id="country" name ="country"></select>
                            </div>
                        </div>
                        <div class="formRow" style="margin-left: 50px;">
                            <label>Region: </label>
                            <div class="formRight">
                                <select name ="state" id ="state"></select>
                            </div>
                        </div>
                    </div>
                    <div style="width: 90%; margin-left: 10%; height: 50px; margin-top: 7px;">
                        <p style="text-align: left; float: left;">Video or phone consultation?</p>
                        
                        <div style="width: 100px; height: 30px; border-radius: 3px; background-color: #535353; float: left; margin-left:80px; margin-top: -6px;">
                            <button style="width: 50px; height: 30px; border-top-left-radius: 3px; border-bottom-left-radius: 3px; background-color: #22aeff; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="find_doctor_video_button">
                                <i class="icon-facetime-video"></i>
                            </button>
                            <button style="width: 50px; height: 30px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; background-color:  #535353; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="find_doctor_phone_button">
                                <i class="icon-phone"></i>
                            </button>
                        </div>
                    </div>
                    
                    <script type= "text/javascript" src = "js/countries.js"></script>
                    <script language="javascript">
                        populateCountries("country", "state");
                    </script>
                </div>
            </div>
            <div style="width: 100%; height: 40px; margin-top: 10px;">
                <button id="find_doctor_cancel_button" class="find_doctor_button" style="background-color: #D84840; float:left; margin-left: 0px;">Cancel</button>
                <button id="find_doctor_next_button" class="find_doctor_button" style="background-color: #52D859;">Next</button>
                <button id="find_doctor_previous_button" class="find_doctor_button" style="background-color: #22aeff;">Previous</button>
            </div>
            <!--<div id="recent_doctors_section" style="display: block;">
                
                <span style="font-size: 16px; color: #555">Your Recent Doctors</span>
                <div id="recent_doctors" style="margin-bottom: 10px;">
                    <?php 
                        /*$result = mysql_query("SELECT most_recent_doc FROM usuarios where Identif='$UserID'");
                        $count=mysql_num_rows($result);
                        $row = mysql_fetch_array($result);
                        $str = $row['most_recent_doc'];
                        $str = str_replace(array("[", "]"), "", $str);
                        $ids = explode(",", $str);
                
                        date_default_timezone_set ("GMT");
                        $date = new DateTime('now');
                        for($i = 0; $i < count($ids); $i++)
                        {
                            $doc_result = mysql_query("SELECT Name,Surname,phone FROM doctors WHERE id=".$ids[$i]." AND telemed=1 AND in_consultation=0");


                            if(mysql_num_rows($doc_result) > 0)
                            {
                                $doc_row = mysql_fetch_array($doc_result);
                                $result2 = mysql_query("SELECT * FROM timeslots WHERE doc_id=".$ids[$i]);
                                $found = false;
                            
                                while(($row2 = mysql_fetch_assoc($result2)) && !$found)
                                {
                                    $start = new DateTime($row2['week'].' '.$row2['start_time']);
                                    $end = new DateTime($row2['week'].' '.$row2['end_time']);
                                    $date_interval = new DateInterval('P'.$row2['week_day'].'D');
                                    $time_interval = new DateInterval('PT'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 8, 2)).'H'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 5, 2)).'M');
                                    if(substr($row2['timezone'], 0 , 1) != '-')
                                    {
                                        $time_interval->invert = 1;
                                    }
                                    $start->add($date_interval);
                                    $end->add($date_interval);
                                    $start->add($time_interval);
                                    $end->add($time_interval);
                                    if($start <= $date && $end >= $date)
                                    {
                                        // doctor is available
                                        $found = true;
                                        break;
                                    }
                                    
                                }
                                if($found)
                                {
                                    echo '<button id="'.$ids[$i].'_'.$doc_row['phone'].'_'.$doc_row['Name'].'_'.$doc_row['Surname'].'" class="recent_doctor_button" style="';
                                    if($i == 0)
                                    {
                                        echo 'border-top-left-radius: 10px; border-top-right-radius: 10px; ';
                                    }
                                    if($i == (count($ids) - 1))
                                    {
                                        echo 'border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; ';
                                    }
                                    echo '">Doctor '.$doc_row['Name'].' '.$doc_row['Surname'].'</button>';
                                    
                                    
                                    
                                }
                            }
                        }*/
                    ?>
                </div>
                
                <p style="text-align: center">Or</p>
                <br/>
            </div>
            <div style="width: 100%; height: 40px; margin-left: 15px;">
                <label style="float: left;">Find me a(n) </label>
                <select style="float: left; width: 72%; margin-top: -5px; margin-left: 20px;" name="speciality" id="speciality">
                    <option value="Allergy and Immunology">Allergist / Immunologist</option>
                    <option value="Anaesthetics">Aesthetician</option>
                    <option value="Cardiology">Cardiologist</option>
                    <option value="Cardiothoracic Surgery">Cardiothoracic Surgeon</option>
                    <option value="Child & Adolescent Psychiatry">Child & Adolescent Psychiatrist</option>
                    <option value="Clinical Neurophysiology">Clinical Neurophysiologist</option>
                    <option value="Dermato-Venereology">Dermato-Venereologist</option>
                    <option value="Dermatology">Dermatologist</option>
                    <option value="-Emergency Medicine">Emergency Medicine Specialist</option>
                    <option value="Endocrinology">Endocrinologist</option>
                    <option value="Gastroenterology">Gastroenterologist</option>
                    <option value="General Practice" selected>General Practice Doctor</option>
                    <option value="General Surgery">General Surgeon</option>
                    <option value="Geriatrics">Geriatrician</option>
                    <option value="Gynaecology and Obstetrics">Gynaecologist / Obstetrician</option>
                    <option value="Health Informatics">Health Informatics Specialist</option>
                    <option value="Infectious Diseases">Infectious Disease Specialist</option>
                    <option value="Internal Medicine">Internal Medicine Specialist</option>
                    <option value="Interventional Radiology">Interventional Radiologist</option>
                    <option value="Microbiology">Microbiologist</option>
                    <option value="Neonatology">Neonatologist</option>
                    <option value="Nephrology">Nephrologist</option>
                    <option value="Neurology">Neurologist</option>
                    <option value="Neuroradiology">Neuroradiologist</option>
                    <option value="Neurosurgery">Neurosurgeon</option>
                    <option value="Nuclear Medicine">Nuclear Medicine Specialist</option>
                    <option value="Occupational Medicine">Occupational Medicine Specialist</option>
                    <option value="Oncology">Oncologist</option>
                    <option value="Ophthalmology">Ophthalmologist</option>
                    <option value="Oral and Maxillofacial Surgery">Oral and Maxillofacial Surgeon</option>
                    <option value="Orthopaedics">Orthopedician</option>
                    <option value="Otorhinolaryngology">Otorhinolaryngologist</option>
                    <option value="Paediatric Cardiology">Paediatric Cardiologist</option>
                    <option value="Paediatric Surgery">Paediatric Surgeon</option>
                    <option value="Paediatrics">Paediatrician</option>
                    <option value="Pathology">Pathologist</option>
                    <option value="Physical Medicine and Rehabilitation">Physical Medicine and Rehabilitation Specialist</option>
                    <option value="Plastic, Reconstructive and Aesthetic Surgery">Plastic, Reconstructive and Aesthetic Surgeon</option>
                    <option value="Pneumology">Pulmonologist</option>
                    <option value="Psychiatry">Psychiatrist</option>
                    <option value="Public Health">Public Health Specialist</option>
                    <option value="Radiology">Radiologist</option>
                    <option value="Radiotherapy">Radiotherapist</option>
                    <option value="Stomatology">Stomatologist</option>
                    <option value="Vascular Medicine">Vascular Medicine Specialist</option>
                    <option value="Vascular Surgery">Vascular Surgeon</option>
                    <option value="Urology">Urologist</option>
                </select>
            </div>
            <button style="width: 200px; heightL 30px; background-color: #22aeff; color: #FFF; border: 0px solid #FFF; margin: auto; margin-top: 15px; border-radius: 7px; outline: 0px;" id="find_doctor_button">Next</button>
-->
        </div>
        <div id="Talk_Section_2" style="display: none;">
            <button style="width: 200px; heightL 30px; background-color: #22aeff; color: #FFF; border: 0px solid #FFF; margin: auto; margin-top: 15px; margin-left: 20px; border-radius: 7px; outline: 0px; float: left;" id="video_call_button">Video Call</button>
            <button style="width: 200px; heightL 30px; background-color: #22aeff; color: #FFF; border: 0px solid #FFF; margin: auto; margin-top: 15px; margin-right: 20px; border-radius: 7px; outline: 0px; float: right;" id="phone_call_button">Phone Call</button>
           
            
        </div>
        <div id="Talk_Section_3" style="display: none;">
            <br/>
            <p>No doctors are available at this time. Please try again later.</p>
           
            
        </div>
        <div id="Talk_Section_4" style="display: none;">
            <br/>
            <p>We are now calling your doctor, please wait...</p>
           
            
        </div>
    </div>
    <!-- END MODAL VIEW TO FIND DOCTOR -->

<input type="hidden" id="NombreEnt" value="<?php echo $NombreEnt; ?>">
<input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?>">
<input type="hidden" id="UserHidden">

	<!--Header Start-->
	<div class="header" >
     	<input type="hidden" id="USERID" Value="<?php echo $UserID; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">
        <input type="hidden" id="USERNAME" Value="<?php echo $UserName; ?>">	
        <input type="hidden" id="USERSURNAME" Value="<?php echo $UserSurname; ?>">	
        <input type="hidden" id="USERPHONE" Value="<?php echo $UserPhone; ?>">	
  		
           <a href="index.html" class="logo"><h1>Health2me</h1></a>
           
           <div class="pull-right">
           
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <span class="name-user"><strong>Welcome</strong> <?php echo $UserName.' '.$UserSurname; ?></span> 
             <?php 
             $hash = md5( strtolower( trim( $UserEmail ) ) );
             $avat = 'identicon.php?size=29&hash='.$hash;
			?>	
              <span class="avatar" style="background-color:WHITE;"><img src="<?php echo $avat; ?>" alt="" ></span> 
              <span class="caret"></span>
            </a>
            <div class="dropdown-menu" id="prof_dropdown">
            <div class="item_m"><span class="caret"></span></div>
            <ul class="clear_ul" >
			<li>
			 <?php if ($privilege==1)
					echo '<a href="dashboard.php">';
				   else if($privilege==2)
					echo '<a href="patients.php">';
			 ?>
			<i class="icon-globe"></i> Home</a></li>
              
              <li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="logout.php"><i class="icon-off"></i> Sign Out</a></li>
            </ul>
            </div>
          </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->
 
   	 <!--- VENTANA MODAL  This has been added to show individual message content which user click on the inbox messages ---> 
   	 <button id="message_modal" data-target="#header-message" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button> 
   	  <div id="header-message" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Message Details
         </div>
         <div class="modal-body">
         <div class="formRow" style=" margin-top:-10px; margin-bottom:10px;">
             <span id="ToDoctor" style="color:#2c93dd; font-weight:bold;">TO <?php echo 'Dr. '.$NameDoctor.' '.$SurnameDoctor; ?></span><input type="hidden" id="IdDoctor" value='<?php echo $IdDoctor; ?>'/>
         </div>
         <textarea  id="messagedetails" class="span message-text" style="height:200px;" name="message" rows="1"></textarea>
         
		 <form id="replymessage" class="new-message">
                   <div class="formRow">
                        <label>Subject: </label>
                        <div class="formRight">
                            <input type="text" id="subjectname_inbox" name="name"  class="span"> 
                        </div>
                   </div>
				   <div class="formRow">
						<label>Attachments: </label>
						<div id="attachreportdiv" class="formRight">
							<input type="button" class="btn btn-success" value="Attach Reports" id="attachreports">
						</div>
				   </div>
                   <div class="formRow">
                        <label>Message:</label>
                        <div class="formRight tooltip-top" style="height:120px;">
                            <textarea  id="messagecontent_inbox" class="span message-text" name="message" style="height:90px;" rows="1"></textarea>
                            
                            <div class="clear"></div>
                        </div>
                   </div>
            </form>
			<div id="attachments" style="display:none">
			
			
			
			</div>
		 </div>
         <input type="hidden" id="Idpin">
        <!-- <input type="hidden" id="docId" value="<?php echo $IdMed; ?>"/> -->
         <input type="hidden" id="userId" value="<?php echo $IdUsu; ?>" />
         <div class="modal-footer">
		     <input type="button" class="btn btn-info" value="Send messages" id="sendmessages_inbox">
             <input type="button" class="btn btn-success" value="Attach" id="Attach">	
	         <input type="button" class="btn btn-success" value="Reply" id="Reply">			 
	         <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseMessage">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 	
      
      <!--- VENTANA MODAL NUMERO 2 ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
   	  <div id="header-modal2" class="modal hide" style="display: none; height:470px;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4>Upload New Report</h4>
                 <input type="hidden" id="URLIma" value="zero"/>
         </div>
         
         <div class="modal-body" id="ContenidoModal2" style="height:320px;">
             <div  id="RepoThumb" style="width:70px; float:right; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);"></div>
           <div class="ContenDinamico2">
        
           <!-- <a href="#" class="btn btn-success" id="ParseReport" style="margin-top:10px; margin-bottom:10px;">Parse this report now.</a> -->

           		<form action="upload_fileUSER.php?queId=<?php echo $UserID ?>&from=0" method="post" enctype="multipart/form-data">
	           		<label for="file">Report:</label>
	           		<input type="file" class="btn btn-success" name="file" id="file" style="margin-right:20px;"><br>


            </div>  

         </div>
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <!--<a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Update Data</a>-->
             <input type="submit" class="btn btn-success" name="submit" value="Submit">
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal" >Close</a>
             
             	</form>

         </div>
       </div>
	  <!--- VENTANA MODAL NUMERO 2  ---> 
 
    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">
    
    	    
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
		
         <?php if ($privilege==1)
				 echo '<li><a href="UserDashboard.php" class="act_link">Dashboard</a></li>';
		 ?>
    	 <li><a href="patientdetailMED-newUSER.php?IdUsu=<?php echo $UserID;?>" >Medical Records</a></li>
         <li><a href="medicalConfiguration.php">Configuration</a></li>
         <li><a href="logout.php" style="color:yellow;">Sign Out</a></li>
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
     
     
     
     <!--CONTENT MAIN START-->
     <div class="content">
     <div class="grid" class="grid span4" style="width:1000px; height:552px; margin: 0 auto; margin-top:30px; padding-top:30px;">
 		     <div class="row-fluid" style="height:200px;">	            
                      <div style="margin:15px; padding-top:0px;">
                             <span class="label label-success" style="left:0px; margin-left:10px; margin-top:0px; font-size:30px;">User Dashboard</span>
                             <div class="clearfix" style="margin-bottom:20px;"></div>
                                          <?php
                                          $hash = md5( strtolower( trim( $UserEmail ) ) );
                                          $avat = 'identicon.php?size=75&hash='.$hash;
                                            ?>	
                             <img src="<?php echo $avat; ?>" style="float:right; margin-right:40px; font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; box-shadow: 3px 3px 15px #CACACA;"/>
                             <span id="NombreComp" style="font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; "><?php echo $UserName;?> <?php echo $UserSurname;?></span>
                             <span id="IdUsFIXED" style="font-size: 12px; color: #3D93E0; font-weight: normal; font-family: Arial, Helvetica, sans-serif; display: block; margin-left:20px;"><?php echo $IdUsFIXED;?></span>
                             <span id="IdUsFIXEDNAME" style="font-size: 14px; color: GREY; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $IdUsFIXEDNAME;?></span>
                             <span id="email" style="font-size: 14px; color: #5EB529; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $UserEmail;?></span>
                            
                            <span id="Nmp" style="float:right; font: bold 16px Arial, Helvetica, sans-serif; color: #54bc00; cursor: auto; margin-right:40px; "><span><i class="icon-refresh  icon-spin" style="color:#22aeff; margin-right:10px;"></i></span>You are connected to <?php echo 'Dr. '.$NameDoctor.' '.$SurnameDoctor; ?> </span>
                            
                            
                             <div class="row-fluid" style="height:250px;">	            
                             </div>	
                      </div>
             </div>
             <!--
             <img src="images/GooglePlay.png"  width="120" style="margin:30px; margin-top:0px;margin-bottom:20px;"/>
			 <img src="images/AppStore.png"  width="120" style="margin:30px; margin-top:0px;margin-bottom:20px; margin-left:0px;"/>
			 -->
			 <div style="display:none; visibility: hidden; margin-top:-50px; margin-right:30px; padding:10px; float:right; border:solid; height:80px; width:500px; border:solid 1px #cacaca; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;">
				 <span style="font-size: 16px; color:#22aeff;">This season the flu strain is more dangerous: </span><span style="font-size: 14px; color:grey;">  Please be aware of the flu shots to prevent major complications. If there is any doubt please <span style="font-weight:bold;">call our office</span> or go to our Health2Me profile page and <span style="font-weight:bold;">appoint a meeting</span>. </span>
				 
			 </div>
			 			 
<div id="modalContents" style="display:none; text-align:center; padding-top:20px;">
		<a id='ButtonSkype' href="skype:health2me?call" value="Telemedicine" class="btn" title="Telemedicine" style="text-align:center; padding:15px; width:40px; height:40px; color:#22aeff; margin-left:50px; float:left;"> 
         	<i class="icon-skype icon-2x" style="margin:0 auto; padding:0px; color:#22aeff;"></i>
         	<div style="width:100%; line-height:5px;"></div>
         	<span>Skype</span>
        </a>
		<a id='ButtonWeemo'  value="Telemedicine" class="btn" title="Telemedicine" style="text-align:center; padding:15px; width:40px; height:40px; color:#22aeff; margin-left:20px; float:left;"> 
         	<i class="icon-camera icon-2x" style="margin:0 auto; padding:0px; color:#22aeff;"></i>
         	<div style="width:100%; line-height:5px;"></div>
         	<span>Embed</span>
        </a>
</div>
	
	 <i id="LoadCanvas1" class="icon-refresh  icon-spin icon-2x" style="color:#22aeff; position:relative; left:220px; top:140px;"></i>
	 <i id="LoadCanvas2" class="icon-refresh  icon-spin icon-2x" style="color:#22aeff; position:relative; left:420px; top:140px;"></i>
 		 
			 <div style="width:100%;"></div>
             <div style="float:left; height:323px; width:900px; margin: 30px auto; margin-top:-10px; margin-left:30px; margin-right:30px; padding:10px; border:1px solid #cacaca; font-size:0px; ">             
                <!-- Left Column  ************************************************** -->
                <div style="float:left; margin-top:-40px; width:855px; border:1px solid #cacaca; padding:10px; margin:10px; border-radius: 3px;">
             		<div style="margin-left:-10px; margin-top:-10px; margin-bottom:-10px; float:left; width:60px; height:260px; border-radius:0px;  border-top-left-radius: 2px; border-bottom-left-radius: 2px; background-color:#54bc00;">
             			<div style="width:180px;height:50px; position:relative; top:120px; left:-50px; -ms-transform:rotate(270deg); -moz-transform:rotate(270deg); -webkit-transform:rotate(270deg);-o-transform:rotate(270deg); color:white; font-size:24px;">Health Passport</div>
	             	</div>
	             	
	             	<div style="float:left; width:; height:24px;  border:0px solid #cacaca; ">
		             	<div style="float:left; margin-left:20px; width:360px; height:22px;  border:0px solid #cacaca; text-align:center; font-size:18px; color: #54bc00;">Summary</div>
		             	<div style="float:left; margin-left:20px; width:360px; height:22px;  border:0px solid #cacaca; text-align:center; font-size:18px; color: #54bc00;">Reports</div>
	             	</div>	 	
            	   
	             	<div style="float:left; width:; height:;  border:0px solid #cacaca; ">
		             	<!--<div style="float:left; width:80px; height:200px; margin-left:20px; border:1px solid #cacaca; text-align:center; font-size:18px; color: #54bc00;"></div>-->
	             	    <canvas id="myCanvas" width="360" height="200" style="float:left; border:1px solid #cacaca; margin-left:20px; cursor: alias; "></canvas>
		             	<!--<div style="float:left; width:80px; height:200px; margin-left:0px; border:1px solid #cacaca; text-align:center; font-size:18px; color: #54bc00;"></div>-->
	
		             	<!--<div style="float:left; width:80px; height:200px; margin-left:20px; border:1px solid #cacaca; text-align:center; font-size:18px; color: #54bc00;"></div>-->
						<canvas id="myCanvas2" width="360" height="200" style="float:left; border:1px solid #cacaca; margin-left:20px; cursor: alias; "></canvas>
		             	<!--<div style="float:left; width:80px; height:200px; margin-left:0px; border:1px solid #cacaca; text-align:center; font-size:18px; color: #54bc00;"></div>	-->
	             	</div>	
				</div> 
				
                <!-- Right Column  ************************************************** -->
                <div style="float:left; margin-top:-40px; width:210px; height:200px; border:1px solid #cacaca; padding:10px; margin:10px; border-radius: 3px;">

             		<div style="margin-left:-10px; margin-top:-10px; margin-bottom:-10px; float:left; width:60px; height:222px; border-radius:0px;  border-top-left-radius: 2px; border-bottom-left-radius: 2px; background-color:#22aeff;"></div>
             			<div style="float:left; width:180px; height:50px; position:relative; top:80px; left:-110px; -ms-transform:rotate(270deg); -moz-transform:rotate(270deg); -webkit-transform:rotate(270deg);-o-transform:rotate(270deg); color:white; font-size:24px;">Doctor Network</div>
			 			<div style="float:left; margin-top:-40px; margin-left:20px; width:110px; height:190px;  ">
	             			<a id='SearchDirectory'  value="SearchDirectory" class="btn" title="Telemedicine" style="float:left; text-align:left; margin-top:10px; padding:10px; width:90px; height:25px; color:#22aeff; float:left;"> 
		                     	<img src="images/icons/SearchDirectory_svg.png" style="float:left; margin-top:-2px; width:30px; height:30px;">
		                     	<div style="float:left; line-height:25px; margin-left:6px;"><span>Search</span></div>
	                     	</a>
	             			<a id='Talk'  value="Talk" class="btn" title="Talk" style="float:left; text-align:left; margin-top:10px; padding:10px; width:90px; height:25px; color:#22aeff; float:left;"> 
		                     	<img src="images/icons/Talk_svg.png" style="float:left; margin-top:-2px; width:30px; height:30px;">
		                     	<div style="float:left; line-height:25px; margin-left:6px;"><span>Talk</span></div>
	                     	</a>
	             			<a id='Request'  value="Request" class="btn" title="Request" style="float:left; text-align:left; margin-top:10px; padding:10px; width:90px; height:25px; color:#22aeff; float:left;"> 
		                     	<img src="images/icons/Request_svg.png" style="float:left; margin-top:-2px; width:30px; height:30px;">
		                     	<div style="float:left; line-height:25px; margin-left:6px;"><span>Request</span></div>
	                     	</a>
			 			</div>
			 			<div style="float:left; margin-top:-40px; margin-left:10px; width:110px; height:190px;  ">
	             			<a id='Send2Doc'  value="Send2Doc" class="btn" title="Send2Doc" style="float:left; text-align:left; margin-top:10px; padding:10px; width:90px; height:25px; color:#22aeff; float:left;"> 
		                     	<img src="images/icons/Send_svg.png" style="float:left; margin-top:-2px; width:30px; height:30px;">
		                     	<div style="float:left; line-height:25px; margin-left:6px;"><span>Send</span></div>
	                     	</a>
	             			<a id='MessageD'  value="MessageD" class="btn" title="MessageD" style="float:left; text-align:left; margin-top:10px; padding:10px; width:90px; height:25px; color:#22aeff; float:left;"> 
		                     	<img src="images/icons/Message_svg.png" style="float:left; margin-top:-2px; width:30px; height:30px;">
		                     	<div style="float:left; line-height:25px; margin-left:6px;"><span>Message</span></div>
	                     	</a>
	             			<a id='SetUp'  value="SetUp" class="btn" title="SetUp" style="float:left; text-align:left; margin-top:10px; padding:10px; width:90px; height:25px; color:#22aeff; float:left;"> 
		                     	<img src="images/icons/Configuration_svg.png" style="float:left; margin-top:-2px; width:30px; height:30px;">
		                     	<div style="float:left; line-height:25px; margin-left:6px;"><span>SetUp</span></div>
	                     	</a>
			 			</div>
              			
	             	


				</div> 
                
             </div>     
             <div  style="display:none; float:left; border:1px solid #cacaca; width:900px; height:420px; margin: 30px auto; margin-top:30px; margin-left:30px; margin-right:30px; padding:10px; ">
                <span class="label label-info" id="EtiTML" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;">User&Doctor Communications Area</span>
                <ul id="myTab" class="nav nav-tabs tabs-main">
                <li class="active"><a href="#inbox" data-toggle="tab" id="newinbox">InBox</a></li>
                <li><a href="#outbox" data-toggle="tab" id="newoutbox">OutBox</a></li></ul>
                <div id="myTabContent" class="tab-content tabs-main-content padding-null">
                               
                <div class="tab-pane tab-overflow-main fade in active" id="inbox">
				<div class="message-list"><div class="clearfix" style="margin-bottom:40px;">
                <div class="action-message"><div class="btn-group">
               
                <button id="delete_message" class="btn b2"><i class="icon-trash padding-null"></i></button>
				<input type="button" style="margin-left:10px" class="btn b2" value="Create Message" id="compose_message">
              
             	</div></div>
				</div>
                    <div style="height:270px; overflow:auto; ">
                            <table class="table table-striped table-mod" id="datatable_3"></table>
                    </div>
                </div></div>
				
				<div class="tab-pane" id="outbox">
				<div class="message-list"><div class="clearfix" style="margin-bottom:40px;">
                <div class="action-message"><div class="btn-group">
                
                <button id="delete_message_outbox" class="btn b2"><i class="icon-trash padding-null"></i></button>
				</div></div>
				</div>
                   <div style="height:270px; overflow:auto; ">
                        <table class="table table-striped table-mod" id="datatable_4"></table> 
                   </div> 
                </div>
                </div>
				
				</div>

             </div>     


           
             </div> 
    </div>
     </div>


				 		<img id="Admin" src="images/icons/Admin_svg.png" style="visibility:hidden;" >
					    <img id="History" src="images/icons/history_svg.png" style="visibility:hidden;" >
					    <img id="Medication" src="images/icons/medication_svg.png" style="visibility:hidden;" >
					    <img id="Immuno" src="images/icons/immuno_svg.png" style="visibility:hidden;" >
					    <img id="Family" src="images/icons/family_svg.png" style="visibility:hidden;" >
					    <img id="Habits" src="images/icons/habits_svg.png" style="visibility:hidden;" >
					
					    <img id="R1" src="images/icons/picture_svg.png"  style="visibility:hidden;">
					    <img id="R2" src="images/icons/beaker_svg.png" style="visibility:hidden;">
					    <img id="R3" src="images/icons/user-md_svg.png"  style="visibility:hidden;">
					    <img id="R4" src="images/icons/inbox_svg.png"  style="visibility:hidden;">
					    <img id="R5" src="images/icons/circle-blank_svg.png" style="visibility:hidden;">
					    <img id="R6" src="images/icons/list-alt_svg.png"  style="visibility:hidden;">
					    <img id="R7" src="images/icons/film_svg.png"  style="visibility:hidden;">
					    <img id="R8" src="images/icons/user_svg.png" style="visibility:hidden;">
					    <img id="R9" src="images/icons/dollar_svg.png"  style="visibility:hidden;">
					    <img id="R10" src="images/icons/question-sign_svg.png"  style="visibility:hidden;">
					    <img id="R11" src="images/icons/list-alt_svg.png" style="visibility:hidden;">
					    <img id="R12" src="images/icons/beaker_svg.png"  style="visibility:hidden;">


     <!--CONTENT MAIN END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>

    <!-- Libraries for notifications -->
    <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<script src="realtime-notifications/pusher.min.js"></script>
	<script src="realtime-notifications/PusherNotifier.js"></script>
	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
    <script>
		$(function() {
	    var pusher = new Pusher('d869a07d8f17a76448ed');
	    var channel_name=$('#MEDID').val();
		var channel = pusher.subscribe(channel_name);
		var notifier=new PusherNotifier(channel);
		
	  });
    </script>
    <!-- Libraries for notifications -->



    <script src="TypeWatch/jquery.typewatch.js"></script>
    <script type="text/javascript" >
    
	var timeoutTime = 18000000;
	//var timeoutTime = 300000;  //5minutes
	var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


	var active_session_timer = 60000; //1minute
	var sessionTimer = setTimeout(inform_about_session, active_session_timer);

    var reportcheck = new Array();
   
	//This function is called at regular intervals and it updates ongoing_sessions lastseen time
	function inform_about_session()
	{
		$.ajax({
			url: '<?php echo $domain?>/ongoing_sessions.php?userid='+<?php echo $_SESSION['MEDID'] ?>,
			success: function(data){
			//alert('done');
			}
		});
		clearTimeout(sessionTimer);
		sessionTimer = setTimeout(inform_about_session, active_session_timer);
	}

	function ShowTimeOutWarning()
	{
		alert ('Session expired');
		var a=0;
		window.location = 'timeout.php';
	}
        
	// function launchTelemedicine()
    // {
		// $.ajax({
		// url: '<?php echo $domain?>/weemo_test.php?calleeID='+<?php echo $DoctorEmail?>,
			// success: function(data){
			//alert('done');
			// }
		// });
    // }	
    function LanzaAjax (DirURL)
		{
		var RecTipo = 'SIN MODIFICACIÓN';
	    $.ajax(
           {
           url: DirURL,
           dataType: "html",
           async: false,
           complete: function(){ //alert('Completed');
                    },
           success: function(data) {
                    if (typeof data == "string") {
                                RecTipo = data;
                                }
                     }
            });
		return RecTipo;
		} 	
	
	setInterval(function() {
    		$('#newinbox').trigger('click');
      }, 10000);

    function displaynotification(status,message){
  
  var gritterOptions = {
			   title: status,
			   text: message,
			   image:'images/Icono_H2M.png',
			   sticky: false,
			   time: '3000'
			  };
	$.gritter.add(gritterOptions);
	
  }

    function getUserData(UserId) {
 	var cadenaGUD = '<?php echo $domain;?>/GetUserData.php?UserId='+UserId;
    $.ajax(
           {
           url: cadenaGUD,
           dataType: "json",
           async: false,
           success: function(data)
           {
           //alert ('success');
           user = data.items;
           }
           });
    }

    function getMedCreator(UserId) {
 	var cadenaGUD = '<?php echo $domain;?>/GetMedCreator.php?UserId='+UserId;
    $.ajax(
           {
           url: cadenaGUD,
           dataType: "json",
           async: false,
           success: function(data)
           {
           //alert ('success');
           doctor = data.items;
           }
           });
    }

        
    $(document).ready(function() {
	
	$(window).load(function() {
		$('#LoadCanvas1').css('display','none');
		$('#LoadCanvas2').css('display','none');
		LoadDonuts();
	});
	
	function LoadDonuts(){
	//var timerId = setTimeout(function() { 

        var UserID = <?php echo $UserID;?>;
		
		var AdminData = 0;
		// Ajax call to retrieve a JSON Array **php return array** 
//		$.post("GetConnectedLight.php", {User: queMED, NReports: 3, Group: group}, function(data, status)
		var queUrl = 'GetSummaryData.php?User='+UserID;
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				//alert('Data Fetched');
				ConnData = data;
			}
		});
		
	  AdminData =	ConnData.AdminData;

	  // SUMMARY GRAPH
	  var canvas = document.getElementById('myCanvas');
      var context = canvas.getContext('2d');
      var x = canvas.width / 2;
      var y = canvas.height / 2;
      var radius = 75;
      var startAngle = 0 - (Math.PI/2);
      var counterClockwise = false;
	  
	  var ColSeg = new Array();
	  var SizSeg = new Array();
	  var ImgSeg = new Array();
	  var MaxValue = new Array();
	  var UIValue = new Array();
	  
	  ColSeg[1] = '#54bc00';
	  MaxValue[1] = 5;
	  UIValue[1] = 10;
	  if (AdminData > MaxValue[1]) {SizSeg[1] = UIValue[1]} else {SizSeg[1] = (AdminData * UIValue[1] / MaxValue[1])};
	  //alert (SizSeg[1]+' - '+MaxValue[1]+' - '+UIValue[1]+' - '+AdminData);
	  ImgSeg[1] = 'Admin';
	  ColSeg[2] = '#2c3e50';
	  SizSeg[2] = 40;
	  ImgSeg[2] = 'History';
	  ColSeg[3] = '#18bc9c';
	  SizSeg[3] = 15;
	  ImgSeg[3] = 'Medication';
	  ColSeg[4] = '#f39c12';
	  SizSeg[4] = 10;
	  ImgSeg[4] = 'Immuno';
	  ColSeg[5] = '#e74c3c';
	  SizSeg[5] = 15;
	  ImgSeg[5] = 'Family';
	  ColSeg[6] = '#3498db';
	  SizSeg[6] = 10;
	  ImgSeg[6] = 'Habits';


	  var SummaryData = 0;
	  
      context.lineWidth = 35;
	  startAngle = 0 - (Math.PI/2);
	  var lastAngle = 0;

		  var side = 'Left';
		  var orderside = 1;
		  var maxside = 3;
		  var XBoxSize = 70;
		  var YBoxSize = 20;
		  var XBox = 0;
		  var YBox = 0;
	      	    
	  var n = 1;
	  while (n < 7)
	  {
	  	  SummaryData = SummaryData + SizSeg[n];
	      context.beginPath();
	      ColorRGB = hexToRgb(ColSeg[n]);
	      // Opacity value reflects how recent data is
	      var Opac = Math.random();
	      var Opac = 1;
	      SegColor = 'rgba('+ColorRGB.r+','+ColorRGB.g+','+ColorRGB.b+','+Opac+')';
	      
	      // Draw the segments
 	      context.beginPath();
	      context.strokeStyle = SegColor;
	      context.lineCap = 'butt';
	      endAngle = startAngle + TranslateAngle(SizSeg[n],100);
	      midAngle = startAngle + (TranslateAngle(SizSeg[n]/2,100));
		  context.lineWidth = 35;
	      context.arc(x, y, radius, startAngle, endAngle, counterClockwise);
	      context.stroke();
 	      
	      // Draw icons into segments
	      var posx = x + (radius * Math.cos (midAngle)) - 10;
	      var posy = y + (radius * Math.sin (midAngle)) - 10;
		  var imageObj=document.getElementById(ImgSeg[n]);
		  if (SizSeg[n] > 4) context.drawImage(imageObj, posx, posy,20,20);
	     

				  //             Labels Section
				  side = 'Left';
				  orderside = n;				  
				  var divisor = y / (maxside+1);
				  
				  if (side='Left') {
				  	XBox = 10 ; 
				  	}
				  else 
				  	{
				  	XBox = ((x/2) - (radius/2) + 20);
				  }
				  YBox = divisor * orderside;
				  
			      // Virtual Box for the Label
				  
				  /*
			      context.beginPath();
			      context.fillStyle = '#cacaca';
			      context.fillRect(XBox,YBox,XBoxSize,YBoxSize);
			      context.stroke();
				  */
				  
				  // Label Text
				  
				  context.font = "10px Arial";
			      context.fillStyle = 'black';
				  context.fillText("Family",XBox,YBox+8);
			      context.fillText("History",XBox,YBox+8+10);
			      //context.stroke();
			     
			      
			      // Divisory Line
			      
			      context.beginPath();
				  context.lineWidth = 3;
				  context.strokeStyle = '#22aeff';
				  context.lineCap = 'round';
		 	      context.moveTo(XBox +35, YBox);
			      context.lineTo(XBox +35, YBox+20);
			      context.stroke();
				  
				  // Section Percentage
				  
				  context.font = "bold 12px Arial";
			      context.fillStyle = 'grey';
				  context.fillText("89%",XBox+40,YBox+3+10);

				  // Connecting Line			      
			      context.beginPath();
				  context.strokeStyle = '#cacaca';
				  context.lineWidth = 1;
				  context.lineCap = 'butt';
		 	      context.moveTo(XBox +40 + 30, YBox+3+10-5);
			      context.lineTo(posx,posy);
			      context.stroke();
			      
			      
		  // ************ Labels Section
	          	     
	     
	      startAngle = endAngle;
	      lastAngle = endAngle;
	      n++;
	  }
 	      // Draw Inner Circle
 	      context.beginPath();
		  context.fillStyle = '#22aeff';
	      context.lineWidth = 1;
		  context.arc(x, y, radius-20, (-Math.PI/2), (Math.PI*2), counterClockwise);
	      context.fill();
	      context.stroke();
		  context.lineWidth = 35;

 		  // Draw Main Text
		  var font = '30pt Lucida Sans Unicode';
		  var message = SummaryData+' %';
		  context.fillStyle = '#22aeff';
		  context.fillStyle = 'white';
		  context.textAlign = 'left';
		  context.textBaseline = 'top'; // important!
		  context.font = font;
		  var w = context.measureText(message).width;
		  var TextH = GetCanvasTextHeight(message,font);
		  context.fillText(message, x-(w/2), y-(TextH));

		  
		  //Draw "Missing" segment
 	      context.beginPath();
	      ColorRGB = hexToRgb('#C0C0C0');
	      // Opacity value reflects how recent data is
	      var Opac = 0.2;
	      SegColor = 'rgba('+ColorRGB.r+','+ColorRGB.g+','+ColorRGB.b+','+Opac+')';
		  context.strokeStyle = SegColor;
 	      var MissingSize = 100 - SummaryData;
	      endAngle = startAngle + TranslateAngle(MissingSize,100);
	      midAngle = startAngle + (TranslateAngle(MissingSize/2,100));
		  context.lineWidth = 35;
	      context.arc(x, y, radius, startAngle, endAngle, counterClockwise);
	      context.stroke();

		  //Draw external border to existing group of segments
		  context.beginPath();
	      startAngle = 0 - (Math.PI/2);
	      endAngle = lastAngle;
	      context.strokeStyle = '#585858';
		  context.lineWidth = 2;
	      context.arc(x, y, radius+17, startAngle, endAngle, counterClockwise);
	      context.stroke();
	      context.beginPath();
	      context.arc(x, y, radius-17, startAngle, endAngle, counterClockwise);
	      context.stroke();
	      context.beginPath();
	      var posx = x + ((radius-18) * Math.cos (startAngle));
	      var posy = y + ((radius-18) * Math.sin (startAngle));
	      var posx2 = x + ((radius+18) * Math.cos (startAngle));
	      var posy2 = y + ((radius+18) * Math.sin (startAngle));
		  context.moveTo(posx,posy);
	      context.lineTo(posx2,posy2);
	      var posx = x + ((radius-18) * Math.cos (lastAngle));
	      var posy = y + ((radius-18) * Math.sin (lastAngle));
	      var posx2 = x + ((radius+18) * Math.cos (lastAngle));
	      var posy2 = y + ((radius+18) * Math.sin (lastAngle));
		  context.moveTo(posx,posy);
	      context.lineTo(posx2,posy2);
	      context.stroke();

		  
 
 	  // REPORTS GRAPH
 	  // Get Basic Icons and Colors for every type of report
 	  	var RepData = Array();
		// Ajax call to retrieve a JSON Array **php return array** 
		var queUrl = 'GetReportSet.php';
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				//alert('Data Fetched');
				RepData = data.items;
			},
 	       error: function (xhr, ajaxOptions, thrownError) {
	        	alert(xhr.status);
				alert(thrownError);
	       }

		});
 	  //
 	  // Get Report Data for this user
 	  	var RepNumbers = Array();
		// Ajax call to retrieve a JSON Array **php return array** 
		var queUrl = 'GetReportNumbers.php?User='+UserID;
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				//alert('Data Fetched');
				RepNumbers = data.items;
			},
 	       error: function (xhr, ajaxOptions, thrownError) {
	        	alert(xhr.status);
				alert(thrownError);
	       }

		});
 	  //
 	  

	  var canvas = document.getElementById('myCanvas2');
      var context = canvas.getContext('2d');
      var x = canvas.width / 2;
      var y = canvas.height / 2;
      var radius = 85;
      var widthSegment = 15;
      var startAngle = 0 - (Math.PI/2);
      var counterClockwise = false;

 	  ColSeg.length = 0;
	  SizSeg.length = 0;
	  ImgSeg.length = 0;
	  
	  var qx = 1;
	  var TotalRep = 0;
	  while (qx < 10){
		  ColSeg[qx] = RepData[qx-1].color;
		  SizSeg[qx] = RepNumbers[qx-1].number;
		  TotalRep = TotalRep + parseInt(SizSeg[qx]);
		  qx++;
	  }
	  
	  ImgSeg[1] = 'R1';
	  ImgSeg[2] = 'R2';
	  ImgSeg[3] = 'R3';
	  ImgSeg[4] = 'R4';
	  ImgSeg[5] = 'R5';
	  ImgSeg[6] = 'R6';
	  ImgSeg[7] = 'R7';
	  ImgSeg[8] = 'R8';
	  ImgSeg[9] = 'R9';
	  ImgSeg[10] = 'R10';
     
	  var MaxSegments = 10;
	  var MaxReports = TotalRep;
     
      context.lineWidth = widthSegment;
	  startAngle = 0 - (Math.PI/2);
	      	    
	  var n = 1;
	  while (n <= MaxSegments)
	  {
	      context.beginPath();
	      SegColor = ColSeg[n];
	      if (SizSeg[n]>0){
		      // Draw the segments
		      context.strokeStyle = SegColor;
		      endAngle = startAngle + TranslateAngle(SizSeg[n],MaxReports);
		      midAngle = startAngle + (TranslateAngle(SizSeg[n]/2,MaxReports));
			  context.lineWidth = widthSegment;
		      context.arc(x, y, radius, startAngle, endAngle, counterClockwise);
		      context.stroke();
	
		      // Draw badges at inner part of segments
			  var SizeBadge = 30;
			  var PointAngle = radius-(widthSegment/2)-(SizeBadge/2);
		      var posx = x + (PointAngle  * Math.cos (midAngle)) - (SizeBadge/2);
		      var posy = y + (PointAngle  * Math.sin (midAngle)) - (SizeBadge/2);
			  var imageObj=document.getElementById(ImgSeg[n]);
			  context.drawImage(imageObj, posx, posy,SizeBadge,SizeBadge);
	
		      startAngle = endAngle;
	      }
		  n++;
	  }

 	      // Draw Inner Circle
 	      context.beginPath();
		  context.fillStyle = '#54bc00';
	      context.lineWidth = 1;
		  context.arc(x, y, radius-50, (-Math.PI/2), (Math.PI*2), counterClockwise);
	      context.fill();
	      context.stroke();
		  context.lineWidth = 35;

 		  // Draw Main Text
		  var font = '30pt Lucida Sans Unicode';
		  var message = MaxReports+'';
		  context.fillStyle = '#54bc00';
		  context.fillStyle = 'white';
		  context.textAlign = 'left';
		  context.textBaseline = 'top'; // important!
		  context.font = font;
		  var w = context.measureText(message).width;
		  var TextH = GetCanvasTextHeight(message,font);
		  context.fillText(message, x-(w/2), y-(TextH));
 

 	//}, 500)
	};


	
	$('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });

	$('#myCanvas').live('click',function(){
		var myClass = $('#USERID').val();
		window.location.replace('medicalPassport.php?IdUsu='+myClass);
	});

	$('#myCanvas2').live('click',function(){
		var myClass = $('#USERID').val();
		window.location.replace('patientdetailMED-newUSER.php?IdUsu='+myClass);
	});

    $(window).bind('load', function(){
        $('#newinbox').trigger('click');
    });
 
	$('#BotMessages').live('click',function(){
        $('#compose_message').trigger('click');
	});
	
	$('#newinbox').live('click',function(){
       //alert('trigger');
	   var queUrl ='<?php echo $domain;?>/getInboxMessageUSER.php?IdMED=<?php echo $MEDID;?>&patient=<?php echo $UserID;?>';
	   //alert (queUrl);
       $('#datatable_3').load(queUrl);
	   $('#datatable_3').trigger('update');
           
   });
   
    $('#newoutbox').live('click',function(){
      //alert('trigger');
	   var queUrl ='<?php echo $domain;?>/getOutboxMessageUSER.php?IdMED=<?php echo $MEDID;?>&patient=<?php echo $UserID;?>';
	   $('#datatable_4').load(queUrl);
	   $('#datatable_4').trigger('update');
           
   });
    
    var doctor_to_connect = '';
    var type_of_doctor_to_find = '';
    $('#Talk').live('click', function()
    {	
        doctor_to_connect = '';
        type_of_doctor_to_find = '';
        $('.recent_doctor_button_selected').attr("class", "recent_doctor_button");
        $("#Talk_Section_1").css("display", "block");
        $("#Talk_Section_2").css("display", "none");
        $("#Talk_Section_3").css("display", "none");
        /*var h = ($("#recent_doctors").children().length - 1) * 35;
        for(var k = 0; k < $("#recent_doctors").children().length; k++)
        {
            if(k == 0 && $("#recent_doctors").children().length == 1)
            {
                $("#recent_doctors").children().eq(k).css("border-top-left-radius", "");
                $("#recent_doctors").children().eq(k).css("border-top-right-radius", "");
                $("#recent_doctors").children().eq(k).css("border-bottom-left-radius", "");
                $("#recent_doctors").children().eq(k).css("border-bottom-right-radius", "");
                $("#recent_doctors").children().eq(k).css("border-radius", "10px");
            }
            else if(k == 0 && $("#recent_doctors").children().length > 1)
            {
                $("#recent_doctors").children().eq(k).css("border-radius", "");
                $("#recent_doctors").children().eq(k).css("border-top-left-radius", "10px");
                $("#recent_doctors").children().eq(k).css("border-top-right-radius", "10px");
                $("#recent_doctors").children().eq(k).css("border-bottom-left-radius", "0px");
                $("#recent_doctors").children().eq(k).css("border-bottom-right-radius", "0px");
            }
            else if(k > 0 && k < $("#recent_doctors").children().length - 1)
            {
                $("#recent_doctors").children().eq(k).css("border-radius", "");
                $("#recent_doctors").children().eq(k).css("border-top-left-radius", "");
                $("#recent_doctors").children().eq(k).css("border-top-right-radius", "");
                $("#recent_doctors").children().eq(k).css("border-bottom-left-radius", "");
                $("#recent_doctors").children().eq(k).css("border-bottom-right-radius", "");
            }
            else if(k == $("#recent_doctors").children().length - 1)
            {
                $("#recent_doctors").children().eq(k).css("border-radius", "");
                $("#recent_doctors").children().eq(k).css("border-top-left-radius", "");
                $("#recent_doctors").children().eq(k).css("border-top-right-radius", "");
                $("#recent_doctors").children().eq(k).css("border-bottom-left-radius", "10px");
                $("#recent_doctors").children().eq(k).css("border-bottom-right-radius", "10px");
            }
        }
        $("#recent_doctors_section").css("display", "block");
        if($("#recent_doctors").children().length == 0)
        {
            h = -90;
            $("#recent_doctors_section").css("display", "none");
        }*/
        $("#find_doctor_modal").dialog({bgiframe: true, width: 550, height: 400, resize: false, modal: true});
        
    });
        
    $("#find_doctor_appointment_button").live('click',function()
    {
        $("#step_bar_1").attr("class", "step_bar lit");
        $("#step_circle_1").attr("class", "step_circle lit");
        $("#step_circle_2").attr("class", "step_circle lit");
        $("#find_doctor_main").fadeOut(300, function(){$("#find_doctor_appointment_1").fadeIn(300)});
    });
    $("#find_doctor_video_button").live('click', function()
    {
        $("#find_doctor_video_button").css('background-color', '#22aeff');
        $("#find_doctor_phone_button").css('background-color', '#535353');
    });
    $("#find_doctor_phone_button").live('click', function()
    {
        $("#find_doctor_phone_button").css('background-color', '#22aeff');
        $("#find_doctor_video_button").css('background-color', '#535353');
    });
        
        
        
    $('#find_doctor_button').live('click', function()
    {
        if(doctor_to_connect.length > 0 || type_of_doctor_to_find.length > 0)
        {
            var info = doctor_to_connect.split("_");
            $.post("find_doctor.php", {type: type_of_doctor_to_find, id: info[0]}, function(data, status)
            {
                if(data == 'none')
                {
                    $('#find_doctor_modal').dialog('option', 'title', 'No Doctors Found');
                    $("#Talk_Section_1").fadeOut("Slow", function(){$("#Talk_Section_3").fadeIn("slow");});
                    $("#find_doctor_modal").dialog("widget").animate({width: '550px', height: '130px'}, {duration: 500, step: 
                        function()
                        {
                            $("#dialog").dialog('option', 'position', 'center');
                        }
                    });
                }
                else
                {
                    var doc = JSON.parse(data);
                    var name = doc.name;
                    name = name.replace("_", " ");
                    doctor_to_connect = doc.id + "_" + doc.phone + "_" + doc.name;
                    $('#find_doctor_modal').dialog('option', 'title', 'Doctor ' + name);
                    $("#Talk_Section_1").fadeOut("Slow", function(){$("#Talk_Section_2").fadeIn("slow");});
                    $("#find_doctor_modal").dialog("widget").animate({width: '550px', height: '130px'}, {duration: 500, step: 
                        function()
                        {
                            $("#dialog").dialog('option', 'position', 'center');
                        }
                    });
                }
            });
        }
        
    });
    $('.recent_doctor_button').live('click', function()
    {
        $('.recent_doctor_button_selected').attr("class", "recent_doctor_button");
        $(this).attr("class", "recent_doctor_button_selected");
        doctor_to_connect = $(this).attr("id");
        type_of_doctor_to_find = '';
    });
    $("#speciality").change(function()
    {
        doctor_to_connect = '';
        type_of_doctor_to_find = $(this).val();
    });
    $('#phone_call_button').live('click',function(e)
    {
        //$("#find_doctor_modal").dialog("close");
        var info = doctor_to_connect.split("_");
        $('#find_doctor_modal').dialog('option', 'title', 'Calling Doctor ' + info[2]);
        $("#Talk_Section_2").fadeOut("Slow", function(){$("#Talk_Section_4").fadeIn("slow");});
        $.post("start_telemed_phonecall.php", {pat_phone: $("#USERPHONE").val(), doc_phone: info[1], doc_id: info[0], pat_id: $("#USERID").val(), doc_name: (info[2] + ' ' + info[3]), pat_name: ($("#USERNAME").val() + ' ' + $("#USERSURNAME").val())}, function(data, status)
        {
            console.log(data);
        });
    });
    $('#video_call_button').live('click',function(e)
     {
        $("#find_doctor_modal").dialog("close");
         var info = doctor_to_connect.split("_");
        $.post("start_telemed_videocall.php", {pat_phone: $("#USERPHONE").val(), doc_phone: info[1], doc_id: info[0], pat_id: $("#USERID").val(), doc_name: (info[2] + ' ' + info[3]), pat_name: ($("#USERNAME").val() + ' ' + $("#USERSURNAME").val())}, function(data, status)
        {
            console.log(data);
            if(data == 1)
            {
                var info = doctor_to_connect.split("_");
                window.open("telemedicine_patient.php?MED=" + info[0] + "&PAT=" + $("#USERID").val(),"Telemedicine","height=650,width=700,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes");
            }
            else
            {
                alert("There was an error. Please try again later");
            }
        });
     });

    $('#compose_message').live('click',function(){
        $('#messagecontent_inbox').attr('value','');
        $('#ToDoctor').attr('value','To: ');
        $('#subjectname_inbox').attr('value','');
        $('#subjectname_inbox').removeAttr("readonly");   
        $('#messagedetails').hide();
        $('#replymessage').show();
        $("#attachments").empty();
        $('#attachments').hide();
        $('#Reply').hide();
        $("#CloseMessage").hide();
        $('#Attach').hide();
        $('#sendmessages_inbox').show();
        $('#attachreports').show();
        $('#message_modal').trigger('click');
   });
                              
    $('#sendmessages_inbox').live('click',function(){
         var IdPaciente = <?php echo $UserID;?>;
         var FullNamePaciente = $('#NombreComp').html();
         //alert (FullNamePaciente);
         IdDoctor = $("#IdDoctor").attr('value');
         var content = $('#messagecontent_inbox').val();
         var subject=$('#subjectname_inbox').val();;
         //reportids = reportids.replace(/\s+$/g,' ');
         var IdDocOrigin = IdDoctor;
         var IdDoctor = IdDoctor;
         var Receiver = 0;
         reportids = ' ';
         //alert ('IdPaciente: '+IdPaciente+' - '+'Sender: '+IdDocOrigin+' - '+'Attachments: '+reportids+' - '+'Receiver: '+IdDoctor+' - '+'Content: '+content+' - '+'subject: '+subject+' - '+'connection_id: 0');
         var cadena='sendMessageUSER.php?sender='+IdDocOrigin+'&receiver='+IdDoctor+'&patient='+IdPaciente+''+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=0&tofrom=to';
         var RecTipo=LanzaAjax(cadena);
         
         $('#messagecontent_inbox').attr('value','');
         $('#subjectname_inbox').attr('value','');
         displaynotification('status',RecTipo);
         getUserData(IdPaciente);
         var UserName = user[0].Name;
         var UserSurname = user[0].Surname; 
         
         getMedCreator(IdDoctor);
         var NameMed = doctor[0].Name;
         var SurnameMed = doctor[0].Surname; 
         
         var cadena='push_serverUSER.php?FromDoctorName='+UserName+'&FromDoctorSurname='+UserSurname+'&FromDoctorId='+IdDoctor+'&Patientname='+UserName+'&PatientSurname='+UserSurname+'&IdUsu='+IdPaciente+'&message= New Message <br>From: '+UserName+' '+UserSurname+' <br>Subject: '+(subject).replace(/RE:/,'')+'&NotifType=1&channel='+IdDoctor+'&Selector=2';
         //alert(cadena);
         var RecTipo=LanzaAjax(cadena);
         
         //}
         reportids='';
         $("#attachment_icon").remove();
    
        
        //alert ('Answer of Messg Proc.?: '+RecTipo);
         $('#message_modal').trigger('click');  //Close the modal window
});
     
    $('.CFILA').live('click',function(){
       var id = $(this).attr("id");
       // Get Doctor id and some info
       var cadena='getDoctorMessage.php?msgid='+id;
       var RecTipo=LanzaAjax(cadena);
       var n = RecTipo.indexOf(",");
	   var IdDoctor = RecTipo.substr(0,n);
       var Remaining = RecTipo.substr(n+1,RecTipo.length);
       m = Remaining.indexOf(",");
	   var NameDoctor = Remaining.substr(0,m);
       var SurnameDoctor = Remaining.substr(m+1,Remaining.length);
       $("#IdDoctor").attr('value',IdDoctor);
       //throw "stop execution";
       
       //displaynotification('Message ID'+ id);
	   
       
       $('input[type=checkbox][id^="reportcol"]').each(
        function () {
		 $('input[type=checkbox][id^="reportcol"]').checked=false;
		});
	   reportcheck.length=0;
	   var content=$(this).find('span#'+id).text().replace(/br8k/g,"\n").replace(/sp0e/g," ");
	   $(this).find('span').hide();
	   var reportsattached=$(this).find('ul#'+id).text();
	   
       //alert(reportsattached);
	   
       $("#attachments").empty();
	   if(reportsattached){
               //alert("into attachments");
               var ElementDOM ='All';
               var EntryTypegroup ='0';
               var Usuario = $('#userId').val();
               var MedID =$('#MEDID').val();
               //alert ('Usuario: '+Usuario+' MedID: '+MedID);
                var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports='+reportsattached;
                var RecTipo=LanzaAjax(queUrl);
                //alert(RecTipo);
                $("#attachments").append(RecTipo);
                /*$("#attachments").load(queUrl);
                $("#attachments").trigger('update');*/
                $("#attachments").show();
		}else{
                $('#attachments').hide();
                //alert("no attachments");
		}
	   //content.replace(/[break]/g,"\n").replace(/[space]/g," ");
	   //alert($(this).find('a').text());
	   //displaynotification('Message read');
	   //$('#attachments').hide();
	   $('#Attach').hide();
	   $('#messagedetails').show();
       $("#ToDoctor").show();
       $('#ToDoctor').html('To: '+NameDoctor+' '+SurnameDoctor);
       $('#replymessage').hide();
	   $("#Reply").attr('value','Reply');
       $("#Reply").show();
       $("#CloseMessage").show();
	   $('#messagedetails').val(content);
	   $('#messagedetails').attr('readonly','readonly');
	   $('#messagedetails,#subjectname_inbox').css("cursor","pointer");
	   $('#subjectname_inbox').val($(this).find('a').text());
	   $('#subjectname_inbox').attr('readonly','readonly');
	   $('#replymessage').hide();
	   $('#sendmessages_inbox').hide();
	   $('#attachreports').hide();
	   $('#message_modal').trigger('click');
	   var cadena='setMessageStatusUSER.php?msgid='+id;
	   var RecTipo=LanzaAjax(cadena);
	   /*setTimeout(function(){
	   $('#newinbox').trigger('click');},1000);*/
	   
   });
        
    $('.CFILA_out').live('click',function(){
       var id = $(this).attr("id");
	   //displaynotification('Message ID'+ id);
	   $('input[type=checkbox][id^="reportcol"]').each(
        function () {
		 $('input[type=checkbox][id^="reportcol"]').checked=false;
		});
	   reportcheck.length=0;
	   var content=$(this).find('span#'+id).text().replace(/br8k/g,"\n").replace(/sp0e/g," ");
	   $(this).find('span').hide();
	   var reportsattached=$(this).find('ul#'+id).text();
	   //alert(reportsattached);
	   $("#attachments").empty();
	   if(reportsattached){
	   //alert("into attachments");
	   var ElementDOM ='All';
	   var EntryTypegroup ='0';
	   var Usuario = $('#userId').val();
	   var MedID =$('#MEDID').val();
	   
		var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports='+reportsattached;
	    var RecTipo=LanzaAjax(queUrl);
		//alert(RecTipo);
		$("#attachments").append(RecTipo);
      	/*$("#attachments").load(queUrl);
    	$("#attachments").trigger('update');*/
		$("#attachments").show();
		}else{
		$('#attachments').hide();
		//alert("no attachments");
		}
	   //content.replace(/[break]/g,"\n").replace(/[space]/g," ");
	   //alert($(this).find('a').text());
	   //displaynotification('Message read');
	   //$('#attachments').hide();
	   $('#Attach').hide();
	   $('#messagedetails').show();
       $('#replymessage').hide();
	   $("#Reply").attr('value','Reply');
       $("#Reply").hide();
       $("#CloseMessage").show();
	   $('#messagedetails').val(content);
	   $('#messagedetails').attr('readonly','readonly');
	   $('#messagedetails,#subjectname_inbox').css("cursor","pointer");
	   $('#subjectname_inbox').val($(this).find('a').text());
	   $('#subjectname_inbox').attr('readonly','readonly');
	   $('#replymessage').hide();
	   $('#sendmessages_inbox').hide();
	   $('#attachreports').hide();
	   //$('#clearmessage').hide();
	   $('#message_modal').trigger('click');
	   /*var cadena='setMessageStatus.php?msgid='+id;
	   var RecTipo=LanzaAjax(cadena);
	   setTimeout(function(){
	   $('#newoutbox').trigger('click');},1000);*/
	   
   });
        
    $("#Attach").live('click',function(){
     reportids='';
    
    $('input[type=checkbox][id^="reportcol"]').each(
     function () {
				var sThisVal = (this.checked ? "1" : "0");
				
				//sList += (sList=="" ? sThisVal : "," + sThisVal);
				if(sThisVal==1){
				 var idp=$(this).parents("div.attachments").attr("id");
				//alert("Id "+idp+" selected"); 
				reportcheck.push(this.id);
				 //messageid=messageid+idp+' ,';
				 reportids=reportids+idp+' ';
				
				 /*var cadena='setMessageStatus.php?msgid='+idp+'&delete=1';
				 LanzaAjax(cadena);*/
				}
			
				
	});
	 //alert(reportids);
	var conf=false;
	if(reportids>'')
		conf=confirm("Confirm Attachments");
	
	if(conf){
	$("#Reply").trigger('click');
	$("#attachreportdiv").append('<i id="attachment_icon" class="icon-paper-clip" style="margin-left:10px"></i>');
	//alert(reportids);
	}else{
	reportids='';
	for (i = 0 ; i < reportcheck.length; i++ ){
				
		document.getElementById(reportcheck[i]).checked = false;
				
	}
	reportcheck.length=0;
	$("#Reply").trigger('click');
	}
      
   });
   
    var isloaded=false;   //This variable is to make sure the page loads the report only once.
   
  
   
    $('#attachreports').live('click',function(){
   
    $('input[type=checkbox][id^="reportcol"]').each(
     function () {
				var sThisVal = (this.checked ? "1" : "0");
				if(sThisVal==1){
				reportcheck.push(this.id);
				}
				
	});
	/*if(!isloaded){
	//$('#attachments').remove();*/
	$("#attachments").empty();
	createPatientReports();
	//isloaded=true;
	//}
	setTimeout(function(){
	for (i = 0 ; i < reportcheck.length; i++ ){
				
		document.getElementById(reportcheck[i]).checked = true;
				
	}},400);
   $("#attachment_icon").remove();
   $('#sendmessages_inbox').hide();
   $('#replymessage').hide();
   $(this).hide();   
   $('#attachments').show();
   $('#Attach').show();
   $("#Reply").attr('value','Back');
   $("#Reply").show();
   
   
   });
      
    $("#Reply").live('click',function(){
       var subject_name='RE:'+($('#subjectname_inbox').val()).replace(/RE:/,'');
       $('#ToDoctor').show();
       $('#ToDoctor').attr('value','To: ');
       $('#subjectname_inbox').val(subject_name);   
       $('#messagedetails').hide();
       $('#replymessage').show();
       $('#attachments').hide();
       $('#Attach').hide();
       $(this).hide();
       $("#CloseMessage").hide();
       $('#sendmessages_inbox').show();
       $('#attachreports').show();
   });

     
   
        
        
        
        
        
        
    $("#SearchUser").typeWatch({
				captureLength: 1,
				callback: function(value) {
					$("#BotonBusquedaPac").trigger('click');
					//alert('searching');
				}
	});
	$("#RetrievePatient").click(function(event) {
   		 $('#BotonBusquedaPac').trigger('click');
   	});
    $("#BotonBusquedaPac").click(function(event) {
     		var queMED = $("#MEDID").val();
    	    var UserInput = $('#SearchUser').val();
    	    //alert(UserInput);
    	    /*
    	    var IdUs =156;
    	    var UserInput = $('#SearchUser').val();
                                  
    	    getLifePines(serviceURL);
    	    var longit = Object.keys(pines).length;	
    	    */
    	    //alert (longit);
    	     
			var onlyGroup=0;
			if ($('#RetrievePatient').is(":checked")){
			onlyGroup=1;
			}else{
			onlyGroup=1;
			}
		   
    	     if(UserInput===""){
			    UserInput=-111;
			 }
			// alert(UserInput);
    	    var queUrl ='getFullUsersMED.php?Usuario='+UserInput+'&IdMed='+queMED+'&Usuario='+UserInput+'&Group='+onlyGroup;
			//alert(queUrl);
    	    $('#TablaPac').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	    $('#TablaPac').trigger('update');
  	    
    });

    $("#BotonBusquedaPacCOMP").click(function(event) {
    	    var IdUs =156;
    	    var UserInput = $('#SearchUserYCOMP').val();
                                  
    	    var serviceURL = '<?php echo $domain;?>/getpines.php' + '?Usuario=' + UserInput; 
    	    getLifePines(serviceURL);
    	    var longit = Object.keys(pines).length;	
    	    //alert (longit);
    	    var queUrl ='getFullUsers.php?Usuario='+UserInput+'&NReports='+longit;
    	    $('#TablaPacCOMP').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	    $('#TablaPacCOMP').trigger('update');
    });
	
	// function connectTelemedicine()
	// {
		// $.ajax({
		 // url: '<?php echo $domain?>/weemo_test.php?calleeID=<?php echo $DoctorEmail?>',
		 // method: 'get',
			// success: function(data){
		    // var htmlContent = data;
			// var e = document.createElement('div');
			// e.setAttribute('style', 'display: none;');
			// e.innerHTML = htmlContent;
			// document.body.appendChild(e);
			// console.log(document.getElementById("runscript").innerHTML);
			// eval(document.getElementById("runscript").innerHTML);
			 // }
		 // });
	// }

	// $("#Telemedicine").on('click',function() {
			// connectTelemedicine();
		 // });

	$('#BotPassbook').live('click',function(){
	  $("#modalContents").dialog({bgiframe: true, height: 1000, height: 340, modal: true});
	});

	$('#ButtonSkype').live('click',function(){
	 $('#modalContents').dialog( "close" );
	});

	$('#ButtonWeemo').live('click',function(){
		$.ajax({
			 url: '<?php echo $domain?>/weemo_test.php?calleeID=<?php echo $DoctorEmail;?>',
			 method: 'get',
				success: function(data){
				var htmlContent = data;
				var e = document.createElement('div');
				e.setAttribute('style', 'display: none;');
				e.innerHTML = htmlContent;
				document.body.appendChild(e);
				eval(document.getElementById("runscript").innerHTML);
			  }
		 });
		$('#modalContents').dialog( "close" );
	});
     
	$('#telemedicine').live('click',function(){
	 // e.preventDefault();
	  $("#modalContents").dialog({bgiframe: true, height: 140, modal: true});
	 });
     /*
     $('#TablaPac').bind('click', function() {
          	
          	var NombreEnt = $('#NombreEnt').val();
            var PasswordEnt = $('#PasswordEnt').val();
                                   
          	window.location.replace('patientdetail.php?Nombre='+NombreEnt+'&Password='+PasswordEnt);

//      alert($(this).text());
      });
*/

     $('#TablaPacCOMP').bind('click', function() {
      alert($(this).text());
      });

	    
 	$('#datatable_1').dataTable( {
		"bProcessing": true,
		"bDestroy": true,
		"bRetrieve": true,
		"sAjaxSource": "getBLOCKS.php"
	} );
    
    $('#Wait1')
    .hide()  // hide it initially
    .ajaxStart(function() {
        //alert ('ajax start');
        $(this).show();
    })
    .ajaxStop(function() {
        $(this).hide();
    }); 

    $('#datatable_1 tbody').click( function () {
    // Alert the contents of an element in a SPAN in the first TD    
    alert( $('td:eq(0) span', this).html() );
    } );
 
    });        
    
     
    function getLifePines(serviceURL) {
    $.ajax(
           {
           url: serviceURL,
           dataType: "json",
           async: false,
           success: function(data)
           {
           pines = data.items;
           }
           });
     }        

	window.onload = function(){		
		
		var quePorcentaje = $('#quePorcentaje').val();
		var g = new JustGage({
			id: "gauge", 
			value: quePorcentaje, 
			min: 0,
			max: 100,
			title: " ",
			label: "% Refered to me"
		}); 
	};
	
	  

  
    function TranslateAngle(x,maxim){
	    var y = (x * Math.PI * 2) / maxim;
	    return parseFloat(y);
    }
    
    function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
	}
	
    
    function GetCanvasTextHeight(text,font){
    var fontDraw = document.createElement("canvas");

    var height = 100;
    var width = 100;

    // here we expect that font size will be less canvas geometry
    fontDraw.setAttribute("height", height);
    fontDraw.setAttribute("width", width);

    var ctx = fontDraw.getContext('2d');
    // black is default
    ctx.fillRect(0, 0, width, height);
    ctx.textBaseline = 'top';
    ctx.fillStyle = 'white';
    ctx.font = font;
    ctx.fillText(text/*'Eg'*/, 0, 0);

    var pixels = ctx.getImageData(0, 0, width, height).data;

    // row numbers where we first find letter end where it ends 
    var start = -1;
    var end = -1;

    for (var row = 0; row < height; row++) {
        for (var column = 0; column < width; column++) {

            var index = (row * width + column) * 4;

            // if pixel is not white (background color)
            if (pixels[index] == 0) {
                // we havent met white (font color) pixel
                // on the row and the letters was detected
                if (column == width - 1 && start != -1) {
                    end = row;
                    row = height;
                    break;
                }
                continue;
            }
            else {
                // we find top of letter
                if (start == -1) {
                    start = row;
                }
                // ..letters body
                break;
            }

        }

    }
   /*
    document.body.appendChild(fontDraw);
    fontDraw.style.pixelLeft = 400;
    fontDraw.style.pixelTop = 400;
    fontDraw.style.position = "absolute";
   */

    return end - start;
    };
				 		
	</script>

    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
    <script src="js/google-code-prettify/prettify.js"></script>
   
    <script src="js/jquery.flot.min.js"></script>
    <script src="js/jquery.flot.pie.js"></script>
    <script src="js/jquery.flot.orderBars.js"></script>
    <script src="js/jquery.flot.resize.js"></script>
    <script src="js/graphtable.js"></script>
    <script src="js/fullcalendar.min.js"></script>
    <script src="js/chosen.jquery.min.js"></script>
    <script src="js/autoresize.jquery.min.js"></script>
    <script src="js/jquery.tagsinput.min.js"></script>
    <script src="js/jquery.autotab.js"></script>
    <script src="js/elfinder/js/elfinder.min.js" charset="utf-8"></script>
	<script src="js/tiny_mce/tiny_mce.js"></script>
    <script src="js/validation/languages/jquery.validationEngine-en.js" charset="utf-8"></script>
	<script src="js/validation/jquery.validationEngine.js" charset="utf-8"></script>
    <script src="js/jquery.jgrowl_minimized.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/jquery.mousewheel.js"></script>
    <script src="js/jquery.jscrollpane.min.js"></script>
    <script src="js/jquery.stepy.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/raphael.2.1.0.min.js"></script>
    <script src="js/justgage.1.0.1.min.js"></script>
	<script src="js/glisse.js"></script>

	<script src="js/application.js"></script>

 <?php

function queFuente ($numero)
{
$queF=10;
switch ($numero)
{
	case ($numero>999 && $numero<9999):	$queF = 30;
										break;
	case ($numero>99 && $numero<1000):	$queF = 50;
										break;
	case ($numero>0 && $numero<100):	$queF = 70;
										break;
}

return ($queF);

}

function queFuente2 ($numero1, $numero2)
{
$queF=10;
$numero= digitos($numero1)+digitos($numero2);
switch ($numero)
{
	case 2:	$queF = 60;
			break;
	case 3:	$queF = 55;
			break;
	case 4:	$queF = 50;
			break;
	case 5:	$queF = 45;
			break;
	case 6:	$queF = 40;
			break;
	case 7:	$queF = 35;
			break;
	case 8:	$queF = 30;
			break;
}

return ($queF);

}

function digitos ($numero)
{
$queF=0;

switch ($numero)
{
	case ($numero>999 && $numero<9999):	$queF = 4;
										break;
	case ($numero>99 && $numero<1000):	$queF = 3;
										break;
	case ($numero>0 && $numero<100):	$queF = 2;
										break;
}

return ($queF);

}
?>

  </body>
</html>
