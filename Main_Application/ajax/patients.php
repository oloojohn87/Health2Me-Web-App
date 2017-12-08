<?php
include("userConstructClass.php");
$user = new userConstructClass();
$user->pageLinks('patients.php');
?>
<!-- Create language switcher instance and set default language to en-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
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

<body>
    <!--<div class="loader_spinner"></div>-->
    <input type="hidden" id="NombreEnt" value="<?php echo $user->doctor_first_name; ?>">
    <input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?>">
    <input type="hidden" id="UserHidden">

    <!--Header Start-->
    <div class="header" >
        <input type="hidden" id="USERDID" Value="<?php if(isset($user->mem_id)) echo $user->mem_id; ?>">  
        <input type="hidden" id="MEDID" Value="<?php echo $user->med_id; ?>"> 
        <input type="hidden" id="IdMEDEmail" Value="<?php echo $user->doctor_email; ?>">  
        <input type="hidden" id="IdMEDName" Value="<?php echo $user->doctor_first_name; ?>">  
        <input type="hidden" id="IdMEDSurname" Value="<?php echo $user->doctor_last_name; ?>">  
        <input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">  

        <?php
if ($_SESSION['CustomLook'] == 'COL') {
    echo '<a href="index-col.html" class="logo"><h1>health2.me</h1></a>';
} else {
    echo '<a href="index.html" class="logo"><h1>health2.me</h1></a>';
}    

        ?>

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
                border-top: 4px solid #048F90;
                margin-top: 3px !important;
                margin-left: 5px !important;
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
                    <span class="avatar" style="background-color:WHITE;"><img src="<?php echo $avat; ?>" alt="" ></span> 
                    <span class="caret"></span>
                </a>
                <div class="dropdown-menu" id="prof_dropdown">
                    <div class="item_m"><span class="caret"></span></div>
                    <ul class="clear_ul" >
                        <li>

                            <a href="MainDashboard.php" lang="en">    
                                <i class="icon-globe"></i> Home</a></li>

                        <li><a href="medicalConfiguration.php" lang="en"><i class="icon-cog"></i> Settings</a></li>
                        <li><a href="logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
                    </ul>
                </div>
            </div>
            <!--Button User END-->  

        </div>
    </div>
    <!--Header END-->

    <!--Content Start-->

    <div id="content" style="padding-left:0px;">


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


                    <li><a href="patients.php" class="act_link"  lang="en" >Members</a></li>
                    <?php if ($user->doctor_privilege==1)
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
        <!--SpeedBar END-->



        <!--CONTENT MAIN START-->
        <div class="content">

            <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">

                <span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;" lang="en">Members</span>     

                <div class="row-fluid" style="height:170px; width:1000px; margin:0 auto;">              

                    <div style="margin:15px; margin-top:5px;">
                        <div id="stats_icon"> <span style="margin-left: 160px;margin-top:50px;color: rgba(245, 174, 118, 0.99);font-size: 32px;">Loading statistics...</span>
                            <i  class="icon-sun icon-spin icon-5x" style="margin-left: 100px;margin-top:50px;color:rgba(245, 174, 118, 0.99);"></i>
                        </div>
                        <div id="loadpatientstats" class="row-fluid"></div>
                    </div></div>

                <!--Search bar Start-->
                <div class="grid" style="width:96%; margin: 0 auto; margin-top:30px; margin-bottom: 30px;">
                    <div class="grid-title">
                        <div class="pull-left" lang="en"><div class="fam-database-lightning" style="margin-right:10px;"></div>Members Database</div>
                        <div class="pull-right"></div>
                        <div class="clear"></div>   
                    </div>

                    <div class="search-bar">

                        <div style="float:left">

                            <input lang="en" type="text" class="span" name="" placeholder="Name" style="width:200px;" id="SearchUser"> 
                            <input lang="en" type="button" class="btn btn-primary" value="Search" style="margin-left:50px;" id="BotonBusquedaPac">
                        </div>

                        <!--<img src="images/load/8.gif" alt="" style="margin-left:50px; display: none;" id="Wait1">-->

                        <div id="stream_indicator" style="float:left;width: 52px; height: 42px; margin-left: 48px;margin-top:-8px;display:none">
                            <img src="images/load/29.gif"  alt="">
                        </div>
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

                    <div class="grid">
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
                        <img src="images/load/8.gif" alt="" style="margin-top:20px;margin-left:350px; display: none;" id="Wait1">
                    </div>                    
                </div>
                <!--Search bar END-->

                <?=$user->footer_copy;?>



            </div>
            <!--CONTENT MAIN END-->
        </div>
    </div>
    <!--Content END-->

    <!-- Le javascript
