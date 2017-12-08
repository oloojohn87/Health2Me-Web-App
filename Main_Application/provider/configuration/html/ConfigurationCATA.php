<?php
include_once("../../../master_classes/userConstructClass.php");
$user = new userConstructClass();
$user->pageLinks('medicalConfiguration.php');
$user->telemedSetter();

include_once("../configurationClass.php");
$configuration = new configurationClass();
?>
<!-- Create language switcher instance and set default language to en-->
<script src="../../../../jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="../../../../jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
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

    function CargaDatos(queEntrada)
    {
        if (queEntrada ==1)
        {
            //retf = $('#IdDoB').val();
            //alert (retf);
            if($('#IdDoB').val() != 'NULL')
             $('#dp32').val($('#IdDoB').val());

            $('#gender').val($('#IdGender').val());
            $('#orderOB').val($('#IdOrden').val());

            $('#VIdUsFIXED').html($('#IdUsFIXED').val());

            $('#Vname').val($('#IdUserName').val());
            $('#Vsurname').val($('#IdUserSurname').val());
            $('#Vpassword').val($('#IdUserPassword').val());
            $('#password2').val($('#IdUserPassword').val());
            $('#VIdUsFIXEDNAME').html($('#IdUsFIXEDNAME').val());

            $('#Vemail').val($('#IdEmail').val());
            $('#Vphone').val('+'+$('#IdTelefono').val());

            $('#VIdUsFIXEDINSERT').html($('#IdUsFIXED').val());
            $('#VIdUsFIXEDNAMEINSERT').html($('#IdUsFIXEDNAME').val());

        }
    }  

</script>    
  </head>

