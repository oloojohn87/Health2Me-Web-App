<?php
include_once("../mainDashboardClass.php");
$user = new mainDashboardClass();
$user->pageLinks('MainDashboard.php');
//This sets telemed variables to be used for telemed sessions...
$user->telemedSetter();


// retrieve all patients that were created by this HT
if($user->doctor_service == 'CATA-HT')
{
    $user->getHTPatients();
}
else if($user->doctor_service == 'CATA-NP')
{
    $user->getNPPatientQueueStatus();
}
?>
    
    <!--Adobe Edge Runtime-->
    <!--meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <script type="text/javascript" charset="utf-8" src="../../../../TutorialBox/AnimationA2_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-43 { visibility:hidden; }
    </style>
    <script type="text/javascript" charset="utf-8" src="../../../../TutorialBox/AnimationA3_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-44 { visibility:hidden; }
    </style>
    <script type="text/javascript" charset="utf-8" src="../../../../TutorialBox/AnimationA4_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-45 { visibility:hidden; }
    </style>
    <script type="text/javascript" charset="utf-8" src="../../../../TutorialBox/AnimationA5_edgePreload.js"></script>
    <style>
        .edgeLoad-EDGE-46 { visibility:hidden; }
    </style>
    

    <style>
        .edgeLoad-EDGE-47 { visibility:hidden; }
    </style-->
