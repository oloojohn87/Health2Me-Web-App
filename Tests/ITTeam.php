<?php
session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 $link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
 mysql_select_db("$dbname")or die("cannot select DB");

?>
<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
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
    <link rel="stylesheet" type="text/css" href="css/googleAPIFamilyCabin.css">
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
    <link rel="shortcut icon" href="images/icons/favicon.ico">
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
    <style>
        .segmented_control{
            width: 100px;
            height: 35px;
            background-color: #FBFBFB;
            color: #222;
            border: 1px solid #E6E6E6;
            outline: 0px;
            float: left;
            cursor: pointer;
        }
        .segmented_control_selected{
            width: 100px;
            height: 35px;
            background-color: #22AEFF;
            color: #FFF;
            border: 0px solid #E6E6E6;
            outline: 0px;
            float: left;
            cursor: default;
        }
    </style>

  <body style="background: #F9F9F9;">
      
      <div id="telemedicine_status" title="Consultation Status" style="display: none; width: 400px; height: 150px;">
          <h4 style="color: #666; text-align: center;">Consultation Started</h4><br/>
          <p style="color: #666; text-align: center;">Call Status: <span id="call_status_label">Connecting...</span></p>
      
      </div>
      
      <!--Header Start-->
	<div class="header" >
  		
           <a href="index.html" class="logo"><h1>Health2me</h1></a>
           
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
                <h3 style="text-align: center;">PDMST Dashboard</h3>
                <div style="width: 400px; height: 170px; float: right; margin-right: 40px; margin-top: 30px;">
                    <div style="width: 200px; height: 100px; float: left;">
                        <h4 style="color: #8A8A8A; text-align: center; font-size: 32px;">Consultations</h4>
                        <p id="number_of_consultations" style="color: #22AEFF; font-size: 42px; font-weight: bold; text-align: center; margin-top: 25px;"></p>
                    </div>
                    <div style="width: 200px; height: 100px; float: left;">
                        <h4 style="color: #8A8A8A; text-align: center; font-size: 32px;">Users</h4>
                        <p id="number_of_users" style="color: #5EB529; font-size: 42px; font-weight: bold; text-align: center; margin-top: 25px;"></p>
                    </div>
                    <div style="width: 200px; height: 35px; margin-top: 25px; float: right; margin-right: 20px;">
                        <button id="toggle_1" class="segmented_control_selected" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;">Actual</button>
                        <button id="toggle_2" class="segmented_control" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;">Cumulative</button>
                    </div>
                </div>
                <div style="width: 500px; height: 100px; margin-left: 40px; margin-top: 30px;">
                    <div style="width: 500px; height: 50px;">
                        <label style="margin-right: 30px; float: left; margin-top: 5px;">Scope: </label>
                        <select id="scope_select" style="float: left;">
                            <option value="Global" selected>Global</option>
                            <?php 
                                $res = mysql_query("SELECT DISTINCT GrantAccess FROM usuarios");
                                while($row = mysql_fetch_assoc($res))
                                {
                                    
                                    if(strlen($row['GrantAccess']) > 0)
                                    {
                                        echo '<option value="'.$row['GrantAccess'].'">'.$row['GrantAccess'].'</option>';
                                    }
                                    else
                                    {
                                        echo '<option value="NULL">H2M</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div style="width: 500px; height: 50px;">
                        <label style="margin-right: 30px; float: left; margin-top: 5px;">Period: </label>
                        <select id="period_select" style="float: left;">
                            <option value="1" selected>Today</option>
                            <option value="2">This Week</option>
                            <option value="3">This Month</option>
                            <option value="4">This Year</option>
                        </select>
                    </div>
                </div>
                <div style="width: 210px; height: 30px; margin-left: 75px; margin-top: 45px; margin-bottom: -35px;">
                    <style>
                        .consultations_button{
                            width: 100px;
                            height: 30px;
                            background-color: #FBFBFB;
                            color: #222;
                            border: 1px solid #E6E6E6;
                            outline: 0px;
                            float: left;
                            cursor: pointer;
                            border-radius: 5px;
                        }
                        .consultations_button_selected{
                            width: 100px;
                            height: 30px;
                            background-color: #22AEFF;
                            color: #FFF;
                            border: 0px solid #E6E6E6;
                            outline: 0px;
                            float: left;
                            cursor: pointer;
                            border-radius: 5px;
                        }
                        .users_button{
                            width: 100px;
                            height: 30px;
                            background-color: #FBFBFB;
                            color: #222;
                            border: 1px solid #E6E6E6;
                            outline: 0px;
                            float: right;
                            cursor: pointer;
                            border-radius: 5px;
                        }
                        .users_button_selected{
                            width: 100px;
                            height: 30px;
                            background-color: #5EB529;
                            color: #FFF;
                            border: 0px solid #E6E6E6;
                            outline: 0px;
                            float: right;
                            cursor: pointer;
                            border-radius: 5px;
                        }
                    </style>
                    <button class="consultations_button_selected">Consultations</button>
                    <button class="users_button_selected">Users</button>
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
                <style>
                    .search_bar_button{
                        width: 70px;
                        height: 30px;
                        background-color: #F6F6F6;
                        outline: 0px;
                        border: 1px solid #E7E7E7;
                        color: #3A3A3A;
                        border-top-right-radius: 5px;
                        border-bottom-right-radius: 5px;
                    }
                </style>
                <div class="controls" style="float: right; width: 500px; margin-right: 50px; margin-top: 80px;">
                    <input placeholder="name or surname or email" class="span7" id="search_bar" style="margin-left:50px;float: left; width: 370px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" size="16" type="text">
                    <button id="search_bar_clear_button" style="height: 18px; width: 18px; border-radius: 15px; border: 0px solid #FFF; outline: 0px; background-color: #E6E6E6; color: #FFF; font-size: 10px; padding: 0px; margin-top: 6px; margin-left: -105px; visibility: hidden;">X</button>
                    <button class="search_bar_button" style="float: left; width: 80px;" id="search_bar_button" lang="en">Search</button>
                </div>
                <div style="width: 400px; height: 35px; margin-left: 50px; margin-top: 80px;margin-right">
                    <button id="segment_1" class="segmented_control_selected" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;">Customers</button>
                    <button id="segment_2" class="segmented_control">Consultations</button>
                    <button id="segment_3" class="segmented_control">Doctors</button>
					<button id="segment_4" class="segmented_control" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;">New Users</button>
                </div>
                <div id="customers_table" style="width: 900px; margin: auto; margin-top: 15px;">
                    <table>
                        <tr>
                            <th style="background-color: #6ECCFF; width: 225px; height: 25px;">
                                Name
                                <button id="caret_button_customers_Surname,Name" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;">
                                Calls
                                <button id="caret_button_customers_numberOfPhoneCalls" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 180px; height: 25px;">
                                Video Conferences
                                <button id="caret_button_customers_videoconferences" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;">
                                Sign Up Date
                                <button id="caret_button_customers_SignUpDate" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;">
                                Plan
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
                            <th style="background-color: #22AEFF; width: 155px; height: 25px;">
                                Doctor
                                <button id="caret_button_consultations_doctorSurname,doctorName" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 155px; height: 25px;">
                                Patient
                                <button id="caret_button_consultations_patientSurname,patientName" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 180px; height: 25px;">
                                Time
                                <button id="caret_button_consultations_DateTime" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 50px; height: 25px;">
                                Type
                                <button id="caret_button_consultations_Type" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 50px; height: 25px;">
                                Length
                                <button id="caret_button_consultations_Length" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 100px; height: 25px;">
                                Status
                                <button id="caret_button_consultations_Status" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 70px; height: 25px;">
                                Notes
                                <!--<button id="caret_button_consultations_Data_File" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px; "></button>-->
                            </th>
                            <th style="background-color: #22AEFF; width: 70px; height: 25px;">
                                Summ.
                                <!--<button id="caret_button_consultations_Summary_PDF" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>-->
                            </th>
                            <th style="background-color: #22AEFF; width: 70px; height: 25px;">
                                Rec.
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
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;">
                                Name
                                <button id="caret_button_doctors_name" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;">
                                Calls
                                <button id="caret_button_doctors_calls" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 180px; height: 25px;">
                                Video Conferences
                                <button id="caret_button_doctors_cideoconferences" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;">
                                Patients
                                <button id="caret_button_doctors_numberOfConsultedPatients" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 150px; height: 25px;">
                                Summaries Edited
                                <button id="caret_button_doctors_summaries" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;">
                                PDFs Created
                                <button id="caret_button_doctors_reportsCreated" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
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
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;">
                                Name
                                <button id="caret_button_newusers_name" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;">
                                Phone
                                <button id="caret_button_newusers2_telefono" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 180px; height: 25px;">
                                Video Conferences
                                <button id="caret_button_doctors_cideoconferences" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;">
                                Email
                                <button id="caret_button_doctorsnewusers3_email" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 150px; height: 25px;">
                                Summaries Edited
                                <button id="caret_button_doctors_summaries" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;">
                                Creation Date
                                <button id="caret_button_doctorsnewusers4_signupdate" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
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
                    <style>
                        .page_button{
                            width: 30px; 
                            height: 30px; 
                            border-radius: 20px; 
                            outline: 0px; 
                            background-color: #22AEFF; 
                            color: #FFF; 
                            border: 0px solid #FFF; 
                            float: left;
                        }
                        .page_button:disabled{
                            background-color: #6ECCFF;
                            cursor: default;
                        }
                    </style>
                    
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
                <style>
                    #medical_availability_container{
                        width: 100%;
                    }
                    .medical_availability_row{
                        width: 100%;
                        height: 30px;
                        margin-bottom: 25px;
                    }
                    .medical_availability_row_name{
                        width: 13%;
                        padding-left: 2%;
                        height: 25px;
                        padding-top: 5px;
                        float: left;
                    }
                    .medical_availability_row_timeslots{
                        width: 83%;
                        padding-left: 2%;
                        height: 25px;
                        padding-top: 5px;
                        float: left;
                    }
                    .timeCellIndicator{
                        border-style: solid; 
                        border-width: 1px;
                        border-color: #BABABA;
                        height: 15px; 
                        width: 32px;
                    }
                    .timeCellIndicatorOff{
                        height: 15px; 
                        width: 8px;
                        margin-top: -1px;
                        float: left;
                    }
                    .timeCellIndicatorOn{
                        background-color: #3d94f6;
                        height: 15px;
                        width: 8px;
                        float: left;
                    }
					
					.timeCellIndicatorTempOn{
                        background-color: #3d94f6;
                        height: 15px;
                        width: 8px;
                        float: left;
                    }
					
                    .timeCellIndicatorMarked{
                        background-color: #A5A5A5;
                        border-style: solid; 
                        border-width: 1px;
                        border-color: #B2B2B2;
                        border-right-color: #FFFFFF;
                        height: 15px;
                        margin-top: -1px;
                        width: 32px;
                    }
                    .timeLabel{
                        color: #CACACA; 
                        font-size: 10px; 
                        margin-left: -10px;
                    }
                    .timeCell{
                        height: 40px; 
                        width: 32px; 
                        float: left;
                        margin-left: 1px;
                    }
                    #medical_availability_show_doctors{
                       float: right; 
                        width: 150px; 
                        height: 30px; 
                        margin-right: 40px; 
                        margin-top: -95px; 
                        background-color: #F6F6F6; 
                        border: 1px solid #999; 
                        outline: none; 
                        border-radius: 5px; 
                        color: #333;
                    }
                    #medical_availability_show_doctors:hover{
                        background-color: #22AEFF;
                        color: #FFF;
                        border: 1px solid #22AEFF; 
                    }
                    #launch_telemedicine_button{
                        width: 150px; 
                        height: 30px; 
                        margin: auto; 
                        background-color: #22AEFF; 
                        border: 1px solid #22AEFF; 
                        outline: none; 
                        border-radius: 5px; 
                        color: #FFF;
                        font-size: 20px;
                    }
                    #launch_telemedicine_button:disabled{
                        background-color: #EEE; 
                        border: 1px solid #EEE;
                        cursor: default;
                    }
                </style>
                <h2 style="text-align: center; color: #666; font-size: 22px;">Medical Availability</h2>
                <div style="width: 300px; height: 50px; margin-top: 20px; margin-bottom: 43px; margin-left: 20px;">
                    <label style="margin-right: 30px; float: left; margin-top: 5px;">Period: </label>
                    <select id="period_select_medical_availability" style="float: left;">
                        <option value="1" selected>Today</option>
                        <option value="2">This Week</option>
                        <option value="3">This Month</option>
                    </select>
                </div>
                <button id="medical_availability_show_doctors">Show All Doctors</button>
                <h2 id="availability_label" style="text-align: center; color: #666; font-size: 22px; margin-top: -50px;">October 1, 2014</h2>
                <div id="medical_availability_container">
                
                </div>
                <hr/>
                <h2 style="text-align: center; color: #666; font-size: 22px;">Launch Consultation</h2>
                <div style="width: 150px; height: 30px; margin: auto; margin-top: 40px; margin-bottom: -40px;">
                    <button id="launch_telemedicine_button" disabled>Launch</button>
                </div>
                <div style="width: 100%; height: 100px;">
                    <div style="float: left; width: 50%; height: 50px; text-align: center;">
                        <h4 style="color: #444; font-size: 22px;">Doctor Selected:</h4>
                        <p id="selected_doctor_label" style="color: #999; font-size: 18px;">None</p>
                    </div>
                    <div style="float: left; width: 50%; height: 50px; text-align: center;">
                        <h4 style="color: #444; font-size: 22px;">Patient Selected:</h4>
                        <p id="selected_user_label" style="color: #999; font-size: 18px;">None</p>
                    </div>
                    
                </div>
                <style>
                    .search_users_bar_button{
                        width: 70px;
                        height: 30px;
                        background-color: #FAFAFA;
                        outline: 0px;
                        border: 1px solid #E7E7E7;
                        color: #7A7A7A;
                        border-top-right-radius: 5px;
                        border-bottom-right-radius: 5px;
                    }
    
                </style>
                <div style="width: 100%; height: 600px;">
                    <div style="float: left; width: 50%; height: 400px; margin: 0px; border-right: 1px solid #F5F5F5; display: block;">
                        <div class="controls" style="margin-left: 100px; margin-top: 10px; width: 400px; margin-bottom: 20px;">
                            <input class="span7" id="search_doctors_bar" style="float: left; width: 270px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" placeholder="Search Doctor (Name or Email)" size="16" type="text">
                            <button class="search_users_bar_button" style="float: left;" id="search_doctors_bar_button" lang="en">Search</button>
                        </div>
                        <div id="search_doctors_results" style="width: 340px; height: 350px; margin-top: 50px; margin-left: 100px; overflow-y: scroll;"></div>
                    </div>
                    <div style="float: left; width: 49%; height: 400px; margin: 0px; display: block;">
                        <div class="controls" style="margin-left: 80px; margin-top: 10px; width: 400px; margin-bottom: 20px;">
                            <input class="span7" id="search_users_bar" style="float: left; width: 270px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" placeholder="Search Patient (Name or Email)" size="16" type="text">
                            <button class="search_users_bar_button" style="float: left;" id="search_users_bar_button" lang="en">Search</button>
                        </div>
                        <div id="search_users_results" style="width: 340px; height: 350px; margin-top: 50px; margin-left: 80px; overflow-y: scroll;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script>
        $(document).ready(function(){
            var day_consultations_data = 
                {
                    label: "Consultations",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "#22AEFF",
                    pointColor: "#22AEFF",
                    pointStrokeColor: "#22AEFF",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                };
            var day_users_data = 
                {
                    label: "Users",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "#5EB529",
                    pointColor: "#5EB529",
                    pointStrokeColor: "#5EB529",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                };
            var day_consultations_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var day_users_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var day_chart_data = {
                labels: ["12:00 AM", "1:00 AM", "2:00 AM", "3:00 AM", "4:00 AM", "5:00 AM", "6:00 AM", "7:00 AM", "8:00 AM", "9:00 AM", "10:00 AM", "11:00 AM", "12:00 PM", "1:00 PM", "2:00 PM", "3:00 PM", "4:00 PM", "5:00 PM", "6:00 PM", "7:00 PM", "8:00 PM", "9:00 PM", "10:00 PM", "11:00 PM"],
                datasets: [
                    {
                        label: "Consultations",
                        fillColor: "rgba(220,220,220,0.2)",
                        strokeColor: "#22AEFF",
                        pointColor: "#22AEFF",
                        pointStrokeColor: "#22AEFF",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    {
                        label: "Users",
                        fillColor: "rgba(151,187,205,0.2)",
                        strokeColor: "#5EB529",
                        pointColor: "#5EB529",
                        pointStrokeColor: "#5EB529",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(151,187,205,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    }
                ]
            };
            var week_consultations_data = [0, 0, 0, 0, 0, 0, 0];
            var week_users_data = [0, 0, 0, 0, 0, 0, 0];
            var week_chart_data = {
                labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                datasets: [
                    {
                        label: "Consultations",
                        fillColor: "rgba(220,220,220,0.2)",
                        strokeColor: "#22AEFF",
                        pointColor: "#22AEFF",
                        pointStrokeColor: "#22AEFF",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [0, 0, 0, 0, 0, 0, 0]
                    },
                    {
                        label: "Users",
                        fillColor: "rgba(151,187,205,0.2)",
                        strokeColor: "#5EB529",
                        pointColor: "#5EB529",
                        pointStrokeColor: "#5EB529",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(151,187,205,1)",
                        data: [0, 0, 0, 0, 0, 0, 0]
                    }
                ]
            };
            var month_consultations_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var month_users_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var month_chart_data = {
                labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"],
                datasets: [
                    {
                        label: "Consultations",
                        fillColor: "rgba(220,220,220,0.2)",
                        strokeColor: "#22AEFF",
                        pointColor: "#22AEFF",
                        pointStrokeColor: "#22AEFF",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    {
                        label: "Users",
                        fillColor: "rgba(151,187,205,0.2)",
                        strokeColor: "#5EB529",
                        pointColor: "#5EB529",
                        pointStrokeColor: "#5EB529",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(151,187,205,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    }
                ]
            };
            var year_consultations_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var year_users_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var year_chart_data = {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [
                    {
                        label: "Consultations",
                        fillColor: "rgba(220,220,220,0.2)",
                        strokeColor: "#22AEFF",
                        pointColor: "#22AEFF",
                        pointStrokeColor: "#22AEFF",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    {
                        label: "Users",
                        fillColor: "rgba(151,187,205,0.2)",
                        strokeColor: "#5EB529",
                        pointColor: "#5EB529",
                        pointStrokeColor: "#5EB529",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(151,187,205,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    }
                ]
            };
            var day_ctx = $("#day_chart").get(0).getContext("2d");
            var Day_Chart = new Chart(day_ctx).Line(day_chart_data, {bezierCurve: false, responsive: true});
            var week_ctx = $("#week_chart").get(0).getContext("2d");
            var Week_Chart = new Chart(week_ctx).Line(week_chart_data, {bezierCurve: false});
            var month_ctx = $("#month_chart").get(0).getContext("2d");
            var Month_Chart = new Chart(month_ctx).Line(month_chart_data, {bezierCurve: false});
            var year_ctx = $("#year_chart").get(0).getContext("2d");
            var Year_Chart = new Chart(year_ctx).Line(year_chart_data, {bezierCurve: false});
            var user_values = [{}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}];
            var current_table = 1;
            var current_column = 'Surname,Name';
            var ascending = true;
            var current_page = 1;
            var data_type = 1;
            var show_consultations = true;
            var show_users = true;
            
            function reload_chart(chart)
            {
                if(chart == 1) // today
                {
                    //Day_Chart.clear();
                    if(show_users)
                    {
                        for(var k = 0; k < Day_Chart.datasets[1].points.length; k++)
                        {
                            Day_Chart.datasets[1].points[k].value = day_users_data[k];
                        }
                    }
                    else
                    {
                        for(var k = 0; k < Day_Chart.datasets[1].points.length; k++)
                        {
                            Day_Chart.datasets[1].points[k].value = 0;
                        }
                    }
                    if(show_consultations)
                    {
                        for(var k = 0; k < Day_Chart.datasets[0].points.length; k++)
                        {
                            Day_Chart.datasets[0].points[k].value = day_consultations_data[k];
                        }
                    }
                    else
                    {
                        for(var k = 0; k < Day_Chart.datasets[0].points.length; k++)
                        {
                            Day_Chart.datasets[0].points[k].value = 0;
                        }
                    }
                    Day_Chart.update();
                    $("#day_chart").css("display", "block");
                }
                else if(chart == 2) // this week
                {
                    Week_Chart.clear();
                    if(show_users)
                    {
                        for(var k = 0; k < Week_Chart.datasets[1].points.length; k++)
                        {
                            Week_Chart.datasets[1].points[k].value = week_users_data[k];
                        }
                    }
                    else
                    {
                        for(var k = 0; k < Week_Chart.datasets[1].points.length; k++)
                        {
                            Week_Chart.datasets[1].points[k].value = 0;
                        }
                    }
                    if(show_consultations)
                    {
                        for(var k = 0; k < Week_Chart.datasets[0].points.length; k++)
                        {
                            Week_Chart.datasets[0].points[k].value = week_consultations_data[k];
                        }
                    }
                    else
                    {
                        for(var k = 0; k < Week_Chart.datasets[0].points.length; k++)
                        {
                            Week_Chart.datasets[0].points[k].value = 0;
                        }
                    }
                    Week_Chart.update();
                    $("#week_chart").css("display", "block");
                }
                else if(chart == 3) // this month
                {
                    Month_Chart.clear();
                    if(show_users)
                    {
                        for(var k = 0; k < Month_Chart.datasets[1].points.length; k++)
                        {
                            Month_Chart.datasets[1].points[k].value = month_users_data[k];
                        }
                    }
                    else
                    {
                        for(var k = 0; k < Month_Chart.datasets[1].points.length; k++)
                        {
                            Month_Chart.datasets[1].points[k].value = 0;
                        }
                    }
                    if(show_consultations)
                    {
                        for(var k = 0; k < Month_Chart.datasets[0].points.length; k++)
                        {
                            Month_Chart.datasets[0].points[k].value = month_consultations_data[k];
                        }
                    }
                    else
                    {
                        for(var k = 0; k < Month_Chart.datasets[0].points.length; k++)
                        {
                            Month_Chart.datasets[0].points[k].value = 0;
                        }
                    }
                    Month_Chart.update();
                    $("#month_chart").css("display", "block");
                }
                else if(chart == 4) // this year
                {
                    Year_Chart.clear();
                    if(show_users)
                    {
                        for(var k = 0; k < Year_Chart.datasets[1].points.length; k++)
                        {
                            Year_Chart.datasets[1].points[k].value = year_users_data[k];
                        }
                    }
                    else
                    {
                        for(var k = 0; k < Year_Chart.datasets[1].points.length; k++)
                        {
                            Year_Chart.datasets[1].points[k].value = 0;
                        }
                    }
                    if(show_consultations)
                    {
                        for(var k = 0; k < Year_Chart.datasets[0].points.length; k++)
                        {
                            Year_Chart.datasets[0].points[k].value = year_consultations_data[k];
                        }
                    }
                    else
                    {
                        for(var k = 0; k < Year_Chart.datasets[0].points.length; k++)
                        {
                            Year_Chart.datasets[0].points[k].value = 0;
                        }
                    }
                    Year_Chart.update();
                    $("#year_chart").css("display", "block");
                }
            }
            
            // this function gets data then loads the charts and the users / consultations numbers on top depending on scope and period
            function get_main_data(scope, period)
            {
                
                for(var i = 0; i < 31; i++)
                {
                    user_values[i] = {};
                }
                $("#day_chart").css("display", "none");
                $("#week_chart").css("display", "none");
                $("#month_chart").css("display", "none");
                $("#year_chart").css("display", "none");
                $("#chart_loader").css("display", "block");
                $.post("getConsultationsAndUsersData.php", {period: period, scope: scope}, function(data, status)
                {
                    console.log(data);
                    var d = JSON.parse(data);
                    $("#chart_loader").css("display", "none");
                    var consultations = d["consultations"];
                    var users = d["users"];
                    var num_consultations = 0;
                    var num_users = d["numUsers"];
                    if(period == 1) // today
                    {
                        Day_Chart.clear();
                        var cumulative = 0;
                        for(var k = 0; k < Day_Chart.datasets[1].points.length; k++)
                        {
                            if(k < users.length)
                            {
                                if(data_type == 2)
                                {
                                    cumulative += users[k];
                                    day_users_data[k] = cumulative;
                                }
                                else
                                {
                                    
                                    day_users_data[k] = users[k];
                                }
                            }
                            else
                            {
                                if(data_type == 2)
                                {
                                    day_users_data[k] = cumulative;
                                }
                                else
                                {
                                    day_users_data[k] = 0;
                                }
                            }
                        }
                        cumulative = 0;
                        for(var k = 0; k < Day_Chart.datasets[0].points.length; k++)
                        {
                            if(k < consultations.length)
                            {
                                if(data_type == 2)
                                {
                                    cumulative += consultations[k];
                                    day_consultations_data[k] = cumulative;
                                }
                                else
                                {
                                    
                                    day_consultations_data[k] = consultations[k];
                                }
                                num_consultations += consultations[k];
                            }
                            else
                            {
                                if(data_type == 2)
                                {
                                    day_consultations_data[k] = cumulative;
                                }
                                else
                                {
                                    day_consultations_data[k] = 0;
                                }
                            }
                        }
                        reload_chart(1);
                        $("#day_chart").css("display", "block");
                    }
                    else if(period == 2) // this week
                    {
                        Week_Chart.clear();
                        var cumulative = 0;
                        for(var k = 0; k < Week_Chart.datasets[1].points.length; k++)
                        {
                            if(k < users.length)
                            {
                                if(data_type == 2)
                                {
                                    cumulative += users[k];
                                    week_users_data[k] = cumulative;
                                }
                                else
                                {
                                    
                                    week_users_data[k] = users[k];
                                }
                            }
                            else
                            {
                                if(data_type == 2)
                                {
                                    week_users_data[k] = cumulative;
                                }
                                else
                                {
                                    week_users_data[k] = 0;
                                }
                            }
                        }
                        cumulative = 0;
                        for(var k = 0; k < Week_Chart.datasets[0].points.length; k++)
                        {
                            if(k < consultations.length)
                            {
                                if(data_type == 2)
                                {
                                    cumulative += consultations[k];
                                    week_consultations_data[k] = cumulative;
                                }
                                else
                                {
                                    
                                    week_consultations_data[k] = consultations[k];
                                }
                                num_consultations += consultations[k];
                            }
                            else
                            {
                                if(data_type == 2)
                                {
                                    week_consultations_data[k] = cumulative;
                                }
                                else
                                {
                                    week_consultations_data[k] = 0;
                                }
                            }
                        }
                        reload_chart(2);
                        $("#week_chart").css("display", "block");
                    }
                    else if(period == 3) // this month
                    {
                        Month_Chart.clear();
                        var cumulative = 0;
                        for(var k = 0; k < Month_Chart.datasets[1].points.length; k++)
                        {
                            if(k < users.length)
                            {
                                if(data_type == 2)
                                {
                                    cumulative += users[k];
                                    month_users_data[k] = cumulative;
                                }
                                else
                                {
                                    
                                    month_users_data[k] = users[k];
                                }
                            }
                            else
                            {
                                if(data_type == 2)
                                {
                                    month_users_data[k] = cumulative;
                                }
                                else
                                {
                                    month_users_data[k] = 0;
                                }
                            }
                        }
                        cumulative = 0;
                        for(var k = 0; k < Month_Chart.datasets[0].points.length; k++)
                        {
                            if(k < consultations.length)
                            {
                                if(data_type == 2)
                                {
                                    cumulative += consultations[k];
                                    month_consultations_data[k] = cumulative;
                                }
                                else
                                {
                                    
                                    month_consultations_data[k] = consultations[k];
                                }
                                num_consultations += consultations[k];
                            }
                            else
                            {
                                if(data_type == 2)
                                {
                                    month_consultations_data[k] = cumulative;
                                }
                                else
                                {
                                    month_consultations_data[k] = 0;
                                }
                            }
                        }
                       reload_chart(3);
                        $("#month_chart").css("display", "block");
                    }
                    else if(period == 4) // this year
                    {
                        Year_Chart.clear();
                        var cumulative = 0;
                        for(var k = 0; k < Year_Chart.datasets[1].points.length; k++)
                        {
                            if(k < users.length)
                            {
                                if(data_type == 2)
                                {
                                    cumulative += users[k];
                                    year_users_data[k] = cumulative;
                                }
                                else
                                {
                                    
                                    year_users_data[k] = users[k];
                                }
                            }
                            else
                            {
                                if(data_type == 2)
                                {
                                    year_users_data[k] = cumulative;
                                }
                                else
                                {
                                    year_users_data[k] = 0;
                                }
                            }
                        }
                        cumulative = 0;
                        for(var k = 0; k < Year_Chart.datasets[0].points.length; k++)
                        {
                            if(k < consultations.length)
                            {
                                if(data_type == 2)
                                {
                                    cumulative += consultations[k];
                                    year_consultations_data[k] = cumulative;
                                }
                                else
                                {
                                    
                                    year_consultations_data[k] = consultations[k];
                                }
                                num_consultations += consultations[k];
                            }
                            else
                            {
                                if(data_type == 2)
                                {
                                    year_consultations_data[k] = cumulative;
                                }
                                else
                                {
                                    year_consultations_data[k] = 0;
                                }
                            }
                        }
                        reload_chart(4);
                        $("#year_chart").css("display", "block");
                    }
                    $("#number_of_consultations").text(num_consultations);
                    $("#number_of_users").text(num_users);
                    
                });
            }
            
            function checkSummary(summary_value){
                if (summary_value == "Packages_Encrypted/"){
                    return "N/A";  
                } 
                summary_link='<a class="summary_link" href="' +summary_value+ '">'+'Show</a>'
                return summary_link;  
            }    
			 
            
            function load_table(table, sort, ascending, page, scope, period, search)
            {
                var back_end_file = "";
                if(table == 1)
                {
                    back_end_file= "getCustomerData2";
                }
                else if(table == 2)
                {
                    back_end_file = "getConsultationsData";
                }
                else if(table == 3)
                {
                    back_end_file = "getDoctorsData";
                }
				else if(table == 4)
                {
                    back_end_file = "getNewUserData";
                }
                var sort_order = 'asc';
                if(!ascending)
                {
                    sort_order = 'desc';
                }
                $('tr').each(function(index, element)
                {
                    if($(element).children().eq(0).is('td'))
                    {
                        $(element).remove();
                    }
                });
                $("#table_loader").css("display", "block");
                $.post(back_end_file+".php", {scope: scope, period: period, sortOrder: sort_order, sortField: sort, searchField: search, currentPage: page}, function(data, status)
                {
                    console.log(data);
                    var pre_info = JSON.parse(data);
                    var num_pages = pre_info['pages'];
                    if(page == num_pages)
                    {
                        $("#page_button_right").attr("disabled", "disabled");
                    }
                    else
                    {
                        $("#page_button_right").removeAttr("disabled");
                    }
                    if(page == 1)
                    {
                        $("#page_button_left").attr("disabled", "disabled");
                    }
                    else
                    {
                        $("#page_button_left").removeAttr("disabled");
                    }
                    if(num_pages > 0)
                    {
                        $("#page_label").text(page + " of " + num_pages);
                    }
                    else
                    {
                        $("#page_label").text("0 of 0");
                        $("#page_button_right").attr("disabled", "disabled");
                        $("#page_button_left").attr("disabled", "disabled");
                    }
                    
                    var html = '';
                    if(table == 1) // customers table
                    {
                        var info = pre_info['customers'];
                        for(var i = 0; i < info.length; i++)
                        {
                            var color = "#F5F5F5";
                            if(i % 2 == 1)
                            {
                                color = "#E9E9E9";
                            }
                            html += '<tr><td style="background-color: '+color+'; width: 225px; height: 15px; text-align: center;">';
                            html += '<a href="patientdetailMED-new.php?IdUsu='+info[i]['Identif']+'" style="color: #5EB529">'+info[i]['Name']+' '+info[i]['Surname']+'</a>';
                            html += '</td><td style="background-color: '+color+'; width: 225px; height: 15px; text-align: center;">';
                            html += info[i]['numberOfPhoneCalls'];
                            //html += '</td><td style="background-color: '+color+'; width: 150px; height: 15px; text-align: center;">';
                            //html += info[i]['video_calls'];
                            html += '</td><td style="background-color: '+color+'; width: 225px; height: 15px; text-align: center;">';
                            html += info[i]['SignUpDate'];
                            html += '</td><td style="background-color: '+color+'; width: 225px; height: 15px; text-align: center;">';
                            html += info[i]['typeOfPlan'];
                            html += '</td></tr>';
                        }
                        $("#customers_table").children('table').eq(0).append(html);
                    }
                    else if(table == 2) // consultations table
                    {
                        var info = pre_info['consultations'];
                        for(var i = 0; i < info.length; i++)
                        {
                            var color = "#F5F5F5";
                            if(i % 2 == 1)
                            {
                                color = "#E9E9E9";
                            }
                            html += '<tr><td style="background-color: '+color+'; width: 130px; height: 15px; text-align: center;">';
                            html += info[i]['doctorName'] + ' ' + info[i]['doctorSurname'];
                            html += '</td><td style="background-color: '+color+'; width: 130px; height: 15px; text-align: center;">';
                            html += '<a href="patientdetailMED-new.php?IdUsu='+info[i]['pat_id']+'" style="color: #5EB529">'+info[i]['patientName'] + ' ' + info[i]['patientSurname']+'</a>';
                            html += '</td><td style="background-color: '+color+'; width: 130px; height: 15px; text-align: center;">';
                            html += info[i]['DateTime'];
                            html += '</td><td style="background-color: '+color+'; width: 130px; height: 15px; text-align: center;">';
                            html += info[i]['Type'];
                            html += '</td><td style="background-color: '+color+'; width: 130px; height: 15px; text-align: center;">';
                            html += info[i]['Length'];
                            html += " sec";
                            html += '</td><td style="background-color: '+color+'; width: 130px; height: 15px; text-align: center;">';
                            html += info[i]['Status'];
                            html += '</td><td style="background-color: '+color+'; width: 40px; height: 15px; text-align: center;">';
                            html += '<a class="notes_link" href="' + info[i]['Data_File'] /*'Packages_Encrypted/eML19870117106ca6230dcfad60b62752a6f5d6a27d16.pdf'*/ + '">Show</a>';
                            html += '</td><td style="background-color: '+color+'; width: 40px; height: 15px; text-align: center;">';

                            //html += '<a class="summary_link" href="' + info[i]['Summary_PDF']/*'Packages_Encrypted/eML1987011710482f859a63220d88fb88688ba4fb7fc3.pdf' */+ '">Show</a>';
							
                            html += checkSummary(info[i]['Summary_PDF']);   
                            
                            html += '</td><td style="background-color: '+color+'; width: 40px; height: 15px; text-align: center;">';
							
							function doesFileExist(url)
							{
								var http = new XMLHttpRequest();
								http.open('HEAD', url, false);
								http.send();
							return http.status!=404;
							}
							
							var resultxyz = doesFileExist('http://beta.health2.me/recordings/'+info[i]['Recorded_File']);
 
							if (resultxyz == true) {
							html += '<a class="recording_link" href="'+info[i]['Recorded_File']+'">Show</a>';
							} else {
							html += 'N/A';
							}
							
                            html += '</td></tr>';
                        }
                        $("#consultations_table").children('table').eq(0).append(html);
                        $(".recording_link").on('click', function(e)
                        {
                            e.preventDefault();
                            $.post('pdmst_dashboard_decrypt.php', {file: $(this).attr("href"), recording: true}, function(data, status)
                            {
                                console.log(data);
                                $("#recordingModal").html('<iframe src="'+/*'http://com.twilio.music.classical.s3.amazonaws.com/BusyStrings.mp3'*/data+'" style="width:400px;height:400px;"></iframe>');
                                $("#recordingModal").dialog({bigframe: true, width: 406, height: 440, modal: true});
                                $.post('pdmst_dashboard_decrypt.php', {erase: true, erase_file: data}, function(data, status){console.log(data);});
                            });
                        });
                        $("#recordingModal").on( "dialogclose", function( event, ui ) {$("#recordingModal").html("");} );
                        $(".summary_link").on('click', function(e)
                        {
                            e.preventDefault();
                            $.post('pdmst_dashboard_decrypt.php', {file: $(this).attr("href")}, function(data, status)
                            {
                                console.log(data);
                                $("#summaryModal").html('<iframe src="'+data+'" style="width:680px;height:800px;"></iframe>');
                                $("#summaryModal").dialog({bigframe: true, width: 680, height: 800, modal: true});
                                $.post('pdmst_dashboard_decrypt.php', {erase: true, erase_file: data}, function(data, status){console.log(data);});
                            });
                        });
                        $(".notes_link").on('click', function(e)
                        {
                            e.preventDefault();
                            $.post('pdmst_dashboard_decrypt.php', {file: $(this).attr("href")}, function(data, status)
                            {
                                console.log(data);
                                $("#notesModal").html('<iframe src="'+data+'" style="width:600px;height:800px;"></iframe>');
                                $("#notesModal").dialog({bigframe: true, width: 605, height: 400, modal: true});
                                $.post('pdmst_dashboard_decrypt.php', {erase: true, erase_file: data}, function(data, status){console.log(data);});

                            });
                        });
                    }
                    else if(table == 3) // doctors table
                    {
                        var info = pre_info['doctors'];
                        
                        for(var i = 0; i < info.length; i++)
                        {
                            var color = "#F5F5F5";
                            if(i % 2 == 1)
                            {
                                color = "#E9E9E9";
                            }
                            html += '<tr><td style="background-color: '+color+'; width: 225px; height: 15px; text-align: center;">';
                            html += info[i]['name'];
                            html += '</td><td style="background-color: '+color+'; width: 225px; height: 15px; text-align: center;">';
                            html += info[i]['calls'];
                            //html += '</td><td style="background-color: '+color+'; width: 150px; height: 15px;">';
                            //html += info[i]['video_calls'];
                            html += '</td><td style="background-color: '+color+'; width: 225px; height: 15px; text-align: center;">';
                            html += info[i]['numberOfConsultedPatients'];
                            //html += '</td><td style="background-color: '+color+'; width: 150px; height: 15px;">';
                            //html += info[i]['summaries'];
                            html += '</td><td style="background-color: '+color+'; width: 225px; height: 15px; text-align: center;">';
                            html += info[i]['reportsCreated'];
                            html += '</td></tr>';
                        }
                        $("#doctors_table").children('table').eq(0).append(html);
                    }
					else if(table == 4) // doctors table
                    {
                        var info = pre_info['newusers'];
                        
                        for(var i = 0; i < info.length; i++)
                        {
                            var color = "#F5F5F5";
                            if(i % 2 == 1)
                            {
                                color = "#E9E9E9";
                            }
                            html += '<tr><td style="background-color: '+color+'; width: 225px; height: 15px; text-align: center;">';
                            html += info[i]['name']+' '+info[i]['surname'];
                            html += '</td><td style="background-color: '+color+'; width: 225px; height: 15px; text-align: center;">';
                            html += info[i]['telefono'];
                            //html += '</td><td style="background-color: '+color+'; width: 150px; height: 15px;">';
                            //html += info[i]['video_calls'];
                            html += '</td><td style="background-color: '+color+'; width: 225px; height: 15px; text-align: center;">';
                            html += info[i]['email'];
                            //html += '</td><td style="background-color: '+color+'; width: 150px; height: 15px;">';
                            //html += info[i]['summaries'];
                            html += '</td><td style="background-color: '+color+'; width: 225px; height: 15px; text-align: center;">';
                            html += info[i]['signupdate'];
                            html += '</td></tr>';
                        }
                        $("#newusers_table").children('table').eq(0).append(html);
                    }
                    $("#table_loader").css("display", "none");
                    
                });
            }
            
            // load doctor availability information
            var medical_availability_show_doctors_value = 0;
            var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            function get_doctors_availability()
            {
                console.log($("#scope_select").val());
                $.post("getAllDoctorsTimeslots.php", {period: $("#period_select_medical_availability").val(), scope: $("#scope_select").val()}, function(data, status)
                {
                    var d = JSON.parse(data);
                    var html = '';
                    var aggregated = new Array();
                    var cell_width = 32;
                    var indicator_width = 8;
                    var num_cells = 24;
                    var label_margin = -10;
                    var num_slots = 96;
                    
                    var today = new Date();
                    //var tomorrow = new Date();
                    //tomorrow.setDate(today.getDate()+1);
                    if($("#period_select_medical_availability").val() == 1)
                    {
                        $("#availability_label").text(months[today.getMonth()]+" "+today.getDate()+", "+today.getFullYear());
                    }
                    if($("#period_select_medical_availability").val() == 2)
                    {
                        cell_width = 36;
                        indicator_width = 9;
                        num_cells = 21;
                        num_slots = 84;
                        var sunday = new Date();
                        
                        sunday.setDate(sunday.getDate() - sunday.getDay());
                        
                        var saturday = new Date(sunday.valueOf());
                        saturday.setDate(saturday.getDate() + 6);
                        
                        $("#availability_label").text(months[sunday.getMonth()]+" "+sunday.getDate()+", "+sunday.getFullYear()+" - "+months[saturday.getMonth()]+" "+saturday.getDate()+", "+saturday.getFullYear());
                    }
                    else if($("#period_select_medical_availability").val() == 3)
                    {
                        cell_width = 24;
                        indicator_width = 6;
                        num_cells = 31;
                        label_margin = -3;
                        num_slots = 124;
                        $("#availability_label").text(months[today.getMonth()]+" "+today.getFullYear());
                    }
                    for(var k = 0; k < num_slots; k++)
                    {
                        aggregated.push(0);
                    }
                    for(var key in d)
                    {
                        html += '<div class="medical_availability_row"><div class="medical_availability_row_name">';
                        html += 'Dr. ' + key;
                        html += '</div><div class="medical_availability_row_timeslots">';
                        for(var i = 0; i < num_cells; i++)
                        {
                            html += '<div class="timeCell" style="width: '+cell_width+'px;" >';
                            html += '<div class="timeCellIndicator" style="width: '+cell_width+'px; ';
                            if(i == 0)
                            {
                                html += 'border-top-left-radius: 3px; border-bottom-left-radius: 3px; -webkit-border-top-left-radius: 3px; -moz-border-top-left-radius: 3px;  -webkit-border-bottom-left-radius: 3px; -moz-border-bottom-left-radius: 3px;"';
                            }
                            else if(i == (num_cells - 1))
                            {
                                html += 'border-top-right-radius: 3px; border-bottom-right-radius: 3px; -webkit-border-top-right-radius: 3px; -moz-border-top-right-radius: 3px;  -webkit-border-bottom-right-radius: 3px; -moz-border-bottom-right-radius: 3px;"';
                            }
                            html += '">';
                            if(d[key][(i * 4)] == 1)
                            {
                                if(aggregated[(i * 4)] < 10)
                                    aggregated[(i * 4)] += 1;
                                html += '<div class="timeCellIndicatorOn" style="width: '+indicator_width+'px;" ></div>';
                            }
                            else
                            {
                                html += '<div class="timeCellIndicatorOff" style="width: '+indicator_width+'px;"></div>';
                            }
                            if(d[key][(i * 4) + 1] == 1)
                            {
                                if(aggregated[(i * 4) + 1] < 10)
                                    aggregated[(i * 4) + 1] += 1;
                                html += '<div class="timeCellIndicatorOn" style="width: '+indicator_width+'px;"></div>';
                            }
                            else
                            {
                                html += '<div class="timeCellIndicatorOff" style="width: '+indicator_width+'px;"></div>';
                            }
                            if(d[key][(i * 4) + 2] == 1)
                            {
                                if(aggregated[(i * 4) + 2] < 10)
                                    aggregated[(i * 4) + 2] += 1;
                                html += '<div class="timeCellIndicatorOn" style="width: '+indicator_width+'px;"></div>';
                            }
                            else
                            {
                                html += '<div class="timeCellIndicatorOff" style="width: '+indicator_width+'px;"></div>';
                            }
                            if(d[key][(i * 4) + 3] == 1)
                            {
                                if(aggregated[(i * 4) + 3] < 10)
                                    aggregated[(i * 4) + 3] += 1;
                                html += '<div class="timeCellIndicatorOn" style="width: '+indicator_width+'px;"></div>';
                            }
                            else
                            {
                                html += '<div class="timeCellIndicatorOff" style="width: '+indicator_width+'px;"></div>';
                            }
                            html += '</div><span class="timeLabel" style="margin-left: '+label_margin+'px;">';
                            if($("#period_select_medical_availability").val() == 1)
                            {
                                if(i == 0 || i == 12)
                                {
                                    html += '12';
                                }
                                else if(i < 12)
                                {
                                    html += i;
                                }
                                else
                                {
                                    html += i - 12;
                                }
                                if(i < 12)
                                {
                                    html += ' am';
                                }
                                else
                                {
                                    html += ' pm';
                                }

                            }
                            else if($("#period_select_medical_availability").val() == 2)
                            {
                                if(i % 3 == 0)
                                {
                                    var day = Math.floor(i / 3);
                                    html += '<span style="color: #888">';
                                    if(day == 0)
                                    {
                                        html += 'Sun';
                                    }
                                    else if(day == 1)
                                    {
                                        html += 'Mon';
                                    }
                                    else if(day == 2)
                                    {
                                        html += 'Tues';
                                    }
                                    else if(day == 3)
                                    {
                                        html += 'Wed';
                                    }
                                    else if(day == 4)
                                    {
                                        html += 'Thurs';
                                    }
                                    else if(day == 5)
                                    {
                                        html += 'Fri';
                                    }
                                    else if(day == 6)
                                    {
                                        html += 'Sat';
                                    }
                                    html += '</span>';
                                }
                                else if(i % 3 == 1)
                                {
                                    html += '8 am';
                                }
                                else
                                {
                                    html += '4 pm';
                                }
                            }
                            else
                            {
                                html += i + 1;
                            }
                            html += '</span></div>';

                        }
                        html += '</div></div>';

                    }

                    // calculate aggregated value
                    var colors = new Array("D3E3F6", "ABCEF6", "7BB4F6", "3d94f6", "0073F6", "0057BA", "00397A", "00244D", "001023", "000000");
                    var pre_html = '<div class="medical_availability_row"><div class="medical_availability_row_name">';
                    pre_html += '<strong>Aggregated</strong>';
                    pre_html += '</div><div class="medical_availability_row_timeslots">';
                    for(var i = 0; i < num_cells; i++)
                    {
                        pre_html += '<div class="timeCell" style="width: '+cell_width+'px;">';
                        pre_html += '<div class="timeCellIndicator"  style="width: '+cell_width+'px; ';
                        if(i == 0)
                        {
                            pre_html += 'border-top-left-radius: 3px; border-bottom-left-radius: 3px; -webkit-border-top-left-radius: 3px; -moz-border-top-left-radius: 3px;  -webkit-border-bottom-left-radius: 3px; -moz-border-bottom-left-radius: 3px;"';
                        }
                        else if(i == num_cells - 1)
                        {
                            pre_html += 'border-top-right-radius: 3px; border-bottom-right-radius: 3px; -webkit-border-top-right-radius: 3px; -moz-border-top-right-radius: 3px;  -webkit-border-bottom-right-radius: 3px; -moz-border-bottom-right-radius: 3px;"';
                        }
                        pre_html += '">';
                        if(aggregated[(i * 4)] > 0)
                        {
                            var val = aggregated[(i * 4)];
                            if(val > 10)
                            {
                                val = 10;
                            }
                            pre_html += '<div class="timeCellIndicatorOn" data-toggle="tooltip" data-placement="top" title="'+aggregated[(i * 4)]+' Doctors" style="width: '+indicator_width+'px; background-color: #'+colors[val - 1]+'"></div>';
                        }
                        else
                        {
                            pre_html += '<div class="timeCellIndicatorOff" style="width: '+indicator_width+'px;"></div>';
                        }
                        if(aggregated[(i * 4) + 1] > 0)
                        {
                            var val = aggregated[(i * 4) + 1];
                            if(val > 10)
                            {
                                val = 10;
                            }
                            pre_html += '<div class="timeCellIndicatorOn" data-toggle="tooltip" data-placement="top" title="'+aggregated[(i * 4) + 1]+' Doctors" style="width: '+indicator_width+'px; background-color: #'+colors[val - 1]+'"></div>';
                        }
                        else
                        {
                            pre_html += '<div class="timeCellIndicatorOff" style="width: '+indicator_width+'px;"></div>';
                        }
                        if(aggregated[(i * 4) + 2] > 0)
                        {
                            var val = aggregated[(i * 4) + 2];
                            if(val > 10)
                            {
                                val = 10;
                            }
                            pre_html += '<div class="timeCellIndicatorOn" data-toggle="tooltip" data-placement="top" title="'+aggregated[(i * 4) + 2]+' Doctors" style="width: '+indicator_width+'px; background-color: #'+colors[val - 1]+'"></div>';
                        }
                        else
                        {
                            pre_html += '<div class="timeCellIndicatorOff" style="width: '+indicator_width+'px;"></div>';
                        }
                        if(aggregated[(i * 4) + 3] > 0)
                        {
                            var val = aggregated[(i * 4) + 3];
                            if(val > 10)
                            {
                                val = 10;
                            }
                            pre_html += '<div class="timeCellIndicatorOn" data-toggle="tooltip" data-placement="top" title="'+aggregated[(i * 4) + 3]+' Doctors" style="width: '+indicator_width+'px; background-color: #'+colors[val - 1]+'"></div>';
                        }
                        else
                        {
                            pre_html += '<div class="timeCellIndicatorOff" style="width: '+indicator_width+'px;"></div>';
                        }
                        pre_html += '</div><span class="timeLabel" style="margin-left: '+label_margin+'px;">';
                        if($("#period_select_medical_availability").val() == 1)
                        {
                            if(i == 0 || i == 12)
                            {
                                pre_html += '12';
                            }
                            else if(i < 12)
                            {
                                pre_html += i;
                            }
                            else
                            {
                                pre_html += i - 12;
                            }
                            if(i < 12)
                            {
                                pre_html += ' am';
                            }
                            else
                            {
                                pre_html += ' pm';
                            }

                        }
                        else if($("#period_select_medical_availability").val() == 2)
                        {
                            if(i % 3 == 0)
                            {
                                var day = Math.floor(i / 3);
                                pre_html += '<span style="color: #888">';
                                if(day == 0)
                                {
                                    pre_html += 'Sun';
                                }
                                else if(day == 1)
                                {
                                    pre_html += 'Mon';
                                }
                                else if(day == 2)
                                {
                                    pre_html += 'Tues';
                                }
                                else if(day == 3)
                                {
                                    pre_html += 'Wed';
                                }
                                else if(day == 4)
                                {
                                    pre_html += 'Thurs';
                                }
                                else if(day == 5)
                                {
                                    pre_html += 'Fri';
                                }
                                else if(day == 6)
                                {
                                    pre_html += 'Sat';
                                }
                                pre_html += '</span>';
                            }
                            else if(i % 3 == 1)
                            {
                                pre_html += '8 am';
                            }
                            else
                            {
                                pre_html += '4 pm';
                            }
                        }
                        else
                        {
                            pre_html += i + 1;
                        }
                        pre_html += '</span></div>';
                    }
                    pre_html += '</div></div>';
                    var display = "none";
                    if(medical_availability_show_doctors_value == 1)
                    {
                        display = "block";
                    }
                    $("#medical_availability_container").html(pre_html+'<div id="all_doctors_availability" style="display: '+display+'">'+html+'</div>');
                    //$('[rel=popover]').popover();
                    $('.timeCellIndicatorOn').tooltip();

                });
            }
            get_doctors_availability();
            $("#period_select_medical_availability").on('change', function()
            {
                get_doctors_availability();
            });
            $("#medical_availability_show_doctors").on('click', function()
            {
                if(medical_availability_show_doctors_value == 0)
                {
                    medical_availability_show_doctors_value = 1;
                    $(this).text("Hide All Doctors");
                    $("#all_doctors_availability").css("display", "block");
                }
                else
                {
                    medical_availability_show_doctors_value = 0;
                    $(this).text("Show All Doctors");
                    $("#all_doctors_availability").css("display", "none");
                }
            });
            
            
            // code for manually launching a telemedicine consultation
            var selected_doctor = -1;
            var selected_user = -1;
            var latest_sid = 0;
            var status_interval = null;
            var selected_doctor_number = '';
            var selected_user_number = '';
            function search_users(type)
            {
                var search_query = $("#search_doctors_bar").val();
                var type_str = "doctor";
                if(type != 1)
                {
                    search_query = $("#search_users_bar").val();
                    type_str = "user";
                }
                console.log("Searching");
                $.post("pdmst_search_users.php", {search: search_query, type: type, scope: $("#scope_select").val()}, function(data, status)
                {
                    console.log(data);
                    
                    var info = JSON.parse(data);
                    var html = '';
                    for(var i = 0; i < info.length; i++)
                    {

                        var id = -1;
                        var email = '';
                        var phone = '';
                        if(type == 1)
                        {
                            id = info[i]['id'];
                            email = info[i]['IdMEDEmail'];
                            phone = info[i]['phone'];
                        }
                        else
                        {
                            id = info[i]['Identif'];
                            email = info[i]['email'];
                            phone = info[i]['telefono'];
                        }
                        html += '<button id="search'+type_str+'_'+id+'_'+phone+'" style="width: 100%; height: 50px; background-color: #FBFBFB; color: #333; border: 0px solid #000; outline: none;text-align: left; border: 1px solid #E8E8E8;" >';
                        html += '<span style="color: #';
                        if(type == 1)
                        {
                            html += '22AEFF';
                        }
                        else
                        {
                            html += '54bc00';
                        }
                        html += '">';
                        if(type == 1)
                        {
                            html += "Dr ";
                        }
                        html += info[i]['Name']+' '+info[i]['Surname']+'</span> <br/> <span style="font-size: 12px;">'+email+'</span>';
                        html += '</button>';
                    }
                    $("#search_"+type_str+"s_results").html(html);
                    $("#search_"+type_str+"s_results").children().first().css('border-top-right-radius', '5px');
                    $("#search_"+type_str+"s_results").children().first().css('border-top-left-radius', '5px');
                    $("#search_"+type_str+"s_results").children().last().css('border-bottom-right-radius', '5px');
                    $("#search_"+type_str+"s_results").children().last().css('border-bottom-left-radius', '5px');

                    $('button[id^="searchdoctor_"]').on('click', function(e)
                    {
                        console.log("calling");
                        var user_id = $(this).attr("id").split("_")[1];
                        var user_name = $(this).children().first().text();
                        selected_doctor = user_id;
                        selected_doctor_number = $(this).attr("id").split("_")[2];
                        $("#selected_doctor_label").text(user_name);
                        $("#selected_doctor_label").css("color", "#22AEFF");
                        if(selected_user > 0 && selected_doctor > 0)
                        {
                            $("#launch_telemedicine_button").removeAttr("disabled");
                        }
                    });
                    $('button[id^="searchuser_"]').on('click', function(e)
                    {
                        var user_id = $(this).attr("id").split("_")[1];
                        var user_name = $(this).children().first().text();
                        selected_user = user_id;
                        selected_user_number = $(this).attr("id").split("_")[2];
                        console.log(selected_user_number);
                        $("#selected_user_label").text(user_name);
                        $("#selected_user_label").css("color", "#54bc00");
                        if(selected_user > 0 && selected_doctor > 0)
                        {
                            $("#launch_telemedicine_button").removeAttr("disabled");
                        }
                    });

                });
            }
            $("#search_doctors_bar_button").on('click', function()
            {
                search_users(1);
            });
            $("#search_users_bar_button").on('click', function()
            {
                search_users(2);
            });
            $('#search_doctors_bar').keypress(function (e) 
            {
                if (e.which == 13) 
                {
                    $("#search_doctors_bar_button").trigger('click');
                }
            });
            $('#search_users_bar').keypress(function (e) 
            {
                if (e.which == 13) 
                {
                    $("#search_users_bar_button").trigger('click');
                }
            });
            $("#telemedicine_status").dialog({bigframe: true, width: 400, height: 150, autoOpen: false});
            $("#launch_telemedicine_button").on('click', function()
            {
                
                
                $.post("start_telemed_phonecall.php", {pat_phone: selected_user_number, doc_phone: selected_doctor_number, doc_id: selected_doctor, pat_id: selected_user, doc_name: $("#selected_doctor_label").text(), pat_name: $("#selected_user_label").text(), override: true}, function(data, status)
                {
                    if(data.substr(0, 2) == 'IC')
                    {
                        alert("We're sorry, this doctor is already in a consultation with another member. Please try another doctor or try again later.");
                    }
                    else if(data.substr(0, 2) == 'NC')
                    {
                        alert("You have not entered a credit card for your account. Please enter a credit card in Setup and try again.");
                    }
                    else
                    {
                        $("#call_status_label").css("color", "#E07221");
                        $("#call_status_label").text('Connecting...');
                        $("#telemedicine_status").dialog('open');
                        latest_sid = data;
                        status_interval = setInterval(function()
                        {
                            
                            $.get("get_call_status.php?sid="+latest_sid, function(data, status)
                            {
                                //console.log(data);
                                $("#call_status_label").text(data);
                                if(data == 'Completed')
                                {
                                    $("#call_status_label").css("color", "#54bc00"); // green
                                }
                                else if(data == 'No Answer' || data == 'Failed' || data == 'Busy' || data == 'Canceled')
                                {
                                    $("#call_status_label").css("color", "#D84840"); // red
                                }
                                else if(data == 'Queued' || data == 'Ringing')
                                {
                                    $("#call_status_label").css("color", "#E07221"); // orange
                                }
                                else
                                {
                                    $("#call_status_label").css("color", "#22AEFF"); // blue
                                }
                            });
                        }, 2000);
                    }
                });
                
            });
            
            
            $("#period_select").on('change', function()
            {
                get_main_data($("#scope_select").val(), parseInt($(this).val()));
                
                current_page = 1;
                load_table(current_table, current_column, ascending, current_page, $("#scope_select").val(), parseInt($("#period_select").val()), $("#search_bar").val());
            });
            $("#scope_select").on('change', function()
            {
                get_main_data($(this).val(), parseInt($("#period_select").val()));
                get_doctors_availability();
                current_page = 1;
                load_table(current_table, current_column, ascending, current_page, $("#scope_select").val(), parseInt($("#period_select").val()), $("#search_bar").val());
            });
            $("#search_bar_clear_button").on('click', function()
            {
                $("#search_bar").val('');
                $(this).css('visibility', 'hidden');
                current_page = 1;
                ascending = 1;
                if(current_table == 1)
                {
                    current_column = 'name';
                }
                else if(current_table == 2)
                {
                    current_column = 'doctor';
                }
                else if(current_table == 3)
                {
                    current_column = 'name';
                }
				else if(current_table == 4)
                {
                    current_column = 'name';
                }
                load_table(current_table, current_column, ascending, current_page, $("#scope_select").val(), parseInt($("#period_select").val()), $("#search_bar").val());
            });
            $("#search_bar_button").on('click', function()
            {
                current_page = 1;
                ascending = 1;
                if(current_table == 1)
                {
                    current_column = 'name';
                }
                else if(current_table == 2)
                {
                    current_column = 'doctor';
                }
                else if(current_table == 3)
                {
                    current_column = 'name';
                }
				else if(current_table == 4)
                {
                    current_column = 'name';
                }
                load_table(current_table, current_column, ascending, current_page, $("#scope_select").val(), parseInt($("#period_select").val()), $("#search_bar").val());
            });
            $("#search_bar").on('input', function()
            {
                if($(this).val().length > 0)
                {
                    $("#search_bar_clear_button").css("visibility", "visible");
                }
                else
                {
                    $("#search_bar_clear_button").css("visibility", "hidden");
                }
            });
            $("button[id^='segment_']").on('click', function()
            {
                // To select the type of table (customers, consultations, or doctors)
                if(!$(this).hasClass("segmented_control_selected"))
                {
                    var selected = parseInt($(this).attr('id').split('_')[1]);
                    $("button[id^='segment_']").addClass("segmented_control");
                    $("button[id^='segment_']").removeClass("segmented_control_selected");
                    $(this).addClass("segmented_control_selected");
                    $("#customers_table").css("display", "none");
                    $("#consultations_table").css("display", "none");
                    $("#doctors_table").css("display", "none");
					$("#newusers_table").css("display", "none");
                    if(selected == 1)
                    {
                        // load customers
                        current_table = 1;
                        $("#customers_table").css("display", "block");
                        $('#caret_button_customers_name').parent().parent().children().each(function(index)
                        {
                            $(this).css('background-color', '#22AEFF');
                        });
                        $('#caret_button_customers_name').parent().css('background-color', '#6ECCFF');
                        current_column = 'name';
                    }
                    else if(selected == 2)
                    {
                        // load consultations
                        current_table = 2;
                        $("#consultations_table").css("display", "block");
                        $('#caret_button_consultations_doctor').parent().parent().children().each(function(index)
                        {
                            $(this).css('background-color', '#22AEFF');
                        });
                        $('#caret_button_consultations_doctor').parent().css('background-color', '#6ECCFF');
                        current_column = 'doctor';
                    }
                    else if(selected == 3)
                    {
                        // load doctors
                        current_table = 3;
                        $("#doctors_table").css("display", "block");
                        $('#caret_button_doctors_name').parent().parent().children().each(function(index)
                        {
                            $(this).css('background-color', '#22AEFF');
                        });
                        $('#caret_button_doctors_name').parent().css('background-color', '#6ECCFF');
                        current_column = 'name';
                    }else if(selected == 4)
                    {
                        // load doctors
                        current_table = 4;
                        $("#newusers_table").css("display", "block");
                        $('#caret_button_newusers').parent().parent().children().each(function(index)
                        {
                            $(this).css('background-color', '#22AEFF');
                        });
                        $('#caret_button_newusers').parent().css('background-color', '#6ECCFF');
                        current_column = 'name';
                    }
                    current_page = 1;
                    ascending = 1;
                    load_table(current_table, current_column, ascending, 1, $("#scope_select").val(), parseInt($("#period_select").val()), $("#search_bar").val());
                        
                }
            });
            $("button[id^='toggle_']").on('click', function()
            {
                // To select whether the charts are regular or cumulative
                if(!$(this).hasClass("segmented_control_selected"))
                {
                    data_type = parseInt($(this).attr('id').split('_')[1]);
                    get_main_data($("#scope_select").val(), parseInt($("#period_select").val()));
                    $("button[id^='toggle_']").addClass("segmented_control");
                    $("button[id^='toggle_']").removeClass("segmented_control_selected");
                    $(this).addClass("segmented_control_selected");
                }
            });
            $('button[id^="caret_button_"]').on('click', function()
            {
                // for choosing the column to sort the table by
                var button_data = $(this).attr("id").split("_");
                console.log(button_data[2] + ":" + button_data[3]);
                current_column = button_data[3];
                if($(this).hasClass("icon-caret-down"))
                {
                    $(this).removeClass("icon-caret-down").addClass("icon-caret-up");
                    ascending = false;
                }
                else
                {
                    $(this).removeClass("icon-caret-up").addClass("icon-caret-down");
                    ascending = true;
                }
                load_table(current_table, current_column, ascending, 1, $("#scope_select").val(), parseInt($("#period_select").val()), $("#search_bar").val());
                current_page = 1;
                
                $(this).parent().parent().children().each(function(index)
                {
                    $(this).css("background-color", "#22AEFF");
                });
                $(this).parent().css("background-color", "#6ECCFF");
            });
            $('button[class^="consultations_button"]').on('click', function()
            {
                if($(this).hasClass("consultations_button_selected"))
                {
                    $(this).removeClass("consultations_button_selected").addClass("consultations_button");
                    show_consultations = false;
                }
                else if($(this).hasClass("consultations_button"))
                {
                    $(this).removeClass("consultations_button").addClass("consultations_button_selected");
                    show_consultations = true;
                }
                reload_chart(parseInt($("#period_select").val()));
            });
            $('button[class^="users_button"]').on('click', function()
            {
                if($(this).hasClass("users_button_selected"))
                {
                    $(this).removeClass("users_button_selected").addClass("users_button");
                    show_users = false;
                }
                else if($(this).hasClass("users_button"))
                {
                    $(this).removeClass("users_button").addClass("users_button_selected");
                    show_users = true;
                }
                reload_chart(parseInt($("#period_select").val()));
            });
                                                          
            $("#page_button_left").on('click', function()
            {
                current_page -= 1;
                load_table(current_table, current_column, ascending, current_page, $("#scope_select").val(), parseInt($("#period_select").val()), $("#search_bar").val());
                
            });
            $("#page_button_right").on('click', function()
            {
                current_page += 1;
                load_table(current_table, current_column, ascending, current_page, $("#scope_select").val(), parseInt($("#period_select").val()), $("#search_bar").val());
            });
            get_main_data("Global", 1); 
            load_table(current_table, current_column, ascending, current_page, $("#scope_select").val(), parseInt($("#period_select").val()), $("#search_bar").val());
            
            
        });
    </script>
  </body>
</html>