<style>
                    .timeCellIndicator{
                        border-style: solid; 
                        border-width: 1px;
                        border-color: #BABABA;
                        height: 15px; 
                        width: 32px;
						cursor:pointer;
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
                </style>

 
  <body onload="CargaDatos(<?php echo $QueEntrada; ?>)" >
<div class="loader_spinner"></div>
<div id="Loading"></div>

      
    <!-------------------------------------------------------------------------------------------HIDDEN INPUTS-------------------------------------------------------------------------------------->
     		
    	<?php 
            if($user->doctor_dob != null && strlen(strval($user->doctor_dob)) == 8)
                $date = DateTime::createFromFormat('Ymd', $user->doctor_dob);
            else
                $date = null;
        ?>
    	<input type="hidden" id="IdDoB" Value="<?php if($date != null) {echo $date->format('Y-m-d');} else{echo 'NULL';} ?>">	
		<input style="display:none;" type="file" name="fileToUpload" id="fileToUpload2" onchange="fileSelected();"/>
		<input id="make_upload" style="width:0px;display:none;" type="button" onclick="uploadFile2()" value="Upload" />
		
    	<input type="hidden" id="IdGender" Value="<?php echo $user->doctor_gender; ?>">	
    	<input type="hidden" id="IdOrden" Value="<?php echo $QueOrden; ?>">	
    	<input type="hidden" id="IdUsFIXED" Value="<?php echo $user->doctor_fixed_id; ?>">	
    	
    	<input type="hidden" id="IdUserName" Value="<?php echo $user->doctor_first_name; ?>">	
    	<input type="hidden" id="IdUserSurname" Value="<?php echo $user->doctor_last_name; ?>">	
    	<input type="hidden" id="IdUserPassword" Value="<?php echo $UserPassword; ?>">	
    	<input type="hidden" id="IdUsFIXEDNAME" Value="<?php echo $user->doctor_fixed_name; ?>">	
  
    	<input type="hidden" id="IdEmail" Value="<?php echo $user->doctor_email; ?>">	
    	<input type="hidden" id="IdTelefono" Value="<?php echo $user->doctor_phone; ?>">	

    		
        <input type="hidden" id="telemed_on" Value="<?php echo $user->doctor_telemed; ?>">
		<input type="hidden" id="MEDID" value="<?php echo $user->med_id ?>">
		<input type="hidden" id="DOC_SPECIALITY" value="<?php echo $user->doctor_speciality ?>">
		<input type="hidden" id="DOC_TIMEZONE" value="<?php echo $user->doctor_timezone ?>">
		<input type="hidden" id="DOC_STATE" value="<?php echo $user->doctor_state ?>">
		<input type="hidden" id="DOC_COUNTRY" value="<?php echo $user->doctor_country ?>">
		<input type="hidden" id="CUSTOM_LOOK" value="<?php echo $CustomLook ?>">
		<input type="hidden" id="MESSAGE" value="<?php if(isset($message)) echo $message; ?>">
		<input type="hidden" id="TIMESTAMP" value="<?php echo $timestamp ?>">
		<input type="hidden" id="UNIQUE_SALT" value="<?php echo md5('unique_salt' . $timestamp); ?>">
        <input type="hidden" id="notify_id">
	<!------------------------------------------------------------------------------------------------END OF HIDDEN INPUTS--------------------------------------------------------------------------------->
      
	<!--Header Start-->
	<?php include '../../common/header.php';?>
    <!--Header END-->

    
    
      
	<div id="notesModal" style="width: 600px; height: 800px; padding: 0px; display: none;"></div>
    <div id="summaryModal" style="width: 680px; height: 800px; padding: 0px; display: none;"></div>
    <div id="recordingModal" style="width: 400px; height: 400px; padding: 0px; display: none;"></div>
    
    <!--Content Start-->
	<div id="content" style="padding-left:0px;">
    
   	  <!--- VENTANA MODAL  ---> 
   	  <button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button>
   	  <div id="header-modal" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header" lang="en">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Establish Connection with User
         </div>
         <div class="modal-body" lang="en">
             <p>Please confirm that you want to link with this user.</p>
         </div>
         <input type="hidden" id="queId">
         <div class="modal-footer">
	         <input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal" lang="en">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 
        
        
     <!-- MODAL FOR PROBE QUESTION EDITING -->
     <div id="probe_question_modal" style="width: 700px; height; 444px; display: none;">
         <div style="width: 96%; height: 30px; margin-top: 5px;">
            <div style="width: 8%; float: left; color: #555; margin-top: 4px;">Title: </div>
             <div style="width: 92%; float: left;">
                 <input type="text" style="width: 100%;" id="probe_question_edit_title" />
             </div>
         </div>
         <div style="height: 125px; width: 96%;  padding-left: 2%; border: 1px solid #AAA; background-color: #F8F8F8; border-radius: 5px; text-align: left; margin-top: 10px; color: #555">
            <div style="width: 100%; text-align: center;">Questions</div><br/>
             <div style="float: left; width: 16%; margin-top: 7px;">English:</div><input type="text" style="width: 77%; float: left;"  id="probe_question_edit_question_en" /><br/>
             <div style="float: left; width: 16%; margin-top: 7px;">Spanish:</div><input type="text" style="width: 77%; float: left;"  id="probe_question_edit_question_es" />
         </div>
         <div style="height: 142px; width: 96%;  padding-left: 2%; border: 1px solid #AAA; background-color: #F8F8F8; border-radius: 5px; text-align: left; margin-top: 10px; color: #555">
            <div style="width: 100%; text-align: center;">Answers</div><br/>
             <div style="width: 100%; height: 25px; margin-bottom: 25px;">
                <div style="width: 15%; float: left; margin-right: 2%; height: 24px; padding-top: 6px;">Min Value:</div> 
                <input type="number" style="width: 12%; float: left;"  id="probe_question_edit_min" />
                <div style="width: 15%; float: left; margin-right: 2%; height: 24px; padding-top: 6px; margin-left: 10px;">Max Value:</div>
                <input type="number" style="width: 12%; float: left;"  id="probe_question_edit_max" />
                <div style="width: 7%; float: left; margin-right: 2%; height: 24px; padding-top: 6px; margin-left: 19px;">Unit:</div>
                <input type="text" style="width: 16%; float: left;"  id="probe_question_edit_unit" />
            </div>
             Ranges:
             <div style="width: 600px; height: 13px; margin: auto; margin-top: 5px;" id="probe_editing_range_selector"></div>
         </div>
         <div style="width: 30%; height: 30px; margin: auto; margin-top: 41px;">
            <button id="probe_question_edit_clear" style="width: 45%; float: right; height: 30px; background-color: #D84840; border: 0px solid #FFF; outline: none; color: #FFF; border-radius: 5px;">Clear</button>
            <button id="probe_question_edit_save" style="width: 45%; float: left; height: 30px; background-color: #54BC00; border: 0px solid #FFF; outline: none; color: #FFF; border-radius: 5px;">Save</button>
         </div>
         
     </div>
     <!-- END MODAL FOR PROBE QUESTION EDITING -->
        
     <!-- MODAL FOR PROBE PROTOCOL EDITING -->
     <div id="probe_protocol_modal" style="width: 400px; height; 220px; display: none;">
         <div style="width: 96%; height: 30px; margin-top: 5px;">
            <div style="width: 15%; float: left; color: #555; margin-top: 4px;">Title: </div>
             <div style="width: 85%; float: left;">
                 <input type="text" style="width: 100%;" id="probe_protocol_edit_title" />
             </div>
         </div>
         <div style="width: 96%; height: 80px; margin-top: 5px;">
            <div style="width: 28%; color: #555; margin-top: 4px;">Description: </div>
             <div style="width: 100%;">
                 <textarea style="width: 100%;" id="probe_protocol_edit_description" ></textarea>
             </div>
         </div>

         <div style="width: 30%; height: 30px; margin: auto; margin-top: 10px;">
            <button id="probe_protocol_edit_save" style="width: 100%; float: left; height: 30px; background-color: #54BC00; border: 0px solid #FFF; outline: none; color: #FFF; border-radius: 5px;">Save</button>
         </div>
         
     </div>
     <!-- END MODAL FOR PROBE PROTOCOL EDITING -->
        
        
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
		
    	 <li><a href="../../maindashboard/html/MainDashboardCATA.php" lang="en">Home</a></li>
         <?php
                                    
                $arr=$user->checkAccessPage("dashboard.php");
                $arr_d=json_decode($arr, true);

                if((count($arr_d['items'])>0)&&($arr_d['items'][0]['accessid']==1)){ 
                
                    echo '<li><a lang="en" href="../../maindashboard/html/MainDashboardCATA.php"  lang="en">Dashboard</a></li>';
                }
                ?>
         <?php if($user->doctor_privilege==3) { ?>
    	 <li><a href="patients.php" lang="en">Members</a></li>
         <?php } ?>
		 <?php if ($user->doctor_privilege==1)
		 {
				 echo '<li><a href="../../medicalconnections/html/MedicalConnectionsCATA.php" lang="en">Doctor Connections</a></li>';
				 echo '<li><a href="../../membernetwork/html/MemberNetworkCATA.php" lang="en">Member Network</a></li>';
		 }
		 ?>
         <li><a href="../../configuration/html/ConfigurationCATA.php" class="act_link"  lang="en">Configuration</a></li>
         <li><a href="../../../ajax/logout.php" style="color:#d9d907;" lang="en">Sign Out</a></li>
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
		  
     <div id="groupconfig" title="Group Details" style="display:none; text-align:center; padding:20px;">
		 
		 <!--<span id="grp_details"  class="label label-info" style="left:0px; margin-left:34px; margin-top:20px; font-size:15px; " lang="en">Group Details</span>-->
		 
		 
<!--------			MODAL WINDOW EDIT GROUPS 			--------->		 
		 <div style="margin-left:10px;margin-top:20px;padding-bottom:20px;">
			 <div id="create-group" title="Create Group">
				 
				<form enctype="multipart/form-data" method="post" style="float:left;margin-left:20px">
					<div class="row" style="margin-left:5px;margin-top:30px;">
					  <!--<label for="fileToUpload">Select Files to Upload</label>-->
					  <div id="grpimage" title="Choose a group picture"><img id="group-img" style="width:150px;height:150px;margin-bottom:10px" src="../../../../images/defaultgrouppic.jpg" alt="Group Picture"/><br />
					  
					  </div>
					  <input type="file" name="filesToUpload[]" id="filesToUpload" multiple="multiple" style="margin-left:30px; width:87px" placeholder="Group Picture"/>
					  <!--<output id="filesInfo"></output>-->
					
					<!--<div class="row">
					  <input type="submit" value="Upload" />
					</div>-->
					</div> 
				</form>
				
			
			<p style="margin-top:30px">
			
 			<p> <span style="width:20%;float:left;margin-left:120px" lang="en"> Group Name: </span> <input id="editgrpname" type="text" placeholder="Group Name" style="width:25%;margin-left:0px"> </p>
			<p> <span style="width:20%;float:left;margin-left:120px" lang="en"> Address: </span> <input id="editgrpadd" type="text" placeholder="Address" style="width:25%;margin-left:0px">  </p>
			<p> <span style="width:20%;float:left;margin-left:120px" lang="en"> ZIP: </span> <input id="editgrpzip" type="text" placeholder="ZIP" style="width:25%;margin-left:0px"> </p>
			<p> <span style="width:20%;float:left;margin-left:120px" lang="en"> City: </span> <input id="editgrpcity" type="text" placeholder="City" style="width:25%;margin-left:0px"> </p>
			<p> <span style="width:20%;float:left;margin-left:295px" lang="en"> State: </span> <input id="editgrpstate" type="text" placeholder="State" style="width:25%;margin-left:0px"> </p>
			<p> <span style="width:20%;float:left;margin-left:295px" lang="en"> Country: </span> <input id="editgrpcountry" type="text" placeholder="Country" style="width:25%;margin-left:0px"> </p>
			
			</p>	 
								
			<div style="width:100%; margin: 0 auto; margin-top:20px;margin-left:-10px;float:right;">
				<input id="Edit_group"  style="width:25%;height:35px; border: 0px solid #E6E6E6;
outline: 0px;border-radius:7px; color:white;background-color: rgb(34,174,255); text-align:center; margin-left:210px;margin-top:20px" value="Save Edits">
				<input id="save_grpdate"  style="width:25%;height:35px; border: 0px solid #E6E6E6;
outline: 0px;border-radius:7px; color:white;background-color: rgb(34,174,255);  text-align:center; margin-left:210px;margin-top:20px; display:none" value="Save Group">
			</div>
			</div>	 
		 </div>
		<div id="group_members" style="margin-left:15px"></div>
		
		<input type="button" id="remove_doc"  style="width:25%;height:35px; border: 0px solid #E6E6E6;
outline: 0px;border-radius:7px; color:white;background-color: rgb(34,174,255);  text-align:center; margin-left:-400px;margin-top:30px;display:none" value="Remove">
		
		<div style="margin-top:20px;margin-left:35px">
		<!--<span style="color:#22aeff; font-size:14px;float:left;" lang="en"> Add Users: </span>-->
		<input id="field_id" type="text" style="width: 40px; margin-top:30px; visibility:hidden;">
		<input id="DoctorSBox" type="text" style="width: 200px;margin-top:30px;float:left;"><span id="results_count"></span>		
		<input type="button" id="add_doctors"  style="width:200px; height:35px; border: 0px solid #E6E6E6;
outline: 0px;border-radius:7px; color:white;background-color: rgb(34,174,255); text-align:center; margin-left: 320px;margin-top: -70px;" value="Add Doctors">
		</div>
		
		<!--<div style="margin-top:20px">
		<span style="color:#22aeff; font-size:14px;float:left;"> Add Administrator: </span>
		<input id="field_id1" type="text" style="width: 40px; margin-top:30px; visibility:hidden;">
		<input id="DoctorSBox1" type="text" style="width: 200px;margin-top:30px;margin-left:-40px;float:left;"><span id="results_count1"></span>		
		<input type="button" id="add_admin"class="btn btn-primary" style=" margin-left: 320px;margin-top: -70px;" value="Add Admin">
		</div>
		
		<div style="margin-top:20px">
		<span style="color:#22aeff; font-size:14px;float:left;"> Add Referral Receiver Incharge: </span>
		<input id="field_id2" type="text" style="width: 40px; margin-top:30px; visibility:hidden;">
		<input id="DoctorSBox2" type="text" style="width: 200px;margin-top:30px;margin-left:-140px;float:left;"><span id="results_count2"></span>		
		<input type="button" id="add_refdoc"class="btn btn-primary" style=" margin-left: 320px;margin-top: -70px;" value="Add Referral Incharge">
		</div>
		
		<div id="ownertab" style="margin-top:20px;">
		<span style="color:#22aeff; font-size:14px;float:left;"> Change Owner: </span>
		<input id="field_id3" type="text" style="width: 40px; margin-top:30px; visibility:hidden;">
		<input id="DoctorSBox3" type="text" style="width: 200px;margin-top:30px;margin-left:-20px;float:left;"><span id="results_count3"></span>		
		<input type="button" id="change_owner"class="btn btn-primary" style=" margin-left: 320px;margin-top: -70px;" value="Change Owner">
		</div>-->
	
	</div>
  
          
     <!--CONTENT MAIN START-->
	<div id="content" style="padding-left:0px;padding-bottom:0px; margin-left:0px;">
        <div style="width:1005px; margin: 0 auto; margin-top:30px;">
			<ul id="myTab" class="nav nav-tabs tabs-main">
			 <li class="active"><a href="#acct_config" data-toggle="tab" lang="en">Account</a></li>
             <li id="password_tab"><a href="#password_config" data-toggle="tab" lang="en">Password</a></li>
            <?php if($user->doctor_privilege != 3) { ?>
             <li id="payments_tab" style="display:none;"><a href="#payments_config" data-toggle="tab" lang="en">Payments</a></li>           
            <li><a href="#notify_config" data-toggle="tab" lang="en">Notifications</a></li>
            <?php } ?>
            <?php    
            
            if ($_SESSION['CustomLook'] != "COL") { 
            ?>    
			        <!-- <li><a href="#scheduler_config" data-toggle="tab" lang="en">Scheduler</a></li>
                     <li><a href="#emr_config" data-toggle="tab" lang="en">EMR Data</a></li>-->
			         <li><a href="#grp_config" data-toggle="tab" lang="en">Group</a></li>
            <?php } ?>    
			<li><a href="#telemed_config" data-toggle="tab" lang="en">Telemedicine</a></li>
			<li><a href="#telemed_activity_config" data-toggle="tab" lang="en">Telemedicine History</a></li>
			<?php 
				if(isset($_GET['appointments_config'])){
					echo "<script>
					$( document ).ready(function() {
						setTimeout(function() { $( '#appointments_tab' ).find('a').trigger( 'click' );}, 1000);
					});
					</script>";
				}
			?>
            <li id="appointments_tab" style="display:none;"><a href="#appointments_config" data-toggle="tab" id="app_config_button" lang="en">Appointments</a></li>
            <?php if($user->doctor_privilege != 3) { ?>
            <li><a href="#financials_config" data-toggle="tab" lang="en">Financials</a></li>
            <li><a href="#probe_config" data-toggle="tab" lang="en">Probes</a></li>
            <!--li><a href="#professional_info" data-toggle="tab" lang="en">Professional Info</a></li-->
            <?php } ?>
        </ul>
	        <!--Form Validation Start-->
			<div id="myTabContent" class="tab-content tabs-main-content padding-null" style="padding-left:0px; background: #F9F9F9; height:auto; width:100%;">
				<div class="tab-pane tab-overflow-main fade in active" id="acct_config" style="padding-left:30px;">
			        <div class="grid" class="grid span4" style="width:700px; margin: 0 auto; margin-top:30px;">
						<div class="grid-content" style="padding-top:10px;">
                            
                            <link rel="stylesheet" href="css/doctor_style_mars.css">
               
                    <!--div style="background-color: #22AEFF; color: #FFF; padding: 12px; border-radius: 8px; margin: auto; margin-bottom:40px; text-align: center; font-size:30px; width: 94%;" lang="en">Credential Overview</div>  
                    <div class="doctor_row" style="width:96%; float:none; margin-bottom:40px; border:1px solid #BEBEBE;">
                        <div class="ribbon-preferred">
                            <i class="icon-star" style="color:white; font-size:20px; margin-right:20px;"></i>
                        </div>                               

                        <div style="width:120px; height: 120px; float:left;">
                            <img class="doctor_pic" src="<?=$filename; ?>">
                            <img class="doctor_certification_icon" src="" style="display:none; margin-left:10px; margin-top:-50px; float:none;">
                        </div>
                        <div class="doctor_main_label" style="margin-left:0;">
                            <div class="doctor_name" style="width:150px;">
                                <span style="color:#22AEFF;">Mars Kim</span>
                            </div>
                            <div class="doctor_speciality" style="width:150px;">Gynecology and Obstetrics</div>
                            <div class="doctor_hospital_info" style="width:150px;">
                                <div class="doctor_hospital_name" title="<?=$user->doctor_hospital_address; ?>"><?=(!empty($user->doctor_hospital_name) ? $user->doctor_hospital_name : 'Hospital Not Specified');?></div>
                                <div class="doctor_stars">
                                    <i class="icon-star" style="float:left; font-size:12px; color:#BDBDBD;"></i>
                                    <i class="icon-star" style="float:left; font-size:12px; color:#BDBDBD;"></i>
                                    <i class="icon-star" style="float:left; font-size:12px; color:#BDBDBD;"></i>
                                    <i class="icon-star" style="float:left; font-size:12px; color:#BDBDBD;"></i>
                                    <i class="icon-star" style="float:left; font-size:12px; color:#BDBDBD;"></i>
                                </div>
                                <div class="doctor_action_box">
                                    <button class="doctor_action_button" style="background-color:gray;">Schedule</button>
                                </div>
                            </div>
                        </div>
                        <div class="doctor_description" style="margin-left:-12px; width:205px;">
                            <div class="doctor_ex_y">
                                <span class="ex_y green_letters">18</span>
                                  years experience
                            </div>
                            <div class="doctor_mem_assoc">
                                Member of 
                                <span class="mem_assoc green_letters">5</span>
                                 Medical Associations
                            </div>
                            <div class="doctor_art_pub">
                                <span class="art_pub green_letters">8</span>
                                 Articles Published
                            </div>
                            <div class="doctor_expertise">
                                <span class="label label-success expertise">Weight Gain</span>
                                <span class="label label-success expertise">Rehabilitation</span>
                            </div>
                        </div>
                        <div class="doctor_license" style="margin-top:-110px; margin-right:-12px;">
                            <div class="license_container">
                                <img id="licImg_1" class="license_flag" src="../images/states_mbs/TMB.jpg">
                                <div id="licInfo_1" class="license_locInfo">
                                    <span class="lic_state_name">TX</span>
                                    <br>
                                    <span class="lic_country_name">USA</span>
                                </div>
                            </div>
                        </div>
                    </div-->
                                                
                            
				           	<div style="background-color: #22AEFF; color: #FFF; padding: 12px; border-radius: 8px; margin: auto; text-align: center; font-size:30px; width: 94%;" lang="en">Account Configuration</div>         
						    <div class="clearfix" style="margin-bottom:20px;"></div>

                            
				            <form id="formID" class="formular" method="post" action="UpdateMEDUser.php">
                 
                 <input type="hidden" id="EnviaUserid" name="EnviaUserid" Value="<?php echo $user->med_id; ?>">	
                 <input type="hidden" id="EnviaModo" name="EnviaModo" Value="<?php echo $QueEntrada; ?>">	
                 <input type="hidden" id="EnviaTipoUsuario" name="EnviaTipoUsuario" Value="2">	
                 <input type="hidden" id="VIdUsFIXEDINSERT" name="VIdUsFIXED" Value="<?php echo $user->doctor_fixed_id; ?>">	
                 <input type="hidden" id="VIdUsFIXEDNAMEINSERT" name="VIdUsFIXEDNAME" Value="<?php echo $user->doctor_fixed_name; ?>">	
                                
                 <div class="clearfix"></div>
                 </br>
                 <span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; " lang="en">User Identification</span>
                 <!--<span id ="VIdUsFIXED" class="label label-success" style="float:right; font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;">00000000</span>-->

                 <div class="clearfix"></div>
                 <div style="margin-left: 40px;">
                     <div class="formRow">
                         <label><span lang="en">Name</span>: </label>
                            <div class="formRight">
                               <input value="" class="validate[required] span" type="text" name="Vname" id="Vname" style="width:360px;" placeholder="Name">
                            </div>
                     </div>
                     
                     <div class="formRow">
                         <label><span lang="en">Surname</span>: </label>
                            <div class="formRight">
                               <input value="" class="validate[required] span" type="text" name="Vsurname" id="Vsurname" style="width:360px;" placeholder="Surname">
                            </div>
                     </div>
                    
                
                    <div id="CLICK">
                     <div class="formRow">
                         <label><span lang="en">Date of Birth</span>: </label>
                         <div class="formRight">
                         <!--<div class="validate[custom[date],past[2013/01/01]] input-append date" id="dpYears" data-date="12-02-2012" data-date-format="dd-mm-yyyy">
                             <input class="span2" size="16" type="text" value="12-02-2010" id="dp32" name="dp32" readonly="" style="width:330px;">
                             <span class="add-on" onclick='return false;'><i class="icon-calendar"></i></span>
                         </div>-->
                             <input id="dp32" name="dp32" type="date" value=""/>
                         </div>
                     </div>
                    </div>
                     
                     <div class="formRow">
                            <label><span lang="en">Gender</span>: </label>
                            <div class="formRight">
                                <select name="gender" id="gender" class="validate[required] span_select" style="width:360px;">
                                    <option value="">Select Gender:</option>
                                    <option value="0">Female</option> 
                                    <option value="1">Male</option>
                                </select>
                            </div>
                     </div>
                    <div class="formRow">
                        <label><span lang="en">Speciality</span>: </label>
                        <div class="formRight">
                            <select name="speciality" id="speciality" style="width:360px;">
                                <option value="Allergy and Immunology">Allergy and Immunology</option>
                                <option value="Anaesthetics">Anaesthetics</option>
                                <option value="Cardiology">Cardiology</option>
                                <option value="Cardiothoracic Surgery">Cardiothoracic Surgery</option>
                                <option value="Child & Adolescent Psychiatry">Child & Adolescent Psychiatry</option>
                                <option value="Clinical Neurophysiology">Clinical Neurophysiology</option>
                                <option value="Dermato-Venereology">Dermato-Venereology</option>
                                <option value="Dermatology">Dermatology</option>
                                <option value="-Emergency Medicine">Emergency Medicine</option>
                                <option value="Endocrinology">Endocrinology</option>
                                <option value="Gastroenterology">Gastroenterology</option>
                                <option value="General Practice">General Practice</option>
                                <option value="General Surgery">General Surgery</option>
                                <option value="Geriatrics">Geriatrics</option>
                                <option value="Gynaecology and Obstetrics">Gynaecology and Obstetrics</option>
                                <option value="Health Informatics">Health Informatics</option>
                                <option value="Infectious Diseases">Infectious Diseases</option>
                                <option value="Internal Medicine">Internal Medicine</option>
                                <option value="Interventional Radiology">Interventional Radiology</option>
                                <option value="Microbiology">Microbiology</option>
                                <option value="none">None</option>
                                <option value="Neonatology">Neonatology</option>
                                <option value="Nephrology">Nephrology</option>
                                <option value="Neurology">Neurology</option>
                                <option value="Neuroradiology">Neuroradiology</option>
                                <option value="Neurosurgery">Neurosurgery</option>
                                <option value="Nuclear Medicine">Nuclear Medicine</option>
                                <option value="Occupational Medicine">Occupational Medicine</option>
                                <option value="Oncology">Oncology</option>
                                <option value="Ophthalmology">Ophthalmology</option>
                                <option value="Oral and Maxillofacial Surgery">Oral and Maxillofacial Surgery</option>
                                <option value="Orthopaedics">Orthopaedics</option>
                                <option value="Otorhinolaryngology">Otorhinolaryngology</option>
                                <option value="Paediatric Cardiology">Paediatric Cardiology</option>
                                <option value="Paediatric Surgery">Paediatric Surgery</option>
                                <option value="Paediatrics">Paediatrics</option>
                                <option value="Pathology">Pathology</option>
                                <option value="Physical Medicine and Rehabilitation">Physical Medicine and Rehabilitation</option>
                                <option value="Plastic, Reconstructive and Aesthetic Surgery">Plastic, Reconstructive and Aesthetic Surgery</option>
                                <option value="Pneumology">Pneumology</option>
                                <option value="Psychiatry">Psychiatry</option>
                                <option value="Public Health">Public Health</option>
                                <option value="Radiology">Radiology</option>
                                <option value="Radiotherapy">Radiotherapy</option>
                                <option value="Stomatology">Stomatology</option>
                                <option value="Vascular Medicine">Vascular Medicine</option>
                                <option value="Vascular Surgery">Vascular Surgery</option>
                                <option value="Urology">Urology</option>
                            </select>
                        </div>
                     </div>
                                
                    <div class="formRow">
                         
                             <div style="margin-left:0px;">
                                 <p>
                                     <input type="checkbox" id="c2" name="cc" >
                                     <label for="c2"><span></span></label>
                                     &nbsp;<span lang="en">Part of a multiple birth ? (twins, triplets, etc.)</span>
                                 </p>
                             </div>
                                 <div class="clearfix"></div>
                 
                            <div id="MULTIPLE" style="margin-left:0px; display:none;">
                       
                            <label lang="en">Order of Birth: </label>
                            <div class="formRight">
                                <select name="orderOB" id="orderOB" style="width:200px;">
                                    <option value="" lang="en">Select Order:</option>
                                    <option value="1" lang="en">First</option> 
                                    <option value="2" lang="en">Second</option>
                                    <option value="3" lang="en">Third</option>
                                    <option value="4" lang="en">Fourth</option>
                                </select>
                            </div>
                            </div>
                     </div>
                 </div> 
                            
                <div class="clearfix"></div>
                 <hr>      
                            
                <span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; " lang="en">Connections</span>
                <div style="margin-left: 40px;">
                      <div class="formRow">
                            <label><span lang="en">Email</span>: </label>
                            <div class="formRight">
                               <input value="" class="validate[required, custom[email]] span" type="email" name="Vemail" id="Vemail" style="width:310px;" placeholder="email">
                                <!--<input value="" class="validate[required, funcCall[checkHELLO]] span" type="email" name="Vemail" id="Vemail" style="width:310px;" placeholder="email">-->
                            </div>
                     </div>
                        <style>
                            .VphoneformError{
                                margin-left:5px;
                            }
                        </style>
                      <div class="formRow">
                            <label><span lang="en">Phone</span>: </label>
                            <div class="formRight">
                               <!--<input value="" type="text" name="Vphone" id="Vphone" style="width:300px;" placeholder="phone">-->
                                
                                <input id="Vphone" type="text" name="Vphone" class="intermediate-input validate[required, funcCall[checkPhoneFormat]] span" placeholder="phone" title="Please insert your phone number including country code(just numbers, no special characters or punctuation signs)"/>
                              
                            </div>
                     </div>
                 </div>
 
                 <div class="clearfix"></div>
                 <hr>

                 <span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; " lang="en">Licenses</span>
                 <!--<span id ="VIdUsFIXEDNAME" class="label label-success" style="float:right; font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;" lang="en">name.surname</span>-->
                 
                 </br></br>
                
                <div style="margin-left: 40px;">
                    <div class="formRow">
                        <label><span lang="en">Country</span>: </label>
                        <div class="formRight">
                            <select id="country" name ="country"></select>
                        </div>
                     </div>
                    <div class="formRow">
                        <label><span lang="en">Region</span>: </label>
                        <div class="formRight">
                            <select name ="state" id ="state"></select>
                        </div>
			</div>

                       <div class="formRow" style="display: none;">
                        <label><span lang="en">Additional Licenses</span>: </label>
                        <div class="formRight">
			<select style='float:left;' name ="state2" id ="state2"></select>
			<i onclick='removeAdditionalLicenses();' style='margin-left:25px;margin-top:10px;float:left;' class='icon-minus'></i>
			<i onclick='addAdditionalLicenses();' style='margin-left:25px;margin-top:10px;float:left;' class='icon-plus'></i>
			<div style='float:right;width:200px;height:150px;border:1px solid #cacaca;border-radius:5px;'>
			<center><span lang="en">Additional Licenses</span></center><hr style='margin:5px;' />
			<div id='display-additional-licenses'>
			</div>
			</div>
                        </div>
                     </div>
                    
                    <script type= "text/javascript" src = "../../../../js/countries.js"></script>
                    <script language="javascript">
                        populateCountries("country", "state");
var states ="Alabama|Alaska|Arizona|Arkansas|California|Colorado|Connecticut|Delaware|District of Columbia|Florida|Georgia|Hawaii|Idaho|Illinois|Indiana|Iowa|Kansas|Kentucky|Louisiana|Maine|Maryland|Massachusetts|Michigan|Minnesota|Mississippi|Missouri|Montana|Nebraska|Nevada|New Hampshire|New Jersey|New Mexico|New York|North Carolina|North Dakota|Ohio|Oklahoma|Oregon|Pennsylvania|Rhode Island|South Carolina|South Dakota|Tennessee|Texas|Utah|Vermont|Virginia|Washington|West Virginia|Wisconsin|Wyoming";
var stateElement = document.getElementById( 'state2' );
	
	stateElement.length=0;	// Fixed by Julian Woods
	stateElement.options[0] = new Option('Select State','-1');
    
	stateElement.selectedIndex = 0;
var state_arr = states.split("|");
for (var i=0; i<state_arr.length; i++) {
		stateElement.options[stateElement.length] = new Option(state_arr[i],state_arr[i]);
	}
                    </script>
                
                </div>
                
                 <div class="clearfix"></div>
                
                
                <hr>

                 <span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; " lang="en">Hospital Information</span>
                <br/><br/>
                 <div style="margin-left: 40px;">
                     <div class="formRow">
                            <label><span lang="en">Hospital Name</span>: </label>
                            <div class="formRight">
                               <input value="<?php echo $user->doctor_hospital_name; ?>" class="span" type="text" name="Hname" id="Hname" style="width:300px;" placeholder="Name">
                            </div>
                     </div>
                     
                     </br></br></br>
                    
                    <div class="formRow">
                        <label><span lang="en">Hospital Address</span>: </label>
                        <div class="formRight">
                           <textarea name="Haddress" id="Haddress" rows="3" cols="25" style="width: 285px;" value=""><?php echo $user->doctor_hospital_address; ?></textarea>
                        </div>
                     </div>
                    <!--<script language="javascript">
                        populateCountries("Hcountry", "Hstate");
                    </script>-->
                </div>
                
                
                
                 <div class="clearfix"></div>
                
                
                
                
                
                 <hr>
 
                    <div>
                        <span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; " lang="en">Image</span>

                         <div id="doctorImageDiv" style="width: 80px; height: 80px; margin: auto;" lang="en">
                            <input type="image" src="../../../../<?=$filename;?>" id="doctorImage" style="width:80px; height:80px;" />
                             <div class="row">
                                <label style="display:none;" for="fileToUpload">Select a File to Upload</label><br />
                                <input style="display:none;" type="file" name="fileToUpload" id="fileToUpload2" onchange="fileSelected();" />
                            </div>
                            <div style="display:none;" id="fileName"></div>
                            <div style="display:none;" id="fileSize"></div>
                            <div style="display:none;" id="fileType"></div>
                            <div class="row">
                            <input id="make_upload" style="width:0px;display:none;" type="button" onclick="uploadFile2()" value="Upload" />
                            </div>
                            <div id="progressNumber" style="color: #22aeff; font-weight: bold;"></div>
                         </div>
                         <!--div id = "SelectFileButton" style="width: 130px; height: 35px; margin: auto;">
                            <input type="file" name="file_upload" id="file_upload" style="width: 150px; height: 35px;"/>
                         </div-->

                    </div>
                
                <hr>
 
                    <div>
                        <span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px;" lang="en">School Certifications</span>
                        <style>
                            .add_new_certification_button{
                                width: 100%;
                                height: 35px;
                                border: 0px solid #FFF;
                                outline: 0px;
                                border-radius: 5px;
                                color: #FFF;
                                background-color: #22AEFF;
                            }
                            .certification_button{
                                width: 100%;
                                height: 30px;
                                border: 0px solid #FFF;
                                outline: 0px;
                                border-radius: 5px;
                                color: #FFF;
                                background-color: #22AEFF;
                            }
                        </style>
                        <input type="hidden" id="certifications_max_count" name="certifications_max_count" value ="0" />
                        <div id="certifications_container">
                            <!--<div style="width:78%; height: 70px; background-color: #FBFBFB; border: 1px solid #E5E5E5; padding: 2%; margin: auto; margin-bottom: 10px; margin-top: 15px;">
                                <div style="width: 100px; height: 70px; float: right;">
                                    <button class="certification_button">Change Image</button>
                                    <input type="file" id="certification_file_1" name="certification_file_1" style="display: none;" />
                                    <input type="hidden" id="certification_filedata_1" name="certification_filedata_1" />
                                    <button class="certification_button" style="background-color: #D84840; margin-top: 10px;">delete</button>
                                </div>
                                <img id="certification_image_1" style="width: 70px; height: 70px; float: right; margin-right: 60px;" />
                                <input type="text" id="certification_name_1" name="certification_name_1" placeholder="Certification Name" style="border: 1px solid #CCC; box-shadow: none; width: 216px; height: 25px; border-radius: 0px; padding: 4px; padding-left: 10px; margin-bottom: 0px; border-top-left-radius: 5px; border-top-right-radius: 5px;"  />
                                <div style="width: 100%; height: 40px; margin-top: 0px;">
                                    <div style="height: 22px; width: 64px; border: 1px solid #CCC; border-right: 0px solid #333; float: left; padding: 4px; padding-left: 10px; padding-top: 7px; background-color: #FFF; color: #989898;  border-bottom-left-radius: 5px;">Start Date: </div>
                                    <input type="date" name="certification_date_1" id="certification_date_1"  style="height: 25px; width: 140px; float: left; border: 1px solid #CCC; border-left: 0px solid #333; border-radius: 0px; color: #989898; box-shadow: none; outline: 0px;  border-bottom-right-radius: 5px;" />
                                </div>
                            </div>-->
                        </div>
                        <div style="margin: auto; width: 90%; height: 35px; margin-top: 10px;">
                            <button class="add_new_certification_button" lang="en">Add Certification</button>
                        </div>
                    </div>
                     
                 <hr>

                
        
        
                 </br>
                 <div style="width: 200px; margin: auto;">
                    <input lang="en" id="submit_button" type="submit" class="btn btn-large btn-primary" value="Save" style="background-color: #22AEFF; width:200px;">
                 </div>
            
                </form>
                 
						</div>
					</div>   
<div class="clear"></div>
</div>

<!-- RESET PASSWORD -->
                <div class="tab-pane" id="password_config" >
					<div class="grid" class="grid span4" style="width:700px; margin: 0 auto; margin-top:30px; height: 300px;">
						<div class="grid-content" style="padding-top:10px;">
							 <div style="background-color: #22AEFF; color: #FFF; padding: 12px; border-radius: 8px; margin: auto; text-align: center; font-size:30px; width: 94%;" lang="en">Change Password</div>
                            
                            <div style="width: 550px; height:300px; margin: auto; margin-top: 20px;">
                                <div id="password_notification_container" style="width: 600px; height: 30px; margin-top: 20px; opacity: 0.0;">
                                    <div id="password_notification" style="height: 30px; text-align: center; padding-top: 10px; width: 500px; background-color: #888; border-radius: 20px; margin-left: 50px; position: relative;"></div>
                                </div>
                                <div style="width: 100%; height: 40px; margin-top: 30px;">
                                    <div style="float: left; margin-top: 6px; margin-bottom: -6px; color: #777; width: 200px; text-align: left;"><span lang="en">Type current password</span>: </div>
                                        <input id="pw1" type="password" style="float: left; height: 15px; width: 200px; padding: 7px; color: #444;" value="" />
                                            <button id="change_password_validate_button" style="width: 75px; height: 30px; background-color: #52D859; color: #FFF; border-radius: 7px; border: 0px solid #FFF; float: left; margin-left: 10px; outline: 0px;" lang="en">Validate</button>
                                </div>
                                <div id="change_password_validated_section" style="display: none;">
                                    <div style="width: 100%; height: 40px;">
                                        <div style="float: left; margin-top: 6px; margin-bottom: -6px; color: #777; width: 200px; text-align: left;"><span lang="en">Type new password</span>: </div>
                                        <input id="pw2" type="password" style="float: left; height: 15px; width: 200px; padding: 7px; color: #444;" value="" />
                                    </div>
                                    <div style="width: 100%; height: 40px;">
                                        <div style="float: left; margin-top: 6px; margin-bottom: -6px; color: #777; width: 200px; text-align: left;"><span lang="en">Retype new password</span>: </div>
                                        <input id="pw3" type="password" style="float: left; height: 15px; width: 200px; padding: 7px; color: #444;" value="" />
                                        <button id="change_password_finish_button" style="width: 75px; height: 30px; background-color: #52D859; color: #FFF; border-radius: 7px; border: 0px solid #FFF; float: left; margin-left: 10px; outline: 0px;"><span lang="en">Finish</span></button>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
<!-- END RESET PASSWORD -->
<!-- PAYMENTS CONFIGURATION -->
                <div class="tab-pane" id="payments_config" >
					<div class="grid" class="grid span4" style="width:700px; margin: 0 auto; margin-top:30px;">
						<div class="grid-content" style="padding-top:10px;">
							 <div style="background-color: #22AEFF; color: #FFF; padding: 12px; border-radius: 8px; margin: auto; text-align: center; font-size:30px; width: 94%;" ><span lang="en">Payments Configuration</span></div>
                            <div id="credit_card_loader" style="width: 220px; height: 19px; margin: 10px auto; visibility: hidden;">
                                <img src="../../../../images/load/8.gif" alt="">
                            </div>
                             <style>
                                .credit_card_row{
                                    background-color: #FBFBFB;
                                    color: #222;
                                    border: 1px solid #E6E6E6;
                                    width: 80%;
                                    height: 35px;
                                    padding: 4px;
                                    letter-spacing: 5px;
                                    font-weight: normal;
                                    margin: auto;
                                }
                                .credit_card_button{
                                    width: 100%; 
                                    height: 30px; 
                                    background-color: #52D859; 
                                    border: 0px solid #FFF; 
                                    color: #FFF; 
                                    outline: 0px; 
                                    border-radius: 5px;
                                    padding: 4px;
                                }
                                .credit_card_notification{
                                    width: 57%; 
                                    height: 18px; 
                                    background-color: #52D859; 
                                    border: 0px solid #FFF; 
                                    color: #FFF; 
                                    outline: 0px; 
                                    border-radius: 10px;
                                    padding: 6px;
                                    margin: auto;
                                    margin-top: 20px;
                                    text-align: center;
                                }
                            </style>
                            <div id="credit_cards_container" style="width: 70%; margin-left: auto; margin-right: auto;">
                                <div class="credit_card_row" style="border-radius: 10px; display: none;">
                                    <div id="current_bank_account_country" style="width: 33px; height: 25px; border-radius: 3px; margin-right: 8px; float: right; margin-top: 6px; box-shadow: 0px 0px 2px #333;"></div>
                                    <img src="../../../../images/credit_card_icons/bank.png" style="float: left; margin-left: 10px; height: 38px;" />
                                    <span id="current_bank_account_number" style="float: left; color: #5A5A5A; font-size: 14px; margin-left: 90px; margin-top: 8px;"></span>
                                </div>
                                <!--<div class="credit_card_row">
                                    <button id="clear_credit_card" style="width: 18px; height: 18px; float: right; color: #F00; padding: 0px; background-color: inherit; border: 0px solid #FFF; border-radius: 3px; outline: 0px;">
                                        <i class="icon-remove" style="width: 12px; height: 12px;"></i>
                                    </button>
                                    <img src="images/credit_card_icons/mastercard.png" style="float: left; margin-left: 10px; height: 38px;" />
                                    <span style="float: left; color: #5A5A5A; font-size: 14px; margin-left: 60px; margin-top: 8px;">****     ****     ****      5231</span>
                                </div>
                                <div class="credit_card_row" style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                                    <button id="clear_credit_card" style="width: 18px; height: 18px; float: right; color: #F00; padding: 0px; background-color: inherit; border: 0px solid #FFF; border-radius: 3px; outline: 0px;">
                                        <i class="icon-remove" style="width: 12px; height: 12px;"></i>
                                    </button>
                                    <img src="images/credit_card_icons/american_express.png" style="float: left; margin-left: 10px; height: 38px;" />
                                    <span style="float: left; color: #5A5A5A; font-size: 14px; margin-left: 60px; margin-top: 8px;">****     ****     ****     7396</span>
                                </div>-->
        
                            </div>
                            <div style="width: 56%; height: 190px; margin: auto; margin-top: 20px;">
                                <input type="text" id="bank_account_name" maxLength="16" placeholder="Enter full name on bank account" style="width: 97%; height: 20px; border-radius: 5px;">
                                <input type="text" onkeypress="return isNumberKey(event)" id="bank_account_number" maxLength="16" placeholder="Enter bank account number" style="width: 97%; height: 20px; border-radius: 5px;">
                                <script>
                                function isNumberKey(evt)
                                {
                                    var charCode = (evt.which) ? evt.which : event.keyCode
                                    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 45)
                                        return false;
                                    return true;
                                } 
                                </script>
                                <select id="bank_account_country" style="width: 100%; height: 30px; font-size: 12px; border-radius: 0px; border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
                                    <option value="NONE">Select Country</option>
                                    <option value="AF">Afghanistan</option>
                                    <option value="AX">Åland Islands</option>
                                    <option value="AL">Albania</option>
                                    <option value="DZ">Algeria</option>
                                    <option value="AS">American Samoa</option>
                                    <option value="AD">Andorra</option>
                                    <option value="AO">Angola</option>
                                    <option value="AI">Anguilla</option>
                                    <option value="AQ">Antarctica</option>
                                    <option value="AG">Antigua and Barbuda</option>
                                    <option value="AR">Argentina</option>
                                    <option value="AM">Armenia</option>
                                    <option value="AW">Aruba</option>
                                    <option value="AU">Australia</option>
                                    <option value="AT">Austria</option>
                                    <option value="AZ">Azerbaijan</option>
                                    <option value="BS">Bahamas</option>
                                    <option value="BH">Bahrain</option>
                                    <option value="BD">Bangladesh</option>
                                    <option value="BB">Barbados</option>
                                    <option value="BY">Belarus</option>
                                    <option value="BE">Belgium</option>
                                    <option value="BZ">Belize</option>
                                    <option value="BJ">Benin</option>
                                    <option value="BM">Bermuda</option>
                                    <option value="BT">Bhutan</option>
                                    <option value="BO">Bolivia, Plurinational State of</option>
                                    <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                    <option value="BA">Bosnia and Herzegovina</option>
                                    <option value="BW">Botswana</option>
                                    <option value="BV">Bouvet Island</option>
                                    <option value="BR">Brazil</option>
                                    <option value="IO">British Indian Ocean Territory</option>
                                    <option value="BN">Brunei Darussalam</option>
                                    <option value="BG">Bulgaria</option>
                                    <option value="BF">Burkina Faso</option>
                                    <option value="BI">Burundi</option>
                                    <option value="KH">Cambodia</option>
                                    <option value="CM">Cameroon</option>
                                    <option value="CA">Canada</option>
                                    <option value="CV">Cape Verde</option>
                                    <option value="KY">Cayman Islands</option>
                                    <option value="CF">Central African Republic</option>
                                    <option value="TD">Chad</option>
                                    <option value="CL">Chile</option>
                                    <option value="CN">China</option>
                                    <option value="CX">Christmas Island</option>
                                    <option value="CC">Cocos (Keeling) Islands</option>
                                    <option value="CO">Colombia</option>
                                    <option value="KM">Comoros</option>
                                    <option value="CG">Congo</option>
                                    <option value="CD">Congo, the Democratic Republic of the</option>
                                    <option value="CK">Cook Islands</option>
                                    <option value="CR">Costa Rica</option>
                                    <option value="CI">Côte d'Ivoire</option>
                                    <option value="HR">Croatia</option>
                                    <option value="CU">Cuba</option>
                                    <option value="CW">Curaçao</option>
                                    <option value="CY">Cyprus</option>
                                    <option value="CZ">Czech Republic</option>
                                    <option value="DK">Denmark</option>
                                    <option value="DJ">Djibouti</option>
                                    <option value="DM">Dominica</option>
                                    <option value="DO">Dominican Republic</option>
                                    <option value="EC">Ecuador</option>
                                    <option value="EG">Egypt</option>
                                    <option value="SV">El Salvador</option>
                                    <option value="GQ">Equatorial Guinea</option>
                                    <option value="ER">Eritrea</option>
                                    <option value="EE">Estonia</option>
                                    <option value="ET">Ethiopia</option>
                                    <option value="FK">Falkland Islands (Malvinas)</option>
                                    <option value="FO">Faroe Islands</option>
                                    <option value="FJ">Fiji</option>
                                    <option value="FI">Finland</option>
                                    <option value="FR">France</option>
                                    <option value="GF">French Guiana</option>
                                    <option value="PF">French Polynesia</option>
                                    <option value="TF">French Southern Territories</option>
                                    <option value="GA">Gabon</option>
                                    <option value="GM">Gambia</option>
                                    <option value="GE">Georgia</option>
                                    <option value="DE">Germany</option>
                                    <option value="GH">Ghana</option>
                                    <option value="GI">Gibraltar</option>
                                    <option value="GR">Greece</option>
                                    <option value="GL">Greenland</option>
                                    <option value="GD">Grenada</option>
                                    <option value="GP">Guadeloupe</option>
                                    <option value="GU">Guam</option>
                                    <option value="GT">Guatemala</option>
                                    <option value="GG">Guernsey</option>
                                    <option value="GN">Guinea</option>
                                    <option value="GW">Guinea-Bissau</option>
                                    <option value="GY">Guyana</option>
                                    <option value="HT">Haiti</option>
                                    <option value="HM">Heard Island and McDonald Islands</option>
                                    <option value="VA">Holy See (Vatican City State)</option>
                                    <option value="HN">Honduras</option>
                                    <option value="HK">Hong Kong</option>
                                    <option value="HU">Hungary</option>
                                    <option value="IS">Iceland</option>
                                    <option value="IN">India</option>
                                    <option value="ID">Indonesia</option>
                                    <option value="IR">Iran, Islamic Republic of</option>
                                    <option value="IQ">Iraq</option>
                                    <option value="IE">Ireland</option>
                                    <option value="IM">Isle of Man</option>
                                    <option value="IL">Israel</option>
                                    <option value="IT">Italy</option>
                                    <option value="JM">Jamaica</option>
                                    <option value="JP">Japan</option>
                                    <option value="JE">Jersey</option>
                                    <option value="JO">Jordan</option>
                                    <option value="KZ">Kazakhstan</option>
                                    <option value="KE">Kenya</option>
                                    <option value="KI">Kiribati</option>
                                    <option value="KP">Korea, Democratic People's Republic of</option>
                                    <option value="KR">Korea, Republic of</option>
                                    <option value="KW">Kuwait</option>
                                    <option value="KG">Kyrgyzstan</option>
                                    <option value="LA">Lao People's Democratic Republic</option>
                                    <option value="LV">Latvia</option>
                                    <option value="LB">Lebanon</option>
                                    <option value="LS">Lesotho</option>
                                    <option value="LR">Liberia</option>
                                    <option value="LY">Libya</option>
                                    <option value="LI">Liechtenstein</option>
                                    <option value="LT">Lithuania</option>
                                    <option value="LU">Luxembourg</option>
                                    <option value="MO">Macao</option>
                                    <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                    <option value="MG">Madagascar</option>
                                    <option value="MW">Malawi</option>
                                    <option value="MY">Malaysia</option>
                                    <option value="MV">Maldives</option>
                                    <option value="ML">Mali</option>
                                    <option value="MT">Malta</option>
                                    <option value="MH">Marshall Islands</option>
                                    <option value="MQ">Martinique</option>
                                    <option value="MR">Mauritania</option>
                                    <option value="MU">Mauritius</option>
                                    <option value="YT">Mayotte</option>
                                    <option value="MX">Mexico</option>
                                    <option value="FM">Micronesia, Federated States of</option>
                                    <option value="MD">Moldova, Republic of</option>
                                    <option value="MC">Monaco</option>
                                    <option value="MN">Mongolia</option>
                                    <option value="ME">Montenegro</option>
                                    <option value="MS">Montserrat</option>
                                    <option value="MA">Morocco</option>
                                    <option value="MZ">Mozambique</option>
                                    <option value="MM">Myanmar</option>
                                    <option value="NA">Namibia</option>
                                    <option value="NR">Nauru</option>
                                    <option value="NP">Nepal</option>
                                    <option value="NL">Netherlands</option>
                                    <option value="NC">New Caledonia</option>
                                    <option value="NZ">New Zealand</option>
                                    <option value="NI">Nicaragua</option>
                                    <option value="NE">Niger</option>
                                    <option value="NG">Nigeria</option>
                                    <option value="NU">Niue</option>
                                    <option value="NF">Norfolk Island</option>
                                    <option value="MP">Northern Mariana Islands</option>
                                    <option value="NO">Norway</option>
                                    <option value="OM">Oman</option>
                                    <option value="PK">Pakistan</option>
                                    <option value="PW">Palau</option>
                                    <option value="PS">Palestinian Territory, Occupied</option>
                                    <option value="PA">Panama</option>
                                    <option value="PG">Papua New Guinea</option>
                                    <option value="PY">Paraguay</option>
                                    <option value="PE">Peru</option>
                                    <option value="PH">Philippines</option>
                                    <option value="PN">Pitcairn</option>
                                    <option value="PL">Poland</option>
                                    <option value="PT">Portugal</option>
                                    <option value="PR">Puerto Rico</option>
                                    <option value="QA">Qatar</option>
                                    <option value="RE">Réunion</option>
                                    <option value="RO">Romania</option>
                                    <option value="RU">Russian Federation</option>
                                    <option value="RW">Rwanda</option>
                                    <option value="BL">Saint Barthélemy</option>
                                    <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                    <option value="KN">Saint Kitts and Nevis</option>
                                    <option value="LC">Saint Lucia</option>
                                    <option value="MF">Saint Martin (French part)</option>
                                    <option value="PM">Saint Pierre and Miquelon</option>
                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                    <option value="WS">Samoa</option>
                                    <option value="SM">San Marino</option>
                                    <option value="ST">Sao Tome and Principe</option>
                                    <option value="SA">Saudi Arabia</option>
                                    <option value="SN">Senegal</option>
                                    <option value="RS">Serbia</option>
                                    <option value="SC">Seychelles</option>
                                    <option value="SL">Sierra Leone</option>
                                    <option value="SG">Singapore</option>
                                    <option value="SX">Sint Maarten (Dutch part)</option>
                                    <option value="SK">Slovakia</option>
                                    <option value="SI">Slovenia</option>
                                    <option value="SB">Solomon Islands</option>
                                    <option value="SO">Somalia</option>
                                    <option value="ZA">South Africa</option>
                                    <option value="GS">South Georgia and the South Sandwich Islands</option>
                                    <option value="SS">South Sudan</option>
                                    <option value="ES">Spain</option>
                                    <option value="LK">Sri Lanka</option>
                                    <option value="SD">Sudan</option>
                                    <option value="SR">Suriname</option>
                                    <option value="SJ">Svalbard and Jan Mayen</option>
                                    <option value="SZ">Swaziland</option>
                                    <option value="SE">Sweden</option>
                                    <option value="CH">Switzerland</option>
                                    <option value="SY">Syrian Arab Republic</option>
                                    <option value="TW">Taiwan, Province of China</option>
                                    <option value="TJ">Tajikistan</option>
                                    <option value="TZ">Tanzania, United Republic of</option>
                                    <option value="TH">Thailand</option>
                                    <option value="TL">Timor-Leste</option>
                                    <option value="TG">Togo</option>
                                    <option value="TK">Tokelau</option>
                                    <option value="TO">Tonga</option>
                                    <option value="TT">Trinidad and Tobago</option>
                                    <option value="TN">Tunisia</option>
                                    <option value="TR">Turkey</option>
                                    <option value="TM">Turkmenistan</option>
                                    <option value="TC">Turks and Caicos Islands</option>
                                    <option value="TV">Tuvalu</option>
                                    <option value="UG">Uganda</option>
                                    <option value="UA">Ukraine</option>
                                    <option value="AE">United Arab Emirates</option>
                                    <option value="GB">United Kingdom</option>
                                    <option value="US">United States</option>
                                    <option value="UM">United States Minor Outlying Islands</option>
                                    <option value="UY">Uruguay</option>
                                    <option value="UZ">Uzbekistan</option>
                                    <option value="VU">Vanuatu</option>
                                    <option value="VE">Venezuela, Bolivarian Republic of</option>
                                    <option value="VN">Viet Nam</option>
                                    <option value="VG">Virgin Islands, British</option>
                                    <option value="VI">Virgin Islands, U.S.</option>
                                    <option value="WF">Wallis and Futuna</option>
                                    <option value="EH">Western Sahara</option>
                                    <option value="YE">Yemen</option>
                                    <option value="ZM">Zambia</option>
                                    <option value="ZW">Zimbabwe</option>
                                </select>
  
                                <input type="text" onkeypress="return isNumberKey(event)" id="bank_account_routing_number" maxLength="16" placeholder="Enter routing number" style="width: 97%; height: 20px; float: left; border-radius: 5px;">
                                <button id="add_card_button" style="width: 48%; height: 30px; background-color: #52D859; border-radius: 0px; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px; border-radius: 5px;"><span lang="en">Set Bank Account</span></button>
                                <button id="remove_card_button" style="width: 48%; height: 30px; background-color: #D84840; border-radius: 0px; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px; margin-left: 4%; border-radius: 5px;"><span lang="en">Clear Bank Account</span></button>
                            
                            </div>
                            <div class="credit_card_notification" style="opacity: 0.0;"><span lang="en">Card Added</span>!</div>
                             
                            
                            
                        </div>
                    </div>
                </div>



