<?php
include("userConstructClass.php");
$user = new userConstructClass();
$user->probeGauge();
$user->pageLinks('PatientNetworkRedesign.php');
$user->doctor_calculations();

$load_patient = -1;
if(isset($_GET['LOADPATIENT']))
    $load_patient = $_GET['LOADPATIENT'];
?>
<script type="text/javascript" src="http://cdn.raygun.io/raygun4js/raygun.min.js"></script>
<script>
  Raygun.init('0n+4YBScBkltZ69Q7FQ1UA==').attach();
</script>
<!-- Create language switcher instance and set default language to en-->
<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
    <script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
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
  <body style="background:url('../images/bg_login.jpg'); background-size:500px 500px;">
<!--<div class="loader_spinner"></div>-->
<!-------------------------------------------------------------------------------------HIDDEN INPUTS-------------------------------------------------------------------------------->
        <input type="hidden" id ="quePorcentaje" value="<?php if(isset($porcentajeCreados)) echo ($porcentajeCreados) ?>" />
     	<input type="hidden" id="MEDID" Value="<?php echo $user->med_id; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $user->doctor_email; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $user->doctor_first_name; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $user->doctor_last_name; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
     	<input type="hidden" id="USERDID" Value="<?php if(isset($user->mem_id)) echo $user->mem_id; ?>">
        <input type="hidden" id="LOADPATIENT" Value="<?php echo $load_patient; ?>">
		<input type="hidden" id="ProbeIDHidden"> 	
		<input type="hidden" value="<?php echo $user->vg_response; ?>" id="vg_probe">
		<input type="hidden" value="<?php echo $user->g_response; ?>" id="g_probe">
		<input type="hidden" value="<?php echo $user->n_response; ?>" id="n_probe">
		<input type="hidden" value="<?php echo $user->b_response; ?>" id="b_probe">
		<input type="hidden" value="<?php echo $user->vb_response; ?>" id="vb_probe">
		<input type="hidden" value="<?php echo $user->t_response; ?>" id="total_probe">
		<input type="hidden" value="" id="time_holder">
		<input type="hidden" value="" id="date_holder">
