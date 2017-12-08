<?php
session_start();

if ($_SESSION['CustomLook']=='COL') {
    header('Location:MainDashboardHTI.php'); 
    echo "<html></html>";  // - Tell the browser there the page is done
    flush();               // - Make sure all buffers are flushed
    ob_flush();            // - Make sure all buffers are flushed
    exit;                  // - Prevent any more output from messing up the redirect
}    

include("userConstructClass.php");
$user = new userConstructClass();

$user->pageLinks('MainDashboard.php');
//This sets telemed variables to be used for telemed sessions...
$user->telemedSetter();



if(!isset($_SESSION['MEDID'])){
	$url = 'index.html';
	header('Location: $url'); 
}

?>
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

    
    <!--Adobe Edge Runtime-->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <script type="text/javascript" charset="utf-8" src="TutorialBox/AnimationA2_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-43 { visibility:hidden; }
    </style>
    <script type="text/javascript" charset="utf-8" src="TutorialBox/AnimationA3_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-44 { visibility:hidden; }
    </style>
    <script type="text/javascript" charset="utf-8" src="TutorialBox/AnimationA4_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-45 { visibility:hidden; }
    </style>
    <script type="text/javascript" charset="utf-8" src="TutorialBox/AnimationA5_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-46 { visibility:hidden; }
    </style>
    

    <style>
        .edgeLoad-EDGE-47 { visibility:hidden; }
    </style>
<!--Adobe Edge Runtime End-->