<!--CONTENT MAIN END-->
                <?php if($user->doctor_privilege != 3) { ?>
				<div class="tab-pane" id="notify_config" >
					<div class="grid" class="grid span4" style="width:700px; margin: 0 auto; margin-top:30px;">
						<div class="grid-content" style="padding-top:10px;">
							 <div style="background-color: #22AEFF; color: #FFF; padding: 12px; border-radius: 8px; margin: auto; text-align: center; font-size:30px; width: 94%;"><span lang="en">Notification Configuration</span></div>
						     <div class="clearfix" style="margin-bottom:20px;"></div>
							 <form id="formID" class="formular" method="post" action="">
                
				<div class="clearfix"></div>
                <style>
                    .patient_row_button{
                        width: 31px; 
                        height: 33px;
                        padding-left: 9px;
                        padding-top: 5px; 
                        border-radius: 40px; 
                        margin-left: 25px;
                        float: left;
                        font-size: 24px;
                        background-color: #FFF;
                        border: 2px solid #CECECE;
                    }
                    #buttons_notification li > input[type='checkbox'] {
                        display: inline;
                    }
                    #buttons_notification ul {
                        text-align: left;
                        list-style-type: none;
                        margin: 0;
                        padding-left: 15px;
                    }
                    #buttons_notification li {
                        font-size: 0.8em;
                        color: grey;
                    }
                    #buttons_notification p {
                        margin-top: 10px;
                        color: #22AEFF;
                        font-weight: bold;
                    }
                </style>
            
          
 
                  <div class="formRow" style="margin-bottom:50px;">
                     
	                     <div style="margin-left:10px; padding-bottom:20px;">
		                     <p>
			                     <!--input type="checkbox" id="notify_link" name="cc" >
			                     <label for="notify_link" lang="en"><span></span> Send Internal Notifications to external Email ID</label-->
                                 <div id="buttons_notification" style="float:left; margin: 15px auto; text-align: center; width: 100%;">
                                     <div id="referralN" style="width:14%; float: left; text-align: center;">
                                        <span class="patient_row_button" title="Report updated in the last 30 days" style="color:green;"> 
                                            <i class="icon-circle-arrow-right" style="margin-left: -8px; margin-top: 2px;"></i>   
                                        </span>
                                        <div style="clear:both;"></div>
                                        <p lang="en">Referrals</p>
                                        <ul>
                                            <li><input type="checkbox" id="refTxt"> <span lang="en">Text</span></li>
                                            <!--li lang="en"><input type="checkbox" id="refVoice"> Voice Msg</li-->
                                            <li><input type="checkbox" id="refEmail"> <span lang="en">Email</span></li>
                                        </ul>       
                                    </div>
                                     
                                    <div id="reportsN" style="width:14%; float: left; text-align: center;">
                                        
                                        <span class="patient_row_button" title="Report updated in the last 30 days" style="color:blue;"> 
                                            <i class="icon-folder-open" style="margin-left: -5px;"></i>   
                                        </span>
                                        <div style="clear:both;"></div>
                                        <p lang="en">Reports</p>
                                            <ul>
                                                <li><input type="checkbox" id="repTxt"> <span lang="en">Text</span></li>
                                                <!--li lang="en"><input type="checkbox" id="repVoice"> Voice Msg</li-->
                                                <li><input type="checkbox" id="repEmail"> <span lang="en">Email</span></li>
                                            </ul>       
                                    </div>

                                    <div id="summaryN" style="width:14%; float: left; text-align: center;">
                                        
                                        <span class="patient_row_button" title="Summary changed in the last 30 days" style="color:violet;">
                                            <i class="icon-edit" style="margin-left: -5px;"></i>
                                        </span>
                                        <div style="clear:both;"></div>
                                        <p lang="en">Summary</p>
                                            <ul>
                                                <li><input type="checkbox" id="sumTxt"> <span lang="en">Text</span></li>
                                                <!--li lang="en"><input type="checkbox" id="sumVoice"> Voice Msg</li-->
                                                <li><input type="checkbox" id="sumEmail"> <span lang="en">Email</span></li>
                                            </ul>       
                                    </div>


                                    <div id="messageN" style="width:14%; float: left; text-align: center;">
                                        
                                        <span class="patient_row_button" title="Messages in the last 30 days" style="color:#FFC23B;">
                                            <i class="icon-envelope-alt" style="margin-left: -8px;"></i>      
                                        </span>
                                        <div style="clear:both;"></div>
                                        <p lang="en">Messages</p>
                                            <ul>
                                                <li><input type="checkbox" id="msgTxt"> <span lang="en">Text</span></li>
                                                <!--li lang="en"><input type="checkbox" id="msgVoice"> Voice Msg</li-->
                                                <li><input type="checkbox" id="msgEmail"> <span lang="en">Email</span></li>
                                            </ul>       
                                    </div>
                                    <div id="probeN" style="width:14%; float: left; text-align: center;">
                                        
                                        <span class="patient_row_button" title="Probe responses in the last 30 days" style="color:#89150B;">
                                            <i class="icon-signal" style="margin-left: -8px;"></i>
                                        </span>   
                                        <div style="clear:both;"></div>
                                        <p lang="en">Probes</p>
                                            <ul>
                                                <li><input type="checkbox" id="prbTxt"> <span lang="en">Text</span></li>
                                                <!--li lang="en"><input type="checkbox" id="prbVoice"> Voice Msg</li-->
                                                <li><input type="checkbox" id="prbEmail"> <span lang="en">Email</span></li>
                                            </ul>       
                                    </div>

                                    <div id="appointmentN" style="width:14%; float: left; text-align: center;">
                                        
                                        <span class="patient_row_button" title="Appointment made in the last 30 days" style="color:#00C9AB;">
                                            <i class="icon-calendar" style="margin-left: -9px;"></i>
                                        </span>
                                        <div style="clear:both;"></div>
                                        <p lang="en">Appointments</p>
                                            <ul>
                                                <li><input type="checkbox" id="aptTxt"> <span lang="en">Text</span></li>
                                                <!--li lang="en"><input type="checkbox" id="aptVoice"> Voice Msg</li-->
                                                <li><input type="checkbox" id="aptEmail"> <span lang="en">Email</span></li>
                                            </ul>       
                                    </div>
                                     <div id="requestN" style="width:14%; float: left; text-align: center;">
                                        
                                        <span class="patient_row_button" title="Report updated in the last 30 days"> 
                                            <img src="../../../../images/icons/Request_svg_b.png" style="margin-left: -8px; margin-top: -3px;"/>   
                                        </span>
                                         <div style="clear:both;"></div>
                                         <p lang="en">Requests</p>
                                            <ul>
                                                <li><input type="checkbox" id="reqTxt"> <span lang="en">Text</span></li>
                                                <!--li lang="en"><input type="checkbox" id="reqVoice"> Voice Msg</li-->
                                                <li><input type="checkbox" id="reqEmail"> <span lang="en">Email</span></li>
                                            </ul>       
                                    </div>
                                </div>

                             
			                 </p>
	                     </div>
	                         <div class="clearfix"></div>
             
                        <!--<div id="MULTIPLE" style="margin-left:0px; display:block;">
                   
                        <label>Order of Birth: </label>
                        <div class="formRight">
                            <select name="orderOB" id="orderOB" style="width:200px;">
                                <option value="">Select Order:</option>
                                <option value="1">First</option> 
                                <option value="2">Second</option>
                                <option value="3">Third</option>
                                <option value="4">Fourth</option>
                            </select>
                        </div>
                        </div>-->
                 </div>
				 <div class="clearfix"></div>
				 <div class="clearfix"></div>
				 <div class="clearfix"></div>
                 <div style="margin-left: 25px;">
                     Group Notifications By: 
                     <select id="groupN" style="margin-left: 10px;">
                        <option value="0">Notification Type</option>
                        <option value="1">User</option>
                     </select>
                 </div>
				 <hr>
				  <input type="button" class="btn btn-large btn-primary" id="notif_update" lang="en" value="Update" style="width:200px;">
				</form> 
						</div>	 
					</div>
				</div>
                <?php } ?>
				<div class="tab-pane" id="scheduler_config">
							<div class="grid" class="grid span4" style="width:700px; margin: 0 auto; margin-top:30px;">
								<div class="grid-content" style="padding-top:30px;">
									 <span class="label label-info" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px;" lang="en">Scheduler Configuration</span>
								     <div class="clearfix" style="margin-bottom:20px;"></div>
								     <div class="clearfix"></div>
						             		<span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; " lang="en">Visit Types : </span>
											<center>
											<div id="CLICK_EVENT"></div>
											</center>
											<div class="clearfix"></div>
											<br>
											<input type="button" class="btn btn-primary" value="Add more Visit Types" onClick="add_visit_types();" >
											<div class="clearfix"></div>
											<hr>
											<div style="margin-top:-15px;">
											<span lang="en">Send Appointment Notification before</span> &nbsp &nbsp <select id="appoint_notify" style="width:8em;margin-top:10px"> </select>&nbsp <span lang="en">minutes</span>
										 
											</div>
										 <!--<div class="clearfix"></div>-->
										     <hr>
											 <div style="margin-top:-15px;">
											<span lang="en">Timezone</span> &nbsp : &nbsp <select id="Timezone" style="width:15em;margin-top:10px"> </select>
										 
											</div>
											<hr>
										<!--<br><br>-->
										  
										 
										 <!--<br><br>-->
										 <!--<center>-->
											 <input type="button" class="btn btn-large btn-primary" onClick="saveSchedulerConfig();" lang="en" value="UPDATE" style="width:200px;margin-top:20px;">
									     <!--</center>-->
										 
										<br>
								</div>		
							</div>
				 </div>
				 
				 <div class="tab-pane" id="emr_config">
							<div class="grid" class="grid span4" style="width:700px; margin: 0 auto; margin-top:30px;">
								<div class="grid-content" style="padding-top:30px;">
									 <span class="label label-info" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px;" lang="en">EMR Configuration</span>
								     <div class="clearfix" style="margin-bottom:20px;"></div>
								     <div class="clearfix"></div>
											<br>
						             		<input type="checkbox" id="emr_demographics" checked disabled="true"><label for="emr_demographics" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspDemographics : </label>
											<br><br>
											<center>
											<table width="60%" >
												<tr bgcolor="#FFFFFF">
													<td width="50%" > <input type="checkbox" id="emr_name" checked disabled="true">	<label for="emr_name"><span></span>&nbsp&nbsp&nbspName</label> </td>
													<td width="50%" > <input type="checkbox" id="emr_address" >	<label for="emr_address"><span></span>&nbsp&nbsp&nbspAddress</label>  </td>
												<tr>
												<tr bgcolor="#FFFFFF">
													<td width="50%" > <input type="checkbox" id="emr_middle" checked disabled="true">	<label for="emr_middle"><span></span>&nbsp&nbsp&nbspMiddle Initial</label> </td>
													<td width="50%" > <input type="checkbox" id="emr_address2" >	<label for="emr_address2"><span></span>&nbsp&nbsp&nbspAddress2</label>  </td>
												<tr>
												<tr bgcolor="#FFFFFF">
													<td width="50%" > <input type="checkbox" id="emr_surname" checked disabled="true">	<label for="emr_surname"><span></span>&nbsp&nbsp&nbspSurname</label> </td>
													<td width="50%" > <input type="checkbox" id="emr_city" >	<label for="emr_city"><span></span>&nbsp&nbsp&nbspCity</label>  </td>
												<tr>
												<tr bgcolor="#FFFFFF">
													<td width="50%" > <input type="checkbox" id="emr_gender" checked disabled="true">	<label for="emr_gender"><span></span>&nbsp&nbsp&nbspGender</label> </td>
													<td width="50%" > <input type="checkbox" id="emr_state" >	<label for="emr_state"><span></span>&nbsp&nbsp&nbspState</label>  </td>
												<tr>
												<tr bgcolor="#FFFFFF">
													<td width="50%" > <input type="checkbox" id="emr_dob" checked disabled="true">	<label for="emr_dob"><span></span>&nbsp&nbsp&nbspDate of Birth</label> </td>
													<td width="50%" > <input type="checkbox" id="emr_country" >	<label for="emr_country"><span></span>&nbsp&nbsp&nbspCountry</label>  </td>
												<tr>
												<tr bgcolor="#FFFFFF">
													<td width="50%" > <input type="checkbox" id="emr_notes" >	<label for="emr_notes"><span></span>&nbsp&nbsp&nbspNotes</label> </td>
													<td width="50%" > <input type="checkbox" id="emr_phone" >	<label for="emr_phone"><span></span>&nbsp&nbsp&nbspPhone</label>  </td>
												<tr>
												<tr bgcolor="#FFFFFF">
													<td width="50%" > <input type="checkbox" id="emr_insurance" >	<label for="emr_insurance"><span></span>&nbsp&nbsp&nbspInsurance</label> </td>
													<td width="50%" >   </td>
												<tr>
											</table>
											</center>
											
											<div class="clearfix"></div>
											<hr>
											
						             		<input type="checkbox" id="emr_personal" ><label for="emr_personal" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspPersonal History : </label>
						
											<div id="PH_Tab" style="margin-left:0px; display:none;">
												<div class="formRow" >
													<input type="checkbox" id="emr_fractures" >	<label for="emr_fractures" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspFractures and Other Traumas</label>
												</div>
												<div class="formRow" >
													<input type="checkbox" id="emr_surgeries" >	<label for="emr_surgeries" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspSurgeries</label>
												</div>
												<div class="formRow" >
													<input type="checkbox" id="emr_otherknown" >	<label for="emr_otherknown" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspOther Known Medical Events</label>
												</div>
												<div class="formRow" >
													<input type="checkbox" id="emr_obstetric" >	<label for="emr_obstetric" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspObstetric History</label>
												</div>
												<div class="formRow" >
													<input type="checkbox" id="emr_othermed" >	<label for="emr_othermed" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspOther Medical Data</label>
												</div>
											</div>
											
											<div class="clearfix"></div>
											<hr>
											<input type="checkbox" id="emr_family" ><label for="emr_family" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspFamily History : </label>
											<div id="FA_Tab" style="margin-left:0px; display:none;">
												<div class="formRow" >
													<input type="checkbox" id="emr_father" >	<label for="emr_father" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspFather</label>
												</div>
												<div class="formRow" >
													<input type="checkbox" id="emr_mother" >	<label for="emr_mother" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspMother</label>
												</div>
												<div class="formRow" >
													<input type="checkbox" id="emr_siblings" >	<label for="emr_siblings" style="margin-left:60px"><span></span>&nbsp&nbsp&nbspSiblings</label>
												</div>
												
											</div>
											<div class="clearfix"></div>
											<hr>
											<input type="checkbox" id="emr_pastdx" ><label for="emr_pastdx" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspPast Diagnostics  </label>
											
											<div class="clearfix"></div>
											<hr>
											<input type="checkbox" id="emr_medications" ><label for="emr_medications" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspMedications  </label>
	
											<div class="clearfix"></div>
											<hr>
											<input type="checkbox" id="emr_immunizations" ><label for="emr_immunizations" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspImmunizations  </label>
											
											<div class="clearfix"></div>
											<hr>
											<input type="checkbox" id="emr_allergies" ><label for="emr_allergies" style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0;margin-left:20px "><span></span>&nbsp&nbsp&nbspAllergies  </label>
											<hr>
											<input type="button" class="btn btn-large btn-primary" onClick="saveEMRConfig();" lang="en" value="UPDATE" style="width:200px;margin-top:20px;">
											<!--<div style="margin-top:-15px;">
											Send Appointment Notification before &nbsp &nbsp <select id="appoint_notify" style="width:8em;margin-top:10px"> </select>&nbsp minutes
										 
											</div>
										 
										     <hr>
											 <div style="margin-top:-15px;">
											Timezone &nbsp : &nbsp <select id="Timezone" style="width:15em;margin-top:10px"> </select>
										 
											</div>
											<hr>
										
										  
										 
										
											 <input type="button" class="btn btn-large btn-primary" onClick="saveSchedulerConfig();" value="UPDATE" style="width:200px;margin-top:20px;">
									    -->
										 
										<br>
								</div>		
							</div>
				 </div>
				 <div class="tab-pane" id="grp_config">
						<div class="grid" class="grid span4" style="width:700px; margin: 0 auto; margin-top:30px;">
							<div class="grid-content" style="padding-top:10px;">
								 <div style="background-color: #22AEFF; color: #FFF; padding: 12px; border-radius: 8px; margin: auto; text-align: center; font-size:30px; width: 94%;" lang="en">Group Configuration</div>
								 <div class="clearfix" style="margin-bottom:20px;"></div>
								 <!--<form id="formID" class="formular" method="post" action="">-->
					
						<div class="clearfix"></div>

						<div class="formRow" style="margin-bottom:50px;">
						 
						   
							 <!--<div style="margin-left:10px; padding-bottom:20px;">
								 
								 <p>
									 <input id="Groupname" type="text" placeholder="Group Name" >
									 <input id="Groupadd" type="text" placeholder="Address" >
									 <input id="Groupzip" type="text" placeholder="ZIP" >
									 <input id="Groupcity" type="text" placeholder="City" >
									 <input id="Groupstate" type="text" placeholder="State" >
									 <input id="Groupcountry" type="text" placeholder="Country" >
								</p>	 
								
								<div style="margin-top:20px;margin-left:-10px">
								
								 <input id="create_group" type="button" class="btn btn-primary" style="margin-left:20px;margin-top:-10px" value="Create Group">
								 
								</div>
							 </div> -->
							 
							  <div id="create-group-old" style="display:none;margin-left:10px;margin-top:20px;padding-bottom:20px;">
				
			
										<form enctype="multipart/form-data" method="post" style="float:left;margin-left:40px">
											<div class="row">
											  <!--<label for="fileToUpload">Select Files to Upload</label>-->
												
											  <div id="creategrpimage" title="Choose a group picture"><img style="width:150px;height:150px;margin-bottom:10px;text-align: center" src="../../../../images/defaultgrouppic.jpg" alt="Group Picture"/>
											  </div>
											  <input type="file" name="filesToUpload[]" id="createfilesToUpload" multiple="multiple" style="margin-left:30px;width:87px" placeholder="Group Picture" />
											  <!--<output id="filesInfo"></output>-->
											
											<!--<div class="row">
											  <input type="submit" value="Upload" />
											</div>-->
											</div> 
										</form>
										
										
									<p style="margin-top:30px">
									
									<p> <span style="float:left;margin-left:120px" lang="en"> Group Name: </span> <input lang="en" id="Groupname" type="text" placeholder="Group Name" style="margin-left:10px"> </p>
									<p> <span style="float:left;margin-left:120px" lang="en"> Address : </span> <input lang="en" id="Groupadd" type="text" placeholder="Address" style="margin-left:30px">  </p>
									<p> <span style="float:left;margin-left:120px" lang="en"> ZIP: </span> <input lang="en" id="Groupzip" type="text" placeholder="ZIP" style="margin-left:60px"> </p>
									<p> <span style="float:left;margin-left:120px" lang="en"> City: </span> <input lang="en" id="Groupcity" type="text" placeholder="City" style="margin-left:55px"> </p>
									<p> <span style="float:left;margin-left:270px" lang="en"> State: </span> <input lang="en" id="Groupstate" type="text" placeholder="State" style="margin-left:45px"> </p>
									<p> <span style="float:left;margin-left:270px" lang="en"> Country: </span> <input lang="en" id="Groupcountry" type="text" placeholder="Country" style="margin-left:30px"> </p>
									
									</p>	 
						
									<div style="margin-top:20px;margin-left:-10px">
										<input id="create_group" type="button" class="btn btn-primary" style="margin-left:270px;margin-top:-10px" value="Create Group">
									</div>
							 </div>
							<!-- OPEN A MODAL BUTTON FOR CREATE GROUP -->	
							<div style="display: table;width:100%;margin 0 auto;">	
								<a  id="create-group-btn" class="btn" style="text-align:center; padding:15px; width:85px; height:40px; color:#22aeff; margin-left:32px; margin-bottom: 15px;">
                    			<i class="icon-group icon-2x" style="margin:0 auto; padding:0px; color:#54bc00;"></i>
                    			<div style="width:100%;"></div>
                    			<span style="font-size:12px;" lang="en">Create Group</span>
                				</a>
							</div>	
							 <div class="clearfix"></div>
							  <span class="label label-info" style="left:0px; margin-left:34px; margin-top:20px; font-size:15px; " lang="en">My Groups</span>
							  <div id="group_details" style="margin-top:20px;margin-left:35px"></div>
							 <div class="clearfix"></div>
				 
						   
					 </div>
					 <div class="clearfix"></div>
					 <div class="clearfix"></div>
					 <div class="clearfix"></div>
					 <hr>
					  <!--<input type="button" class="btn btn-large btn-primary" id="notif_update" value="Update" style="width:200px;">
						</form>-->
							</div>	 
					</div>
					 
				 </div>
				 
				 <div class="tab-pane" id="telemed_config">
						<div class="grid" class="grid span4" style="width:98%; margin: 0 auto; margin-top:30px;">
							<div class="grid-content" style="padding-top:10px;">
								 <div style="background-color: #22AEFF; color: #FFF; padding: 12px; border-radius: 8px; margin: auto; text-align: center; font-size:30px; width: 94%;" lang="en">Telemedicine Configuration</div>
								    <div class="clearfix"></div>
									<div class="row-fluid"  style="width:100%">	
										<div class="grid" style="padding:10px; ">
                                            <style>
                                            .rate_button{
                                                 width: 175px; 
                                                 margin-left: 15px;
                                                 height: 30px;
                                                 text-align: center;
                                                 color: #FFFFFF;
                                                 border-style: solid; 
                                                 border-width: 1px; 
                                                 border-color: #3d94f6;
                                                 background-color: #3d94f6;
                                                 border-radius:4px;
                                            }
                                            </style>
											<div style="float:right" class="the-icons">
												<i class="icon-chevron-right" style="padding:10px 10px;float:right;margin-right:0px;cursor:pointer" id="next"></i>
												<label style="padding:10px;float:right;margin-right:0px;" id="CurrentPage"></label>
												<i class="icon-chevron-left" style="padding:10px ;float:right;margin-right:0px;cursor:pointer" id="prev"></i>
                                                <br/>
                                                <div style="width: 400px; height: 100px; float: right; margin-top: 50px; margin-bottom: -50px;">
                                                    
                                                    
                                                    <button class="rate_button" id="clear_week" style="width: 100px; float: right; background-color: #F66765; border-color: #F66765;" lang="en">
                                                        Clear Week
                                                      </button>
                                                    <button class="rate_button" id="copy_week" style="width: 100px; float: right;" lang="en">
                                                        Copy Week
                                                      </button>
                                                    <button class="rate_button" id="paste_week" style="width: 100px; float: right; display: none;" lang="en">
                                                        Paste Week
                                                      </button>
                                                </div>
											</div>
                                       <!--
                                            <div style="width: 500px; height: 30px; margin-left: 5px;">
                                    <?php    
                                        if ($CustomLook != "COL") { 
                                    ?>
                                                <label style="float: left; margin-top: 5px; margin-bottom: 5px;" lang="en">Hourly Rate: $</label>
                                                <div class="formRight" style="float: left;">
                                                   <input type="text" name="rate" id="rate" style="width:60px; margin-left: 10px;" <?php if($user->doctor_hourly_rate > 0){echo 'value="'.$user->doctor_hourly_rate.'"';} ?> placeholder="10">
                                                </div>
                                                <label style="float: left; margin-top: 5px; margin-bottom: 5px; margin-left: 10px;" lang="en"> / hour</label>
                                                
                                                <button class="rate_button" id="set_rate" style="float: left;">
                                                    Set Hourly Rate
                                                  </button>
                                    <?php } ?>
                                            </div>
                                            -->
                                            <div style="width: 500px; height: 54px; margin-bottom: -20px;">
                                                <style>
                                                .telemed_button{
                                                    width: 400px; 
                                                    margin-left: 5px;
                                                    height: 30px;
                                                    text-align: center;
                                                    color: #FFFFFF;
                                                    border-style: solid; 
                                                    border-width: 1px; 
                                                    border-color: #3d94f6; 
                                                    border-radius:4px;
                                                    margin-top: 5px;
													margin-bottom: 5px;
		
                                                }
                                                </style>
                                                <!--button class="telemed_button" id="start_telemed"  lang="en">
                                                    Offline
                                                </button-->
                                                <input id="start_telemed" type="checkbox">
                                                <div style="width: 400px; height: 30px; margin: 10px 0 10px 5px;">
                                                    <div id="consultation_status_label" style="width: 230px; height: 27px; padding-top: 3px; float: left;"  lang="en">
                                                        You are currently: 
                                                        <?php
                                                            echo '<span style="color: ';
                                                            if($in_consultation == 0)
                                                            {
                                                                echo '#54bc00';
                                                            }
                                                            else
                                                            {
                                                                echo '#D84840';
                                                            }
                                                            echo ';">';
                                                            if($in_consultation == 0)
                                                            {
                                                                echo 'Not in consultation';
                                                            }
                                                            else
                                                            {
                                                                echo 'In consultation';
                                                            }
                                                            echo '</span>';
                                                        ?>
                                                    </div>
                                                    <button id="reset_availability_button" style="width: 170px; height: 25px; background-color: #22AEFF; border: 0px solid #FFF; outline: none; border-radius: 5px; float: left; color: #FFF;" lang="en">
                                                        Reset Availability
                                                    </button>
                                                    
                                                </div>
                                                <span style="font-size: 14px; margin-left: 5px; margin-top: 2px;" lang="en">Select Timezone: <br/>
                                                    <select class="timezonepicker" id="timezone_picker" style="display:block; margin-top: 5px;">
													  <option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
													  <option value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
													  <option value="-10.0">(GMT -10:00) Hawaii</option>
													  <option value="-9.0">(GMT -9:00) Alaska</option>
													  <option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
													  <option value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
													  <option value="-6.0" selected>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
													  <option value="-5.0">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
													  <option value="-4.0">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
													  <option value="-3.5">(GMT -3:30) Newfoundland</option>
													  <option value="-3.0">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
													  <option value="-2.0">(GMT -2:00) Mid-Atlantic</option>
													  <option value="-1.0">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
													  <option value="0.0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
													  <option value="1.0">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
													  <option value="2.0">(GMT +2:00) Kaliningrad, South Africa</option>
													  <option value="3.0">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
													  <option value="3.5">(GMT +3:30) Tehran</option>
													  <option value="4.0">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
													  <option value="4.5">(GMT +4:30) Kabul</option>
													  <option value="5.0">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
													  <option value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
													  <option value="5.75">(GMT +5:45) Kathmandu</option>
													  <option value="6.0">(GMT +6:00) Almaty, Dhaka, Colombo</option>
													  <option value="7.0">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
													  <option value="8.0">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
													  <option value="9.0">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
													  <option value="9.5">(GMT +9:30) Adelaide, Darwin</option>
													  <option value="10.0">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
													  <option value="11.0">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
													  <option value="12.0">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
													</select>
                                                </span>
                                                <style>
                                                    select.timezonepicker {
                                                       width: 400px;
                                                       padding: 3px;
                                                       font-size: 14px;
                                                       color: #B3B3B3;
                                                       line-height: 1;
                                                       border: 1;
                                                       border-radius: 4;
                                                       height: 24px;
                                                        background: url(images/dropdown_arrow.png) no-repeat right #FFF;
                                                        -webkit-appearance: none;
                                                        margin-left: 5px;
                                                        margin-right: 15px;
                                                    }
                                                </style>
                                            </div>
                                            
											     <div class="grid" style="padding:10px; margin-top:100px" id = "AppointmentContainer">
												    
											 </div>
										</div>
									</div>
									
									
									
					 				  
							</div>	 
					    </div>
					 
				 </div>
				 <!-- BEGIN TELEMED ACTIVITY CONSOLE-->
				 <link rel="stylesheet" href="css/bootstrap-responsive.css">
                 <link rel='stylesheet' href='css/bootstrap-switch.min.css'>
				 <link rel="stylesheet" href="css/toggle-switch.css">
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
                <?php if($user->doctor_privilege != 3) { ?>
				<div class='tab-pane' id='financials_config'>
					<div class="grid" style="width:98%; margin: 0 auto; margin-top:30px;">
                        <div class="grid-content" style="padding-top:10px; overflow: hidden;">
                            <iframe id="finanza" width='100%' height='1100px' scrolling='no' src="../../../../MODULES/Financial_Console/chartClassUnit.php" style="border: none;">
                            
                            </iframe>
                        </div>
					</div>
				</div>
                <div class='tab-pane' id='probe_config'>
                    <style>
                    .probes_row{
                        width: 96%;
                        height: 44px;
                        padding-top: 4px;
                        padding-bottom: 2px;
                        padding-left: 1%;
                        padding-right: 1%;
                        background-color: #FAFAFA;
                        border: 1px solid #D8D8D8;
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
                        width: 30px;
                        height: 30px;
                        background-color: #54bc00;
                        color: #FFF;
                        border-radius: 30px;
                        outline: none;
                        border: 0px solid #FFF;
                        
                    }
                    .probes_delete_button{
                        width: 30px;
                        height: 30px;
                        background-color: #D84840;
                        color: #FFF;
                        border-radius: 30px;
                        outline: none;
                        border: 0px solid #FFF;
                        
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
					<div class="grid" style="width:98%; margin: 0 auto; margin-top:30px;">
                        <div class="grid-content" style="padding-top:10px; overflow: hidden;">
                            <div id="probes_container" style="width: 100%; height: 400px; overflow-y: auto;">
                            
                            </div>
                            <div style="height: 80px; width: 180px; margin: auto;">
                                <button id="new_probe_protocol_button" data-on="0" style="height: 80px; width: 180px; background-color: #22AEFF; color: #FFF; border: 0px solid #FFF; border-radius: 5px; outline: none;">
                                    <i class="icon-cogs" style="font-size: 45px;"></i><br/>Create New Probe
                                </button>
                            </div>
                        </div>
					</div>
				</div>




                <!--div class='tab-pane' id='professional_info'>
                     <link rel="stylesheet" href="css/doctor_style_mars.css">
                    <div class="grid" style="width:98%; margin:0 auto; margin-top:30px;">
                        <div class="grid-content" style="padding-top:10px;">
                            <div style="background-color: #22AEFF; color: #FFF; padding: 12px; border-radius: 8px; margin: auto; text-align: center; font-size:30px; width: 94%;" lang="en">Professional Information</div>
                            <div class="clearfix" style="margin-bottom:20px"></div>
                            <div class="grid" style="padding:10px;">
                                
                                
                                
                                
                                
                                
                                
                                
                                
                            </div>
                            
                        </div> 
                    </div>
                </div-->

                <?php } ?>
				
				 <div class='tab-pane' id='telemed_activity_config'>
				 <div class="content">
            <div class="grid" class="grid span4" style="width:950px; margin: 0 auto; margin-top:30px; padding-top:10px;">
                
                <!-- ADD STUFF HERE -->
				<div style="background-color: #22AEFF; color: #FFF; padding: 12px; border-radius: 8px; margin: auto; text-align: center; font-size:30px; width: 94%;" lang="en">Telemedicine History</div>
            
                <div style="width: 400px; height: 170px; float: right; margin-right: 40px; margin-top: 30px;">
                    <div style="width: 200px; height: 100px; float: left;">
                        <h4 style="color: #8A8A8A; text-align: center; font-size: 32px;" lang="en">Consultations</h4> <!-- Added Lang by Pallab-->
                        <p id="number_of_consultations" style="color: #22AEFF; font-size: 42px; font-weight: bold; text-align: center; margin-top: 25px;"></p>
                    </div>
                    <div style="width: 200px; height: 100px; float: left;">
						<h4 style="color: #8A8A8A; text-align: center; font-size: 32px;" lang="en">Users</h4> <!-- Added Lang by Pallab-->
                        <p id="number_of_users" style="color: #5EB529; font-size: 42px; font-weight: bold; text-align: center; margin-top: 25px;"></p>

                    </div>
                    <div style="width: 200px; height: 35px; margin-top: 25px; float: right; margin-right: 20px;">
                        <button id="toggle_1" class="segmented_control_selected" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;" lang="en">Actual</button>
                        <button id="toggle_2" class="segmented_control" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;" lang="en">Cumulative</button>
                    </div>
                </div>
                <div style="width: 500px; height: 100px; margin-left: 40px; margin-top: 30px;">

                    <div style="width: 500px; height: 50px;">
                        <label style="margin-right: 30px; float: left; margin-top: 5px;"><span lang="en">Period: </span></label> <!-- Added Lang by Pallab-->
                        <select id="period_select" style="float: left;">
                            <option value="1" selected lang="en">Today</option> <!-- Added Lang by Pallab-->
                            <option value="2">This Week</option> <!-- Added Lang by Pallab-->
                            <option value="3">This Month</option> <!-- Added Lang by Pallab-->
                            <option value="4">This Year</option> <!-- Added Lang by Pallab-->
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
                    <button class="consultations_button_selected" lang="en">Consultations</button> <!-- Added Lang by Pallab-->
                </div>
                <div style="width: 900px; height: 300px; margin-left: 40px; margin-top: 30px;">
                    <canvas id="day_chart" width="900" height="300"></canvas>
                    <canvas id="week_chart" width="900" height="300" style="display: none;"></canvas>
                    <canvas id="month_chart" width="900" height="300" style="display: none;"></canvas>
                    <canvas id="year_chart" width="900" height="300" style="display: none;"></canvas>
                </div>
                <div id="chart_loader" style="width: 52px; height: 42px; margin-left: auto; margin-right: auto; margin-top: -170px; margin-bottom: 208px; display: none;">
                    <img src="../../../../images/load/29.gif"  alt="">
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
                <div class="controls" style="float: right; width: 500px; margin-right: 50px; margin-top: 50px;">
                    <input lang="en" placeholder="name or surname or email" class="span7" id="search_bar" style="margin-left:50px;float: left; width: 370px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" size="16" type="text">
                    <button id="search_bar_clear_button" style="height: 18px; width: 18px; border-radius: 15px; border: 0px solid #FFF; outline: 0px; background-color: #E6E6E6; color: #FFF; font-size: 10px; padding: 0px; margin-top: 6px; margin-left: -105px; visibility: hidden;">X</button>
                    <button class="search_bar_button" style="float: left; width: 80px;" id="search_bar_button" lang="en">Search</button>
                </div>
                <div style="width: 400px; height: 35px; margin-left: 50px; margin-top: 80px;margin-right">
				</div>
                <div id="customers_table" style="width: 900px; margin: auto; margin-top: 15px;display:none;">
                    <table>
                        <tr>
                            <th style="background-color: #6ECCFF; width: 225px; height: 25px;" lang="en">
                                Name <!-- Added Lang by Pallab-->
                                <button id="caret_button_customers_Surname,Name" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
                                Calls <!-- Added Lang by Pallab-->
                                <button id="caret_button_customers_numberOfPhoneCalls" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 180px; height: 25px;">
                                Video Conferences
                                <button id="caret_button_customers_videoconferences" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;">
                                <span lang="en">Sign Up Date</span> <!-- Added Lang by Pallab-->
                                <button id="caret_button_customers_SignUpDate" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;"><span lang="en">Plan </span>
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
                <div id="consultations_table" style="width: 900px; margin: auto; margin-top: -15px; display: none;">
                    <table>
                        <tr>
                            <th style="background-color: #22AEFF; width: 155px; height: 25px;" lang="en">
                                Doctor <!-- Added Lang by Pallab-->
                                <button id="caret_button_consultations_doctorSurname,doctorName" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 155px; height: 25px;" lang="en">
                                Patient <!-- Added Lang by Pallab-->
                                <button id="caret_button_consultations_patientSurname,patientName" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 180px; height: 25px;" lang="en">
                                Time
                                <button id="caret_button_consultations_DateTime" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 50px; height: 25px;" lang="en">
                                Type
                                <button id="caret_button_consultations_Type" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 50px; height: 25px;" lang="en">
                                Length
                                <button id="caret_button_consultations_Length" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 100px; height: 25px;" lang="en">
                                Status
                                <button id="caret_button_consultations_Status" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 70px; height: 25px;" lang="en">
                                Notes
                                <!--<button id="caret_button_consultations_Data_File" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px; "></button>-->
                            </th>
                            <th style="background-color: #22AEFF; width: 70px; height: 25px;" lang="en">
                                Summ.
                                <!--<button id="caret_button_consultations_Summary_PDF" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>-->
                            </th>
                            <th style="background-color: #22AEFF; width: 70px; height: 25px;" lang="en">
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
                <div id="doctors_table" style="width: 1005px; margin: auto; margin-top: 15px; display: none;">
                    <table>
                        <tr>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
                                Name
                                <button id="caret_button_doctors_name" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
                                Calls
                                <button id="caret_button_doctors_calls" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 180px; height: 25px;">
                                Video Conferences
                                <button id="caret_button_doctors_cideoconferences" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
                                Patients
                                <button id="caret_button_doctors_numberOfConsultedPatients" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 150px; height: 25px;">
                                Summaries Edited
                                <button id="caret_button_doctors_summaries" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;"><span  lang="en">PDFs Created</span>
                                
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
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
                                Name
                                <button id="caret_button_newusers_name" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
                                Phone
                                <button id="caret_button_newusers2_telefono" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 180px; height: 25px;">
                                Video Conferences
                                <button id="caret_button_doctors_cideoconferences" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;" lang="en">
                                Email
                                <button id="caret_button_doctorsnewusers3_email" class="icon-caret-down" style="margin-left: -2px; border: 0px solid #FFF; background-color: inherit; outline: 0px; padding: 0px;"></button>
                            </th>
                            <!--<th style="background-color: #22AEFF; width: 150px; height: 25px;">
                                Summaries Edited
                                <button id="caret_button_doctors_summaries" class="icon-caret-down" style="margin-left: 7px; border: 0px solid #FFF; background-color: inherit; outline: 0px;"></button>
                            </th>-->
                            <th style="background-color: #22AEFF; width: 225px; height: 25px;"><span lang="en">Creation Date</span>
                                
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
                    <img src="../../../../images/load/8.gif"  alt="">
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
                        height: 42px; <!-- Changed height from 30 to 42 -->
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
                
                </div>
            </div>
        </div>


	<script src="../../../../js/Chart.min.js"></script>
    <!--<script src="js/bootstrap.min.js"></script>-->
    <script src="../../../../js/bootstrap-popover.js"></script>
    <input type="hidden" id="scope_select" value="Global">
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
            var Day_Chart = new Chart(day_ctx).Line(day_chart_data, {bezierCurve: false});
            var week_ctx = $("#week_chart").get(0).getContext("2d");
            var Week_Chart = new Chart(week_ctx).Line(week_chart_data, {bezierCurve: false});
            var month_ctx = $("#month_chart").get(0).getContext("2d");
            var Month_Chart = new Chart(month_ctx).Line(month_chart_data, {bezierCurve: false});
            var year_ctx = $("#year_chart").get(0).getContext("2d");
            var Year_Chart = new Chart(year_ctx).Line(year_chart_data, {bezierCurve: false});
            var user_values = [{}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}];
            var current_table = 1;
            var current_column = 'patientSurname,patientName';
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
                var medid = $('#MEDID').val();
                for(var i = 0; i < 31; i++)
                {
                    user_values[i] = {};
                }
                $("#day_chart").css("display", "none");
                $("#week_chart").css("display", "none");
                $("#month_chart").css("display", "none");
                $("#year_chart").css("display", "none");
                $("#chart_loader").css("display", "block");
                $.post("../../../ajax/getConsultationsDataConfig.php", {period: period, medid: medid}, function(data, status)
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
			 
            
            function load_table(table_holder,sort, ascending, page, scope, period, search)
            {
				var table = 2;
                var back_end_file = "";
                if(table == 1)
                {
                    back_end_file= "getCustomerData2";
                }
                else if(table == 2)
                {
                    back_end_file = "getConsultationsDataTable";
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
                var medid = $('#MEDID').val();
                $.post('../../../ajax/'+back_end_file+".php", {medid: medid, period: period, sortOrder: sort_order, sortField: sort, searchField: search, currentPage: page}, function(data, status)
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
                    var table = 2;
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
                            var date_holder = info[i]['DateTime'];
                            var new_date_holder = new Date(date_holder);
                            var doctor_timezone = '<?php echo $user->doctor_timezone ?>';
                            doctor_timezone = parseInt(doctor_timezone.substring(0,3));
                            //console.log(doctor_timezone);
                            var tzDifference = doctor_timezone * 60 + new_date_holder.getTimezoneOffset();
                            var offsetTime = new Date(new_date_holder.getTime() + doctor_timezone * 60 * 60 * 1000);
                            html += '<tr><td style="background-color: '+color+'; width: 130px; height: 15px; text-align: center;">';
                            html += info[i]['doctorName'] + ' ' + info[i]['doctorSurname'];
                            html += '</td><td style="background-color: '+color+'; width: 130px; height: 15px; text-align: center;">';
                            html += '<a href="patientdetailMED-new.php?IdUsu='+info[i]['pat_id']+'" style="color: #5EB529">'+info[i]['patientName'] + ' ' + info[i]['patientSurname']+'</a>';
                            html += '</td><td style="background-color: '+color+'; width: 305px; height: 15px; text-align: center;">';
                            html += '<span style="font-size:75%;">'+offsetTime+'</span>';
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
								//http.send();
							return http.status!=404;
							}
							
							var resultxyz = doesFileExist('<?php echo $hardcode ?>recordings/'+info[i]['Recorded_File']);
 
							if (resultxyz == true) {
							html += '<a class="recording_link" href="'+info[i]['Recorded_File']+'">Show</a>';
							} else {
							html += 'N/A';
							}
							
                            html += '</td></tr>';
                        }
                        console.log(html);
                        $("#consultations_table").children('table').eq(0).append(html);
                        $("#consultations_table").css('display', 'block');
                        $(".recording_link").on('click', function(e)
                        {
                            e.preventDefault();
                            $.post('../../../ajax/pdmst_dashboard_decrypt.php', {file: $(this).attr("href"), recording: true}, function(data, status)
                            {
                                console.log(data);
                                $("#recordingModal").html('<iframe src="'+/*'http://com.twilio.music.classical.s3.amazonaws.com/BusyStrings.mp3'*/data+'" style="width:400px;height:400px;"></iframe>');
                                $("#recordingModal").dialog({bigframe: true, width: 406, height: 440, modal: true});
                                $.post('../../../ajax/pdmst_dashboard_decrypt.php', {erase: true, erase_file: data}, function(data, status){console.log(data);});
                            });
                        });
                        $("#recordingModal").on( "dialogclose", function( event, ui ) {$("#recordingModal").html("");} );
                        $(".summary_link").on('click', function(e)
                        {
                            e.preventDefault();
                            $("#summaryModal").dialog({bgiframe: true, width: 680, height: 800, modal: true});
                            $("#summaryModal").html('<div style="width: 100%; text-align: center; margin-top: 200px;"><img src="../../../../images/load/29.gif"><br/><br/><br/>Decrypting File, Please wait...</div>');
                            $.post('../../../ajax/pdmst_dashboard_decrypt.php', {file: $(this).attr("href")}, function(data, status)
                            {
                                console.log(data);
                                $("#summaryModal").html('<iframe src="'+data+'" style="width:680px;height:800px;"></iframe>');
                                
                                $.post('../../../ajax/pdmst_dashboard_decrypt.php', {erase: true, erase_file: data}, function(data, status){console.log(data);});
                            });
                        });
                        $(".notes_link").on('click', function(e)
                        {
                            e.preventDefault();
                            $.post('../../../ajax/pdmst_dashboard_decrypt.php', {file: $(this).attr("href")}, function(data, status)
                            {
                                console.log(data);
                                $("#notesModal").html('<iframe src="'+data+'" style="width:600px;height:800px;"></iframe>');
                                $("#notesModal").dialog({bigframe: true, width: 605, height: 400, modal: true});
                                $.post('../../../ajax/pdmst_dashboard_decrypt.php', {erase: true, erase_file: data}, function(data, status){console.log(data);});

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
                $.post("../../../ajax/getAllDoctorsTimeslots.php", {period: $("#period_select_medical_availability").val(), scope: $("#scope_select").val()}, function(data, status)
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
                        
                        //Start of new code by Pallab for dynamically translating the month to Spanish based on language set
                        if(langType == 'th')
                        {
                        
                         var lang = {"Januray":"Enero","February":"Febrero","March":"Marzo","April":"Abril","May":"Mayo","June":"Junio","July":"Julio","August":"Agosto","September":"Septiembre","October":"Octubre","November":"Noviembre","December":"Diciembre"};
                         var month = months[today.getMonth()];
                         $("#availability_label").text(today.getDate()+", "+lang[month]+", "+today.getFullYear());
                         
                        }
                        
                        //End of new code by Pallab. Below availability_label put under else due to the above introduced if block which is when the default language is English
                        
                        else
                        {
                            $("#availability_label").text(months[today.getMonth()]+" "+today.getDate()+", "+today.getFullYear());
                        }
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
                    
                    //Below line commented by Pallab for lang pack translation not working on Aggregated as it is statically added
                   // pre_html += '<strong>Aggregated</strong>';
                    //Start of new piece of code by Pallab
                    
                    if(langType == 'th')
                        {
                            pre_html += '<strong>Agrupados</strong>';
                        }
                    else
                        {
                            pre_html += '<strong>Aggregated</strong>';
                            
                        }
                    //End of new piece of code by Pallab
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
                $.post("../../../ajax/pdmst_search_users.php", {search: search_query, type: type, scope: $("#scope_select").val()}, function(data, status)
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
                
                
                $.post("../../../ajax/start_telemed_phonecall.php", {pat_phone: selected_user_number, doc_phone: selected_doctor_number, doc_id: selected_doctor, pat_id: selected_user, doc_name: $("#selected_doctor_label").text(), pat_name: $("#selected_user_label").text(), override: true}, function(data, status)
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
                            
                            $.get("../../../ajax/get_call_status.php?sid="+latest_sid, function(data, status)
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
                load_table(current_table, current_column, ascending, current_page, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
            });
            $("#scope_select").on('change', function()
            {
                get_main_data($(this).val(), parseInt($("#period_select").val()));
                get_doctors_availability();
                current_page = 1;
                load_table(current_table, current_column, ascending, current_page, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
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
                load_table(current_table, current_column, ascending, current_page, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
            });
            $("#search_bar_button").on('click', function()
            {
				current_table = 2;
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
                load_table(current_table, current_column, ascending, current_page, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
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
                    load_table(current_table, current_column, ascending, 1, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
                        
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
                load_table(current_table, current_column, ascending, 1, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
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
                load_table(current_table, current_column, ascending, current_page, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
                
            });
            $("#page_button_right").on('click', function()
            {
                current_page += 1;
                load_table(current_table, current_column, ascending, current_page, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
            });
            get_main_data("Global", 1); 
            load_table(current_table, current_column, ascending, current_page, "Global", parseInt($("#period_select").val()), $("#search_bar").val());
         
        $('option').attr('lang','en');
        
    });
    </script>
				 <!-- END TELEMED ACTIVITY CONSOLE-->


                <div class="tab-pane" id="appointments_config">
						<div class="grid" class="grid span4" style="width:96%; margin: 0 auto; margin-top:30px;">
							<div class="grid-content" style="padding-top:10px; padding-left: 2%; overflow: scroll;">
								 <div style="background-color: #22AEFF; color: #FFF; padding: 12px; border-radius: 8px; margin: auto; text-align: center; font-size:30px; width: 94%;" lang="en">Upcoming Appointments</div>
                                <br/><br/>
                                <div id="appointments_container">
                                    <!--
                                    <h3 style="text-align: center; font-size: 20px;">Wednesday June 25, 2014</h3>
                                    <div style="text-align: center; margin-right: 10px; background-color: #7EC1FF; border-top-left-radius: 8px; border-top-right-radius: 8px; color: #FFF; font-size: 16px; font-weight: bold; padding: 5px;">8:00 AM - 10:00 AM</div>
                                    <div style="text-align: left; margin-right: 10px; background-color: #F3F3F3; color: #000; font-size: 14px; padding: 4px; padding-left: 10px;"><button style="width: 18px; height: 18px; float: right; color: #F00; padding: 0px; background-color: inherit; border: 0px solid #FFF; border-radius: 3px; outline: 0px;"><i class="icon-remove" style="width: 12px; height: 12px;"></i></button>Jane Doesix</div>
                                    <div style="text-align: left; margin-right: 10px; background-color: #DADADA; color: #000; font-size: 14px; padding: 4px; padding-left: 10px;"><button style="width: 18px; height: 18px; float: right; color: #F00; padding: 0px; background-color: inherit; border: 0px solid #FFF; border-radius: 3px; outline: 0px;"><i class="icon-remove" style="width: 12px; height: 12px;"></i></button>John Doe</div>
                                    <div style="text-align: center; margin-right: 10px; background-color: #7EC1FF; color: #FFF; font-size: 16px; font-weight: bold; padding: 5px;">2:00 PM - 4:00 PM</div>
                                    <div style="text-align: left; margin-right: 10px; background-color: #F3F3F3; color: #000; font-size: 14px; padding: 4px; padding-left: 10px;"><button style="width: 18px; height: 18px; float: right; color: #F00; padding: 0px; background-color: inherit; border: 0px solid #FFF; border-radius: 3px; outline: 0px;"><i class="icon-remove" style="width: 12px; height: 12px;"></i></button>Jane Doesix</div>
                                    <div style="text-align: left; margin-right: 10px; background-color: #DADADA; color: #000; font-size: 14px; padding: 4px; padding-left: 10px;"><button style="width: 18px; height: 18px; float: right; color: #F00; padding: 0px; background-color: inherit; border: 0px solid #FFF; border-radius: 3px; outline: 0px;"><i class="icon-remove" style="width: 12px; height: 12px;"></i></button>John Doe</div>
                                    <div style="text-align: left; margin-right: 10px; background-color: #F3F3F3; color: #000; font-size: 14px; padding: 4px; padding-left: 10px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;"><button style="width: 18px; height: 18px; float: right; color: #F00; padding: 0px; background-color: inherit; border: 0px solid #FFF; border-radius: 3px; outline: 0px;"><i class="icon-remove" style="width: 12px; height: 12px;"></i></button>Jane Doesix</div>
                                    -->

                                </div>

									
					 				  
							</div>	 
					    </div>
					 
				 </div>
                <br />
                  <?=$user->footer_copy;?>  


			</div>
            
		</div>
	</div>



	
	</div>
	
	</div>
	
	</div>
    <!--Content END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../../../../js/jquery.min.js"></script>
    <script src="../../../../js/jquery-ui.min.js"></script>
    <script src="../../../../js/jquery.easing.1.3.js"></script>
   
    <script src="../../../../js/bootstrap.min.js"></script>
    <script src="../../../../js/bootstrap-datepicker.js"></script>
    <script src="../../../../js/bootstrap-colorpicker.js"></script>
    <script src="../../../../js/google-code-prettify/prettify.js"></script>
   <script src="../../../../js/timezones.js" type="text/javascript"></script>
    <script src="../../../../js/jquery.timepicker.js"></script>   
    <script src="../../../../js/jquery.flot.min.js"></script>
    <script src="../../../../js/jquery.flot.pie.js"></script>
    <script src="../../../../js/jquery.flot.orderBars.js"></script>
    <script src="../../../../js/jquery.flot.resize.js"></script>
    <script src="../../../../js/graphtable.js"></script>
    <script src="../../../../js/fullcalendar.min.js"></script>
    <script src="../../../../js/chosen.jquery.min.js"></script>
    <script src="../../../../js/autoresize.jquery.min.js"></script>
    <script src="../../../../js/jquery.tagsinput.min.js"></script>
    <script src="../../../../js/jquery.autotab.js"></script>
    <script src="../../../../js/elfinder/js/elfinder.min.js" charset="utf-8"></script>
	<script src="../../../../js/tiny_mce/tiny_mce.js"></script>
    <script src="../../../../js/validation/languages/jquery.validationEngine-en.js" charset="utf-8"></script>
	<script src="../../../../js/validation/jquery.validationEngine.js" charset="utf-8"></script>
    <script src="../../../../js/jquery.jgrowl_minimized.js"></script>
    <script src="../../../../js/jquery.dataTables.min.js"></script>
    <script src="../../../../js/jquery.mousewheel.js"></script>
    <script src="../../../../js/jquery.jscrollpane.min.js"></script>
    <script src="../../../../js/jquery.stepy.min.js"></script>
    <script src="../../../../js/jquery.validate.min.js"></script>
    <script src="../../../../js/raphael.2.1.0.min.js"></script>
    <script src="../../../../js/justgage.1.0.1.min.js"></script>
	<script src="../../../../js/glisse.js"></script>
    <script src="../../../../js/bootstrap-tooltip.js"></script>
    <script type="text/javascript" src="../../../../js/H2MRange2.js"></script>
    <!--script src="js/iframeResizer.min.js"></script-->
    
	<script src="../../../../js/application.js"></script>
   <script src="../../../../realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
    <link href="../../../../realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
    <!--<script src="realtime-notifications/pusher.min.js"></script>
    <script src="realtime-notifications/PusherNotifier.js"></script>-->
    <script src="../../../../js/socket.io-1.3.5.js"></script>
    <script src="../../../../push/push_client.js"></script>
	
	<link rel="stylesheet" media="screen" type="text/css" href="../../../../css/colorpicker2.css" />
	<link rel="stylesheet" type="text/css" href="../../../../css/sweet-alert.css">
    
    <link rel="stylesheet" href="../../../../css/jquery-ui-1.8.16.custom.css" media="screen"  />
	<script type="text/javascript" src="../../../../js/colorpicker.js"></script>
	<script type="text/javascript" src="../../../../jscolor/jscolor.js"></script>
    <script type="text/javascript" src="../../../../js/uploadify/jquery.uploadify.min.js"></script>

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script src="../../../../build/js/intlTelInput.js"></script>
    <script src="../../../../js/isValidNumber.js"></script>	
    <script src="../../../../js/bootstrap-switch.min.js"></script>
	
    <script type="text/javascript" src="../js/configuration.js"></script>
	<script src="../../../../js/sweet-alert.min.js"></script>

  </body>
</html>

