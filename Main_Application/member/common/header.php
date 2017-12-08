<div class="header" >
     	<input type="hidden" id="USERID" Value="<?php if(isset($user->mem_id)) echo $user->mem_id; ?>">	
        <input type="hidden" id="USER_TIMEZONE" value="<?php if(isset($user->member_timezone)) echo $user->member_timezone; ?>">
		<input type="hidden" id="USER_LOCATION" value="<?php if(isset($user->member_location)) echo $user->member_location; ?>">
    	<input type="hidden" id="MEDID" Value="<?php if(isset($user->both_id)) echo $user->both_id ?>">		
    	<input type="hidden" id="GROUPID" Value="<?php echo $user->doctor_group; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php if(isset($user->DoctorEmail)) echo $user->DoctorEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php if(isset($MedUserName)) echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php if(isset($MedUserSurname)) echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php if(isset($MedUserLogo)) echo $MedUserLogo; ?>">
        <input type="hidden" id="USERNAME" Value="<?php echo $user->member_first_name ?>">	
        <input type="hidden" id="USERSURNAME" Value="<?php echo $user->member_last_name ?>">	
        <input type="hidden" id="USERPHONE" Value="<?php echo $user->member_phone ?>">	
        <input type="hidden" id="CURRENTCALLINGDOCTOR" Value="<?php echo $user->member_current_calling_doctor ?>">	
        <input type="hidden" id="CURRENTCALLINGDOCTORNAME" Value="<?php echo $user->current_calling_doctor_name; ?>" />
		<input type="hidden" id="ORIGINAL_USER" value="<?php if(isset($user->original_user)) echo $user->original_user; ?>">
        <input type="hidden" id="DOMAIN" value="<?php echo $user->hardcode; ?>">
  		
        
        <a href="../../userdashboard/html/UserDashboard.php" class="logo"><h1>Health2me</h1></a>
               <style>
                .addit_button{
                    background: transparent;
                    <?php if($user->member_privilege == 'CATA') { ?>
                    color: Red;
                    border: 1px solid Red;
                    <?php } else { ?>
                    color: whitesmoke;
                    border: 1px solid #E5E5E5;
                    <?php } ?>
                    text-shadow: none;         
                    font-size: 12px !important;
                    height: 20px;
                    line-height: 12px;      
                }
                .addit_caret{
                    <?php if($user->member_privilege == 'CATA') { ?>
                    border-top: 4px solid DarkRed;
                    <?php } else { ?>
                    border-top: 4px solid whitesmoke;
                    <?php } ?>
                    margin-top: 3px !important;
                    margin-left: 5px !important;
                }      
                <?php if($user->member_privilege == 'CATA') { ?>
                #lang1:hover {
                    color: DarkRed !important;
                }
                <?php } ?>
                .dropdown-menu > li > a {
                    color: Red;
                }
                .btn:hover { 
                    color: DarkRed;
                }
                .dropdown-menu {
                    border: 1px solid Red;
                }
                <?php if($_SESSION['CustomLook'] == "COL") { ?>
                .addit_button{
                    color: #048F90;   
                    border: 1px solid #048F90;
                }
                .addit_caret{
                    border-top: 4px solid #048F90;
                }
                <?php } ?>

               </style>
               <div style="margin-top:11px;float:left; margin-left:50px;" class="btn-group">
                      <button id="lang1" type="button" class="btn btn-default dropdown-toggle addit_button" data-toggle="dropdown">
                        Language <span class="caret addit_caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#en" onclick="setCookie('lang', 'en', 30); return false;">English</a></li>
                        <li><a href="#sp" onclick="setCookie('lang', 'th', 30); return false;">Espa&ntilde;ol</a></li>
                        <li><a href="#tu" onclick="setCookie('lang', 'tu', 30); return false;">T&uuml;rk&ccedil;e</a></li>
                        <li><a href="#hi" onclick="setCookie('lang', 'hi', 30); return false;">हिंदी</a></li>
                      </ul>
                </div>
               <!-- End of new code by Pallab-->
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
    
                
              
           
        <div class="pull-right">
			
            <?php include '../../../member/common/message_center.php'; ?>
            <!--Button User Start-->
            <div class="btn-group pull-right" >
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
                <?php if($user->member_privilege == 'CATA' || strpos($user->doctor_service, 'CATA') !== false){ ?>
                <span class="name-user"><strong lang="en" style="color:DarkRed;">Welcome</strong> <?php echo $user->member_first_name.' '.$user->member_last_name; ?></span>
                <?php }
                    $hash = md5( strtolower( trim( $user->doctor_email ) ) );
                    $avat = '../../../../identicon.php?size=29&hash='.$hash;
                ?>	
                <span class="avatar" style="background-color:WHITE;"><img src="../../../../<?php echo $avat; ?>" alt="" ></span> 
                <span class="caret"></span>
            </a>
            <div class="dropdown-menu" id="prof_dropdown" style="background-color:transparent; border:none; -webkit-box-shadow:none; box-shadow:none;">
                <div class="item_m"><span class="caret"></span></div>
                    <ul class="clear_ul" >
                        <li>
                            <?php 
                                if ($user->session_privilege==1)
                                    echo '<a href="../userdashboard/html/UserDashboard.php" lang="en">';
                                else if($user->session_privilege==2)
                                    echo '<a href="../patientdetails/html/PatientDetails.php" lang="en">';
                            ?>
                        <i class="icon-globe"></i> Home</a></li>
                        <?php
                            // If this is a family subscription, load all of the users in the drop down menu
                            if($plan == 'FAMILY' && count($user->user_accts) > 0 && ($user->original_access == 'Owner' || $user->original_access == 'Admin'))
                            {
                                $count = count($user->user_accts);
                                echo '<div id="family_members_dropdown">';
                                for($i = 0; $i < $count; $i++)
                                {
                                    echo '<li><a href="#" class="change_user_dropdown_button" id="user_'.$user->user_accts[$i]['ID'].'_'.$user->user_accts[$i]['email'].'_'.$user->user_accts[$i]['age'].'_'.$user->user_accts[$i]['grant_access'].'_dropdown" lang="en">';
                                    echo '<i class="icon-user"></i> '.$user->user_accts[$i]['Name'].'</a></li>';
                                }
                                echo '</div>';
                            }
                        ?>
                        
                        <li><a href="../../../ajax/logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
                    </ul>
                </div>
            </div>
            <!--Button User END-->  
          
        </div>
    </div>