<?php
include_once('../../../master_classes/userConstructClass.php');
$user = new userConstructClass();

include_once('../medicalConnectionsClass.php');	
$medical_connections = new medicalConnectionsClass();
?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../../../../css/style.css" rel="stylesheet">
    <link href="../../../../css/bootstrap.css" rel="stylesheet">

    <link rel="stylesheet" href="../../../../css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="../../../../css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="../../../../css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="../../../../css/datepicker.css" >
    <link rel="stylesheet" href="../../../../css/colorpicker.css">
    <link rel="stylesheet" href="../../../../css/glisse.css?1.css">
    <link rel="stylesheet" href="../../../../css/jquery.jgrowl.css">
    <link rel="stylesheet" href="../../../../js/elfinder/css/elfinder.css" media="screen" />
    <link rel="stylesheet" href="../../../../css/jquery.tagsinput.css" />
    <link rel="stylesheet" href="../../../../css/demo_table.css" >
    <link rel="stylesheet" href="../../../../css/jquery.jscrollpane.css" >
    <link rel="stylesheet" href="../../../../css/validationEngine.jquery.css">
    <link rel="stylesheet" href="../../../../css/jquery.stepy.css" />

    <!--<link rel="stylesheet" href="css/icon/font-awesome.css">-->
    <link rel="stylesheet" href="../../../../font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../../../css/bootstrap-responsive.css">

    <?php
if ($_SESSION['CustomLook']=="COL") { ?>
    <link href="../../../../css/styleCol.css" rel="stylesheet">
    <?php } ?>



    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../../../../images/icons/favicon.ico">
    <link rel="stylesheet" href="../../../../css/toggle-switch.css">

    <!-- Create language switcher instance and set default language to en-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
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
    </head>
    <body onload="$('#BotonBusquedaSents').trigger('click'); $('#BotonBusquedaPermit').trigger('click');">
        <div class="loader_spinner"></div>
        <input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
        <input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
        <input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
        <input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
        <input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
        <input type="hidden" id="USERDID" Value="<?php if(isset($USERID)) echo $USERID; ?>">	
        <link rel='stylesheet' href='../../../../css/bootstrap-dropdowns.css'>
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
        <!--Header Start-->
        <div class="header" >

            <a href="index.html" class="logo"><h1>health2.me</h1></a>
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

            <?php include 'message_center.php'; ?>
            <!--Button User Start-->
            <div class="btn-group pull-right" >

                <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
                    <span class="name-user"><strong lang="en">Welcome</strong> Dr, <?php echo $MedUserName.' '.$MedUserSurname; ?></span> 
                    <?php 
$hash = md5( strtolower( trim( $MedUserEmail ) ) );
$avat = '../../../ajax/identicon.php?size=29&hash='.$hash;
                    ?>	
                    <span class="avatar" style="background-color:WHITE;"><img src="<?php echo $avat; ?>" alt="" ></span> 
                    <span class="caret"></span>
                </a>
                <div class="dropdown-menu" id="prof_dropdown"  style="background-color:transparent; border:none; -webkit-box-shadow:none; box-shadow:none;">
                    <div class="item_m"><span class="caret"></span></div>
                    <ul class="clear_ul" >
                        <li><a href="../../maindashboard/html/MainDashboard.php" lang="en"><i class="icon-globe"></i> Home</a></li>

                        <li><a href="../../configuration/html/Configuration.php" lang="en"><i class="icon-cog"></i> Settings</a></li>
                        <li><a href="../../../ajax/logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
                    </ul>
                </div>
            </div>
            <!--Button User END-->  

        </div>
        </div>
    <!--Header END-->

    <!--Content Start-->
    <div id="content" style="padding-left:0px;">

        <!--- VENTANA MODAL  ---> 
        <button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button>
        <!--<div id="header-modal" class="modal hide" style="display: none; width: 80%; /* desired relative width */left: 10%; /* (100%-width)/2 */ /* place center */ margin-left:auto; margin-right:auto; " aria-hidden="true">-->
        <!--START SEND REFERRAL-->
        
        <div id="header-modal" class="modal hide" style="display:none; width:800px; left:20%; margin-left: auto; margin-right: auto;" aria-hidden="true">
            <div class="modal-header" style="height:65px;">
                <button class="close" type="button" data-dismiss="modal">×</button>
                <div style="width:90%; margin-top:12px; float:left; line-height:12px;">
                    <div id="selpat" style="width: 100px; margin-left:5%; float: left; color: rgb(61, 147, 224);">
                        <div style="font-size: 20px; font-weight: bold; width: 100%; margin-bottom:15px;">Step 1</div>
                        <span style="font-size: 12px; width: 100%;" lang="en">Select Member</span>
                    </div>
                    <div id="seldr" style="width:100px; margin-left:5%; float:left;">
                        <div style="font-size:20px; font-weight:bold; width:100%; margin-bottom:15px;">Step 2</div>
                        <span style="font-size:12px; width:100%;" lang="en">Select Doctor</span>
                    </div>
                    <div id="att" style="width:100px; margin-left:5%; float:left;">
                        <div style="font-size:20px; font-weight:bold; width:100%; margin-bottom:15px;">Step 3</div>
                        <span style="font-size:12px; width:100%;" lang="en">Attach Reports</span>
                    </div>
                    <div id="addcom" style="width:100px; margin-left:5%; float:left;">
                        <div style="font-size:20px; font-weight:bold; width:100%; margin-bottom:15px;">Step 4</div>
                        <span style="font-size:12px; width:100%;" lang="en">Add Comments</span>
                    </div>
                    <i id="attachment_icon" class="icon-paper-clip icon-4x" style="color:#ccc; margin-left:10px;font-size:30px"></i>
                </div>
            </div>
            <div class="modal-body" style="height:300px;">
                <div id="ContentScroller" style="width:100%; height:280px; overflow: hidden; ">
                    <div id="ScrollerContainer" style="width:2000px; height:275px; ">
                        <div id="content_selpat" style="float:left; width:400px; height:270px; ">
                            <p>
                                <!-- PATIENT SELECTION TABLE --->
                            <div class="grid" style="float:left; width:90%; height:265px; margin: 0 auto; margin-left:2%; margin-top:0px; margin-bottom:0px; overflow:auto;">
                                <div class="grid-title">
                                    <div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div>Select a Member</div>
                                    <img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">
                                    <div class="pull-right"></div>
                                    <div class="clear"></div>   
                                </div>
                                <div class="search-bar">
                                    <input type="text" class="span" placeholder="Search Member" style="width:150px;" id="SearchUserT"> 
                                    <input type="button" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaPacCOMP">
                                </div>
                                <div class="grid" style="margin-top:0px;">
                                    <table class="table table-mod" id="TablaPac" style="width:100%; table-layout: fixed; ">
                                    </table> 
                                </div>
                            </div>
                            <!-- PATIENT SELECTION TABLE --->
                            </p>
                        </div>    
                        <div id="content_seldr" style="float:left; width:400px; height:370px; ">
                            <p>

                                <!-- DOCTOR SELECTION TABLE --->
                            <div class="grid" style="float:left; width:90%; height:265px; margin: 0 auto; margin-left:2%; margin-top:0px; margin-bottom:0px; overflow:scroll;">

                                <div class="grid-title">
                                    <div class="pull-left" lang="en"><div class="fam-user-delete" style="margin-right:10px;"></div>If Doctor is not H2M User:</div>
                                    <input type="text" class="span" name="" placeholder="Doctor's email" style="margin:5px; margin-left:10px; width:32%;" id="DoctorEmail"> 
                                    <input type="button" class="btn btn-primary" value="Add" style="margin-top:7px; margin-left:7px;" id="AddNonUser">

                                    <div class="pull-right"></div>
                                    <div class="clear"></div>   
                                </div>

                                <div class="grid-title">
                                    <div class="pull-left" lang="en"><div class="fam-user-delete" style="margin-right:10px;"></div>Select a Doctor</div>

                                    <img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">

                                    <div class="pull-right"></div>
                                    <div class="clear"></div>   
                                </div>

                                <div class="search-bar">

                                    <input type="text" class="span" name="" placeholder="Search Doctor" style="width:150px;" id="SearchDoctor"> 
                                    <input type="button" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaMedCOMP">

                                </div>

                                <div class="grid" style="margin-top:0px;">
                                    <table class="table table-mod" id="TablaMed" style="width:100%; table-layout: fixed; ">
                                    </table> 

                                </div>

                            </div>
                            <!-- DOCTOR SELECTION TABLE --->

                            </p>
                        </div>
                        <div id="content_att" style="float:left; width:370px; height:50px; ">
                            <p>
                                <div id="Phase3Container" style="width:100%; height:290px; overflow: auto; margin-top:-20px;">
                                    <span style="margin-left:300px;margin-top:-10px;display:none;color:#22aeff" id="H2M_Spin_Stream"><i class="icon-spinner icon-2x icon-spin" ></i> 
                                    </span>
                                    <div id="ReportStream" style="">
                                    </div>
                                </div>       
                                <p id="NumberRA" style="color:#22aeff; font-size:16px; text-align:center; margin:0 auto; height:0px;">
                                </p>
                            </p>
                        </div>
                        <div id="content_addcom" style="float:left; width:550px; height:150px; padding:10px;">

                            <div style="height:40px; width:500px;margin-top:10px">	
                                <span style="margin-right:20px; font-size:14px; color:#22aeff; " lang="en"> Referral Type: </span>

                                <select id="referral_type">
                                    <option value="0" lang="en">Normal</option>
                                    <option value="1" lang="en">Opinion</option>
                                </select>
                            </div>

                            <div style="height:40px; width:500px;margin-top:10px">
                                <p style="float:left;">
                                    <input type="checkbox" id="c2" name="cc" style="width:140px;">
                                    <label for="c2" >
                                        <span></span>
                                    </label>
                                </p>

                                <span style="float:left; margin-left:20px; font-size:14px; color:#22aeff;" lang="en"> Urgent (Send SMS text message)</span>

                                <input type="text" id="cellphone" placeholder="cell phone number" style="width:180px; margin-left:20px; margin-top:-5px;" />
                            </div>
                            <div style="height:100px; width:500px;">
                                <p>
                                    <span style="margin-right:20px; font-size:14px; color:#22aeff;" lang="en"> Welcoming Message:</span>
                                    <textarea id="WelMes" type="textarea" style="width:450px; height:60px; margin-right:20px; font-size:14px; color:#54bc00;"></textarea>
                                </p>
                            </div>

                            <div style="height:40px; width:500px;margin-top:10px">
                                <p style="float:left;">
                                    <input type="checkbox" id="c2_doc" name="cc" style="width:140px;">
                                    <label for="c2_doc" >
                                        <span></span>
                                    </label>
                                </p>
                                    <span style="float:left; margin-left:20px; font-size:14px; color:#22aeff;" lang="en"> On Behalf of Dr:</span>
                                    <div id="fill_doctors" style="inline-block;"></div><!--style="margin-left:500px;margin-top:-25px"-->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="queId">
            <div id="footer-modal" style="height:120px;">
                <div style="height:80px; width:100%:">
                    <p id="TextoSend" style="text-align:center; margin-top:0px; ">
                        <span style="color:grey;" lang="en">Send </span>
                        <span style="color:#54bc00; font-size:30px;">      </span>
                        <span style="color:grey;" lang="en"> to </span>
                        <span style="color:#22aeff; font-size:30px;">     </span>
                    </p>
                </div>
                <div style="height:20px; width:90%; text-align: right; display:inline-block;">
                    <input type="button" class="btn btn-success" value="SEND" id="SendButton" style="width:100px; display:none;">
                    <input type="button" class="btn btn-success" value="NEXT" id="Attach" style="visibility: hidden;">
                    <a href="javascript:window.location.href=window.location.href" class="btn btn-danger" data-dismiss="modal" id="CloseModal"  lang="en">Cancel</a>
                    <input type="button" class="btn btn-info" value="Previous" id="PhasePrev">
                    <input type="button" class="btn btn-success" value="NEXT" id="PhaseNext" style="width:100px;">

                </div> 
            </div>
        </div>
        <!-- END OF REFERRAL MODAL-->
        