</head>
<body>
    <style>
        html, body { height: 100%; }
        body .modal {
          width: 70%;
          left: 15%;
          margin-left:auto;
          margin-right:auto; 
        }
        .modal-body {
            height:1000px;
        }
        .InfoRow{
            border:solid 1px #cacaca; 
            height:20px; 
            padding:5px;
            margin-left:5px;
            margin-right:5px;
            margin-top:-1px;	
        }
        .PatName{
            margin-left:10px;
            font-size:14px;
            color:#54bc00;	
        }
        body .ui-autocomplete {
          background-color:white;
          color:#22aeff;
          /* font-family to all */
        }
    </style>
    
    
    <!--<div id="notification_bar" style="position: fixed;text-align:center;width: 100%; height: 44px; color: white; z-index: 2; background-color: rgb(119, 190, 247);"><div id="notification_bar_msg" style="display:inline-block;margin-right:20px;height:40px;vertical-align: middle;">We use cookies to improve your experience. By your continued use of this site you accept such use. </div>
       <div id="notification_bar_close" style="display:inline-block;margin-top: 2px;margin-left:40px">
           <i class="icon-remove-circle icon-3x"></i>
   </div></div>-->
      <div id="notification_bar"></div>
    
    <!-- MODAL VIEW FOR DECK -->
    <div id="modalContents" style="display:none; text-align:center; padding:20px;">
        <div style="color:#22aeff; font-size:14px; text-align: right; width: 73%; height: 10px; float: left; padding-bottom: 10px;" lang="en"> Patient's Deck for Dr. <?php echo $MedUserName.' '.$MedUserSurname; ?>'<?php if($MedUserSurname[(strlen($MedUserSurname) - 1)] != 's') echo 's'; ?> Office</div>
        <div style="text-align: right; width: 27%; float: left; color:#474747;" id="deck_date">
            <a href="#" class="icon-arrow-left" style="color: #22aeff" id="deck_date_left"></a>
            <span id="deck_date_val">Mar. 5</span>
            <a href="#" class="icon-arrow-right" style="color: #22aeff" id="deck_date_right"></a>
        </div>
        <div id="DeckContainer" style="border:solid 1px #cacaca; height:300px; margin-top:30px; padding-top:5px; overflow:auto;">
        </div>
        <div style="border:solid 1px #cacaca; height:70px; margin-top:20px; padding:10px;">
            <input id="field_id" type="text" style="width: 40px; margin-top:5px; visibility:hidden;"><input id="PatientSBox" type="text" style="width: 200px; float:left;" placeholder="Enter Patient's Name"><span id="results_count"></span>
            <div style="width:100%;"></div>
            <div style="float:left; border:solid 1px #cacaca; padding:3px; margin-top: -10px; width: 60px; height: 30px; ">
                <i id="IconSch" data-toggle="tooltip" class="icon-time icon-2x" style="float:left; margin-left:3px; color:#cacaca;" title="Title"></i> 
                <i id="IconSur" data-toggle="tooltip" class="icon-pencil icon-2x" style="float:left; margin-left:3px; color:#cacaca;" title="Title"></i> 
            </div>
            <div style="float:left; border:solid 1px #cacaca; padding:3px; margin-top: -10px; width: 30px; height: 30px; margin-left:10px; ">
                <i id="IconAle" data-toggle="tooltip" class="icon-check-sign icon-2x" style="float:left; margin-left:3px; color:#cacaca;" title="Title"></i> 
            </div>
            <input id="TimePat" type="text" style="width: 70px;float: left;margin-left: 10px; margin-top: -5px; text-align:center;" placeholder="Time" />
            <a id="ButtonAddDeck" class="btn" style="height: 50px; width:30px; color:#22aeff; float:right; margin-top:-40px;">Add</a>
        </div>
    </div>
    <!-- END MODAL VIEW FOR DECK -->
    
    <div class="loader_spinner"></div>

    <input type="hidden" id="NombreEnt" value="<?php if(isset($user->member_first_name)) echo $user->member_first_name; ?>">
    <input type="hidden" id="PasswordEnt" value="<?php if(isset($PasswordEnt)) echo $PasswordEnt; ?>">
    <input type="hidden" id="UserHidden">

	<!-- HEADER VIEW FOR MAIN TOOLBAR -->
	<div class="header" >
     	<input type="hidden" id="USERDID" Value="<?php if(isset($user->mem_id)) echo $user->mem_id; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $user->med_id; ?>">	
    	<input type="hidden" id="GROUPID" Value="<?php echo $user->doctor_group; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $user->doctor_email; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $user->doctor_first_name; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $user->doctor_last_name; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
  		<input type="hidden" id="TELEMED_TYPE" Value="<?php echo $user->telemed_type; ?>">
        <input type="hidden" id="IN_CONSULTATION" Value="<?php echo $user->in_consultation; ?>">
		<input type="hidden" id="CONSULTATION_DATE" value="<?php echo $user->doctor_consultation_date ?>">
        
        <a href="index.html" class="logo"><h1>Health2me</h1></a>
		
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
            
            <!--div class="notifications-head"-->
            <!-- MESSAGES PATIENTS -->
            <!--div class="btn-group m_left hide-mobile">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="patient_messages_button">
                    <span class="notification" style="display: none;">531</span>
                    <span class="triangle-1" style="display: none;"></span>
                    <i class="icon-user" style="-webkit-filter: hue-rotate(240deg) saturate(8.9); "></i>
                    <span class="caret"></span>
                </a>
                <div class="dropdown-menu">
                
                    <span class="triangle-2"></span>
                    <div class="ichat" id="patient_messages">
                        <div class="ichat-messages">
                            <div class="ichat-title">
                                <div class="pull-left" lang="en">Unread Member Messages</div>
                                <div class="clear"></div>
                            </div>
                            <div style="text-align: center; align: center" id="patient_messages_indicator">
                                <i class="icon-spinner icon-spin " style="margin-top: 20px; margin-bottom: 20px; margin-right: auto; margin-left: auto; color:#22aeff;"></i>
                            </div>
                            
                            <div class="imessage" id="patient_mes_1" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="patient_mes_link_1" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="patient_mes_2" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="patient_mes_link_2" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="patient_mes_3" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="patient_mes_link_3" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="patient_mes_4" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="patient_mes_link_4" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="patient_mes_5" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                               <div class="idelete"><a id="patient_mes_link_5" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="ichat-link" style="text-align: center;">
                                <p id="patient_more_messages" lang="en">0 More Messages</p> 
                                <div class="clear"></div>
                            </div>
                        
                        </div>
                        <a href="Messages.php" class="iview" style="width: 100%" lang="en">View all</a>
                   
                    </div>
                
                </div>
            </div>
            <!-- END MESSAGES PATIENTS -->
            
            <!-- MESSAGES DOCTORS -->
            <!--div class="btn-group pull-left hide-mobile">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="doctor_messages_button">
                    <span class="notification" style="display: none;">77</span>
                    <span class="triangle-1" style="display: none;"></span>
                    <i class="icon-user-md" style="-webkit-filter: hue-rotate(340deg) saturate(8.9);" ></i>
                    <span class="caret"></span>
                </a>
                <div class="dropdown-menu">
                
                    <span class="triangle-2"></span>
                    <div class="ichat" id="doctor_messages">
                        <div class="ichat-messages">
                            <div class="ichat-title">
                                <div class="pull-left" lang="en">Unread Doctor Messages</div>
                                <div class="clear"></div>
                            </div>
                            
                            <div style="text-align: center; align: center" id="doctor_messages_indicator">
                                <i class="icon-spinner icon-spin " style="margin-top: 20px; margin-bottom: 20px; margin-right: auto; margin-left: auto; color:#22aeff;"></i>
                            </div>
                            
                            <div class="imessage" id="doctor_mes_1" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="doctor_mes_link_1" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="doctor_mes_2" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="doctor_mes_link_2" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="doctor_mes_3" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="doctor_mes_link_3" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="doctor_mes_4" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="doctor_mes_link_4" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="imessage" id="doctor_mes_5" style="display: none;">
                                <div class="iavatar"></div>
                                <div class="imes">
                                    <div class="iauthor"></div>
                                    <div class="itext" style="width: 140px; white-space: normal;"></div>
                                </div>
                                <div class="idelete"><a id="doctor_mes_link_5" href="#"><span><i class="icon-arrow-right"></i></span></a></div>
                                <div class="clear"></div>
                            </div>
                            <div class="ichat-link" style="text-align: center;">
                                <p id="doctor_more_messages" lang="en">0 More Messages</p> 
                                <div class="clear"></div>
                            </div>
                        
                        </div>
                        <a href="Messages.php?isDoctors=1" class="iview" style="width: 100%" lang="en">View all</a>
                   
                    </div>
                
                </div>
            </div>
            <!-- END MESSAGES DOCTORS -->
            <!--/div-->
            <?php include 'message_center.php'; ?>
            <!--Button User Start-->
            <div class="btn-group pull-right" >
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
                <span class="name-user"><strong lang="en">Welcome</strong> Dr. <?php echo $user->doctor_first_name.' '.$user->doctor_last_name; ?></span> 
                <?php 
                    $hash = md5( strtolower( trim( $user->doctor_email ) ) );
                    $avat = 'identicon.php?size=29&hash='.$hash;
                ?>	
                <span class="avatar" style="background-color:WHITE;"><img src="<?php echo $avat; ?>" alt="" ></span> 
                <span class="caret"></span>
            </a>
            <div class="dropdown-menu" id="prof_dropdown" style="background-color:transparent; border:none; -webkit-box-shadow:none; box-shadow:none;">
                <div class="item_m"><span class="caret"></span></div>
                    <ul class="clear_ul" >
                        <li>
                            <?php 
                                if ($user->doctor_privilege==1 or $user->doctor_privilege==4) echo '<a href="MainDashboard.php" lang="en">';
                                else if($user->doctor_privilege==2) echo '<a href="patients.php" lang="en">';
                            ?>
                        <i class="icon-globe"></i> Home</a></li>
                    
                        <li><a href="medicalConfiguration.php" lang="en"><i class="icon-cog"></i> Settings</a></li>
                        <li><a href="logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
                    </ul>
                </div>
            </div>
            <!--Button User END-->  
          
        </div>
    </div>
    <!-- END HEADER VIEW FOR MAIN TOOLBAR -->
    
    
    <div id="content" style="padding-left:0px;">

    <!-- Button trigger modal -->
    <button id="LaunchModal" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" style="display:none;" lang="en">Launch demo modal</button>
     	    
	 <!-- MAIN TOOLBAR -->
     <div class="speedbar">
        <div class="speedbar-content" style="position:relative;">
            <ul class="menu-speedbar">
                <li><a href="MainDashboard.php" class="act_link" lang="en">Home</a></li>
                
                <?php
                                    
                $arr=$user->checkAccessPage("dashboard.php");
                $arr_d=json_decode($arr, true);

                if((count($arr_d['items'])>0)&&($arr_d['items'][0]['accessid']==1)){ 
                
                    echo '<li><a lang="en" href="MainDashboard.php"  lang="en">MainDashboard</a></li>';
                }
                ?>
                <!--li><a href="patients.php"  lang="en">Members</a></li-->
                <?php 
                    if ($user->doctor_privilege==1 or $user->doctor_privilege==4)
                    {
                        echo '<li><a href="medicalConnections.php"  lang="en">Doctor Connections</a></li>';
                        echo '<li><a href="PatientNetwork.php"  lang="en">Member Network</a></li>';
                    }
                ?>
                <li><a href="medicalConfiguration.php" lang="en">Configuration</a></li>
                <li><a href="logout.php" style="color:yellow;" lang="en">Sign Out</a></li>
            </ul>

     
        </div>
    </div>
    <!-- END MAIN TOOLBAR -->
        
    <style>
            
        div.H2MBox{
            float:left; 
            margin-left:30px; 
            width:250px; 
            height:400px; 
            border:1px solid #b0b0b0; 
            background-color:white;
        }
        div.H2MBox:hover 
            .H2MTextFooter{
                background-color:#e8e8e8;
                color:black;       
        }
        div.H2MBox:hover 
          .H2MTitleA{
                color:black;       
        }
            
        div.H2MTitle{
            width:100%; 
            height:20%; 
            display:table;
        }
    
        div.H2MTitleA{
            padding:10px; 
            font-size:30px; 
            text-align:left; 
            color:white; 
            display:table-cell; 
            vertical-align:middle;
            padding-left:30px;
            width:50%;    
        }
    
            
        div.H2MTitleB{
            padding:10px; 
            font-size:30px; 
            text-align:right; 
            color:white; 
            display:table-cell; 
            vertical-align:middle;
        }
    
        div.H2MSet1{
            width:100%; 
            height:15%;
            color:inherit;    
        }
        
        div.H2MSet1A{
            opacity:1.0; 
            padding:10px; 
            padding-top:20px; 
            font-size:14px; 
            text-align:left; 
            color:inherit;
            display:table-cell; 
            vertical-align:middle;
        }
            
        span.H2MBigNumber{
            font-size:20px; 
            margin-left:10px; 
            margin-right:-10px;
            color:inherit;
            font-weight:bold;    
        }
            
        div.H2MTextContent{
            opacity:1.0; 
            padding:10px; 
            padding-top:20px; 
            font-size:16px; 
            text-align:left; 
            color:grey; 
            display:table-cell; 
            vertical-align:middle;
        }
    
        div.H2MFooter{
            display:table; 
            width:100%; 
            height:20%; 
        }
    
    
        div.H2MTextFooter{
            padding:10px; 
            font-size:25px; 
            font-style:italic; 
            font-weight:bold;
            text-align:center; 
            color:grey; 
            display:table-cell; 
            vertical-align:middle;
        }
    </style>
    <input type="hidden" id="Privilege" value="<?php echo $user->doctor_privilege; ?>">
        
    <!-- MAIN CONTENT -->
     <div class="content">
    <!-- MAIN GRID -->
     <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">
         <!-- PART A -->
         <div class="grid" class="grid span4" style="height:60px; margin:0px auto; margin-top:-20px; margin-left:10px; margin-right:10px; padding:20px;">
            <div id="DivAv" style="float:left; width:60px; height:100%; ">
                <img src="<?php echo $avat; ?>" style="width:50px; margin:0px; float:left; font-size:18px;  border:1px solid #b0b0b0;"/>
            </div>   
            <div style="float:left; width:360px; height:100%; ">
            <div id="NombreComp" style="width:100%; color: rgba(34,174,255,1); font: bold 22px Arial, Helvetica, sans-serif; cursor: auto;"><?php echo $user->doctor_title.' '.$user->doctor_first_name;?> <?php echo $user->doctor_last_name;?></div>
                <span id="NombreComp" style="color: rgba(84,188,0,1); font: bold 16px Arial, Helvetica, sans-serif; cursor: auto; margin-top:-5px;"><span lang="en">CENTER</span>:  <?php if ($user->doctor_group_name<' ') echo 'single license'; else echo $user->doctor_group_name ;?></span>
                <div style="width:100%; margin-top:5px; "></div>
                <?php 
                if ($user->doctor_privilege==1 or $user->doctor_privilege==4)
                {
                    echo '<div style="float:left; width:150px; height:20px; text-align:center; border:1px solid darkgrey; border-radius:5px;display:table;margin:0px;background-color: silver;">';
                    echo '<span id="TypeAccount" style="color: white; font: bold 12px Arial, Helvetica, sans-serif; cursor: auto; display:table-cell; vertical-align:middle; padding-top:1px;" lang="en">PREMIUM ACCOUNT</span>';
                    echo '</div>';
                    $VoidLink = '';
                }
                ?>
                <?php if ($user->doctor_privilege==2)
                {
                    echo '<div style="float:left;  width:150px; height:20px; text-align:center; border:1px solid goldenrod; border-radius:5px;display:table;margin:0px;background-color: gold; margin-right:20px;">';
                    echo '<span id="TypeAccount" style="color: grey; font: bold 12px Arial, Helvetica, sans-serif; cursor: auto; display:table-cell; vertical-align:middle; padding-top:1px;" lang="en">FREE ACCOUNT</span>';
                    echo '</div>';
                    echo '<div style=" float:left; margin-left:20px;  width:100px; height:20px; text-align:center; border:1px solid goldenrod; border-radius:5px; display:table; margin:0px;background-color: gold;">';
                    echo '<span id="TypeAccount" style="color: grey; font: bold 12px Arial, Helvetica, sans-serif; cursor: auto; display:table-cell; vertical-align:middle; padding-top:1px;" lang="en">Upgrade</span>';
                    echo '</div>';
                    $VoidLink = '1';
                }
                ?>

            </div>    
            <!--<div style="float:left; width:200px; height:100%;">
                <span id="NumMsgUser<?php echo $user->visable_user?><?php echo $VoidLink?>" style="color:rgba(105,188,0,<?php echo $user->opacity_user;?>); margin-left:10px;" title="Doctors uploaded information in the past 30 days"><i class="icon-envelope icon-3x" style="cursor: pointer;" id="PatientMessagesButton"></i></span>
                <span style="visibility:<?php echo $user->visable_user?>; background-color:black;" class="H2MBaloon" ><?php echo $user->new_messages_to_doctor;?></span>
                
                <span  id="NumMsgDr<?php echo $user->visable_doctor?><?php echo $VoidLink?>" style="color:rgba(90,170,255,<?php echo $user->opacity_doctor;?>); margin-left:10px;" title="Doctors uploaded information in the past 30 days"><i class="icon-envelope icon-3x" style="cursor: pointer;" id="DoctorMessagesButton"></i></span>
                <span style="visibility:<? echo $user->visable_doctor?>; background-color:red;" class="H2MBaloon" ><?php echo $user->total_messages_to_doctor;?></span>

                
							
            </div>-->
            <div style="float:left; width:300px; height:100%; ">
                <style>
                    div.H2MExBox{
                        width:200px; 
                        height:50px; 
                        border:1px solid #cacaca; 
                        border-radius:5px; 
                        display:table;
                        margin:10px;
                        background-color: rgb(34,174,255);
                        opacity: 0.1;
                    }
                    
                    div.H2MInText{
                        padding:0px; 
                        font-size:16px; 
                        text-align:left; 
                        color:grey; 
                        display:table-cell; 
                        vertical-align:middle;
                        padding-left:30px;
                        width:50%;  
                          
                        }
                    
                    div.H2MTray{
                        width:300px; 
                        height:50px; 
                        border:1px solid #cacaca; 
                        border-radius:5px; 
                        display:table;
                        margin:0px;
                        background-color: #F8F8F8;
                     }
                    
                    div.H2MTSCont{
                        width:300px;
                        overflow:hidden;
                    }
                    
                    div.H2MTrayScroll{
                        width:1000px;
                    }    
                    
                    tr.MsgRow:hover {
                        background-color: #f4f4f4;
                        border: 3px solid #cacaca;
                    }	         
                    
                    td.RightZone:hover {
                        background-color: black;
                        border: 1px solid black;
                        cursor:pointer;
                    }	     
                    tbody.rActivity {
                        max-height: 495px;
                        overflow: auto;
                        display:inline-block;
                    }
                    
                </style>
            <!-- NOTIFICATION TRAY -->
            <!--<div class="H2MTray">
                <div class="H2MTSCont">
                    <div class="H2MTrayScroll">
                        <div class="H2MInText" style="width:270px;">
                            <div style="width:40%; float:right; margin-top:10px; text-align:right; padding-right:20px;"><i class="icon-bullhorn icon-2x"></i></div>
                            <span style="font-size:10px;" lang="en">H2M messaging system</span>
                            <div style="width:50%; padding-top:-10px;"></div>
                            <span style="" lang="en">Notification tray</span>
                        </div>
                        <div id="Notif1" class="H2MInText" style="width:270px; color:#22aeff;">
                            <div id="IconText1" style="width:20%; float:right; margin-top:10px; text-align:right; padding-right:20px; "><i class="icon-folder-open-alt icon-2x"></i></div>
                            <span id="SubText1" style="font-size:10px;"> </span>
                            <div style="width:70%; padding-top:-10px; "></div>
                            <a id="MainText1" style=""><span id="" style="text-decoration:none;"></span></a>
                        </div>
                    </div>
                </div>
            </div>-->
            <!-- END NOTIFICATION TRAY -->

        </div>
        <style>
            .myPsClass {
                font-size:14px;
                color:blue;
            }
            .myPsClass:active {
                font-size:14px;
                color:green;
            }
            .myPsClass:hover {
                font-size:14px;
                color:grey;
            }
        </style>
             
        
        <a id="BotonRef" class="btn" title="Billing" style="color:black; margin-right:20px;  float:right; visibility:hidden;" lang="en"><i class="icon-plus"></i>Members In</a>
        
    </div>
    <!-- END PART A -->
         
         <!-- PART F -->
   
         
    <div class="grid" class="grid span4" style="margin:0px auto; margin-top: 10px; margin-left:10px; margin-right:10px; padding:20px; padding-top: 10px; height: 30px; display: none; background-color: #54bc00;" id="part_f">
        <div style="margin-top:10px;"><div style="width: 78%; height: 30px; margin-top: -6px; float: right;"><a id="phone_consultation_connect" href="javascript:void(0)" class="btn" title="Acknowledge" style="width:18%; color: green; margin-bottom: 2px; float: right; padding-left: -15px;" lang="en">View Records</a></a><p id="phone_consultation_text" style="color: #FFF; height: 10px; font-size: 14px; margin-top: 4px;" lang="en">You are on a phone consultation with member <?php echo $telemed_name; ?>.</p></div><input type="hidden" id="phone_consultation_id" value="<?php echo $telemed; ?>" /><input type="hidden" id="phone_consultation_name" value="<?php echo $telemed_name; ?>" /><span class="label label-info" id="EtiTML" style="background-color:#FFF; margin:20px; margin-left:0px; margin-bottom:0px; font-size:16px; text-shadow:none; text-decoration:none; color:#54bc00;" lang="en">Phone Consultation</span></div>

    </div>
    <!-- END PART F -->
         
    <!-- PART E -->
    <div class="grid" class="grid span4" style="margin:0px auto; margin-top: 10px; margin-left:10px; margin-right:10px; padding:20px; padding-top: 10px; height: 30px; display: none; background-color: #54bc00;" id="part_e">
        <div style="margin-top:10px;"><div style="width: 78%; height: 30px; margin-top: -6px; float: right;"><a id="telemed_deny_button" href="javascript:void(0)" class="btn" title="Acknowledge" style="width:2%; color: red; margin-bottom: 2px;margin-left: 2%; float: right; padding-left: -15px;"><i id="' + items[i].ID + '" class="icon-remove"></i></a><a id="telemed_connect_button" href="javascript:void(0)" class="btn" title="Acknowledge" style="width:2%; color: green; margin-bottom: 2px; float: right; padding-left: -15px;"><i id="' + items[i].ID + '" class="icon-facetime-video"></i></a><p id="video_consultation_text" style="color: #FFF; height: 10px; font-size: 14px; margin-top: -5px;" lang="en">Member Jane Doe is calling you for a video consultation </p></div><input type="hidden" id="video_consultation_id" value="<?php echo $telemed; ?>" /><input type="hidden" id="video_consultation_name" value="<?php echo $telemed_name; ?>" /><span class="label label-info" id="EtiTML" style="background-color:#FFF; margin:20px; margin-left:0px; margin-bottom:0px; font-size:16px; text-shadow:none; text-decoration:none; color:#54bc00;" lang="en">Video Consultation</span></div>
        
    </div>
    <!-- END PART E -->
         
    <!-- PART D -->
    <div class="grid" class="grid span4" style="margin:0px auto; margin-top: 10px; margin-left:10px; margin-right:10px; padding:20px; padding-top: 10px; display: block;" id="part_d">
        <div style="margin-top:10px; margin-bottom: 20px; margin-left: 1%;"><span class="label label-info" id="EtiTML" style="background-color:#22aeff; margin:10px; margin-left:0px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">Notifications</span></div>
        <!--<table style="width: 100%; background-color: #FFFFFF;" id="pending_referrals">
        </table>
        <style>
            .notification_button{
                width: 15%; 
                height: 40px; 
                float: right; 
                outline: none; 
                border: 0px solid #FFF; 
                background-color: #BABABA; 
                color: #FFF; 
                font-size: 16px;
            }
            .notification_button:hover{
                background-color:  #AAA;
            }
            #notifications a{
                color: inherit;
                text-decoration: none;
                
            }
        </style>-->
        <div id="notifications"></div>
        
    </div>
    <!-- END PART D -->
         
      <!-- Start of Pending Review-->
    <div class="grid" class="grid span4" style="margin:0px auto; margin-top: 10px; margin-left:10px; margin-right:10px; padding:20px; padding-top: 10px; display: none;" id="pendingReview">
        <div style="margin-top:10px;"><span class="label label-info" id="EtiTML" style="background-color:#22aeff; margin:10px; margin-left:0px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">Pending Review</span></div>
        <table style="width: 100%; background-color: #FFFFFF;" id="pending_review">
        </table>
    </div>
    <!-- End of Pending Review -->   
         
    <!-- Start of Pending Appointments-->
    <div class="grid" class="grid span4" style="margin:0px auto; margin-top: 10px; margin-left:10px; margin-right:10px; padding:20px; padding-top: 10px; display: none;" id="AppointmentNotification">
        <div style="margin-top:10px;"><span class="label label-info" id="EtiTML" style="background-color:#22aeff; margin:10px; margin-left:0px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">New Appointments</span></div>
        <table style="width: 100%; background-color: #FFFFFF;" id="appointment_notification_table">

            <tr style="border-bottom:1px solid #cacaca; height:20px;"><td style="width: 80%; "></td><td style="width: 80%;" ></td></tr>
            <tr style="border-bottom:1px solid #cacaca; border-top:1px solid #cacaca; height:40px;">

                <td style="width: 80%; padding:6px; ">
                    <div style="font-size:15px; width:100%;">
                        <span style="color: #54bc00;">John Smith</span>
                        <span style="color: #494949;"> on </span>
                        <span style="color: #22aeff;"> Jan. 17, 2015 11:15 am</span>
                    </div>
                </td>

                <td style="width: 20%; padding:6px; ">
                    <button id="pending_appointment_ack"  class="btn" title="Review" style="width:65%; color:grey; margin-bottom: 2px;"><i class="icon-remove"></i> Dismiss</button>
                </td>
            </tr>
     
        </table>
    </div>
    <!-- End of Pending Appointments -->  
         
         
    <!-- PART B -->  
    <div id="StatsNew" style="width:938px; padding:20px; margin:10px; border:1px solid #cacaca; display:table; border-radius:5px; overflow:auto;"><center>
        <div style="float:left; margin-right:10px; min-width: 370px;">
            <div style="width:100%; height:90px; margin-top:10px;">
                <a  href="dropzone.php" class="btn" title="Records" style="text-align:center; padding:15px; width:85px; height:40px; color:#22aeff; margin-left:5px; float:left;">
                    <i class="icon-plus-sign icon-2x" style="margin:0 auto; padding:0px; color:#54bc00;"></i>
                    <div class="transBox" style="width:100%; height:20px;">
                        <span style="font-size:12px;" lang="en">Create Member</span>
                    </div>
                </a>
                <?php 
                if ($user->doctor_privilege==1 or $user->doctor_privilege==4)
                {
                    echo '    <a href="medicalConnections.php" class="btn" title="Records" style="text-align:center; padding:15px; width:85px; height:40px; color:#22aeff; margin-left:10px; float:left;">';
                    echo '               <i class="icon-circle-arrow-right icon-2x" style="margin:0 auto; padding:0px; color:#54bc00;"></i>';
                    echo '            <div class="transBox" style="width:100%; height:20px;">';
                    echo '              <span lang="en">Send Referral</span>';
                    echo '            </div>';
                    echo '     </a>';
                    echo '    <a  href="PatientNetwork.php" class="btn" title="Records" style="text-align:center; padding:15px; width:85px; height:40px; color:#22aeff; margin-left:10px; float:left;">';
                    echo '            <i class="icon-link icon-2x" style="margin:0 auto; padding:0px; color:#54bc00;"></i>';
                    echo '            <div style="width:100%; height:20px;">';
                    echo '              <span lang="en">Link Member</span>';
                    echo '            </div>';
                    echo '    </a>';
                }
                ?>
            </div>
        </div>
        <div id="ConnBOX" style="width:250px; float:left; margin-right:25px; margin-left:20px;">
            <div style="background-color:#1d92d7; height:30px; width:100%;">
                <p style="color:white; font-size:20px; font-weight:bold; font-style:italic; padding-top:5px;" lang="en">Connect</p>
            </div>
            <div id="ConnectSCROLL" style="height:60px; overflow:hidden;">		             		 	
                <div style="background-color:#1d92d7; height:60px; width:100%;">
                    <div style="float:left; background-color:#22aeff; height:60px; width:45%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p id="Connect2" style="color:white; font-size:35px; font-weight:bold; text-align:right; margin-right:5px; padding-top:8px;">
                                <i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:white;"></i>
                            </p>
                        </div>	
                    </div>
                    <div style="float:left; background-color:#22aeff; height:60px; width:55.3%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p style="color:white; font-weight:bold; text-align:left; line-height:100%; width:90%; padding-top:8px;">
                                <span id="Connect2T1" style="width:200px; font-size:16px; font-weight:normal;"></span><br/>
                                <span id="Connect2T2" style="width:100%; font-size:14px; font-weight:bold;"></span>
                            </p>
                        </div>
                    </div>
                    <div style="float:left; background-color:#22aeff; height:60px; width:45%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p id="Connect3" style="color:white; font-size:35px; font-weight:bold; text-align:right; margin-right:5px; padding-top:8px;">
                                <i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:white;"></i>
                            </p>
                        </div>	
                    </div>
                    <div style="float:left; background-color:#22aeff; height:60px; width:55.3%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p style="color:white; font-weight:bold; text-align:left; line-height:100%; width:90%; padding-top:8px;">
                                <span id="Connect3T1" style="width:200px; font-size:16px; font-weight:normal;"></span><br/>
                                <span id="Connect3T2" style="width:100%; font-size:14px; font-weight:bold;"></span>
                            </p>
                        </div>
                    </div>
                </div>	 	
            </div> 	
        </div>
        <div id="EngaBOX" style="width:250px; float:left;">
            <div style="background-color:#549500; height:30px; width:100%;">
                <p style="color:white; font-size:20px; font-weight:bold; font-style:italic; padding-top:5px;" lang="en">Engage</p>
            </div>
            <div id="EngageSCROLL" style="height:60px; overflow:hidden;">		             	
                <div style="background-color:#54bc00; height:60px; width:100%;">
                    <div style="float:left; background-color:#54bc00; height:60px; width:45%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p id="Engage1" style="color:white; font-size:35px; font-weight:bold; text-align:right; margin-right:5px; padding-top:8px;">
                                <i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color:white;"></i>
                            </p>
                        </div>	
                    </div>
                    <div style="float:left; background-color:#54bc00; height:60px; width:55.3%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p style="color:white; font-weight:bold; text-align:left; line-height:100%; width:90%; padding-top:8px;">
                                <span id="Engage1T1" style="width:200px; font-size:16px; font-weight:normal;"></span><br/>
                                <span id="Engage1T2" style="width:100%; font-size:14px; font-weight:bold;"></span>
                            </p>
                        </div>
                    </div>
                    <div style="float:left; background-color:#54bc00; height:60px; width:45%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p id="Engage2" style="color:white; font-size:35px; font-weight:bold; text-align:right; margin-right:5px; padding-top:8px;">
                                <i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color:white;"></i>
                            </p>
                        </div>	
                    </div>
                    <div style="float:left; background-color:#54bc00; height:60px; width:55.3%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p style="color:white; font-weight:bold; text-align:left; line-height:100%; width:90%; padding-top:8px;">
                                <span id="Engage2T1" style="width:200px; font-size:16px; font-weight:normal;"></span><br/>
                                <span id="Engage2T2" style="width:100%; font-size:14px; font-weight:bold;"></span>
                            </p>
                        </div>
                    </div>
                </div>	 	 	
            </div> 	
        </div>
        </center>
    </div>
    <!-- END PART B -->
         
         
     <!--Search bar Start-->
        <div class="grid" style="width:978px; margin: 10px; border-radius: 5px;">
            <div class="grid-title" style="height: 60px; border-bottom: none;">
                <div class="pull-left" lang="en" style="margin-top:20px;">
                    <div class="fam-database-lightning" style="margin-right: 10px;"></div>
                    <span class="label label-info" style="background-color:#22aeff; color: white; font-weight: bold; margin-left:18px; line-height: 14px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">Member Search</span>
                </div>
                <div id="Wait1" style="margin-top:20px;margin-left:150px; float:left; display: inline;">
                    <img src="images/load/8.gif" alt="" style="" >
                </div>
                <div class="pull-right">
                    <div class="search-bar" style="border-bottom: none; background:white;">

                        <div style="float:right">
                            <input lang="en" type="text" class="span" name="" placeholder="Shows up to 20 members" style="width:200px;" id="SearchUser"> 
                            <input lang="en" type="button" class="btn btn-primary" value="Search" style="margin-left:50px;" id="BotonBusquedaPac">
                            <div id="stream_indicator" style="float:right;width: 52px; height: 42px; margin-left: 48px;margin-top:-8px;display:none">
                            <img src="images/load/29.gif"  alt="">
                            </div>
                        </div>

                        <!--<img src="images/load/8.gif" alt="" style="margin-left:50px; display: none;" id="Wait1">-->


                        <!--
                        <div style="float:right; margin-right:20px;">
                            <label class="checkbox toggle candy blue" onclick="" style="width:100px;">
                                <input type="checkbox" id="RetrievePatient" name="RetrievePatient" />
                                <p>
                                    <span title="Search in only in group" lang="en">Group</span>
                                    <span title="Search all patients" lang="en">All</span>
                                </p>

                                <a class="slide-button"></a>
                            </label>
                        </div>
                        -->
                    </div>
                </div>
                <div class="clear"></div>   
            </div>

            <div id="searchUserGrid" class="grid" style="display:none; margin-top: 0;">
                <table class="table table-mod">
                    <thead>
                        <tr id="FILA" class="CFILA">
                            <th lang="en">Identifier</th>
                            <th lang="en">First Name</th>
                            <th lang="en">Last Name</th>
                            <th lang="en">Username</th>
                            <th lang="en">Total Reports</th>
                        </tr>
                    </thead>
                    <tbody id='TablaPac'></tbody>
                </table> 
            </div>   
        </div>
        <!--Search bar END-->
   
    
    <a id="ButtonDeck" class="btn" title="Member´s Deck" style="text-align:center; padding:5px; width:40px; height:20px; color:#22aeff; float:right; margin-right: 20px; margin-bottom: -20px; display:none;">
        <span id="BaloonDeck" style="visibility: hidden; background-color: red; float: right; margin-right: -10px; margin-bottom: -15px;  border: none; position:relative;" class="H2MBaloon">6</span>
        <span lang="en">Deck</span>
    </a>
    <label class="checkbox toggle candy blue" onclick="" style="width:100px; float:right; margin-right: 20px; margin-bottom: -20px;">
        <input type="checkbox" id="Group_toggle" name="CRows"   />
        <p>
            <span lang="en">Group</span>
            <span lang="en">Me</span>
        </p>
    
        <a class="slide-button"></a>
    </label>
         
    <!-- PART C -->     
    <div id="NewsArea" style="width:978px; margin:10px; border:1px solid #cacaca; padding-left:0px; padding-right:0px; display:table; border-radius:5px; border:none;">
        <div class="tabbable">
            <ul class="nav nav-tabs myPsClass" style="margin: 0;">
               <li class="" style="width:120px;display:none;"><a href="#lA" data-toggle="tab"><i class="icon-info-sign" style="margin:0 auto; padding:0px; color:#54bc00; margin-right: 4px;"></i><span class="label label-success" lang="en">Information</span></a></li>
                <li class="active" style="width:170px;"><a href="#lB" data-toggle="tab"><i class="icon-sort-by-order" style="margin:0 auto; padding:0px; color:#22aeff;"></i> <span class="label label-info" style="-webkit-animation: glow 2s linear infinite;" lang="en">Recent EMR Activity</span></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="lA" style='display:none;'>
                    <div style="width:930px; height:400px; border:1 solid #cacaca; padding-left:50px;">
            
                        <div class="csslider">
                            <input type="radio" name="slides" id="slides_1" checked />
                            <input type="radio" name="slides" id="slides_2"  />
                            <input type="radio" name="slides" id="slides_3"  />
                            <input type="radio" name="slides" id="slides_4"  />
                            <input type="radio" name="slides" id="slides_5"  />
                            <input type="radio" name="slides" id="slides_6"  />
                            <ul>
                                <li><img src="TutorialBox/H2MAnimations001.png" /></li>
                                <li><div id="Stage" class="EDGE-43"></div></li>
                                <li><div id="Stage2" class="EDGE-44"></div></li>
                                <li><div id="Stage3" class="EDGE-45"></div></li>
                                <li><div id="Stage4" class="EDGE-46"></div></li>
                                <li><div id="Stage5" class="EDGE-47"></div></li>
                            </ul>
                            <div class="arrows">
                                <label for="slides_1" id="Arr0"></label>
                                <label for="slides_2" id="Arr1"></label>
                                <label for="slides_3"></label>
                                <label for="slides_4"></label>
                                <label for="slides_5"></label>
                                <label for="slides_6"></label>
                                <label for="slides_N"></label>
                                <label for="slides_1" class="goto-first"></label>
                                <label for="slides_N" class="goto-last"></label>
                            </div>
                            <div class="navigation">
                                <div>
                                    <label for="slides_1"></label>
                                    <label for="slides_2"></label>
                                    <label for="slides_3"></label>
                                    <label for="slides_4"></label>
                                    <label for="slides_5"></label>
                                    <label for="slides_6"></label>
                                </div>
                            </div>
                        </div>
                                     
                                     
                    </div>
                </div>
                
                
		        <div class="tab-pane active" id="lB" style="background-color:white; border:1px solid #cacaca; border-radius:5px; border-top: none; border-top-left-radius: 0px; border-top-right-radius: 0px;">
            <div id="leftColumn" class="elements" style="width:40%; min-width:390px; padding-left: 25px; display:table-cell;">              
        
                        <style>
                            div.BarExternal
                            {
                                width:95%; 
                                height:50px; 
                                margin-top:20px; 
                                background-color:white; 
                                border:1px solid #cacaca; 
                                border-radius:5px; 
                                border-bottom-left-radius:0px; 
                                border-bottom-right-radius:0px;  
                            }
                            
                            a.BarInternal{
                                float:left; 
                                width:33.33%; 
                                border:0px solid black; 
                                height:100%; 
                                text-align:center; 
                                display:table; 
                                color:#22aeff;
                                margin-left:-1px;
                                text-decoration: none;
                            }
                            
                            a.BarInternal:hover{
                                color:white; 
                                background-color:#22aeff;
                            }
                            
                            a.BarInternal:active{
                                color:white; 
                                background-color:grey;
                            }
                            
                            a.BarInternalMid{
                                float:left; 
                                width:33.33%; 
                                border:0px solid black; 
                                height:100%; 
                                text-align:center; 
                                display:table; 
                                border-left:1px solid #cacaca; 
                                border-right:1px solid #cacaca;
                                color:#22aeff;
                                text-decoration: none;
                            }
                            
                            a.BarInternalMid:hover{
                                color:white; 
                                background-color:#22aeff;
                            }
                            
                            .ColumnLabel{
                                color: #22aeff; 
                                font-size: 18px; 
                                width: 190px; 
                                margin-top: -8px; 
                                margin-bottom: 8px; 
                                text-align: right; 
                                float: left; 
                                margin-right: 15px;
                            } 
                        </style>
                        
                        <!-- RECENT ACTIVITY COLUMN -->
                        <div id="PatNewlyN" style="width:33%; float:left">
                            <div class="BarExternal" style="text-align: center; align: center; vertical-align: middle; padding-top: 12px; height: 38px;">
                                    <h3 class="ColumnLabel" lang="en">Recent Activity</h3>
                                    <i id="iconRecent" class="fa fa-clock-o icon-2x" style="color: #22aeff; float: left;"></i>
                                    <!--<span id="BaloonActivity" style="visibility:hidden; background-color:#cacaca; float:left; margin-top:5px; margin-left:-50px; border:none;" class="H2MBaloon">2</span>-->
                            </div>
                            <div id="PatNewlyContainer_emract" style="width:95%; height:72px; display:table; margin-top:-1px; margin-bottom:20px; border:1px solid #cacaca; border-radius:5px; border-top-left-radius:0px; border-top-right-radius:0px;">
                                <p id="EMRActIndicator" style="color: #22aeff; font-size:35px; font-weight:bold; text-align:center; margin-top: 30px;">
                                    <i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color: #22aeff;"></i>
                                </p>
                            </div>
                        </div>
                        <!-- END RECENT ACTIVITY COLUMN -->
                        
                        <!-- REFERRED IN COLUMN -->
                        <div id="PatNewlyN" style="width:33%; float: left">
                            <div class="BarExternal" style="text-align: center; align: center; vertical-align: middle; padding-top: 12px; height: 38px;">
                                    <h3 class="ColumnLabel" lang="en">Referred In</h3>
                                    <i id="IconRefIn"  class="icon-signin icon-2x" id="H2M_Spin"  style="color: #22aeff; float: left;"></i>
                                    <!--<span id="BaloonRefIn" style="visibility:hidden; background-color:#cacaca; float:left; margin-top:5px; margin-left:-50px; border:none;" class="H2MBaloon">2</span>-->
                            </div>
                            <div id="PatNewlyContainer_refin" style="width:95%; height:72px; display:table; margin-top:-1px; margin-bottom:20px; border:1px solid #cacaca; border-radius:5px; border-top-left-radius:0px; border-top-right-radius:0px;">
                                <p id="RefInIndicator" style="color: #22aeff; font-size:35px; font-weight:bold; text-align:center; margin-top: 30px;">
                                    <i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color: #22aeff;"></i>
                                </p>
                            </div>
                        </div>
                        <!-- END REFERRED IN COLUMN -->
                        
                        <!-- REFERRED OUT COLUMN -->
                        <div id="PatNewlyN" style="width:33%; float: left">
                            <div class="BarExternal" style="text-align: center; align: center; vertical-align: middle; padding-top: 12px; height: 38px;">
                                    <h3 class="ColumnLabel" lang="en">Referred Out</h3>
                                    <i id="IconRefOut" class="icon-signout icon-2x" id="H2M_Spin" style="color: #22aeff; float: left;"></i>
                                    <!--<span id="BaloonRefOut" style="visibility:hidden; background-color:#cacaca; float:left; margin-top:5px; margin-left:-50px; border:none;" class="H2MBaloon">2</span>-->
                            </div>
                            <div id="PatNewlyContainer_refout" style="width:95%; height:72px; display:table; margin-top:-1px; margin-bottom:20px; border:1px solid #cacaca; border-radius:5px; border-top-left-radius:0px; border-top-right-radius:0px;">
                                    <p id="RefOutIndicator" style="color: #22aeff; font-size:35px; font-weight:bold; text-align:center; margin-top: 30px;">
                                        <i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color: #22aeff;"></i>
                                    </p>
                            
                            </div>
                        </div>
                        <!-- REFERRED OUT COLUMN -->
                        
                        
                        
                        
                    </div>
                </div>
                <div class="tab-pane" id="lC">
                    <p></p>
                </div>
            </div>     
            
            
            
            
        </div>
				
    </div>
    <!-- END PART C -->
         


    <?=$user->footer_copy;?>
	


         