<!------------------------------------------------------------------------------------END HIDDEN INPUTS------------------------------------------------------------------------------->
	<!--Header Start-->
	<div id='checkout-window' style='overflow:hidden;' title='Connect New Member'></div>
	<div class="header" >
    	
           <a href="index.html" class="logo"><h1>health2.me</h1></a>
           
		    <!-- Below is earlier code for language -->
      <!--  Start of comment <div style="float:left;">
		   <a href="#en" onclick="setCookie('lang', 'en', 30); return false;"><img src="images/icons/english.png"></a>
		   </br>
			<a href="#sp" onclick="setCookie('lang', 'th', 30); return false;"><img src="images/icons/spain.png"></a>
			</div> End of comment -->
        
             <!-- Start of new code by Pallab -->
             <!-- Beautification of button (changes to standar classes to be added to this instance of dropdown -->
               <link rel='stylesheet' href='css/bootstrap-dropdowns.css'>
               <link rel='stylesheet' href='css/bootstrap-switch.min.css'>
               <style>
                .patient_row_button{
                    width: 31px; 
                    height: 33px;
                    padding-left: 9px;
                    padding-top: 5px; 
                    border-radius: 40px; 
                    border: 2px solid #CECECE;
                    float: left;
                    margin-top: -10px; 
                    font-size: 24px;
                    margin-left: 15px;
                    color: #22AEFF;
                    background-color: #FFF;
                }
                .patient_row_button:hover{
                    cursor: pointer;
                }
                .patient_row_button_half_active{
                    border: 2px solid #CCC;
                    color: #22AEFF;
                    background-color: #EAEAEA;
                }
                .patient_row_button_active{
                    border: 2px solid #22AEFF;
                    color: #FFF;
                    background-color: #22AEFF;
                }
                .patient_name{
                    font-size: 18px; 
                    color: #777; 
                    font-weight: normal;
                    text-decoration: none;
                }
                .patient_name:hover{
                    color: #54bc00; 
                    text-decoration: none;
                    cursor: pointer;
                }
    
                .addit_button{
                    background: transparent;
                    color: whitesmoke;
                    text-shadow: none;
                    border: 1px solid #E5E5E5;
                    font-size: 12px !important;
                    height: 20px;
                    line-height: 12px;      
                }
                .addit_caret{
                   border-top: 4px solid whitesmoke;
                   margin-top: 3px !important;
                   margin-left: 5px !important;
                }  
               </style>
               
               <div style="margin-top:11px;float:left; margin-left:50px;" class="btn-group">
                      <button id="lang1" type="button" class="btn btn-default dropdown-toggle addit_button" data-toggle="dropdown">
                        <span lang="en">Language</span> <span class="caret addit_caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#en" onclick="setCookie('lang', 'en', 30); return false;">English</a></li>
                        <li><a href="#sp" onclick="setCookie('lang', 'th', 30); return false;">Espa&ntilde;ol</a></li>
                        <li><a href="#tu" onclick="setCookie('lang', 'tu', 30); return false;">T&uuml;rk&ccedil;e</a></li>
                        <li><a href="#hi" onclick="setCookie('lang', 'hi', 30); return false;">हिंदी</a></li>
                      </ul>
                </div>
               
             <script>
                var langType = initial_language;
                var language = '';

                if(langType == 'th')
                {
                    language = 'th';
                    $("#lang1").html("Espa&ntilde;ol <span class=\"caret addit_caret\"></span>");
                }
                else if(langType == 'tu')
                {
                    language = 'tu';
                    $("#lang1").html("T&uuml;rk&ccedil;e <span class=\"caret addit_caret\"></span>");
                }
                 else if(langType == 'hi')
                {
                    language = 'hi';
                    $("#lang1").html("हिंदी <span class=\"caret addit_caret\"></span>");
                }
                else{
                    language = 'en';
                    $("#lang1").html("English <span class=\"caret addit_caret\"></span>");
                }
                </script>
              <!-- End of new code by Pallab-->
		   
           <div class="pull-right">
           
            <?php include 'message_center.php'; ?>
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <span class="name-user"><strong lang="en">Welcome</strong> Dr, <?php echo $user->doctor_first_name.' '.$user->doctor_last_name; ?></span> 
             <?php 
             $hash = md5( strtolower( trim( $user->doctor_email ) ) );
             $avat = 'identicon.php?size=29&hash='.$hash;
			?>	
              <span class="avatar" ><img src="<?php echo $avat; ?>" alt="" ></span> 
              <span class="caret"></span>
            </a>
            <div class="dropdown-menu" id="prof_dropdown" style="background-color:transparent; border:none; -webkit-box-shadow:none; box-shadow:none;">
            <div class="item_m"><span class="caret"></span></div>
            <ul class="clear_ul" >
              <li><a href="MainDashboard.php" lang="en"><i class="icon-globe"></i> Home</a></li>
              
              <li><a href="medicalConfiguration.php" lang="en"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
            </ul>
            </div>
          </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->
	
	<!--Pop up to create Probe-->
	<button id="probeRequest" data-target="#header-modal333" data-toggle="modal" class="btn btn-warning" lang="en">Modal with Header</button> 
				<div id="header-modal333" class="modal fade hide" style="display: none;overflow:visible;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">×</button>
						<div id="InfB" >
	                 	<h4 lang="en">Create Probe Request</h4>
						 </div>
				
						
				</div>
				<div class="modal-body">
					<div id="InfoIDPacienteB">
					</div>
					<br><br><br>
					<center><!--
					<div style="margin-top:-15px;">	Timezone &nbsp : &nbsp <select id="Timezone" style="width:15em;margin-top:10px"> </select></div>
					<div >	Time &nbsp : &nbsp&nbsp&nbsp&nbsp <input id="Time" style="width:16em;margin-top:10px"> </input></div>
					<div>	Interval &nbsp : &nbsp <select id="Interval" style="width:15em;margin-top:10px"> </select></div>
					<div>	<input style="display:block" type="radio" name="feedbackValue" value="Email"/> Email</div>-->
					<table id = "ProbeTab">
						<tr>
							<td lang="en"> <span lang="en">Timezone</span> :</td>
							<td><select id="Timezone"> </select></td>
						</tr>
						<tr>
							<td lang="en"> <span lang="en">Time</span> :</td>
							<td><input id="Time"> </input></td>
						</tr>
						<tr>
							<td lang="en"> <span lang="en">Interval</span> :</td>
							<td><select id="Interval"> </select></td>
						</tr>
												
						<tr>
							<td lang="en"> <span lang="en">Probe Method</span> :</td>
							<td><select id="Method"> 
									<option value="Email" lang="en">Email</option>
									<option value="Phone" lang="en">Phone</option>
									<option value="Message" lang="en">Text Message</option>
								</select>
							</td>
						</tr>
						
						<tr id="EmailRow">
							<td lang="en"> <span lang="en">Email ID</span> : </td>
							<td> <input id="Email" readonly> </td>
						</tr>
						<tr id="PhoneRow">
							<td lang="en"> <span lang="en">Phone Number</span> : </td>
							<td><input id="Phone" type="tel" placeholder="e.g. +1 702 123 4567" readonly></td>
							
						</tr>
						<tr id="MessageRow">
							<td lang="en"> <span lang="en">Phone Number</span> : </td>
							<td> <input id="Message" type="tel" placeholder="e.g. +1 702 123 4567" readonly> </td>
						</tr>
						
						
					
					</table>
					<input type="hidden" id="patientID">
					
					</center>
					
				</div>
					
				<div class="modal-footer">
					<a href="#" class="btn btn-success" data-dismiss="modal" id="createProbe" lang="en">Create</a>
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModallink" lang="en">Close</a>
				</div>
				</div>  
	<!--End Pop up for creating probe-->
	
	<!--Pop up to show Probe History-->
	<button id="probeHistory" data-target="#header-modal5656" data-toggle="modal" class="btn btn-warning" lang="en">Modal with Header</button> 
				<div id="header-modal5656" class="modal fade hide" aria-hidden="true">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">×</button>
							<div id="InfB" >
							<h4 lang="en">Probe History</h4>
							 </div>
					
							
					</div>
					<div class="modal-body">
						<div id="InfoIDPacienteStats">
						</div>
						<div id="InfoIDPacienteBB">
						</div>
						<br><br><br>
						<div class="grid" style="margin-top:0px;overflow:scroll;overflow-x:hidden;height:300px">
							<table class="table table-mod" id="TablaProbeHistory">
							</table> 
						</div>
					</div>
						
					<div class="modal-footer">
						<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModallink" lang="en">Close</a>
					</div>
				</div>  
	<!--End Pop up Probe History-->
    <style>
        #probe_toggle{
            margin-top: 25px;
            width: 97%;
            height: 35px;
            border: 0px solid #FFF;
            outline: none;
            border-radius: 5px;
            background-color: #54bc00;
            color: #FFF;
        }
        .probe_method_button{
            width: 50px;
            height: 50px;
            border: 1px solid #DDD;
            outline: none;
            border-radius: 50px;
            background-color: #FFF;
            color: #BBB;
            
        }
        .probe_method_button_selected{
            width: 50px;
            height: 50px;
            border: 1px solid #22AEFF;
            outline: none;
            border-radius: 50px;
            background-color: #22AEFF;
            color: #FFF;
            
        }
        .probe_interval_button{
            width: 82px;
            height: 40px;
            border: 1px solid #CCC;
            outline: none;
            background-color: #FFF;
            color: #777;
            margin: none;
            margin-left: -5px;
            margin-top: 5px;
            
        }
        .probe_interval_button_selected{
            width: 82px;
            height: 40px;
            border: 1px solid #22AEFF;
            outline: none;
            background-color: #22AEFF;
            color: #FFF;
            margin: none;
            margin-left: -5px;
            margin-top: 5px;
        }
        #edit_probes_button:disabled{
            cursor: default;
            background-color: #75CEFF;

        }
        #launch_probes_button{
            margin-top: 20px;
            width: 97%;
            height: 35px;
            border: 0px solid #FFF;
            outline: none;
            border-radius: 5px;
            background-color: #22AEFF;
            color: #FFF;
        }
        #probe_add,#probe_cancel{
            margin-top: 10px;
            width: 100%;
            height: 35px;
            border: 0px solid #FFF;
            outline: none;
            border-radius: 5px;
            background-color: #54bc00;
            color: #FFF;
        }
        .probe_info_section{
            width: 94%; 
            margin: auto;
            padding: 2%;
            background-color: #FDFDFD;
            border: 1px solid #F2F2F2;
            border-radius: 5px;
            color: #6A6A6A;
            text-align: center;
        }
        #probe_alert_button{
            width: 6%;
            height: 30px;
            padding-left: 1%;
            padding-right: 1%;
            background-color: #89150B;
            color: #FFF;
            outline: none;
            border-radius: 6px;
            border: 0px solid #FFF;
            margin-left: 1%;
            font-size: 18px;
        }
        #add_probe_button{
            width: 6%;
            height: 30px;
            padding-left: 1%;
            padding-right: 1%;
            background-color: #54BC00;
            color: #FFF;
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
            outline: none;
            border: 0px solid #FFF;
            font-size: 18px;
            margin-right: -5px;
            margin-left: 8px;
            font-weight: bold;
            font-size: 20px;
        }
        #probe_delete_button{
            width: 6%;
            height: 30px;
            padding-left: 1%;
            padding-right: 1%;
            background-color: #D84840;
            color: #FFF;
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
            outline: none;
            border: 0px solid #FFF;
            font-size: 18px;
            margin-right: -5px;
        }
    </style>
    <div id="probe_editor" title="Probes" style="display: none; overflow: hidden;">
        <div id="manage_user_probe" style="display: block; margin: auto; margin-top: 10px; overflow: hidden;">
            <h1 style="color: #444; font-size: 14px; text-align: center; margin-top: -13px;"><span lang="en">Manage Probe</span></h1>
            <div class="probe_info_section" style="height: 320px; margin-top: -10px;">
                <div style="width: 100%; margin: auto; height: 80px;">
                    <button id="standard_probe_button" data-on="0" style="height: 80px; width: 47%; float: left; background-color: #22AEFF; color: #FFF; border: 0px solid #FFF; border-radius: 5px; outline: none;">
                        <i class="icon-signal" style="font-size: 45px;"></i><br/><span lang="en">Use Standard Probe</span>
                    </button>
                    
                    <!--<div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Probe:</div>-->
                    <div id="select_probe_section" style="height: 73px; padding-top: 7px; width: 47%; float: right; background-color: #22AEFF; color: #FFF; border-radius: 5px;">
                        <span lang="en">Select Probe</span><br/>
                        <div style="width: 100%; height: 12px; margin-top: -7px; margin-bottom: 7px;"><i class="icon-chevron-down" style="font-size: 12px;"></i></div>
                        <select id="probe_protocols" style="width: 90%;">
                        </select>
                    </div>
                    
                    <div style="height: 50px; padding-top: 30px; width: 4%; margin-right: 1%; float: right;">
                        or
                    </div>
                </div>
                <!--
                <button id="add_probe_button">+</button>
                <button id="probe_delete_button"><i class="icon-remove"></i></button>
                
                <button id="probe_alert_button"><i class="icon-exclamation"></i></button>
                -->
                <br/>
                <div style="width: 100%; height: 30px; margin-bottom: 20px;">
                    <div style="width: 18%; float: left; margin-right: 20px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;"><span lang="en">Select Time</span>:</div>
                    <div style="width: 76%; float: left;">
                        <input id="probe_time5" type="text"  style="width: 17%; height: 18px; float: left;"/>
                        <select id="probe_timezone" style="width: 48%; margin-left: 18px; height: 30px; float: left;">
                            <option value="3" lang="en">US Pacific Time</option>
                            <option value="4" lang="en">US Mountain Time</option>
                            <option value="2" lang="en">US Central Time</option>
                            <option value="1" lang="en">US Eastern Time</option>
                            <option value="5" lang="en">Europe Central Time</option>
                            <option value="6" lang="en">Greenwich Mean Time</option>
                        </select>
                        <select id="probe_language" style="width: 22%; margin-left: 18px; height: 30px; float: left;">
                            <option value="en" lang="en">English</option>
                            <option value="es" lang="en">Español</option>
                        </select>
                    </div>
                </div>
                <div style="width: 100%; height: 56px;">
                    <div style="float: left; width: 170px;">
                        <button class="probe_method_button_selected" data-on="1" id="probe_method_phone"><i class="icon-phone" style="font-size: 30px;"></i></button>
                        <button class="probe_method_button" data-on="0" id="probe_method_text"><i class="icon-mobile-phone" style="font-size: 40px;"></i></button>
                        <button class="probe_method_button" data-on="0" id="probe_method_email"><i class="icon-envelope" style="font-size: 30px;"></i></button>
                    </div>
                    <div style="float: right; width: 62%; text-align: right;">
                        <button class="probe_interval_button_selected" data-on="1" id="probe_interval_daily" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;"><span lang="en">Daily</span></button>
                        <button class="probe_interval_button" data-on="0" id="probe_interval_weekly"><span lang="en">Weekly</span></button>
                        <button class="probe_interval_button" data-on="0" id="probe_interval_monthly"><span lang="en">Monthly</span></button>
                        <button class="probe_interval_button" data-on="0" id="probe_interval_yearly" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;"><span lang="en">Yearly</span></button>
                    </div>
                </div>
                <div style="width: 100%; text-align: left;"><span lang="en">Expectation</span>:</div>
                <div style="width: 73%; padding-top: 8px; padding-left: 10px; text-align: left; background-color: #FFF; border: 1px solid #CCC; border-radius: 5px; margin: auto; margin-top: 8px;">
                    <div id="probe_alert_chart_button" style="width: 25px; height: 22px; padding-top: 3px; margin-right: 5px; border-radius: 25px; background-color: #F8F8F8; border: 1px solid #DDD; float: right; color: #22AEFF; text-align: center;">
                        <i class="icon-bar-chart"></i>
                    </div>
                    <style>
                        input[type='range'] {  
                            -webkit-appearance: none;  
                            width: 72px;  
                            border-radius: 8px;  
                            height: 5px;  
                            border: 1px solid #CCC;  
                            background-color: #F8F8F8; 
                            outline: none;
                        }
                        input[type='range']::-webkit-slider-thumb {
                            -webkit-appearance: none;
                            background-color: #E8E8E8;
                            border: 1px solid #999;
                            width: 16px;
                            height: 16px;
                            border-radius: 10px;
                            cursor: pointer;
                            box-shadow: -1px 1px 3px #DDD;
                        }
                    </style>
                    <strong><span lang="en">Start</span>:</strong>&nbsp;&nbsp;&nbsp;<input type="number" id="probe_alert_start_value" style="width: 50px;" /> 
                    &nbsp;<strong><span lang="en">Tolerance</span>:</strong>&nbsp;&nbsp;<input id="probe_alert_tolerance" style="margin-right: 4px;" type="range" value="5" max="40" min="5" /><span id="probe_alert_tolerance_value">5</span>%<br/>
                    <strong><span lang="en">Finish</span>:</strong>&nbsp;<input type="number" id="probe_alert_expected_value" style="width: 50px;" /> <span lang="en">in</span> <input type="number" id="probe_alert_expected_day_1" style="width: 50px;" /> <span lang="en">to</span> <input type="number" id="probe_alert_expected_day_2" style="width: 50px;" /> <span lang="en">days</span>.
                </div>
                <!--<br/>
                <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Method: </div>
                <select id="probe_method" style="width: 64%; height: 30px;">
                    <option value="0">N/A</option>
                    <option value="1">Text Message</option>
                    <option value="2">Phone Call</option>
                    <option value="3">Email</option>
                </select>
                <br/>
                <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Interval: </div>
                <select id="probe_interval" style="width: 64%; height: 30px;">
                    <option value="0">N/A</option>
                    <option value="1">Daily</option>
                    <option value="7">Weekly</option>
                    <option value="30">Monthly</option>
                    <option value="365">Yearly</option>
                </select>-->
                <br/>
                
                <!--<button id="save_probes_button" style="margin-right: 4%; margin-left: 5px; background-color: #54BC00;"><i class="icon-lock"></i>&nbsp;&nbsp;Save</button>
                <button id="edit_probes_button"><i class="icon-pencil"></i>&nbsp;&nbsp;Edit Probes</button>-->
            </div>
            <div class="probe_info_section" style="height: 135px; margin-top: 10px;">
                <span lang="en">Current Probe Status</span>: 
                <link rel="stylesheet" href="css/toggle.css" />
                <div style="width: 100px; height: 30px; margin: auto; margin-top: 10px;">
                    <input id="probeToggle" class="h2m-toggle h2m-toggle-round" type="checkbox" />
                    <label id="pTL" for="probeToggle" data-on="Yes" data-off="No" style="float: left;"></label>
                    <div id="probeToggleLabel" style="color: #54BC00; float: right; width: 30px; height: 30px; font-size: 22px; text-align: right; margin-top: 7px;" lang="en">On</div>
                </div>
                <!--<button id="edit_probes_button"><i class="icon-pencil"></i>&nbsp;&nbsp;Edit Probe</button>-->
                <button id="launch_probes_button"><i class="icon-bolt"></i>&nbsp;&nbsp;<span lang="en">Launch Probe Now</span></button>
            </div>
        </div>
        <div id="view_probes" style="display: none; overflow: scroll;">
            <style>
                .probes_row{
                    width: 94%;
                    height: 25px;
                    padding-top: 8px;
                    padding-bottom: 2px;
                    padding-left: 3%;
                    padding-right: 1%;
                    background-color: #F2F2F2;
                    border: 1px solid #E8E8E8;
                    border-radius: 6px;
                    margin: auto;
                    margin-bottom: 8px;
                }
                .no_probes_notification{
                    width: 94%;
                    height: 50px;
                    padding-top: 8px;
                    padding-bottom: 2px;
                    padding-left: 3%;
                    padding-right: 1%;
                    background-color: #F2F2F2;
                    border: 1px dashed #E8E8E8;
                    border-radius: 6px;
                    margin: auto;
                    margin-bottom: 8px;
                    text-align: center;
                    color: #777;
                }
                .probes_edit_button{
                    width: 6%;
                    height: 25px;
                    padding-left: 1%;
                    padding-right: 1%;
                    background-color: #54bc00;
                    color: #FFF;
                    border-radius: 6px;
                    outline: none;
                    border: 0px solid #FFF;
                    margin-top: -5px;
                }
                .probes_delete_button{
                    width: 6%;
                    height: 25px;
                    padding-left: 1%;
                    padding-right: 1%;
                    background-color: #D84840;
                    color: #FFF;
                    border-radius: 6px;
                    outline: none;
                    border: 0px solid #FFF;
                    margin-top: -5px;
                    margin-left: 5px;
                }
                #probe_question_previous,#probe_question_next{
                    width: 27px;
                    height: 27px;
                    border-radius: 27px;
                    border: 0px solid #FFF;
                    background-color: #22AEFF;
                    color: #FFF;
                    outline: none;
                }
            </style>
            <div id="probes_container" style="width: 100%; height: 470px; overflow: scroll">
                <div class="probes_row">
                    <div style="float: left; width: 40%; margin-right: 3%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-weight: bold;">Thjekwhalkjhgakwejghkwjebgjkwebg</div>
                    <div style="float: left; width: 40%; margin-right: 3%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">KEWGJKNKWJENgkwjengkajnwlgkjngkejbgerbgkjebrgkhebg</div>
                    <button class="probes_edit_button"><i class="icon-pencil"></i></button>
                    <button class="probes_delete_button">X</button>
                </div>
                <div class="probes_row"></div>
                <div class="probes_row"></div>
                <div class="probes_row"></div>
            </div>
            <!--<button id="add_probe_button" style="width: 99%; height: 35px; background-color: #54bc00; border-radius: 5px; border: 0px solid #FFF; outline: none; color: #FFF; margin-left: 2px;">+</button>-->
            <button id="add_probe_button_back" style="width: 99%; height: 35px; background-color: #22AEFF; border-radius: 5px; border: 0px solid #FFF; outline: none; color: #FFF; margin-left: 2px; margin-top: 10px;">Back</button>
        </div>
        <div id="add_probe" style="display: none; overflow: scroll;">
            <div style="width: 20%; float: left; margin-right: 4%; height: 24px; padding-top: 6px;"><span lang="en">Name</span>: </div>
            <input type="text" id="probe_name_edit" style="width: 73%; float: left;" />
            <br/>
            <div style="width: 20%; float: left; margin-right: 4%; height: 24px; padding-top: 6px;"><span lang="en">Description</span>: </div>
            <input type="text" id="probe_description" style="width: 73%; float: left;" />
            <br/>
            
            <div style="text-align: center; width: 100%; height: 30px; margin-top: 55px;">
                <button id="probe_question_previous"><i class="icon-chevron-left"></i></button>
                <span id="probe_question_label" style="font-size: 14px; color: #777;">&nbsp;&nbsp;<span lang="en">Question</span> 1&nbsp;&nbsp;</span>
                <button id="probe_question_next"><i class="icon-chevron-right"></i></button>
            </div>
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;"><span lang="en">Title></span>:</div>
            <input id="probe_question_title" type="text" style="width: 76%; float: left;" />
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;"><span lang="en">English</span>:</div>
            <input id="probe_question_en" type="text" style="width: 76%; float: left;" />
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;"><span lang="en">Spanish</span>:</div>
            <input id="probe_question_es" type="text" style="width: 76%; float: left;" />
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;"><span lang="en">Min Value</span>:</div> 
            <input type="number" id="probe_question_min" style="width: 12%; float: left;" />
            <div style="width: 15%; float: left; margin-right: 2%; height: 24px; padding-top: 6px; margin-left: 10px;"><span lang="en">Max Value</span>:</div>
            <input type="number" id="probe_question_max" style="width: 12%; float: left;" />
            <div style="width: 7%; float: left; margin-right: 2%; height: 24px; padding-top: 6px; margin-left: 19px;"><span lang="en">Unit</span>:</div>
            <input type="text" id="probe_question_unit" style="width: 16%; float: left;" />
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;"><span lang="en">Answer Type</span>:</div>
            <select id="probe_question_answer_type" style="width: 78%; float: left;">
                <option value="1" lang="en">Single Digit (0 - 9)</option>
                <option value="2" lang="en">Regular Number</option>
                <option value="3"><span lang="en">Yes</span> / No</option>
            </select>
            <span style="text-align: center;"><span lang="en">Range Units</span>:</span>
            <div id="probe_range_selector" style="width: 100%; height: 80px; float: left; margin-top: 5px;"></div>
            <div style="text-align: center; width: 100%; height: 100px; margin-top: 95px; text-align: center;">
                <button id="probe_add"><i class="icon-ok"></i>&nbsp;&nbsp;<span lang="en">Done</span></button><br/>
                <button id="probe_cancel" style="background-color: #D84840"><i class="icon-remove"></i>&nbsp;&nbsp;<span lang="en">Cancel</span></button>
            </div>
            
        </div>
        <div id="edit_probe_alerts" style="display: none; overflow: scroll;">
            <h1 style="color: #444; font-size: 14px; text-align: center; margin-bottom: -10px;" lang="en">Probe Alerts</h1>
            
            <h1 style="color: #777; font-size: 12px; margin-bottom: -5px; margin-top: -30px;" lang="en">Select Question</h1>
            <select id="probe_alert_question" style="width: 100%;">
            </select>
            <!--<div style="float: right; width: 100%; height: 150px; background-color: #FCFCFC; border: 1px solid #EEE; border-radius: 5px; text-align: center;">
                <div style="font-size: 12px; color: #555; text-align: center; margin-bottom: 5px;">Expectations</div>
                The patient is starting with a value of <input type="number" id="probe_alert_start_value" style="width: 50px; margin-left: 10px;" /><br/> and is expected to reach a value of <input type="number" id="probe_alert_expected_value" style="width: 50px; margin-left: 10px;" /> <br/>in <input type="number" id="probe_alert_expected_day_1" style="width: 50px;" /> to <input type="number" id="probe_alert_expected_day_2" style="width: 50px;" /> days.
            </div>-->
            <h1 style="color: #777; font-size: 12px; margin-bottom: -10px;"><span lang="en">Tolerance</span>:</h1>
            <div style="width: 100%;">
                <div style="width: 7%; float: left;">5%</div>
                <!--<input id="probe_alert_tolerance" type="range" style="width: 86%; float: left;" max="40" min="5" />-->
                <div style="width: 7%; float: left; text-align: right;">40%</div>
            </div>
            <div style="width: 95px; height: 100px; margin-top: 10px; margin-top: 30px; float: right;">
                <div style="width: 20px; height: 20px; border-radius: 20px; background-color: #FFF; float: left; margin-top: 10px; border: 1px solid #AAA;"></div>
                <div style="width: 66px; height: 22px; margin-left: 5px; float: left; margin-top: 10px;" ><span lang="en">Good</span></div>
                <div style="width: 20px; height: 20px; border-radius: 20px; background-color: rgba(194, 206, 218, 1.0); float: left; margin-top: 10px; border: 1px solid #AAA;"></div>
                <div style="width: 66px; height: 22px; margin-left: 5px; float: left; margin-top: 10px;" ><span lang="en">Tolerated</span></div>
                <div style="width: 20px; height: 20px; border-radius: 20px; background-color: rgba(165, 175, 185, 1.0); float: left; margin-top: 10px; border: 1px solid #AAA;"></div>
                <div style="width: 66px; height: 22px; margin-left: 5px; float: left; margin-top: 10px;" ><span lang="en">Alert</span></div>
            </div>
            <div style="width: 400px; height: 150px; margin: auto; margin-top: 34px;">
                <canvas id="probe_alert_graph" width="380" height="150">

                </canvas>
            </div>
            <!--<button id="probe_alert_clear_button" style="width: 99%; height: 35px; background-color: #D84840; border-radius: 5px; border: 0px solid #FFF; outline: none; color: #FFF; margin-left: 2px; margin-top: 100px;">Clear All</button>-->
            <button id="probe_alerts_save_button" style="width: 99%; height: 35px; background-color: #54BC00; border-radius: 5px; border: 0px solid #FFF; outline: none; color: #FFF; margin-left: 2px; margin-top: 10px;">Save</button>
            <button id="probe_alerts_button_back" style="width: 99%; height: 35px; background-color: #22AEFF; border-radius: 5px; border: 0px solid #FFF; outline: none; color: #FFF; margin-left: 2px; margin-top: 10px;">Back</button>
        </div>
        
    </div>
	
	
	
	 <!--- VENTANA MODAL  This has been added to show individual message content which user click on the inbox messages ---> 
   	 <button id="message_modal" data-target="#header-message" data-toggle="modal" class="btn btn-warning" lang="en">Modal with Header</button> 
   	  <div id="header-message" class="modal hide" aria-hidden="true">
         <div class="modal-header" lang="en">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  <span lang="en">Message Details</span>
         </div>
         <div class="modal-body">
         <div class="formRow" style=" margin-top:-10px; margin-bottom:10px;">
             <span id="ToDoctor"></span><input type="hidden" id="IdDoctor" value='-'/>
         </div>
         <textarea  id="messagedetails" class="span message-text" name="message" rows="1"></textarea>
         
		 <form id="replymessage" class="new-message">
                   <div class="formRow">
                        <label lang="en"><span lang="en">Subject</span>: </label>
                        <div class="formRight">
                            <input type="text" id="subjectname_inbox" name="name"  class="span"> 
                        </div>
                   </div>
				   <div class="formRow">
						<label lang="en"><span lang="en">Attachments</span>: </label>
						<div id="attachreportdiv" class="formRight">
							<input type="button" class="btn btn-success" value="Attach Reports" id="attachreports">
						</div>
				   </div>
                   <div class="formRow">
                        <label lang="en"><span lang="en">Message</span>:</label>
                        <div class="formRight tooltip-top" style="height:120px;">
                            <textarea  id="messagecontent_inbox" class="span message-text" name="message" rows="1"></textarea>
                            
                            <div class="clear"></div>
                        </div>
                   </div>
            </form>
			<div id="attachments">
			
			
			
			</div>
		 </div>
         <input type="hidden" id="Idpin">
        <!-- <input type="hidden" id="docId" value="<?php if(isset($user->med_id)) echo $user->med_id; ?>"/> -->
         <input type="hidden" id="userId" value="<?php if(isset($user->mem_id)) echo $user->mem_id; ?>" />
         <div class="modal-footer">
		     <input type="button" class="btn btn-info" value="Send message" id="sendmessages_inbox">
             <input type="button" class="btn btn-success" value="Attach" id="Attach">	
	         <input type="button" class="btn btn-success" value="Reply" id="Reply">			 
	         <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseMessage" lang="en">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 	

    <!--Content Start-->
	<div id="content" style='display:none;'>

	  <!--- VENTANA MODAL  phases wizard---> 
   	  <button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning"  lang="en">Modal with Header</button>
   	 <!--<div id="header-modal" class="modal hide" style="display: none; width: 80%; /* desired relative width */left: 10%; /* (100%-width)/2 */ /* place center */ margin-left:auto; margin-right:auto; " aria-hidden="true">-->
   	 <div id="header-modal" class="modal hide" aria-hidden="true">
         <div class="modal-header" style="height:60px;">
             <button class="close" type="button" data-dismiss="modal">×</button>
             <div style="width:90%; margin-top:12px; float:left;">
                 <div id="selpat">
                     <div style="font-size: 20px; font-weight: bold; width: 100%;"><span lang="en">Step</span> 1</div>
                     <span style="font-size: 12px; width: 100%;" lang="en">Select Patient</span>
                 </div>
                 <div id="seldr">
                     <div style="font-size:20px; font-weight:bold; width:100%; "><span lang="en">Step</span> 2</div>
                     <span style="font-size:12px; width:100%;" lang="en">Link to Patient</span>
                 </div>
             </div>
         </div>
         <div class="modal-body" style="height:300px;">
             <div id="ContentScroller">
                 <div id="ScrollerContainer" style="">
                        <div id="content_selpat">
                           <p>
                           	<!-- PATIENT SELECTION TABLE --->
							<div class="grid" style="float:left; width:90%; height:265px; margin: 0 auto; margin-left:2%; margin-top:0px; margin-bottom:0px; overflow:scroll;">
                                <div class="grid-title">
									<div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div><span lang="en">Select a Patient</span></div>
									<img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait2">
									<div class="pull-right"></div>
									<div class="clear"></div>   
								</div>
								<div class="search-bar">
									<input type="text" class="span" name="" placeholder="Search Patient" style="width:150px;" id="SearchUserT"> 
									<input type="button" class="btn btn-primary" value="Search" style="margin-left:50px;" id="BotonBusquedaPacNEW">
								</div>
								<div class="grid" style="margin-top:0px;">
									<table class="table table-mod" id="TablaPacModal" style="width:100%; table-layout: fixed; ">
									</table> 
								</div>
						   </div>
							<!-- PATIENT SELECTION TABLE --->
                           </p>
                        </div>    
                        <div id="content_seldr" style="float:left; text-align:center; width:900px; height:370px; ">
                            <div style="text-align:center; width:750px; height:170px;">
                                <div style="margin:0 auto; margin-top: 10px; width:360px; height:160px;">
                                    <div id="DivConnect" style="float:left; display: table-cell; vertical-align: middle; text-align:center; margin:0 auto; width:150px; height:150px; border:1px solid #cacaca; border-radius:5px; background-color:#f5f5f5">
                                        <div style="display: inline-block; width: 150px; height: 100px; margin-top:40px; text-align: center; ">
                                            <div id="IconConnect"><i class="icon-link icon-3x" ></i></div>
                                            <div style="margin-top:10px;"><span id="SpanConnect" lang="en">Connect Connect Connect Connect</span></div>
                                        </div>
                                    </div>
                                     <div id="DivInvite" style="float:left; text-align:center; margin:0 auto; margin-left:50px; width:150px; height:150px; border:1px solid #cacaca; border-radius:5px; background-color:#f5f5f5">
                                        <div style="display: inline-block; width: 150px; height: 100px; margin-top:40px; text-align: center; ">
                                            <div id="IconInvite"><i class="icon-tag icon-3x" ></i></div>
                                            <div style="margin-top:10px;"><span id="SpanInvite" lang="en">Invite Invite Invite Invite</span></div>
                                        </div>
                                     </div>
                                </div>
                                <div style="margin:0 auto; margin-top: 0px; width:400px; height:190px; ">
                                    <input id="IdPatient" type="hidden" value="0">
                                    <input id="UEmail" type="text" value="" placeholder="email" value="" >
                                    <input id="UPhone" type="text" value="" placeholder="phone number" value="" >
                                    <input id="UTempoPass" type="text" value="" placeholder="Temporary Password" value="">
                                    
                                </div>    
                             </div>
                        </div>
                        <div id="content_att" style="float:left; width:370px; height:50px; ">
                        </div>
                        <div id="content_addcom" style="float:left; width:370px; height:150px; padding:30px;">
                        </div>
                  </div>
             </div>
         </div>
         <input type="hidden" id="queId">
         <div class="modal-footer" style="height:120px;">
	         <div style="height:80px; width:100%:">
                    <p id="TextoSend" style="text-align:center; margin-top:0px; ">
                        <span style="color:grey;" lang="en">Send </span>
                        <span style="color:#54bc00; font-size:30px;">      </span>
                        <span style="color:grey;" lang="en"> to </span>
                        <span style="color:#22aeff; font-size:30px;">     </span>
                    </p>
             </div>
	         <div style="height:20px; width:100%:">
                 <input type="button" class="btn btn-success" value="SEND" id="SendButton" style="width:100px; display:none;">
                 <input type="button" class="btn btn-success" value="NEXT" id="Attach" style="visibility: hidden;">
                 <a href="#" class="btn btn-danger" data-dismiss="modal" id="CloseModal"  lang="en">Cancel</a>
                 <input type="button" class="btn btn-info" value="Previous" id="PhasePrev">
                 <input type="button" class="btn btn-success" value="NEXT" id="PhaseNext" style="width:100px;">
                 
             </div> 
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 

      <!--- VENTANA MODAL  2 ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
   	  <div id="header-modal2" class="modal hide" style="display: none; height:320px;" aria-hidden="true">
 <button id="BotonMod2" data-target="#header-modal2" data-toggle="modal" class="btn" style="float:right; margin-right:10px; display:none;" lang="en"><i class="icon-indent-left"></i>New</button>

         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4 lang="en">Connection with patient</h3>
                 <input type="hidden" id="IdUserSel"></input>
                 <input type="hidden" id="IdUserSwitch"></input>
         </div>
         
         <div class="modal-body" id="ContenidoModal2" style="height:170px;">
           <p lang="en">
           Reset your password. 
           </p>
           
         </div>
         <input type="hidden" id="queId">
        
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">
             <a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Proceed</a>--->
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal2" lang="en">OK</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL 2 ---> 

    <!-- CONNECT MEMBER MODAL WINDOW -->
    <div id="connectMemberDialog" title="Connect New Member" style="width: 600px; height: 600px; display: none">
        <div id="connectMemberStep1" style="width: 100%; height: 460px; margin-top: 20px; display: block;">
            <span style="color: #54BC00; font-size: 18px;">1. <span lang="en">Select member to connect</span></span>
            <style>
                .connectMemberRow{
                    width: 486px; 
                    height: 38px;
                    padding: 6px;
                    color: #333;
                    border: 1px solid #E6E6E6;

                }
                .connectMemberRow_bg1{
                    background-color: #FFF;
                }
                .connectMemberRow_bg2{
                    background-color: #F2F9EC;
                }
                .connectMemberRow span{
                    color: #777;
                    font-size: 12px;
                    margin-top: -10px;
                }
                .connectMemberRow:hover{
                    background-color: #54BC00;
                    border: 1px solid #54BC00;
                    color: #FFF;
                    cursor: pointer;
                }
                
                .connectMemberRow:hover span{
                    color: #FFF;
                }
                
                .connectMembersSearchBarButton{
                    width: 70px;
                    height: 30px;
                    background-color: #FAFAFA;
                    outline: 0px;
                    border: 1px solid #E7E7E7;
                    color: #7A7A7A;
                    border-top-right-radius: 5px;
                    border-bottom-right-radius: 5px;
                    text-align: center;
                    font-size:0.7em;
                    padding: 4px 8px 4px 8px;
                }


            </style>
            <div class="controls" style="width: 500px; margin: auto; margin-top: 20px;margin-bottom: 8px;">
                <input class="span7" id="connectMembersSearchBar" style="float: left; width: 430px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" lang="en" placeholder="Search Member (Name or Email)" type="text">
                <input class="btn btn-default connectMembersSearchBarButton" id="connectMembersSearchBarButton" lang="en" type="button" value="Search" />
            </div>
            <div id="connectMemberTable" style="border-radius: 5px; width: 500px; height: 400px; margin: auto; overflow: hidden; overflow-y: auto; margin-top: 15px;">

            </div>
        </div>
        <div id="connectMemberStep2" style="width: 100%; height: 460px; margin-top: 20px; display: none; padding: 0px;">
            <span style="color: #54BC00; font-size: 18px;">2. <span lang="en">Share Reports</span></span>
            <style>
                ::-webkit-scrollbar {
                    -webkit-appearance: none;
                    width: 7px;
                }
                ::-webkit-scrollbar-thumb {
                    border-radius: 4px;
                    background-color: rgba(0,0,0,.15);
                    -webkit-box-shadow: 0 0 1px rgba(255,255,255,.5);
                }
            </style>
            <div id="share_files_container" style="overflow-y: hidden; overflow-x: scroll; width: 100%; height: 477px;">
            </div>
            <div style="width: 315px; height: 30px; margin: auto;">
                <button id="connectMemberSharePrevButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px; float: left;">Prev</button>
                    <button id="connectMemberShareNextButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px;float: right;">Next</button>
            </div>
        </div>
        <div id="connectMemberStep3" style="width: 100%; height: 460px; margin-top: 20px; display: none;">
            <span style="color: #54BC00; font-size: 18px;">3. <span lang="en">Member Details</span></span>
            <div style="width: 500px; margin: auto; margin-top: 20px;">
                <div style="width: 100%; height: 40px;">
                    <div style="width: 60px; float: left; color: #666; padding-top: 5px;"><span lang="en">Email</span>:</div> <input type="text" id="connectMemberEmail" placeholder="Member Email" style="float: left; width: 400px;" />
                </div>
                <div style="width: 100%; height: 40px;">
                    <div style="width: 60px; float: left; color: #666; padding-top: 5px;"><span lang="en">Phone</span>:</div> <input type="tel" id="connectMemberPhone" placeholder="Member Phone Number" style="float: left; width: 400px;" />
                </div>
                <div style="width: 100%; height: 40px; margin-top: 25px;">
                    <div style="width: 300px; height: 15px; padding: 7px; background-color: #FAFAFA; border-radius: 15px; border: 1px solid #DDD; margin: auto;">
                        <button id="connectMemberSubscribeButton" style="width: 15px; height: 15px; outline: none; border: 1px solid #DDD; background-color: #FFF; border-radius: 15px; float: left; color: #54BC00; font-size: 12px;">
                        </button>
                        <div style="color: #666; padding-left: 17px; float: left; margin-top: -2px;"><span lang="en">Subscribe this member to a probe</span></div>
                    </div>
                </div>
                <div id="connectMembersProbeSection" style="height: 200px; margin-top: 25px; display: none;">
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;"><span lang="en">Select Probe</span>:</div>
                    <select id="connectMember_probe_protocols" style="width: 57%; height: 30px;">
                    </select>
                    <button id="connectMember_edit_probes" style="width: 5%; height: 29px; margin-top: -10px; margin-left: 4px; border: 0px solid #FFF; outline: none; background-color: #54BC00; color: #FFF; border-radius: 5px;">
                        <i class="icon-pencil"></i>
                    </button>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;"><span lang="en">Select Time</span>:</div>
                    <input id="connectMember_probe_time" type="text"  style="width: 61%; height: 18px;"/>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;"><span lang="en">Select Timezone</span>: </div>
                    <select id="connectMember_probe_timezone" style="width: 64%; height: 30px;">
                        <option value="3" lang="en">US Pacific Time</option>
                        <option value="4" lang="en">US Mountain Time</option>
                        <option value="2" lang="en">US Central Time</option>
                        <option value="1" lang="en">US Eastern Time</option>
                        <option value="5" lang="en">Europe Central Time</option>
                        <option value="6" lang="en">Greenwich Mean Time</option>
                    </select>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;"><span lang="en">Select Method</span>: </div>
                    <select id="connectMember_probe_method" style="width: 64%; height: 30px;">
                        <option value="1" lang="en">Text Message</option>
                        <option value="2" lang="en">Phone Call</option>
                        <option value="3" lang="en">Email</option>
                    </select>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;"><span lang="en">Select Interval</span>: </div>
                    <select id="connectMember_probe_interval" style="width: 64%; height: 30px;"> 
                        <option value="1" lang="en">Daily</option>
                        <option value="7" lang="en">Weekly</option>
                        <option value="30" lang="en">Monthly</option>
                        <option value="365" lang="en">Yearly</option>
                    </select>
                </div>
            </div>
            <div style="width: 450px; height: 30px; margin: auto; text-align: center;">
                <button id="connectMemberCheckoutPrevButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px; margin: auto;">Prev</button>
                <button id="connectMemberCheckoutButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px; margin-left: 15px;">Next</button>
            </div>
        </div>
        <div id="connectMemberStep4" style="width: 100%; height: 460px; margin-top: 20px; display: none;">
            <span style="color: #54BC00; font-size: 18px;">4. <span lang="en">Billing</span></span>
            <div style="width: 500px; margin: auto; margin-top: 20px; text-align: center;">
                <button id="connectMemberPayNow" style="width: 135px; height: 25px; background-color: #54BC00; color: #FFF; border: 0px solid #FFF; border-top-left-radius: 5px; border-bottom-left-radius: 5px; outline: none; margin-bottom: 7px;" data-on="1">
                    <span lang="en">Doctor Pay Now</span>
                </button>
                <button id="connectMemberCardPayNow" style="width: 135px; height: 25px; background-color: #F8F8F8; color: #555; border: 1px solid #DDD; outline: none; margin-bottom: 7px;" data-on="0">
                    <span lang="en">Member Pay Now</span>
                </button>
                <button id="connectMemberAssignPaymentToPatient" style="width: 135px; height: 25px; background-color: #F8F8F8; color: #555; border: 1px solid #DDD; border-top-right-radius: 5px; border-bottom-right-radius: 5px; outline: none; margin-bottom: 7px;" data-on="0">
                    <span lang="en">Assign Payment To Patient</span>
                </button>
                <div id="connectMemberPayNowSection">
                    <div style="text-align: center; width: 100%; height: 19px;" id="creditsLabel"><span lang="en">You have</span> $<span id="numDoctorCredits"><?php echo $user->doctor_credits / 100; ?></span> <span lang="en">left</span></div>
                    <div id="creditsLoadingBar" style="width: 100%; height: 19px; text-align: center; display: none;">
                        <img src="images/load/8.gif" />
                    </div>
                    
                    
                    <button id="purchaseMoreCreditsButton" style="width: 100%; height: 30px; border: 0px solid #FFF; background-color: #54BC00; color: #FFF; border-radius: 5px; color: #FFF; outline: none;" data-on="0">
                        <span lang="en">Purchase More Credits</span> <i class="icon-caret-down"></i>
                    </button>
                    <div id="connectMemberPurchaseCredits" style="display: none;">
                        <div style="width: 100%; height: 40px; margin-top: 20px;">
                            <div style="width: 200px; float: left; color: #666; padding-top: 5px;"><span lang="en">Amount</span>:</div> 
                            <input type="number" min="1" id="connectMemberNumCredits" placeholder="Amount to Purchase" style="float: left; width: 260px;" />
                        </div>
                        <div style="width: 100%; height: 40px;">
                            <div style="width: 200px; float: left; color: #666; padding-top: 5px;"><span lang="en">Credit Card Number</span>:</div> <input type="text" id="connectMemberCreditCard" placeholder="Credit Card Number" style="float: left; width: 260px;" />
                        </div>
                        <div style="width: 100%; height: 40px;">
                            <div style="width: 200px; float: left; color: #666; padding-top: 5px;"><span lang="en">Expiration Date</span>:</div> <input type="month" id="connectMemberExpDate" style="float: left; width: 260px;" />
                        </div>
                        <div style="width: 100%; height: 40px;">
                            <div style="width: 200px; float: left; color: #666; padding-top: 5px;">CVC:</div> <input type="password" id="connectMemberCVC" placeholder="CVC" style="float: left; width: 260px;" />
                        </div>
                    
                        <div style="width: 200px; height: 30px; margin: auto; margin-top: 15px;">
                            <button id="connectMemberPurchaseTokensButton" style="width: 200px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px; margin: auto;">
                                <span lang="en">Purchase Credits</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <center><div id="probe-review-section" style="height:30px;display:none;background-color:#54BC00;border-radius:5px;margin-top:10px;width: 94%; margin: auto;padding: 2%;">
									<div>
										<span id="review-cc-number" style='float:left;margin-left:30px;'></span>
										<button onclick="change_cards();" class="btn btn-default" style="float:right;border-radius:10px;" lang="en">Change Card</button>
									</div>
								</div></center>
								</div></center>
                
                <div id="connectMemberPayNowSectionCard" style='display:none;'>
						<div class="probe_cc_section" style="height:115px;">
							<style>
								.credit_card_row{
									background-color: #FBFBFB;
									color: #222;
									border: 1px solid #E6E6E6;
									width: 96%;
									height: 35px;
									padding: 4px;
								}
							</style>
							<div id="credit_cards_container" style="width: 300px; margin-left: auto; margin-right: auto; height: 0px; overflow: scroll;display:none;">
							<i class="fa fa-spinner fa-spin"></i>
							</div>
							<div style="margin-top: 10px; width: 100%; margin-left: auto; margin-right: auto;">
								<script>
								function isNumberKey(evt)
								{
									var charCode = (evt.which) ? evt.which : event.keyCode
									if (charCode > 31 && (charCode < 48 || charCode > 57))
										return false;
									return true;
								}    
								</script>
								<div style='width:100%;height:150px;margin-top:100px;'>
									<center>
									<input type="text" onkeypress="return isNumberKey(event)" id="credit_card_number" maxLength="16" placeholder="Enter card number" style="width: 220px; height: 30px; border-radius: 5px;">
									<input id="credit_card_csv_code" type="text" onkeypress="return isNumberKey(event)" id="csv_code" maxLength="3" placeholder="CSV" style="width: 85px; height: 30px; border-radius: 5px;">
									<div>
										<div style="margin-left:117px;float:left;color: #969696; width: 80px; text-align: left; padding-left: 5px; border-top-left-radius: 5px; border-bottom-left-radius: 5px; border: 1px solid #CACACA; height: 30px; padding-top: 5px; border-right: 0px solid #FFF;" lang="en">Exp. Date:</div>
										<input id="credit_card_exp_date" type="month" style="float:left;width: 155px; height: 27px; font-size: 12px; border-radius: 0px; border-left: 0px solid #FFF; border-top-right-radius: 5px; border-bottom-right-radius: 5px;" />
									</div>
									<button onclick="add_credit_card2();" id="add_card_button" style="display:none;margin-top:3px;margin-left:-90px;width: 100px; height: 30px; background-color: #52D859; border-radius: 0px; border: 0px solid #FFF; color: #FFF; outline: 0px; border-radius: 5px;" lang="en">Add Card</button>
									</center>
								</div>
								</div>
								</div>
								
								
                    </div>
                
                <center><div><div style="width: 100%; height: 15px; margin-top: 45px;" lang="en">For how many months would you like to run this probe?</div>
                <select id="connectMemberMonths" style="margin-top: 10px;" id="month-count">
                    <option value="1">1 <span lang="en">Month</span></option>
                    <option value="2">2 <span lang="en">Months</span></option>
                    <option value="3">3 <span lang="en">Months</span></option>
                    <option value="4">4 <span lang="en">Months</span></option>
                    <option value="5">5 <span lang="en">Months</span></option>
                    <option value="6">6 <span lang="en">Months</span></option>
                    <option value="7">7 <span lang="en">Months</span></option>
                    <option value="8">8 <span lang="en">Months</span></option>
                    <option value="9">9 <span lang="en">Months</span></option>
                    <option value="10">10 <span lang="en">Months</span></option>
                    <option value="11">11 <span lang="en">Months</span></option>
                    <option value="12">12 <span lang="en">Months</span></option>
                </select>
                
                <div style="width: 100%; height: 15px; margin-top: 10px;"><span lang="en">Cost</span>: <span style="font-weight: bold">$<span id="costCredits">10</span></span></div>
                </div>
                </center>
                <div style="width: 150px; height: 30px; margin: auto; margin-top: 20px;">
                        <button id="connectMemberFinishButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px;float: right;">Finish</button>
                </div>
                <div id="connectMemberFinishCode" style="width: 100%; height: 100px; padding-top: 80px; margin-top: 10px; display: none; text-align: center;">
                    <span style="color: #333; font-size: 18px;"><span lang="en">Member Code</span>: <span style="color: #54BC00;">X10-PFe8Dfi</span></span><br/>
                    <span style="color: #888;" lang="en">Please provide this code to the member.</span>
                </div>
            </div>
        </div>
        <div id="connectMemberFinalStep" style="width: 100%; height: 260px; padding-top: 200px; margin-top: 20px; display: none; text-align: center;">
            <span style="color: #333; font-size: 18px;"><span lang="en">Member Code</span>: <span style="color: #54BC00;">X10-PFe8Dfi</span></span><br/>
            <span style="color: #888;" lang="en">Please provide this code to the member.</span>
        </div>
    </div>

	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
		
    	 <li><a href="MainDashboard.php" lang="en">Home</a></li>
         <?php
                                    
                $arr=$user->checkAccessPage("dashboard.php");
                $arr_d=json_decode($arr, true);

                if((count($arr_d['items'])>0)&&($arr_d['items'][0]['accessid']==1)){ 
                
                    echo '<li><a lang="en" href="dashboard.php"  lang="en">Dashboard</a></li>';
                }
         ?>
		 
    	 <!--li><a href="patients.php"  lang="en">Members</a></li-->
		 <?php if ($user->doctor_privilege==1)
		 {
				 echo '<li><a href="medicalConnections.php"  lang="en">Doctor Connections</a></li>';
				 echo '<li><a href="PatientNetwork.php" class="act_link" lang="en">Member Network</a></li>';
		 }
		 ?>
         <li><a href="medicalConfiguration.php" lang="en">Configuration</a></li>
         <li><a href="logout.php" style="color:yellow;" lang="en">Sign Out</a></li>
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
	 
	 
     
     
     <!--CONTENT MAIN START-->
	<!-- <div class="content" style="" onmouseover="setGrabQuery2();setGrabQuery3()">-->
     <div class="content" style="">
         
            <div class="grid" class="grid span4" style="width:1000px; min-height:1300px; margin: 0 auto; margin-top:30px; padding-top:30px;">
                

                <span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;" lang="en">Member Network</span>
                <!-- UPPER INFORMATION AREA --->     
                <div class="row-fluid"  style="width:940px; margin-left:30px; margin-right:0px; margin-top:0px;">	            
                            <input type="hidden" id ="quePorcentaje" value="<?php if(isset($porcentajeCreados)) echo ($porcentajeCreados) ?>" /> 
                    
                    <div class="grid" style="padding:10px; height:110px;">
                    <div id="WaitHeader" style="margin-top:25px;margin-left:420px; float:left; display: inline;">
                        <span style="font-size:35px; color:#cccccc;" id="H2M_Spin_Stream">
					       <center><i id="connect_spinner" class="icon-spinner icon-2x icon-spin" ></i></center>
                        </span>
                    </div>        
							
							<!-- MODAL VIEW TO MAKE APPOINTMENTS -->
                    <div id="make_appointment" title="Create Appointment" style="display:none;">
                        <div id="appointments_content" style="width: 100%; height: 320px;">
                            <div id="appointments_container" style="width: 100%; margin-top: 10px; text-align: center; height: 300px; overflow: auto; overflow-x: hidden; border-radius: 5px; padding-top: 10px; padding-bottom: 10px;">

                            </div>
                            <div id="add_appointments" style="width: 92%; height: 0px; border: 1px solid #ECECEC; border-radius: 5px; padding: 2%; padding-left: 4%; padding-right: 4%; background-color: #FCFCFC; color: #777; display: none; margin-top: 10px;">
                                <button style="width: 40px; height: 30px; float: right; border: 0px solid #FFF; border-radius: 5px; outline: none; background-color: #D84840; color: #FFF; margin-top: 3px;"  id="add_appointment_cancel_button">
                                    <i class="icon-remove"></i>
                                </button>
                                <button style="width: 40px; height: 30px; float: right; border: 0px solid #FFF; border-radius: 5px; outline: none; background-color: #54BC00; color: #FFF; margin-right: 15px; margin-top: 3px;" id="add_appointment_add_button">
                                    <i class="icon-plus"></i>
                                </button>
                                <div style="width: 60px; height: 30px; border-radius: 5px; background-color: #535353; float: right; margin-right: 30px; margin-top: 3px;">
                                    <button style="width: 30px; height: 30px; border-top-left-radius: 3px; border-bottom-left-radius: 3px; background-color: #22aeff; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="new_appointment_video_button">
                                        <i class="icon-facetime-video"></i>
                                    </button>
                                    <button style="width: 30px; height: 30px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; background-color:  #535353; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="new_appointment_phone_button">
                                        <i class="icon-phone"></i>
                                    </button>
                                </div>
                                <span lang="en">Date</span>: <input style="width: 150px; margin-left: 10px; margin-right: 10px; margin-top: 4px;"id="add_appointment_date" type="date" />
                                <span lang="en">Time</span>: <input style="width: 70px; margin-left: 10px; margin-top: 4px;" id="add_appointment_time" type="text" />
                            </div>
                        </div>
                        <div style="width: 100%; height: 40px;">
                            <button style="width: 100%; height: 30px; background-color: #54BC00; border: 0px solid #FFF; outline: none; color: #FFF; border-radius: 5px; font-size: 14px;  margin-top: 10px;" id="add_appointment_new_button">
                                <span lang="en">Add Appoinment</span>
                            </button>
                        </div>
                    </div>
                            <!-- END MODAL VIEW TO MAKE APPOINTMENTS-->
                            <!--Stacked Bar Chart-->
                    <div id="header-stackedBar" style="display:none;">
                        <div style="float:left;height:100px; width:350px; margin-top: 6px;">
                            <div class="phs_label" style="width:350px; font-size:16px; color:#b6b6b6; margin-bottom:12px;">
                                <center><span lang="en">Total</span> 
                                    <span id="num_patients" style="font-size:20px; color:#449800;">
                                    <p id="TotPats">
                                        <span id="H2M_Spin_Stream"></span>
                                    </p>
                                    </span> <span lang="en">Patients</span>
                                </center>
                            </div>

                            <div class="NNot" style="float:left; height:40px; line-height: 40px; width:0px;  background-color:#54bc00; text-align: center;"> <span id="NNot_green" style="font-size:20px; color:white;"></span></div>
                            <div class="NCon" style="float:left; height:40px; line-height: 40px; width:0px;   background-color:#449800; text-align: center;"> <span id="NCon_green" style="font-size:20px; color:white;"></span></div>
                            <!--div class="NTra" style="float:left; height:40px; line-height: 40px; width:0px;  background-color:#357601; text-align: center;"> <span id="NTra_green" style="font-size:20px; color:white;">52</span></div-->

                            <div style="width:100%; float:left;"></div>

                            <div class="NNot_label" style="float:left; width:250px; font-size:14px; color:#54bc00; text-align: center; line-height: 100%; margin-top:12px; min-width: 100px;">
                            <div id="container_NNot" style="display: inline-block;"><i class="fa fa-chain-broken" style="color:#22aeff;"></i> <span lang="en">Not Connected</span></div>
                            </div>

                            <div class="NCon_label" style="float:left; width:250px; font-size:14px; color:#449800; text-align: center; line-height: 100%; margin-top:12px; min-width: 100px;">
                            <div id="container_NCon" style="display: inline-block;"><i class="fa fa-link" style="color:#22aeff;"></i> <span lang="en">Connected</span></div>
                            </div>

                            <!--div class="NTra_label" style="float:left; width:250px; font-size:14px; color:#357601; text-align: center; line-height: 100%; margin-top:12px; min-width: 100px;">
                            <div id="container_NTra" style="display: inline-block;"><i class="fa fa-tag" style="color:#22aeff;"></i> <span>Tracking</span></div>
                            </div-->

                        </div>    
                        <!--Stacked Bar END-->

                        <!-- Patient TRACKING Count-->
                        <div style="float:left; height:100px; width:120px; text-align: center;margin: 6px 0 0 18px;" title="This is the number of patients who receive probes from you">
                            <div style="display: inline-block; width:100%; height: 21px; line-height:21px; margin-bottom: 12px;"><center style="font-size:14px; color:#b6b6b6; "><span lang="en">Tracking</span></center> </div>
                            <div style="display: inline-block; width:85px;line-height: 40px; height:40px; text-align:center; background-color:orange;"><span id="trkCount" style="color:white; font-size:20px;"></span> </div>
                            <div style="display: inline-block; width:100%; height: 14px; margin-top: 12px;"><center style="font-size:14px; color:#b6b6b6; "><span lang="en">Members</span></center> </div>
                        </div>
                        <!--Patient Tracking done-->
                                	
                        <!--New Patient Activity Count-->
                        <div id="ActivitySCROLL" style="float:left; height:100px; width:120px; text-align: center; margin-top:6px; overflow:hidden;" title="This is the total activities of your patients within 30 days">
                            <div style="height:100px; width:100%;">
                                
                                
                                <div style="display: inline-block; width:100%; height: 21px; line-height:21px; margin-bottom: 12px;"><center style="font-size:14px; color:#b6b6b6; "><span lang="en">You have</span></center> </div>
                                <div style="display: inline-block;  width:85px; line-height: 40px; height:40px; text-align:center; background-color:#22aeff;"><span id="newActsUp" style="color:white; font-size:20px;"></span> </div>
                                <div style="display: inline-block;  height: 14px; width:100%; margin-top: 12px;"><center style="font-size:14px; color:#b6b6b6; "><span lang="en">New Activities</span></center> </div>
                                
                                <div style="display: inline-block; width:100%; height: 21px; line-height:21px; margin-bottom: 12px;"><center style="font-size:14px; color:#b6b6b6; "><span lang="en">From</span></center> </div>
                                <div style="display: inline-block;  width:85px; line-height: 40px; height:40px; text-align:center; background-color:#22aeff;"><span id="newPats" style="color:white; font-size:20px;"></span> </div>
                                <div style="display: inline-block;  height: 14px; width:100%; margin-top: 12px;"><center style="font-size:14px; color:#b6b6b6; "><span lang="en">Members</span></center> </div>                             
                                
                            </div>
                        </div>
                        <!--New PAtient Activity done-->
                            
                
                            
                            
                            
                            
                            
                        
                                
                        <!--buttons_doctor-->
                        <div id="buttons_doctor" style="float:left; margin: 30px auto; text-align: center; width: 33%;">
                            <div style="width:20%; float: left;">
                                <span id="repUp_bubble" style="background-color:red; margin-left: 40px;" class="H2MBaloon" ></span>
                                <span id="repUp" class="patient_row_button filter" title="Report updated in the last 30 days"> 
                                    <i class="icon-folder-open" style="margin-left: -5px;"></i>   
                                </span>
                                
                            </div>
                            
                            <div style="width:20%; float: left;">
                                <span id="sumUp_bubble" style="background-color:red; margin-left: 40px;" class="H2MBaloon" ></span>
                                <span id="sumUp" class="patient_row_button filter" title="Summary changed in the last 30 days">
                                    <i class="icon-edit" style="margin-left: -5px;"></i>
                                </span>
                            </div>
                            
                            
                            <div style="width:20%; float: left;">
                                <span id="msgUp_bubble" style="margin-left: 40px;" class="H2MBaloon" ></span>
                                <span id="msgUp" class="patient_row_button filter" title="Messages in the last 30 days">
                                    <i class="icon-envelope-alt" style="margin-left: -8px;"></i>      
                                </span>
                                
                            </div>
                            <div style="width:20%; float: left;">
                                <span id="prbUp_bubble" style="background-color:red; margin-left: 40px;" class="H2MBaloon" ></span>
                                <span id="prbUp" class="patient_row_button filter" title="Probe responses in the last 30 days">
                                    <i class="icon-signal" style="margin-left: -8px;"></i>
                                </span>   
                            </div>
                            
                            <div style="width:20%; float: left;">
                                <span id="apptUp_bubble" style="background-color:red; margin-left: 40px;" class="H2MBaloon" ></span>
                                <span id="apptUp" class="patient_row_button filter" title="Appointment made in the last 30 days">
                                    <i class="icon-calendar" style="margin-left: -9px;"></i>
                                </span>
                            </div>
                            
                        </div>
                        <!--buttons doctor done-->   
                        <div class="clear"></div>   
                                                    
                    </div>
                </div>	
                <!-- UPPER INFORMATION AREA --->     

                
                <!-- PENDING TASKS TABLE --->     
                <input type="hidden" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaPending">
                <div id="PendingTasks" style="width:940px; margin-left:30px; margin-right:0px; margin-top:30px; display:none;" >
                        <div class="grid" style="width:100%; min-height:100px; max-height:300px;margin: 0 auto; overflow-y:scroll;">
                                    <div class="grid-title">
                                        <div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div><span lang="en">Pending Confirmations and Invitations</span></div>
               
                                            <img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">
    
                                        <div class="pull-right"></div>
                                        <div class="clear"></div>   
                                    </div>
                                    <div class="grid" style="margin-top:0px; ">
                                       <div style="min-height:56px; ">
                                            <table class="table table-mod" id="TablaPacPending" style="width:100%; table-layout: fixed; ">
                                            </table> 
                                        </div>
                                    </div>
                        </div>
               </div>  
                <!-- PENDING TASKS TABLE --->     
                
        <!--TAB Start-->
            <!--<li class="active" style="width:50%; " id="probeTab"><a href="#probe"  data-toggle="tab" lang="en">Probes</a></li>
			Connected Members-->
			<br />
			<div class="search-bar" style="border-bottom:none;">
                <input type="text" class="span" name="" lang="en" placeholder="Search Member" style="width:150px;" id="SearchUserUSERFIXED">               
                <input type="button" class="btn btn-primary" lang="en" value="Filter" style="margin-left:15px; margin-right:15px;" id="BotonBusquedaSents">
                 
                <input id="trackToggle" type="checkbox">

                <input type="button" class="btn btn-success" lang="en" value="Connect New Member" style="margin-left:15px;" id="ConnectMemberButton">
                <div style="width:28%; margin-left:15px; display:inline-block;">
                    <div style="display:inline-block;">
                            <span id="filterstatus" style="color:#22AEFF; font-size:12px; font-weight:bold; text-align: right; display:none;"></span>
                    </div>
                </div>
										
									
										<div style="float:right;" class="the-icons">
											<i class="icon-chevron-right" style="padding:10px 10px;float:right;margin-right:0px;" id="nextPatients"></i>
											<label style="padding:10px;float:right;margin-right:0px;margin-top:-4px;" id="CurrentPage"></label>
											<i class="icon-chevron-left" style="padding:10px ;float:right;margin-right:0px;" id="prevPatients"></i>
										</div>
										
										<!--<div style="float:right; margin-right:100px;">
											<label class="checkbox toggle candy" onclick="" style="width:100px;">
												<input type="checkbox" id="CToggle" name="CToggle" />
												<p>
													<span lang="en">Probe</span>
													<span lang="en">All</span>
												</p>
												
												<a class="slide-button"></a>
											</label>
										</div>
                                        <!--<input type="button" class="btn btn-success" value="Connect more patients" id="BotonWizard" style="margin-left:250px; margin-top:0px; width:250px;">-->
										<!--
                                        <div style="float:right; margin-right:100px;">
											<label class="checkbox toggle candy blue" onclick="" style="width:100px;">
												<input type="checkbox" id="RetrievePatient" name="RetrievePatient" />
												<p>
													<span title="Search in only in group">Group</span>
													<span title="Search all patients">All</span>
												</p>
												
												<a class="slide-button"></a>
											</label>
										</div>
                                        -->
                                    </div>
            
          <div id="myTabContent" class="tab-content tabs-main-content padding-null" style="overflow-x:hidden;width:calc(100% - 2px); height:857px;overflow:hidden;">
                    <style>
                        .probe_chart_button{
                            float: left; 
                            width: 20px; 
                            margin-right: 10px; 
                            height: 20px; 
                            border-radius: 20px; 
                            border: 1px solid #B8B8B8; 
                            outline: none; 
                            color: #AAA; 
                            background-color: #F8F8F8; 
                            font-size: 10px;
                            cursor: pointer;
                        }
                        .probe_chart_button:disabled{
                            border: 1px solid #DDD; 
                            color: #DDD;
                            cursor: default;
                            background-color: #FBFBFB; 
                        }
                        .probe_chart_button_selected{
                            float: left; 
                            width: 20px; 
                            margin-right: 10px; 
                            height: 20px; 
                            border-radius: 20px; 
                            border: 1px solid #22AEFF; 
                            outline: none; 
                            color: #FFF; 
                            background-color: #22AEFF; 
                            font-size: 10px;
                            cursor: default;
                        }
                    </style>
                    <link rel="stylesheet" href="css/network_styles.css">
                    <span style="font-size:30px; color:#cccccc; display:none;" id="loading_spinner">
                        <center style="margin-top:20px;"><i id="connect_spinner" class="icon-spinner icon-2x icon-spin"></i></center>
                    </span>
					<div id="TablaSents" class="tab-pane tab-overflow-main fade in active" style="width:940px; margin-left:5px; margin-right:0px; margin-top:5px;">
                    
					<div id='vitals-window' title="Review Vitals" style='display:none;'></div>



					
					</div>
                    <!--<span style="font-size:40px;" id="H2M_Spin_Stream">
					       <center><i id="connect_spinner" class="icon-spinner icon-2x icon-spin" ></i></center>
					</span>-->
					
	
					
						<!--<div class="tab-pane " id="cpatients">-->
						<div class="tab-pane" style="width:940px; margin-left:30px; margin-right:0px; margin-top:30px;"  id="cpatients" >
								
								
								
								
								<div class="grid" style="width:95%; margin: -8px;" id="connectedPatientsGrid">
                                    <div class="grid-title">
                                        <div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div><span lang="en">Connected Members</span></div>
										<img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait4">
                                            
                                        <div class="pull-right"></div>
                                        <div class="clear"></div>   
                                    </div>
                                    <div class="search-bar" >
                                        <input type="text" class="span" name="" lang="en" placeholder="Search Member" style="width:150px;" id="SearchUserUSERFIXED"> 
                                        <input type="button" class="btn btn-primary" lang="en" value="Filter" style="margin-left:50px;" id="BotonBusquedaSents">
                                        <input type="button" class="btn btn-success" lang="en" value="Connect more members" id="BotonWizard" style="margin-left:50px; margin-top:0px;">
                                    </div>
                                    <div class="grid" style="margin-top:0px;">
                                       <div style="max-height:800px; overflow-y:scroll; overflow-x:hidden">
                                            <table class="table table-mod" id="TablaSents" style="width:100%; table-layout: fixed; ">
                                            </table>
											<center>
												<div id = "prev" style = "width: 75px; height: 50px; margin-right: 100px; display: inline-block; cursor: pointer" lang="en">
												   <i class = "icon-chevron-left"></i> Previous
												</div>
												<div id = "pageDisplay" style = "display: inline-block;">
													
												</div>
												<div id = "next" style = "width: 75px; height: 50px; margin-left: 100px; display: inline-block; cursor: pointer" lang="en">
													Next <i class = "icon-chevron-right"></i> 
												</div>
											</center>
											<div style="width:100%; height:2px;"></div>
		
                                        </div>
                                    </div>
								</div>
						
						
						
						</div>
					


				
		  </div>	 		  
		<!---COMPOSE MODAL--->
		<div id="compose_modal"  title="Messages" style="display: none; width: 600px; height: 500px;">
            <div id="compose_messages"  style="width: 570px; height: 380px; overflow: auto; margin-top: 10px;">
            </div>
            <div id="compose_new" style="width: 500px; height: 380px; margin-left: 40px; display: none; margin-top: 10px;">
                <strong><span lang="en">To</span>: </strong><span id="compose_message_recipient_label"></span><br/><br/>
                <strong><span lang="en">Subject</span>: </strong><br/><input type="text" id="compose_message_subject" style="width: 500px; margin-top: 5px;" /><br/>
                <strong><span lang="en">Message</span>:</strong><br/>
                <textarea id="compose_message_content" style="width: 500px; height: 200px; margin-top: 15px;"></textarea>
            </div>
            <div id="compose_attachments" style="margin-left: 2%; width: 96%; height: 380px; display: none;">
                <div style="width: 100%; height: 30px;">
                    <div style="width: 14%; height: 30px; margin-top: 4px; float: left; color: #666"><span lang="en">Select Member</span>:</div>
                    <select id="compose_select_member" style="width: 58%; float: left;">

                    </select>
                    <button style="width: 12%; float: left; margin-left: 2%; height: 30px; border: 0px solid #FFF; border-radius: 5px; outline: none; background-color: #22AEFF; color: #FFF;"><span lang="en">Select All</span></button>
                    <button style="width: 12%; float: left; margin-left: 2%; height: 30px; border: 0px solid #FFF; border-radius: 5px; outline: none; background-color: #D84840; color: #FFF;"><span lang="en">Deselect All</span></button>
                </div>
                <div id="compose_attachments_container" style="width: 100%; height: 300px; margin-top: 15px; background-color: #333;">

                </div>
            </div>
            <div style="width: 100%; height: 30px; padding-top: 10px; margin-top: 20px;">	 
                <input type="button" class="btn btn-success" style="float: right; margin-left: 8px; display: none; margin-right: 18px;" value="Send" id="ComposeSendButton">
                <input type="button" class="btn btn-success" value="Compose" id="ComposeNewButton" style="float: right; margin-left: 8px; margin-right: 18px;">
                <input type="button" class="btn btn-success" value="Messages" id="ComposeMessageButton" style="display: none; float: right; margin-left: 8px;">
                <input type="button" class="btn btn-danger" style="float: right; margin-left: 8px;" value="Cancel" id="ComposeCancelButton" style="display: none;">
            </div>
        </div>
        <!---COMPOSE MODAL END--->
	
	<!--Probe Console MODAL--->
		<div id="probe_console" title="Probe Console" style="display: none; width: 600px; height: 500px;">
        
    </div>
	<!---Probe Console END--->
		
                <!-- COMMUNICATIONS & MESSAGING --->     
                <div class="row-fluid"  style="">	            
                 <div class="grid" class="grid span4" style="height:420px; padding:10px; ">
                    <span class="label label-info" id="EtiTML" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">User & Doctor Communications Area</span>
                    <ul id="myTab" class="nav nav-tabs tabs-main">
                    <li class="active"><a href="#inbox" data-toggle="tab" id="newinbox" lang="en">InBox</a></li>
                    <li><a href="#outbox" data-toggle="tab" id="newoutbox" lang="en">OutBox</a></li></ul>
                    <div id="myTabContent2" class="tab-content tabs-main-content padding-null" style="padding: 0; padding-top:10px;">
                                   
                    <div class="tab-pane tab-overflow-main fade in active" id="inbox" >
                    <div class="message-list" ><div class="clearfix" style="margin-bottom:40px;">
                    <div class="action-message" ><div class="btn-group">
                   
                    <button id="delete_message" class="btn b2"><i class="icon-trash padding-null"></i></button>
                    <!--<input type="button" style="margin-left:10px" class="btn b2" value="Create Message" id="compose_message">-->
                  
                    </div></div>
                    </div>
                        <div style="height:280px; overflow:auto; ">
                            <table class="table table-striped table-mod" id="datatable_3"></table>
                        </div>
                    </div></div>
                    <div class="tab-pane" id="outbox">
                    <div class="message-list"><div class="clearfix" style="margin-bottom:40px;">
                    <div class="action-message"><div class="btn-group">
                    <button id="delete_message_outbox" class="btn b2"><i class="icon-trash padding-null"></i></button>
                    </div></div>
                    </div>
                        <div style="height:280px; overflow:auto; ">
                            <table class="table table-striped table-mod" id="datatable_4"></table> 
                        </div>
                    </div>
                    </div>
                    </div>
            </div>     
			<div id="summary_modal" lang="en" title="Summary" style="display:none; text-align:center; width: 1000px; height: 660px; overflow: hidden;">
    </div>
            <!-- COMMUNICATIONS & MESSAGING --->     
            <div style="width:100%; height:2px;"></div>     
                
            <!-- PATIENT SELECTION TABLE --->
            
            <div id="OLDpatientstable" class="grid" style="float:left; width:90%; height:300px; margin: 0 auto; margin-left:2%; margin-top:30px; margin-bottom:30px; overflow:scroll;">
                    <div class="grid-title">
                        <div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div><span lang="en">Select a Member</span></div>
                            <img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">
                        <div class="pull-right"></div>
                        <div class="clear"></div>   
                    </div>
                    <div class="search-bar">
                        <input type="text" class="span" name="" lang="en" placeholder="Member Name" style="width:150px;" id="SearchUserT"> 
                        <input type="button" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaPacCOMP">
                    </div>
                    <div class="grid" style="margin-top:0px;">
                        <table class="table table-mod" id="TablaPac" style="width:100%; table-layout: fixed; ">
                        </table> 
        
                    </div>
               </div>
                
            <!-- PATIENT SELECTION TABLE --->

            </div>
         <?=$user->footer_copy;?>
    </div>
	
	<!--HIDDENS-->
	<input type="hidden" value="" id="message_id_holder" />
	<input type="hidden" value="" id="message_name_holder" />
	<input type="hidden" value="" id="last_page" />
    
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <!--<script src="js/jquery.easing.1.3.js"></script>-->
	<script src="js/Chart.min.js"></script>

	<script src="build/js/intlTelInput.js"></script>
	<script src="js/isValidNumber.js"></script>

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	
    <!-- Libraries for notifications -->
    <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<!--<script src="realtime-notifications/pusher.min.js"></script>
    <script src="realtime-notifications/PusherNotifier.js"></script>-->
    <script src="js/socket.io-1.3.5.js"></script>
    <script src="push/push_client.js"></script>
	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	   <!-- <script>
		$(function() {
	    var pusher = new Pusher('d869a07d8f17a76448ed');
	    var channel_name=$('#MEDID').val();
		var channel = pusher.subscribe(channel_name);
		var notifier=new PusherNotifier(channel);
		
	  });
    </script>-->
	
	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
	<script type= "text/javascript" src = "js/countries.js"></script>
    <!-- Libraries for notifications -->

    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
    <script src="js/bootstrap-switch.min.js"></script>
    <script src="js/google-code-prettify/prettify.js"></script>
    
    <script src="js/kinetic-v4.5.5.min.js"></script>
    <script src="js/konva.min.js"></script>
    <script src="js/moment-with-locales.js"></script>
    <script type="text/javascript" src="js/h2m_probegraph-newdes.js"></script>
    <script type="text/javascript" src="js/H2MRange.js"></script>
    <!--<script src="js/h2m_probegraph.js"></script>-->
    <script type="text/javascript" src="js/tipped.js"></script>
    <script type="text/javascript" src="js/jquery.timepicker.min.js"></script>
    <script type="text/javascript" src="js/h2m_patientnetwork.js"></script>
    
   
    <script src="js/jquery.flot.min.js"></script>
    <!--<script src="js/jquery.flot.pie.js"></script>-->
    <!--<script src="js/jquery.flot.orderBars.js"></script>-->
    <!--<script src="js/jquery.flot.resize.js"></script>-->
    <!--<script src="js/graphtable.js"></script>-->
    <script src="js/fullcalendar.min.js"></script>
    <script src="js/chosen.jquery.min.js"></script>
    <script src="js/autoresize.jquery.min.js"></script>
    <script src="js/jquery.tagsinput.min.js"></script>
    <script src="js/jquery.autotab.js"></script>
    <script src="js/elfinder/js/elfinder.min.js" charset="utf-8"></script>
	<script src="js/tiny_mce/tiny_mce.js"></script>
    <!--<script src="js/validation/languages/jquery.validationEngine-en.js" charset="utf-8"></script>-->
	<script src="js/validation/jquery.validationEngine.js" charset="utf-8"></script>
    <script src="js/jquery.jgrowl_minimized.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <!--<script src="js/jquery.mousewheel.js"></script>-->
    <script src="js/jquery.jscrollpane.min.js"></script>
    <!--<script src="js/jquery.stepy.min.js"></script>-->
    <!--<script src="js/jquery.validate.min.js"></script>-->
    <script src="js/raphael.2.1.0.min.js"></script>
    <script src="js/justgage.1.0.1.min.js"></script>
	<script src="js/glisse.js"></script>
    <script src="js/morris.js"></script>
    <script src="js/jquery.textfill.min.js"></script>

	
	<script src="js/application.js"></script>
    <script src="js/sweet-alert.min.js"></script>
    
    <!-- CHECKOUT MODULES -->
    <script src="js/CHECKOUT/connect_member_button.js"></script>
    <script src="js/CHECKOUT/probe_checkout.js"></script>

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

function queFuente3 ($numero)
{
$queF=10;
switch ($numero)
{
	case ($numero>999 && $numero<9999):	$queF = 40;
										break;
	case ($numero>99 && $numero<1000):	$queF = 60;
										break;
	case ($numero>0 && $numero<100):	$queF = 80;
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
