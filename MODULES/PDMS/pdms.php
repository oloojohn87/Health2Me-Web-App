<?php
include_once('../../userConstructClass.php');
$user = new userConstructClass();
?>
<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../../css/style.css" rel="stylesheet">
    <link href="css/pdms.css" rel="stylesheet">
    <link href="../../css/bootstrap.css" rel="stylesheet">
    <link href="../../css/bootstrap-dropdowns.css" rel="stylesheet">
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
    <link rel="stylesheet" type="text/css" href="../../css/googleAPIFamilyCabin.css">
	<link rel="stylesheet" href="../../css/icon/font-awesome.css">
    <link rel="stylesheet" href="../../css/bootstrap-responsive.css">
	<link rel="stylesheet" href="../../css/toggle-switch.css">

	<!--HIDDENS-->
	<input type='hidden' id='user-access' value='<?php echo $user->doctor_privilege ?>' />
	<!----------->
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/icons/favicon.ico">
  <body style="background: #F9F9F9;">
      
      <div id="telemedicine_status" title="Consultation Status" style="display: none; width: 400px; height: 150px;">
          <h4 style="color: #666; text-align: center;">Consultation Started</h4><br/>
          <p style="color: #666; text-align: center;">Call Status: <span id="call_status_label">Connecting...</span></p>
      
      </div>
      
      <!--Header Start-->
	<div class="header" >
  		
           <a href="index.html" class="logo"><h1>Health2me</h1></a>
        
        <!-- Start of new code by Pallab for language changes-->
             <!-- Beautification of button (changes to standar classes to be added to this instance of dropdown -->
               <div style="margin-top:11px;float:left; margin-left:50px;" class="btn-group">
                      <button id="lang1" type="button" class="btn btn-default dropdown-toggle addit_button" data-toggle="dropdown">
                        Language <span class="caret addit_caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#en" onclick="setCookie('lang', 'en', 30); return false;">English</a></li>
                        <li><a href="#sp" onclick="setCookie('lang', 'th', 30); return false;">Espa&ntilde;ol</a></li>
                      </ul>
                </div>
               
             <script>
                var langType = initial_language;

                if(langType == 'th')
                {
                $("#lang1").html("Espa&ntilde;ol <span class=\"caret addit_caret\"></span>");
                }
                else{
                var language = 'en';
                $("#lang1").html("English <span class=\"caret addit_caret\"></span>");
                }
                </script>
              <!-- End of new code by Pallab language changes-->
           
           <div class="pull-right">
           
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >

            <div class="dropdown-menu" id="prof_dropdown">
            <div class="item_m"><span class="caret"></span></div>
            <ul class="clear_ul" >
			<li>
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
    <div id="content" style="background: #F9F9F9; padding-left:0px;">
    
    	    
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">

     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
     
     
        <div id="notesModal" style="width: 600px; height: 800px; padding: 0px; display: none;"></div>
        <div id="summaryModal" style="width: 680px; height: 800px; padding: 0px; display: none;"></div>
        <div id="recordingModal" style="width: 400px; height: 400px; padding: 0px; display: none;"></div>
     <!--CONTENT MAIN START-->
        <div class="content">
            <div class="grid" class="grid span4" style="width:1000px; height:2400px; margin: 0 auto; margin-top:30px; padding-top:30px;">
                
                <!-- ADD STUFF HERE -->
                <h3 style="text-align: center;"><span lang="en">PDMST Dashboard</span></h3> <!-- Added Lang by Pallab-->
                <div style="width: 400px; height: 170px; float: right; margin-right: 40px; margin-top: 30px;">
                    <div style="width: 200px; height: 100px; float: left;">
                        <h4 style="color: #8A8A8A; text-align: center; font-size: 32px;" lang="en">Consultations</h4> <!-- Added Lang by Pallab-->
                        <p id="number_of_consultations" style="color: #22AEFF; font-size: 42px; font-weight: bold; text-align: center; margin-top: 25px;"></p>
                    </div>
                    <div style="width: 200px; height: 100px; float: left;">
                        <h4 style="color: #8A8A8A; text-align: center; font-size: 32px;" lang ="en">Users</h4> <!-- Added Lang by Pallab-->
                        <p id="number_of_users" style="color: #5EB529; font-size: 42px; font-weight: bold; text-align: center; margin-top: 25px;"></p>
                    </div>
                    <div style="width: 200px; height: 35px; margin-top: 25px; float: right; margin-right: 20px;">
                        <button id="toggle_1" class="segmented_control_selected" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;" lang="en">Actual</button>
                        <button id="toggle_2" class="segmented_control" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;" lang="en">Cumulative</button>
                    </div>
                </div>
                <div style="width: 500px; height: 100px; margin-left: 40px; margin-top: 30px;">
                    <div style="width: 500px; height: 50px;">
                        <label style="margin-right: 30px; float: left; margin-top: 5px;"><span lang="en">Scope</span>:&nbsp;</label> <!-- Added Lang by Pallab-->
                        <select id="scope_select" style="float: left;">
                            <option value="Global" selected lang = "en">Global</option> <!-- Added Lang by Pallab-->
                            <?php 
                            require("../../environment_detail.php");

							$dbhost = $env_var_db["dbhost"];
							$dbname = $env_var_db["dbname"];
							$dbuser = $env_var_db["dbuser"];
							$dbpass = $env_var_db["dbpass"];
							
							$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
							if (!$con){
								die('Could not connect: ' . mysql_error());
								}
								if($user->doctor_privilege == 3){
									$res = $con->prepare("SELECT DISTINCT GrantAccess FROM usuarios WHERE GrantAccess != 'H2M' && GrantAccess is not null");
								}else{
									$res = $con->prepare("SELECT DISTINCT GrantAccess FROM usuarios WHERE GrantAccess = 'H2M' OR GrantAccess is null");
								}
                                $res->execute();
                                
                                while($row = $res->fetch(PDO::FETCH_ASSOC))
                                {
                                    
                                    if(strlen($row['GrantAccess']) > 0)
                                    {
                                        echo '<option value="'.$row['GrantAccess'].'">'.$row['GrantAccess'].'</option>';
                                    }
                                    else
                                    {
                                        //echo '<option value="NULL">H2M</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div style="width: 500px; height: 50px;">
                        <label style="margin-right: 30px; float: left; margin-top: 5px;"><span lang="en">Period</span>:&nbsp;</label> <!-- Added Lang by Pallab-->
                        <select id="period_select" style="float: left;">
                            <option value="1" selected lang="en">Today</option> <!-- Added Lang by Pallab-->
                            <option value="2" lang="en">This Week</option> <!-- Added Lang by Pallab-->
                            <option value="3" lang="en">This Month</option> <!-- Added Lang by Pallab-->
                            <option value="4" lang="en">This Year</option> <!-- Added Lang by Pallab-->
                        </select>
                    </div>
                </div>
                <div style="width: 210px; height: 30px; margin-left: 75px; margin-top: 45px; margin-bottom: -35px;">
                    <button class="consultations_button_selected" lang="en">Consultations</button> <!-- Added Lang by Pallab-->
                    <button class="users_button_selected" lang="en">Users</button> <!-- Added Lang by Pallab-->
                </div>
                <div style="width: 900px; height: 300px; margin-left: 40px; margin-top: 30px;">
                    <canvas id="day_chart" width="900" height="300"></canvas>
                    <canvas id="week_chart" width="900" height="300" style="display: none;"></canvas>
                    <canvas id="month_chart" width="900" height="300" style="display: none;"></canvas>
                    <canvas id="year_chart" width="900" height="300" style="display: none;"></canvas>
                </div>
                <div id="chart_loader" style="width: 52px; height: 42px; margin-left: auto; margin-right: auto; margin-top: -170px; margin-bottom: 208px; display: none;">
                    <img src="images/load/29.gif"  alt="">
                </div>
                <div class="controls" style="float: right; width: 480px; margin-right: 50px; margin-top: 80px;">
                    <input lang="en" placeholder="name or surname or email" class="span7" id="search_bar" style="margin-left:30px;float: left; width: 370px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" size="16" type="text">
                    <button id="search_bar_clear_button" style="height: 18px; width: 18px; border-radius: 15px; border: 0px solid #FFF; outline: 0px; background-color: #E6E6E6; color: #FFF; font-size: 10px; padding: 0px; margin-top: 6px; margin-left: -105px; visibility: hidden;">X</button>
                    <button class="search_bar_button" style="float: left; width: 80px;" id="search_bar_button" lang="en">Search</button>
                </div>
                <div style="width: 425px; height: 35px; margin-left: 50px; margin-top: 80px;margin-right">
                    <button id="segment_1" class="segmented_control_selected" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;" lang ="en">Customers</button> <!-- Added Lang by Pallab-->
                    <button id="segment_2" class="segmented_control" lang="en">Consultations</button> <!-- Added Lang by Pallab-->
                    <button id="segment_3" class="segmented_control" lang="en">Doctors</button> <!-- Added Lang by Pallab-->
					<button id="segment_4" class="segmented_control" style="border-top-right-radius: 5px; border-bottom-right-radius:5px; width:115px;" lang="en">New Users</button> <!-- Added Lang by Pallab-->
                </div>
                <div id="customers_table" style="width: 900px; margin: auto; margin-top: 15px;">
                    <table>
                        <tr>
                            <th style="background-color: #6ECCFF; width: 225px; height: 25px;" ><span lang="en">Name</span>&nbsp;<button id="caret_button_customers_Surname,Name" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" ><span lang="en">Calls</span>&nbsp;<button id="caret_button_customers_numberOfPhoneCalls" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button></th>
                            <!--<th style="background-color: #22AEFF; width: 180px; height: 25px;">
                                Video Conferences
                                <button id="caret_button_customers_videoconferences" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;">
                                <span lang="en">Sign Up Date</span> <!-- Added Lang by Pallab-->
                                <button id="caret_button_customers_SignUpDate" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;"><span lang="en">Plan</span>
                                <!-- Added Lang by Pallab-->
                                <button id="caret_button_customers_typeOfPlan" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                        </tr>
                    </table>
                </div>
                <div id="consultations_table" style="width: 900px; margin: auto; margin-top: 15px; display: none;">
                    <table>
                        <tr>
                            <th style="background-color: #22AEFF; width: 155px; height: 25px;" ><span lang="en">Doctor</span>&nbsp;<button id="caret_button_consultations_doctorSurname,doctorName" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 155px; height: 25px;" ><span lang="en">Patient</span>&nbsp;<button id="caret_button_consultations_patientSurname,patientName" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 190px; height: 25px;" ><span lang="en">Time</span>&nbsp;<button id="caret_button_consultations_DateTime" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 50px; height: 25px;" ><span lang="en">Type</span>&nbsp;<button id="caret_button_consultations_Type" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 65px; height: 25px;" ><span lang="en">Length</span>&nbsp;<button id="caret_button_consultations_Length" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 100px; height: 25px;" ><span lang="en">Status</span>&nbsp;<button id="caret_button_consultations_Status" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 70px; height: 25px;" ><span lang="en">Notes</span>&nbsp;
                                
                                <!--<button id="caret_button_consultations_Data_File" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px; "></button>-->
                            </th>
                            <th style="background-color: #22AEFF; width: 70px; height: 25px;" lang="en">Summ.
                                <!--<button id="caret_button_consultations_Summary_PDF" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>-->
                            </th>
                            <th style="background-color: #22AEFF; width: 70px; height: 25px;" lang="en">Rec.
                                <!--<button id="caret_button_consultations_Recorded_File" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>-->
                            </th>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 130px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 40px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 130px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 40px; height: 15px;"></td>
                        </tr>
                    </table>
                </div>
                <div id="doctors_table" style="width: 900px; margin: auto; margin-top: 15px; display: none;">
                    <table>
                        <tr>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" ><span lang="en">Name</span>&nbsp;<button id="caret_button_doctors_name" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" ><span lang="en">Calls</span>&nbsp;<button id="caret_button_doctors_calls" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 180px; height: 25px;">
                                Video Conferences
                                <button id="caret_button_doctors_cideoconferences" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" ><span lang="en">Patients</span>&nbsp;<button id="caret_button_doctors_numberOfConsultedPatients" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 150px; height: 25px;">
                                Summaries Edited
                                <button id="caret_button_doctors_summaries" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;"><span lang="en">PDFs Created</span>&nbsp;<button id="caret_button_doctors_reportsCreated" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                        </tr>
                    </table>
                    
                    
                </div>
				<div id="newusers_table" style="width: 900px; margin: auto; margin-top: 15px; display: none;">
                    <table>
                        <tr>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" ><span lang="en">Name</span>&nbsp;<button id="caret_button_newusers_name" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" ><span lang="en">Phone</span>&nbsp;<button id="caret_button_newusers2_telefono" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 180px; height: 25px;">
                                Video Conferences
                                <button id="caret_button_doctors_cideoconferences" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" ><span lang="en">Email</span>&nbsp;<button id="caret_button_doctorsnewusers3_email" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 150px; height: 25px;">
                                Summaries Edited
                                <button id="caret_button_doctors_summaries" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;"><span lang="en">Creation Date</span>&nbsp;<button id="caret_button_doctorsnewusers4_signupdate" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                            <td style="background-color: #F5F5F5; width: 225px; height: 15px;"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                            <td style="background-color: #E9E9E9; width: 225px; height: 15px;"></td>
                        </tr>
                    </table>
                    
                    
                </div>
                <div id="table_loader" style="width: 220px; height: 19px; margin-left: auto; margin-right: auto; margin-top: 40px; margin-bottom: 40px; display: none;">
                    <img src="images/load/8.gif"  alt="">
                </div>
                <div style="width: 120px; height: 50px; margin-top: 25px; margin-left: auto; margin-right: auto;">
                    
                    <button id="page_button_left" class="page_button" disabled>
                        <i class="icon-arrow-left"></i>
                    </button>
                    <div id="page_label" style="width: 60px; height: 30px; float: left; margin-top: 5px; text-align: center;">
                        1 of 1
                    </div>
                    <button id="page_button_right" class="page_button" disabled>
                        <i class="icon-arrow-right"></i>
                    </button>
                    
                </div>
                
                <hr/>
                <h2 style="text-align: center; color: #666; font-size: 22px;"><span lang="en">Medical Availability</span></h2>
                <div style="width: 305px; height: 50px; margin-top: 20px; margin-bottom: 43px; margin-left: 20px;">
                    <label style="margin-right: 30px; float: left; margin-top: 5px;"><span lang="en">Period</span>:&nbsp;</label>
                    <select id="period_select_medical_availability" style="float: left;">
                        <option value="1" selected lang="en">Today</option>
                        <option value="2" lang="en">This Week</option>
                        <option value="3" lang="en">This Month</option>
                    </select>
                </div>
                <button id="medical_availability_show_doctors" lang="en">Show All Doctors</button>
                <h2 id="availability_label" style="text-align: center; color: #666; font-size: 22px; margin-top: -50px;">October 1, 2014</h2>
                <div id="medical_availability_container">
                
                </div>
                <hr/>
                <h2 style="text-align: center; color: #666; font-size: 22px;"><span lang="en">Launch Consultation</span></h2>
<center><span lang='en'>Message to doctor</span></center>
		<center><input style="text-align: center;width:600px;" type='text' id='launch-consultation-message' lang='en' /></center>
		<center><div style="width: 150px; height: 30px; margin: auto; margin-top: 40px; margin-bottom: -40px;">
                    <button style='float:left;' class='btn btn-success' id="select-language-en" lang="en">English</button>
		    <button style='float:left;' class='btn btn-default' id="select-language-sp" lang="en">Spanish</button>
                </div></center>                
		<div style="width: 150px; height: 30px; margin: auto; margin-top: 40px; margin-bottom: -40px;">
                    <button id="launch_telemedicine_button" disabled lang="en">Launch</button>
                </div>
                <div style="width: 100%; height: 100px;">
                    <div style="float: left; width: 50%; height: 50px; text-align: center;">
                        <h4 style="color: #444; font-size: 22px;"><span lang="en">Doctor Selected:</span></h4>
                        <p id="selected_doctor_label" style="color: #999; font-size: 18px;" lang="en">None</p>
                    </div>
                    <div style="float: left; width: 50%; height: 50px; text-align: center;">
                        <h4 style="color: #444; font-size: 22px;" lang="en"><span lang="en">Patient Selected:</span></h4>
                        <p id="selected_user_label" style="color: #999; font-size: 18px;" lang="en">None</p>
                    </div>
                    
                </div>
                <div style="width: 100%; height: 600px;">
                    <div style="float: left; width: 50%; height: 400px; margin: 0px; border-right: 1px solid #F5F5F5; display: block;">
                        <div class="controls" style="margin-left: 100px; margin-top: 10px; width: 400px; margin-bottom: 20px;">
                            <input class="span7" id="search_doctors_bar" style="float: left; width: 270px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" lang="en" placeholder="Search Doctor (Name or Email)" size="16" type="text">
                            <button class="search_users_bar_button" style="float: left;" id="search_doctors_bar_button" lang="en">Search</button>
                        </div>
                        <div id="search_doctors_results" style="width: 340px; height: 350px; margin-top: 50px; margin-left: 100px; overflow-y: scroll;"></div>
                    </div>
                    <div style="float: left; width: 49%; height: 400px; margin: 0px; display: block;">
                        <div class="controls" style="margin-left: 80px; margin-top: 10px; width: 400px; margin-bottom: 20px;">
                            <input class="span7" id="search_users_bar" style="float: left; width: 270px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" lang="en" placeholder="Search Patient (Name or Email)" size="16" type="text">
                            <button class="search_users_bar_button" style="float: left;" id="search_users_bar_button" lang="en">Search</button>
                        </div>
                        <div id="search_users_results" style="width: 340px; height: 350px; margin-top: 50px; margin-left: 80px; overflow-y: scroll;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/jquery-ui.min.js"></script>
    <script src="../../js/Chart.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap-popover.js"></script>
    <!--MAIN JS FILE FOR FUNCTIONALITY!!!-->
    <script src="js/pdms.js"></script>
    <!--NO IDEA WTF THIS IS FOR-->
    <script type="text/javascript" src="../../js/42b6r0yr5470"></script>
  </body>
</html>