</div>
<!-- END MAIN GRID -->
</div>
<audio>
    <source id="Beep24" src="sound/beep-24.mp3"></source>
</audio>
<!-- END MAIN CONTENT -->







<!-- JAVASCRIPT -->
<script src="TypeWatch/jquery.typewatch.js"></script>
<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
<script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
<!--<script src="realtime-notifications/pusher.min.js"></script>
<script src="realtime-notifications/PusherNotifier.js"></script>-->
<script src="js/socket.io-1.3.5.js"></script>
<script src="push/push_client.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-colorpicker.js"></script>
<script src="js/fullcalendar.min.js"></script>
<script src="js/chosen.jquery.min.js"></script>
<script src="js/autoresize.jquery.min.js"></script>
<script src="js/jquery.tagsinput.min.js"></script>
<script src="js/jquery.autotab.js"></script>
<script src="js/elfinder/js/elfinder.min.js" charset="utf-8"></script>
<script src="js/tiny_mce/tiny_mce.js"></script>
<script src="js/validation/jquery.validationEngine.js" charset="utf-8"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/jquery.jscrollpane.min.js"></script>
<script src="js/jquery.stepy.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/justgage.1.0.1.min.js"></script>
<script src="js/glisse.js"></script>
<script src="js/jquery.timepicker.js"></script>
<script src="js/jquery.flot.min.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="js/moment-with-locales.js"></script>

<script type="text/javascript" src="js/h2m_maindashboard.js"></script>
<script type="text/javascript" src="js/h2m_notifications.js"></script>


    
</body>
</html>