<!--- VENTANA MODAL  ---> 
<!--SpeedBar Start--->
<div class="speedbar">
    <div class="speedbar-content">
        <ul class="menu-speedbar">

            <li><a href="../../maindashboard/html/MainDashboard.php" lang="en">Home</a></li>
            <?php require_once("../../../../checkPageAccessControl.php");



$arr=checkAccessPage("dashboard.php");
$arr_d=json_decode($arr, true);

if((count($arr_d['items'])>0)&&($arr_d['items'][0]['accessid']==1)){ 

    echo '<li><a lang="en" href="dashboard.php"  lang="en">Dashboard</a></li>';
}
            ?>
            <!--li><a href="patients.php"  lang="en">Members</a></li-->
            <?php if ($user->doctor_privilege==1)
            {
                echo '<li><a href="../../medicalconnections/html/MedicalConnections.php"  class="act_link" lang="en">Doctor Connections</a></li>';
                echo '<li><a href="../../membernetwork/html/MemberNetwork.php"  lang="en">Member Network</a></li>';
            }
            ?>
            <li><a href="../../configuration/html/Configuration.php" lang="en">Configuration</a></li>
            <li><a href="../../../ajax/logout.php" style="color:yellow;" lang="en">Sign Out</a></li>
        </ul>


    </div>
</div>
<!--SpeedBar END-->


<!--CONTENT MAIN START-->
<div class="content">

    <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">

        <span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;" lang="en">Doctor Connections</span>

        <div style="float:right; margin-right:20px;">
            <label class="checkbox toggle candy blue" onclick="" style="width:100px; margin-left:0;">
                <input type="checkbox" id="Group_toggle" name="CRows"/>
                <p>
                    <span lang="en">Group</span>
                    <span lang="en">Me</span>
                </p>

                <a class="slide-button"></a>
            </label>
        </div>

        <div style="margin:15px; margin-top:5px;">
            <?php
// Sección para construir la información estadística del Médico (Dashboard: relativo a "packets")  
$r=0;
$EstadCanal = array(0,0,0,0,0,0,0);
$EstadCanalValid = array(0,0,0,0,0,0,0);
$EstadCanalNOValid = array(0,0,0,0,0,0,0);
$ValidationStatus = array(0,0,0,0,0,0,0,0,0,0);

$r=0;
while ($r<6)
{
    $EstadCanal[$r]= 1;
    $EstadCanalValid[$r]= 1;
    $EstadCanalNOValid[$r]= 1;
    $ValidationStatus[$r]= 1;
    $r++;
}	


// Variante para calcular de forma más ajustada el porcentaje de packetes sobre el total de los paquetes de los pacientes que han sido vistos por otros (EXPORTADOS) ((FORMA LARGA)

// Sección para construir la información estadística del Médico (Dashboard: relativo a "packets")  
$hash = md5( strtolower( trim( $MedUserEmail ) ) );
$avat = '../../../ajax/identicon.php?size=75&hash='.$hash;
            ?>	

            <div class="row-fluid"  style="">	            
                <input type="hidden" id ="quePorcentaje" value="<?php if(isset($porcentajeCreados)) echo $porcentajeCreados; ?>" />  
                <!--- MAIN GRAPHICAL DASHBOARD --->
                <div style="height:400px; text-align:center; margin:0 auto; margin-top:30px; margin-botom:-20px; border: 1px solid #cacaca; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;">
                    <div style="width:100%; margin-top:15px;">
                        <style>
                            i.navArrows{
                                color: #22aeff;
                            }
                            i.navArrows:hover{
                                color: #226fff;
                            }
                            i.navArrows:focus{
                                color: black;
                            }
                        </style>
                        <div id="CenterLabels" style="width:450px; margin:0 auto; text-align:center; padding-left:100px;">
                            <i id="LessPage" class="icon-chevron-sign-left icon-2x navArrows" ></i>
                            <span id="MyRadar" style="left: 0px; margin-left: 20px; margin-right: 20px; margin-top: 20px; margin-bottom: 5px; font-size: 16px; background-color: #22aeff; padding: 1px 4px 2px; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; font-size: 22px; font-weight: bold; line-height: 22px; color: #ffffff; white-space: nowrap; vertical-align: baseline; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;"> <span lang='en'>Referral's Radar</span>&copy</span>
                            <i id="MorePage" class="icon-chevron-sign-right icon-2x navArrows"></i>
                            <span id="pageNumber" style="font-size:18px; color:#22aeff; float:right; margin-top:5px;"><span lang="en">page</span> 1/3</span>
                        </div>
                    </div>
                    <div style="width:300px; height:350px; float:left; padding-top:50px; /*border:solid;*/" id="BoxLeft">
                        <?php 
$indice=0;
$indiceM = 0;
$PacketsVistos = 0; 
$C5='rgba(105,120,250,';
$C0='rgba(115,187,59,';
//rgba(115,187,59,0.99)
                        ?>
                        <!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
                        <div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:30px;" title="This figure indicates the number of individual members that have accessed information created by this user">

                            <div style="height:50px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
                                <p id="NReferrals" style=" font-size:<?php echo queFuente(12);?>px; font-weight:bold; color: <?php echo $C5.'0.99)' ?>; margin-top:30px; font-family:Arial;"><?php echo $indice ?></p>
                            </div>

                            <div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C5.'0.99)' ?>; border:1px solid <?php echo $C5.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
                                <p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">Referrals</p>
                            </div>	

                        </div>
                        <!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
                        <!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
                        <div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px; " title="This figure indicates the number of individual doctors that have accessed information created by this user">

                            <div style="height:50px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
                                <p id="NPatients" style=" font-size:<?php echo queFuente(25);?>px; font-weight:bold; color: <?php echo $C0.'0.99)' ?>;  margin-top:30px; font-family:Arial;"><?php echo $indiceM ?></p>
                            </div>

                            <div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C0.'0.99)' ?>; border:1px solid <?php echo $C0.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
                                <p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">Members</p>
                            </div>	

                        </div>
                        <!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
                        <!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
                        <div style="margin-top:15px; margin-left:45px; width:180px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; " title="This figure indicates the number of individual pieces of information (packets) created by this user that have benn accessed in total">

                            <div style="height:80px; width:180px;  text-align:center; margin:0px; display: table;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
                                <p id="NTime2Visit" style=" font-size:<?php echo queFuente(3);?>px; font-weight:bold; color: rgba(255,66,66,0.99);  margin-top:0px; font-family:Arial; font-size:<?php echo queFuente(3)/2;?>px; display: table-cell; vertical-align: middle;"><?php echo $PacketsVisto; ?> <span lang="en">days</span></p>
                            </div>

                            <div style="width:180px;  text-align:center; margin:0px; background-color: rgba(255,66,66,0.99); border:1px solid rgba(255,66,66,0.99); margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
                                <p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">Time to Visit</p>
                            </div>	

                        </div>
                        <!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->


                    </div>

                    <canvas id="myCanvas" width="450" height="350" style="width:0px; height:0px; margin:0 auto; /*border:solid;*/ ">
                    </canvas>
                    <div id="CanvasContainer" style="width:450px; height:350px; margin:0 auto; float:left; "></div>

                    <div style="width:200px; height:300px; float:right; text-align:left; padding-top:100px;" id="BoxRight">

                        <!--<div id="DrName" style="font-family: 'Cabin'; color: #3d93e0; font-size:30px; font-weight:bold; width:100%; "></div>-->
                        <div id="DrName" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #3d93e0; font-size:20px; font-weight:bold; width:100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></div>
                        <div style="width:100%; margin-top:5px;"></div>
                        <span id="DrEmail" style="margin-top:10px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #3d93e0; font-size:15px; width:100%;"></span>
                        <!--
<span id="DrName" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:20px; font-weight:bold; color:#3d93e0;">Dr. Nameajdh Surnamelkd</span>   
<div style="width:100%;"></div>
<span id="DrEmail" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:14px;  color:#3d93e0;">namea.surnamelkd@mail.com</span>   
-->		             	
                        <div style="width:100%; margin-bottom:15px;"></div>
                        <span id="DrPatients" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:20px;  color:rgb(115,187,59); font-weight:bold;"></span>   
                        <span id="AdditHtml" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:16px;  color:grey;"></span>   
                        <div style="width:100%;"></div>
                        <span id="DrTtoVText" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:16px;  color:grey;"></span>   
                        <span id="DrTtoV" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:20px;  color:rgb(115,187,59); font-weight:bold;"></span>   

                        <div style="width:100%; margin-bottom:45px;"></div>
                        <!--
<span id="DrName" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:20px; font-weight:bold; color:rgb(115,187,59);">Patname Patsurname</span>   
<span id="DrEmail" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:14px;  color:rgb(115,187,59);">npatnaa.supatied@mail.com</span>   
<div style="width:100%; margin-bottom:15px;"></div>
<span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:14px;  color:grey;"> Time to Visit: </span>   
<span id="DrTtoV" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:18px;  color:#3d93e0;">5 days</span>   
-->

                    </div>

                </div>
            </div>	


        </div>

        



    <!--div class="grid" style="height: auto; overflow: none; text-align: center; margin-top: 0;"-->
        <!--Tabs Start-->
        <div style="margin:10px; margin-top:-30px;" >
            <!--TAB Start-->
            <ul id="myTab" class="nav nav-tabs tabs-main">
                <li class="active" style="width:50%; " onclick = "drawReferredOutRadar();"><a href="#sendR" data-toggle="tab" lang="en">Referred OUT<button type="button" class="btn btn-success" id="BotonWizard" lang="en" style="float:right; padding: 1px 14px;" lang="en">Refer a Member</button></a></li>
                <li style="width:50%; " onclick = "drawReferredInRadar();"><a href="#getR" data-toggle="tab" lang="en">Referred IN</a></li>
            </ul> 
            <div id="myTabContent" class="tab-content tabs-main-content padding-null">
                <div class="tab-pane tab-overflow-main fade in active" id="sendR">

                    <div class="grid" style="float:left; width:95%; margin: 0 auto; margin-left:2%; margin-top:10px; margin-bottom:30px;">

                    
                                
                                <!--div class="pull-right"></div>
                                <div class="clear"></div>   
                            </div>
                        </div--> 

                        <div class="search-bar">
                            <div class="pull-left" style="font-weight:bold; margin:5px 30px 10px 10px;"><div class="fam-user" style="margin-right:10px;" lang="en"></div><span lang="en">Referrals List & Status</span></div>
                            <input type="text" class="span" name="" lang="en" placeholder="Search Referred Out" style="width:150px;" id="SearchUserUSERFIXED"> 
                            <input type="button" class="btn btn-primary" lang="en" value="Filter" style="margin-left:50px;" id="BotonBusquedaSents">
                            
                            <span id="radartext" style="margin-left:50px;font-size:18px; color:#22aeff;"></span>
                            <input type="button" class="btn btn-primary" lang="en" value="Reset" style="margin-left:50px;display:none" id="BotonBusquedaReset">
                            <img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait11">
                            <div style="float:right; margin-right:20px;">
                                <label class="checkbox toggle candy blue" onclick="" style="width:100px; margin-left:0;">
                                    <input type="checkbox" id="Cstate" name="CRows"/>
                                    <p>
                                        <span lang="en">Old</span>
                                        <span lang="en">Active</span>
                                    </p>

                                    <a class="slide-button"></a>
                                </label>
                            </div>
                        </div>

                        <div class="grid" style="margin-top:0px;">
                            <table class="table table-mod" id="TablaSents" style="width:100%; table-layout: fixed; ">
                            </table> 

                        </div>

                    </div>
                        


                    <p id="TextoSend" style="text-align:center;"></p>





                </div>

                <div class="tab-pane" id="getR">

                    <div class="grid" style="float:left; width:95%; margin: 0 auto; margin-left:2%; margin-top:10px; margin-bottom:30px;">
                        <div class="grid-title">
                            <div class="pull-left" lang="en"><div class="fam-user" style="margin-right:10px;"></div>Connection Permits List</div>

                            <img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">

                            <div class="pull-right"></div>
                            <div class="clear"></div>   
                        </div>

                        <div class="search-bar">

                            <input type="text" class="span" name="" lang="en" placeholder="Search Member" style="width:150px;" id="SearchUserPermits"> 
                            <input type="button" class="btn btn-primary" lang="en" value="Filter" style="margin-left:50px;" id="BotonBusquedaPermit">
                            <span id="radartext2" style="margin-left:50px;font-size:18px; color:#22aeff;"></span>
                            <input type="button" class="btn btn-primary" lang="en" value="Reset" style="margin-left:50px;display:none" id="BotonBusquedaReset2">
                            <img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait12">
                        </div>

                        <div class="grid" style="margin-top:0px;">
                            <table class="table table-mod" id="TablaPermit" style="width:100%; table-layout: fixed; ">
                            </table> 

                        </div>

                    </div>
                </div>
                <!--TAB END-->
            </div>
            <!--Tabs END-->  
            <?=$user->footer_copy;?>
        </div>
        <!--CONTENT MAIN END-->

    </div> 
