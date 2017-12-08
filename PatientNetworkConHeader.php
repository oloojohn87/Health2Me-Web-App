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
               <style>
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
               <link rel='stylesheet' href='css/bootstrap-dropdowns.css'>
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
               var langType = $.cookie('lang');

                if(langType == 'th')
                {
                var language = 'th';
                $("#lang1").html("Espa&ntilde;ol <span class=\"caret addit_caret\"></span>");
                }
                else{
                var language = 'en';
                $("#lang1").html("English <span class=\"caret addit_caret\"></span>");
                }
                </script>
              <!-- End of new code by Pallab-->
		   
           <div class="pull-right">
           
            
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
            <div class="dropdown-menu" id="prof_dropdown">
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
							<td lang="en"> Timezone :</td>
							<td><select id="Timezone"> </select></td>
						</tr>
						<tr>
							<td lang="en"> Time :</td>
							<td><input id="Time"> </input></td>
						</tr>
						<tr>
							<td lang="en"> Interval :</td>
							<td><select id="Interval"> </select></td>
						</tr>
												
						<tr>
							<td lang="en"> Probe Method :</td>
							<td><select id="Method"> 
									<option value="Email" lang="en">Email</option>
									<option value="Phone" lang="en">Phone</option>
									<option value="Message" lang="en">Text Message</option>
								</select>
							</td>
						</tr>
						
						<tr id="EmailRow">
							<td lang="en"> Email ID : </td>
							<td> <input id="Email" readonly> </td>
						</tr>
						<tr id="PhoneRow">
							<td lang="en"> Phone Number : </td>
							<td><input id="Phone" type="tel" placeholder="e.g. +1 702 123 4567" readonly></td>
							
						</tr>
						<tr id="MessageRow">
							<td lang="en"> Phone Number : </td>
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
						<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModallink">Close</a>
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
        #edit_probes_button, #save_probes_button{
            margin-top: 10px;
            width: 47%;
            height: 35px;
            border: 0px solid #FFF;
            outline: none;
            border-radius: 5px;
            background-color: #22AEFF;
            color: #FFF;
            float: left;
            margin-top: 20px;
        }
        #launch_probes_button{
            margin-top: 10px;
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
        #probes_alert_button{
            width: 7%;
            height: 30px;
            padding-left: 1%;
            padding-right: 1%;
            background-color: #89150B;
            color: #FFF;
            border-radius: 6px;
            outline: none;
            border: 0px solid #FFF;
            margin-left: 1%;
        }
    </style>
    <div id="probe_editor" title="Probes" style="display: none; overflow: hidden;">
        <div id="manage_user_probe" style="display: block; margin: auto; margin-top: 10px; overflow: hidden;">
            <h1 style="color: #444; font-size: 14px; text-align: center; margin-top: -10px;">Probe Information</h1>
            <div class="probe_info_section" style="height: 250px;">
                <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Probe:</div>
                <select id="probe_protocols" style="width: 56%; height: 30px; float: left;">
                </select>
                <button id="probes_alert_button"><i class="icon-exclamation"></i></button>
                <br/>
                <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Time:</div>
                <input id="probe_time" type="text"  style="width: 61%; height: 18px;"/>
                <br/>
                <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Timezone: </div>
                <select id="probe_timezone" style="width: 64%; height: 30px;">
                    <option value="3">US Pacific Time</option>
                    <option value="4">US Mountain Time</option>
                    <option value="2">US Central Time</option>
                    <option value="1">US Eastern Time</option>
                    <option value="5">Europe Central Time</option>
                    <option value="6">Greenwich Mean Time</option>
                </select>
                <br/>
                <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Method: </div>
                <select id="probe_method" style="width: 64%; height: 30px;">
                    <option value="1">Text Message</option>
                    <option value="2">Phone Call</option>
                    <option value="3">Email</option>
                </select>
                <br/>
                <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Interval: </div>
                <select id="probe_interval" style="width: 64%; height: 30px;"> 
                    <option value="1">Daily</option>
                    <option value="7">Weekly</option>
                    <option value="30">Monthly</option>
                    <option value="365">Yearly</option>
                </select>
                <br/>
                <button id="save_probes_button" style="margin-right: 4%; margin-left: 5px; background-color: #54BC00;"><i class="icon-lock"></i>&nbsp;&nbsp;Save</button>
                <button id="edit_probes_button"><i class="icon-pencil"></i>&nbsp;&nbsp;Edit Probes</button>
            </div>
            <h1 style="color: #444; font-size: 14px; text-align: center;">Probe Actions</h1>
            <div class="probe_info_section" style="height: 130px;">
                Current Probe Status: <span style="color: #D84840;" id="probe_status">Off</span>
                
                <button id="probe_toggle"><i class="icon-off"></i>&nbsp;&nbsp;Turn Probe On</button>
                <button id="launch_probes_button"><i class="icon-bolt"></i>&nbsp;&nbsp;Launch Probe Now</button>
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
            <button id="add_probe_button" style="width: 99%; height: 35px; background-color: #54bc00; border-radius: 5px; border: 0px solid #FFF; outline: none; color: #FFF; margin-left: 2px;">+</button>
            <button id="add_probe_button_back" style="width: 99%; height: 35px; background-color: #22AEFF; border-radius: 5px; border: 0px solid #FFF; outline: none; color: #FFF; margin-left: 2px; margin-top: 10px;">Back</button>
        </div>
        <div id="add_probe" style="display: none; overflow: scroll;">
            <div style="width: 20%; float: left; margin-right: 4%; height: 24px; padding-top: 6px;">Name: </div>
            <input type="text" id="probe_name" style="width: 73%; float: left;" />
            <br/>
            <div style="width: 20%; float: left; margin-right: 4%; height: 24px; padding-top: 6px;">Description: </div>
            <input type="text" id="probe_description" style="width: 73%; float: left;" />
            <br/>
            
            <div style="text-align: center; width: 100%; height: 30px; margin-top: 55px;">
                <button id="probe_question_previous"><i class="icon-chevron-left"></i></button>
                <span id="probe_question_label" style="font-size: 14px; color: #777;">&nbsp;&nbsp;Question 1&nbsp;&nbsp;</span>
                <button id="probe_question_next"><i class="icon-chevron-right"></i></button>
            </div>
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;">English:</div>
            <input id="probe_question_en" type="text" style="width: 76%; float: left;" />
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;">Spanish:</div>
            <input id="probe_question_es" type="text" style="width: 76%; float: left;" />
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;">Min Value:</div> 
            <input type="number" id="probe_question_min" style="width: 76%; float: left;" />
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;">Max Value:</div>
            <input type="number" id="probe_question_max" style="width: 76%; float: left;" />
            <br/>
            <div style="width: 19%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;">Answer Type:</div>
            <select id="probe_question_answer_type" style="width: 78%; float: left;">
                <option value="1">Single Digit (0 - 9)</option>
                <option value="2">Regular Number</option>
                <option value="3">Yes / No</option>
            </select>
            <span style="text-align: center;">Range Units:</span>
            <div id="probe_range_selector" style="width: 100%; height: 80px; float: left"></div>
            <div style="text-align: center; width: 100%; height: 100px; margin-top: 95px; text-align: center;">
                <button id="probe_add"><i class="icon-ok"></i>&nbsp;&nbsp;Done</button><br/>
                <button id="probe_cancel" style="background-color: #D84840"><i class="icon-remove"></i>&nbsp;&nbsp;Cancel</button>
            </div>
            
        </div>
        
    </div>
	
	
	
	 <!--- VENTANA MODAL  This has been added to show individual message content which user click on the inbox messages ---> 
   	 <button id="message_modal" data-target="#header-message" data-toggle="modal" class="btn btn-warning" lang="en">Modal with Header</button> 
   	  <div id="header-message" class="modal hide" aria-hidden="true">
         <div class="modal-header" lang="en">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Message Details
         </div>
         <div class="modal-body">
         <div class="formRow" style=" margin-top:-10px; margin-bottom:10px;">
             <span id="ToDoctor"></span><input type="hidden" id="IdDoctor" value='-'/>
         </div>
         <textarea  id="messagedetails" class="span message-text" name="message" rows="1"></textarea>
         
		 <form id="replymessage" class="new-message">
                   <div class="formRow">
                        <label lang="en">Subject: </label>
                        <div class="formRight">
                            <input type="text" id="subjectname_inbox" name="name"  class="span"> 
                        </div>
                   </div>
				   <div class="formRow">
						<label lang="en">Attachments: </label>
						<div id="attachreportdiv" class="formRight">
							<input type="button" class="btn btn-success" value="Attach Reports" id="attachreports">
						</div>
				   </div>
                   <div class="formRow">
                        <label lang="en">Message:</label>
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
	<div id="content">

	  <!--- VENTANA MODAL  phases wizard---> 
   	  <button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning"  lang="en">Modal with Header</button>
   	 <!--<div id="header-modal" class="modal hide" style="display: none; width: 80%; /* desired relative width */left: 10%; /* (100%-width)/2 */ /* place center */ margin-left:auto; margin-right:auto; " aria-hidden="true">-->
   	 <div id="header-modal" class="modal hide" aria-hidden="true">
         <div class="modal-header" style="height:60px;">
             <button class="close" type="button" data-dismiss="modal">×</button>
             <div style="width:90%; margin-top:12px; float:left;">
                 <div id="selpat">
                     <div style="font-size: 20px; font-weight: bold; width: 100%;">Step 1</div>
                     <span style="font-size: 12px; width: 100%;" lang="en">Select Patient</span>
                 </div>
                 <div id="seldr">
                     <div style="font-size:20px; font-weight:bold; width:100%; ">Step 2</div>
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
									<div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div>Select a Patient</div>
									<img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait2">
									<div class="pull-right"></div>
									<div class="clear"></div>   
								</div>
								<div class="search-bar">
									<input type="text" class="span" name="" placeholder="Search Patient" style="width:150px;" id="SearchUserT"> 
									<input type="button" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaPacNEW">
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
        <div id="connectMemberStep1" style="width: 100%; height: 460px; margin-top: 20px;">
            <span style="color: #54BC00; font-size: 18px;">1. Select member to connect</span>
            <style>
                .connectMemberRow{
                    width: 486px; 
                    height: 38px;
                    padding: 6px;
                    color: #333;
                    border: 1px solid #E6E6E6;

                }
                .connectMemberRow_bg1{
                    background-color: #E6E6E6;
                }
                .connectMemberRow_bg2{
                    background-color: #FBFBFB;
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
                }


            </style>
            <div class="controls" style="width: 500px; margin: auto; margin-top: 20px;margin-bottom: 8px;">
                <input class="span7" id="connectMembersSearchBar" style="float: left; width: 430px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" lang="en" placeholder="Search Member (Name or Email)" size="16" type="text">
                <button class="connectMembersSearchBarButton" style="float: left;" id="connectMembersSearchBarButton" lang="en">Search</button>
            </div>
            <div id="connectMemberTable" style="border-radius: 5px; width: 500px; height: 300px; margin: auto; overflow: hidden; overflow-y: auto; margin-top: 15px;">

            </div>
        </div>
        <div id="connectMemberStep2" style="width: 100%; height: 460px; margin-top: 20px; display: none;">
            <span style="color: #54BC00; font-size: 18px;">2. Member Details</span>
            <div style="width: 500px; margin: auto; margin-top: 20px;">
                <div style="width: 100%; height: 40px;">
                    <div style="width: 60px; float: left; color: #666; padding-top: 5px;">Email:</div> <input type="text" id="connectMemberEmail" placeholder="Member Email" style="float: left; width: 400px;" />
                </div>
                <div style="width: 100%; height: 40px;">
                    <div style="width: 60px; float: left; color: #666; padding-top: 5px;">Phone:</div> <input type="text" id="connectMemberPhone" placeholder="Member Phone Number" style="float: left; width: 400px;" />
                </div>
                <div style="width: 100%; height: 40px; margin-top: 25px;">
                    <div style="width: 300px; height: 15px; padding: 7px; background-color: #FAFAFA; border-radius: 15px; border: 1px solid #DDD; margin: auto;">
                        <button id="connectMemberSubscribeButton" style="width: 15px; height: 15px; outline: none; border: 1px solid #DDD; background-color: #FFF; border-radius: 15px; float: left; color: #54BC00; font-size: 12px;">
                        </button>
                        <div style="color: #666; padding-left: 17px; float: left; margin-top: -2px;">Subscribe this member to a probe</div>
                    </div>
                </div>
                <div id="connectMembersProbeSection" style="height: 200px; margin-top: 25px; display: none;">
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Probe:</div>
                    <select id="connectMember_probe_protocols" style="width: 64%; height: 30px;">
                    </select>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Time:</div>
                    <input id="connectMember_probe_time" type="text"  style="width: 61%; height: 18px;"/>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Timezone: </div>
                    <select id="connectMember_probe_timezone" style="width: 64%; height: 30px;">
                        <option value="3">US Pacific Time</option>
                        <option value="4">US Mountain Time</option>
                        <option value="2">US Central Time</option>
                        <option value="1">US Eastern Time</option>
                        <option value="5">Europe Central Time</option>
                        <option value="6">Greenwich Mean Time</option>
                    </select>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Method: </div>
                    <select id="connectMember_probe_method" style="width: 64%; height: 30px;">
                        <option value="1">Text Message</option>
                        <option value="2">Phone Call</option>
                        <option value="3">Email</option>
                    </select>
                    <br/>
                    <div style="width: 27%; float: left; margin-right: 40px; margin-left: 5px; height: 24px; padding-top: 6px; text-align: left;">Select Interval: </div>
                    <select id="connectMember_probe_interval" style="width: 64%; height: 30px;"> 
                        <option value="1">Daily</option>
                        <option value="7">Weekly</option>
                        <option value="30">Monthly</option>
                        <option value="365">Yearly</option>
                    </select>
                </div>
            </div>
            <div style="width: 150px; height: 30px; margin: auto;">
                <button id="connectMemberCheckoutButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px; margin: auto;">Next</button>
            </div>
        </div>
        <div id="connectMemberStep3" style="width: 100%; height: 460px; margin-top: 20px; display: none;">
            <span style="color: #54BC00; font-size: 18px;">3. Billing</span>
            <div style="width: 500px; margin: auto; margin-top: 20px;">
                <div style="width: 100%; height: 40px;">
                    <div style="width: 200px; float: left; color: #666; padding-top: 5px;">Credit Card Number:</div> <input type="text" id="connectMemberCreditCard" placeholder="Credit Card Number" style="float: left; width: 260px;" />
                </div>
                <div style="width: 100%; height: 40px;">
                    <div style="width: 200px; float: left; color: #666; padding-top: 5px;">Expiration Date:</div> <input type="month" id="connectMemberExpDate" style="float: left; width: 260px;" />
                </div>
                <div style="width: 100%; height: 40px;">
                    <div style="width: 200px; float: left; color: #666; padding-top: 5px;">CVC:</div> <input type="text" id="connectMemberCVC" placeholder="CVC" style="float: left; width: 260px;" />
                </div>
                <div style="width: 150px; height: 30px; margin: auto; margin-top: 15px;">
                    <button id="connectMemberFinishButton" style="width: 150px; height: 30px; background-color: #54BC00; outline: none; border: 0px solid #000; color: #FFF; border-radius: 5px; margin: auto;">
                        Finish
                    </button>
                </div>
                <div id="connectMemberFinishCode" style="width: 100%; height: 100px; padding-top: 80px; margin-top: 10px; display: none; text-align: center;">
                    <span style="color: #333; font-size: 18px;">Member Code: <span style="color: #54BC00;">X10-PFe8Dfi</span></span><br/>
                    <span style="color: #888;">Please provide this code to the member.</span>
                </div>
            </div>
        </div>
        <div id="connectMemberFinalStep" style="width: 100%; height: 260px; padding-top: 200px; margin-top: 20px; display: none; text-align: center;">
            <span style="color: #333; font-size: 18px;">Member Code: <span style="color: #54BC00;">X10-PFe8Dfi</span></span><br/>
            <span style="color: #888;">Please provide this code to the member.</span>
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
		 
    	 <li><a href="patients.php"  lang="en">Members</a></li>
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
                                Date: <input style="width: 150px; margin-left: 10px; margin-right: 10px; margin-top: 4px;"id="add_appointment_date" type="date" />
                                Time: <input style="width: 70px; margin-left: 10px; margin-top: 4px;" id="add_appointment_time" type="text" />
                            </div>
                        </div>
                        <div style="width: 100%; height: 40px;">
                            <button style="width: 100%; height: 30px; background-color: #54BC00; border: 0px solid #FFF; outline: none; color: #FFF; border-radius: 5px; font-size: 14px;  margin-top: 10px;" id="add_appointment_new_button">
                                Add Appoinment
                            </button>
                        </div>
                    </div>
                    <!-- END MODAL VIEW TO MAKE APPOINTMENTS-->
                    <div class="grid" style="padding:10px; height:110px;">
                            				
                        <!--Stacked Bar Chart-->

                        <div style="float:left;height:100px; width:470px;">    

                            <div class="phs_label" style="width:450px; font-size:16px; color:#b6b6b6; margin-bottom:5px;">
                                <center>Total 
                                    <span id="num_patients" style="font-size:20px; color:rgb(0, 161, 2);">
                                    <p id="TotPats">
                                        <span id="H2M_Spin_Stream">
                                            <?php echo $user->doctor_member_count ?>
                                        </span>
                                    </p>
                                    </span> Patients
                                </center>
                            </div>

                            <div class="NNot" style="float:left; height:40px; line-height: 40px; width:0px;  background-color:#54bc00; text-align: center;"> <span id="NNot_green" style="font-size:20px; color:white;">124</span></div>
                            <div class="NCon" style="float:left; height:40px; line-height: 40px; width:0px;   background-color:#449800; text-align: center;"> <span id="NCon_green" style="font-size:20px; color:white;">65</span></div>
                            <div class="NTra" style="float:left; height:40px; line-height: 40px; width:0px;  background-color:#357601; text-align: center;"> <span id="NTra_green" style="font-size:20px; color:white;">52</span></div>

                            <div style="width:100%; float:left;"></div>

                            <div class="NNot_label" style="float:left; width:250px; font-size:14px; color:#54bc00; text-align: center; line-height: 100%; margin-top:12px; min-width: 100px;">
                            <div id="container_NNot" style="display: inline-block;"><i class="fa fa-chain-broken" style="color:#22aeff;"></i> <span>Not Connected</span></div>
                            </div>

                            <div class="NCon_label" style="float:left; width:250px; font-size:14px; color:#449800; text-align: center; line-height: 100%; margin-top:12px; min-width: 100px;">
                            <div id="container_NCon" style="display: inline-block;"><i class="fa fa-link" style="color:#22aeff;"></i> <span>Connected</span></div>
                            </div>

                            <!--div class="NTra_label" style="float:left; width:250px; font-size:14px; color:#357601; text-align: center; line-height: 100%; margin-top:12px; min-width: 100px;">
                            <div id="container_NTra" style="display: inline-block;"><i class="fa fa-tag" style="color:#22aeff;"></i> <span>Tracking</span></div>
                            </div-->
                        </div>    
                        <!--Stacked Bar END-->

                        <!--New Patient Activity Count-->
                        <div style="float:left; height:100px; width:170px;">
                            <div style="float:left; width:100%; height:25px; line-height:25px;"><center style="font-size:14px; color:#b6b6b6; ">New Activity from</center> </div>
                            <div style="float:left; width:100%; height:50px; line-height:50px; text-align:center;"><span style="font-size:45px; color:#22aeff; font-weight:bold;">124</span> </div>
                            <div style="float:left; width:100%; height:25px; line-height:25px;"><center style="font-size:14px; color:#b6b6b6; ">Patients</center> </div>
                        </div>
                        <!--New PAtient Activity done-->
                        <!--buttons_doctor-->
                        <?php if($user->doctor_reports_count > 0){
                            $new_reports = "patient_row_button_active";
                            $reports_visible = '';
                        }else{
                            $new_reports = "";
                            $reports_visible = 'visibility:hidden;';
                        }
                        if($user->doctor_message_count > 0){
                            $message_visible = '';
                        }else{
                            $message_visible = 'visibility:hidden;';
                        } 
                        if($user->doctor_probe_count > 0){
                            $probe_visible = '';
                        }else{
                            $probe_visible = 'visibility:hidden;';
                        }?>
                        <div id="buttons_doctor" style="float:left; margin: 30px auto; text-align: center; width: 30%;">
                            <div style="width:20%; float: left; margin-left: 5px;">
                                <span id="TotUpDrV" style="<?php echo $reports_visible ?> background-color:red; margin-left: 40px;" class="H2MBaloon" ><?php echo $user->doctor_reports_count ?></span>
                                <span class="patient_row_button <?=$new_reports;?>"> 
                                    <i class="icon-folder-open" style="margin-left: -6px;"></i>   
                                </span>
                                
                            </div>
                            <div style="width:20%; float: left; margin-left: 5px; ">
                                <span id="TotMsgV" style="<?php echo $message_visible ?> margin-left: 40px;" class="H2MBaloon" ><?php echo $user->doctor_message_count ?></span>
                                <span class="patient_row_button <?=$new_messages;?>">
                                    <i class="icon-envelope-alt" style="margin-left: -6px;"></i>      
                                </span>
                                
                            </div>
                            <div style="width:20%; float: left; margin-left: 5px; ">
                                <span id="TotUpUsV" style="<?php echo $probe_visible ?>background-color:red; margin-left: 40px;" class="H2MBaloon" ><?php echo $user->doctor_probe_count ?></span>
                                <span class="patient_row_button <?=$new_messages;?>" title="Probe responses in the last 30 days">
                                    <i class="icon-ok" style="margin-left: -6px;"></i>
                                </span>
                                
                            </div>
                        </div>
                        <!--buttons doctor done-->   
                        <div class="clear"></div>   
                                                    
                    </div>
                    <div style="display:none;">
                        <input type="hidden"  value="<?=$user->doctor_notReach_count;?>" id="NNot">
                        <input type="hidden"  value="<?=$user->doctor_reach_count;?>" id="NCon">
                        <input type="hidden"  value="<?=$user->doctor_track_count;?>" id="NTra">
                    </div>
                </div>	
                <!-- UPPER INFORMATION AREA --->     

                
                <!-- PENDING TASKS TABLE --->     
                <input type="hidden" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaPending">
                <div id="PendingTasks" style="width:940px; margin-left:30px; margin-right:0px; margin-top:30px; display: none;">
                        <div class="grid" style="width:100%; min-height:100px; max-height:300px;margin: 0 auto; overflow-y:scroll;">
                                    <div class="grid-title">
                                        <div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div>Pending Confirmations and Invitations</div>
               
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
                
		<div style="margin:30px; " >
        <!--TAB Start-->
            <!--<li class="active" style="width:50%; " id="probeTab"><a href="#probe"  data-toggle="tab" lang="en">Probes</a></li>-->
			Connected Members
			
			<div class="search-bar" >
                <input type="text" class="span" name="" lang="en" placeholder="Search Member" style="width:150px;" id="SearchUserUSERFIXED"> 
                <input type="button" class="btn btn-primary" lang="en" value="Filter" style="margin-left:50px;" id="BotonBusquedaSents">
                <input type="button" class="btn btn-success" lang="en" value="Connect New Member" style="margin-left:50px;" id="ConnectMemberButton">

										<?php
										$pass_count = ceil(($user->doctor_member_count / 10));
										?>
										
									
										<div style="float:right;" class="the-icons">
											<i class="icon-chevron-right" style="padding:10px 10px;float:right;margin-right:0px;" id="nextPatients"></i>
											<label style="padding:10px;float:right;margin-right:0px;margin-top:-4px;" id="CurrentPage">1 - <?php echo $pass_count ?></label>
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
					<div id="TablaSents" class="tab-pane tab-overflow-main fade in active" style="width:940px; margin-left:5px; margin-right:0px; margin-top:5px;">

					<div id='vitals-window' title="Review Vitals" style='display:none;'></div>


					<?php
					require("environment_detail.php");
					$dbhost = $env_var_db['dbhost'];
					$dbname = $env_var_db['dbname'];
					$dbuser = $env_var_db['dbuser'];
					$dbpass = $env_var_db['dbpass'];

					$tbl_name="usuarios"; // Table name

					$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
																																					 PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

					if (!$con)
					{
						die('Could not connect: ' . mysql_error());
					}	

					$queUsu = '';
					$UserDOB = '';
					$IdDoc = $user->med_id;
					$start = 0;
					$numDisplay = 10;

					$result = $con->prepare("SELECT IdGroup FROM doctorsgroups WHERE IdDoctor = ?");
					$result->bindValue(1, $IdDoc, PDO::PARAM_INT);
					$result->execute();

					$row = $result->fetch(PDO::FETCH_ASSOC);
					$MyGroup = $row['IdGroup'];

					$TotMsg = 0;
					$TotUpDr = 0;
					$TotUpUs = 0;

					$result = $con->prepare("SELECT * FROM usuarios WHERE IdUsRESERV IS NOT NULL AND Surname like ? 
					AND (IdCreator = ? OR IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = ?) 
					OR Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = ?) 
					OR Identif IN (SELECT IdPac FROM doctorslinkdoctors 
					WHERE IdMED2 = ?)) ORDER BY Surname ASC LIMIT ?, $numDisplay");

					$result = $con->prepare("Select q.* from ((select USU.* from ".$dbname.".usuarios USU INNER JOIN ((select A.idDoctor from ".$dbname.".doctorsgroups A INNER JOIN (select idGroup from ".$dbname.".doctorsgroups where idDoctor=?) B where B.idGroup=A.idGroup)) DG where DG.idDoctor=USU.IdCreator)UNION(select USU.* from ".$dbname.".usuarios USU INNER JOIN (select IdPac from ".$dbname.".doctorslinkdoctors where IdMED2=?) DLD where DLD.IdPac=USU.Identif)UNION(select USU.* from ".$dbname.".usuarios USU INNER JOIN (select IdUs from ".$dbname.".doctorslinkusers where IdMED=?) DLU where DLU.IdUs=USU.Identif)UNION(Select * from ".$dbname.".usuarios where IdCreator=?))q where q.Surname like ? or q.Name like ? or q.IdUsFIXEDNAME like ? ORDER BY Surname ASC LIMIT ?, $numDisplay");
					$result->bindValue(1, $IdDoc, PDO::PARAM_INT);
					$result->bindValue(2, $IdDoc, PDO::PARAM_INT);
					$result->bindValue(3, $IdDoc, PDO::PARAM_INT);
					$result->bindValue(4, $IdDoc, PDO::PARAM_INT);
					$result->bindValue(5, '%'.$queUsu.'%', PDO::PARAM_STR);
					$result->bindValue(6, '%'.$queUsu.'%', PDO::PARAM_STR);
					$result->bindValue(7, '%'.$queUsu.'%', PDO::PARAM_STR);
					$result->bindValue(8, $start, PDO::PARAM_INT);

					$result->execute();

					while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
						$PatientId = $row['Identif'];


						$vitals_count = 0;
						
						//THIS CALCULATES THE VITALS FOR DOCTOR STATS ON MEMBER NETWORK
						//Habits...
						$result3 = $con->prepare("SELECT * FROM p_habits WHERE idpatient = ? AND (latest_update BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result3->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result3->execute();
						
						$count_habits = $result3->rowCount();
						$vitals_count = $vitals_count + $count_habits;
						
						//Vaccinations...
						$result4 = $con->prepare("SELECT * FROM p_immuno WHERE idpatient = ? AND VaccCode != '' AND (latest_update BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result4->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result4->execute();
						
						$count_vacc = $result4->rowCount();
						$vitals_count = $vitals_count + $count_vacc;
						
						//Allergies...
						$result4 = $con->prepare("SELECT * FROM p_immuno WHERE idpatient = ? AND AllerCode != '' AND (latest_update BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result4->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result4->execute();
						
						$count_aller = $result4->rowCount();
						$vitals_count = $vitals_count + $count_aller;
						
						//Family...
						$result5 = $con->prepare("SELECT * FROM p_family WHERE idpatient = ? AND (latest_update BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result5->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result5->execute();
						
						$count_family = $result5->rowCount();
						$vitals_count = $vitals_count + $count_family;
						
						//Medications...
						$result6 = $con->prepare("SELECT * FROM p_medication WHERE idpatient = ? AND (latest_update BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result6->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result6->execute();
						
						$count_meds = $result6->rowCount();
						$vitals_count = $vitals_count + $count_meds;
						
						//Diagnostics...
						$result7 = $con->prepare("SELECT * FROM p_diagnostics WHERE idpatient = ? AND (latest_update BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result7->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result7->execute();
						
						$count_diag = $result7->rowCount();
						$vitals_count = $vitals_count + $count_diag;
						//END CALCULATIONS...


						
						$result2 = $con->prepare("SELECT * FROM message_infrastructureuser WHERE sender_id = ? AND patient_id = ? AND status='new' ");
						$result2->bindValue(1, $IdDoc, PDO::PARAM_INT);
						$result2->bindValue(2, $PatientId, PDO::PARAM_INT);
						$result2->execute();

						$count2 = $result2->rowCount();
                        $new_messages = "";
						if ($count2 >0)
						{    
							$visible_baloon = 'visible';
							$unread = $count2;
							$message_color ='#54bc00';
							$addiclass='CENVELOPE';
                            $new_messages = "patient_row_button_active";
						} 
						else
						{
							$visible_baloon = 'hidden';
							$unread = 0;
							$message_color ='#e0e0e0';
							$addiclass='';
						}
						$TotMsg = $TotMsg + $unread;

						//Browsing activity from the User
						// This retrieves only the last 30 days
						$result2 = $con->prepare("SELECT * FROM bpinview USE INDEX(I1) WHERE VIEWIdUser = ? AND VIEWIdMed = ? AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) ");
						$result2->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result2->bindValue(2, $PatientId, PDO::PARAM_INT);
						$result2->execute();

						$count2 = $result2->rowCount();
						if ($count2 >0)
						{    
							$webactivWEB_color ='#54bc00';
							$webactivMOB_color ='#e0e0e0';
						} 
						else
						{
							$webactivWEB_color ='#e0e0e0';
							$webactivMOB_color ='#e0e0e0';
						}

						//Upload activity from Doctors
						$result2 = $con->prepare("SELECT * FROM bpinview USE INDEX(I1) WHERE VIEWIdUser = ? AND VIEWIdMed <> ? AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) AND (Content = 'Report Uploaded') ");
						$result2->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result2->bindValue(2, $PatientId, PDO::PARAM_INT);
						$result2->execute();

						//OR Content = 'Report Verified'
						$count2 = $result2->rowCount();
						if ($count2 >0)
						{    
							$visible_baloon3 = 'visible';
							$NewRepDoc = $count2;
							$repactivUpDr_color ='#54bc00';
						} 
						else
						{
							$visible_baloon3 = 'hidden';
							$NewRepDoc = 0;
							$repactivUpDr_color ='#e0e0e0';
						}
						$TotUpDr = $TotUpDr + $NewRepDoc;

						//Upload activity from Users
						$result2 = $con->prepare("SELECT * FROM bpinview USE INDEX(I1) WHERE VIEWIdUser = ? AND VIEWIdMed = ? AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) AND (Content = 'Report Uploaded') ");
						$result2->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result2->bindValue(2, $IdDoc, PDO::PARAM_INT);
						$result2->execute();
                        
                        $new_reports = "";
						$count2 = $result2->rowCount();
						if ($count2 >0)
						{    
							$visible_baloon2 = 'visible';
							$NewRepPat = $count2;
							$repactivUpUser_color ='#54bc00';
                            $new_reports = "patient_row_button_active";
						} 
						else
						{
							$visible_baloon2 = 'hidden';
							$NewRepPat =0;
							$repactivUpUser_color ='#e0e0e0';
						}
						$TotUpUs = $TotUpUs + $NewRepPat;

						//Upload probes from Users
						$result2 = $con->prepare("SELECT * FROM probe WHERE patientID = ? && doctorID = ?");
						$result2->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result2->bindValue(2, $IdDoc, PDO::PARAM_INT);
						$result2->execute();

						$row6 = $result2->fetch(PDO::FETCH_ASSOC);
						$num1 = $result2->rowCount();
						
						$probeID = $row6['probeID'];

						$Totprobe = 0;

						$result3 = $con->prepare("SELECT * FROM proberesponse WHERE probeID = ? AND (responseTime BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result3->bindValue(1, $row6['probeID'], PDO::PARAM_INT);
						$result3->execute();

						$count3 = $result3->rowCount();
						$Totprobe = $Totprobe + $count3;
                        $new_probes = "";

						if ($Totprobe >0)
						{    
							$probe_baloon = 'visible';
							$probe_color ='#54bc00';
                            $new_probes = "patient_row_button_active";
						} 
						else
						{
							$probe_baloon = 'hidden';
							$probe_color ='#e0e0e0';
						}
                        
                        if($num1==0)
                        {
                            $probe_icon = '<div id="'.$PatientId.'" class="CreateProbe patient_row_button '.$new_probes.'">
                                    <i id="'.$PatientId.'"  class="icon-ok"></i></div>';
                        }
                        else if($num1>=1)
                        {
                            $probe_icon = '<div id="'/*.$row6['probeID']*/.$PatientId.'" class="EditProbe patient_row_button '.$new_probes.'" >
                                    <i id="'.$row6['probeID'].'"  class="icon-undo"></i></div>';
                        }
						
						// probe's protocol
						$protocol = $con->prepare("SELECT * FROM probe_protocols WHERE protocolID = ?");
						$protocol->bindValue(1, $row6['protocolID'], PDO::PARAM_INT);
						$protocol->execute();
						$probe_protocol = $protocol->fetch(PDO::FETCH_ASSOC);
						$protocol_disabled_tags = array('', '', '', '', '');
						
						if($probe_protocol['question1'] == NULL)
							$protocol_disabled_tags[0] = 'disabled';
						if($probe_protocol['question2'] == NULL)
							$protocol_disabled_tags[1] = 'disabled';
						if($probe_protocol['question3'] == NULL)
							$protocol_disabled_tags[2] = 'disabled';
						if($probe_protocol['question4'] == NULL)
							$protocol_disabled_tags[3] = 'disabled';
						if($probe_protocol['question5'] == NULL)
							$protocol_disabled_tags[4] = 'disabled';
							
						//THIS PULLS PROBE HISTORY FOR CHART.JS GRAPH//////////////////////////////////////////////////////////////////
						$result3 = $con->prepare("SELECT * FROM proberesponse WHERE probeID = ? ORDER BY responseTime ASC LIMIT 20");
						$result3->bindValue(1, $row6['probeID'], PDO::PARAM_INT);
						$result3->execute();
						  
						$history_count = $result3->rowCount();
						
						// inside the $probe_array, create 5 arrays that represent each of the 5 possible questions that a probe can have
						$probe_array = array(/*array(), array(), array(), array(), array()*/);
						$probe_labels = array();
						while($probe_history = $result3->fetch(PDO::FETCH_ASSOC))
						{
							$date = date_create($probe_history['responseTime']);
							$format_date =  date_format($date,"d M");

							$probe_labels[] = $format_date;

							
							// get the question of the probe that this response belongs to
							$question = 1;
							if(isset($probe_history['question']))
							{
								$question = $probe_history['question'];
							}
							
							// add the response to the appropriate array
							array_push($probe_array/*[$question - 1]*/, $probe_history['response']);
						}

						$missing_data = 7 - $history_count;
						if($missing_data > 0){
							for($i=0; $i<$missing_data; $i++){
								array_unshift($probe_labels, 'No Probe');
								array_unshift($probe_array, 0);
							}
						}

						////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

						//Upload referrals from doctor
						$result2 = $con->prepare("SELECT * FROM doctorslinkdoctors WHERE (IdMED = ? OR IdMED2 = ?) AND IdPac = ? AND (Fecha BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result2->bindValue(1, $IdDoc, PDO::PARAM_INT);
						$result2->bindValue(2, $IdDoc, PDO::PARAM_INT);
						$result2->bindValue(3, $PatientId, PDO::PARAM_INT);
						$result2->execute();

						$count2 = $result2->rowCount();
						//echo '  ********************** COUNT = '.$count2;
						if ($count2 >0)
						{    
							$referral_baloon = 'visible';
							$referral_color ='#54bc00';
						} 
						else
						{
							$referral_baloon = 'hidden';
							$referral_color ='#e0e0e0';
						}
						$referral_count = $count2;

						//Video consultations with doctor
						$result2 = $con->prepare("SELECT * FROM consults WHERE Doctor = ? AND Patient = ? AND Status='Completed' AND Type='video' AND (DateTime BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result2->bindValue(1, $IdDoc, PDO::PARAM_INT);
						$result2->bindValue(2, $PatientId, PDO::PARAM_INT);
						$result2->execute();
                        
                        $new_consultations = "hidden";

						$count2 = $result2->rowCount();
						//echo '  ********************** COUNT = '.$count2;
						if ($count2 >0)
						{    
							$video_baloon = 'visible';
							$video_color ='#54bc00';
                            $new_consultations = "visible";
						} 
						else
						{
							$video_baloon = 'hidden';
							$video_color ='#e0e0e0';
						}
						$video_count = $count2;

						//Phone consultations with doctor
						$result2 = $con->prepare("SELECT * FROM consults WHERE Doctor = ? AND Patient = ? AND Status='Completed' AND Type='phone' AND (DateTime BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result2->bindValue(1, $IdDoc, PDO::PARAM_INT);
						$result2->bindValue(2, $PatientId, PDO::PARAM_INT);
						$result2->execute();

						$count2 = $result2->rowCount();
						//echo '  ********************** COUNT = '.$count2;
						if ($count2 >0)
						{    
							$phone_baloon = 'visible';
							$phone_color ='#54bc00';
                            $new_consultations = "visible";
						} 
						else
						{
							$phone_baloon = 'hidden';
							$phone_color ='#e0e0e0';
						}
						$phone_count = $count2;
                        
                        //Summary Edits
                        $result2 = $con->prepare("SELECT id FROM p_log WHERE Timestamp BETWEEN (SYSDATE() - INTERVAL 30 DAY) AND SYSDATE() AND IdUsu = ?");
                        $result2->bindValue(1, $PatientId, PDO::PARAM_INT);
                        $result2->execute();
                        $count2 = $result2->rowCount();

                        $new_summary = "";
                        if ($count2 >0)
                        {    
                            $new_summary = "patient_row_button_active";
                        } 

						//Appointments with doctor
						$result2 = $con->prepare("SELECT * FROM appointments WHERE med_id = ? AND pat_id = ? AND (date BETWEEN NOW() - INTERVAL 1 DAY AND NOW() + INTERVAL 30 DAY)");
						$result2->bindValue(1, $IdDoc, PDO::PARAM_INT);
						$result2->bindValue(2, $PatientId, PDO::PARAM_INT);
						$result2->execute();

						$count2 = $result2->rowCount();
                        $new_appointments = "";
						//echo '  ********************** COUNT = '.$count2;
						if ($count2 >0)
						{    
							$appt_baloon = 'visible';
							$appt_color ='#54bc00';
                            $new_appointments = "patient_row_button_active";
						} 
						else
						{
							$appt_baloon = 'hidden';
							$appt_color ='#e0e0e0';
						}
						$appt_count = $count2;

						$current_encoding = mb_detect_encoding($row['Name'], 'auto');
						$show_text = iconv($current_encoding, 'ISO-8859-1', $row['Name']);

						$current_encoding = mb_detect_encoding($row['Surname'], 'auto');
						$show_text2 = iconv($current_encoding, 'ISO-8859-1', $row['Surname']); 

						$full_name = $show_text.' '.$show_text2;
						//die($full_name);
                        
                        // check if this patient has been referred to another doctor
                        $result2 = $con->prepare("SELECT id FROM doctorslinkdoctors WHERE IdMED = ? AND IDPAC = ?");
                        $result2->bindValue(1, $IdDoc, PDO::PARAM_INT);
                        $result2->bindValue(2, $PatientId, PDO::PARAM_INT);
                        $result2->execute();
                        $referred = 'hidden';
                        if($result2->rowCount() > 0)
                        {
                            $referred = 'visible';
                        }

						
						echo '<div class="doctor_row_wrapper">
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
                                </style>
						
								<div class="doctor_row" id="onclick'.$PatientId.'" style="margin-bottom: 10px; position:relative;">
									<span onclick="grabTimeline('.$PatientId.');" class="doctor_row_resize icon-resize-full"></span>
									
                                    <div style="width:100%; height:22px;"></div>

                                    <div style="width: 17px; margin-left: 3px; height: 70px; float: left; margin-top: -22px; color: #54BC00;">
                                        <div style="width: 100%; height: 20px; visibility: '.$referred.'">
                                            <i class="icon-share-alt"></i>
                                        </div>
                                        <div style="width: 100%; height: 20px; margin-top: 33px; visibility: '.$new_consultations.'">
                                            <i class="icon-facetime-video"></i>
                                        </div>
                                    </div>
                                    <div id="'.$PatientId.'" class="CFILAPAT" style="height:20px; width: 20%; margin-left:10px; float:left;">
                                        <a class="patient_name">'.SCaps($show_text).' '.SCaps($show_text2).'</a>
                                        <div style="width:100%; margin-top:-8px;"></div>
                                        <span style="font-size:10px; color:#BEBEBE; font-weight:normal;">'.$row['email'].'</span>


                                    </div>';
									echo '<div id="MidIcons" style=" margin-bottom:30px; text-align:center; float:left; width:300px; font-size:14px; color:#22aeff;">';
                                
                                if($vitals_count > 0){
										$vitals_visible = '';
									}else{
										$vitals_visible = 'visibility:hidden;';
									}
								
								echo '<div onclick="openVitalsWindow('.$PatientId.',\'all\');" style="width:20%;float:left;"><span style="color:#54bc00; margin-left:5px;" title="Vitals updated in the last 30 days"><i class="icon-heart icon-2x "></i></span>
                                <span id="MemHeart" style="'.$vitals_visible.'background-color:red;" class="H2MBaloon" >'.$vitals_count.'</span></div>';
                                
                                if($count_diag > 0){
										$diag_visible = '';
									}else{
										$diag_visible = 'visibility:hidden;';
									}
								
								echo '<!--<div>Icon made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed under <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0">CC BY 3.0</a></div>-->
								<div onclick="openVitalsWindow('.$PatientId.',\'diag\');" style="width:13%;float:left;"><span style="color:#54bc00;" title="Diagnostic vitals updated in the last 30 days"><img style="margin-top:-8px;" width="15px" src="images/icons/stethoscope.png" /></span>
                                <span id="MemDiag" style="'.$diag_visible.'background-color:grey;" class="H2MBaloon" >'.$count_diag.'</span></div>';
                                
                                 if($count_vacc > 0){
										$vacc_visible = '';
									}else{
										$vacc_visible = 'visibility:hidden;';
									}
								echo '<!--<div>Icon made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed under <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0">CC BY 3.0</a></div>-->
								<div onclick="openVitalsWindow('.$PatientId.',\'vacc\');" style="width:13%;float:left;"><span style="color:#54bc00;" title="Vaccine vitals updated in the last 30 days"><img style="margin-top:-8px;" width="15px" src="images/icons/vaccines.png" /></span>
                                <span id="MemVacc" style="'.$vacc_visible.'background-color:grey;" class="H2MBaloon" >'.$count_vacc.'</span></div>';
                                
                                if($count_meds > 0){
										$meds_visible = '';
									}else{
										$meds_visible = 'visibility:hidden;';
									}
								echo '<!--<<div>Icon made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed under <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0">CC BY 3.0</a></div>-->
								<div onclick="openVitalsWindow('.$PatientId.',\'meds\');" style="width:13%;float:left;"><span style="color:#54bc00;" title="Medication vitals updated in the last 30 days"><img style="margin-top:-8px;" width="15px" src="images/icons/drugs.png" /></span>
                                <span id="MemMeds" style="'.$meds_visible.'background-color:grey;" class="H2MBaloon" >'.$count_meds.'</span></div>';
                                
                                if($count_aller > 0){
										$aller_visible = '';
									}else{
										$aller_visible = 'visibility:hidden;';
									}
								echo '<!--<div>Icon made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed under <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0">CC BY 3.0</a></div>-->
								<div onclick="openVitalsWindow('.$PatientId.',\'aller\');" style="width:13%;float:left;"><span style="color:#54bc00;" title="Allergy vitals updated in the last 30 days"><img style="margin-top:-8px;" width="15px" src="images/icons/allergies.png" /></span>
                                <span id="MemAller" style="'.$aller_visible.'background-color:grey;" class="H2MBaloon" >'.$count_aller.'</span></div>';
                                
                                if($count_family > 0){
										$fam_visible = '';
									}else{
										$fam_visible = 'visibility:hidden;';
									}
								echo '<!--<div>Icon made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed under <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0">CC BY 3.0</a></div>-->
								<div onclick="openVitalsWindow('.$PatientId.',\'family\');" style="width:13%;float:left;"><span style="color:#54bc00; margin-left:5px;" title="Family history vitals updated in the last 30 days"><img style="margin-top:-8px;" width="15px" src="images/icons/genetics.png" /></span>
                                <span id="MemFam" style="'.$fam_visible.'background-color:grey;" class="H2MBaloon" >'.$count_family.'</span></div>';
                                
                                if($count_habits > 0){
										$habits_visible = '';
									}else{
										$habits_visible = 'visibility:hidden;';
									}
								echo '<!--<div>Icon made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed under <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0">CC BY 3.0</a></div>-->
								<div onclick="openVitalsWindow('.$PatientId.',\'habits\');" style="width:13%;float:left;"><span style="color:#54bc00;" title="Habit vitals updated in the last 30 days"><img style="margin-top:-8px;" width="15px" src="images/icons/habits.png" /></span>
                                <span id="MemHabits" style="'.$habits_visible.'background-color:grey;" class="H2MBaloon" >'.$count_habits.'</span></div>

                                
            
                            </div>


                                    <!--
									<div id="icons_patient" style="float:left;">
										<span style="color:'.$repactivUpUser_color.';" title="New reports present in last 30 days."><a class="neutral" style="color:'.$repactivUpUser_color.'"><i id="'.$PatientId.'"  class="icon-folder-open-alt icon-2x "></i></a></span>
										<span style="visibility:'.$visible_baloon2.'" class="H2MBaloon" >'.$TotUpUs.'</span>
										<span style="color:'.$message_color.';" title="New messages present in last 30 days."><a class="neutral" style="color:'.$message_color.'"><i id="'.$PatientId.'"  class="icon-envelope-alt icon-2x "></i></a></span>
										<span style="visibility:'.$visible_baloon.'" class="H2MBaloon" >'.$unread.'</span>
										<span style="color:'.$probe_color.';" title="New probe response in last 30 days."><a class="neutral" style="color:'.$probe_color.'"><i id="'.$PatientId.'"  class="icon-ok icon-2x "></i></a></span>
										<span style="visibility:'.$probe_baloon.'" class="H2MBaloon" >'.$Totprobe.'</span>
										<span style="color:#e0e0e0;" title="New vitals present in last 30 days."><a class="neutral" style="color:#e0e0e0"><i id="'.$PatientId.'"  class="icon-heart icon-2x "></i></a></span>
										<span style="visibility:hidden" class="H2MBaloon" >'.$unread.'</span>
										<span style="color:'.$referral_color.';" title="New referral present in last 30 days."><a class="neutral" style="color:'.$referral_color.'"><i id="'.$PatientId.'"  class="icon-reply-all icon-2x "></i></a></span>
										<span style="visibility:'.$referral_baloon.'" class="H2MBaloon" >'.$referral_count.'</span>
										<span style="color:'.$video_color.';" title="New video consultation in last 30 days."><a class="neutral" style="color:'.$video_color.'"><i id="'.$PatientId.'"  class="icon-facetime-video icon-2x "></i></a></span>
										<span style="visibility:'.$video_baloon.'" class="H2MBaloon" >'.$video_count.'</span>
										<span style="color:'.$phone_color.';" title="New phone consultation in last 30 days."><a class="neutral" style="color:'.$phone_color.'"><i id="'.$PatientId.'"  class="icon-phone icon-2x "></i></a></span>
										<span style="visibility:'.$phone_baloon.'" class="H2MBaloon" >'.$phone_count.'</span>
										<span style="color:'.$appt_color.';" title="Appointments in the next 30 days."><a class="neutral" style="color:'.$appt_color.'"><i id="'.$PatientId.'"  class="icon-calendar icon-2x "></i></a></span>
										<span style="visibility:'.$appt_baloon.'" class="H2MBaloon" >'.$appt_count.'</span>
									</div>
                                    -->


                                    <div id="buttons_doctor" style="float:right; width:42%;">


                                        <div onclick="window.location.href = \'patientdetailMED-new.php?IdUsu='.$PatientId.'\'" class="patient_row_button '.$new_reports.'">
                                            <i id="'.$PatientId.'"  class="icon-folder-open"></i>
                                        </div>
                                        <div onclick="openSummary('.$PatientId.');" class="patient_row_button '.$new_summary.'">
                                            <i id="'.$PatientId.'"  class="icon-edit"></i>
                                        </div>
                                        <div onclick="createMessage('.$PatientId.', \''.$full_name.'\');"  class="patient_row_button '.$new_messages.'">
                                            <i id="'.$PatientId.'"  class="icon-envelope-alt"></i>
                                        </div>
                                        '.$probe_icon.'
                                        <div id="patientAppointments_'.$PatientId.'"  class="patient_row_button '.$new_appointments.'">
                                            <i id="'.$PatientId.'"  class="icon-calendar "></i>
                                        </div>
                                        <div onclick="createDetails();"  class="patient_row_button">
                                            <i id="'.$PatientId.'"  class="icon-info-sign"></i>
                                        </div>
                                    </div>
						  
									<div style="width:100%; height:1px; float:left;"></div>
						   
									<div id="graph_probes_container" style="margin-top:17px; float:left; padding:10px; width: 350px;">';
										if($row6['probeID'] != ''){
											echo '<div id="probegraph_question_'.$row6['probeID'].'" style="width: 350px; height: 35px; font-size: 12px; color: #777; overflow: hidden; text-overflow: ellipsis; line-height: 115%;">
											</div>
											<div id="probegraph_container_'.$row6['probeID'].'">
												<canvas height="120" width="350" id="probegraph_'.$row6['probeID'].'"></canvas>
											</div>
											<div style="width: 150px; height: 20px; margin: auto;">
												<button id="probebutton_'.$row6['probeID'].'_1" class="probe_chart_button" '.$protocol_disabled_tags[0].'>
													<div style="margin-top: -3px;">1</div>
												</button>
												<button id="probebutton_'.$row6['probeID'].'_2" class="probe_chart_button" '.$protocol_disabled_tags[1].'>
													<div style="margin-top: -3px;">2</div>
												</button>
												<button id="probebutton_'.$row6['probeID'].'_3" class="probe_chart_button" '.$protocol_disabled_tags[2].'>
													<div style="margin-top: -3px;">3</div>
												</button>
												<button id="probebutton_'.$row6['probeID'].'_4" class="probe_chart_button" '.$protocol_disabled_tags[3].'>
													<div style="margin-top: -3px;">4</div>
												</button>
												<button id="probebutton_'.$row6['probeID'].'_5" class="probe_chart_button" '.$protocol_disabled_tags[4].'>
													<div style="margin-top: -3px;">5</div>
												</button>
											</div>';
										}else{
											echo "This member has not responded with the status of their health.";
										}
									echo '</div>
							   
									<div id="timeline_label" style="font-size:12px; color:#54bc00; font-weight:normal; float:left; margin-top:20px; margin-left:30px;"><center>'.$full_name.'\'s Timeline</center></div>
							   
									<div id="timeline'.$PatientId.'" style="width: 55%; height:150px; float:left; position:relative; margin-left:20px;"></div>
								
								</div> 
							</div>'; 

						echo "<script type='text/javascript'>";
						
						echo "var data = {
						labels: [";
						foreach($probe_labels as $label){
							echo "'".$label."', ";
						}
						echo"],
						datasets: [
							{
								label: 'Probe Responses',
								fillColor: 'rgba(220,220,220,0.2)',
								strokeColor: 'rgba(220,220,220,1)',
								pointColor: 'rgba(220,220,220,1)',
								pointStrokeColor: '#22aeff',
								pointHighlightFill: '#54bc00',
								pointHighlightStroke: 'rgba(220,220,220,1)',
								data: [";
						foreach($probe_array as $probes){
							echo "'".$probes."', ";
						}
						
						$initial_chart = "]
							}
						]
					};
					var options = {
							bezierCurve: true,
							scaleFontSize: 10,
							scaleFontColor: '#cacaca',
							/*scaleOverride: true,
							scaleSteps: 4,
							scaleStepWidth: 1,
							scaleStartValue: 1,*/
					datasetFill: true
						};
					</script>";
						
						echo $initial_chart;
					/**/
						
					}

					$probe_button_event = "
						<script>
							$(document).ready(function()
							{
								$('.probe_chart_button').on('click', function()
								{
									var info = $(this).attr('id').split('_');
									var probe = info[1];
									var question = info[2];
									$(this).siblings().each(function()
									{
										if($(this).hasClass('probe_chart_button_selected'))
											$(this).removeAttr('disabled');
										$(this).removeClass('probe_chart_button_selected').addClass('probe_chart_button');
									});
									$(this).removeClass('probe_chart_button').addClass('probe_chart_button_selected');
									$(this).attr('disabled', 'disabled');
									//$('#probegraph_container_'+probe).html('<img src=\"images/load/29.gif\"  alt=\"\">');
									$.post('get_probe_graph_data.php', {probeID: probe, question: question}, function(data, status)
									{
										var d = JSON.parse(data);
										
										var dataset = d.chart;
										
										if(d.count > 0)
										{
											$('#probegraph_container_'+probe).html('<canvas height=\"120\" width=\"350\" id=\"probegraph_'+probe+'\"></canvas>');

											var c = $('#probegraph_'+probe);
											var ct = c.get(0).getContext('2d');
											var ctx = document.getElementById('probegraph_'+probe).getContext('2d');

											myNewChart = new Chart(ct).Line(dataset, options);
										}
										else
										{
											$('#probegraph_container_'+probe).html('<div style=\"width: 350px; height: 66px; padding-top: 50px; margin-bottom: 10px; text-align: center; background-color: #FAFAFA; border-radius: 5px; border: 1px dashed #EEE; color: #777;\"><p>No data available for this question</p></div>');
										}
										$('#probegraph_question_'+probe).text(d.question);
										
									});
								});
								
								$('div[id^=\"probegraph_container_\"]').each(function()
								{
									var probe = $(this).attr('id').split('_')[2];
									$('#probebutton_'+probe+'_1').removeClass('probe_chart_button').addClass('probe_chart_button_selected');
									$('#probebutton_'+probe+'_1').attr('disabled', 'disabled');
									$.post('get_probe_graph_data.php', {probeID: probe, question: 1}, function(data, status)
									{
										var d = JSON.parse(data);
										
										var dataset = d.chart;

										if(d.count > 0)
										{
											$(this).html('<canvas height=\"120\" width=\"350\" id=\"probegraph_'+probe+'\"></canvas>');

											var c = $('#probegraph_'+probe);
											var ct = c.get(0).getContext('2d');
											var ctx = document.getElementById('probegraph_'+probe).getContext('2d');

											myNewChart = new Chart(ct).Line(dataset, options);
										}
										else
										{
											$(this).html('<div style=\"width: 350px; height: 66px; padding-top: 50px; margin-bottom: 10px; text-align: center; background-color: #FAFAFA; border-radius: 5px; border: 1px dashed #EEE; color: #777;\"><p>No data available for this question</p></div>');
										}
										$('#probegraph_question_'+probe).text(d.question);
									});
									
								});
							});
						</script>";

					echo $probe_button_event;

					function SCaps ($cadena)
					{
						return strtoupper(substr($cadena,0,1)).substr($cadena,1);
					}    
					
					?>
					
					</div>
                    <!--<span style="font-size:40px;" id="H2M_Spin_Stream">
					       <center><i id="connect_spinner" class="icon-spinner icon-2x icon-spin" ></i></center>
					</span>-->
					
	
					
						<!--<div class="tab-pane " id="cpatients">-->
						<div class="tab-pane" style="width:940px; margin-left:30px; margin-right:0px; margin-top:30px;"  id="cpatients" >
								
								
								
								
								<div class="grid" style="width:95%; margin: -8px;" id="connectedPatientsGrid">
                                    <div class="grid-title">
                                        <div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div>Connected Members</div>
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
        </div>    
		<!---COMPOSE MODAL--->
		<div id="compose_modal"  title="Messages" style="display: none; width: 600px; height: 500px;">
            <div id="compose_messages"  style="width: 570px; height: 380px; overflow: auto; margin-top: 10px;">
            </div>
            <div id="compose_new" style="width: 500px; height: 380px; margin-left: 40px; display: none; margin-top: 10px;">
                <strong>To: </strong><span id="compose_message_recipient_label"></span><br/><br/>
                <strong>Subject: </strong><br/><input type="text" id="compose_message_subject" style="width: 500px; margin-top: 5px;" /><br/>
                <strong>Message:</strong><br/>
                <textarea id="compose_message_content" style="width: 500px; height: 200px; margin-top: 15px;"></textarea>
            </div>
            <div id="compose_attachments" style="margin-left: 2%; width: 96%; height: 380px; display: none;">
                <div style="width: 100%; height: 30px;">
                    <div style="width: 14%; height: 30px; margin-top: 4px; float: left; color: #666">Select Member:</div>
                    <select id="compose_select_member" style="width: 58%; float: left;">

                    </select>
                    <button style="width: 12%; float: left; margin-left: 2%; height: 30px; border: 0px solid #FFF; border-radius: 5px; outline: none; background-color: #22AEFF; color: #FFF;">Select All</button>
                    <button style="width: 12%; float: left; margin-left: 2%; height: 30px; border: 0px solid #FFF; border-radius: 5px; outline: none; background-color: #D84840; color: #FFF;">Deselect All</button>
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
                 <div class="grid" class="grid span4" style="height:420px; margin: 30px auto; margin-top:30px; margin-left:30px; margin-right:30px; padding:10px; ">
                    <span class="label label-info" id="EtiTML" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">User&Doctor Communications Area</span>
                    <ul id="myTab" class="nav nav-tabs tabs-main">
                    <li class="active"><a href="#inbox" data-toggle="tab" id="newinbox" lang="en">InBox</a></li>
                    <li><a href="#outbox" data-toggle="tab" id="newoutbox" lang="en">OutBox</a></li></ul>
                    <div id="myTabContent2" class="tab-content tabs-main-content padding-null" >
                                   
                    <div class="tab-pane tab-overflow-main fade in active" id="inbox" >
                    <div class="message-list" ><div class="clearfix" style="margin-bottom:40px;">
                    <div class="action-message" ><div class="btn-group">
                   
                    <button id="delete_message" class="btn b2"><i class="icon-trash padding-null"></i></button>
                    <!--<input type="button" style="margin-left:10px" class="btn b2" value="Create Message" id="compose_message">-->
                  
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
			<div id="summary_modal" lang="en" title="Summary" style="display:none; text-align:center; width: 1000px; height: 660px; overflow: hidden;">
    </div>
            <!-- COMMUNICATIONS & MESSAGING --->     
            <div style="width:100%; height:2px;"></div>     
                
            <!-- PATIENT SELECTION TABLE --->
            
            <div id="OLDpatientstable" class="grid" style="float:left; width:90%; height:300px; margin: 0 auto; margin-left:2%; margin-top:30px; margin-bottom:30px; overflow:scroll;">
                    <div class="grid-title">
                        <div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div>Select a Member</div>
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
	<input type="hidden" value="<?php echo $pass_count ?>" id="last_page" />
    
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
	<script src="realtime-notifications/pusher.min.js"></script>
	<script src="realtime-notifications/PusherNotifier.js"></script>
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
    <script src="js/google-code-prettify/prettify.js"></script>
    
    <script src="js/kinetic-v4.5.5.min.js"></script>
    <script src="js/moment-with-locales.js"></script>
    <script type="text/javascript" src="js/H2MRange.js"></script>
    <script src="js/h2m_probegraph.js"></script>
    <script type="text/javascript" src="js/tipped.js"></script>
    <script type="text/javascript" src="js/jquery.timepicker.min.js"></script>
    <script type="text/javascript" src="js/h2m_patientnetwork.js"></script>
    <script src="js/jquery.textfill.min.js"></script>
    
   
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

	
	<script src="js/application.js"></script>
    <script src="js/sweet-alert.min.js"></script>

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