<!--Adobe Edge Runtime End-->
<link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
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
        /*.timeCellIndicator{ 
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
        }*/
        #schedule {
            width: 804px;
            height: 80px;
            margin: 0 0 10px 70px;
            font-family: 'Lato', sans-serif;
            background-color: #B4D7FA;
            background-image: -webkit-linear-gradient(left, #F4F7F9, #B4D7FA);
            background-image: -o-linear-gradient(left, #F4F7F9, #B4D7FA);
            background-image: linear-gradient(to right, #F4F7F9, #B4D7FA);          
            background-image: -moz-linear-gradient(left, #F4F7F9, #B4D7FA);
            border-right: 2px solid #52A7F9;
            z-index: 2;
        }
        .hourslot {
            height: inherit;
            width: 65px;
            border-left: 2px solid #52A7F9;
            display: inline-block;
        }
        .minslot {
            height: inherit;
            /*border-right: 1px solid #B2B2B2;*/
            display: inline-block;
        }
        .capsule {
            height: inherit; 
            border-radius: 5px;
            display: table;
            margin: 0 1px;
            color: white;
            font-size: 8px;          
            text-align: center;       
        }
        span.vertical_name {
            display:table-cell;
            line-height: 9px;
            margin: auto;
            font-family: 'Lato', sans-serif;
            font-weight: bold;
            vertical-align: middle;
        }
        .capsule.active {
            background-color: #0265C0;
        }
        .capsule.canceled {
            background-color: #FF4D4D;
        }
        .capsule.completed {
            background-color: #CACACA;
        }
        .capsule.missed {
            background-color: #FF4D4D;
        }
        .capsule.next {
            background-color: #6FC041;
        }
        .capsule.editActive {
            background-color: #6FC041;
        }
        .capsule.editNonActive {
            background-color: gray;
        }
        #timelabel {
            font-family: 'Lato', sans-serif;
            margin-left: 15px;
        }
        .timeLabel {
            margin-left: 35px;
            width: 35px;
            display: inline-block;
            color: #4EA8FC;
            z-index: 3
        }
        .timeLabel.pm {
            color: #164F86;
        }
        #today {
            font-family: 'Lato', sans-serif;
            margin-left: 12px;
            color: #164F86;
            float:left;
            margin: 12px 0 0 65px;
        }
        #todayBar {
            margin-bottom: -32px;
            margin-top:70px;
        }
        #curTimeLabel {
            font-family: 'Lato', sans-serif;
        }
        #noticeNext {
            font-family: 'Lato', sans-serif;
            float: right;
            color: #4EA8FC;
            display: inline-block;
            margin: 0 60px 10px 0;
            text-align: right;
        }
    </style>
    
    
    <!--<div id="notification_bar" style="position: fixed;text-align:center;width: 100%; height: 44px; color: white; z-index: 2; background-color: rgb(119, 190, 247);"><div id="notification_bar_msg" style="display:inline-block;margin-right:20px;height:40px;vertical-align: middle;">We use cookies to improve your experience. By your continued use of this site you accept such use. </div>
       <div id="notification_bar_close" style="display:inline-block;margin-top: 2px;margin-left:40px">
           <i class="icon-remove-circle icon-3x"></i>
   </div></div>-->
      <div id="notification_bar"></div>
    <div class="loader_spinner"></div>

    <input type="hidden" id="NombreEnt" value="<?php if(isset($user->member_first_name)) echo $user->member_first_name; ?>">
    <input type="hidden" id="PasswordEnt" value="<?php if(isset($PasswordEnt)) echo $PasswordEnt; ?>">
    <input type="hidden" id="UserHidden">
    

	<!-- HEADER VIEW FOR MAIN TOOLBAR -->
	<?php include '../../common/header.php';?>
    <!-- END HEADER VIEW FOR MAIN TOOLBAR -->
    <!-- MODAL VIEW FOR HT SCHEDULING -->
    <div id="HTSchedule" style="display:none; text-align:center; padding:20px;">
         <style>
            .ui-widget-header {
                background: none;
                color: #FFF;
                background-color: #EE3423;
                border: 1px solid #EE3423;
            }
        </style>
        <div style="width: 100%; height: 70px; margin-top: 10px;">
            <div style="width: 70%; height: 70px; float: right;">
                <input type="date" id="ht_schedule_date" min="<?php echo date("Y-m-d"); ?>" style="padding: 10px; border-radius: 5px; width: 203px; outline: none;" />
            </div>
            <div style="width: 30%; height: 60px; padding-top: 10px; float: left; font-size: 18px; color: #777;">
                Date:
            </div>
        </div>
        <div style="width: 100%; height: 70px;">
            <div style="width: 70%; height: 70px; float: right;">
                <input type="text" id="ht_schedule_time" style="padding: 10px; border-radius: 5px; width: 203px; outline: none;" />
            </div>
            <div style="width: 30%; height: 60px; padding-top: 10px; float: left; font-size: 18px; color: #777;">
                Time:
            </div>
        </div>
        <div style="width: 100%; height: 70px;">
            <div style="width: 70%; height: 70px; float: right;">
                <select id="ht_schedule_language" style="padding: 15px; border-radius: 8px; height: 42px; width: 223px; outline: none;" >
                    <option value="en" selected>English</option>
                    <option value="es">Espa&ntilde;ol</option>
                </select>
            </div>
            <div style="width: 30%; height: 60px; padding-top: 10px; float: left; font-size: 18px; color: #777;">
                Language:
            </div>
        </div>
        <div style="width: 60%; height: 40px; margin: auto; margin-top: 10px;">
            <button id="ht_create_appointment" style="width: 100%; height: 40px; border: 0px solid #FFF; outline: none; border-radius: 10px; background-color: #EE3423; color: #FFF;">Create Appointment</button>
        </div>
    </div>
    
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
    
    
    <div id="content" style="padding-left:0px;">

    <!-- Button trigger modal -->
    <button id="LaunchModal" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" style="display:none;" lang="en">Launch demo modal</button>
     	    
	 <!-- MAIN TOOLBAR -->
     <div class="speedbar">
        <div class="speedbar-content" style="position:relative;">
            <ul class="menu-speedbar">
                <li><a href="../../maindashboard/html/MainDashboardCATA.php" class="act_link" lang="en">Home</a></li>
                
                <?php
                                    
                $arr=$user->checkAccessPage("dashboard.php");
                $arr_d=json_decode($arr, true);

                if((count($arr_d['items'])>0)&&($arr_d['items'][0]['accessid']==1)){ 
                
                    echo '<li><a lang="en" href="../../maindashboard/html/MainDashboardCATA.php"  lang="en">Dashboard</a></li>';
                }
                ?>
                <!--li><a href="patients.php"  lang="en">Members</a></li-->
                <?php 
                    if ($user->doctor_privilege==1 or $user->doctor_privilege==4)
                    {
                        echo '<li><a href="../../medicalconnections/html/MedicalConnectionsCATA.php"  lang="en">Doctor Connections</a></li>';
                        echo '<li><a href="../../membernetwork/html/MemberNetworkCATA.php"  lang="en">Member Network</a></li>';
                    }
                ?>
                <li><a href="../../configuration/html/ConfigurationCATA.php" lang="en">Configuration</a></li>
                <li><a href="../../../ajax/logout.php" style="color:DarkRed;" lang="en">Sign Out</a></li>
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
                <img src="../../../../<?php echo $avat; ?>" style="width:50px; margin:0px; float:left; font-size:18px;  border:1px solid #b0b0b0;"/>
            </div>   
            <div style="float:left; width:360px; height:100%; ">
            <div id="NombreComp" style="width:100%; color: Red; font: bold 22px Arial, Helvetica, sans-serif; cursor: auto; text-shadow: 0px 2px 2px rgba(0, 0, 0, 0.30);"><?php echo $user->doctor_title.' '.$user->doctor_first_name;?> <?php echo $user->doctor_last_name;?></div>
                <span id="NombreComp" style="color: DarkGray; font: bold 16px Arial, Helvetica, sans-serif; cursor: auto; margin-top:-5px;"><span lang="en">CENTER</span>:  <?php if ($user->doctor_group_name<' ') echo 'single license'; else echo $user->doctor_group_name ;?></span>
                <div style="width:100%; margin-top:5px; "></div>
                <?php 
                if ($user->doctor_privilege==1 or $user->doctor_privilege==4)
                {
                    echo '<div style="float:left; width:150px; height:20px; text-align:center; border:1px solid DarkRed; border-radius:5px;display:table;margin:0px;background-color: DarkRed;">';
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
            #schedule_edit:hover {
                color: whitesmoke;
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
    
    <?php if ($user->doctor_service == 'CATA-NP') { ?>
         
    <!-- PART AGENDA -->
    <div class="grid" class="grid span4" style="margin:0px auto; margin-top: 10px; margin-left:10px; margin-right:10px; padding:20px; padding-top: 10px; display: block;" id="part_d">
        <div style="margin-top:10px; margin-bottom: 20px; margin-left: 1%;"><span class="label label-info" id="EtiTML" style="background-color:Red; margin:10px; margin-left:0px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">Today's Schedule</span></div>
       
        <div id="schedule_top">
            <div id="today"></div>
            <input type="hidden" id="todayHidden" value="" />
        <input lang="en" type="button" class="btn btn-danger" value="Edit Schedule" style="float:right; margin-right:65px;" id="schedule_edit">
        </div>
        <div style="claer:both;"></div>
        <div id="todayBar"></div>
        <div id="timelabel">
            <div id="label-7am" class="timeLabel">7AM</div>
            <div id="label-8am" class="timeLabel">8AM</div>
            <div id="label-9am" class="timeLabel" style="margin-left:30px;">9AM</div>
            <div id="label-10am" class="timeLabel" style="margin-left:28px;">10AM</div>
            <div id="label-11am" class="timeLabel" style="margin-left:27px;">11AM</div>
            <div id="label-12pm" class="timeLabel" style="margin-left:27px;">12PM</div>
            <div id="label-1pm" class="timeLabel pm" style="margin-left:33px;">1PM</div>
            <div id="label-2pm" class="timeLabel pm" style="margin-left:30px;">2PM</div>
            <div id="label-3pm" class="timeLabel pm" style="margin-left:30px;">3PM</div>
            <div id="label-4pm" class="timeLabel pm" style="margin-left:29px;">4PM</div>
            <div id="label-5pm" class="timeLabel pm" style="margin-left:29px;">5PM</div>
            <div id="label-6pm" class="timeLabel pm" style="margin-left:29px;">6PM</div>
            <div id="label-7pm" class="timeLabel pm" style="margin-left:30px;">7PM</div>
        </div>
        <div id="schedule"></div>
        <div id="noticeNext"></div>
        <div style="clear:both;"></div>
        
    </div>
    <!-- END PART AGENDA -->
    <?php } ?>

    <?php if ($user->doctor_service == 'CATA-HT') { ?>
         
    <div id="patients_to_schedule" class="grid" class="grid span4" style="margin:0px auto; margin-top: 10px; margin-left:10px; margin-right:10px; padding:20px; padding-top: 10px; display: block;">
        
        <?php
            $patient_count = count($user->ht_patients);
            for($i = 0; $i < $patient_count; $i++)
            {
                if($user->ht_patients[$i]['Date'] == NULL)
                {
                    echo '<div style="width: 100%; height: 40px; font-family: Helvetica, sans-serif; color: #EE3423; font-size: 16px;">';
                    echo '<div style="float: left;">'.$user->ht_patients[$i]['PatientName'].'</div>';
                    echo '<div style="float: right;"><button id="ht_schedule_'.$user->ht_patients[$i]['PatientId'].'" class="ht_schedule_button" style="width: 100px; height: 30px; border: 0px solid #FFF; outline: none; border-radius: 5px; background-color: #EE3423; color: #FFF;">Schedule</button></div>';
                    echo '</div>';
                }
                else
                {
                    echo '<div style="width: 100%; height: 40px; font-family: Helvetica, sans-serif; color: #666; font-size: 16px;">';
                    echo '<div style="float: left;">'.$user->ht_patients[$i]['PatientName'].'</div>';
                    echo '<div style="float: right;">Scheduled with NP '.$user->ht_patients[$i]['NPName'].' for '.date('F j, Y g:i A', strtotime($user->ht_patients[$i]['Date'])).'</div>';
                    echo '</div>';     
                }
            }
        ?>
        <!--
        <div style="width: 100%; height: 40px; font-family: Helvetica, sans-serif; color: #666; font-size: 16px;">
            <div style="float: left;">John Smith</div>
            <div style="float: right;">Scheduled with NP Javier Vinals for May 22, 2015</div>
        </div>     
        <div style="width: 100%; height: 40px; font-family: Helvetica, sans-serif; color: #EE3423; font-size: 16px;">
            <div style="float: left;">Jane Doe</div>
            <div style="float: right;"><button style="width: 100px; height: 30px; border: 0px solid #FFF; outline: none; border-radius: 5px; background-color: #EE3423; color: #FFF;">Schedule</button></div>
        </div>  
        -->
    </div>
         
    <?php } ?>
         
    <!-- PART D -->
    <div class="grid" class="grid span4" style="margin:0px auto; margin-top: 10px; margin-left:10px; margin-right:10px; padding:20px; padding-top: 10px; display: block;" id="part_d">
        <div style="margin-top:10px; margin-bottom: 20px; margin-left: 1%;"><span class="label label-info" id="EtiTML" style="background-color:Red; margin:10px; margin-left:0px; margin-bottom:20px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">Notifications</span></div>
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
    <div id="StatsNew" style="width:938px; padding:20px; margin:10px; border:1px solid Red; display:table; border-radius:5px; overflow:auto;"><center>
        <div style="float:left; margin-right:10px; min-width: 370px;">
            <div style="width:100%; height:90px; margin-top:10px;">
                <a href="../../../ajax/dropzone.php" class="btn" title="Records" style="text-align:center; padding:15px; width:85px; height:40px; color:Red; margin-left:5px; float:left; background-image:none;">
                    <i class="icon-plus-sign icon-2x" style="margin:0 auto; padding:0px; color:DarkRed;"></i>
                    <div class="transBox" style="width:100%; height:20px;">
                        <span style="font-size:12px;" lang="en">Create Member</span>
                    </div>
                </a>
                <?php 
                if ($user->doctor_privilege==1 or $user->doctor_privilege==4)
                {
                    echo '    <a href="../../medicalconnections/html/MedicalConnectionsCATA.php" class="btn" title="Records" style="text-align:center; padding:15px; width:85px; height:40px; color:Red; margin-left:10px; float:left; background-image:none;">';
                    echo '               <i class="icon-circle-arrow-right icon-2x" style="margin:0 auto; padding:0px; color:DarkRed;"></i>';
                    echo '            <div class="transBox" style="width:100%; height:20px;">';
                    echo '              <span lang="en">Send Referral</span>';
                    echo '            </div>';
                    echo '     </a>';
                    echo '    <a href="../../membernetwork/html/MemberNetworkCATA.php" class="btn" title="Records" style="text-align:center; padding:15px; width:85px; height:40px; color:Red; margin-left:10px; float:left; background-image:none;">';
                    echo '            <i class="icon-link icon-2x" style="margin:0 auto; padding:0px; color:DarkRed;"></i>';
                    echo '            <div style="width:100%; height:20px;">';
                    echo '              <span lang="en">Link Member</span>';
                    echo '            </div>';
                    echo '    </a>';
                }
                ?>
            </div>
        </div>
        <div id="ConnBOX" style="width:250px; float:left; margin-right:25px; margin-left:20px;">
            <div style="background-color:DarkRed; height:30px; width:100%;">
                <p style="color:white; font-size:20px; font-weight:bold; font-style:italic; padding-top:5px;" lang="en">Pending Consults</p>
            </div>
            <div id="ConnectSCROLL" style="height:60px; overflow:hidden;">		             		 	
                <div style="background-color:DarkRed; height:60px; width:100%;">
                    <div style="float:left; background-color:Red; height:60px; width:45%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p id="Connect2CATA" style="color:white; font-size:35px; font-weight:bold; text-align:right; margin-right:5px; padding-top:8px;">
                                <?php echo $user->np_active; ?>
                            </p>
                        </div>	
                    </div>
                    <div style="float:left; background-color:Red; height:60px; width:55.3%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p style="color:white; font-weight:bold; text-align:left; line-height:100%; width:90%; padding-top:8px;">
                                <span id="Connect2T1CATA" style="width:200px; font-size:16px; font-weight:normal;">Pending</span><br/>
                                <span id="Connect2T2CATA" style="width:100%; font-size:14px; font-weight:bold;">Consultations</span>
                            </p>
                        </div>
                    </div>
                    <!--<div style="float:left; background-color:Red; height:60px; width:45%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p id="Connect3" style="color:white; font-size:35px; font-weight:bold; text-align:right; margin-right:5px; padding-top:8px;">
                                <i class="icon-spinner icon-spin" id="H2M_Spin" style="margin:0 auto; color:white;"></i>
                            </p>
                        </div>	
                    </div>
                    <div style="float:left; background-color:Red; height:60px; width:55.3%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p style="color:white; font-weight:bold; text-align:left; line-height:100%; width:90%; padding-top:8px;">
                                <span id="Connect3T1" style="width:200px; font-size:16px; font-weight:normal;"></span><br/>
                                <span id="Connect3T2" style="width:100%; font-size:14px; font-weight:bold;"></span>
                            </p>
                        </div>
                    </div>-->
                </div>	 	
            </div> 	
        </div>
        <div id="EngaBOX" style="width:250px; float:left;">
            <div style="background-color:Gray; height:30px; width:100%;">
                <p style="color:white; font-size:20px; font-weight:bold; font-style:italic; padding-top:5px;" lang="en">Completed Consults</p>
            </div>
            <div id="EngageSCROLL" style="height:60px; overflow:hidden;">		             	
                <div style="background-color:Gray; height:60px; width:100%;">
                    <div style="float:left; background-color:DarkGray; height:60px; width:45%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p id="Engage1CATA" style="color:white; font-size:35px; font-weight:bold; text-align:right; margin-right:5px; padding-top:8px;">
                                <?php echo $user->np_completed; ?>
                            </p>
                        </div>	
                    </div>
                    <div style="float:left; background-color:DarkGray; height:60px; width:55.3%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p style="color:white; font-weight:bold; text-align:left; line-height:100%; width:90%; padding-top:8px;">
                                <span id="Engage1T1CATA" style="width:200px; font-size:16px; font-weight:normal;">Completed</span><br/>
                                <span id="Engage1T2CATA" style="width:100%; font-size:14px; font-weight:bold;">Consultations</span>
                            </p>
                        </div>
                    </div>
                    <!--<div style="float:left; background-color:DarkGray; height:60px; width:45%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p id="Engage2" style="color:white; font-size:35px; font-weight:bold; text-align:right; margin-right:5px; padding-top:8px;">
                                <i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color:white;"></i>
                            </p>
                        </div>	
                    </div>
                    <div style="float:left; background-color:DarkGray; height:60px; width:55.3%; display:table; text-align:center;">
                        <div style="display:table-cell; vertical-align:middle;">
                            <p style="color:white; font-weight:bold; text-align:left; line-height:100%; width:90%; padding-top:8px;">
                                <span id="Engage2T1" style="width:200px; font-size:16px; font-weight:normal;"></span><br/>
                                <span id="Engage2T2" style="width:100%; font-size:14px; font-weight:bold;"></span>
                            </p>
                        </div>
                    </div>-->
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
                    <span class="label label-info" style="background-color:Red; color: white; font-weight: bold; margin-left:18px; line-height: 14px; font-size:16px; text-shadow:none; text-decoration:none;" lang="en">Member Search</span>
                </div>
                <div id="Wait1" style="margin-top:20px;margin-left:150px; float:left; display: inline;">
                    <img src="../../../../images/load/8.gif" alt="" style="" >
                </div>
                <div class="pull-right">
                    <div class="search-bar" style="border-bottom: none; background:white;">

                        <div style="float:right">
                            <input lang="en" type="text" class="span" name="" placeholder="Shows up to 20 members" style="width:200px;" id="SearchUser"> 
                            <input lang="en" type="button" class="btn btn-danger" value="Search" style="margin-left:50px;" id="BotonBusquedaPac">
                            <div id="stream_indicator" style="float:right;width: 52px; height: 42px; margin-left: 48px;margin-top:-8px;display:none">
                            <img src="../../../../images/load/29.gif"  alt="">
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
                <li class="active" style="width:170px;"><a href="#lB" data-toggle="tab"><i class="icon-sort-by-order" style="margin:0 auto; padding:0px; color:Red;"></i> <span class="label label-danger" style="-webkit-animation: glow 2s linear infinite;" lang="en">Recent EMR Activity</span></a></li>
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
                                <li><img src="../../../../TutorialBox/H2MAnimations001.png" /></li>
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
                
                
		        <div class="tab-pane active" id="lB" style="background-color:white; border:1px solid Red; border-radius:5px; border-top: none; border-top-left-radius: 0px; border-top-right-radius: 0px;">
            <div id="leftColumn" class="elements" style="width:40%; min-width:390px; padding-left: 25px; display:table-cell;">              
        
                        <style>
                            div.BarExternal
                            {
                                width:95%; 
                                height:50px; 
                                margin-top:20px; 
                                background-color:white; 
                                border:1px solid Red; 
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
                                background-color:Red;
                            }
                            
                            a.BarInternal:active{
                                color:white; 
                                background-color:DarkRed;
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
                                color: Red; 
                                font-size: 18px; 
                                width: 190px; 
                                margin-top: -8px; 
                                margin-bottom: 8px; 
                                text-align: right; 
                                float: left; 
                                margin-left: 65px;
                                margin-right: 15px;
                            } 
                        </style>
                        
                        <!-- RECENT ACTIVITY COLUMN -->
                        <div id="PatNewlyN" style="width:50%; float:left">
                            <div class="BarExternal" style="text-align: center; align: center; vertical-align: middle; padding-top: 12px; height: 38px;">
                                    <h3 class="ColumnLabel" lang="en" style="padding-left:18px;">Recent Activity</h3>
                                    <i id="iconRecent" class="fa fa-clock-o fa-2x" style="color: Red; float: left;"></i>
                                    <!--<span id="BaloonActivity" style="visibility:hidden; background-color:#cacaca; float:left; margin-top:5px; margin-left:-50px; border:none;" class="H2MBaloon">2</span>-->
                            </div>
                            <div id="PatNewlyContainer_emract" style="width:95%; height:72px; display:table; margin-top:-1px; margin-bottom:20px; border:1px solid Red; border-radius:5px; border-top-left-radius:0px; border-top-right-radius:0px;">
                                <p id="EMRActIndicator" style="color: #22aeff; font-size:35px; font-weight:bold; text-align:center; margin-top: 30px;">
                                    <i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color: Red;"></i>
                                </p>
                            </div>
                        </div>
                        <!-- END RECENT ACTIVITY COLUMN -->
                        
                        <!-- REFERRED IN COLUMN -->
                        <!--div id="PatNewlyN" style="width:50%; float: left">
                            <div class="BarExternal" style="text-align: center; align: center; vertical-align: middle; padding-top: 12px; height: 38px;">
                                    <h3 class="ColumnLabel" lang="en">Referred In</h3>
                                    <i id="IconRefIn"  class="icon-signin icon-2x" id="H2M_Spin"  style="color: Red; float: left;"></i>
                                    <!--<span id="BaloonRefIn" style="visibility:hidden; background-color:#cacaca; float:left; margin-top:5px; margin-left:-50px; border:none;" class="H2MBaloon">2</span>-->
                            <!--/div>
                            <div id="PatNewlyContainer_refin" style="width:95%; height:72px; display:table; margin-top:-1px; margin-bottom:20px; border:1px solid Red; border-radius:5px; border-top-left-radius:0px; border-top-right-radius:0px;">
                                <p id="RefInIndicator" style="color: Red; font-size:35px; font-weight:bold; text-align:center; margin-top: 30px;">
                                    <i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color: Red;"></i>
                                </p>
                            </div>
                        </div>
                        <!-- END REFERRED IN COLUMN -->
                        
                        <!-- REFERRED OUT COLUMN -->
                        <div id="PatNewlyN" style="width:50%; float: left">
                            <div class="BarExternal" style="text-align: center; align: center; vertical-align: middle; padding-top: 12px; height: 38px;">
                                    <h3 class="ColumnLabel" lang="en">Referred Out</h3>
                                    <i id="IconRefOut" class="icon-signout icon-2x" id="H2M_Spin" style="color: Red; float: left;"></i>
                                    <!--<span id="BaloonRefOut" style="visibility:hidden; background-color:#cacaca; float:left; margin-top:5px; margin-left:-50px; border:none;" class="H2MBaloon">2</span>-->
                            </div>
                            <div id="PatNewlyContainer_refout" style="width:95%; height:72px; display:table; margin-top:-1px; margin-bottom:20px; border:1px solid #cacaca; border-radius:5px; border-top-left-radius:0px; border-top-right-radius:0px;">
                                    <p id="RefOutIndicator" style="color: Red; font-size:35px; font-weight:bold; text-align:center; margin-top: 30px;">
                                        <i class="icon-spinner icon-spin " id="H2M_Spin" style="margin:0 auto; color: Red;"></i>
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
<!--audio>
    <source id="Beep24" src="sound/beep-24.mp3"></source>
</audio-->
<!-- END MAIN CONTENT -->







<!-- JAVASCRIPT -->
<!--script src="../../../../TypeWatch/jquery.typewatch.js"></script-->
<!--link href="../../../../realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
<script src="../../../../realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script-->
<!--<script src="realtime-notifications/pusher.min.js"></script>
<script src="realtime-notifications/PusherNotifier.js"></script>-->
<script src="../../../master_js/socket.io-1.3.5.js"></script>
<script src="../../../modules/push/push_client.js"></script>
<script src="../../../master_js/bootstrap.min.js"></script>
<script src="../../../master_js/bootstrap-colorpicker.js"></script>
<script src="../../../master_js/fullcalendar.min.js"></script>
<script src="../../../master_js/chosen.jquery.min.js"></script>
<script src="../../../master_js/autoresize.jquery.min.js"></script>
<script src="../../../master_js/jquery.tagsinput.min.js"></script>
<script src="../../../master_js/jquery.autotab.js"></script>
<script src="../../../master_js/elfinder/js/elfinder.min.js" charset="utf-8"></script>
<script src="../../../master_js/tiny_mce/tiny_mce.js"></script>
<script src="../../../master_js/validation/jquery.validationEngine.js" charset="utf-8"></script>
<script src="../../../master_js/jquery.dataTables.min.js"></script>
<script src="../../../master_js/jquery.jscrollpane.min.js"></script>
<script src="../../../master_js/jquery.stepy.min.js"></script>
<script src="../../../master_js/jquery.validate.min.js"></script>
<script src="../../../master_js/justgage.1.0.1.min.js"></script>
<script src="../../../master_js/glisse.js"></script>
<script src="../../../master_js/jquery.timepicker.js"></script>
<script src="../../../master_js/jquery.flot.min.js"></script>
<script src="../../../master_js/jquery.cookie.js"></script>
<script src="../../../master_js/moment-with-locales.js"></script>
<script src="../../../master_js/sweet-alert.min.js"></script>
<script src="../../../master_js/draggabilly.pkgd.min.js"></script>
<script type="text/javascript" src="../../../master_js/tipped.js"></script>

<script type="text/javascript" src="../js/maindashboard.js"></script>
<script type="text/javascript" src="../../../master_js/cata_schedule.js"></script>
<script type="text/javascript" src="../../../master_js/h2m_notifications.js"></script>


    
</body>
</html>