</div>
<!--Content END-->
<div id="footer" style="margin:20px;">
    <div id="center_footer">
        
    </div>
</div> 
<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>-->
<script src="../../../../js/jquery.min.js"></script>
<script src="../../../../js/jquery-ui.min.js"></script>



<script src="../../../../realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
<link href="../../../../realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
<!--<script src="realtime-notifications/pusher.min.js"></script>
<script src="realtime-notifications/PusherNotifier.js"></script>-->
<script src="../../../../js/socket.io-1.3.5.js"></script>
<script src="../../../../push/push_client.js"></script>
<script src="../../../../js/kinetic-v4.5.5.min.js"></script>


<script>
    $(function() {
        
        function getScript(url)
        {
            $.ajax(
            {
                url: url,
                dataType: "script",
                async: false
            });
        }

        function displaynotification(status,message){
            getScript('../../../../realtime-notifications/lib/gritter/js/jquery.gritter.min.js');
            var gritterOptions = {
                                    title: status,
                                    text: message,
                                    image:'images/Icono_H2M.png',
                                    sticky: false,
                                    time: '3000'
                                 };
            $.gritter.add(gritterOptions);

        }
        
        //var pusher = new Pusher('d869a07d8f17a76448ed');
        //var channel_name=$('#MEDID').val();
        //var channel = pusher.subscribe(channel_name);
        
        var push = new Push($("#MEDID").val(), window.location.hostname + ':3955');
        
        push.bind('notification', function(data) {
            displaynotification('New Message', data);
        });
        
        $("#BotonWizard").click(function(event) {
            phase = 1;
            phaseReached = 1;
            numReports = 0;
            $("#PhaseNext").attr("disabled", "true");
            $("#PhasePrev").attr("disabled", "true");
            var ancho = $("#header-modal").width()*0;
            $("#ContentScroller").animate({ scrollLeft: ancho}, 175);
            paciente='';
            destino='';
            TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+destino+'   </span>';
            if (paciente>'' && destino>'') TextoS = TextoS + '';
            IsOnCounter = 0;
            IsOnCounter2 = 0;
            $('#TextoSend').html(TextoS);
            $("#selpat").css("color","rgb(61, 147, 224)");
            $("#seldr").css("color","#ccc");
            $("#att").css("color","#ccc");
            $("#addcom").css("color","#ccc");
            $('#BotonModal').trigger('click');
            $('#SearchUserT').value = '';
            $('#SearchDoctor').value = '';
            $('#DoctorEmail').value = '';
            $('#TablaPac').empty();
            $('#TablaMed').empty();
        });
        
        //WHEN RETURN KEY IS PUSHED WHILE #SearchUserT HAS BEEN FOCUSED, IT TRIGGERS THE FILTER BUTTON AT THE RIGHT
        $('#SearchUserT').keypress(function(e){
            if (e.which == 13){
                $("#BotonBusquedaPacCOMP").click();
            }
        });
        $('#SearchDoctor').keypress(function(e){
            if (e.which == 13){
                $("#BotonBusquedaMedCOMP").click();
            }
        });

    });