================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="TypeWatch/jquery.typewatch.js"></script>
    <!--Added for real-time notifications start-->
    <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
    <link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
    <!--<script src="realtime-notifications/pusher.min.js"></script>
    <script src="realtime-notifications/PusherNotifier.js"></script>-->
    <script src="js/socket.io-1.3.5.js"></script>
    <script src="push/push_client.js"></script>
    <script type="text/javascript" src="js/h2m_patients.js"></script>


    <script>
        $(function() {
            //var pusher = new Pusher('d869a07d8f17a76448ed');
            //var channel_name=$('#MEDID').val();
            //var channel = pusher.subscribe(channel_name);
            //var notifier=new PusherNotifier(channel);

            var push = new Push($("#MEDID").val(), window.location.hostname + ':3955');

            push.bind('notification', function(data) {
                displaynotification('New Message', data);
            });

        });

    </script>


    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
    <script src="js/google-code-prettify/prettify.js"></script>

    <script src="js/jquery.flot.min.js"></script>
    <script src="js/jquery.flot.pie.js"></script>
    <script src="js/jquery.flot.orderBars.js"></script>
    <script src="js/jquery.flot.resize.js"></script>
    <script src="js/graphtable.js"></script>
    <script src="js/fullcalendar.min.js"></script>
    <script src="js/chosen.jquery.min.js"></script>
    <script src="js/autoresize.jquery.min.js"></script>
    <script src="js/jquery.tagsinput.min.js"></script>
    <script src="js/jquery.autotab.js"></script>
    <script src="js/elfinder/js/elfinder.min.js" charset="utf-8"></script>
    <script src="js/tiny_mce/tiny_mce.js"></script>
    <script src="js/validation/languages/jquery.validationEngine-en.js" charset="utf-8"></script>
    <script src="js/validation/jquery.validationEngine.js" charset="utf-8"></script>
    <script src="js/jquery.jgrowl_minimized.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/jquery.mousewheel.js"></script>
    <script src="js/jquery.jscrollpane.min.js"></script>
    <script src="js/jquery.stepy.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/raphael.2.1.0.min.js"></script>
    <script src="js/justgage.1.0.1.min.js"></script>
    <script src="js/glisse.js"></script>

    <script src="js/application.js"></script>

    <?php

function queFuente ($numero)
{
    $queF=10;
    switch ($numero)
    {
        case ($numero>999 && $numero<9999): $queF = 30;
        break;
        case ($numero>99 && $numero<1000):  $queF = 50;
        break;
        case ($numero>0 && $numero<100):  $queF = 70;
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
        case 2: $queF = 60;
        break;
        case 3: $queF = 55;
        break;
        case 4: $queF = 50;
        break;
        case 5: $queF = 45;
        break;
        case 6: $queF = 40;
        break;
        case 7: $queF = 35;
        break;
        case 8: $queF = 30;
        break;
    }

    return ($queF);

}

function digitos ($numero)
{
    $queF=0;

    switch ($numero)
    {
        case ($numero>999 && $numero<9999): $queF = 4;
        break;
        case ($numero>99 && $numero<1000):  $queF = 3;
        break;
        case ($numero>0 && $numero<100):  $queF = 2;
        break;
    }

    return ($queF);

}
    ?>
</body>
</html>

