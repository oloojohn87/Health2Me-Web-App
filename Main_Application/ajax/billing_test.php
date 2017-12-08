<?php
/*KYLE
session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    echo "<META http-equiv='refresh' content='0;URL=index.html'>";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$MEDID = $_SESSION['MEDID'];
$Acceso = $_SESSION['Acceso'];
if ($Acceso != '23432')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}

					// Connect to server and select databse.
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");
$result = mysql_query("SELECT * FROM doctors where id='$MEDID'");
$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);
$success ='NO';
if($count==1){
	$success ='SI';
	$MedID = $row['id'];
	$MedUserEmail= $row['IdMEDEmail'];
	$MedUserName = $row['Name'];
	$MedUserSurname = $row['Surname'];
	$MedUserLogo = $row['ImageLogo'];
	$IdMedFIXED = $row['IdMEDFIXED'];
	$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
	
}
else
{
echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}
?>


<html lang="en" style="background: #F9F9F9;">
	<head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
	
    <link rel="stylesheet" href="css/jquery-ui-autocomplete.css" />
	<link rel="stylesheet" href="css/loadingbar.css" />
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="css/datepicker.css" >
    <link rel="stylesheet" href="css/colorpicker.css">
    <link rel="stylesheet" href="css/glisse.css?1.css">
    <link rel="stylesheet" href="css/jquery.jgrowl.css">
    <link rel="stylesheet" href="js/elfinder/css/elfinder.css" media="screen" />
    <link rel="stylesheet" href="css/jquery.tagsinput.css" />
    <link rel="stylesheet" href="css/demo_table.css" >
    <link rel="stylesheet" href="css/jquery.jscrollpane.css" >
    <link rel="stylesheet" href="css/validationEngine.jquery.css">
    <link rel="stylesheet" href="css/jquery.stepy.css" />
	<link rel="stylesheet" href="css/icon/font-awesome.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/Multiselectable.css">
	<!--<link rel="stylesheet" type="text/css" href="css/base.css" />-->
	<link href='css/jquery-ui.css' rel='stylesheet' />	
	<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
	<script src='js/jquery-1.9.1.js'></script>
	<script src='js/jquery-ui.js'></script>
	<script src='js/jquery.loadingbar.js'></script>
	<link href='css/fullcalendar.css' rel='stylesheet' />
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
	  /*KYLE
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
	<script src='js/jquery-1.10.2.min.js'></script>
	<script src='js/jquery-1.10.2.min.map'></script>
	<script src='js/jquery-ui-1.10.2.custom.min.js'></script>
	<script src='js/fullcalendar.min.js'></script>
	<script src="js/jquery-1.9.1-autocomplete.js"></script>
	<script src="js/jquery-ui-autocomplete.js"></script>
	<!--<script src="js/jQuery.dualListBox-1.3.js"></script>-->
	<script src="js/Multiselectable.js"></script>
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
  <script type="text/javascript" src="js/jquery.timepicker.js"></script>  
  <!--<script type="text/javascript" src="js/base.js"></script> -->
   <script type="text/javascript">
var ordering=1;  
var sorted="desc";
var oldnumpages=0;
var currpage=1;
var user = new Array();
var event_sources = new Array();
var icdHash = new Array();
var doctors = new Array();
var pats = new Array();
var current_user=<?php echo $_SESSION['MEDID']; ?>;
var availablePatientTags = new Array();
var current_source = <?php echo $_SESSION['MEDID']; ?>;
var colours = ["red","green","blue","orange"];
var user_event = document.getElementById('user_event');
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37863944-1']);
  _gaq.push(['_setDomainName', 'health2.me']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  
  
  var timeoutTime = 30000000;  //5minutes
var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


var active_session_timer = 600000; //1minute
var sessionTimer = setTimeout(inform_about_session, active_session_timer);


$(window).load(function() {
	//alert("started");
	$(".loader_spinner").fadeOut("slow");
	})

//This function is called at regular intervals and it updates ongoing_sessions lastseen time
function inform_about_session()
{
	$.ajax({
		url: '<?php echo $domain?>/ongoing_sessions.php?userid='+$('#MEDID').val(),
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

function progress() {
	var progressbarValue = progressbar.find( ".ui-progressbar-value" );
	var val = progressbar.progressbar( "value" ) || 0;
	progressbar.progressbar( "value", val + 1 );
	progressbar.css({"background": 'black'});
	progressbarValue.css({"background": '#4169E1'});    //#5882FA
	if ( val < 99 ) {
	setTimeout( progress, 50 );
	}
	}

	
function addIcdCode()
{
	alert(getelementbyid('icdCode').val());
	
}

  function open_config()
		{
				window.location.replace("<?php echo $domain;?>/scheduler_config.php");
		
		}
	
  /**
 * You first need to create a formatting function to pad numbers to two digits…
 **//*KYLE
function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}

/**
 * …and then create the method to output the date string as desired.
 * Some people hate using prototypes this way, but if you are going
 * to apply this to more than one Date object, having it as a prototype
 * makes sense.
 **//*KYLE
Date.prototype.toMysqlFormat = function() {
    return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(this.getUTCDate()) + " " + twoDigits(this.getUTCHours()) + ":" + twoDigits(this.getUTCMinutes()) + ":" + twoDigits(this.getUTCSeconds());
};
  function getpatients(serviceURL) 
{
   $.ajax(
   {
       url: serviceURL,
       dataType: "json",
       async: false,
       success: function(data)
       {
			//alert('Data Fetched');
           patients = data.items;
       }
   });
}   

  
function geteventData(serviceURL) 
{
   $.ajax(
   {
       url: serviceURL,
       dataType: "json",
       async: false,
       success: function(data)
       {
			//alert('Data Fetched');
           pines = data.items;
       }
   });
}   

function getusersingroup(serviceURL) 
{
   $.ajax(
   {
       url: serviceURL,
       dataType: "json",
       async: false,
       success: function(data)
       {
			//alert('Data Fetched');
           usr = data.items;
		   //alert(usr.length);
       }
   });
}   

function getUserEvent(serviceURL) 
{
   $.ajax(
   {
       url: serviceURL,
       dataType: "json",
       async: false,
       success: function(data)
       {
			//alert('Data Fetched');
           userEvent = data.items;
		   //alert(usr.length);
       }
   });
}   

function getgroupevents(serviceURL) 
{
   $.ajax(
   {
       url: serviceURL,
       dataType: "json",
       async: false,
       success: function(data)
       {
			//alert('Data Fetched');
           type_events = data.items;
		   //alert(usr.length);
       }
   });
}   

function getCpts(serviceURL) 
{
   $.ajax(
   {
       url: serviceURL,
       dataType: "json",
       async: false,
       success: function(data)
       {
			//alert('Data Fetched');
           cpts = data.items;
		   //alert(usr.length);
       }
   });
} 

function getIcds(serviceURL) 
{
   $.ajax(
   {
       url: serviceURL,
       dataType: "json",
       async: false,
       success: function(data)
       {
			//alert('Data Fetched');
           icds = data.items;
		   //alert(usr.length);
       }
   });
} 

function getIcdData(serviceURL) 
{
		setImageVisible('Wait1', true);
   $.ajax(
   {
       url: serviceURL,
       dataType: "json",
       async: false,
       success: function(data)
       {
			//alert('Data Fetched');
           icdData = data.items;
		   	setImageVisible('Wait1', false);
	
       }
   });
} 

function getSelectedIcdData(serviceURL) 
{
	setImageVisible('Wait1', true);//$('#Wait1').show();
   $.ajax(
   {
       url: serviceURL,
       dataType: "json",
       async: false,
       success: function(data)
       {
			//alert('Data Fetched');
           selectedIcdData = data.items;
		   setImageVisible('Wait1', false);
		 //  $('#Wait1').hide();
		   //alert(usr.length);
       }
   });
} 

function getPas(serviceURL) 
{
   $.ajax(
   {
       url: serviceURL,
       dataType: "json",
       async: false,
       success: function(data)
       {
			//alert('Data Fetched');
           pas = data.items;
		   //alert(usr.length);
       }
   });
}

function getnumrows(serviceURL) {
    $.ajax(
           {
           url: serviceURL,
           dataType: "json",
           async: false,
           success: function(data)
           {
			numrow = data.items;
		   console.log(data);
           }
           });
     }   
Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
function getTransactionsTotal(serviceURL) 
{
   $.ajax(
   {
       url: serviceURL,
       dataType: "json",
       async: false,
       success: function(data)
       {
           tt = data.items;
		    $("#totalAmt").prop('readonly', false);
			$("#totalCollect").prop('readonly', false);
		   document.getElementById('totalAmt').innerHTML ='$'+(parseFloat(tt[0].tAmount)).formatMoney(2,'.',',');
		   document.getElementById('totalCollect').innerHTML ='$'+(parseFloat(tt[0].tCollected)).formatMoney(2,'.',',');
		   //alert(tt[0].tAmount);
		   document.getElementById('totalPend').innerHTML ='$'+((parseFloat(tt[0].tAmount)-parseFloat(tt[0].tCollected))).formatMoney(2,'.',',');
       }
   });
}

// function loadingIcdCodes() {
		// /* var val=10;
	 // (function myLoop (i,val) {          
		// setTimeout(function () {   
         // $("div[class='bar']").width(val); 
          // val=val+90;          
		  // if (--i) myLoop(i,val);     
	   // }, 200)
	// })(15,10);*/
	// $('.tooltip').tooltipster('show');
	// var progressbar = $( "#progressbar" ),
	// progressLabel = $( ".progress-label" );
	// progressbar.width(1025);
	// progressbar.css({"margin-left":'125px'});
	// progressbar.progressbar({
	// value: false,
	// change: function() {
	// progressLabel.css({"color":'white'});
	// progressLabel.css({"text-shadow":'none'});
	// progressLabel.text( progressbar.progressbar( "value" ) + "%" );
	// },
	// complete: function() {
	// progressLabel.css({"color":'white'});
	// progressLabel.css({"font-size":'10px'});
	// progressLabel.css({"text-shadow":'none'});
	// progressLabel.css({"text-align":'center'});
	// progressLabel.css({"margin-left":'-50px'});
	// progressLabel.text( "Decryption Complete!" );
	// setTimeout( function(){
	// $("#progressstatus").hide();
	// $("#progressbar").hide();
	// $("#encryptbox").hide();
	// $("#tabsWithStyle").show();
	   // var reportstobereviewed=$('#reportid_review').val();
	   // var reportstobereviewed_ids=reportstobereviewed.split(" "); 
	   // for (var i = 0, len = reportstobereviewed_ids.length; i < len; ++i)
	   // {
		  // alert(reportstobereviewed_ids[i]);
		  // id^="reportcol"]'
		  // $('i[id^="report-eye"]').each(function(){
		  
		  // var id=parseInt($(this).parents("div.note2").attr('id'));
		  // alert(id);
		  // if(id==parseInt(reportstobereviewed_ids[i]))
		  // {
		   // $(this).css("color","#000000");
		  // }
		  
		  
		  
		  // });
	   // $('div#'+reportstobereviewed_ids[i]).find("#report-eye").css("color","#3d93e0");
	   
	   // }
		
	
	// }, 2000 );
	// }
	// });
	// }
	
	// function progress() {
	// var progressbarValue = progressbar.find( ".ui-progressbar-value" );
	// var val = progressbar.progressbar( "value" ) || 0;
	// progressbar.progressbar( "value", val + 1 );
	// progressbar.css({"background": 'black'});
	// progressbarValue.css({"background": '#4169E1'});    //#5882FA
	// if ( val < 99 ) {
	// setTimeout( progress, 50 );
	// }
	// }
	// setTimeout( progress, 1000 );
	
	/*setTimeout(function(){
	 //$("#progressbar").hide();
	 //alert("triggered");
	 $('.TABES:eq(9)').trigger('update');
	 
	 },3000);*/
	
	//};
	/*KYLE
window.onload = function () {
    (function () {
        var a = document.getElementById('myDiv');
        if (a) {
            // do something with a, you found the div
        }
        else {
            setTimeout(arguments.callee, 50); // call myself again in 50 msecs
        }
    }());
};

function setImageVisible(id, visible) {
    var img = document.getElementById(id);
    img.style.visibility = (visible ? 'visible' : 'hidden');
}
	
var queUrl = "";
function loadTransactionsForUser(isSessionUser)
{
	setImageVisible('Wait1',true);
    var searchProcedure = $('#SearchProcedure').val();
	var user = <?php echo $_SESSION['MEDID'];?>;
	var NumFilas =0;
	if ($('#CRows').is(":checked"))	NumFilas = 1; 
	//alert(isSessionUser);
	if(isSessionUser=='0')	//load transactions of user that is selected as current user
	{
		var opts = document.getElementById('currentUser');
		var selectedIndex = document.getElementById('currentUser').selectedIndex;
		var user = opts.options[opts.selectedIndex].value;
	}
	else if(isSessionUser=='2') //load transactions of user whose data is recently updated
	{
		var opts = document.getElementById('doctor');
		var selectedIndex = document.getElementById('doctor').selectedIndex;
		document.getElementById('currentUser').selectedIndex=selectedIndex;
		var user = opts.options[opts.selectedIndex].value;
	}
    var max;		  
	if(NumFilas==0)
	{
		max=15;
	}
	else
	{
		max=30;
	}
	 allTicks = $('#TablaPac').find('input:checked').map(function() { 
	  return this.id; });
	  // alert('ordering: '+ordering);
	 // alert('sorted: '+sorted);
	var numUrl ='<?php echo $domain;?>/getTransactionsCount.php?userid='+user+'&GetOrder='+ ordering+'&GetSort='+ sorted+'&GetSearch='+ searchProcedure;  
	getnumrows(numUrl);	
	var longit = Object.keys(numrow).length;	
    var totalpage=Math.ceil(numrow[0].num/max);	
    console.log(totalpage);	
	if(numrow[0].num<max)
	{
		currpage=totalpage=1;
	}
	if(currpage>totalpage)
	{
		currpage=1;
	}
	
	$('#CurrentPage').text(currpage+" of "+totalpage);
	var GetCurrent= 0;
	if ($('#CCurrent').is(":checked")) GetCurrent = 1;
	//alert(ordering);
	if (GetCurrent==1)
	{			
	  queUrl ='<?php echo $domain;?>/getTransactions.php?userid='+user+'&GetOrder='+ ordering+'&GetSort='+ sorted+'&GetSearch='+ searchProcedure+'&Getcurrpage='+currpage+'&NumF='+NumFilas+'&current=1';
	}
	else
	{
	  queUrl ='<?php echo $domain;?>/getTransactions.php?userid='+user+'&GetOrder='+ ordering+'&GetSort='+ sorted+'&GetSearch='+ searchProcedure+'&Getcurrpage='+currpage+'&NumF='+NumFilas+'&current=0';
	}
	//console.log(queUrl);
	$('#TablaPac tbody > tr').remove();
	$('#TablaPac').load(queUrl);
	$('#TablaPac').trigger('click');
	$('#TablaPac').trigger('update');
	 if(allTicks.length!=0)
	  // alert("You have checked some columns!");
	   //$(document).ready(function (){
	  setTimeout(function(){ for (i = 0 ; i < allTicks.length; i++ ){
			//alert("array"+document.getElementById(allTicks[i]));
			document.getElementById(allTicks[i]).checked = true;
			// $('#' + allTicks[i]).attr('checked', 'checked');
	   }},600);	 
	setImageVisible('Wait1',false);
}
   	// $('#TablaPac').load(queUrl, function() {
     // $('#Wait1').hide()  
	// });
	
	
function loadSelectableIcds()
{
	//loadingIcdCodes();
	//$('#Wait1').show();
	setImageVisible('Wait1',true);
	getIcdData('getIcdData.php');
	var selectbox = document.getElementById("m-selectable");
	var i;
    for(i=selectbox.options.length-1;i>=0;i--)
    {
        selectbox.remove(i);
    }
		 for(var i=0;i<icdData.length;i++)
		{
		//alert(usr[i].idmedfixedname);
		var opt = document.createElement("option");
		opt.text = icdData[i].DiagnosisCode+'-'+icdData[i].LongDescription;	
        opt.value = icdData[i].ID;	
		// icdHash[selectedIcdData[i].icdCode]=selectedIcdData[i].ID;	
        // Add an Option object to Drop Down/List Box
        document.getElementById("m-selectable").options.add(opt);
        // Assign text and value to Option object
		}
	//setImageVisible('Wait1', false);
	//$('#Wait1').hide();
	setImageVisible('Wait1',false);
	$('#BotonModalICD').trigger('click');
}

function loadSelectedIcds()
{
	setImageVisible('Wait1', true);
	getSelectedIcdData('getSelectedIcdData.php');
	var selectbox = document.getElementById("m-selected");
	var i;
	//alert("loadSelectedIcds");
	// for(i=0;i<=selectbox.options.length-1;i++)
	// {
		// previousSelectedIds.push(selectbox.options[i].value);
		// alert(selectbox.options[i].value);
	// }
   for(i=selectbox.options.length-1;i>=0;i--)
    {
        selectbox.remove(i);
    }
	for(var i=0;i<selectedIcdData.length;i++)
	{
		//alert(usr[i].idmedfixedname);
		var opt = document.createElement("option");
		opt.text = selectedIcdData[i].DiagnosisCode+'-'+selectedIcdData[i].LongDescription;	
        opt.value = selectedIcdData[i].ID;	
		previousIcdCodeIds.push(selectedIcdData[i].ID);
		// icdHash[selectedIcdData[i].icdCode]=selectedIcdData[i].ID;	
        // Add an Option object to Drop Down/List Box
        document.getElementById("m-selected").options.add(opt);
        // Assign text and value to Option object
    }
	setImageVisible('Wait1', false);
}

function loadDoctors()
{
	var opts = document.getElementById('doctor');
	var selectedIndex = document.getElementById('doctor').selectedIndex;
	current_user = opts.options[opts.selectedIndex].value;
	var select = document.getElementById("doctor");
	var length = select.options.length;
	for (var i=select.options.length-1;i>=0;i--) 
	{
		select.options[i] = null;
	}
	for(var i=0;i<type_events.length;i++)
	{
		if(type_events[i].userid==current_user)
		{
			select.options[select.options.length] = new Option(type_events[i].title, type_events[i].id);
		}
	}
}

  function setOffset()
  {
   var top = ($("body").height() / 2) - ($("#BotonModal").height()/2);
   var left = ($("body").width() / 2) - ($("#BotonModal").width()/2);
   // alert(top);
   // alert(left);
   $("#BotonModal").offset({top: top, left: left});
  }
  
  function sortTable(col)
	{
		if(ordering == col)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=col;
		$("#BotonBusquedaPac").trigger('click');
	}
	//$.configureBoxes();
	// $(window).load(function() {
	// {
		// $('#Wait1').show();  
	// }
   $(document).ready(function() {
	//Step 0: Get all patients in users groups

	var GetCurrent= 0;
	var NumFilas = 0; 
	getpatients('getuserpatients.php');
	addDefaultUser(); addDefaultCPTProcedure(); addDefaultICDCode(); addDefaultAgency();setOffset();
	getTransactionsTotal('getTransactionsTotal.php?userid=<?php echo $_SESSION['MEDID'];?>');  
	 $("#CRows").click(function(event) {
		
		if ($('#CRows').is(":checked"))	NumFilas = 1; 
		loadTransactionsForUser('1');
	 });
	 
	 
	 $("#CCurrent").click(function(event) {
			// var opts = document.getElementById('doctor');
			// var selectedIndex = document.getElementById('doctor').selectedIndex;
			// document.getElementById('currentUser').selectedIndex=selectedIndex;
			// var user = opts.options[opts.selectedIndex].value;
			// var GetCurrent= 0;
    	    // if ($('#CCurrent').is(":checked")) GetCurrent = 1;
			// var max;		  
			// if(NumFilas==0)
			// {
				// max=15;
			// }
			// else
			// {
				// max=30;
			// }
			 // allTicks = $('#TablaPac').find('input:checked').map(function() { 
			 // alert(this.id);
			  // return this.id; });
			// var numUrl ='<?php echo $domain;?>/getTransactionsCount.php?userid=<?php echo $_SESSION['MEDID'];?>&GetOrder='+ ordering+'&GetSort='+ sorted+'&NumF='+NumFilas+'&GetSearch='+ searchcriteria;  
			// getnumrows(numUrl);
			// var longit = Object.keys(numrow).length;	
    	    // var totalpage=Math.ceil(numrow[0].num/max);			
			
			// if(currpage<1)
			// {
				// currpage=totalpage;
			// }
			
			// if(currpage>totalpage)
			// {
				// currpage=1;
			// }
			// $('#CurrentPage').text(currpage+" of "+totalpage);
			GetCurrent = 0;
    	    if ($('#CCurrent').is(":checked")) GetCurrent = 1;
			loadTransactionsForUser('1');
			// if (GetCurrent==1)
			// {			
			 // var queUrl ='<?php echo $domain;?>/getTransactions.php?userid=<?php echo $_SESSION['MEDID'];?>&GetOrder='+ ordering+'&GetSort='+ sorted+'&NumF='+NumFilas+'&GetSearch='+ searchcriteria+'&Getcurrpage='+currpage+'&current=1';
			// }
			// else
			// {
			 // var queUrl ='<?php echo $domain;?>/getTransactions.php?userid=<?php echo $_SESSION['MEDID'];?>&GetOrder='+ ordering+'&GetSort='+ sorted+'&NumF='+NumFilas+'&GetSearch='+ searchcriteria+'&Getcurrpage='+currpage+'&current=0';
			// }
			// $('#TablaPac tbody > tr').remove();
			// $('#TablaPac').load(queUrl);
    	    // $('#TablaPac').trigger('click');
    	    // $('#TablaPac').trigger('update');
			 // if(allTicks.length!=0)
			  // setTimeout(function(){ for (i = 0 ; i < allTicks.length; i++ ){
					// document.getElementById(allTicks[i]).checked = true;
			   // }},600);	 
		});
		
	$("#prev").click(function(event) {
		currpage=currpage-1;
		decrypt=true;
		$('#BotonBusquedaPac').trigger('click');
	 });
	 
	$("#next").click(function(event) {
		currpage=currpage+1;
		decrypt=true;
		$('#BotonBusquedaPac').trigger('click');
	 });
	 $table = $("#Tablapac");
	 $("thead>tr",$table).click(function(event) {
		if(ordering == 1)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=1;
		$("#BotonBusquedaPac").trigger('click');
	});
	
	
	
	$("#col2").click(function(event) {
		if(ordering == 2)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=2;
		$("#BotonBusquedaPac").trigger('click');
	});
	
	$("#col3").click(function(event) {
		if(ordering == 3)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=3;
		$("#BotonBusquedaPac").trigger('click');
	});
	
	$("#col4").click(function(event) {
		if(ordering == 4)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=4;
		$("#BotonBusquedaPac").trigger('click');
	});
	
	$("#col5").click(function(event) {
		if(ordering == 5)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=5;
		$("#BotonBusquedaPac").trigger('click');
	});
	$("#col6").click(function(event) {
		if(ordering == 6)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=6;
		$("#BotonBusquedaPac").trigger('click');
	});
	$("#col7").click(function(event) {
		if(ordering == 7)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=7;
		$("#BotonBusquedaPac").trigger('click');
	});
	
	$("#col8").click(function(event) {
		if(ordering == 8)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=8;
		$("#BotonBusquedaPac").trigger('click');
	});
	
	$("#col9").click(function(event) {
		if(ordering == 9)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=9;
		$("#BotonBusquedaPac").trigger('click');
	});
	
    // $("tr.CFILA td.checktab,#nxtaction,#prev,#next").live("mouseenter",function () {
			// $(this).css("background","LightSteelBlue");
			// $(this).css("cursor","pointer");
		// });
		
	// $("tr.CFILA td.checktab,#nxtaction,#prev,#next").live("mouseleave",function () {
			// $(this).css("background","");
	// });
	 
	 $("#BotonBusquedaPac").click(function(event) {
		loadTransactionsForUser('1');
			// var searchcriteria = $('#SearchProcedure').val()
			// var NumFilas = 0;
    	    // if ($('#CRows').is(":checked"))	NumFilas = 1; 
			// var GetPending = 1;
    	    // if ($('#CPending').is(":checked")) GetPending = 0;
			// var GetOthers = 1;
    	    // if ($('#COthers').is(":checked")) GetOthers = 0;
			// alert ('Rows =  '+NumFilas+' Others = '+GetOthers+'Pending = '+GetPending  );
			
    	    // var IdUs =156;
    	    // var UserInput = $('#SearchUser').val();
			// var max;		  
			// if(NumFilas==0)
			// {
				// max=15;
			// }
			// else
			// {
				// max=30;
			// }
			// allTicks = $('#TablaPac').find('input:checked').map(function() { 
			 // alert(this.id);
			  // return this.id; });
			// var GetCurrent= 0;
    	    // if ($('#CCurrent').is(":checked")) GetCurrent = 1;
			// if (GetCurrent==1)
			// {			
			 // var numUrl ='<?php echo $domain;?>/getTransactionsCount.php?userid=<?php echo $_SESSION['MEDID'];?>&GetOrder='+ ordering+'&GetSort='+ sorted+'&GetSearch='+ searchcriteria+'&NumF='+NumFilas+'&current=1';
			// }
			// else
			// {
			 // var numUrl ='<?php echo $domain;?>/getTransactionsCount.php?userid=<?php echo $_SESSION['MEDID'];?>&GetOrder='+ ordering+'&GetSort='+ sorted+'&GetSearch='+ searchcriteria+'&NumF='+NumFilas+'&current=0';
			// }
			// getnumrows(numUrl);
			// var longit = Object.keys(numrow).length;	
    	    // var totalpage=Math.ceil(numrow/max);			
			// console.log(numrow.num/max);
			// if(totalpage<1)
			// {
				// totalpage=1;
			// }
			
			// $('#CurrentPage').text("Page "+currpage+" of "+totalpage);
			// $('#CurrentPage').text(currpage+" of "+totalpage);
			// var GetCurrent= 0;
    	    // if ($('#CCurrent').is(":checked")) GetCurrent = 1;
			// if (GetCurrent==1)
			// {			
			 // var queUrl ='<?php echo $domain;?>/getTransactions.php?userid=<?php echo $_SESSION['MEDID'];?>&GetOrder='+ ordering+'&GetSort='+ sorted+'&GetSearch='+ searchcriteria+'&NumF='+NumFilas+'&Getcurrpage='+currpage+'&current=1';
			// }
			// else
			// {
			 // var queUrl ='<?php echo $domain;?>/getTransactions.php?userid=<?php echo $_SESSION['MEDID'];?>&GetOrder='+ ordering+'&GetSort='+ sorted+'&GetSearch='+ searchcriteria+'&NumF='+NumFilas+'&Getcurrpage='+currpage+'&current=0';
			// }
			// console.log(queUrl);
			// $('#TablaPac tbody > tr').remove();
			// $('#TablaPac').load(queUrl);
    	    // $('#TablaPac').trigger('click');
    	    // $('#TablaPac').trigger('update');
			 // if(allTicks.length!=0)
			  // alert("You have checked some columns!");
			   // $(document).ready(function (){
			  // setTimeout(function(){ for (i = 0 ; i < allTicks.length; i++ ){
					// alert("array"+document.getElementById(allTicks[i]));
					// document.getElementById(allTicks[i]).checked = true;
					// $('#' + allTicks[i]).attr('checked', 'checked');
			   // }},600);	 
	 });
	
	for(var i=0;i<patients.length;i++)
	{
		availablePatientTags[i]=patients[i].idusfixedname;
		pats[patients[i].identif] = patients[i].idusfixedname;
		//alert('adding ' + patients[i].identif + '  ' + patients[i].idusfixedname);
	}
	
	
   $( "#tags" ).autocomplete({
      source: availablePatientTags ,
	  appendTo: "#header-modal"
	  
	  
    });
	
	
	// $("#SearchProcedure").typeWatch({
				// captureLength: 1,
				// callback: function(value) {
					// $("#BotonBusquedaPac").trigger('click');
				//	alert('searching');
				// }
	// });
	
	//alert(availablePatientTags.length);
	
	//Step 1 : Get all other doctors in users groups 
	getusersingroup('getusersingroup.php?userid=<?php echo $_SESSION['MEDID'];?>');  //fills usr
	
	//getgroupevents('get_group_events.php?userid=<?php echo $_SESSION['MEDID'];?>');  //fills type_events
	
	//getUserEvent('getusereventconfig.php?userid=<?php echo $_SESSION['MEDID'];?>');  //fills user_event
	
	getCpts('getCpts.php');
	
	getIcds('getIcds.php');
	
	//$('#Wait1').show();
	
	 getIcdData('getIcdData.php');
	
	 getSelectedIcdData('getSelectedIcdData.php');
	
	//$('#Wait1').hide();
	
	getPas('getPas.php');
	//Step 2 : Assign colors randomly to each user
	//		   Dynamically create buttons for each user
	var foo = document.getElementById("fooBar");
	
	var elem = document.createElement("input");
		elem.setAttribute("type","button");
		elem.setAttribute("value",'All');
		elem.setAttribute("id",'all');
		elem.setAttribute("name",'all');
		
		//foo.appendChild(elem);
		
    for(var i=0;i<usr.length;i++)
	{
		if(i<colours.length)
		{
			user[usr[i].id]=colours[i];
		}
		else
		{
			user[usr[i].id]=get_random_color();
		}
		event_sources[usr[i].id] = new Array();		
	}
	for(var i=0;i<usr.length;i++)
	{
		//alert(usr[i].idmedfixedname);
		var opt = document.createElement("option");
		opt.text = usr[i].idmedfixedname;
        opt.value = usr[i].id;	
        // Add an Option object to Drop Down/List Box
        document.getElementById("currentUser").options.add(opt);
        // Assign text and value to Option object
     }
	var opts = document.getElementById('currentUser');
	for(var i=0;i<usr.length;i++)
	{
		if(opts.options[i].value == <?php echo $_SESSION['MEDID']; ?>)
		 opts.selectedIndex = i;
	}
	
	for(var i=0;i<usr.length;i++)
	{
		//alert(usr[i].idmedfixedname);
		var opt = document.createElement("option");
		opt.text = usr[i].idmedfixedname;
        opt.value = usr[i].id;	
        // Add an Option object to Drop Down/List Box
        document.getElementById("doctor").options.add(opt);
        // Assign text and value to Option object
     }
	var opts = document.getElementById('doctor');
	for(var i=0;i<opts.options.length;i++)
	{
		if(opts.options[i].value == <?php echo $_SESSION['MEDID']; ?>)
		 opts.selectedIndex = i;
	}
	
	for(var i=0;i<cpts.length;i++)
	{
		//alert(usr[i].idmedfixedname);
		var opt = document.createElement("option");
		opt.text = cpts[i].cptProcedure;
        opt.value = cpts[i].ID;	
        // Add an Option object to Drop Down/List Box
        document.getElementById("cptProcedure").options.add(opt);
        // Assign text and value to Option object
     }
	 var opts = document.getElementById('cptProcedure');
	
	 for(var i=0;i<opts.options.length;i++)
	{
		if(opts.options[i].value ==  "--Select CPT Procedure--")
		 opts.selectedIndex = i;
	}
	//	setSelectedValue( document.getElementById("cptProcedure"),);
	
	for(var i=0;i<icdData.length;i++)
	{
		//alert(usr[i].idmedfixedname);
		var opt = document.createElement("option");
		opt.text = icdData[i].DiagnosisCode+'-'+icdData[i].LongDescription;	
        opt.value = icdData[i].ID;	
		// icdHash[icdData[i].icdCode]=icdData[i].ID;	
        // Add an Option object to Drop Down/List Box
        document.getElementById("box1View").options.add(opt);
        // Assign text and value to Option object
     }

	 
	for(var i=0;i<icds.length;i++)
	{
		//alert(usr[i].idmedfixedname);
		var opt = document.createElement("option");
		opt.text = icds[i].icdCode;	
        opt.value = icds[i].ID;	
		icdHash[icds[i].icdCode]=icds[i].ID;	
        // Add an Option object to Drop Down/List Box
        document.getElementById("icdCodes").options.add(opt);
        // Assign text and value to Option object
     }
	  var opts = document.getElementById('icdCodes');
	  

	 for(var i=0;i<opts.options.length;i++)
	{
		if(opts.options[i].value ==  "--Select ICD Code--")
		 opts.selectedIndex = i;
	}
	for(var i=0;i<patients.length;i++)
	{
		//alert(usr[i].idmedfixedname);
		var opt = document.createElement("option");
		opt.text = patients[i].idusfixedname;	
        opt.value = patients[i].identif;		
        // Add an Option object to Drop Down/List Box
        document.getElementById("patient1").options.add(opt);
        // Assign text and value to Option object
    }
	
	 var opts = document.getElementById('patient1');
	 for(var i=0;i<opts.options.length;i++)
	{
		if(opts.options[i].value ==  "--Select User--")
		 opts.selectedIndex = i;
	}
	for(var i=0;i<pas.length;i++)
	{
		//alert(usr[i].idmedfixedname);
		var opt = document.createElement("option");
		opt.text = pas[i].paName;
        opt.value = pas[i].ID;	
        // Add an Option object to Drop Down/List Box
        document.getElementById("payingAgency").options.add(opt);
        // Assign text and value to Option object
     }
	
	 var opts = document.getElementById('payingAgency');
	 for(var i=0;i<opts.options.length;i++)
	{
		if(opts.options[i].value ==  "--Select Agency--")
		 opts.selectedIndex = i;
	}
	
	var demo1 = $('[name="box1"]').multiselectable();
	event_sources['all'] = new Array();
	
	//Step 3 : Get all events of the group
	geteventData('events.php?userid=<?php echo $_SESSION['MEDID'];?>');
	
	//getUserEvent('getusereventconfig.php?userid=<?php echo $_SESSION['MEDID'];?>');
	
	//Step 4 : Create data sources for each user and each 'All' 
		 
	// for(var i=0;i<pines.length;i++)
	// {
		// var flag = false;
		// alert("pine: "+pines[i].title)
		// var event = new Object();
		// event.id = pines[i].id;
		// event.start = pines[i].start; // this should be date object
		// event.end = pines[i].end; // this should be date object
	
		
		// for(var j=0;j<userEvent.length;j++)
		// {
		 // alert("user: "+userEvent[j].title);
		 
		 // alert(pines[i].title + ' Non-Working Hours');
		 // if(pines[i].title=='Non-Working Hours')
		 // {
			// alert("Non working match");
			// event.title = ''; // this should be string
			// event.color="#A4A4A4";
			// event.allDay = false;
		    // event_sources[pines[i].userid].push(event);
			// event_sources['all'].push(event);
			// flag = true;
			// break;
			// event.textColor="#A4A4A4";
	 	 // } 
		 // else if(pines[i].title==userEvent[j].title)
		 // {
			// alert('first' + pats[pines[i].patient] + '  ' + pines[i].id);
			// event.title = pats[pines[i].patient]; // this should be string
			// event.color = "#"+userEvent[j].colour;
			// alert("pines,user match:  "+event.color);
			// event.allDay = false;
			// event_sources[pines[i].userid].push(event);
			// event_sources['all'].push(event);
			// flag = true;
			// break;
	 	 // }
		
		// }
		// if(flag==false)
		// {
			// if(pines[i].title=='Non-Working Hours')
			// {
				// event.title = ''; // this should be string
				// event.color='#A4A4A4';
			// }
			// else
			// {
				// event.title = pines[i].title; // this should be string
				// event.color = user[pines[i].userid];
			// }
			
			// event.allDay = false;
			// event_sources[pines[i].userid].push(event);
			// event_sources['all'].push(event);
			// flag = true;
		// }
	// }
	// alert(event_sources[28].length);
 
	
	// var select = document.getElementById("appointment");
	// var length = select.options.length;
	
	// for (var i=select.options.length-1;i>=0;i--) 
	// {
		// select.options[i] = null;
	// }
	
	
	// for(var i=0;i<type_events.length;i++)
	// {
		// if(type_events[i].userid==current_source)
		// {
			// select.options[select.options.length] = new Option(type_events[i].title, type_events[i].id);
		// }
		
	// }
	
	 $(".logo").loadingbar({
	  target: "#loadingbar-frame",
	  replaceURL: false,
	  direction: "right",
	 
	  /* Default Ajax Parameters.  *//*KYLE
	  async: true, 
	  complete: function(xhr, text) {},
	  cache: true,
	  error: function(xhr, text, e) {},
	  global: true,
	  headers: {},
	  statusCode: {},
	  success: function(data, text, xhr) {},
	  dataType: "html",
	  done: function(data) {}
	});
	
 });


</script>
</head>

<body onload="$('#BotonBusquedaPac').trigger('click');" style="background: #F9F9F9;">
<div class="loader_spinner"></div>
	<input type="hidden" id="NombreEnt" value="<?php echo $NombreEnt; ?>">
	<input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?>">
	<input type="hidden" id="UserHidden">

	<!--Header Start-->
	<div class="header" >
     	<input type="hidden" id="USERDID" Value="<?php echo $USERID; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
		<input type="hidden" id="start">	
		<input type="hidden" id="end">	
  		<input type="hidden" id="eventid">	
        <a href="index.html" name='pageload' class="logo"><h1>Health2me</h1></a>
         
        <div class="pull-right">
           
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
				<a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
					<span class="name-user"><strong>Welcome</strong> Dr, <?php echo $MedUserName.' '.$MedUserSurname; ?></span> 
					<?php 
						$hash = md5( strtolower( trim( $MedUserEmail ) ) );
						$avat = 'identicon.php?size=29&hash='.$hash;
					?>	
					<span class="avatar" style="background-color:WHITE;"><img src="<?php echo $avat; ?>" alt="" ></span> 
					<span class="caret"></span>
				</a>
				<div class="dropdown-menu" id="prof_dropdown">
					<div class="item_m"><span class="caret"></span></div>
					<ul class="clear_ul" >
						<li><a href="dashboard.php"><i class="icon-globe"></i> Home</a></li>
						<li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li>
						<li><a href="logout.php"><i class="icon-off"></i> Sign Out</a></li>
					</ul>
				</div>
          </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->
      <div id="loadingbar-frame"></div>
    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">
    
    	    
		<!--SpeedBar Start--->
		<div class="speedbar">
			<div class="speedbar-content">
				<ul class="menu-speedbar">
					<li><a href="dashboard.php" >Dashboard</a></li>
					<li><a href="patients.php" class="act_link">Patients</a></li>
					<li><a href="medicalConnections.php" >Doctor Connections</a></li>
					<li><a href="PatientNetwork.php" >Patient Network</a></li>
					<li><a href="medicalConfiguration.php">Configuration</a></li>
					<li><a href="logout.php" style="color:yellow;">Sign Out</a></li>
				</ul>
			</div>
		</div>
     <!--SpeedBar END-->
     
     
     
		<!--CONTENT MAIN START-->
		<div class="content">
			<!-- Pop up(Add Transaction) Start-->
			<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;height:568px; width:820px; ">Modal with Header</button>
			
				<div id="header-modal" class="modal hide" style="display: none; height:568px; width:820px;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal" onclick="clearData()">×</button>
					<div id="InfB" >
	                 	<h4>Transaction Details</h4>
					</div>
        
					<div id="InfoIDPacienteB" style="float:left;  margin-left:70%;">
		
					</div>
				</div>
         
				<div class="modal-body" id="ContenidoModal" style="height:568px;width:820px;">
				<table style="margin:20px; background:transparent;">
				<tr style="margin:10px;height:10px;">
				</tr>
				<tr style="margin:10px;height:15px;">
				<td >Select Patient:<select id="patient1" onchange="createPatientReports()" style="height:32px;width:160px;left-margin:5px;"></td>
				<td style="margin:20px;height:4px;width:230px;margin-left=5px;">&nbsp;&nbsp;&nbsp;Select Date of Transaction<input type="text" id="tranDate" style="width:130px;height:30px;margin-left:10px;"  /></td>
				<td style="margin:20px;width 150px;" colspan="2">&nbsp;&nbsp;Select Doctor:<select id="doctor"  style="height:32px;width:200px;left-margin:5px;"></td>
				</tr>	
				<tr style="height:15px"></tr>
				<tr style="margin:20px;height:15px;margin-top:15px;">
				<td colspan="3">Select CPT Procedure:<select id="cptProcedure" style="height:32px;width:350px"></td>
			<!--	<td ><input type="button" class="btn btn-primary" value="Manage CPT codes" style="width:120px;margin-top:25px;margin-left:8px"  id="manageCPT"></td>-->
				<td ></td>
				</tr>
				<tr style="height:15px"></tr>
				<tr style="margin:10px;height:15px;margin-top:15px;">
				<td colspan="2">Select Paying Agency:
				<select id="payingAgency" style="height:35px;width:250px; margin-top:8px"></td>
			<!--	<td><input type="button" class="btn btn-primary" value="Add Agency" style="width:140px" id="addAgency"></td> -->
				</tr>
				<tr style="height:15px"></tr>
				<tr style="margin:10px;height:15px;margin-top:15px;">
				<td style="width:130px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Amount:
				 <input type="text" id="amount1"  style="width:150px; height:30px;"/></td>
				<td style="text-align:left" colspan="2">Collected:
				<input type="text" id="collected"  style="width:150px; height:30px;"/></td>
				</tr>
				<tr style="height:15px"></tr>
				<tr style="margin:20px;height:15px;margin-top:15px;">
				<td style="width:230px">ICD Codes: <input type="text" id="icdCodesTxt" style="width:230px;height:30px;" /></td>
				<td style="width:150px"><input type="button"  class="btn btn-primary" value="Add ICD Code"  style="width:150px;margin-top:8px;margin-left:12px;" id="addICDCode"></td>
				<td style="width:200px"><select id="icdCodes" style="height:25px;width:200px;margin-top:8px;"></td> 
	<!--			<td style="width:180px"><input type="button" class="btn btn-primary" value="Manage ICD codes" onclick="$('#BotonModalICD').trigger('click');" style="width:120px;margin-left:8px;" id="manageICD"></td> -->
				</tr> 
				<tr>
				<td colspan="3">
				<div id="attachments" style="width:150px">
				</div>
				</td>
				<td>
				</td>
				</tr>
				</table>
				</div>
				
				<div class="modal-footer">
					<!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
					<a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Update Data</a>
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal" onclick="clearData()">Close</a>
				</div>
			</div>  
			
			
			<!--Pop up(Add event) End-->
		<button id="BotonModalCPT" data-target="#header-modal1" data-toggle="modal" onload="loadDoctors()" class="btn btn-warning" style="display: none;height:300px; width:320px; ">Modal with Header</button>
			
				<div id="header-modal1" class="modal hide" style="display: none; height:290px; width:420px;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal" onclick="clearCPTData()">×</button>
					<div id="InfB" >
	                 	<h4>CPT Code Details</h4>
					</div>
        
					<div id="InfoIDPacienteB" style="float:left;  margin-left:70%;">
		
					</div>
				</div>
         
				<div class="modal-body" id="ContenidoModal" >
				<table style="margin:20px; background:transparent;">
				
				<tr style="height:15px"></tr>
				<tr style="margin:20px;height:15px;margin-top:15px;">
				<td>Enter CPT Procedure:</td>
				<td><input type="text" id="cptProcedureTxt" style="height:32px;width:150px"></td>
				</tr>
				<tr style="margin:10px;height:15px;margin-top:15px;">
				<td>Enter CPT Code:</td>
				<td><input type="text" id="cptCodeTxt" style="height:32px;width:150px"></td>
				</tr> 
				</table>
				</div>
				
				<div class="modal-footer">
					<!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
					<a href="#" class="btn btn-success" data-dismiss="modal" id="updateCPT" >Update Data</a>
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal" onclick="clearCPTData()">Close</a>
				</div>
			</div>  	
			
			<!--Pop up(Add event) End-->
	<!--	<button id="BotonModalICD" data-target="#header-modal2" data-toggle="modal" onload="loadDoctors()" class="btn btn-warning" style="display: none;height:230px; width:420px;">Modal with Header</button>
			
				<div id="header-modal2" class="modal hide" style="display: none; height:235px; width:420px;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal" onclick="clearICDData()">×</button>
					<div id="InfB" >
	                 	<h4>ICD Code Details</h4>
					</div>
        
					<div id="InfoIDPacienteB" style="float:left;  margin-left:70%;">
		
					</div>
				</div> -->
         <button id="BotonModalICD" data-target="#header-modal2" data-toggle="modal" onload="loadDoctors();" class="btn btn-warning" style="display: none;height:550px; width:715px;">Modal with Header</button>
			
				<div id="header-modal2" class="modal hide" style="display: none; height:550px; width:715px;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal" onclick="clearICDData()">×</button>
					<div id="InfB" >
	                 	<h4>ICD Code Details</h4>
					</div>
        
					<div id="InfoIDPacienteB" style="float:left;  margin-left:70%;">
		
					</div>
				</div>
				<div class="modal-body" id="ContenidoModal"  >
		<!--		<table style="margin:20px; background:transparent;">
				
				<tr style="margin:10px;height:15px;margin-top:15px;">
				<td>Enter ICD Code:</td>
				<td><input type="text" id="icdCodeTxt" style="height:32px;width:150px"></td>
				</tr> 
				</table> -->
	<!--			<table style="backgound:invisible;">
				<tr>
				<td>	
				Filter: <input type="text" id="box1Filter" />
				<button type="button" id="box1Clear">X</button><br />-->
				
				<select id="box1View" multiple="multiple" name="box1" style="height:400px;width:150px;"></select><br/>
	<!--			<span id="box1Counter" class="countLabel"></span>
				<select id="box1Storage"></select>
				</td>
				<td>
				<button id="to2" type="button"> > </button>
				<button id="allTo2" type="button"> >> </button>
				<button id="allTo1" type="button"> << </button>
				<button id="to1" type="button"> < </button>
				</td>
				<td>
				Filter: <input type="text" id="box2Filter" />
				<button type="button" id="box2Clear">X</button><br />
				<select id="box2View" multiple="multiple" style="height:500px;width:300px;"></select><br/>
				<span id="box2Counter" class="countLabel"></span>
				<select id="box2Storage"></select>
				</td>
				</tr>
				</table> -->
				</div>
				
				<div class="modal-footer">
					<!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
					<a href="#" class="btn btn-success" data-dismiss="modal" id="updateICD" >Update Data</a>
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal" onclick="clearICDData()">Close</a>
				</div>
			</div>  
		
		<!--- Report Attachments --->
	   <button id="report_modal" data-target="#header-reports" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button> 
   	  <div id="header-reports" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Report Details
         </div>
         <div class="modal-body">
		 <div id="attachments" style="display:none">
			
			
			
		 </div>
		 </div>
		 <div class="modal-footer">
		      <input type="button" class="btn btn-success" value="Attach" id="Attach">	
	          <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseMessage">Close</a>
         </div>
      </div>  
      <!--- Report Attachments --->	
			<!--Pop up(Add event) End-->
	 
		<div class="grid" style="width:1000px;margin: 0 auto; margin-top:30px; padding-top:30px;">
				<div><span class="label label-info" style="left:0px;height:1000px; margin-left:30px; font-size:30px;">Billing and Transactions</span>  </div>
	<!--			 <div class="grid" style="width:97%; margin-top:-20px; margin-bottom:10px;" id="encryptbox">
					<div class="grid-content overflow">
						<div id="progressstatus" style="width:80%; margin:0 auto; text-shadow:none; color:black; text-align:center;">Loading ICD Codes</div>
						<div id="progressbar" style="margin-top:15px; height:20px; width:80%; margin:0 auto; text-align:center;"><div style="margin-left:0px; margin-top:-5px; padding:0px; text-shadow:none; color:white; font-size:10px;" class="progress-label"></div></div> 
					</div>	
				</div>	-->
				<div style="margin:15px; margin-top:5px;">
				<div class="grid" style="padding:10px;  margin-left:10px;margin-right:140px">Select User:<select style="margin-left:270px" id="currentUser" onchange="loadTransactionsForUser('0');" ></select>	</div>						
				<div  style="width:100%; margin-bottom:10px;"></div>
				</div>	
				<div class="grid" style="margin:15px; margin-top:5px;background:transparent;"  >
				<table id="totalTable" style="margin:15px; margin-top:5px;background:transparent;">
					<tr>
					<div style="margin-left:8px"><td style="width:200px;font-size:12px;" > Total Amount: </td></div>
					<div style="margin-left:8px"><td style="width:200px;font-size:12px;"><label id="totalAmt" style="width:120px"></label></td></div>
					<div style="margin-left:8px"><td style="width:220px;font-size:12px;"> Total Collected: </td></div>
					<div style="margin-left:8px"><td style="width:200px;font-size:12px;"><label id="totalCollect" style="width:120px"></label></td></div>
					<div style="margin-left:8px"><td style="width:220px;font-size:12px;">Total Pending: </td></div>
					<div style="margin-left:8px"><td style="width:200px;font-size:12px;"><label id="totalPend" style="width:120px"></label></td></div>
					</tr>
				</table>
				</div>
				<div class="grid" style="width:98%; margin: 0 auto; margin-top:30px; margin-bottom:30px;">
					<div class="grid-title">
						<div class="pull-left"><div class="fam-email-open-image" style="margin-right:10px;"></div>Transactions
						</div>
						<img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px;" id="Wait1"></img>
						<div class="pull-right"></div>
						<div class="clear"></div>   
					</div>
					<div class="search-bar">
						<input type="text" class="span" name="" placeholder="Search pattern" style="width:200px;font-size:12px;" id="SearchProcedure"> 
						<input type="button" class="btn btn-primary" value="Filter" style="margin-left:8px;font-size:12px;" id="BotonBusquedaPac">
<!--						<div><img src="images/ajax-loader.gif" alt="" style="margin-left:0px; margin-top:0px;visibility:hidden" id="loader"></div> -->
						<div style="float:right; margin-right:0px;margin-left:5px;">
							<label class="checkbox toggle candy" onclick="" style="width:100px;">
								<input type="checkbox" id="CCurrent" name="CCurrent" />
								<p>
									<span>Current</span>
									<span>All</span>
								</p>
								<a class="slide-button"></a>
							</label>
						</div>
						<div style="float:right; margin-right:0px;margin-left:5px;">
							<label class="checkbox toggle candy blue" onclick="" style="width:100px; ">
							<input type="checkbox" id="CRows" name="CRows"/>
							<p>
								<span>30</span>
								<span>15</span>
							</p>
							<a class="slide-button"></a>
							</label>
						</div>
					</div>
					<div class="search-bar" style="height:50px;background-color:AliceBlue;" id="numbar">
						<input type="button" class="btn btn-primary" value="New Transaction" style="margin-left:8px;font-size:12px;" id="newTransaction" onclick="$('#BotonModal').trigger('click');">
						<input type="button" class="btn btn-primary" name="ajax-call" value="Manage ICD codes" style="width:180px;margin-left:8px;font-size:12px;" id="manageICD" onclick="loadSelectedIcds();loadSelectableIcds();">
						<td style="width:180px"><input type="button" class="btn btn-primary" value="Manage CPT codes" style="width:180px;margin-left:8px;font-size:12px;" id="manageCPT" onclick="$('#BotonModalCPT').trigger('click');"></td>				
					</div>
					<div class="search-bar" style="height:50px;background-color:AliceBlue;" id="numbar">
					<!--<input type="button" class="btn btn-success" value="delete" style="margin-left:0px;" id="divdelbutton">
					<input type='button' class='btn btn-success' value='verify' style='margin-left:30px;' id='reportvalidate'>
					<!--<label style="float:right;" id="CurrentPage"></label>-->
					<div style="float:right;" class="the-icons">
					<i class="icon-chevron-right" style="padding:10px 10px;float:right;margin-right:0px;" id="next"></i>
					<label style="padding:10px;float:right;margin-right:0px;" id="CurrentPage"></label>
					<i class="icon-chevron-left" style="padding:10px ;float:right;margin-right:0px;" id="prev"></i>
					</div>
					</div>
					<div class="grid" style="margin-top:0px;">
						<table class="table table-mod" style="font-size:12px" id="TablaPac" style="width:100%; table-layout: fixed; ">
						</table> 
					</div>
				</div>
			</div>
	</div>
	
	<script type="text/javascript" >
	
		$('#tranDate').datepicker({
				inline: true,
				nextText: '&rarr;',
				prevText: '&larr;',
				showOtherMonths: true,
				dateFormat: 'yy-mm-dd',
				dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
				showOn: "button",
				buttonImage: "images/calendar-blue.png",
				buttonImageOnly: true,
			});
		//This is a function used to add events . It takes input as text boxes on the popup. So fill data in these textboxes before you call this function
		//$('#GrabaDatos').live('click',function() {
		$(document).on('click', '#updateCPT', function(){ 
			var cptCode = $("#cptCodeTxt").val();
			var cptProc = $("#cptProcedureTxt").val();
			$.ajax({
								url: 'addCptCodes.php',
								data: {'cptCode':cptCode,'cptProcedure':cptProc},
								type	: "POST",
								success: function(json) {
								console.log(json);
								//alert("Updated Successfully");
								$('#Refresh').trigger('click');
								 clearCPTData();
								}
							});
		
		});
		var previousIcdCodeIds= []
		$(document).on('click', '#updateICD', function(){ 
			// var icdCode = $("#icdCodeTxt").val();
			var icdCodes = document.getElementById('m-selected');
			//alert(icdCodes);
			var icdCodeIds = getSelectValues(icdCodes);
			for(var i=0;i<icdCodeIds.length;i++)
				{
					$.ajax({
										url: 'selectIcdCodes.php',
										data: {'ID':icdCodeIds[i]},
										type	: "POST",
										success: function(json) {
										console.log(json);
										//alert("Updated Successfully");
										$('#Refresh').trigger('click');
										clearICDData();
										}
										});
				}
			for(var i=0;i<previousIcdCodeIds.length;i++)
				{
					//alert(previousIcdCodeIds[i]);
					for(var j=0;j<icdCodeIds.length;j++)
					{
						if(icdCodeIds[j]==previousIcdCodeIds[i])
						{
							previousIcdCodeIds.splice(i,1);
						}
					}
				}
				for(var i=0;i<previousIcdCodeIds.length;i++)
				{
					$.ajax({
										url: 'deselectIcdCodes.php',
										data: {'ID':previousIcdCodeIds[i]},
										type	: "POST",
										success: function(json) {
										console.log(json);
										alert("Updated Successfully");
										$('#Refresh').trigger('click');
										clearICDData();
										}
										});
				}
			previousICDCodeIds = icdCodeIds;
		}
				
				
		);
							 
	
	  // $( '#contenidomodal' ).ajaxStart(function() {
	// alert ('ajax start');
	// $('#loader').show();
    // })
	
    // $( '#contenidomodal' ).ajaxComplete(function() {
        // $('#loader').hide();
		// $( '#contenidomodal' ).unbind('ajaxStart');
    // }); 
	
	// $(document).ajaxSend(function(){
	// setImageVisible('loader',true);
	// });
	// $(document).ajaxComplete(function(){
	  // setImageVisible('loader',false);
	// });
	
	function getSelectValues(select) {
  var result = [];
  var options = select && select.options;
  var opt;

  for (var i=0, iLen=options.length; i<iLen; i++) {
    opt = options[i];

   // if (opt.selected) {
      result.push(opt.value || opt.text);
    //}
  }
  return result;
}
function clearICDData()
{
	//document.getElementById("icdCodeTxt").value="";
}
function clearCPTData()
{
	document.getElementById("cptCodeTxt").value="";
	document.getElementById("cptProcedureTxt").value="";
}							
function clearData()
{
	document.getElementById("amount1").value="";
	document.getElementById("collected").value="";
	document.getElementById("icdCodesTxt").value="";
	document.getElementById("tranDate").value="";
	// document.getElementById('cptProcedure').value="--Select CPT Procedure--";
	// document.getElementById('payingAgency').value="--Select Paying Agency--";
	// document.getElementById('icdCodes').value="--Select ICD Code--";
	//alert("cleardata");
	 var opts = document.getElementById('cptProcedure');
	//	opts.value = "--Select CPT Procedure--";
	 for(var i=0;i<opts.options.length;i++)
	{
		//alert(opts.options[i].text);
		if(opts.options[i].text ==  "--Select CPT Procedure--")
		{
			opts.selectedIndex = i;
			//alert(i);
		}
	}

  var opts = document.getElementById('icdCodes');
	 
	 for(var i=0;i<opts.options.length;i++)
	{
		if(opts.options[i].text ==  "--Select ICD Code--")
		 opts.selectedIndex = i;
	}

 var opts = document.getElementById('patient1');
	 for(var i=0;i<opts.options.length;i++)
	{
		if(opts.options[i].text ==  "--Select User--")
		 opts.selectedIndex = i;
	}

 var opts = document.getElementById('payingAgency');
	 for(var i=0;i<opts.options.length;i++)
	{
		if(opts.options[i].text ==  "--Select Agency--")
		 opts.selectedIndex = i;
	}
	$('#attachments').remove();
}		
		
$(document).on('click', '#GrabaDatos', function(){ 
			
	var cptId = $("#cptProcedure option:selected").val();
	if(cptId==0)
	{
		alert("Please select a CPT Procedure.");
		return;
	}
	var drId = $("#doctor option:selected").val();
	var patientId = $("#patient1 option:selected").val();
	if(patientId==0)
	{
		alert("Please select a Patient.");
		return;
	}
	var payerId = $("#payingAgency option:selected").val();
	if(payerId==0)
	{
		alert("Please select a Payment Agency.");
		return;
	}
	var amount1 = $("#amount1").val();
	if(amount1=="")
	{
		alert("Please enter an amount.");
		return;
	}
	var collected = $("#collected").val();
	if(collected=="")
	{
		alert("Please enter an amount to be collected.");
		return;
	}
	var tranDate = new Date($("#tranDate").val()).toMysqlFormat();
	if(tranDate=="")
	{
		alert("Please enter a transaction date.");
		return;
	}
	var icdIds = $("#icdCodesTxt").val();
	if(icdIds=="")
	{
		alert("Please enter an ICD code.");
		return;
	}
	var icdId = icdIds.split(",");
	var reportcheck=new Array();
	var reportids='';
	for(var i=icdIds.length;i>0;i--)
	{
		if(icdIds[i]=="")
		{
			icdIds.splice(i,1);
			break;
		}
	}
	$('input[type=checkbox][id^="reportcol"]').each(
	function () {
			var sThisVal = (this.checked ? "1" : "0");
			//sList += (sList=="" ? sThisVal : "," + sThisVal);
			if(sThisVal==1){
			var idp=$(this).parents("div.attachments").attr("id");
			//alert("Id "+idp+" selected"); 
			reportcheck.push(this.id);
			 //messageid=messageid+idp+' ,';
			reportids=reportids+idp+',';
			}
		});
	 //alert(reportids);
	var conf=false;
	if(reportids>'')
	//		conf=confirm("Confirm Attachments");
	conf=true;
	if(conf){
	//alert ('confirmed');
	$("#AttachButton").attr('value','Reports Attached');
	//$("#attachment_icon").show();

	//$("#attachreportdiv").append('<i id="attachment_icon" class="icon-paper-clip" style="margin-left:10px"></i>');
	//alert(reportids);
	}else{
	reportids='';
		for (i = 0 ; i < reportcheck.length; i++ ){
		document.getElementById(reportcheck[i]).checked = false;	
	}
	reportcheck.length=0;
	//$("#Reply").trigger('click');
	}
	
	for(var i=reportids.length;i>0;i--)
	{
		if(reportids[i]=="")
		{
			reportids.splice(i,1);
			break;
		}
	}
	var reportId= reportids.split(",");
	//alert(reportId);
	var query = "tranDate='"+tranDate+"'&drId="+drId+"&cptId="+cptId+"&amount="+ amount1 +"&collected="+ collected+"&payerId="+payerId+"&patientId="+patientId ;
	console.log(query);
	var transactionId;
	$.ajax({
	url: 'addTransaction.php',
	data: {'tranDate':tranDate,'drId':drId,'cptId':cptId,'amount':amount1,'collected':collected,'payerId':payerId,'patientId':patientId },
	//data: query,
	type	: "POST",
	success: function(json) {
	//alert("Updated Successfully");
	transactionId = json;
	console.log(transactionId);
	$('#Refresh').trigger('click');
			for(var i = 0; i < icdId.length; i++)
			{
			var query ='transactionId='+transactionId+'&icdId='+icdId[i];
			//alert(query);
				$.ajax({
					url: 'addTransactionICD.php',
					data: {'transactionId':transactionId,'icdId':icdHash[icdId[i]]},
					type	: "POST",
					success: function(json) {
					console.log(json);
					//alert("Updated Successfully");
					$('#Refresh').trigger('click');
					
					}
				});
			}	
			for(var i = 0; i < reportId.length; i++)
			{
				var query ='transactionId='+transactionId+'&reportId='+reportId[i];
				//alert(query);
				$.ajax({
					url: 'addTransactionReport.php',
					data: {'transactionId':transactionId,'reportId':reportId[i]},
					type	: "POST",
					success: function(json) {
					console.log(json);
					//alert("Updated Successfully");
					$('#Refresh').trigger('click');
					}
				});
			}	
			loadTransactionsForUser('2');

		}
		});
		clearData();	
});
		
	function createPatientReports(){
		var ElementDOM ='All';
		var EntryTypegroup ='0';
		var Usuario = $('#patient1 option:selected').val();   //$('#userId').val();
		var MedID =$('#MEDID').val();
		var queUrl ='CreateAttachmentStreamNEW.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
		//var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports=1226';
		$("#attachments").load(queUrl);
		$("#attachments").trigger('update');
		$("#attachments").show();  
	}	
	
	 function addDefaultUser()
	 {
		var select = document.getElementById("patient1");
		select.options[select.options.length] = new Option("--Select User--", 0);
		select.value = "--Select User--";
	 }
	 
	  function addDefaultAgency()
	 {
		var select = document.getElementById("payingAgency");
		select.options[select.options.length] = new Option("--Select Agency--", 0);
		select.value = "--Select Agency--";
	 }
	 function addDefaultCPTProcedure()
	 {
		var select = document.getElementById("cptProcedure");
		select.options[select.options.length] = new Option("--Select CPT Procedure--", 0);
		
		
	 }
	
	function addDefaultICDCode()
	 {
		var select = document.getElementById("icdCodes");
		select.options[select.options.length] = new Option("--Select ICD Code--", 0);
		document.getElementById("icdCodes").value = "--Select ICD Code--";
	 }
	 
	 var selectedIcds = new Array();
		$(document).on('click', '#addICDCode', function(){ 
			var selectedIcd=$("#icdCodes option:selected").text();
			if(selectedIcd=="--Select ICD Code--")
			{
				alert("Please select a valid ICD Code.");
						return;
			}
			selectedIcds =  $("#icdCodesTxt").val().split(',');
			for(var i=0;i<selectedIcds.length;i++)
			{
				if(selectedIcds[i]==selectedIcd)
				{
						alert("ICD Code already added.");
						return;
				}
			}
			selectedIcds.push(selectedIcd);
			console.log(selectedIcds);
			var icdCodesTxt =  $("#icdCodesTxt").val();
			//console.log(icdCodesTxt);
			if(icdCodesTxt=="")
			$("#icdCodesTxt").val(selectedIcd);
			else
			$("#icdCodesTxt").val(icdCodesTxt+','+selectedIcd);
		});
		
		
		
		//This function deletes an event. Takes input from text box "eventid".
	
		
	

		// function minimum(a,b)
		// {
			// if(a==-1)
			// {
				// a=999;
			// }
		
			// if(b==-1)
			// {
				// b=999;
			// }
			// if(a<b)
				// return a;
			// else 
				// return b;
		// }
		
		// function adjust_endtime(input)
		// {
			// var str = input.split(':')[0];
			// return ((parseInt(str)+1)%24+':'+input.split(':')[1]+':00');
		// }

		// function find_time(input)
		// {
			// input = input.toLowerCase();
			// var timezone = 'pm';
			// if(input.toLowerCase().search('pm') != -1)
			// {
				// timezone = 'pm';
			// }
			// else if(input.toLowerCase().search('am') != -1)
			// {
				// timezone = 'am';
			// }
	
	
			// var r = /\d+\.?\d*/g;
		//	var s = input.substring(k);
			// var time = input.match(r);
	

			// var hour;
			// var min='00';
	
			// time = time.toString();
			
			// if(time.indexOf(',') !== -1)
			// {
				// time = time.split(',')[0];
			// }
			
			// if(time.indexOf('.') !== -1)
			// {
	//				alert('here');
				// hour=time.split('.')[0];
		//		alert(hour);
				// min=time.split('.')[1];
			// }
			// else
			// {
				// hour = time;
		
			// }
	
			// if(timezone=='pm' && hour<12)
			// {
				// hour = parseInt(hour) + 12;
			// }
	
			// return( hour + ':' + min+':00');
	

		// }
/*KYLE

	</script>
	<!--<script src="js/bootstrap.min.js"></script>
    <!-- <script src="/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script> -->
	<script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
	<script src="js/bootstrap-modal.js"></script>
	<script src="js/bootstrap-dropdown.js"></script>
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

	
	
</body>
</html>