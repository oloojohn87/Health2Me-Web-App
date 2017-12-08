<div class="header" >
     	<input type="hidden" id="USERDID" Value="<?php if(isset($user->mem_id)) echo $user->mem_id; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $user->med_id; ?>">	
    	<input type="hidden" id="GROUPID" Value="<?php echo $user->doctor_group; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $user->doctor_email; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $user->doctor_first_name; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $user->doctor_last_name; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php if(isset($MedUserLogo)) echo $MedUserLogo; ?>">	
  		<input type="hidden" id="TELEMED_TYPE" Value="<?php echo $user->telemed_type; ?>">
        <input type="hidden" id="IN_CONSULTATION" Value="<?php echo $user->in_consultation; ?>">
		<input type="hidden" id="CONSULTATION_DATE" value="<?php echo $user->doctor_consultation_date ?>">
        <?php if(strpos($user->doctor_service, 'CATA') !== false) { ?>
            <a href="../../maindashboard/html/MainDashboardCATA.php" class="logo">
        <?php } else { ?> 
            <a href="../../maindashboard/html/MainDashboard.php" class="logo">
        <?php } ?>
            <h1>Health2me</h1></a>
		
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
                    color: Red;
                    text-shadow: none;
                    border: 1px solid Red;
                    font-size: 12px !important;
                    height: 20px;
                    line-height: 12px;      
                }
                .addit_caret{
                   border-top: 4px solid DarkRed;
                   margin-top: 3px !important;
                   margin-left: 5px !important;
                }
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
			
            <?php include 'messages/message_center.php'; ?>
            <!--Button User Start-->
            <div class="btn-group pull-right" >
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
                <?php if($user->member_privilege == 'CATA' || strpos($user->doctor_service, 'CATA') !== false){ ?>
                <span class="name-user"><strong lang="en" style="color:DarkRed;">Welcome</strong> <?php echo $user->doctor_first_name.' '.$user->doctor_last_name; ?></span>
                <?php } else { ?>                
                <span class="name-user"><strong lang="en" style="color:DarkRed;">Welcome</strong> Dr. <?php echo $user->doctor_first_name.' '.$user->doctor_last_name; ?></span> 
                <?php } ?>
                <?php 
                    $hash = md5( strtolower( trim( $user->doctor_email ) ) );
                    $avat = '../../../identicon.php?size=29&hash='.$hash;
                ?>	
                <span class="avatar" style="background-color:WHITE;"><img src="../../../../<?php echo $avat; ?>" alt="" ></span> 
                <span class="caret"></span>
            </a>
            <div class="dropdown-menu" id="prof_dropdown" style="background-color:transparent; border:none; -webkit-box-shadow:none; box-shadow:none;">
                <div class="item_m"><span class="caret"></span></div>
                    <ul class="clear_ul" >
                        <li>
                            <?php 
                                if ($user->doctor_privilege==1 or $user->doctor_privilege==4) echo '<a href="../../member/userdashboard/html/UserDashboard.php" lang="en">';
                                else if($user->doctor_privilege==2) echo '<a href="patients.php" lang="en">';
                            ?>
                        <i class="icon-globe"></i> Home</a></li>
                    
                        <li><a href="../configuration/html/ConfigurationCATA.php" lang="en"><i class="icon-cog"></i> Settings</a></li>
                        <li><a href="../../ajax/logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
                    </ul>
                </div>
            </div>
            <!--Button User END-->  
          
        </div>
    </div>