<!DOCTYPE html>
<html style="background: #F9F9F9;">
<head>
    <meta charset="utf-8">
    <title lang="en">Health2Me - HOME</title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

	


    <!-- Le styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="css/datepicker.css" >
    <link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
    <link rel="stylesheet" href="css/colorpicker.css">
    <link rel="stylesheet" href="css/glisse.css?1.css">
    <link rel="stylesheet" href="css/jquery.jgrowl.css">
    <link rel="stylesheet" href="js/elfinder/css/elfinder.css" media="screen" />
    <link rel="stylesheet" href="css/jquery.tagsinput.css" />
    <link rel="stylesheet" href="css/demo_table.css" >
    <link rel="stylesheet" href="css/jquery.jscrollpane.css" >
    <link rel="stylesheet" href="css/validationEngine.jquery.css">
    <link rel="stylesheet" href="css/jquery.stepy.css" />
	
    
   	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/H2MIcons.css" />
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
	<link rel="stylesheet" href="css/csslider.css">
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
	
	<!-- Create language switcher instance and set default language to en-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript">
	var lang = new Lang('en');
	window.lang.dynamic('th', 'jquery-lang-js-master/js/langpack/th.json');


function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function setCookie(name,value,days) {
confirm('Would you like to switch languages?');
delete_cookie('lang');
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
	
	pageRefresh(); 
}

function pageRefresh(){
location.reload();
}

//alert($.cookie('lang'));

var langType = $.cookie('lang');

if(langType == 'th'){
setTimeout(function(){
window.lang.change('th');
lang.change('th');
//alert('th');
}, 10000);
}

if(langType == 'en'){
setTimeout(function(){
window.lang.change('en');
lang.change('en');
//alert('th');
}, 10000);
}
	

</script>

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
	
        
    </style>
	
	<a id="ButtonDeck" class="btn" title="Member´s Deck" style="text-align:center; padding:5px; width:40px; height:20px; color:#22aeff; float:right; margin-right: 20px; margin-bottom: -20px;">
        <span id="BaloonDeck" style=" background-color: red; float: right;   border: none; position:relative;" class="H2MBaloon">6</span>
        <span lang="en">Deck</span>
    </a>
	
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
	
	
	<!-- JAVASCRIPT -->
<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="TypeWatch/jquery.typewatch.js"></script>
<script src="js/spin.js"></script>
<script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
<script src="realtime-notifications/pusher.min.js"></script>
<script src="realtime-notifications/PusherNotifier.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-colorpicker.js"></script>
<script src="js/jquery.timepicker.js"></script>
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
	
	<script type="text/javascript">
	$(document).ready(function() 
{
	$('#ButtonDeck').live('click',function()
    {
		$("#modalContents").dialog({bgiframe: true, width: 550, height: 550, modal: false});
	});
	});
	</script>