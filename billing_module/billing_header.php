<?php
session_start();
require("../environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con) die('Could not connect: ' . mysql_error());
?>
<!DOCTYPE html>
<html id ="html" lang="en" style="background: #F9F9F9;">
<head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <link rel="icon" type="image/ico" href="../favicon.ico"/>
	
	<!-- Create language switcher instance and set default language to en-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
    <script src="../jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
    <script src="../jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>

    <script type="text/javascript">
        var lang = new Lang('en');
        window.lang.dynamic('th', '../jquery-lang-js-master/js/langpack/th.json');


        function setCookie(name,value,days) {
            confirm('Would you like to switch languages?');
            delete_cookie('lang');
            if (days) {
                var date = new Date();
                date.setTime(date.getTime()+(days*24*60*60*1000));
                var expires = "; expires="+date.toGMTString();
            }
            else { var expires = ""; }
            document.cookie = name+"="+value+expires+"; path=/";
        }

        function delete_cookie( name ) {
          document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }

        function pageRefresh(){
        location.reload();
        }

        //alert($.cookie('lang'));

        var langType = $.cookie('lang');

        if(langType == 'th'){
            var language = 'th';
        }else{
            var language = 'en';
        }

        if(langType == 'th'){
            setTimeout(function(){
                window.lang.change('th');
                lang.change('th');
                //alert('th');
            }, 5000);
        }

        if(langType == 'en'){
            setTimeout(function(){
                window.lang.change('en');
                lang.change('en');
                //alert('th');
            }, 5000);
        }
    </script>
	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Le styles -->
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="../css/jquery-ui-autocomplete.css"  />
    <link rel="stylesheet" href="../css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="../css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="../css/datepicker.css" >
    <link rel="stylesheet" href="../css/colorpicker.css">
    <link rel="stylesheet" href="../css/glisse.css?1.css">
    <link rel="stylesheet" href="../css/jquery.jgrowl.css">
    <link rel="stylesheet" href="../js/elfinder/css/elfinder.css" media="screen" />
    <link rel="stylesheet" href="../css/jquery.tagsinput.css" />
    <link rel="stylesheet" href="../css/demo_table.css" >
    <link rel="stylesheet" href="../css/jquery.jscrollpane.css" >
    <link rel="stylesheet" href="../css/validationEngine.jquery.css">
    <link rel="stylesheet" href="../css/jquery.stepy.css" />
    <!--link rel="stylesheet" type="text/css" href=href="css/googleAPIFamilyCabin.css"-->
      <script type="text/javascript" src="../js/42b6r0yr5470"></script>
	<link rel="stylesheet" href="../css/icon/font-awesome.css">
 <!--link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet" -->
    <link rel="stylesheet" href="../css/bootstrap-responsive.css">
	<link rel="stylesheet" href="../css/toggle-switch.css">
	<link rel="stylesheet" href="../build/css/intlTelInput.css">
    <link rel="stylesheet" href="css/billingmenu_styles.css">
	
    <?php if ($_SESSION['CustomLook']=="COL") : ?>
        <link href="../css/styleCol.css" rel="stylesheet">
    <?php endif; ?>
<!--link href="css/FamilyTree.css" rel="stylesheet"-->
	
	<script src="../js/jquery.min.js"></script>
    <script src="../js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../js/bootstrap.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="../js/jquery.easing.1.3.js"></script>	
	<!--script type="text/javascript" src="js/modernizr.2.5.3.min.js"></script-->	
    <script type="text/javascript" src="js/billingmenu_script.js"></script>
    <script type="text/javascript" src="js/carrierdropdownajax.js"></script>
    <script src="js/h2m_billing.js" type="text/javascript"></script>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../images/icons/favicon.ico">
    	
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
    <style type="text/css">
        html, body {
            background-image: none;
        }
        label {
            display: inline-block;
            margin: 0 10px 14px 10px;
            font-weight: bold;
            font-size: 14px;
        }
        #container_box {
            margin: 50px;
            width: 880px; 
            height: 900px; 
            border: 2px groove gray; 
            border-radius: 7px;
            padding: 20px;
        }
        #container_box.modal_container {
            margin: 10px;
        }
        .address {
            width: 60%;
        }
        .codeslot {
            width: 64px;
        }
        select {
            width: auto;
        }
        #search {
            margin: 0 0 9px 10px;
        }
        .shortinput {
            width: 100px;
        }
        input[type='checkbox'] {
            display: inline-block;
            height: 20px;
            width: 20px;
        }
        #left_column {
            width: 35%;
            height: 620px;
            float: left;
            position: relative;
        }
        #right_column {
            width: 60%;
            height: 620px;
            float: left;
            position: relative;
            padding-left: 20px;
        }
        #PNTitle {
            font-size: 16px; 
            font-weight: bold;
            line-height: 30px;
        }
        input[type='date'] {
            width: 135px;
        }
        #payOrderContainer {
            text-align: center;
        }
        textarea#payOrder {
            background-color: #C2C2C2;
            border: 3px inset gray;
            width: 240px;
            height: 80px;
            position: relative;
        }
        #payOrderChange {
            width: 240px;
        }
        fieldset {
            width: 70%;
            border: 4px groove threedface;
            border-radius: 10px;
            margin-bottom: 20px;
            display: inline-block;
            padding-bottom: 35px;
            text-align: center;
        }
        fieldset.trans {
            width: 95%;
            text-align: left;
            padding-left: 30px;
            padding-bottom: 15px;
        }
        legend {
            width: auto;
            border-bottom: 0;
        }
        #transaction_container {
            border: 1px solid black;
            background: #eeeeee;
            height: 230px;
            min-width: 880px;
            overflow: auto;
        }
        .scriptHolder {
            float: left;
            width: 15.65%;
            /* uncomment to make the title match the ul width (see listHeader too)*/
            /*position: relative;*/
        }
        .listHeader {
            color: #ffffff;
            background: #444444;
            padding: 10px;
            text-transform: uppercase;
            font-weight:bold;
            font-size:11px;
            text-align: center;
            text-indent: 1em;
            position: absolute;
            z-index:10;
            width: 120px;
            /* uncomment to make the title match the ul width (see genericScriptsHolder too)*/
            /*width: 100%;*/
        }
        .listHeader_num {
            color: #ffffff;
            background: #444444;
            padding: 10px;;
            text-transform: uppercase;
            font-weight:bold;
            font-size:11px;
            text-align: center;
            text-indent: 1em;
            position: absolute;
            z-index:10;
            width: 30px;
            /* uncomment to make the title match the ul width (see genericScriptsHolder too)*/
            /*width: 100%;*/
        }
        .scrollingList li.PAheader {
            color: #ffffff;
            background: #a3a3a3;
            padding: 10px;;
            text-transform: uppercase;
            font-weight:bold;
            font-size:11px;
            text-align: center;
            text-indent: 1em;
            overflow: auto;
            height: auto;
            line-height: 10px;
            list-style-type: none;
        }
        .scrollingList li.PAli {
            padding: 4px;
            height: 35px;
            background-color: #CCF2CC;
        }
        .scriptHolder_num {
            float: left;
            width: 50px;
            /*position: relative;*/
        }
        .service_dates {
            font-size: 0.7em;
        }
        .scrollingList li {
            overflow: auto;
            height: 40px;
            color: #666666;
            background-color: #cccccc;
            text-align: center;
            padding: 10px 10px 10px 20px;
            margin: 2px 0 0 0;
            list-style-type: none;
        }
        fieldset > div > a {
            margin: 20px 0;
        }
        ul {
            margin: 0;
            padding-top: 40px;
        }
        .desc {
            font-weight: bold;
            color: #2c63c2;
            vertical-align: top;
            display: inline-block;
        }
        span {
            display: inline;
        }
        .half_line {
            width: 50%;
            position: relative;
            float: left;
        }
        .image_field {
            border: 2px groove gray;
            margin-left: 20px;
            background-color: gray;
            width: 190px;
            height: 110px;
            display: inline-block;
        }
        /* jquery ui-autocomplete duplicate */
        .ui-menu .ui-menu-item a {
            display: inline;
        }
        .modal.fade {
            z-index: -1;
        }
        .modal.fade.in {
            z-index: 1050;
        }
        .modal.fade, .modal.fade.in {
            position: fixed;
        }
        #problemModal, #carrierModal {
            width: auto;
            max-width: 990px;
            top: 33%;
            bottom: 13%;
            left: 33%;
            right: 13%;
        }
        #problemModal .modal-dialog, #carrierModal .modal-dialog {
            margin: 10px;
            width: auto;
            position: relative;
        }
        
        .modal .modal-body {
            max-height: 740px;
            overflow-y: auto;
        }
        .modal-header {
            text-align: center;
        }
    </style>
    <script type="text/javascript"> 
        //TO PREVENT RETURN KEY SUBMISSION
        function stopRKey(evt) { 
          var evt = (evt) ? evt : ((event) ? event : null); 
          var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
          if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
        } 

        document.onkeypress = stopRKey; 
    </script>
</head>
    <body> 
        
    <div id='cssmenu'>
        <ul>
            <li><a href='patientForm.php'><span>Patients</span></a></li>
            <li><a href='billing_transaction.php'><span>Transactions</span></a></li>
            <li class='has-sub'><a href='#'><span>Management</span></a>
                <ul>                     
                    <li><a href='insuranceCompInfo.php'><span>Ins. Companies</span></a></li>
                    <li><a href='providerForm.php'><span>Providers</span></a></li>
                    <li><a href='policyForm.php'><span>Policies</span></a></li>
                    <li><a href='employerForm.php'><span>Employers</span></a></li>
                    <li><a href='probForm.php'><span>Problems</span></a></li>
                    <li><a href='procedureForm.php'><span>Procedures</span></a></li>
                    <li><a href='diagnosisForm.php'><span>Diagnoses</span></a></li>
                    <li><a href='modifierForm.php'><span>Modifiers</span></a></li>
                    <li class='last'><a href='LocationForm.php'><span>Locations</span></a></li>
                </ul>
            </li>
            <li class='last'><a href='#'><span>Contact</span></a></li>
        </ul>
    </div>