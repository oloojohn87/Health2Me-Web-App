<?php
include_once("../userDashboardClass.php");
$user = new userDashboardClass();
$user->pageLinks('UserDashboard.php');
$user->checkForSlatedProbe();
?>

    <!-- Le styles -->
    <script type="text/javascript" src="../../../../js/42b6r0yr5470"></script>
	

	
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

</script>

<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//v2.zopim.com/?2MtPbkSnwlPlIVIVYQMfPNXnx6bGJ0Rj';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->  
    
    
  </head>

  <body>


	
	<?php if($user->member_password_hash == null){
			echo "
			<div id='no-user-password-window' style='display:none;overflow:hidden;'>
			<center><span style='color: #3D93E0;'>We have detected that your account is missing some credentials.  </span></br><span style='color: #3D93E0;'>Please update them now.</span></center></br>
				<center><input id='missing-email' type='text' placeholder='Enter Email' value='".$user->member_email."' /></center></br>
				<center><input id='missing-phone' type='text' placeholder='Enter Phone' value='".$user->member_phone."' /></center></br>
				<center><input id='missing-password1' type='password' placeholder='Enter Password' /></center></br>
				<center><input id='missing-password2' type='password' placeholder='Repeat Password' /></center></br>
				<button onclick='update_missing_credentials();' style='float:right;' class='btn btn-success btn-large'>Update</button>
			</div>
	
			<script type='text/javascript'>
				var update_user_details = $('#no-user-password-window').dialog({bgiframe: true, width: 600, height: 400, autoOpen: false, modal: true,title:'Update credentials for member ".$user->member_first_name.' '.$user->member_last_name."'});
				setTimeout(function(){
					update_user_details.dialog('open');
				}, 3000);
				function update_missing_credentials(){
					var email = $('#missing-email').val();
					var phone = $('#missing-phone').val();
					var pass1 = $('#missing-password1').val();
					var pass2 = $('#missing-password2').val();
					var user_id = $('#USERID').val();
					var error_displayed = 0;
					
					if(email == '' && error_displayed == 0){
						swal('Please Enter Your Email', 'We noticed you did not specify an email.  Please do so to continue.', 'error');
						error_displayed = 1;
					}
					if(phone == '' && error_displayed == 0){
						swal('Please Enter Your Phone', 'We noticed you did not specify an phone.  Please do so to continue.', 'error');
						error_displayed = 1;
					}
					if(pass1 == '' && error_displayed == 0){
						swal('Please Enter Your Password', 'We noticed you did not specify a new password.  Please do so to continue.', 'error');
						error_displayed = 1;
					}
					if(pass2 == '' && error_displayed == 0){
						swal('Please Repeat Your Password', 'We noticed you did not repeat your password.  Please do so to continue.', 'error');
						error_displayed = 1;
					}
					if(pass1 != pass2 && error_displayed == 0){
						swal('Password Mismatch!', 'We noticed your passwords did not match.  Please try again with matching passwords.', 'error');
						error_displayed = 1;
					}
					
					if(email != '' && error_displayed == 0){
						$.post('checkDupe.php?type=email', {user_id: user_id, email: email}, function(data, status)
						{
						console.log(data);
							if(data == 1){
								swal('Duplicate Email Detected!', 'We have detected an account with that email already.  Please log into that account with that email or try another email.', 'error');
								error_displayed = 1;
							}
						});
					}
					
					if(phone != '' && error_displayed == 0){
						$.post('checkDupe.php?type=phone', {user_id: user_id, phone: phone}, function(data, status)
						{
							if(data == 1){
								swal('Duplicate Phone Detected!', 'We have detected an account with that phone already.  If you do not already have an account.  Please contact the site administrator.', 'error');
								error_displayed = 1;
							}
						});
					}
					
					if(pass1 == pass2 && error_displayed == 0){
						$.post('checkDupe.php?update=credentials', {user_id: user_id, phone: phone, email: email, pass1: pass1, pass2: pass2}, function(data, status)
						{
						console.log(data);
							if(data == 1){
								swal('Credentials Updated!', 'We have updated your credentials.  You can now login using your new password.', 'success');
								update_user_details.dialog('close');
							}else{
								swal('Something Went Wrong!', 'Something went wrong while updating your user credentials.  If this problem persists please contact a site administrator.', 'error');
							}
						});
					}
					
				}
			</script>";
			
		}
	?>

    <!-- MODAL VIEW FOR VIDEO TELEMEDICINE -->
    <script src="../../../../js/SimpleWebRTC/latest.js"></script> 
    <script src="../../../../js/SimpleWebRTC/RecordRTC.js"></script> 
    <div id="videoTelemed" title="Telemedicine" style="display:none; text-align:center; width: 100%; height: 100%;">
        
        <div style="width: 100%; height: 85%; margin-top: 7px;">
            <div id="remoteVideo" style="background-color: #393939; width: 100%; height: 100%; border-radius: 6px;">
                
                
            </div>
            <div style="float:left; margin-left: 20px; margin-top: -68px;"><video id="localVideo" width="70" style="mask-image: url(small_video_mask.png);" autoplay></video></div>
            
            <style>
            .status{
                    width: 616px;
                    height: 56px;
                    background-color: #5A5A5A;
                    border-radius: 10px;
                    padding: 12px;
                    margin-top: 10px;
                }
            .status p{
                    color: #FFFFFF;
                    font-size: 18px;
                    text-align: center;
                    margin-top: 10px;
                    margin-bottom: -10px;
                    font-family: Arial, Helvetica, sans-serif;
                    width: 75%;
                    margin-right: auto;
                    margin-left: auto;
                }
            </style>
        </div>
    </div>
    <!-- END MODAL VIEW FOR VIDEO TELEMEDICINE -->
      
    <!-- MODAL VIEW FOR DOCTOR DIRECTORY -->
    <!-- STYLES FOR THIS MODAL WINDOW ARE IN css/doctor_styles.css -->
    <!-- JAVSCRIPT CODE FOR THIS MODAL WINDOW IS IN js/doctor_search.js -->
    <!--<div id="notification_bar" style="position: fixed;text-align:center;width: 100%; height: 44px; color: white; z-index: 2; background-color: rgb(119, 190, 247);"><div id="notification_bar_msg" style="display:inline-block;margin-right:20px;height:40px;vertical-align: middle;">We use cookies to improve your experience. By your continued use of this site you accept such use. </div>
       <div id="notification_bar_close" style="display:inline-block;margin-top: 2px;margin-left:40px">
           <i class="icon-remove-circle icon-3x"></i>
   </div></div>-->
    <div id="notification_bar"></div>
    <div id="personal_doctor_share_modal" title="Share Reports With Doctor" style="display:none; text-align:center; position: relative;">
        <div id="personalDoctorShareContainer" style="width: 100%; margin: auto; height: 300px; overflow-y:hidden;text-align: center; margin-bottom: 10px; position: relative;"></div>
        
        <div style="height: 30px; width: 480px; margin: auto;">
            <button id="personal_doctor_set_button" style="width: 150px; height: 30px; border: 0px solid #FFF; outline: none; border-radius: 5px; background-color: #22AEFF; color: #FFF; float: left;">
                Share Selected
            </button>
            <button id="personal_doctor_select_all_button" style="width: 150px; height: 30px; border: 0px solid #FFF; margin-left: 15px; margin-right: -15px; outline: none; border-radius: 5px; background-color: #22AEFF; color: #FFF; float: left;">
                Select All
            </button>
            <button id="personal_doctor_reset_button" style="width: 150px; height: 30px; border: 0px solid #FFF; outline: none; border-radius: 5px; background-color: #D84840; color: #FFF; float: left; margin-left: 30px;">
                Remove All
            </button>
        </div>
    </div>
    <div id="personal_doctors_messages_modal" title="Doctor Messages" style="display: none;">
        <div style="width: 100%; height: 30px;">
            <!--<div class="input-append">-->
                <input class="span7" id="personal_doctor_message_search_bar" style="float: left; width: 85%; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" size="16" type="text">
                <button style="width: 15%; height: 30px; color: #666; float: left; border-top-right-radius: 5px; border-bottom-right-radius: 5px; outline: none; border: 1px solid #BBB; background-color: #F2F2F2;" id="personal_doctor_message_search_bar_button" lang="en">Search</button>
            <!--</div>-->
        </div>
        <div id="personal_doctor_messages_container" style="text-align: center;">
            <div style="width: 99%; height: 25px; padding-left: 1%; padding-top: 5px; background-color: #FBFBFB; margin-top: 10px; margin-bottom: 10px; border: 1px solid #AAA; border-radius: 5px; overflow: hidden;">
                <div style="width: 50%; height: 25px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><strong>Subject:</strong> RE: Testasdhfkasdhgkshdgksdhgkshdglkashdgksahdg</div>
                <div style="width: 40%; height: 25px; float: left;"><strong>Date:</strong> Oct 23, 2014 5:14 PM</div>
                <button style="float: right; background-color: transparent; border: 0px solid #FFF; margin-top: -3px; margin-right: 5px; outline: none;" class="personal_doctor_expand_message">
                    <i class="icon-caret-down"></i>
                </button>
                <div style="height: 125px; width: 100%; color: #555; font-size: 12px; overflow-y: auto;" >
                    This is a test message.

                    Hello!
                </div>
            </div>

            <div style="width: 100%; height: 30px; background-color: #FBFBFB; margin-top: 10px; margin-bottom: 10px; border: 1px solid #AAA; border-radius: 5px;">

            </div>
            <div style="width: 100%; height: 30px; background-color: #FBFBFB; margin-top: 10px; margin-bottom: 10px; border: 1px solid #AAA; border-radius: 5px;">

            </div>
        </div>
      
    </div>
    <div id="search_modal" title="Search Doctors" style="display:none; text-align:center; width: 900px; height: 700px; overflow: hidden;">
        <!-- Search Modal Nav Bar -->
        <div class="doctor_search_nav_bar">
            <button class="doctor_search_nav_button_selected" data-index="1">Directory</button>
            <button class="doctor_search_nav_button" data-index="2">My Doctors</button>
            <button class="doctor_search_nav_button" style="border-right: 0px solid #FFF;" data-index="3">Personal Doctor</button>
        </div>
        <div id="doctor_directory">
            <div id="search_doctor_toolbar" style="width: 100%; height: 30px; margin-left: 15px;">
                <div style="float: left; margin-bottom: -25px; margin-top: 10px;">
                    <button class="sort_button" id="name_button" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;" lang="en">
                       Name
                        <i class="icon-caret-up" style="margin-left: 3px;"></i>
                    </button>
                    <button class="sort_button" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;" id="rating_button" lang="en">
                       Rating
                        <i class="" style="margin-left: 3px;"></i>
                    </button>
                </div>
                <div style="width: 670px; float: left; margin-left: 15px; margin-top: 10px; margin-bottom: -10px;">
                    <div class="controls" style="float: left; width: 225px;">
                        <!--<div class="input-append">-->
                            <input class="span7" id="search_bar" style="float: left; width: 135px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" size="16" type="text">
                            <button class="search_bar_button" style="float: left;" id="search_bar_button" lang="en">Search</button>
                        <!--</div>-->
                    </div>
                    <div style="float: left; width:140px; margin-left: 10px;">
                        <div class="search_toggle"  style="width: 140px;" lang="en">
                            <button id="telemedicine_toggle_button" class="search_toggle_button" style="background-color: #22AEFF;" data-on="true" ></button>
                            Telemedicine
                        </div>
                    </div>
                    <div style="float: left; width:100px; margin-left: 20px;">
                        <div class="search_toggle" lang="en">
                            <button id="available_toggle_button" class="search_toggle_button" data-on="false" ></button>
                            Available
                        </div>
                    </div>
                    <div style="float: left; width:100px;margin-left:20px;">
						<div class="search_toggle" lang="en">
							<button id="filter-catapult" class="search_toggle_button" data-on="false" ></button>
							Catapult
						</div>
					</div>
                    <div style="float: left; width:30px; margin-left: 20px; font-size: 20px;">
                        <button id="advanced_toggle_button" class="doctor_search_advanced_toggle_button" >
                            <i class="icon-cog" ></i>
                        </button>
                    </div>
                </div>

            </div>
            <div id="doctor_search_advanced" style="width: 100%; margin-top: 50px; display: none;">
                <div style="width: 360px; height: 50px; margin: auto;">

                    <div style="width: 100%; height; 25px; padding-top: 5px; color: #767676; text-align: center;">
                        Speciality: 
                    </div>
                    <select name="search_speciality" id="search_speciality" style="float: left; width: 360px;">
                        <option value="Any" selected>Any</option>
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
                        <option value="General Practice">General Practicioner</option>
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
                <div style="width: 360px; margin: auto; height: 50px; margin-top: 25px;">

                    <div style="width: 100%; height; 25px; padding-top: 5px; color: #767676; text-align: center;" lang="en">
                        Country: 
                    </div>
                    <select id="country_search" name ="country_search" style="width: 360px;"></select>
                </div>
                <div style="width: 360px; margin: auto; height: 50px; margin-top: 25px;">

                    <div style="width: 100%; height; 25px; padding-top: 5px; margin-left: 15px; color: #767676; text-align: center;" lang="en">
                        Region: 
                    </div>
                    <select id="region_search" name ="region_search" style="width: 360px;"></select>
                </div>
                <div style="width: 330px; margin: auto; margin-top: 30px;">
                    <button class="doctor_search_advanced_button" lang="en">Search</button>
                    <button class="doctor_search_advanced_button" style="margin-left: 15px;" lang="en">Reset</button>
                    <button class="doctor_search_advanced_button" style="margin-left: 15px;" lang="en">Back</button>
                </div>
                <script type= "text/javascript" src = "../../../../js/countries.js"></script>
                <script language="javascript">
                    populateCountries("country_search", "region_search");
                </script>
            </div>
            <div id="doctor_rows" style="width: 100%; margin-top: 30px; height: 510px;">
                <!--<div class="doctor_row">
                    <img class="doctor_pic" src="identicon.php?size=25&hash='<?php echo md5( strtolower( trim( 'blima@inmers.us' ) ) ); ?>'" />
                    <div class="doctor_main_label">
                        <div class="doctor_name"><span style="color: #22AEFF">Bruno</span> <span style="color: #00639A">Lima</span></div>
                        <div class="doctor_speciality">General Practicioner</div>
                        <div class="doctor_location">Texas, USA</div>
                    </div>
                    <div class="doctor_hospital_info">
                        <div class="doctor_stars">
                            <i class="icon-star" style="float: left; font-size: 12px; color: #666;"></i>
                            <i class="icon-star" style="float: left; font-size: 12px; color: #666;"></i>
                            <i class="icon-star" style="float: left; font-size: 12px; color: #666;"></i>
                            <i class="icon-star" style="float: left; font-size: 12px; color: #666;"></i>
                            <i class="icon-star-half" style="float: left; font-size: 12px; color: #666;"></i>
                        </div>
                        <div class="doctor_hospital_name">
                            Baylor Medical Center
                        </div>
                        <div class="doctor_hospital_address">
                            1000 Super Rd. Dallas, TX 75001
                        </div>
                    </div>
                </div>
                <div class="doctor_row">

                </div>
                <div class="doctor_row">

                </div>
                <div class="doctor_row">

                </div>
                <div class="doctor_row">

                </div>
                <div class="doctor_row">

                </div>
                <div class="doctor_row">

                </div>
                <div class="doctor_row">

                </div>
                <div class="doctor_row">

                </div>
                <div class="doctor_row">

                </div>
                -->
            </div>
            <button id="personal_doctors_cancel_change" style="float: right; background-color: #D84840; margin-top: 25px; margin-right: 20px; display: none;">Cancel</button>
            <div id="doctors_search_page_buttons" style="width: 80px; height: 50px; margin-top: 25px; margin-left: auto; margin-right: auto; display: none;">
                <button id="doctors_page_button_left" class="doctors_page_button" disabled>
                    <i class="icon-arrow-left"></i>
                </button>
                <button id="doctors_page_button_right" class="doctors_page_button" style="margin-left: 20px" disabled>
                    <i class="icon-arrow-right"></i>
                </button>
            </div>
        </div>
        
        <div id="my_doctors" style="display: none;">
            <br/>
            <div id="my_doctors_page_1">
                <button class="my_doctors_add_button">+</button>
                
                <div id="my_doctors_container" style="height: 595px; overflow-y: scroll;">
                    
                </div>
                
                
            </div>
            <style>
                
            </style>
            <div id="my_doctors_page_2" style="display: none;">
                
                <div style="width: 100%; height: 150px;">
                    <span class="my_doctors_page_2_label">Doctor's Name</span>
                    <div style="width: 100%">
                        <input type="text" placeholder="Name" id="my_doctors_name" style="border-top-left-radius: 5px; border-top-right-radius: 5px;" />
                        <input type="text" placeholder="Surname" id="my_doctors_surname" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; border-top: 0px solid #FFF;" />
                    </div>
                    <br/>
                    <span class="my_doctors_page_2_label">Doctor's Contact Information</span>
                    <div style="width: 100%">
                        <input type="text" placeholder="Email" id="my_doctors_email" style="border-top-left-radius: 5px; border-top-right-radius: 5px;" />
                        <input type="text" placeholder="Phone" id="my_doctors_phone" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; border-top: 0px solid #FFF;" />
                    </div>
                    <br/>
                    <span class="my_doctors_page_2_label">Doctor's Hospital</span>
                    <div style="width: 100%">
                        <input type="text" placeholder="Hospital Name" id="my_doctors_hospital_name" style="border-top-left-radius: 5px; border-top-right-radius: 5px;" />
                        <input type="text" placeholder="Street" id="my_doctors_hospital_street" style="border-top: 0px solid #FFF;" />
                        <input type="text" placeholder="City" id="my_doctors_hospital_city" style="border-top: 0px solid #FFF;" />
                        <input type="text" placeholder="State" id="my_doctors_hospital_state" style="border-top: 0px solid #FFF;" />
                        <input type="text" placeholder="Zip" id="my_doctors_hospital_zip" style="border-top: 0px solid #FFF;" />
                        <input type="text" placeholder="Country" id="my_doctors_hospital_country" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; border-top: 0px solid #FFF;" />
                    </div>
                    <br/>
                    <span class="my_doctors_page_2_label">Doctor's Speciality</span>
                    <div style="width: 100%">
                        <select id="my_doctors_speciality">
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
                            <option value="General Practice" selected>General Practicioner</option>
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
                    <div style="width: 70%; margin: auto; margin-top: 20px;">
                        <button id="my_doctors_done_button">Done</button>
                        <button id="my_doctors_cancel_button">Cancel</button>
                    </div>
                </div>
                
            </div>
        </div>
        
        <div id="personal_doctor" style="display: none;">
            <p>Personal Doctor</p>
            
            <div id="my_personal_doctor_cont" style="width: 100%; height: 160px; margin-bottom: 20px; margin-top: 20px;">
                <!--<div class="doctor_row" style="float: none; margin-bottom: 50px;">
                    <img class="doctor_pic" src="identicon.php?size=25&hash='<?php echo md5( strtolower( trim( 'blima@inmers.us' ) ) ); ?>'" />
                    <div class="doctor_main_label">
                        <div class="doctor_name"><span style="color: #22AEFF">Bruno</span> <span style="color: #00639A">Lima</span></div>
                        <div class="doctor_speciality">General Practicioner</div>
                        <div class="doctor_location">Texas, USA</div>
                    </div>
                    <div class="doctor_hospital_info">
                        <div class="doctor_stars">
                            <i class="icon-star" style="float: left; font-size: 12px; color: #666;"></i>
                            <i class="icon-star" style="float: left; font-size: 12px; color: #666;"></i>
                            <i class="icon-star" style="float: left; font-size: 12px; color: #666;"></i>
                            <i class="icon-star" style="float: left; font-size: 12px; color: #666;"></i>
                            <i class="icon-star-half" style="float: left; font-size: 12px; color: #666;"></i>
                        </div>
                        <div class="doctor_hospital_name">
                            Baylor Medical Center
                        </div>
                        <div class="doctor_hospital_address">
                            1000 Super Rd. Dallas, TX 75001
                        </div>
                    </div>
                </div>-->
            </div>
            <div style="width: 47%; height: 30px; margin: auto; margin-bottom: 20px; ">
                <button id="personal_doctor_share_button" style="width: 48%; height: 30px; border: 0px solid #FFF; outline: none; background-color: #22AEFF; color: #FFF; float: left; font-size: 18px; border-radius: 5px;"><i class="icon-share-alt"></i><span style="font-size: 12px;">&nbsp;&nbsp;&nbsp;Share With Doctor</span></button>
                <button id="personal_doctor_change_button" style="width: 48%; height: 30px; border: 0px solid #FFF; outline: none; background-color: #22AEFF; color: #FFF; float: left; margin-left: 4%; font-size: 18px; border-radius: 5px;"><i class="icon-gears"></i><span style="font-size: 12px;">&nbsp;&nbsp;&nbsp;Change Doctors</span></button>
                
                <div class="probe_cc_section" style="display:none;height:215px;">
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
								<div id='card-hider'>
								<div id="credit_cards_container2" style="width: 100%; margin-left: auto; margin-right: auto; height: 135px; overflow: scroll;">
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
									<input type="text" onkeypress="return isNumberKey(event)" id="credit_card_number2" maxLength="16" placeholder="Enter card number" style="width: 220px; height: 30px; float: left; border-radius: 5px;">
									<input id="credit_card_csv_code2" type="text" onkeypress="return isNumberKey(event)" id="csv_code" maxLength="3" placeholder="CSV" style="width: 85px; height: 30px; margin-left: 18px; float: left; border-radius: 5px;">
									<div style="color: #969696; width: 80px; float: left; text-align: left; padding-left: 5px; border-top-left-radius: 5px; border-bottom-left-radius: 5px; border: 1px solid #CACACA; height: 33px; padding-top: 5px; border-right: 0px solid #FFF;" lang="en">Exp. Date:</div>
									<input id="credit_card_exp_date2" type="month" style="width: 155px; height: 30px; float: left; font-size: 12px; border-radius: 0px; border-left: 0px solid #FFF; border-top-right-radius: 5px; border-bottom-right-radius: 5px;" />
									<button onclick="add_credit_card2();" id="add_card_button2" style="width: 100px; height: 30px; background-color: #52D859; border-radius: 0px; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px; margin-left: 18px; border-radius: 5px;" lang="en">Add Card</button>
									</div></div>
							<center><div id="probe-review-section" style="height:30px;display:none;background-color:#54BC00;border-radius:5px;margin-top:10px;width: 94%; margin: auto;padding: 2%;">
			<center><span id="review-cc-number"></span></center>
			<button onclick="change_cards();" class="btn btn-default" style="float:right;margin-top:-38px;border-radius:10px;">Change Card</button>
			<div style='margin-top:15px;'>Probe: <?php echo $user->slated_protocol_row['name'] ?></br>
				 Description: <?php echo $user->slated_protocol_row['description'] ?></br>
				 Time: <?php echo $user->member_slated_probe['desiredTime'] ?></br>
				 Timezone: <?php echo $user->slated_probe_timezone ?></br>
				 Method: <?php echo $user->slated_probe_type ?></br>
				 Interval: <?php echo $user->slated_probe_interval ?></br>
				 Doctor: <?php echo $user->slated_doctor_row['Name'].' '.$user->slated_doctor_row['Surname'] ?></br>
				 Price:  <?php echo $user->slated_probe_price ?></br>
				 Duration: <?php echo $user->slated_probe_months ?> Months</div>
			<button onclick='check_out_button();' class='btn btn-success' id='purchase-slated-probe-button' style='float:right;'>Purchase</button>
        </div></center>
				</div>
				
						
                <?php
					if($user->member_slated_probe_count > 0){
						echo '<button onclick="purchaseProbe();" id="purchase-slated-probes" style="width: 48%; height: 30px; border: 0px solid #FFF; outline: none; background-color: #22AEFF; color: #FFF; float: left; margin-left: 105px;margin-top:2%; font-size: 18px; border-radius: 5px;"><i class="icon-medkit"></i><span style="font-size: 12px;">&nbsp;&nbsp;&nbsp;Purchase Probe('.$user->member_slated_probe_count.')</span></button>';
					}
                ?>
            </div>
            <div style="width: 100px; height: 100px; margin: auto; margin-top: 50px; margin-bottom: 20px; position: relative;" id="personal_doctor_knob_graph">
            
            </div>
            <div style="width: 23%; height: 30px; margin: auto; margin-bottom: 20px;">
                <div style="background-color: #F00; color: #FFF; height: 18px; width: 18px; border-radius: 18px; float: right; margin-right: -5px; margin-top: -15px; position: relative; z-index: 3; font-size: 10px; visibility: hidden;" id="personal_doctor_messages_button_ballon">3</div>
                <button id="personal_doctor_messages_button" style="width: 100%; height: 30px; border: 0px solid #FFF; outline: none; background-color: #22AEFF; color: #FFF; float: left; font-size: 18px; border-radius: 5px; margin-top: -9px; position: relative; z-index: 2;">
                    <i class="icon-envelope"></i>
                    <span style="font-size: 12px;">&nbsp;&nbsp;Messages</span>
                </button>
            </div>
            <span id="personal_doctor_timeline_label" style="color: #333; text-align: center; font-size: 14px;">Timeline:</span>
            <div id="personal_doctor_timeline" style="width: 873px; height: 100px; position:relative; border:1px solid rgba(202, 202, 202, 0);">
                <!--<div style="width: 100%; height: 3px; background-color: #666; border-radius: 3px;"></div>   
                <div id="personal_doctor_timeline_container">
                </div>-->
            </div>
        </div>
    </div>
    <!-- END MODAL VIEW FOR DOCTOR DIRECTORY -->
      
      
    <!-- MODAL VIEW FOR SUMMARY -->
    <div id="summary_modal" lang="en" title="Summary" style="display:none; text-align:center; width: 1000px; height: 660px; overflow: hidden;">
    </div>
    <!-- END MODAL VIEW FOR SUMMARY -->
      
    <!-- MODAL VIEW FOR SETUP -->
    <div id="setup_modal" title="SetUp" style="display:none; text-align:center; padding:20px; width: 600px; height: 380px;">
        <div id="setup_modal_container" style="width: 600px; height: 350px;">
            <h4 id="setup_title" style="width: 85%; text-align: center; margin-left: 15%;" lang="en">Change Password</h4>
            <div style="width: 15%; height:250px; float: left; margin-top: -30px;">
                <button id="setup_menu_1" accesskey=""style="background-color: #22AEFF; color: #FFF; width: 50px; height: 50px; font-size: 24px; border: 0px solid #FFF; outline: 0px; border-top-left-radius: 10px; border-top-right-radius: 10px; float: left;">
                    <i class="icon-lock"></i>
                </button>
                <button id="setup_menu_2" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px; float: left;">
                    <i class="icon-time"></i>
                </button>
                <button id="setup_menu_3" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px; float: left;">
                    <i class="icon-credit-card"></i>
                </button>
                <button id="setup_menu_4" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px; float: left;">
                    <i class="icon-map-marker"></i>
                </button>
                <button id="setup_menu_5" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px;float: left;">
                    <i class="icon-phone"></i>
                </button>
                <button id="setup_menu_6" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;float: left;">
                    <i class="icon-key"></i>
                </button>
                 <!--<button id="setup_menu_7" style="background-color: #FBFBFB; color: #22AEFF; width: 50px; height: 50px; font-size: 24px; border: 1px solid #E6E6E6; outline: 0px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; float: left;">
                    <i class="icon-meh"></i>
                </button>-->
            </div>
            <div style="width: 85%; height: 200px; float: left;">
                <div id="setup_page_1">
                    <div style="width: 100%; height: 40px;">
                        <div style="float: left; margin-top: 6px; margin-bottom: -6px; color: #777; width: 200px; text-align: left;" lang="en">Type current password: </div>
                        <input id="pw1" type="password" style="float: left; height: 15px; width: 200px; padding: 7px; color: #444;" value="" />
                        <button id="change_password_validate_button" style="width: 75px; height: 30px; background-color: #52D859; color: #FFF; border-radius: 7px; border: 0px solid #FFF; float: left; margin-left: 10px; outline: 0px;">Validate</button>
                    </div>
                    <div id="change_password_validated_section" style="display: none;">
                        <div style="width: 100%; height: 40px;">
                            <div style="float: left; margin-top: 6px; margin-bottom: -6px; color: #777; width: 200px; text-align: left;" lang="en">Type new password: </div>
                            <input id="pw2" type="password" style="float: left; height: 15px; width: 200px; padding: 7px; color: #444;" value="" />
                        </div>
                        <div style="width: 100%; height: 40px;">
                            <div style="float: left; margin-top: 6px; margin-bottom: -6px; color: #777; width: 200px; text-align: left;" lang="en">Retype new password: </div>
                            <input id="pw3" type="password" style="float: left; height: 15px; width: 200px; padding: 7px; color: #444;" value="" />
                            <button id="change_password_finish_button" style="width: 75px; height: 30px; background-color: #52D859; color: #FFF; border-radius: 7px; border: 0px solid #FFF; float: left; margin-left: 10px; outline: 0px;">Finish</button>
                        </div>
                    </div>
                </div>
                <div id="setup_page_2" style="display: none;">
                    <select class="timezonepicker" id="timezone_picker" size="8" style="display:block; margin-top: 0px; width: 100%;">
                      <option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
                      <option value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
                      <option value="-10.0">(GMT -10:00) Hawaii</option>
                      <option value="-9.0">(GMT -9:00) Alaska</option>
                      <option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
                      <option value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
                      <option value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
                      <option value="-5.0" selected>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
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
                    <button id="timezone_button" style="width: 100%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #22AEFF;">
                        Update
                    </button>
                </div>
                <div id="setup_page_3" style="display: none;">
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
                    <div id="credit_cards_container" style="width: 70%; margin-left: auto; margin-right: auto; height: 135px; overflow: scroll;">
                    </div>
                    <div style="margin-top: 10px; width: 70%; margin-left: auto; margin-right: auto;">
                        <script>
                        function isNumberKey(evt)
                        {
                            var charCode = (evt.which) ? evt.which : event.keyCode
                            if (charCode > 31 && (charCode < 48 || charCode > 57))
                                return false;
                            return true;
                        }    
                        </script>
                        <input type="text" onkeypress="return isNumberKey(event)" id="credit_card_number" maxLength="16" placeholder="Enter card number" style="width: 220px; height: 20px; float: left; border-radius: 5px;">
                        <input id="credit_card_csv_code" type="text" onkeypress="return isNumberKey(event)" id="csv_code" maxLength="3" placeholder="CSV" style="width: 85px; height: 20px; margin-left: 18px; float: left; border-radius: 5px;">
                        <div style="color: #969696; width: 80px; float: left; text-align: left; padding-left: 5px; border-top-left-radius: 5px; border-bottom-left-radius: 5px; border: 1px solid #CACACA; height: 23px; padding-top: 5px; border-right: 0px solid #FFF;" lang="en">Exp. Date:</div>
                        <input id="credit_card_exp_date" type="month" style="width: 135px; height: 20px; float: left; font-size: 12px; border-radius: 0px; border-left: 0px solid #FFF; border-top-right-radius: 5px; border-bottom-right-radius: 5px;" />
                        <button id="add_card_button" style="width: 100px; height: 30px; background-color: #52D859; border-radius: 0px; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px; margin-left: 18px; border-radius: 5px;" lang="en">Add Card</button>
						</div>
						
						</div>
                <div id="setup_page_4" style="display: none;">
                    <div class="formRow" style="margin-left: 50px; margin-top: 20px;">
                        <label lang="en">Country: </label>
                        <div class="formRight">
                            <select id="country_setup" name ="country_setup"></select>
                        </div>
                    </div>
                    <div class="formRow" style="margin-left: 50px; visibility: hidden;">
                        <label lang="en">Region: </label>
                        <div class="formRight">
                            <select name ="state_setup" id ="state_setup"></select>
                        </div>
                    </div>
                    <script language="javascript">
                        populateCountries("country_setup", "state_setup");
                    </script>
                    <button id="location_button" style="width: 45%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #22AEFF; margin-top: 45px;" lang="en">
                        Update
                    </button>
                </div>
                <div id="setup_page_5" style="display: none;">
                    <div class="formRow" style="margin-left: 50px; margin-top: 20px; margin-bottom: 40px;">
                        <label lang="en">Phone: </label>
                        <div class="formRight">
                            <input id="setup_phone" type="text" name="phone" class="intermediate-input validate[required, funcCall[checkPhoneFormat]] span" placeholder="phone" title="Please insert your phone number including country code(just numbers, no special characters or punctuation signs)" style="width: 300px;"/>
                        </div>
                    </div>
                    <div class="formRow" style="margin-left: 50px; margin-top: 10px; margin-bottom: 20px;">
                        <label lang="en">Email: </label>
                        <div class="formRight">
                            <input type="text" id="setup_email" name ="email" style="width: 290px;" />
                        </div>
                    </div>

                    <button id="contact_button" style="width: 45%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #22AEFF; margin-top: 45px;" lang="en">
                        Update
                    </button>
                </div>
                <div id="setup_page_6" style="display: none;">
                    <div style="width: 300px; text-align: center; margin: auto; margin-top: 5px; margin-bottom: 10px;">
                        <label lang="en">Your current subscription is: <span style="color: #22AEFF;"><?php echo $user->member_plan; ?></span></label>
                    </div>
                    <?php
                        if($user->member_plan == 'FREE')
                        {
                            echo '<button id="upgrade_premium_button" style="width: 60%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #54bc00; margin: auto; margin-top: 20px;" lang="en">Change To Premium</button>';
                        }
                        if($user->member_plan == 'FREE' || $user->member_plan == 'PREMIUM')
                        {
                            echo '<button id="upgrade_family_button" style="width: 60%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #54bc00; margin: auto; margin-top: 20px;" lang="en">Change To Family</button><div style="width: 42px; height: 48px; padding-top: 70px; margin: auto; visibility: hidden;" id="upgrade_loading_bar"><img src="../../../../images/load/29.gif" style="margin-bottom: 7px;"  alt=""></div>';
                        }
                        if($user->member_plan == 'FAMILY' && ($user->member_plan == 'Owner' || $user->member_plan == 'Admin'))
                        {
                    ?>
                    
                    
                    
                    <!-- Family Account management will only be loaded if the current account's subscription is 'FAMILY' -->
                    <div id="family_members" style="width: 500px; height: 200px; background-color: #FFF;">
                        <style>
                            .family_member_row{
                                width: 100%;
                                height: 26px;
                                margin-bottom: 8px;
                            }
                        </style>
                        <div style="width: 100%; height: 150px; overflow: scroll;" id="family_users">
                        <?php
                            for($i = 0; $i < count($user->user_accts); $i++)
                            {
                                if($user->user_accts[$i]['access'] != 'Owner')
                                {
                                    echo '<div id="family_member_row_'.$user->user_accts[$i]['ID'].'" class="family_member_row">';
                                    echo '<div style="width: 20%; height: 26px; float: left;">'.$user->user_accts[$i]['Name'].'</div>';
                                    echo '<div style="width: 20%; height: 26px; float: left; color: #22AEFF;">'.ucwords($user->user_accts[$i]['Relationship']).'</div>';
                                    echo '<div style="width: 20%; height: 26px; float: left; color: #54bc00; ';
                                    if($user->user_accts[$i]['access'] == 'Owner' || $user->user_accts[$i]['access'] == 'Admin')
                                    {
                                        echo 'font-weight: bold;';
                                    }
                                    echo '">'.$user->user_accts[$i]['access'].'</div>';
                                    echo '<button id="family_member_edit_'.$user->user_accts[$i]['ID'].'" style="width: 15%; margin-left: 5%; height: 26px; float: left; color: #FFF; background-color: #54bc00; border-radius: 5px; outline: none; border: 0px solid #FFF;">Edit</button>';
                                    echo '<button id="family_member_delete_'.$user->user_accts[$i]['ID'].'" style="width: 15%; margin-left: 5%; height: 26px; float: left; color: #FFF; background-color: #D84840; border-radius: 5px; outline: none; border: 0px solid #FFF;">Delete</button>';
                                    echo '</div>';
                                }
                            }
                            
                        ?>
                            
                        </div>
                        <button id="add_family_member_button" style="width: 45%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #54bc00; margin-top: 20px;" lang="en">
                            Add Family Member
                        </button>
                        <?php
                            if($user->member_age >= 18)
                            {
                                $grant_access = $user->member_grant_access;
                        ?>
                            <button id="grant_admin_access_button" style="width: 65%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: <?php if($grant_access == '0'){echo '#54bc00;';}else{echo '#D84840;';} ?> margin-top: 20px;" lang="en">
                                <?php 
                                    if($grant_access == 0)
                                    {
                                        echo 'Grant Admin Access To My Account';
                                    }
                                    else
                                    {
                                        echo 'Remove Admin Access To My Account';
                                    }
                                ?>
                                
                            </button>
                        
                        <?php
                            }
                        ?>
                    
                    </div>
                    <div id="edit_family_member" style="width: 500px; height: 200px; background-color: #FFF; display: none;">
                        <style>
                            #edit_family_member input{
                                border-radius: 0px;
                                height: 18px;
                                width: 200px;
                                margin: 0px;
                                box-shadow: 0px 0px 0px #FFF;
                                outline: none;
                            }
                            #edit_family_member select{
                                border-radius: 0px;
                                height: 26px;
                                width: 212px;
                                margin: 0px;
                                margin-top: 15px;
                                border: 1px solid #CCC;
                                box-shadow: 0px 0px 0px #FFF;
                                outline: none;
                            }
                        </style>
                        <div style="width: 100%; height: 150px;">
                            <div style="width: 50%; height: 150px; float: left;">
                                <input type="text" placeholder="Name" id="family_member_name" style="border-top-left-radius: 5px; border-top-right-radius: 5px;" />
                                <input type="text" placeholder="Surname" id="family_member_surname" />
                                <div style="width: 40px; height: 22px; border: 1px solid #CCC; border-right: 0px solid #FFF; color: #999; float: left; margin-left: 18px; padding-left: 5px; padding-top: 4px;">DOB: </div>
                                
                                <input type="date" id="family_member_dob" style="width: 155px; float: left; border-left: 0px solid #FFF;" />
                                <input type="text" placeholder="Phone number" id="family_member_phone" class="intermediate-input validate[required, funcCall[checkPhoneFormat]] span" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;" />
                                
                                
                                <select id="family_member_gender" style="margin-top: 8px;">
                                    <option value="none">Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div style="width: 50%; height: 150px; float: left;">
                                <input type="password" placeholder="Password" id="family_member_password" style="border-top-left-radius: 5px; border-top-right-radius: 5px;" />
                                <input type="password" placeholder="Retype Password" id="family_member_password2" />
                                <input type="text" placeholder="Email" id="family_member_email" />
                                
                                <input type="text" placeholder="Birth order (if twin)" id="family_member_order" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;" />
                                
                                
                                <select id="family_member_relationship">
                                    <option value="none">Relationship to owner</option>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Husband">Husband</option>
                                    <option value="Wife">Wife</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                </select>
                            </div>
                        </div>
                        <div style="width: 100%; height: 10px; margin-top: 10px;">
                            <span style="font-size: 10px; text-align: left; "><em>* Email and phone only required if user is over 18</em></span>
                        </div>
                        <button id="edit_family_member_cancel_button" style="width: 15%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #D84840; margin-top: 20px; float: right; margin-right: 20px;" lang="en">
                            Cancel
                        </button>
                        <button id="edit_family_member_done_button" style="width: 15%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #54bc00; margin-top: 20px; margin-right: 10px; float: right;" lang="en">
                            Done
                        </button>
                        
                        <button id="give_admin_privileges" style="height: 16px; width: 16px; background-color: #F8F8F8; border: 1px solid #CCC; padding: 0px; float: left; outline: 0px; border-radius: 16px; margin-top: 22px; margin-left: 20px;">
                            <input type="hidden" value="0" />
                            <div style="width: 10px; height: 10px; margin-left: 2px; border-radius: 10px; background-color: #54bc00;" ></div>
                        </button>
                        <span style="float: left; text-align: center; margin-left: 10px; margin-top: 20px;">Give Admin Privileges</span>
                    
                    </div>
                    
                    <?php
                            }
                            else if($user->member_plan == 'FAMILY' && $user->access == 'Delegate' && $user->member_age >= 18)
                            {
                                
                                $grant_access = $user->member_grant_access;
                        ?>
                            <button id="grant_admin_access_button" style="width: 65%; height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: <?php if($grant_access == '0'){echo '#54bc00;';}else{echo '#D84840;';} ?> margin-top: 20px;" lang="en">
                                <?php 
                                    if($grant_access == 0)
                                    {
                                        echo 'Grant Admin Access To My Account';
                                    }
                                    else
                                    {
                                        echo 'Remove Admin Access To My Account';
                                    }
                                ?>
                                
                            </button>
                        
                        <?php
                            }
                        ?>
                </div>
                <div id="setup_page_7" style="display: none;">



                    <div style="margin-left:50px;">
                    <?php
                        if(file_exists("../../../../PatientImage/".$user->mem_id.".jpg")){
							echo "<img id='profile_pic' style='float:left;border:2px solid #cacaca;max-height:250px;max-width:200px;' width='200px' src='../../../../PatientImage/".$user->mem_id.".jpg' />";
						}else{
							echo "<img id='profile_pic' style='float:left;border:2px solid #cacaca;max-height:250px;max-width:200px;' width='200px' src='../../../../PatientImage/defaultDP.jpg' />";
						}
						
						if(file_exists("PatientImage/licenses/".$user->mem_id.".jpg")){
							echo "<img id='license_pic' style='float:left;border:2px solid #cacaca;max-height:250px;max-width:200px;' width='200px' src='../../../../PatientImage/licenses/".$user->mem_id.".jpg' />";
						}else{
							echo "<img id='license_pic' style='float:left;border:2px solid #cacaca;max-height:250px;max-width:200px;' width='200px' src='../../../../PatientImage/licenses/icon-id.jpg' />";
						}
						
						if(file_exists("../../../../PatientImage/".$user->mem_id.".jpg") && file_exists("../../../../PatientImage/licenses/".$user->mem_id.".jpg")){
							//$output = shell_exec("br -algorithm FaceRecognition -compare /var/www/html/PatientImage/".$user->mem_id.".jpg /var/www/html/PatientImage/licenses/".$user->mem_id.".jpg");
							//echo "<div style='width:400px;' id='matching_percent'>".($output * 100)."% Match</div>";
						}
                        ?>
                        
                        <div class="row">
						<label style="display:none;" for="fileToUpload">Select a File to Upload</label><br />
						<input style="display:none;" type="file" name="fileToUpload" id="fileToUpload2" onchange="fileSelected();"/>
						</div>
						<div  style="display:none;" id="fileName"></div>
						<div  style="display:none;" id="fileSize"></div>
						<div  style="display:none;" id="fileType"></div>
						<div class="row">
						<input id="make_upload" style="width:0px;display:none;" type="button" onclick="uploadFile2()" value="Upload" />
						</div>
						<div id="progressNumber"></div>
						
                        <div style="width:100%;">
                        <button id="add_profile_image_button" style="width:150px;height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #22AEFF; margin-top: 20px;float:left;margin-left:35px;" lang="en">
                        Add Profile Image
						</button>
                        <button id="add_license_button" style="width:150px;height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #22AEFF; margin-top: 20px;float:left;margin-left:50px;" lang="en">
                        Add License
						</button>
						</div>
						<div style='width:100%;'>
							<?php
							if($user->member_charge1 >= 50 && $user->member_charge2 >= 50  && $user->member_cc_verified != 1){
								echo "<div style='width:220px;height: 60px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: white; margin-top: 20px;float:left;'>
								<input id='charge1_cc' type='number' size='4' max='1' min='0.5' step='0.01' value='0.5' placeholder='First Charge' />
								<input id='charge2_cc' type='number' size='4' max='1' min='0.5' step='0.01' value='0.5' placeholder='Second Charge' />
								</div>
								
								<button id='check_cc_amounts' style='width:180px;height: 60px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #54bc00; margin-top: 20px;float:left;margin-left:20px;' lang='en'>
								Submit Charges for Verification
								</button>";
							}elseif($user->member_cc_verified == 1){
								echo "<button style='width:250px;height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #54bc00; margin-top: 20px;float:left;margin-left:80px;' lang='en'>
								Credit Card Verified
								</button>";
							}else{
								echo "<button id='verify_cc' style='width:220px;height: 60px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #22AEFF; margin-top: 20px;float:left;margin-left:85px;' lang='en'>
								Charge My Credit Card Two Small Amounts to Verify Bank Information
								</button>";
							}
							echo "<button id='verify_back_license' style='width:220px;height: 30px; border-radius: 5px; border: 0px solid #FFF; outline: 0px; color: #FFF; background-color: #22AEFF; margin-top: 20px;float:left;margin-left:95px;' lang='en'>
								Scan My Driver's License
								</button>";
							?>
						</div>
						
                        </div>
                        
                        
                        <!--
                        <div class="formRow" style="margin-left: 50px; margin-top: 20px; margin-bottom: 40px;">
						<input type="text" style="width: 220px; height: 20px; float: left; border-radius: 5px;margin-left:75px;" placeholder="Enter First Charge" lang="en">
						<input type="text" style="width: 220px; height: 20px; float: left; border-radius: 5px;margin-left:75px;" placeholder="Enter Second Charge" lang="en">
                        <button id="verify_card_button" style="width: 100px; height: 30px; background-color: #52D859; border-radius: 0px; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px; margin-left: 18px; border-radius: 5px;" lang="en">Verify</button>
                        -->
                    </div>
                    
                    <img id="update_spinning_icon" style="visibility: hidden; margin-top: 40px;" src="../../../../images/load/29.gif"/>
                </div>
						
        </div>
        
            </div>
            
        
        <div id="setup_modal_notification_container" style="display:none;width: 600px; height: 30px; margin-left: 25px; opacity: 0.0;">
            <div id="setup_modal_notification" style="height: 30px; padding-top: 10px; width: 500px; background-color: #888; border-radius: 20px; margin-left: 50px; position: relative;"></div>
        </div> 
        
        
    </div>
    
    <!--MODAL VIEW FOR SCANNING PDF-417-->
        <div id="scan_backdl_photo" title="Scan Driver's License" style="display:none; text-align:center; padding:20px; width: 600px; height: 600px;">
			<div style="border:1px soldi #cacaca;float:left;"><img style="width:300px;" src="../../../../images/backoflicense.jpg" /></div><p></p>
			<div style="float:left;" ><img style="width:300px;" src="../../../../PatientImage/licenses/icon-id.jpg" /></div>
		</div>
    <!--END MODAL VIEW-->
      
      
      <!-- MODAL VIEW TO FIND DOCTOR -->
    <div id="find_doctor_modal" title="Find Doctor" style="display:none; text-align:center; padding:20px;">
        <div id="Talk_Section_1" style="display: block;">
            <!--<input type="text" style="width: 90%; margin-top: 15px; margin-bottom: 15px; height: 20px; color: #CACACA; padding: 5px;" placeholder="Search for a doctor..." value="" />-->
            <style>
                .recent_doctor_button{
                        height: 50px; 
                        width: 50px; 
                        margin: auto; 
                        color: #FFFFFF; 
                        background-color: #22AEFF;
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
                .square_blue_button_disabled
                {
                    width: 110px;
                    height: 110px;
                    border-radius: 7px;
                    font-size: 14px;
                    color: #FFFFFF;
                    border: 0px solid #FFF;
                    outline: 0px;
                    margin-top: 55px;
                    margin-left: 15px;
                    margin-right: 15px;
                    background-color: #D4F0FF;
                    cursor: default;
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
                .yes_no_button{
                    width: 60px;
                    height: 40px;
                    border-radius: 4px;
                    font-size: 14px;
                    color: #FFFFFF;
                    background-color: #22aeff;
                    border: 0px solid #FFF;
                    outline: 0px;
                    margin-top: 40px;
                    margin-left: 10px;
                    margin-right: 10px;
                }
            </style>
            <div style="width: 100%; height: 35px; margin-top: -5px; margin-left: -5px;">
                <p id="find_doctor_label" style="font-size: 18px; color: #CACACA; font-style: italic; float: right;"></p>
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
                    <button lang="en" id="find_doctor_now_button" class="square_blue_button<?php 
                                $res = $user->con->prepare("SELECT id FROM doctors WHERE telemed=1 AND in_consultation=0");
								$res->execute();
                                $num_rows = $res->rowCount();
                                if($num_rows == 0)
                                {
                                    echo "_disabled";
                                }
                            ?>">
                        <div style="margin-bottom: -8px;"><i class="icon-bolt" style="font-size: 40px;"></i></div>
                        <br/><span lang="en">Call Now</span>
                    </button>
                    <button lang="en" id="find_doctor_my_doctors_button" class="square_blue_button<?php 
                        $res = $user->con->prepare("SELECT most_recent_doc FROM usuarios WHERE Identif=?");
						$res->bindValue(1, $user->mem_id, PDO::PARAM_INT);
						$res->execute();
						
                        $row = $res->fetch(PDO::FETCH_ASSOC);
                        $str = $row['most_recent_doc'];
                        if(strlen($str) < 3)
                        {
                            echo "_disabled";
                        }?>">
                        <div style="margin-bottom: -8px;"><i class="icon-user-md" style="font-size: 40px;"></i></div>
                        <br/><span lang="en">My Doctors</span>
                    </button>
                    <button lang="en" id="find_doctor_appointment_button" class="square_blue_button">
                        <div style="margin-bottom: -8px;"><i class="icon-calendar" style="font-size: 40px;"></i></div>
                        <br/><span lang="en">Appointment</span>
                    </button>
                </div>
                
                <!-- My Doctors Pages -->
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_my_doctors_1">
                    <?php 
                        $str = $user->member_most_recent_doc;
                        $str = str_replace(array("[", "]"), "", $str);
                        $ids = explode(",", $str);
                
                        date_default_timezone_set ("GMT");
                        $date = new DateTime('now');
                        for($i = 0; $i < count($ids); $i++)
                        {
                            $doc_result = $user->con->prepare("SELECT Name,Surname,phone,location FROM doctors WHERE id=? AND telemed=1 AND in_consultation=0");
							$doc_result->bindValue(1, $ids[$i], PDO::PARAM_INT);
							$doc_result->execute();

                            if($doc_result->rowCount() > 0)
                            {
                                $doc_row = $doc_result->fetch(PDO::FETCH_ASSOC);
                                $result2 = $user->con->prepare("SELECT * FROM timeslots WHERE doc_id=?");
								$result2->bindValue(1, $ids[$i], PDO::PARAM_INT);
								$result2->execute();
								
                                $found = false;
                            
                                while(($row2 = $result2->fetch(PDO::FETCH_ASSOC)) && !$found)
                                {
                                    $start = new DateTime($row2['week'].' '.$row2['start_time']);
                                    $end = new DateTime($row2['week'].' '.$row2['end_time']);
                                    $date_interval = new DateInterval('P'.$row2['week_day'].'D');
                                    $time_interval = new DateInterval('PT'.intval(substr((htmlspecialchars($row2['timezone'])), strlen((htmlspecialchars($row2['timezone']))) - 8, 2)).'H'.intval(substr((htmlspecialchars($row2['timezone'])), strlen((htmlspecialchars($row2['timezone']))) - 5, 2)).'M');
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
                                echo '<button id="recdoc_'.$ids[$i].'_'.(htmlspecialchars($doc_row['phone'])).'_'.(htmlspecialchars($doc_row['Name'])).'_'.(htmlspecialchars($doc_row['Surname'])).'_'.(htmlspecialchars($doc_row['location']));
                                if($found)
                                {
                                    echo '_Available';
                                }
                                echo '" class="square_blue_button" style="width: 100px; height: 100px; margin-left: 3px; margin-right: 3px; padding: 0px;">Doctor<br/>'.(htmlspecialchars($doc_row['Name'])).' '.(htmlspecialchars($doc_row['Surname'])).'</button>';
                            }
                        }
                    ?>
                </div>
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_my_doctors_2">
                    <div style="width: 100%; height: 75px;">
                        
                        <p style="float: right; margin-top: 5px; margin-right: 50px;">
						   <input type="checkbox" id="in_location_checkbox">
            			   <label for="in_location_checkbox"><span></span></label>
                        </p>
                        <p style="text-align: left; margin-top: 30px; margin-bottom: -30px; margin-left: 50px;" id="doctor_location_text" lang="en">Doctor Janme Doe is in <strong>TEXAS</strong>.<br/>Please confirm that you are in <strong>TEXAS</strong> as well.</p>
                    </div>
                    <div style="width: 90%; margin-left: 10%; height: 50px; margin-top: 7px;">
                        <p style="text-align: left; float: left;" lang="en">Video or phone consultation?</p>
                        
                        <div style="width: 100px; height: 30px; border-radius: 3px; background-color: #535353; float: left; margin-left:105px; margin-top: -6px;">
                            <button style="width: 50px; height: 30px; border-top-left-radius: 3px; border-bottom-left-radius: 3px; background-color: #22aeff; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="find_doctor_video_button_2">
                                <i class="icon-facetime-video"></i>
                            </button>
                            <button style="width: 50px; height: 30px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; background-color:  #535353; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="find_doctor_phone_button_2">
                                <i class="icon-phone"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_my_doctors_3">
                    <p style="margin-top: 30px; margin-bottom: -30px;" id="doctor_oncall_text" lang="en">Doctor Jane Doe is ON CALL NOW!<br/>Would you like to connect now?</p>
                    <button class="yes_no_button" id="connect_now_yes" lang="en">Yes</button>
                    <button class="yes_no_button" id="connect_now_no" lang="en">No</button>
                </div>
                <!-- End My Doctors Pages -->
                
                <!-- Appointment Pages -->
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_appointment_1">
                    <div style="width: 100%; height: 140px;">
                        <p lang="en">Which area will you be calling from?</p>
                        <div class="formRow" style="margin-left: 50px;">
                            <label lang="en">Country: </label>
                            <div class="formRight">
                                <select id="country" name ="country"></select>
                            </div>
                        </div>
                        <div class="formRow" style="margin-left: 50px; display: none;">
                            <label lang="en">Region: </label>
                            <div class="formRight">
                                <select name ="state" id ="state"></select>
                            </div>
                        </div>
                    </div>
                    <div style="width: 90%; margin-left: 10%; height: 50px; margin-top: 7px;">
                        <p style="text-align: left; float: left;" lang="en">Video or phone consultation?</p>
                        
                        <div style="width: 100px; height: 30px; border-radius: 3px; background-color: #535353; float: left; margin-left:80px; margin-top: -6px;">
                            <button style="width: 50px; height: 30px; border-top-left-radius: 3px; border-bottom-left-radius: 3px; background-color: #22aeff; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="find_doctor_video_button">
                                <i class="icon-facetime-video"></i>
                            </button>
                            <button style="width: 50px; height: 30px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; background-color:  #535353; border: 0px solid #FFF; color: #FFF; float: left; outline: 0px;" id="find_doctor_phone_button">
                                <i class="icon-phone"></i>
                            </button>
                        </div>
                    </div>
                    
                    
                    <script language="javascript">
                        populateCountries("country", "state");
                    </script>
                </div>
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_appointment_2">
                    <button id="find_doctor_general_practicioner" class="square_blue_button" style="float: right; margin-top: 15px;">
                        <div style="margin-bottom: -8px;"><i class="icon-user-md" style="font-size: 40px;"></i></div>
                        <br/><span lang="en">General Practicioner</span>
                    </button>
                    <div style="width: 400px; height: 140px;">
                        <select name="speciality" id="speciality" size="6" style="float: left; width: 360px; margin-top: 15px;">
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
                </div>
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_appointment_3">
                    <br/><br/><br/><br/>
                    <p id="not_found_text" style="color: #FF3730; font-weight: bold; text-align: center;" lang="en">Sorry, we could not find<br/>any general practicioners in your area.</p>
                </div>
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_time">
                    <style>
                        .days_button{
                            width: 47px;
                            height: 50px;
                            font-size: 14px;
                            color: #FFFFFF;
                            background-color: #22AEFF;
                            border: 0px solid #FFF;
                            outline: 0px;
                            margin-top: 55px;
                            margin-right: 2px;
                            float: left;
                        }
                        .day_selected{
                            background-color: #1673A5;
                        }
                        .day_disabled{
                            cursor: default;
                            background-color: #B3E4FF;
                        }
                        .slots_button{
                            width: 125px;
                            height: 30px;
                            font-size: 14px;
                            color: #FFFFFF;
                            background-color: #FF8C2C;
                            border: 0px solid #FFF;
                            outline: 0px;
                            margin-bottom: 2px;
                            float: right;
                        }
                        .slot_selected{
                            background-color: #AA5D1D;
                        }
                        .slot_disabled{
                            cursor: default;
                            background-color: #FFDABC;
                        }
                    </style>
                    <div style="height: 100%; float: right; margin-top: -20px; width: 20px;">
                        <i class="icon-chevron-left" id="time_selector_1" style="display: none;"></i>
                    </div>
                    <div style="width: 23%; height: 100%; float: right; margin-top: -20px;">
                        <button class="slots_button" id="8_10_am" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">8 to 10 am</button>
                        <button class="slots_button" id="10_12">10 to 12 pm</button>
                        <button class="slots_button" id="12_2">12 to 2 pm</button>
                        <button class="slots_button" id="2_4">2 to 4 pm</button>
                        <button class="slots_button" id="4_6">4 to 6 pm</button>
                        <button class="slots_button" id="6_8">6 to 8 pm</button>
                        <button class="slots_button" id="8_10_pm" style="border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;">8 to 10 pm</button>
                    </div>
                    <div style="width: 70%; height: 75px; float: left;">
                        <button class="days_button" id="sun" style="border-top-left-radius: 4px; border-bottom-left-radius: 4px;">Sun<br/>
                            <?php
                            echo '<span style="font-size: 10px;">'.$user->generate_calender(0, 'd M').'</span>';
                            echo '<input type="hidden" value="'.$user->generate_calender(0, 'Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        <button class="days_button" id="mon" lang="en">Mon<br/>
                            <?php
                            echo '<span style="font-size: 10px;">'.$user->generate_calender(1, 'd M').'</span>';
                            echo '<input type="hidden" value="'.$user->generate_calender(1, 'Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        <button class="days_button" id="tues" lang="en">Tues<br/>
                            <?php
                            echo '<span style="font-size: 10px;">'.$user->generate_calender(2, 'd M').'</span>';
                            echo '<input type="hidden" value="'.$user->generate_calender(2, 'Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        <button class="days_button" id="wed" lang="en">Wed<br/>
                            <?php 
                            echo '<span style="font-size: 10px;">'.$user->generate_calender(3, 'd M').'</span>';
                            echo '<input type="hidden" value="'.$user->generate_calender(3, 'Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        <button class="days_button" id="thur" lang="en">Thur<br/>
                            <?php
                            echo '<span style="font-size: 10px;">'.$user->generate_calender(4, 'd M').'</span>';
                            echo '<input type="hidden" value="'.$user->generate_calender(4, 'Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        <button class="days_button" id="fri" lang="en">Fri<br/>
                            <?php
                            echo '<span style="font-size: 10px;">'.$user->generate_calender(5, 'd M').'</span>';
                            echo '<input type="hidden" value="'.$user->generate_calender(5, 'Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                        <button class="days_button" id="sat" style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;" lang="en">Sat<br/>
                            <?php
                            echo '<span style="font-size: 10px;">'.$user->generate_calender(6, 'd M').'</span>';
                            echo '<input type="hidden" value="'.$user->generate_calender(6, 'Y-m-d').'" />';
                            ?>
                        <input type="hidden" value="" />
                        <input type="hidden" value="" />
                        </button>
                    </div>
                    <div style="height: 75px; width: 70%; float: left; margin-top: 30px; margin-left: 0px;">
                        <i class="icon-chevron-up" id="day_selector_1" style="float: left; display: none;"></i>
                    </div>
                    
                </div>
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_receipt">
                    <ul style="color: #22AEFF; margin-top: 50px; margin-left: 120px;">
                        <li style="text-align: left;"><span lang="en">Receipt:</span> <strong>HTI - CR102388</strong></li>
                        <li style="text-align: left;" lang="en"><strong lang="en">Video Consultation</strong></li>
                        <li style="text-align: left;" lang="en">With a <strong>General Practicioner</strong></li>
                        <li style="text-align: left;" lang="en">next <strong>Thursday</strong> between <strong>12 and 2 pm</strong></li>
                    </ul>
                </div>
                <!-- End Appointment Pages -->
                
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_confirmation">
                    <p style="color: #22AEFF; margin-top: 50px;" lang="en">
                        <strong lang="en">Thank you!</strong><br/><strong lang="en">Your consultation appointment is confirmed</strong><br/><span lang="en">Please be ready at the selected date and time, and follow the instructions that we sent you.</span>
                    </p>
                </div>
                
                <div style="width: 100%; height: 235px; padding-top: 15px; display: none;" id="find_doctor_call_status">
                    <p style="color: #22AEFF; margin-top: 50px;" lang="en">
                        <strong lang="en">Thank you!</strong>
                        <br/>
                        <strong lang="en">Your consultation appointment is starting now.</strong>
                        <br/><br/>
                        <span lang="en" style="color: #555; font-size: 18px;">Call Status:  <span id="call_status_label" lang="en" style="color: #777;">Sent Doctor Notification, please wait ...</span></span>
                    </p>
                </div>
            </div>
            <div style="width: 100%; height: 40px; margin-top: 10px;">
                <button id="find_doctor_cancel_button" class="find_doctor_button" style="background-color: #D84840; float:left; margin-left: 0px;" lang="en">Cancel</button>
                <button id="find_doctor_close_button" class="find_doctor_button" style="background-color: #52D859; display: none; margin-left: auto; margin-right: auto; float: none;" lang="en">Close</button>
                <button id="find_doctor_next_button" class="find_doctor_button" style="background-color: #52D859;" lang="en">Next</button>
                <button id="find_doctor_previous_button" class="find_doctor_button" style="background-color: #22aeff;" lang="en">Previous</button>
            </div>
        </div>
        <div id="Talk_Section_2" style="display: none;">
            <button style="width: 200px; heightL 30px; background-color: #22aeff; color: #FFF; border: 0px solid #FFF; margin: auto; margin-top: 15px; margin-left: 20px; border-radius: 7px; outline: 0px; float: left;" id="video_call_button" lang="en">Video Call</button>
            <button style="width: 200px; heightL 30px; background-color: #22aeff; color: #FFF; border: 0px solid #FFF; margin: auto; margin-top: 15px; margin-right: 20px; border-radius: 7px; outline: 0px; float: right;" id="phone_call_button" lang="en">Phone Call</button>
           
            
        </div>
        <div id="Talk_Section_3" style="display: none;">
            <br/>
            <p lang="en">No doctors are available at this time. Please try again later.</p>
           
            
        </div>
        <div id="Talk_Section_4" style="display: none;">
            <br/>
            <p lang="en">We are now calling your doctor, please wait...</p>
           
            
        </div>
    </div>
      
    <!-- END MODAL VIEW TO CONNECT DOCTOR -->



<input type="hidden" id="NombreEnt" value="<?php echo $user->member_first_name; ?>">
<input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?>">
<input type="hidden" id="UserHidden">

	<!--Header Start-->
	<?php include '../../common/header.php'; ?>

    <!--Header END-->

<!-- Start of code inserted from userdashboard-new-pallab.php -->
<!-- Start of code for displaying modal window for Send buttton-->
<div id="modal_send" title= "Send Reports Link" style="display:none; text-align:center; padding:10px;">
    
        <form lang="en">
         Doctor's Email: <input type = "text" id="EmailID" width ="80" value='' />
        </form>
    
        <!-- add stream here -->
        <div style="width: 100%; margin: auto; height: 300px; overflow-y:hidden;text-align: center; margin-top: 30px; margin-bottom: 20px;" id="sendStreamContainer">
            <!--<div style="width: 52px; height: 42px; margin-left: auto; margin-right: auto; margin-top: 100px;">
                <img src="images/load/29.gif"  alt=""> Loading Reports

            </div>-->
           
        </div>
	
<!-- MODAL FOR MEDICAL RELEASE FORM -->
 <div id="medical-release" title='Request Reports' style="display:none;">
	<form id="release-form" method="post" action="" class="sigPad">	
	<div>
		This authorizes you to provide a copy, summary, or narrative of my medical records or otherwise release confidential information.
	</div>

<!--<select id="recordtype" onchange="displaySelectOptions();">
  <option value="0">Complete record </option>
  <option value="1">Records of care from the folowing dates:</option>
  <option value="2">Records concerning the following conditions:</option>
  <option value="3">Other, please specify</option>
  <option value="4">Confer orally with person(s) listed below about my medical information:</option>
</select>
	<div id="recordtype-dates" style="display:none;">
<span><input id="start-date" type="date" name="start-date"></span> to <span><input id="end-date" type="date" name="end-date"></span>
	</div>	
	<div id="recordtype-info" style="display:none;">
		<textarea rows="4" cols="50">
		</textarea>
	</div>	-->
<div><h3 style="color:#5ab1f4;font-size:18px;">Patient Information:</h3>
	<div><div><div style="display:inline-block;margin:5px;margin-right:50px;"><span><label>Patient Name: </label></span><span><input type="text" id="patient_name" name="patient_name"></span></div>
		
		<div style="display:inline-block;margin:5px;"><span><label>Patient SS#: </label></span>
			<input id="ssnum" type="text" name="ssnum"></div>
	</div></div>
	<div><div><div style="display:inline-block;margin:5px;margin-right:50px;"><span><label>Patient DOB: </label></span>
		<span><input id="dob" type="date" name="dob"></span></div>
		<div style="display:inline-block;margin:5px;">
		<span><label>Patient Telephone #:</label></span>
		<span><input id="telephone" type="text" name="telephone"></span></div>
	</div></div>
<div>
<h3 style="color:#5ab1f4;font-size:18px;">Request Reports From:</h3>
</div>	
<span><label>Doctor's Email: </label></span><span><input type="text" id="doc_email" name="doc_email"></span>	
</div>	

<!--<div>The reason or purposes for this release of information are as follows:
	<div><textarea id="reasons" rows="4" cols="50"></textarea></div>
</div>	-->

<div style="margin-top:10px;margin-bottom:10px;">I understand that the doctor's office will provide this information within 30 days from receipt of request and that a fee for preparing and furnishing this information may be charged according to applicable state and federal laws.
</div>	

	  <label for="name">Print your name</label>
  <input type="text" name="name" id="name" class="name">
  <p class="typeItDesc">Review your signature</p>
  <p class="drawItDesc">Draw your signature</p>
  <ul class="sigNav">
    <li class="typeIt"><a href="#type-it" class="current">Type It</a></li>
    <li class="drawIt"><a href="#draw-it">Draw It</a></li>
    <li class="clearButton"><a href="#clear">Clear</a></li>
  </ul>
  <div class="sig sigWrapper">
    <div class="typed"></div>
    <canvas class="pad" width="400" height="55"></canvas>
    <input type="hidden" id="output" name="output" class="output">
  </div>
	   <input type="checkbox" name="formWheelchair" value="Yes" />I accept the terms of this agreement.<br>
	
    <div ><button style="float:none; background-color:#51a7f9; color:white; margin: 0 auto; display:block;" id="request-button">Request Reports</button></div>
</form>	
</div>
<!-- END MODAL FOR MEDICAL RELEASE FORM -->
    
        <button id="CaptureEmail2Send2Doc" style="border:0px;border-radius:6px;height: 24px; width:50px; color:#FFF; background-color:#22aeff;float:bottom; margin-top:20px;" lang="en">Send</button>
    
     <!-- Commented by Pallab for further testing   <button id="getPreviousDoctors" style="border:0px;border-radius:6px;height: 24px; width:50px; color:#FFF; background-color:#22aeff;float:bottom; margin-top:20px;" lang="en">Select Previous Doctors</button> -->
</div>
<!-- End of code for displaying modal window for Send buttton-->

<!-- Start of code for displaying modal window for Request button -->

<div id="modal_request" title ="Request Reports" style="display:none; text-align:center; padding:10px;">
    
        <form>
         <span style="" lang="en">Email of Doctor</span> <input type = "text" id = "EmailIDRequestPage" style="width:250px">
        </form>
        
     <!--  <div style="text-align: top;margin-top:20px">
           <!--<form>-->
        <!--    <span style="" lang="en">Message</span> <textarea name = "Message" id = "MessageForDoctor" style="height:100px;width:300px;" ></textarea>      
           <!--</form>-->
       <!-- </div> -->
        
        <div> 
            <button id="CaptureEmail2Request2Doc"  style="border:0px;color:#FFF;border-radius:6px;height: 40px; width:138px;margin-top:20px;margin-right:40px;margin-left:40px;background-color:#22aeff;" lang="en">Request Reports</button>
        </div>
        
    
</div>

<!-- End of code for displaying modal window for Request button -->   
<!-- End of code inserted from userdashboard-new-pallab.php -->
 
   	 <!--- VENTANA MODAL  This has been added to show individual message content which user click on the inbox messages ---> 
   	 <button id="message_modal" data-target="#header-message" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button> 
   	  <div id="header-message" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header" lang="en">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  <span lang="en">Message Details</span>
         </div>
         <div class="modal-body">
         <div class="formRow" style=" margin-top:-10px; margin-bottom:10px;">
             <span id="ToDoctor" style="color:#2c93dd; font-weight:bold;" lang="en">TO <?php echo 'Dr. '.$user->NameDoctor.' '.$user->SurnameDoctor; ?></span><input type="hidden" id="IdDoctor" value='<?php echo $user->member_id_creator ?>'/>
         </div>
         <textarea  id="messagedetails" class="span message-text" style="height:200px;" name="message" rows="1"></textarea>
         
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
                            <textarea  id="messagecontent_inbox" class="span message-text" name="message" style="height:90px;" rows="1"></textarea>
                            
                            <div class="clear"></div>
                        </div>
                   </div>
            </form>
			<div id="attachments" style="display:none">
			
			
			
			</div>
		 </div>
         <input type="hidden" id="Idpin">
        <!-- <input type="hidden" id="docId" value="<?php if(isset($IdMed)) echo $IdMed; ?>"/> -->
         <div class="modal-footer">
		     <input type="button" class="btn btn-info" value="Send messages" id="sendmessages_inbox">
             <input type="button" class="btn btn-success" value="Attach" id="Attach">	
	         <input type="button" class="btn btn-success" value="Reply" id="Reply">			 
	         <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseMessage" lang="en">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 	
      
      <!--- VENTANA MODAL NUMERO 2 ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
   	  <div id="header-modal2" class="modal hide" style="display: none; height:470px;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4 lang="en">Upload New Report</h4>
                 <input type="hidden" id="URLIma" value="zero"/>
         </div>
         
         <div class="modal-body" id="ContenidoModal2" style="height:320px;">
             <div  id="RepoThumb" style="width:70px; float:right; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);"></div>
           <div class="ContenDinamico2">
        
           <!-- <a href="#" class="btn btn-success" id="ParseReport" style="margin-top:10px; margin-bottom:10px;">Parse this report now.</a> -->

           		<form action="../../../../upload_fileUSER.php?queId=<?php echo $user->mem_id ?>&from=0" method="post" enctype="multipart/form-data">
	           		<label for="file" lang="en">Report:</label>
	           		<input type="file" class="btn btn-success" name="file" id="file" style="margin-right:20px;"><br>


            </div>  

         </div>
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <!--<a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Update Data</a>-->
             <input type="submit" class="btn btn-success" name="submit" value="Submit">
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal" lang="en" >Close</a>
             
             	</form>

         </div>
       </div>
	  <!--- VENTANA MODAL NUMERO 2  ---> 
 
    <!--Content Start-->
	<div id="content" style="padding-left:0px;">
    
    	    
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
		
         <li><a href="" class="act_link" lang="en">Dashboard</a></li>

    	 <li><a href="../../patientdetails/html/PatientDetails.php?IdUsu=<?php echo $user->mem_id ?>"  lang="en">Medical Records</a></li>
 <!--        <li><a href="medicalConfiguration.php">Configuration</a></li>-->
         <?php if($user->member_privilege == 'CATA') { ?>
         <li><a href="../../../ajax/logout.php" style="color:DarkRed;" lang="en">Sign Out</a></li>
         <?php } else { ?>
         <li><a href="../../../ajax/logout.php" style="color:yellow;" lang="en">Sign Out</a></li>
         <?php } ?>
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
     
     <style>
        #tickerwrapper{margin:0px; padding:0px;width:300px;}
         #tickerwrapper ul li{padding:5px;text-align:left}
         #tickerwrapper .container{width:300px;height:60px;overflow:hidden; float:left;display:inline-block}  
         div.H2MInText{
                        padding:0px; 
                        font-size:12px; 
                        font-family: "Arial";
                        text-align:left; 
                        color:grey; 
                        display:table-cell; 
                        vertical-align:middle;
                        padding-left:20px;
                        width:50%;  
                          
                        }
                    
        div.H2MTray{
                        width:300px; 
                        height:50px; 
                        border:1px solid #cacaca; 
                        border-radius:5px; 
                        display:table;
                        margin:0px;
                        padding: 10px;
                        background-color: #F8F8F8;
                        float:right;
                        margin-bottom: 20px;
                        vertical-align: top;
                        margin-right: 50px;
                     }
     </style>     
     
     <!--CONTENT MAIN START-->
     <div class="content">
     <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">
 		     <div class="row-fluid" style="">	            
                      <div style="margin:15px; padding-top:0px;">
                             <span class="label label-success" style="left:0px; margin-left:10px; margin-top:0px; font-size:30px;" lang="en">User Dashboard</span>
                             <div class="clearfix" style="margin-bottom:20px;"></div>
                             <?php
                                          $hash = md5( strtolower( trim( $user->member_email ) ) );
                                          $avat = '../../../../identicon.php?size=75&hash='.$hash;
                                            ?>	
						  <a href="../../../../emergency.php?id=<?php echo $user->mem_id; ?>">   <img src="<?php echo $user->qrcodeResults; ?>" style="width:90px;height:90px;float:right; margin-right:40px; font-size:18px; padding:0px 0px 15px 0px; font-family: “Andale Mono”, AndaleMono, monospace;"/></a>
                          <style>
                            div.RowNotif{      
                                  width: 100%;
                                  height: 25px;
                                  position: relative;
                                  line-height: 25px;
                                  border: 0px solid #cacaca;
                                  margin-top:3px;
                            }
                            span.TextNot{
                                float: left;
                                margin-left: 10px;  
                                font-size: 14px;
                            }
                            span.To{
                                color:grey;
                            }
                            span.Who{
                                color:#22aeff;
                                width:158px;
                            }
                            span.When{
                                color:grey;
                                width: 120px;
                                text-align: center;
                            }
                              
                          </style>  
                          <!--
                            <div id="TrackNotifications" style="border:0px solid #cacaca; width:450px; height:100px; float:right; margin-right:50px; position:relative; overflow: auto;"></div>
                          --->    
                            <div style="display:inline-block; line-height: 200%; width: 250px;">
                            <?php if($plan == 'FAMILY' && count($user->user_accts) > 0 && ($user->original_access == 'Owner' || $user->original_access == 'Admin')) { ?>
                             <div style="width: 40px; height: 22px; float: right; margin-top: -5px; margin-right: -15px; margin-bottom: 5px; z-index: 2; position: relative;">
                                 <button style="border: 0px solid #FFF; height: 22px; outline: 0px; background-color: #FFF;" id="select_users_button">
                                    <i class="icon-caret-down" style="color: #22AEFF; font-size: 22px; height: 22px;"></i>
                                 </button>
                             </div>
                             <?php } ?>
                             <!--div id="main_notification_baloon" style="margin-top: -10px; margin-left:5px; position: absolute; height: 25px; width: 25px; background: red; border-radius: 12px; text-align: center; color: white; line-height:25px; font-size:14px;"><i class="fa fa-spinner fa-spin"></i></div-->    
                             <span id="NombreComp" style="font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; "><?php echo $user->member_first_name ?>  <?php echo $user->member_last_name ?></span>
                            <?php

                                if($user->member_plan == 'FAMILY' && count($user->user_accts) > 0 && ($user->original_access == 'Owner' || $user->original_access == 'Admin'))
                                {
                                    echo '<div id="select_users" style="font: bold 18px Arial, Helvetica, sans-serif; margin-left:20px; margin-bottom: -'.strval((count($user->user_accts) * 30) + 14).'px; height: '.strval(count($user->user_accts) * 30).'px; padding: 7px; z-index: 3; position: relative; background-color: #555; border-radius: 5px; display: none;">';
                                    echo '<div style="height: 22px; width: 30px; margin: auto; font-size: 22px; color: #555; margin-top: -22px;"><i class="icon-caret-up"></i></div>';
                                    $count = count($user->user_accts);
                                    for($i = 0; $i < $count; $i++)
                                    {
                                        echo '<button id="user_'.$user->user_accts[$i]['ID'].'_'.$user->user_accts[$i]['email'].'_'.$user->user_accts[$i]['age'].'_'.$user->user_accts[$i]['grant_access'].'" class="user_button" style="width: 100%; height: 30px; background-color: #22AEFF; outline: 0px; border: 1px solid #555; color: #FFF;';
                                        if($i == 0)
                                        {
                                            echo ' border-top-left-radius: 5px; border-top-right-radius: 5px;';
                                        }
                                        if($i == $count - 1)
                                        {
                                            echo ' border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;';
                                        }
                                        echo '">'.$user->user_accts[$i]['Name'].'</button>';
                                 
                                    }
                                }
                             ?>
                             </div>
                             <span id="IdUsFIXED" style="font-size: 12px; color: #3D93E0; font-weight: normal; font-family: Arial, Helvetica, sans-serif; display: block; margin-left:20px;"><?php echo $user->member_fixed_id ?></span>
                             <span id="IdUsFIXEDNAME" style="font-size: 14px; color: GREY; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $user->member_fixed_name ?></span>
                             <span id="email" style="font-size: 14px; color: #5EB529; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $user->member_email ?></span>
                           </div>

                          
                     <!--<div class="H2MTray">
        

                        <div class="H2MInText" style="width:270px;">
                            
                            <div id="tickerwrapper">
                            <div class="container">
                                <ul>
                                    <li>You are connected to 2 doctors</li>
                                    <li>You have 18 records available</li>
                                    <li>View your patient summary here</li>
                                    <li>You have 2 upcoming appointments</li>
                                    <li>View your medical history here</li>
                                    <li>View updates here</li>
                                </ul>
                            </div>
                                
                            </div>
                            </div>

                <div style="width:40%; margin-right: 20px; margin-top:20px; text-align:right;display:inline-block"><span class="icon-bullhorn icon-2x"></span></div>    
                        </div>

                <div style="width:40%; margin-right: 20px; margin-top:20px; text-align:right;display:inline-block"></div>    
                        </div>-->

                             
                           
                     
             
             <!--
             <img src="images/GooglePlay.png"  width="120" style="margin:30px; margin-top:0px;margin-bottom:20px;"/>
			 <img src="images/AppStore.png"  width="120" style="margin:30px; margin-top:0px;margin-bottom:20px; margin-left:0px;"/>
			 -->
			 <div style="display:none; visibility: hidden; margin-top:-50px; margin-right:30px; padding:10px; float:right; border:solid; height:80px; width:500px; border:solid 1px #cacaca; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;">
				 <span style="font-size: 16px; color:#22aeff;" lang="en">This season the flu strain is more dangerous: </span><span style="font-size: 14px; color:grey;" lang="en">  Please be aware of the flu shots to prevent major complications. If there is any doubt please <span style="font-weight:bold;">call our office</span> or go to our Health2Me profile page and <span style="font-weight:bold;">appoint a meeting</span>. </span>
				 
			 </div>
			 			 
<div id="modalContents" style="display:none; text-align:center; padding-top:20px;">
		<a id='ButtonSkype' href="skype:health2me?call" value="Telemedicine" class="btn" title="Telemedicine" style="text-align:center; padding:15px; width:40px; height:40px; color:#22aeff; margin-left:50px; float:left;"> 
         	<i class="icon-skype icon-2x" style="margin:0 auto; padding:0px; color:#22aeff;"></i>
         	<div style="width:100%; line-height:5px;"></div>
         	<span lang="en">Skype</span>
        </a>
		<a id='ButtonWeemo'  value="Telemedicine" class="btn" title="Telemedicine" style="text-align:center; padding:15px; width:40px; height:40px; color:#22aeff; margin-left:20px; float:left;"> 
         	<i class="icon-camera icon-2x" style="margin:0 auto; padding:0px; color:#22aeff;"></i>
         	<div style="width:100%; line-height:5px;"></div>
         	<span lang="en">Embed</span>
        </a>
</div>
	 <i id="LoadCanvas1" class="icon-refresh  icon-spin icon-2x" style="color:#22aeff; position:relative; left:300px; top:160px; z-index: 999;"></i>
	 <i id="LoadCanvas2" class="icon-refresh  icon-spin icon-2x" style="color:#22aeff; position:relative; left:680px; top:160px; z-index: 999;"></i>
 		
 						<!-- 	<div id="status">Status here</div>  -->
 
			 <div style="width:100%;"></div>
         
            <!-- TELEMED NOTIFICATOR -->
                <div class="grid" class="grid span4" style="margin:0px auto; margin-bottom: 20px; margin-left: 30px; float: left; padding:20px; padding-top: 10px; height: 30px; display: none; background-color: #54bc00; width: 88%" id="telemed_notificator">
                    <div style="margin-top:10px;"><div style="width: 78%; height: 30px; margin-top: -6px; float: right;"><a id="telemed_deny_button" href="javascript:void(0)" class="btn" title="Acknowledge" style="width:2%; color: red; margin-bottom: 2px;margin-left: 2%; float: right; padding-left: -15px;"><i id="' + items[i].ID + '" class="icon-remove"></i></a><a id="telemed_connect_button" href="javascript:void(0)" class="btn" title="Acknowledge" style="width:2%; color: green; margin-bottom: 2px; float: right; padding-left: -15px;"><i id="' + items[i].ID + '" class="icon-facetime-video"></i></a><p id="video_consultation_text" style="color: #FFF; height: 10px; font-size: 14px; margin-top: 4px;">Doctor Javier Vinals is calling you for a video consultation </p></div><input type="hidden" id="video_consultation_id" value="<?php if(isset($telemed)) echo $telemed; ?>" /><input type="hidden" id="video_consultation_name" value="<?php if(isset($telemed_name)) echo $telemed_name; ?>" /><span class="label label-info" id="EtiTML" style="background-color:#FFF; margin:20px; margin-left:0px; margin-bottom:0px; font-size:16px; text-shadow:none; text-decoration:none; color:#54bc00;" lang="en">Video Consultation</span></div>
                    
                </div>
            <!-- END TELEMED NOTIFICATOR -->

             <!-- PHS NOTIFICATIONS --->
             <!--<div id="main_notif_container" style="float:left; width:920px;  max-height: 220px;  overflow-y: auto; margin-left:30px; margin-bottom:30px; border:0px solid #cacaca; display:none;"></div>-->
                 <div id="main_notif_container" style="width: 920px; margin: auto; margin-bottom: 30px;"></div>
             <!-- END PHS NOTIFICATIONS --->

             <div style="float:left; height:440px; width:900px; margin: 30px auto; margin-top:-10px; margin-left:30px; margin-right:30px; padding:10px; border:1px solid #cacaca; font-size:0px; z-index: 1; position: relative; ">             
                <!-- Upper ROW  ************************************************** -->
                <div class="UpperRow" style="float:left; margin-top:-40px; width:855px; border:1px solid #cacaca; padding:10px; margin:10px; border-radius: 3px;">
             		<div style="margin-left:-10px; margin-top:-10px; margin-bottom:-10px; float:left; width:60px; height:260px; border-radius:0px;  border-top-left-radius: 2px; border-bottom-left-radius: 2px; background-color:#54bc00;">
             			<div style="width:180px;height:50px; position:relative; top:100px; left:-50px; -ms-transform:rotate(270deg); -moz-transform:rotate(270deg); -webkit-transform:rotate(270deg);-o-transform:rotate(270deg); color:white; font-size:24px;" lang="en">Health Passport</div>
	             	</div>
	             	
	             	<div style="float:left; width:; height:24px;  border:0px solid #cacaca; ">
		             	<div id="PHSLabelOrig" style="float:left; margin-left:23px; width:360px; height:22px;  border:0px solid #cacaca; text-align:center; font-size:18px; color: #54bc00;" lang="en">Personal Health Summary</div>
		             	<div id="MHRLabel" style="float:left; margin-left:20px; width:360px; height:22px;  border:0px solid #cacaca; text-align:center; font-size:18px; color: #54bc00;" lang="en">Medical History Reports</div>
	             	</div>	 	
            	   
	             	<div style="float:left; width:; height:;  border:0px solid #cacaca; ">
		             	<div style="float:left; width:; height:;  border:0px solid #cacaca; ">
			             	<canvas id="myCanvas" width="360" height="200" style="float:left; border:0px solid #cacaca; margin-left:20px; cursor: pointer; "></canvas>		
			             	<div id="ALTCanvas1" style="float:left; position:absolute; width:360px; height:200px;  font-size:10px; margin-left:20px; border:0px solid #cacaca; background-color:white; overflow:hidden; "></div>           
		             	</div>
		             	<canvas id="myCanvas2" width="360" height="200" style="float:left; border:0px solid #cacaca; margin-left:20px; cursor: pointer; "></canvas>
		            </div>	
					<button id="PHSLabel" class="find_doctor_button" style="float:left; display: block; background-color: rgb(82, 216, 89); width:80px; margin-top: 0px;height: 16px;font-size: 12px;line-height: 12px;" lang="en">See Graph</button>
					<button id="ButtonReview" class="find_doctor_button" style="float:left; margin-left:10px; display: block; background-color: rgb(34, 174, 255); width:80px; margin-top: 0px;height: 16px;font-size: 12px;line-height: 12px;" lang="en">Edit</button>
                    
                    
                    
                    <!-- Edit for Patient Detail Med ****************************** -->
                    <button id="editPatient" class="find_doctor_button" style="float:left; display: block; background-color: rgb(34, 174, 255); width:80px; margin-top: 0px;margin-left:300px;height: 16px;font-size: 12px;line-height: 12px;" lang="en">Edit</button>

				</div> 
				
                <!-- Bottom ROW  ************************************************** -->
                <div style="float:left; margin-top:-40px; width:854px; height:120px; border:1px solid #cacaca; padding:10px; margin:10px; border-radius: 3px;">
             		<div style="margin-left:-10px; margin-top:-10px; margin-bottom:-10px; float:left; width:60px; height:140px; border-radius:0px;  border-top-left-radius: 2px; border-bottom-left-radius: 2px; background-color:#22aeff;"></div>    
             			<div style="float:left; width:180px; height:50px; position:relative; top:-12px; left:-110px; -ms-transform:rotate(270deg); -moz-transform:rotate(270deg); -webkit-transform:rotate(270deg);-o-transform:rotate(270deg); color:white; font-size:24px;" lang="en">Doctors</div>
          			    
			 		<style>
			 			a.ButtonDrAct{
				 			float:left; 
				 			text-align:left; 
				 			margin-top:10px; 
				 			margin-right:30px;
				 			padding:10px; 
				 			width:90px; 
				 			height:80px; 
				 			color:#22aeff; 
				 			text-align:center;
			 			}
			 		</style>
			 		
			 			<div style="margin-top:-50px; margin-left:60px; height:120px; padding-right:30px; text-align:justify; display: inline-block;">
	             			<a id='SearchDirectory'  value="SearchDirectory" class="btn ButtonDrAct" title="Search for a doctor near you"> 
		                     	<img src="../../../../images/icons/mydoctors_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	<div style="line-height:25px; margin-left:6px;"><span lang="en">My Doctors</span></div>
	                     	</a>
	             			<a id='Talk'  value="Talk" class="btn ButtonDrAct"  title="Talk to a doctor by phone or by video chat" > 
		                     	<img src="../../../../images/icons/Talk_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	<div style="line-height:25px; margin-left:6px;"><span lang="en">Talk</span></div>
	                     	</a>
	             			<a id='Request'  value="Request" class="btn ButtonDrAct"  title="Request your medical records from your doctor"> 
		                     	<img src="../../../../images/icons/Request_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
		                     	<div style="line-height:25px; margin-left:6px;"><span lang="en">Request</span></div>
	                     	</a>
	             			<a id='Send2Doc'  value="Send2Doc" class="btn ButtonDrAct" title="Send your records to a doctor" style="position:relative;"> 
		                     	<img src="../../../../images/icons/Send_svg.png" style=" margin-top:-2px; width:50px; height:50px;">
                                <!--
                                <span id="BaloonSents" style="visibility:hidden; background-color: red; top:25px; left:80px; border: none; position:absolute;" class="H2MBaloon">2</span>
                                -->
		                     	<div style=" line-height:25px; margin-left:6px;"><span lang="en">Send</span></div>
	                     	</a>
	             			<a id='SetUp'  value="SetUp" class="btn ButtonDrAct" title="Configure your account settings"> 
		                     	<img src="../../../../images/icons/Configuration_svg.png" style="margin-top:-2px; width:50px; height:50px;">
		                     	<div style="line-height:25px; margin-left:6px;"><span lang="en">SetUp</span></div>
	                     	</a>
			 			</div>
			 			
 
				</div> 
				</div>
                
             </div>     
             <div  style="display:none; float:left; border:1px solid #cacaca; width:900px; height:420px; margin: 30px auto; margin-top:30px; margin-left:30px; margin-right:30px; padding:10px; ">
                <span class="label label-info" id="EtiTML" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">User&Doctor Communications Area</span>
                <ul id="myTab" class="nav nav-tabs tabs-main">
                <li class="active"><a href="#inbox" data-toggle="tab" id="newinbox" lang="en">InBox</a></li>
                <li><a href="#outbox" data-toggle="tab" id="newoutbox" lang="en">OutBox</a></li></ul>
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
            <?=$user->footer_copy;?>

           
             </div> 
         </div>
     </div>


				 		<img id="Admin" src="../../../../images/icons/Admin_svg.png" style="visibility:hidden;" >
					    <img id="History" src="../../../../images/icons/history_svg.png" style="visibility:hidden;" >
					    <img id="Medication" src="../../../../images/icons/medication_svg.png" style="visibility:hidden;" >
					    <img id="Immuno" src="../../../../images/icons/immuno_svg.png" style="visibility:hidden;" >
					    <img id="Family" src="../../../../images/icons/family_svg.png" style="visibility:hidden;" >
					    <img id="Habits" src="../../../../images/icons/habits_svg.png" style="visibility:hidden;" >
					
					    <img id="R1" src="../../../../images/icons/picture_svg.png"  style="visibility:hidden;">
					    <img id="R2" src="../../../../images/icons/beaker_svg.png" style="visibility:hidden;">
					    <img id="R3" src="../../../../images/icons/user-md_svg.png"  style="visibility:hidden;">
					    <img id="R4" src="../../../../images/icons/inbox_svg.png"  style="visibility:hidden;">
					    <img id="R5" src="../../../../images/icons/circle-blank_svg.png" style="visibility:hidden;">
					    <img id="R6" src="../../../../images/icons/list-alt_svg.png"  style="visibility:hidden;">
					    <img id="R7" src="../../../../images/icons/film_svg.png"  style="visibility:hidden;">
					    <img id="R8" src="../../../../images/icons/user_svg.png" style="visibility:hidden;">
					    <img id="R9" src="../../../../images/icons/dollar_svg.png"  style="visibility:hidden;">
					    <img id="R10" src="../../../../images/icons/question-sign_svg.png"  style="visibility:hidden;">
					    <img id="R11" src="../../../../images/icons/list-alt_svg.png" style="visibility:hidden;">
					    <img id="R12" src="../../../../images/icons/beaker_svg.png"  style="visibility:hidden;">


     <!--CONTENT MAIN END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../../../master_js/jquery.min.js"></script>
    <script src="../../../master_js/jquery-ui.min.js"></script>
    <script src="../../../master_js/jquery.easing.1.3.js"></script>
    <script src="../../../../build/js/intlTelInput.js"></script>
    <script src="../../../master_js/isValidNumber.js"></script>	
    <script src="../../../master_js/jquery.cookie.js"></script>

    <!-- Libraries for notifications -->
    <script src="../../../../realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<!--<script src="realtime-notifications/pusher.min.js"></script>
    <script src="realtime-notifications/PusherNotifier.js"></script>-->
    <script src="../../../master_js/socket.io-1.3.5.js"></script>
    <script src="../../../modules/push/push_client.js"></script>
	<link href="../../../../realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<script src="../../../../imageLens/jquery.imageLens.js" type="text/javascript"></script>
     <script src="../../../master_js/jquery-easy-ticker-master/jquery.easy-ticker.js"></script>      
    <script>
	  $(function() {
	    //var pusher = new Pusher('d869a07d8f17a76448ed');
	    //var channel_name=$('#MEDID').val();
		//var channel = pusher.subscribe(channel_name);
		//var notifier=new PusherNotifier(channel);
		
	  });
    </script>
    <!-- Libraries for notifications -->



    <script src="../../../../TypeWatch/jquery.typewatch.js"></script>
    <script type="text/javascript" src="../js/userdashboard.js"></script>
    <script type="text/javascript" src="../../../master_js/h2m_probePurchaseClass.js"></script>
    <script type="text/javascript" src="../../../master_js/h2m_notifications.js"></script>
    <script src="../../../master_js/bootstrap.min.js"></script>
    <script src="../../../master_js/bootstrap-datepicker.js"></script>
    <script src="../../../master_js/bootstrap-colorpicker.js"></script>
    <script src="../../../../js/google-code-prettify/prettify.js"></script>
   
    <script src="../../../master_js/jquery.flot.min.js"></script>
    <script src="../../../master_js/jquery.flot.pie.js"></script>
    <script src="../../../master_js/jquery.flot.orderBars.js"></script>
    <script src="../../../master_js/jquery.flot.resize.js"></script>
    <script src="../../../master_js/graphtable.js"></script>
    <script src="../../../master_js/fullcalendar.min.js"></script>
    <script src="../../../master_js/chosen.jquery.min.js"></script>
    <script src="../../../master_js/autoresize.jquery.min.js"></script>
    <script src="../../../master_js/jquery.tagsinput.min.js"></script>
    <script src="../../../master_js/jquery.autotab.js"></script>
    <script src="../../../master_js/elfinder/js/elfinder.min.js" charset="utf-8"></script>
	<script src="../../../master_js/tiny_mce/tiny_mce.js"></script>
    <script src="../../../master_js/validation/languages/jquery.validationEngine-en.js" charset="utf-8"></script>
	<script src="../../../master_js/validation/jquery.validationEngine.js" charset="utf-8"></script>
    <script src="../../../master_js/jquery.jgrowl_minimized.js"></script>
    <script src="../../../master_js/jquery.dataTables.min.js"></script>
    <script src="../../../master_js/jquery.mousewheel.js"></script>
    <script src="../../../master_js/jquery.jscrollpane.min.js"></script>
    <script src="../../../master_js/jquery.stepy.min.js"></script>
    <script src="../../../master_js/jquery.validate.min.js"></script>
    <script src="../../../master_js/raphael.2.1.0.min.js"></script>
    <script src="../../../master_js/justgage.1.0.1.min.js"></script>
	<script src="../../../master_js/glisse.js"></script>
    <script src="../../../master_js/timezones.js" type="text/javascript"></script>
    <script type="text/javascript" src="../../../master_js/tipped.js"></script>
    <script src="../../../master_js/doctor_search.js"></script> <!-- used for doctor search modal window -->
    <script src="../../../master_js/personal_doctor.js"></script> <!-- used for personal doctors -->
	<script src="../../../master_js/application.js"></script>
    <script src="../../../master_js/jquery-easy-ticker-master/jquery.easy-ticker.js"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script src="../../../master_js/moment-with-locales.js"></script>
    <script type="text/javascript" src="../../../master_js/imagesloaded.pkgd.min.js"></script>
    <script type="text/javascript" src="../../../master_js/H2M_Timeline.js"></script>
    <script src="../../../master_js/jquery.fittext.js"></script>
    <script src="../../../master_js/sweet-alert.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../../../../css/sweet-alert.css">	
<link rel="stylesheet" href="../../../../js/signature/assets/jquery.signaturepad.css">

    <script src="../../../master_js/h2m_phsnotifications.js"></script>
  <script src="../../../master_js/signature/jquery.signaturepad.js"></script>
  <script src="../../../master_js/signature/assets/json2.min.js"></script>
  <script src="../../../master_js/h2m_medicalrelease.js">
	  alert("loaded script");
</script>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">


	<script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>

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