</script>
<script type="text/javascript" >

    var paciente='';
    var destino='';
    var Nondestino='';
    var IdPaciente = -1;
    var IdDoctor = -1;

    var timeoutTime = 18000000;
    //var timeoutTime = 300000;  //5minutes
    var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


    var active_session_timer = 60000; //1minute
    var sessionTimer = setTimeout(inform_about_session, active_session_timer);


    $(window).load(function() {
        //alert("started");
        $(".loader_spinner").fadeOut("slow");
    })

    //This function is called at regular intervals and it updates ongoing_sessions lastseen time
    function inform_about_session()
    {
        $.ajax({
            url: '../../../../ongoing_sessions.php?userid='+<?php echo $_SESSION['MEDID'] ?>,
            success: function(data){
            //alert('done');
        }	
               });
        clearTimeout(sessionTimer);
        sessionTimer = setTimeout(inform_about_session, active_session_timer);
    }

    function ShowTimeOutWarning()
    {
        alert ('Session expired');
        var a=0;
        window.location = 'timeout.php';
    }

    // RADAR: INITIAL LOADING OF ARRAY AND LAYER SETUP
    var stageScenery = new Kinetic.Stage({
        container: 'CanvasContainer',
        width: 450,
        height: 350
    });

    var layer = new Kinetic.Layer();
    var layerDr = new Kinetic.Layer();
    var layerEvents = new Kinetic.Layer();
    var layerArcs = new Kinetic.Layer();
    var unidad = (Math.PI * 0.1);

    var PatientName = Array();
    var PatientStage = Array();
    var PatientDoctor = Array();
    var PatientDoctorFULL = Array();
    var DoctorId = Array();
    var DoctorEmail = Array();
    var PatientTTV = Array();
    var DoctorsNames = Array();
    var DoctorsNamesFULL = Array();
    var DoctorsEmails = Array();

    var IsOn = Array();
    var IsOnCounter = 0;
    var IsOnCounter2 = 0;
    var group=0;

    //if($('#Group_toggle').is(":checked")) group=1;

    /*  getReferredPatients('getReferredPatients.php?group=0');
                        $thisreco =0;
                        while ($thisreco <  ReferredPatients.length)
                        {
                            PatientName[$thisreco] = ReferredPatients[$thisreco].PatientName;
                            PatientStage[$thisreco] = ReferredPatients[$thisreco].PatientStage;
                            PatientDoctor[$thisreco] = ReferredPatients[$thisreco].PatientDoctor;
                            PatientDoctorFULL[$thisreco] = ReferredPatients[$thisreco].PatientDoctorFULL;
                            DoctorId[$thisreco] = ReferredPatients[$thisreco].IdMED2;
                            DoctorEmail[$thisreco] = ReferredPatients[$thisreco].EmailDoctor;
                            if ( PatientDoctor[$thisreco] < "   ")  PatientDoctor[$thisreco]=DoctorEmail[$thisreco];
                            PatientTTV[$thisreco] = ReferredPatients[$thisreco].TTV;
                            DoctorsNames[DoctorId[$thisreco]] = PatientDoctor[$thisreco];
               			    DoctorsNamesFULL[DoctorId[$thisreco]] = PatientDoctorFULL[$thisreco];
               			    DoctorsEmails[DoctorId[$thisreco]] = DoctorEmail[$thisreco];
               			    $thisreco ++;
                        }*/


    function getInitialRadarData(){
        group=0;
        if($('#Group_toggle').is(":checked")) group=1;

        //getReferredPatients('getReferredPatients.php?group='+group);
        getReferredPatients('../../../ajax/getReferredPatients.php?group=0');
        $thisreco =0;
        while ($thisreco <  ReferredPatients.length)
        {
            PatientName[$thisreco] = ReferredPatients[$thisreco].PatientName;
            PatientStage[$thisreco] = ReferredPatients[$thisreco].PatientStage;
            PatientDoctor[$thisreco] = ReferredPatients[$thisreco].PatientDoctor;
            PatientDoctorFULL[$thisreco] = ReferredPatients[$thisreco].PatientDoctorFULL;
            DoctorId[$thisreco] = ReferredPatients[$thisreco].IdMED2;
            DoctorEmail[$thisreco] = ReferredPatients[$thisreco].EmailDoctor;
            if ( PatientDoctor[$thisreco] < "   ")  PatientDoctor[$thisreco]=DoctorEmail[$thisreco];
            PatientTTV[$thisreco] = ReferredPatients[$thisreco].TTV;
            DoctorsNames[DoctorId[$thisreco]] = PatientDoctor[$thisreco];
            DoctorsNamesFULL[DoctorId[$thisreco]] = PatientDoctorFULL[$thisreco];
            DoctorsEmails[DoctorId[$thisreco]] = DoctorEmail[$thisreco];
            $thisreco ++;
        }
    }

    getInitialRadarData();

    var DrColor = Array();

    // Create array for Referrals (doctors)
    var ReferralId = Array();
    var ReferralName = Array();
    var ReferralNameFULL = Array();
    var ReferralStaP = Array();
    var ReferralEndP = Array();
    var ReferralNumPats = Array();
    var ReferralTTV = Array();
    var brokenReferral = Array();
    $thisreco =0;
    var ReferralNumber = 0;
    var LatestPatient = 0;
    var LatestDoctor = 0;
    var SumTimes = 0;
    var MeanTTV = 0;
    
    while ($thisreco <=  ReferredPatients.length)
    {
        //console.log('Patient:'+$thisreco+'  Name: '+PatientName[$thisreco] +'  TTV: '+PatientTTV[$thisreco]);

        if ($thisreco > 0 && (DoctorId[$thisreco] != LatestDoctor))
        {
            ReferralStaP[ReferralNumber] = LatestPatient;
            ReferralEndP[ReferralNumber] = $thisreco-1;

            ReferralId[ReferralNumber] = DoctorId[$thisreco-1];
            ReferralName[ReferralNumber] = DoctorsNames[DoctorId[$thisreco-1]];
            ReferralNameFULL[ReferralNumber] = DoctorsNamesFULL[DoctorId[$thisreco-1]];

            ReferralNumPats[ReferralNumber] = ReferralEndP[ReferralNumber] - ReferralStaP[ReferralNumber] + 1;
            ReferralTTV[ReferralNumber] = SumTimes;

            MeanTTV = MeanTTV + Math.round(ReferralTTV[ReferralNumber]/ReferralNumPats[ReferralNumber]);
                    //console.log('Sumtimes: '+SumTimes); 
            SumTimes = 0;		
            
								console.log('- NEW REFERRAL: -' ); 
								console.log(ReferralNumber ); 
								console.log(ReferralId[ReferralNumber] ); 
								console.log(ReferralName[ReferralNumber] ); 
								console.log(ReferralStaP[ReferralNumber] ); 
								console.log(ReferralEndP[ReferralNumber] ); 
								console.log('' ); 
								
            LatestPatient = $thisreco;
            
            ReferralNumber++;
        }

        LatestDoctor = DoctorId[$thisreco];
        SumTimes = SumTimes + parseInt(PatientTTV[$thisreco]);
        $thisreco ++;
    }


    DrColor[0]='rgb(255,66,66)';
    DrColor[1]='rgb(105,120,250)';
    DrColor[2]='orange';
    DrColor[3]='rgb(255,66,166)'; 
    DrColor[4]='rgb(122,199,59)';
    DrColor[5]='rgb(115,100,59)';
    DrColor[6]='rgb(115,150,59)';
    DrColor[7]='rgb(115,250,59)';
    DrColor[8]='rgb(115,187,59)';
    DrColor[9]='rgb(115,187,159)';
    // RADAR: INITIAL LOADING OF ARRAY AND LAYER SETUP


    function getReferredPatients(serviceURL) 
    {
        //var ReferredPatients='';
        $.ajax(
            {
                url: serviceURL,
                dataType: "json",
                async: false,
                success: function(data)
                {
                    //alert('Data Fetched');
                    ReferredPatients='';
                    ReferredPatients = data.items;
                    //console.log('show the data:');
                    //console.log(data.items);
                }
            });
    }

    var NPatients;
    var NReferrals;
    var MaxPatDisplay;    
    $(document).ready(loadRadarData);

    function loadRadarData() 
    {
        // Draw Radar for the first time
        NPatients = $thisreco-1;
        NReferrals = ReferralNumber;
        MaxPatDisplay = 20;
        if (NPatients>MaxPatDisplay)
        {
            Pages = Math.ceil(NPatients/MaxPatDisplay);
            PatientsPage = Math.ceil(NPatients/Pages);
        }
        else
        {
            Pages = 1;
            PatientsPage = NPatients;
            $("#MorePage").css('visibility','hidden');
            $("#LessPage").css('visibility','hidden');
            $("#pageNumber").css('visibility','hidden');
        }
        //alert (' Referrals: '+NReferrals+' Patients: '+NPatients+' Pages: '+Pages+' at '+PatientsPage+' per page' );  
        ActualPage = 1;
        RadarPage();
        // Draw Radar for the first time
    }
    $("#MorePage").click(function(event) {
        if (ActualPage < Pages) 
        {
            ActualPage++; 
            RadarPage();
        }
    });

    $("#LessPage").click(function(event) {
        if (ActualPage > 1) 
        {
            ActualPage--; 
            RadarPage();
        }
    });

    function RadarPage()
    {
        layer.removeChildren();
        layerDr.removeChildren();
        layerEvents.removeChildren();
        layerArcs.removeChildren();
        layer.draw();
        layerDr.draw();
        layerEvents.draw();
        layerArcs.draw();
        PatIni = (ActualPage-1) * PatientsPage;
        PatEnd = (ActualPage) * PatientsPage;
        DrawRadarPatients (PatIni,PatEnd);
        $("#pageNumber").html('Page '+ActualPage+'/'+Pages);
    }

    function DrawStats(init,end)
    {
        var daystrans = '';

        if(language == 'th'){
            daystrans = 'días';
        }else if(language == 'en'){
            daystrans = 'days';
        }

        
        $("#NReferrals").html(ReferralNumber);
        $("#NPatients").html($thisreco-1);
        $("#NTime2Visit").html(Math.round(MeanTTV/ReferralNumber)+' '+daystrans);
    }			

    var InOutFlag = 'out';
    
    function DrawRadarPatients (startingPatient, endingPatient)
    {
        var centerX = 200;
        var centerY = 175;
        var stagenum = 0;
        // Draw patients section 

        // This puts a limit if the endingpatient value is above maximum number of patients
        var endSequence = parseInt(endingPatient);
        if (endSequence >= PatientName.length) endSequence = parseInt(PatientName.length)-1;

        var NumberOfItems = endSequence - startingPatient + 1;
        if (NumberOfItems !=  0) unidad = (Math.PI * 2 / NumberOfItems); else  unidad = (Math.PI);
        var canvas = $("#myCanvas");
        var context = canvas.get(0).getContext("2d");
        context.lineWidth = 1;
        context.beginPath(); // Start the path
        context.moveTo(200, 175); // Set the path origin

        var n = startingPatient;
        /*
			console.log(' ************************************ ');
			console.log('From: '+n+'  To: '+endingPatient);
			console.log('Total patients: '+PatientName.length);
			*/

        while (parseInt(n) <= endSequence)
        {
            drawingOrder = n - startingPatient;
            stage = PatientStage[n] ;
            switch (stage)
            {
                case '': 	stagenum = 25;
                    break;
                case '1': 	stagenum = 25;
                    break;
                case '2': 	stagenum = 50;
                    break;
                case '3': 	stagenum = 75;
                    break;
                case '4': 	stagenum = 100;
                    break;
            }
            var angleShift = -(Math.PI*.5)+(drawingOrder*unidad);

            var wedgeP = new Kinetic.Wedge({
                x: centerX,
                y: centerY,
                radius: stagenum,
                angle: unidad,
                fillRadialGradientStartPoint: 0,
                fillRadialGradientStartRadius: 0,
                fillRadialGradientEndPoint: 0,
                fillRadialGradientEndRadius: stagenum,
                fillRadialGradientColorStops: [0, '#cacaca', 1, 'grey'],
                stroke: '#808080',
                strokeWidth: 1,
                rotation: angleShift,
                opacity:0.9
            });
            // add the shape to the layer
            layer.add(wedgeP);
            //			          fillRadialGradientColorStops: [0, 'red', 0.5, 'yellow', 1, 'blue'],

            wedgeP.on('touchstart click mouseover', function(evt) {
                var shape = evt.targetNode;
                this.setOpacity(0.8);
                this.setStroke('black');
                this.setStrokeWidth(2);
                layer.draw();	
                m = this.index;
                RealIndex = parseInt(m) + parseInt(startingPatient);
                
                
                $('#DrName').html(RealIndex + ' ' + PatientName[RealIndex]);
                $('#DrEmail').html(DoctorsNames[DoctorId[RealIndex]]);
                this.setOpacity(0.4);
                this.setStroke(DrColor[RealIndex]);
                this.setStrokeWidth(1);						
                //arc.setStrokeWidth(10);
            });

            //console.log(NumberOfItems+' Items  '+n+'   PatientName:'+PatientName[n]+'   DoctorName:'+ReferralName[n]+'    stagenum:'+stagenum+'   angleshift:'+angleShift);
            
            n++;	
        }
        stageScenery.add(layer);
        //console.log(ReferralName);
        // Draw doctors section (referrals)
        n = 0;
        maxReferrals = ReferralId.length;
        //console.log('***************************************');
        var countseries = 0;
        while (n <= maxReferrals)
        {
            decena = parseInt(n/10);
            secColor = n - (decena*10);

            mustDrawReferral = 0;
            if ((ReferralEndP[n] >= startingPatient) && (ReferralStaP[n] <= endSequence)) mustDrawReferral = 1; else mustDrawReferral = 0; 
            //console.log ('REFERRAL: '+n+'        ReferralEndPatient: '+ReferralEndP[n]+'     startingPatient: '+startingPatient);
            if (mustDrawReferral == 1)
            {
                countseries++;
                startDrawPat = ReferralStaP[n]- startingPatient;
                if (countseries == 1) {startDrawPat = 0; var FirstReferral = n;}

                if (ReferralEndP[n] <= endSequence) 
                {
                    endDrawPat = ReferralEndP[n] - startingPatient; 
                    brokenReferral[n] = 0;
                }
                else 
                {
                    endDrawPat = endSequence - startingPatient;
                    brokenReferral[n] = 1;
                }
                var inicio = -(Math.PI*.5) + (startDrawPat*unidad);
                var final =  -(Math.PI*.5) + (endDrawPat*unidad);							
                // 'wedge' is the pie slice that corresponds to a referral (doctor)	
                referralWidth = unidad * (endDrawPat-startDrawPat+1); 
                var wedge = new Kinetic.Wedge({
                    x: 200,
                    y: 175,
                    radius: 100,
                    angle: referralWidth,
                    fill: DrColor[secColor],
                    stroke: DrColor[secColor],
                    strokeWidth: 1,
                    rotation: inicio,
                    name: 'cosa',
                    opacity:0.40
                });

                layerEvents.add(wedge);

                // 'arc' is the external arc outside the pie, representing a referral (doctor)

                DrawArcRef (inicio+0.1,inicio+referralWidth-0.1,DrColor[secColor]);

                // Position the label for the Doctor
                TextFontsize = 12;
                context.font = 'bold 12px Helvetica';
                DrName = ReferralName[n].substr(0,12);
                DrNameFULL = ReferralNameFULL[n].substr(0,12);
                //DrName = n + ' ' + DrName;
                if (ReferralName[n].length>11) DrName = DrName+'..';
                var midArc = inicio + ((final-inicio)/2) ;
                var newX = 200 + (140 * Math.cos(midArc)) ;
                var newY = 175 + (140 * Math.sin(midArc)) ;
                var textmetrics = context.measureText(DrName);
                var textwidth = textmetrics.width;
                var textheight = TextFontsize * 1.5;
                if (newX < 200) 
                {
                    newX = newX - textwidth;
                }
                if (newY < 175)
                {
                    var basePos = 'bottom';
                    var iniBox = 15 ;
                } 
                else
                {
                    var basePos = 'top';
                    var iniBox = 0;
                }

                // Draw Box around text

                context.beginPath();
                context.rect(newX-5, newY-iniBox, textwidth+10, 16);
                var rect = new Kinetic.Rect({
                    x: newX-5,
                    y: newY-iniBox,
                    width: textwidth+10,
                    height: 16,
                    fill: DrColor[secColor],
                    stroke: 'grey',
                    strokeWidth: 1,
                    opacity:0.8
                });

                // add the shape to the layer
                layerDr.add(rect);
                context.fillStyle = DrColor[secColor];
                context.fill();
                context.lineWidth = 1;
                context.strokeStyle = DrColor[secColor];
                context.stroke();


                context.fillStyle = 'white';
                context.font = 'bold 12px Helvetica';
                context.textBaseline = basePos;
                //context.fillText(DrName, 50, 100);
                context.fillText(DrName, newX, newY);
                var simpleText = new Kinetic.Text({
                    x: newX,
                    y: newY-iniBox+2,
                    text: DrName,
                    fontSize: 12,
                    fontFamily: 'Helvetica',
                    fill: 'white'
                });
                // add the shape to the layer
                layerDr.add(simpleText);

                // Add interaction
                wedge.on('touchstart click mouseover', function(evt) {
                    document.body.style.cursor = 'pointer';		
                    var shape = evt.targetNode;
                    this.setOpacity(0.6);
                    this.setStroke('black');
                    this.setStrokeWidth(2);
                    layerEvents.draw();

                    m = this.index;
                    RealM = m + FirstReferral;
                    if (brokenReferral[m] == 0)  
                        $('#DrName').html(ReferralNameFULL[RealM]); 
                    else 
                        $('#DrName').html(ReferralNameFULL[RealM]+'../.');

                    var membertrans = '';
                    var timetovisit = '';
                    var daystrans = '';

                    if(language == 'th'){
                        membertrans = 'Miembros';
                        timetovisit = 'Tiempo hasta la Visita';
                        daystrans = 'días';
                    }else if(language == 'en'){
                        membertrans = 'Members';
                        timetovisit = 'Time to Visit';
                        daystrans = 'days';
                    }

                    $('#DrEmail').html(DoctorsEmails[m]);
                    $('#DrPatients').html(ReferralNumPats[RealM]);
                    $('#AdditHtml').html(' '+membertrans);
                    //$('#DrTtoV').html(MeaMean nTTV.toFixed(1)+' days');
                    $('#DrTtoVText').html(timetovisit+': '+Math.round(ReferralTTV[RealM]/ReferralNumPats[RealM])+' '+daystrans);
                    //this.setFill(Acolor);
                    this.setOpacity(0.4);
                    this.setStroke(DrColor[m]);
                    this.setStrokeWidth(1);		
                    //arc.setStrokeWidth(10);
                });

                wedge.on('mouseout', function() {		//Added by Ankit
                    document.body.style.cursor = 'default';
                });
                wedge.on('click', function() {
                    var m = this.index;
                    //var RealM = m + FirstReferral;
                    var RealM = parseInt(m) + parseInt(FirstReferral);
                    var DrId = ReferralId[RealM];
					console.log(DrId);
                    //console.log(' IdOrden: '+RealM+'   IdDoctor'+DrId+'  Name: '+ReferralName[RealM]);

                    var IdMed = $('#MEDID').val();
                    var UserDOB='';
                    var queUrl ='';
                    
                    //DISTINGUISHED BY InOutFlag TO CHANGE THE QUERY
                    if(InOutFlag == 'out') {
                        queUrl = '../../../ajax/getSents.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3&ToDoc='+DrId+'&state=2';
                        $('#TablaSents').load(queUrl);
                        $('#TablaSents').trigger('update');
                    }
                    else {
                        queUrl ='../../../ajax/getPermits.php?Doctor='+IdMed+'&DrEmail=&NReports=3&ToDoc='+DrId;
                        $('#TablaPermit').load(queUrl);
                        $('#TablaPermit').trigger('update');  
                    }
                    
                    var txt='<b> Dr. '+DoctorsNames[DrId] + '</b>';
                    $('#radartext').html(txt);
                    $('#BotonBusquedaReset').show();
                    $('#BotonBusquedaReset').show();
                    $('#radartext2').html(txt);
                    $('#BotonBusquedaReset2').show();
                    
                });


            }

            /*
				console.log('- REFERRAL drawing: -' ); 
				console.log(n); 
				console.log(ReferralName[n]); 
				console.log('Starts in: '+ReferralStaP[n]+' Ends In:'+ReferralEndP[n]); 
				console.log(mustDrawReferral); 
				console.log('' ); 
				*/
            n++;
        }


        stageScenery.add(layer);
        stageScenery.add(layerDr);
        stageScenery.add(layerEvents);
        stageScenery.add(layerArcs);


    }

    function DrawArcRef (ArcStart,ArcEnd,ArcColor)
    {
        var arc = new Kinetic.Shape({
            drawFunc: function(canvas) {
                var context = canvas.getContext();
                var context = canvas.getContext('2d');
                context.globalAlpha=0.7; // 0.8 opacity
                context.beginPath();
                context.arc(200, 175, 120, ArcStart, ArcEnd, false);
                canvas.stroke(this);
            },
            fill: ArcColor,
            stroke: ArcColor,
            opacity:0.7,
            strokeWidth: 10,
        });
        layerArcs.add(arc);	    
    }


    function drawReferredOutRadar()
    {
        $thisreco = 0;
        PatientName = Array();
        PatientStage = Array();
        PatientDoctor = Array();
        PatientDoctorFULL = Array();
        DoctorId = Array();
        DoctorEmail = Array();
        PatientTTV = Array();
        DoctorsNames = Array();
        DoctorsNamesFULL = Array();
        DoctorsEmails = Array();
        InOutFlag = 'out';
        var group=0;

        if($('#Group_toggle').is(":checked")) group=1;

        getReferredPatients('../../../ajax/getReferredPatients.php?group='+group);
        // getReferredPatients('getReferredPatients.php?group=1');



        //alert('count:'+ ReferredPatients.length);
        //alert('patientnamearray:'+PatientName.length);

        while ($thisreco <  ReferredPatients.length)
        {
            PatientName[$thisreco] = ReferredPatients[$thisreco].PatientName;
            PatientStage[$thisreco] = ReferredPatients[$thisreco].PatientStage;
            PatientDoctor[$thisreco] = ReferredPatients[$thisreco].PatientDoctor;
            PatientDoctorFULL[$thisreco] = ReferredPatients[$thisreco].PatientDoctorFULL;
            DoctorId[$thisreco] = ReferredPatients[$thisreco].IdMED2;
            DoctorEmail[$thisreco] = ReferredPatients[$thisreco].EmailDoctor;
            if ( PatientDoctor[$thisreco] < "   ")  PatientDoctor[$thisreco]=DoctorEmail[$thisreco];
            PatientTTV[$thisreco] = ReferredPatients[$thisreco].TTV;
            DoctorsNames[DoctorId[$thisreco]] = PatientDoctor[$thisreco];
            DoctorsNamesFULL[DoctorId[$thisreco]] = PatientDoctorFULL[$thisreco];
            DoctorsEmails[DoctorId[$thisreco]] = DoctorEmail[$thisreco];
            $thisreco ++;
        }

        $thisreco = 0;
        ReferralId = Array();
        ReferralName = Array();
        ReferralNameFULL = Array();
        ReferralStaP = Array();
        ReferralEndP = Array();
        ReferralNumPats = Array();
        ReferralTTV = Array();
        brokenReferral = Array();
        ReferralNumber = 0;
        LatestPatient = 0;
        LatestDoctor = 0;
        SumTimes = 0;
        MeanTTV = 0;


        while ($thisreco <=  ReferredPatients.length)
        {
            if ($thisreco > 0 && (DoctorId[$thisreco] != LatestDoctor))
            {
                ReferralStaP[ReferralNumber] = LatestPatient;
                ReferralEndP[ReferralNumber] = $thisreco-1;

                ReferralId[ReferralNumber] = DoctorId[$thisreco-1];
                ReferralName[ReferralNumber] = DoctorsNames[DoctorId[$thisreco-1]];
                ReferralNameFULL[ReferralNumber] = DoctorsNamesFULL[DoctorId[$thisreco-1]];

                ReferralNumPats[ReferralNumber] = ReferralEndP[ReferralNumber] - ReferralStaP[ReferralNumber] + 1;
                ReferralTTV[ReferralNumber] = SumTimes;

                MeanTTV = MeanTTV + Math.round(ReferralTTV[ReferralNumber]/ReferralNumPats[ReferralNumber]);

                SumTimes = 0;		

                LatestPatient = $thisreco;
                
                ReferralNumber++;
            }
            LatestDoctor = DoctorId[$thisreco];
            SumTimes = SumTimes + parseInt(PatientTTV[$thisreco]);
            $thisreco ++;
        }

        NPatients = $thisreco-1;
        NReferrals = ReferralNumber;
        MaxPatDisplay = 20;
        if (NPatients>MaxPatDisplay)
        {
            Pages = Math.ceil(NPatients/MaxPatDisplay);
            PatientsPage = Math.ceil(NPatients/Pages);
        }else
        {
            Pages = 1;
            PatientsPage = NPatients;
            $("#MorePage").css('visibility','hidden');
            $("#LessPage").css('visibility','hidden');
            $("#pageNumber").css('visibility','hidden');
        } 
        ActualPage = 1;
        //loadRadarData();
        RadarPage();
        $('#BotonBusquedaSents').trigger('click');
        if(Pages > 1){
			$("#pageNumber").css('visibility','visible');
			$("#MorePage").css('visibility','visible');
			$("#LessPage").css('visibility','visible');
		}
    }

    function drawReferredInRadar()
    {
        InOutFlag = 'in';
        PatientName = Array();
        PatientStage = Array();
        PatientDoctor = Array();
        PatientDoctorFULL = Array();
        DoctorId = Array();
        DoctorEmail = Array();
        PatientTTV = Array();
        DoctorsNames = Array();
        DoctorsNamesFULL = Array();
        DoctorsEmails = Array();
        var group=0;

        if($('#Group_toggle').is(":checked")) group=1;


        getReferredPatients('../../../ajax/getReferredInPatients.php?group='+group);
        //getReferredPatients('getReferredInPatients.php?group=1');

        $thisreco = 0;

        while ($thisreco <  ReferredPatients.length)
        {
            PatientName[$thisreco] = ReferredPatients[$thisreco].PatientName;
            PatientStage[$thisreco] = ReferredPatients[$thisreco].PatientStage;
            PatientDoctor[$thisreco] = ReferredPatients[$thisreco].PatientDoctor;
            PatientDoctorFULL[$thisreco] = ReferredPatients[$thisreco].PatientDoctorFULL;
            DoctorId[$thisreco] = ReferredPatients[$thisreco].IdMED;
            DoctorEmail[$thisreco] = ReferredPatients[$thisreco].EmailDoctor;
            if ( PatientDoctor[$thisreco] < "   ")  PatientDoctor[$thisreco]=DoctorEmail[$thisreco];
            PatientTTV[$thisreco] = ReferredPatients[$thisreco].TTV;
            DoctorsNames[DoctorId[$thisreco]] = PatientDoctor[$thisreco];
            DoctorsNamesFULL[DoctorId[$thisreco]] = PatientDoctorFULL[$thisreco];
            DoctorsEmails[DoctorId[$thisreco]] = DoctorEmail[$thisreco];
            $thisreco ++;
        }

        ReferralId = Array();
        ReferralName = Array();
        ReferralNameFULL = Array();
        ReferralStaP = Array();
        ReferralEndP = Array();
        ReferralNumPats = Array();
        ReferralTTV = Array();
        brokenReferral = Array();
        $thisreco = 0;
        ReferralNumber = 0;
        LatestPatient = 0;
        LatestDoctor = 0;
        SumTimes = 0;
        MeanTTV = 0;

        while ($thisreco <=  ReferredPatients.length)
        {
            if ($thisreco > 0 && (DoctorId[$thisreco] != LatestDoctor))
            {
                ReferralStaP[ReferralNumber] = LatestPatient;
                ReferralEndP[ReferralNumber] = $thisreco-1;

                ReferralId[ReferralNumber] = DoctorId[$thisreco-1];
                ReferralName[ReferralNumber] = DoctorsNames[DoctorId[$thisreco-1]];
                ReferralNameFULL[ReferralNumber] = DoctorsNamesFULL[DoctorId[$thisreco-1]];

                ReferralNumPats[ReferralNumber] = ReferralEndP[ReferralNumber] - ReferralStaP[ReferralNumber] + 1;
                ReferralTTV[ReferralNumber] = SumTimes;

                MeanTTV = MeanTTV + Math.round(ReferralTTV[ReferralNumber]/ReferralNumPats[ReferralNumber]);
                SumTimes = 0;		
                
                /*console.log('- NEW REFERRAL: -' ); 
								console.log(ReferralNumber ); 
								console.log(ReferralId[ReferralNumber] ); 
								console.log(ReferralName[ReferralNumber] ); 
								console.log(ReferralStaP[ReferralNumber] ); 
								console.log(ReferralEndP[ReferralNumber] ); 
								console.log('' ); */
                
                LatestPatient = $thisreco;
               
                ReferralNumber++;
            }
            LatestDoctor = DoctorId[$thisreco];
            SumTimes = SumTimes + parseInt(PatientTTV[$thisreco]);
            $thisreco ++;
        }
        NPatients = $thisreco-1;
        NReferrals = ReferralNumber;
        MaxPatDisplay = 20;
        if (NPatients>MaxPatDisplay)
        {
            Pages = Math.ceil(NPatients/MaxPatDisplay);
            PatientsPage = Math.ceil(NPatients/Pages);
        }else
        {
            Pages = 1;
            PatientsPage = NPatients;
            $("#MorePage").css('visibility','hidden');
            $("#LessPage").css('visibility','hidden');
            $("#pageNumber").css('visibility','hidden');
        }

        ActualPage = 1;
        RadarPage();
        //loadRadarData();
        $('#BotonBusquedaPermit').trigger('click');
    }


    $(window).bind('load', function(){
        $('#BotonBusquedaSents').trigger('click');
        $('#BotonBusquedaPermit').trigger('click');
    });


    var phase = 1;
    var ancho = $("#header-modal").width();
    $('#content_selpat').css('width',ancho);
    $('#content_seldr').css('width',ancho);
    $('#content_att').css('width',ancho);
    $('#content_addcom').css('width',ancho);
    $('#ScrollerContainer').css('width',ancho*5);


    $('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
        //alert('Reset counter');
    });

    var user;
    var doctor;
    //changes for the attachments
    var reportcheck=new Array();
    var reportids='';

    /*setInterval(function() {
       $('#BotonBusquedaSents').trigger('click');
      }, 10000);*/

    var phaseReached = 0;
    var numReports = 0;
    $("#PhaseNext").click(function(event) {

		console.log('phase '+phase);
        if (phase < 4) phase++; else 
        {

            $('#CloseModal').trigger('click');
            $('#SendButton').trigger('click');
			console.log('end of loop');
			$("#header-modal").hide();
			//$("body").focus();
            location.reload();

        }


        var ancho = $("#header-modal").width()*(phase-1);
        $("#ContentScroller").animate({ scrollLeft: ancho}, 175);
        if (phase > phaseReached)
        {
            phaseReached = phase;
        }
        if ((phase >= phaseReached && phase < 4) )
        {

            if (phase == 3)
            {
                numReports = 0;
            }else{

                $("#PhaseNext").attr("disabled", "true");
            }
        }
        switch (phase)
        {
            case 1:   $("#selpat").css("color","rgb(61, 147, 224)");
                $("#seldr").css("color","#ccc");
                $("#att").css("color","#ccc");
                $("#addcom").css("color","#ccc");
                break;     
            case 2:   $("#selpat").css("color","rgb(61, 147, 224)");
                $("#seldr").css("color","rgb(61, 147, 224)");
                $("#att").css("color","#ccc");
                $("#addcom").css("color","#ccc");
                $("#PhasePrev").removeAttr("disabled");
                break;     
            case 3:   $("#selpat").css("color","rgb(61, 147, 224)");
                $("#seldr").css("color","rgb(61, 147, 224)");
                $("#att").css("color","rgb(61, 147, 224)");
                $("#addcom").css("color","#ccc");
                createPatientReportsNEW ();
                break;     
            case 4:   $("#selpat").css("color","rgb(61, 147, 224)");
                $("#seldr").css("color","rgb(61, 147, 224)");
                $("#att").css("color","rgb(61, 147, 224)");
                $("#addcom").css("color","rgb(61, 147, 224)");
                break; 
            default:    alert ('no phase detected');
                break;
        }
        if (phase == 4){

            attachReports();
            showAttachmentIcon(numReports);
        } //$("#Attach").trigger('click');
    });

    $("#PhasePrev").click(function(event) {
        if (phase == 3) $("#Attach").trigger('click');
        if (phase >1) phase--; else 
        {
            // alert ('beginning of loop');    
        }
        if (phase < phaseReached)
        {
            $("#PhaseNext").removeAttr("disabled");
        }
        if (phase == 3)
        {
            numReports = 0;
            //$("#PhaseNext").attr("disabled", "true");
        }
        var ancho = $("#header-modal").width()*(phase-1);
        switch (phase)
        {
            case 1:   
                $("#selpat").css("color","rgb(61, 147, 224)");
                $("#seldr").css("color","#ccc");
                $("#att").css("color","#ccc");
                $("#addcom").css("color","#ccc");
                $("#PhaseNext").removeAttr("disabled");
                $("#PhasePrev").attr("disabled", "true");
                break;     
            case 2:   
                $("#selpat").css("color","rgb(61, 147, 224)");
                $("#seldr").css("color","rgb(61, 147, 224)");
                $("#att").css("color","#ccc");
                $("#addcom").css("color","#ccc");
                break;     
            case 3:   
                $("#selpat").css("color","rgb(61, 147, 224)");
                $("#seldr").css("color","rgb(61, 147, 224)");
                $("#att").css("color","rgb(61, 147, 224)");
                $("#addcom").css("color","#ccc");
                createPatientReportsNEW ();
                break;     
            case 4:   
                $("#selpat").css("color","rgb(61, 147, 224)");
                $("#seldr").css("color","rgb(61, 147, 224)");
                $("#att").css("color","rgb(61, 147, 224)");
                $("#addcom").css("color","rgb(61, 147, 224)");
                break; 
            default:    
                alert ('no phase detected');
                break;
        }
        $("#ContentScroller").animate({ scrollLeft: ancho}, 175);
    });


    $("#BotonBusquedaPac").click(function(event) {
        var IdUs =156;
        var UserInput = $('#SearchUser').val();
        var UserEmail = $('#SearchEmail').val();
        var IdUsFIXED = $('#SearchIdUsFIXED').val();
        var MEDID = $('#MEDID').val();
        var queUrl ='../../../ajax/getFullUsersLINK.php?Usuario='+UserInput+'&NReports=10&MEDID='+MEDID+'&Email='+UserEmail+'&IdUsFIXED='+IdUsFIXED;

        $('#TablaPac').load(queUrl);
        //$('#TablaPac').trigger('click');
        $('#TablaPac').trigger('update');

    });

    $("#BotonBusquedaMen").click(function(event) {
        var IdUs =156;
        var UserInput = $('#SearchUser').val();
        var MEDID = $('#MEDID').val();
        var queUrl ='../../../ajax/getMessages.php?Usuario='+UserInput+'&NReports=10&MEDID='+MEDID;

        $('#TablaPac').load(queUrl);
        //$('#TablaPac').trigger('click');
        $('#TablaPac').trigger('update');

    });

    $("#BotonBusquedaPacCOMP").live('click',function() {
        var UserInput = $('#SearchUserT').val();
        var UserDOB = '';
        var IdMed = $('#MEDID').val();
        var queUrl ='../../../ajax/getSearchUsers.php?Usuario='+UserInput+'&UserDOB='+UserDOB+'&IdDoc='+IdMed+'&NReports=3';
        $('#TablaPac').load(queUrl);
        $('#TablaPac').trigger('update');
        $('#BotonBusquedaSents').trigger('click');
    });     

    $("#BotonBusquedaMedCOMP").live('click',function() {
        var UserInput = $('#SearchDoctor').val();
        var UserDOB = $('#DoctorEmail').val();
        var queUrl ='../../../ajax/getSearchDoctors.php?Doctor='+UserInput+'&DrEmail=&NReports=3';
        $('#TablaMed').load(queUrl);
        $('#TablaMed').trigger('update');
    }); 


    //Added a change to for On-behalf functionality

    //click(function(event) {
    var IdMed = $('#MEDID').val();
    var url='getGroupDoctors.php?Doctor='+IdMed;
    RecTipo = LanzaAjax (url);
    $('#fill_doctors').html(RecTipo);
    if($("#doclist").children().length == 0)
    {
        $("#doclist").parent().parent().css('display', 'none');
    }
    //});

    //Added a toggle button for the distinguishing between archive and active
    $("#Cstate").click(function(event) {
        // decrypt=true;
        $('#BotonBusquedaSents').trigger('click');
    });

    $('#Group_toggle').click(function(event) {

        /*getInitialRadarData();
		drawReferredOutRadar();
		$('#BotonBusquedaSents').trigger('click');*/

        var elem = $("#myTab").find(".active");
        //alert(elem.prop("tagName"));
        //var elem=document.getElementsByClassName("active");
        elem.trigger('click');
        /*if (typeof elem.onclick == "function") {
				elem.onclick.apply();
				alert();
		}*/
        if(document.getElementsByClassName("grid").style.backgroundColor != 'yellow')
            document.getElementsByClassName("grid").style.backgroundColor = 'yellow';
        else if(document.getElementsByClassName("grid").style.backgroundColor == 'yellow')
            document.getElementsByClassName("grid").style.backgroundColor = 'white';



    });
    //$("#BotonBusquedaSents").live('click',function() {
    $("#BotonBusquedaSents").click(function(event) {
        var IdMed = $('#MEDID').val();
        var UserDOB = '';
        var Username='';
        var state=0;
        var group=0;
        if($('#SearchUserUSERFIXED').val() == ''){
            Username=99;
        }else{
            Username= $('#SearchUserUSERFIXED').val();
        }

        if ($('#Cstate').is(":checked")) state = 1;

        if($('#Group_toggle').is(":checked")) group=1;
        //alert(state);

        $('#Wait11').show();
        var queUrl ='../../../ajax/getSents.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3&Username='+Username+'&state='+state+'&group='+group;
        //var queUrl ='getSents.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3';
        //alert (queUrl);

        $('#TablaSents').load(queUrl);
        $('#TablaSents').trigger('update');
        $('#BotonBusquedaReset').hide();
        $('#Wait11').hide();
        $('#radartext').html("");
        //layer.removeChildren();
        //layer.draw();
        DrawStats();    
    }); 

    $("#BotonBusquedaReset").click(function(event) {
        var IdMed = $('#MEDID').val();
        var UserDOB = '';
        var Username='';
        Username=99;
        var queUrl ='../../../ajax/getSents.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3&Username='+Username;
        //alert (queUrl);
        $('#Wait11').show();
        $('#TablaSents').load(queUrl);
        $('#TablaSents').trigger('update');
        $('#radartext').html("");
        $('#BotonBusquedaReset').hide();
        $('#Wait11').hide();
        /*timerrad=window.clearInterval(timerrad);
		 filterInterval();*/
        //layer.removeChildren();
        //layer.draw();
        DrawStats();    
    });
    
    $("#BotonBusquedaReset2").click(function(event) {
        var IdMed = $('#MEDID').val();
        var UserDOB = '';
        var Username='';
        Username=99;
        var group=0;
        if($('#Group_toggle').is(":checked")) 
            group = 1;
        var queUrl ='../../../ajax/getPermits.php?Doctor='+IdMed+'&DrEmail=&NReports=3&Username='+Username+'&group='+group;
        $('#TablaPermit').load(queUrl);
        $('#TablaPermit').trigger('update');
        $('#radartext2').html("");
        $('#BotonBusquedaReset2').hide();
        $('#Wait12').hide();
        /*timerrad=window.clearInterval(timerrad);
		 filterInterval();*/
        //layer.removeChildren();
        //layer.draw();
        DrawStats();    
    });

    $(".ROWREF").live('click',function(evt) {
        var myId = $(this).attr("id");
        //var queMED = $("#MEDID").val();
        //document.getElementById('UserHidden').value=myClass;
        //alert(document.getElementById('UserHidden').value);
        if(evt.target.id.indexOf('Bot') == -1 && !$(evt.target).closest('div[id^="Bot"]').length) window.location.replace('patientdetailMED-new.php?IdUsu='+myId);
        //alert('patientdetailMED-new.php?IdUsu='+myClass);
        //window.location.replace('patientdetailMED.php'); 
    });

    // Changes for revoking the connection
    $("#BotRevoke,#BotCancel").live('click',function(event){
        var idmed = $('#MEDID').val();

        var conf=confirm("Are you sure that you want to revoke this referral connection?");

        if(conf){

            var id=$(this).parents(".CFILASents").attr('id');
            var revokeurl ='../../../ajax/revokeReferralConnection.php?id='+id+'&docid='+idmed;
            RecTipo = LanzaAjax (revokeurl);
            //displayalertnotification('Referral connection revoked. All related data has been deleted!');
            //$("#BotonBusquedaSents").trigger('click');
            window.location.reload();

        }



        //event.stopPropagation();
        //window.location("medicalConnections.php"); // Added by Pallab for refreshing the page


    });

    //Changes for archiving a referral connection
    $("#BotArchive").live('click',function(event){

        var conf=confirm("Are you sure that you want to Archive this referral connection?");

        if(conf){

            var id=$(this).parents(".CFILASents").attr('id');
            var revokeurl ='../../../ajax/ArchiveReferralConnection.php?id='+id;
            RecTipo = LanzaAjax (revokeurl);
            displayalertnotification('Referral connection Archived!');
            $("#BotonBusquedaSents").trigger('click');

        }

        event.stopPropagation();


    });


    // Changes for sending a reminder
    $("#BotReminder").live('click',function(event){

        var conf=confirm("Are you sure you want to send a reminder to the Doctor again?");

        if(conf){

            var id=$(this).parents(".CFILASents").attr('id');
            //var idpac=$(this).parents(".CFILASents").attr('idpac');
            //var idmed=$(this).parents(".CFILASents").attr('idmed');

            var remindurl = '../../../ajax/SendReminder.php?id='+id;

            RecTipo = LanzaAjax (remindurl);
            //LIne added by Pallab
            //  console.log(RecTipo); //Pallab

            //alert ('url: '+remindurl+' Salida:  '+RecTipo);
            displayalertnotification('Reminder has been sent.');
            $("#BotonBusquedaSents").trigger('click');

        }

        event.stopPropagation();


    });

    $("#BotonBusquedaPermit").click(function(event) {
        var IdMed = $('#MEDID').val();

        var Username = 99;
        if($('#SearchUserPermits').val().length > 0)
        {
            Username= $('#SearchUserPermits').val();
        }
        
        //console.log('Username: ' + Username);
        var group=0;
        if($('#Group_toggle').is(":checked")) 
            group = 1;
        $('#Wait12').show();
        var queUrl ='../../../ajax/getPermits.php?Doctor='+IdMed+'&DrEmail=&NReports=3&Username='+Username+'&group='+group;
        $('#TablaPermit').load(queUrl);
        $('#TablaPermit').trigger('update');
        $('#BotonBusquedaReset2').hide();
        $('#Wait12').hide();
        $('#radartext2').html("");
        DrawStats();
    });       

    $(".CFILASents").live('click',function() {
        var myClass = $(this).attr("id");
        // alert (myClass);


        var IdMed = $('#MEDID').val();
        var UserDOB = '';
        var queUrl ='../../../ajax/getPermits.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3';
        $('#TablaPermit').load(queUrl);
        $('#TablaPermit').trigger('update');

    });       

    function SendWelcomeMessage(){ 
        var content = $('#WelMes').val();
        reportids = reportids.replace(/\s+$/g,' ');
        var IdDocOrigin = $('#MEDID').val();
        var subject = "I referred a new member";
        var cadena = '../../../ajax/GetConnectionId.php?Tipo=1&IdPac='+IdPaciente+'&IdDoc='+IdDoctor+'&IdDocOrigin='+IdDocOrigin;
        //alert (cadena);
        RecTipo = LanzaAjax (cadena);
        //alert (RecTipo);
        //alert ('IdPaciente: '+IdPaciente+' - '+'Sender: '+IdDocOrigin+' - '+'Attachments: '+reportids+' - '+'Receiver: '+IdDoctor+' - '+'Content: '+content+' - '+'subject: '+IdPaciente+' - '+'connection_id: '+RecTipo);


        var cadena='../../../ajax/sendMessage.php?sender='+IdDocOrigin+'&receiver='+IdDoctor+'&patient='+IdPaciente+''+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+RecTipo+'&type=referral';
        var RecTipo=LanzaAjax(cadena);
        //alert ('Answer of Messg Proc.?: '+RecTipo);
    };

    $('#sendmessages_inbox').live('click',function(){
        var sel=$('#doctorsdetails').find(":selected").attr('id');
        var content=$('#messagecontent_inbox').val().replace(/ /g,"sp0e").replace(/\r\n|\r|\n/g,"br8k");
        var subject=$('#subjectname_inbox').val();
        reportids = reportids.replace(/\s+$/g,' ');
        /*
              var cadena='sendMessage.php?sender=<?php // echo $MedID;?>&receiver=<?php // echo $otherdoc;?>&patient=<?php // echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php // echo $referral_id;?>';
             */
        var RecTipo=LanzaAjax(cadena);
        $('#messagecontent_inbox').attr('value','');
        $('#subjectname_inbox').attr('value','');
        displaynotification('status',RecTipo);
        /*
              var cadena='push_server.php?FromDoctorName=<?php// echo $IdMEDName;?>&FromDoctorSurname=<?php // echo $IdMEDSurname;?>&Patientname=<?php // echo $MedUserName; ?>&PatientSurname=<?php // echo $MedUserSurname; ?>&IdUsu=<?php // echo $USERID;?>&message= New Message <br>From: Dr. <?php // echo $IdMEDName;?> <?php // echo $IdMEDSurname;?><br>Subject: '+(subject).replace(/RE:/,'')+'&channel='+<?php // echo $otherdoc?>;
             */
        var RecTipo=LanzaAjax(cadena);
        reportids='';
        $("#attachment_icon").remove();
        $('#message_modal').trigger('click');
    });



    var nonusertoken='-1';

    $("#SendButton").live('click',function() {
        // Confirm
        //alert ('sending...');
        var subcadena='';
        var CallPhone = 0;
        var sendOnbehalf=0;
        var Onbehalfdoc='';
        if($('#c2').is(":checked")){
            //alert("checked");
            subcadena =' (will send text message also)';
            CallPhone = 1; 
        }

        /*if ($('#c2').attr('checked')=='checked'){ 
             alert("checked");
	     	subcadena =' (will send text message also)';
		    CallPhone = 1; 
	     }*/

        var IdDocOrigin = $('#MEDID').val();
        var NameDocOrigin = $('#IdMEDName').val() ;
        var SurnameDocOrigin = $('#IdMEDSurname').val() ;
        var CellPhone = $('#cellphone').val();

        reportids = reportids.replace(/\s+$/g,' ');
        var RecTipo;
        //Added for the sending on behalf
        if ($('#c2_doc').is(":checked")){

            //subcadena =' (will send text message also)';
            Onbehalfdoc=$('#doclist').val();
            sendOnbehalf = 1; 
            //alert(Onbehalfdoc);
        }

        //Added for the special referral
        var reftype=$('#referral_type').val();

        var pat_cancelled=-1;

        //Start of new code added by Pallab - to handle non H2M doctors 
        var toEmail;
        if(doctor[0].Name == null)
            toEmail = $("#DoctorEmail").val();
        else
            toEmail = doctor[0].IdMEDEmail;
        //End of new code added by Pallab


        if(destino>'' || Nondestino > '') {

            //Start of New code added by Pallab to find whether the doctor is non h2m or not, if non h2m destino will be blank 
            if(!(destino > ''))
                var r=confirm('Confirm sending member '+paciente+' to '+toEmail+' ?   '+subcadena);
            else
                var r=confirm('Confirm sending member '+paciente+' to '+destino+' ?   '+subcadena);
            //End of new code added by Pallab

            if (r==true)
            {

                if(destino>'') {
                    
                    console.log('1');
                    // Update database table (1 or 2) and handle communication with Referral
                    // Changed the below line in &ToEmail='+doctor[0].IdMEDEmail+' to &ToEmail='+toEmail
                    var cadena = '../../../ajax/SendReferral.php?Tipo=1&IdPac='+IdPaciente+'&IdDoc='+IdDoctor+'&IdDocOrigin='+IdDocOrigin+'&NameDocOrigin='+NameDocOrigin+'&SurnameDocOrigin='+SurnameDocOrigin+'&ToEmail='+toEmail+'&From='+'&Leido=0&Push=0&estado=1'+'&CallPhone='+CallPhone+'&CellPhone='+CellPhone+'&attachments='+reportids+'&Onbehalfdoc='+Onbehalfdoc+'&sendOnbehalf='+sendOnbehalf+'&reftype='+reftype;

                    //alert (cadena);
                    RecTipo = LanzaAjax (cadena);

                    console.log("From Send Referral"+RecTipo);

                    //alert (RecTipo);
                    $('#BotonBusquedaSents').trigger('click');
                    SendWelcomeMessage();
                    //location.reload();
                    //alert (RecTipo);
                    // Refresh table in this page accordingly

                    $('.modal').modal('hide');        

                }else if(Nondestino>'') {

                    //alert("reached here");
                    console.log('2');
                    var cadena = '../../../ajax/SendReferral.php?Tipo=0&token='+nonusertoken+'&IdPac='+IdPaciente+'&IdDoc='+IdDoctor+'&IdDocOrigin='+IdDocOrigin+'&NameDocOrigin='+NameDocOrigin+'&SurnameDocOrigin='+SurnameDocOrigin+'&ToEmail='+Nondestino+'&From='+'&Leido=0&Push=0&estado=1'+'&CallPhone='+CallPhone+'&CellPhone='+CellPhone+'&attachments='+reportids+'&Onbehalfdoc='+Onbehalfdoc+'&sendOnbehalf='+sendOnbehalf+'&reftype='+reftype;
                    //alert(cadena);

                    RecTipo = LanzaAjax (cadena);
                    //alert (RecTipo);
                    $('#BotonBusquedaSents').trigger('click');
                    SendWelcomeMessage();
                    pat_cancelled = 0;
                    
                    $('.modal').modal('hide');        
                }


            }else{

                alert('Member Referral Cancelled.');
                pat_cancelled = 1;
                // location.reload();
            }
        }
        //alert(RecTipo);
        var res=parseInt(RecTipo);

        //alert(res);
        if(res){
            if(res==3){
                alert('Referral request already present for this member!');
            }else{
                //displayalertnotification('Referral request sent!');

            }}
        else if(pat_cancelled == 0){

            alert('Although this email is already present in the system, your referral request has been sent. Please make sure you use the filter button for normal referral process!');

        }
        //alert(RecTipo);
        //}

        $('#TextoSend').html('');
        destino='';
        Nondestino='';
        // $("#attachment_icon").hide();
        location.reload();

    });     



    $(".CFILADoctor").live('click',function() {
        //$("#attachment_icon").hide();
        var myClass = $(this).attr("id");
        getMedCreator(myClass);
        destino = "Dr. "+doctor[0].Name+" "+doctor[0].Surname; 
        IdDoctor = doctor[0].id;
        PhoneDoctor = doctor[0].phone;
        if (PhoneDoctor > '') $('#cellphone').val(PhoneDoctor);
        //alert (destino);	
        Nondestino='';
        TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+destino+'   </span>';
        if (paciente>'' && destino>'') TextoS = TextoS + '';
        $("#PhaseNext").removeAttr("disabled");
        $('#TextoSend').html(TextoS);
        $('#PhaseNext').trigger('click');
    }); 	

    $(".CFILAMODAL").live('click',function() {
        //$("#attachment_icon").hide();
        var myClass = $(this).attr("id");
        getUserData(myClass);
        paciente = user[0].Name+" "+user[0].Surname; 
        IdPaciente = user[0].Identif;
        if(Nondestino>'')
            TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+Nondestino+'   </span>';
        else 
            TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+destino+'   </span>';
        if (paciente>'' && destino>'') TextoS = TextoS + '<p><span><input type="button" class="btn btn-info" value="Attach Reports" id="AttachButton" style="margin-top:10px;"></p>';
        else if (paciente>'' && Nondestino>'') TextoS = TextoS + '';
        $("#PhaseNext").removeAttr("disabled");
        $('#TextoSend').html(TextoS);
        $('#PhaseNext').trigger('click');
    }); 

    //Changes for adding a non-user

    $("#AddNonUser").live('click',function() 
                          {
        if($("#DoctorEmail").val().length > 0)
        {
            //var myClass = $(this).attr("id");
            //getMedCreator(myClass);
            //$("#attachment_icon").hide();
            destino='';
            var docId = $("#DoctorEmail").val();
            console.log('adding doctor');
            $.get("../../../ajax/getMedCreator.php?UserId=-1&email="+docId, function(data, status)
                  {
                //console.log(JSON.parse(data));
                doctor = new Array();
                doctor=data.items;
                //doctor.push(JSON.parse(data));
                console.log(doctor[0]);
                /*if(doctor[0].id == undefined)
                {
                    doctor[0] = doctor[0].items;
                }*/
                IdDoctor = doctor[0].id;
                //alert (IdDoctor);	
                console.log(IdDoctor);
                Nondestino = docId; 
                nonusertoken=doctor[0].token;
                //IdDoctor = doctor[0].id;
                //alert (destino);	
                TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+Nondestino+'   </span>';
                if (paciente>'' && destino>'') 
                    TextoS = TextoS + '';
                else if (paciente>'' && Nondestino>'') 
                    TextoS = TextoS + '';
                $("#PhaseNext").removeAttr("disabled");
                $('#TextoSend').html(TextoS);
                $('#PhaseNext').trigger('click');
            },"json");

        }
    });



    //Changes for attaching reports
    $("#AttachButton").live('click',function(){
        createPatientReports();
        setTimeout(function(){$("#report_modal").trigger('click');},500);

    });


    $(".CheckContainer").live('mousedown',function(){
        var myClass = $(this).attr("id");

        var recoZ = 0;
        var Yaexiste = 0;


        while (recoZ < IsOnCounter)
        {
            if (IsOn[recoZ] == myClass ) 
            {
                IsOn[recoZ] = 0;
                IsOnCounter2--; 
                Yaexiste = 1;
            }
            recoZ++;
        }
        if (Yaexiste == 0) 
        { 
            IsOnCounter++ ; 
            IsOnCounter2++ ; 
            IsOn[IsOnCounter] = myClass;
        }
        $('#NumberRA').html (IsOnCounter2+' Reports Attached');
        if (IsOnCounter2 >0) 
        {
            $("#attachment_icon").css("display","visible");
            $("#attachment_icon").css("color","#22aeff");
            $("#attachment_icon").addClass("icon-spin");
        }
        else
        {
            // $("#attachment_icon").css("display","none");
            $("#attachment_icon").css("color","#ccc");
            $("#attachment_icon").removeClass("icon-spin");
        }

    });

    $('input[type=checkbox][id^="reportcol"]').live('click',function()
                                                    {
        var val = (this.checked ? "1" : "0");
        if (val == 1)
        {
            numReports++;
        }
        else
        {
            numReports--;
        }
        if (numReports == 0)
        {
            //$("#PhaseNext").attr("disabled", "true");
        }
        else
        {

            // $("#PhaseNext").removeAttr("disabled");
        }
    });

    function attachReports(){
        reportids='';

        $('input[type=checkbox][id^="reportcol"]').each(function () {
            var sThisVal = (this.checked ? "1" : "0");
            //sList += (sList=="" ? sThisVal : "," + sThisVal);
            if(sThisVal==1)
            {
                var idp=$(this).parents("div.attachments").attr("id");
                //alert("Id "+idp+" selected"); 
                reportcheck.push(this.id);
                //messageid=messageid+idp+' ,';
                reportids=reportids+idp+' ';

            }
        });
        // alert(reportids);
        var conf=false;
        if(reportids>'')
            //conf=confirm("Confirm Attachments");
            conf=true;
        if(conf){
            //alert ('confirmed');
            //$("#AttachButton").attr('value','Reports Attached');

            //alert(reportids);

        }else{
            reportids='';
            for (i = 0 ; i < reportcheck.length; i++ ){

                document.getElementById(reportcheck[i]).checked = false;

            }
            reportcheck.length=0;
            //$("#Reply").trigger('click');
        }
        //setTimeout(function(){$("#report_modal").trigger('click');},50);

    }


    function showAttachmentIcon(isReportAttached){

        if (isReportAttached >0) 
        {
            $("#attachment_icon").css("display","visible");
            $("#attachment_icon").css("color","#22aeff");
            $("#attachment_icon").addClass("icon-spin");
        }
        else
        {
            // $("#attachment_icon").css("display","none");
            $("#attachment_icon").css("color","#ccc");
            $("#attachment_icon").removeClass("icon-spin");
        }

    }


    function createPatientReports(){
        var ElementDOM ='All';
        var EntryTypegroup ='0';
        var Usuario = IdPaciente   //$('#userId').val();
        var MedID =$('#MEDID').val();

        var queUrl ='../../../ajax/createAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
        //var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports=1226';
        $("#attachments").load(queUrl);
        $("#attachments").trigger('update');
        $("#attachments").show();

    }	
    function createPatientReportsNEW (){
        var ElementDOM ='All';
        var EntryTypegroup ='0';
        var Usuario = IdPaciente   //$('#userId').val();
        var MedID =$('#MEDID').val();
        //$("#Phase3Container").html(data);
        $("#ReportStream").html('');
        $("#H2M_Spin_Stream").show();
        setTimeout(function(){
            var queUrl ='../../../ajax/createAttachmentStreamNEWTEST.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
            console.log(queUrl);
            //var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports=1226';
            $.get(queUrl, function(data, status)
                  {
                //$("#Phase3Container").html(data);
                $("#ReportStream").html(data);
                //$("#Phase3Container").trigger('update');
                $("#Phase3Container").show();
                $("#H2M_Spin_Stream").hide();
            });




        },1000);
    }	


    function displayalertnotification(message){

        var gritterOptions = {
            title: 'status',
            text: message,
            image:'../../../../images/Icono_H2M.png',
            sticky: false,
            time: '3000'
        };
        $.gritter.add(gritterOptions);

    } 



    function getBlocks(serviceURL) {
        $.ajax(
            {
                url: serviceURL,
                dataType: "json",
                async: false,
                success: function(data)
                {
                    blocks = data.items;
                    //$('#Wait1').hide(); 
                    //alert ("PASA");
                    //alert (employees);
                }
            });
    }        

    $(".CFILA").live('click',function() {
        /*	var myClass = $(this).attr("id");
     	var NombreEnt = $('#NombreEnt').val();
     	var PasswordEnt = $('#PasswordEnt').val();
     	//window.location.replace('patientdetail.php?Nombre='+NombreEnt+'&Password='+PasswordEnt+'&IdUsu='+myClass);
     	//alert (myClass);
        $('#BotonModal').trigger('click');
      */
    });

    $(".view-button").live('click',function() {
        var myClass = $(this).attr("id");
        $('#queId').attr("value",myClass);
        var NameMed = $('#IdMEDName').val();
        var SurnameMed = $('#IdMEDSurname').val();
        var PasswordEnt = $('#PasswordEnt').val();
        var MEDID = $('#MEDID').val();
        var MEDEmail = $('#IdMEDEmail').val();

        $('#BotonModal').trigger('click');
    });

    $("#ConfirmaLink").live('click',function() {
        var To = $('#queId').val();
        getUserData(To);

        if (user[0].email==''){
            var IdCreador = user[0].IdCreator;

            alert ('orphan user . Creator= '+IdCreador);

            getMedCreator(IdCreador);

            var NameMed = $('#IdMEDName').val();
            var SurnameMed = $('#IdMEDSurname').val();
            var From = $('#MEDID').val();
            var FromEmail = $('#IdMEDEmail').val();
            var Subject = 'Request conection from Dr. '+NameMed+' '+SurnameMed;

            var Content = 'Dr. '+NameMed+' '+SurnameMed+' is requesting to establish connection with your member named: '+user[0].Name+' '+user[0].Surname+' (UserId:  '+To+'). Please confirm, or just close this message to reject.';

            //alert (Content);
            var destino = "Dr. "+doctor[0].Name+" "+doctor[0].Surname; 
            var cadena = '../../../ajax/MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doctor[0].id+'&ToEmail='+doctor[0].IdMEDEmail+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1';

            //alert (cadena);
            var RecTipo = LanzaAjax (cadena);

            //alert (RecTipo);
        }
        else
        {
            var NameMed = $('#IdMEDName').val();
            var SurnameMed = $('#IdMEDSurname').val();
            var From = $('#MEDID').val();
            var FromEmail = $('#IdMEDEmail').val();
            var Subject = 'Request conection ';

            var Content = 'Dr. '+NameMed+' '+SurnameMed+' is requesting to establish connection with you (UserId:  '+To+'). Please confirm, or just close this message to reject.';

            var cadena = '../../../ajax/MsgInterno.php?Tipo=0&IdPac=0&To='+To+'&ToEmail='+user[0].email+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1';

            //alert (cadena);
            var RecTipo = 'Temporal';
            $.ajax(
                {
                    url: cadena,
                    dataType: "html",
                    async: false,
                    complete: function(){ //alert('Completed');

                    },
                    success: function(data)
                    {
                        if (typeof data == "string") {
                            RecTipo = data;
                        }
                    }
                });


        }

        $('#CloseModal').trigger('click');
        $('#BotonBusquedaPac').trigger('click');

    });


    $('#Wait1')
    .hide()  // hide it initially
    .ajaxStart(function() {
        //alert ('ajax start');
        $(this).show();
    })
    .ajaxStop(function() {
        $(this).hide();
    }); 


    function getUserData(UserId) {
        var cadenaGUD = '../../../ajax/getUserData.php?UserId='+UserId;
        $.ajax(
            {
                url: cadenaGUD,
                dataType: "json",
                async: false,
                success: function(data)
                {
                    //alert ('success');
                    user = data.items;
                }
            });
    }

    function getMedCreator(UserId) {
        var cadenaGUD = '../../../ajax/getMedCreator.php?UserId='+UserId;
        $.ajax(
            {
                url: cadenaGUD,
                dataType: "json",
                async: false,
                success: function(data)
                {
                    //alert ('success');
                    doctor = data.items;
                }
            });
    }


    function EnMail (aQuien, Tema, Contenido)
    {
        var cadena = '../../../ajax/EnMail.php?aQuien='+aQuien+'&Tema='+Tema+'&Contenido='+Contenido;
        var RecTipo = 'Temporal';
        $.ajax(
            {
                url: cadena,
                dataType: "html",
                async: false,
                complete: function(){ //alert('Completed');
                },
                success: function(data)
                {
                    if (typeof data == "string") {
                        RecTipo = data;
                    }
                }
            });
        //alert (RecTipo);	    
    }

    function LanzaAjax (DirURL)
    {
        var RecTipo = 'SIN MODIFICACIÓN';
        $.ajax(
            {
                url: DirURL,
                dataType: "html",
                async: false,
                complete: function(){ //alert('Completed');
                },
                success: function(data) {
                    if (typeof data == "string") {
                        RecTipo = data;
                    }
                }
            });
        return RecTipo;
    }    

      


</script>


<script src="../../../../js/bootstrap.min.js"></script>
<script src="../../../../js/bootstrap-datepicker.js"></script>
<script src="../../../../js/bootstrap-colorpicker.js"></script>
<script src="../../../../js/google-code-prettify/prettify.js"></script>

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
<script src="../../../../js/morris.js"></script>

<script src="../../../../js/application.js"></script>

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
