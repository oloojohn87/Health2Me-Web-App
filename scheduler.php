<?php
session_start();
 require("environment_detail.php");
 require_once("displayExitClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$MEDID = $_SESSION['MEDID'];
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];
if ($Acceso != '23432')
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(1);
die;
}

					// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
  
$result = $con->prepare("SELECT * FROM doctors where id=?");
$result->bindValue(1, $MEDID, PDO::PARAM_INT);
$result->execute();

$count = $result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);
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
$exit_display = new displayExitClass();

$exit_display->displayFunction(2);
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
	<!--<link rel="stylesheet" type="text/css" href="css/base.css" />-->
	<link href='css/jquery-ui.css' rel='stylesheet' />	
	<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
	<script src='js/jquery-1.9.1.js'></script>
	<script src='js/jquery-ui.js'></script>
	<link href='css/fullcalendar.css' rel='stylesheet' />
	<script src='js/jquery-1.9.1.min.js'></script>
	<script src='js/jquery-ui-1.10.2.custom.min.js'></script>
	<script src='js/fullcalendar.min.js'></script>
	<script src="js/jquery-1.9.1-autocomplete.js"></script>
	<script src="js/jquery-ui-autocomplete.js"></script>
	
  <script type="text/javascript" src="js/jquery.timepicker.js"></script>  
  <!--<script type="text/javascript" src="js/base.js"></script> -->
   <script type="text/javascript">
var user = new Array();
var event_sources = new Array();
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
  
  
  var timeoutTime = 300000;  //5minutes
var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


var active_session_timer = 60000; //1minute
var sessionTimer = setTimeout(inform_about_session, active_session_timer);

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


  function open_config()
		{
				window.location.replace("<?php echo $domain;?>/medicalConfiguration.php");
		
		}
	
  
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


function get_random_color() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.round(Math.random() * 15)];
    }
    return color;
}

function user_click_event()
{
	
	var opts = document.getElementById('user_event_config');
	var selectedIndex = document.getElementById('user_event_config').selectedIndex;
	current_user = opts.options[opts.selectedIndex].value;
	//window.event.srcElement.id;
	//alert('Current source = '+current_source);
	//alert(button_id);
	$('#calendar').fullCalendar( 'removeEventSource', event_sources[current_source] );
	$('#calendar').fullCalendar( 'addEventSource', event_sources[current_user] );
	//rrent_source = opts.selectedIndex;
	
	var select = document.getElementById("appointment");
	//var select;
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
	//select.options[select.options.length] = new Option("Non-Working Hours", 0);
}
  
   $(document).ready(function() {
	//Step 0: Get all patients in users groups
	getpatients('getuserpatients.php');
	

	
	//alert(patients.length);
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
	
	//alert(availablePatientTags.length);
	
	//Step 1 : Get all other doctors in users groups 
	getusersingroup('getusersingroup.php?userid=<?php echo $_SESSION['MEDID'];?>');  //fills usr
	
	getgroupevents('get_group_events.php?userid=<?php echo $_SESSION['MEDID'];?>');  //fills type_events
	
	getUserEvent('getusereventconfig.php?userid=<?php echo $_SESSION['MEDID'];?>');  //fills user_event
	
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
		
		
	/*	var element = document.createElement("input");
		element.setAttribute("type","button");
		element.setAttribute("value",usr[i].idmedfixedname);
		element.setAttribute("id",usr[i].id);
		element.setAttribute("name",usr[i].id);
		//element.setAttribute("style.backgroundColor",user[usr[i].iddoctor]);
	
		foo.appendChild(element);
		
		var e = document.getElementById(usr[i].id);
		e.style.background=user[usr[i].id];
		*/
		
	}
	for(var i=0;i<usr.length;i++)
	{
		//alert(usr[i].idmedfixedname);
		var opt = document.createElement("option");
		opt.text = usr[i].idmedfixedname;
        opt.value = usr[i].id;	
        // Add an Option object to Drop Down/List Box
        document.getElementById("user_event_config").options.add(opt);
        // Assign text and value to Option object
     }
	var opts = document.getElementById('user_event_config');
	for(var i=0;i<usr.length;i++)
	{
		if(opts.options[i].value == <?php echo $_SESSION['MEDID']; ?>)
		 opts.selectedIndex = i;
	}
/*	var select = document.getElementById('user_event_config');
	//select.options.length = 0; // clear out existing items
	for(var i=0; i < usr.length; i++) 
	{
		var el = document.createElement('option');
		el.textContent = usr[i].idmedfixedname;
		el.value = usr[i].idmedfixedname;
		el.innerHTML = usr[i].idmedfixedname;
		select.appendChild(el);
	}
*/	
	
/*	var select = document.getElementById("user_event_config");
	var length = select.options.length;
	
	for (var i=select.options.length-1;i>=0;i--) 
	{
		select.options[i] = null;
	}
	for(var i=0;i<length;i++)
	{
		
		select.options[select.options.length] = new Option(usr[i].title, usr[i].id);
			//select.options[i] = new Option(type_events[i].title, type_events[i].id);
	}
*/	
	event_sources['all'] = new Array();
	
	//Step 3 : Get all events of the group
	geteventData('events.php?userid=<?php echo $_SESSION['MEDID'];?>');
	
	//getUserEvent('getusereventconfig.php?userid=<?php echo $_SESSION['MEDID'];?>');
	
	//Step 4 : Create data sources for each user and each 'All' 
		 
	for(var i=0;i<pines.length;i++)
	{
		var flag = false;
		//alert("pine: "+pines[i].title)
		var event = new Object();
		event.id = pines[i].id;
		event.start = pines[i].start; // this should be date object
		event.end = pines[i].end; // this should be date object
	
		
		for(var j=0;j<userEvent.length;j++)
		{
		 //alert("user: "+userEvent[j].title);
		 
		 //alert(pines[i].title + ' Non-Working Hours');
		 if(pines[i].title=='Non-Working Hours')
		 {
			//alert("Non working match");
			event.title = ''; // this should be string
			event.color="#A4A4A4";
			event.allDay = false;
		    event_sources[pines[i].userid].push(event);
			event_sources['all'].push(event);
			flag = true;
			break;
			//event.textColor="#A4A4A4";
	 	 } 
		 else if(pines[i].title==userEvent[j].title)
		 {
			//alert('first' + pats[pines[i].patient] + '  ' + pines[i].id);
			event.title = pats[pines[i].patient]; // this should be string
			event.color = "#"+userEvent[j].colour;
			//alert("pines,user match:  "+event.color);
			event.allDay = false;
			event_sources[pines[i].userid].push(event);
			event_sources['all'].push(event);
			flag = true;
			break;
	 	 }
		
		}
		if(flag==false)
		{
			if(pines[i].title=='Non-Working Hours')
			{
				event.title = ''; // this should be string
				event.color='#A4A4A4';
			}
			else
			{
				event.title = pines[i].title; // this should be string
				event.color = user[pines[i].userid];
			}
			
			event.allDay = false;
			event_sources[pines[i].userid].push(event);
			event_sources['all'].push(event);
			flag = true;
		}
	}
	//alert(event_sources[28].length);
	 

 /* for(var i=0;i<pines.length;i++)
	{
		
		var event = new Object();
		event.id = pines[i].id;
		event.start = pines[i].start; // this should be date object
		event.end = pines[i].end; // this should be date object
		if(pines[i].title=='Non-Working Hours')
		{
			event.title = ''; // this should be string
			event.color="#A4A4A4";
			//event.textColor="#A4A4A4";
		}
		else
		{
			event.title = pines[i].title; // this should be string
			event.color = user[pines[i].userid];
		}
		event.allDay = false;
		
		event_sources[pines[i].userid].push(event);
		event_sources['all'].push(event);
	}	
	*/
	
  var date = new Date();
  var d = date.getDate();
  var m = date.getMonth();
  var y = date.getFullYear();

  var calendar = $('#calendar').fullCalendar({
   editable: true,
   header: {
    left: 'prev,next today',
    center: 'title',
    right: 'month,agendaWeek,agendaDay,list'
   },
   
   
   
   selectable: true,
   selectHelper: true,
   select: function(start, end, allDay) {
		start = $.fullCalendar.formatDate(start, "yyyy-MM-dd HH:mm:TT");
		end = $.fullCalendar.formatDate(end, "yyyy-MM-dd HH:mm:TT");
		
		$('#title').val('');
		$('#datepicker1').val('');
		$('#timepicker1').val('');
		$('#datepicker2').val('');
		$('#timepicker2').val('');
		
		
		$('#datepicker1').val(start.substring(0,10));
		$('#timepicker1').val(start.substring(10));
		$('#datepicker2').val(end.substring(0,10));
		$('#timepicker2').val(end.substring(10));
		calculate_end_time();
		$('#tags').val('');
		var title=$("#appointment option:selected").text();
		if(title=="Non-Working Hours")
		 document.getElementById("tags").disabled = true;
		else
		 document.getElementById("tags").disabled = false; 
		$('#timepicker1').timepicker({ 'timeFormat': 'h:i A' });
		hours = timeObject.getHours();
		minutes = timeObject.getMinutes();
		var seconds = timeObject.getSeconds();
		var timeOfDay;
		if(hours<10)
				{
					hours='0'+hours;
					timeOfDay = 'AM';
				}
				else if(hours>=10 && hours<12)
				{
					timeOfDay = 'AM';
				}
				else if(hours==12)
				{
					timeOfDay = 'PM';	
				}
				else if(hours>12)
				{
					hours='0'+(hours-12);
					timeOfDay = 'PM';	
				}
				if(minutes<10)
					minutes='0'+minutes;
				if(seconds<10)
					seconds='0'+seconds;
		$('#timepicker1').val(hours+':'+minutes+' '+timeOfDay);
		$('#BotonModal').trigger('click');
		
	
   },
   
   editable: true,
   eventDrop: function(event, delta) {
   start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
   end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
   $.ajax({
   url: 'update_events.php',
   data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,
   type: "POST",
   success: function(json) {
	/*if(json=='success')
	{
	
	}
	else
	{
		var str = 'Events coincides with the following events : \n';
						//alert(n);
						for(var j=0;j<json.length;j++)
						{
							if(json.charAt(j)=='$')
							{
								str+='\n';
							}
							else
							{
								str+=json.charAt(j);
							}
							//str = n[j] + '\n';
						}
						alert(str);
	}*/
   
    //alert("Updated Successfully");
	//$('#Refresh').trigger('click');
   }
   });
   },
   eventResize: function(event) {
   start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
   end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
   $.ajax({
    url: 'update_events.php',
    data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,
    type: "POST",
    success: function(json) {
     //alert("Updated Successfully");
	 //$('#Refresh').trigger('click');
    }
   });

},
eventClick : function(calEvent,jsEvent,view)
{
	//alert('Event:'+calEvent.title+' '+calEvent.id);
	$('#eventid').val(calEvent.id);
	$('#BotonModal1').trigger('click');
},

eventRender:function(event,element){
if(event.title=="")
{
 element.find('.fc-event-time').hide();
 //element.find('.fc-event-title').hide();
 }
},

eventMouseover: function(event, jsEvent, view) {
    //$('.fc-event-inner', this).append('<div id=\"'+event.id+'\" class=\"hover-end\">'+$.fullCalendar.formatDate(event.end, 'h:mmt')+'</div>');
	//alert(event.title);
},

eventMouseout: function(event, jsEvent, view) {
    //$('#'+event.id).remove();
}
   
   
   
  });
  //alert(current_source);
  
	$('#calendar').fullCalendar( 'addEventSource', event_sources[current_source] );  
//alert('changed source');	
	
	var select = document.getElementById("appointment");
	var length = select.options.length;
	
	for (var i=select.options.length-1;i>=0;i--) 
	{
		select.options[i] = null;
	}
	
	
	for(var i=0;i<type_events.length;i++)
	{
		if(type_events[i].userid==current_source)
		{
			select.options[select.options.length] = new Option(type_events[i].title, type_events[i].id);
		}
		
	}
	//select.options[select.options.length] = new Option("Non-Working Hours", 0);
	//alert('added appointments');
/*	
	var select = document.getElementById("user_event_config");
	var length = select.options.length;
	
	for (var i=select.options.length-1;i>=0;i--) 
	{
		select.options[i] = null;
	}
	for(var i=0;i<select.options.length;i++)
	{
		
		select.options[select.options.length] = new Option(userEvent[i].idmedfixedname, i);
			//select.options[i] = new Option(type_events[i].title, type_events[i].id);
	}
	*/
	
 });
 

</script>
</head>

<body style="background: #F9F9F9;">

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
        <a href="index.html" class="logo"><h1>Health2me</h1></a>
           
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
						<li>
						<?php if ($privilege==1)
						echo '<a href="dashboard.php">';
					   else if($privilege==2)
						echo '<a href="patients.php">';
						?>
						<i class="icon-globe"></i> Home</a></li>
						<li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li>
						<li><a href="logout.php"><i class="icon-off"></i> Sign Out</a></li>
					</ul>
				</div>
          </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->
    
    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">
    
    	    
		<!--SpeedBar Start--->
		<div class="speedbar">
			<div class="speedbar-content">
				<ul class="menu-speedbar">
					<?php if ($privilege==1)
						echo '<li><a href="dashboard.php" >Dashboard</a></li>';
				    ?>
					<li><a href="patients.php" class="act_link">Patients</a></li>
					<li><a href="medicalConnections.php" >Doctor Connections</a></li>
					<?php if ($privilege==1)
						echo '<li><a href="PatientNetwork.php" >Patient Network</a></li>';
					?>
					<li><a href="medicalConfiguration.php">Configuration</a></li>
					<li><a href="logout.php" style="color:yellow;">Sign Out</a></li>
				</ul>
			</div>
		</div>
     <!--SpeedBar END-->
     
     
     
		<!--CONTENT MAIN START-->
		<div class="content">
			<!-- Pop up(Add Event) Start-->
			<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
				<div id="header-modal" class="modal hide" style="display: none; height:490px; width:400px; margin-left:-200px;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">×</button>
					<div id="InfB" >
	                 	<h4>Event Details</h4>
					</div>
        
					<div id="InfoIDPacienteB" style="float:left; margin-top:-30px; margin-left:70%;">
		
					</div>
				</div>
         
	<!--			<div class="modal-body" id="ContenidoModal" style="height:320px;">
	<!--				<div style="margin:20px;height:4px;">Title : </div>
					<div style="margin:20px;"><input type="text" name="title" id="title" style="font-size:14px;"></div>	-->
	<!--				<div>
					<div style="margin:20px;height:4px;">Select Patient : </div><br/>	
					<div style="margin:20px;height:4px;"><input id="tags"  /></div><br/>
					</div>	
					<div style="margin-top=5px">
					<div style="margin:20px;height:4px;">Type:</div><br/>
					<div style="margin:20px;height:4px"><select id="appointment" style="margin:20px;"></div><br/>
					</div>
					<div style="margin-top=5px">
					<div style="margin:20px;height:4px">Start Date: </div><br/>
					<div style="margin:20px;height:4px;"><input type="text" id="datepicker1"  /></div><br/>
					</div>
					<div style="margin-top=5px">
					<div style="margin:20px;height:4px;">End Date: </div><br/>
					<div style="margin:20px;height:4px;"><input type="text" id="datepicker2"/></div><br/>
					</div>
					<div style="margin-top=5px">
					<div style="margin:20px;height:4px;">Start Time: </div><br/>
					<div style="margin:20px;height:4px;"><input type="text" id="timepicker1" ></div><br/>	
					</div>
					<div style="margin-top=5px">
					<div style="margin:20px;height:4px;">End Time: </div><br/>
					<div style="margin:20px;height:4px;"><input type="text" id="timepicker2"/></div> <br/>
					</div>
					<div class="clear"></div>  
          
				</div>																				-->										
		-	<div class="modal-body" id="ContenidoModal" style="height:320px;">
	<!--				<div style="margin:20px;height:4px;">Title : </div>
					<div style="margin:20px;"><input type="text" name="title" id="title" style="font-size:14px;"></div>	-->
					<table style="background:transparent; height:300px;" >
					<tr>
					<td style="height:24px;">Select Patient : </td>
					<td style="height:24px;"><input id="tags"  /></td>
					</tr><tr></tr>
			<!--		<tr style="background-colour:white height:10px">  -->
					<tr style="height:24px;"><td>Type:</td>
					<td style="height:24px; ">  
					<select id="appointment" style="height:25px">
					</td> 
					</tr><tr></tr>
					<tr >
					<td style="height:24px">Start Date: </td>
					<td style="height:24px;"><input type="text" id="datepicker1"  /></td>
					</tr><tr></tr>
					<tr>
					<tr>
					<td style="height:24px;">End Date: </td>
					<td style="height:24px;"><input type="text" id="datepicker2"/></td>
					</tr><tr></tr>
					<tr >
					<td style="height:24px;">Start Time: </td>
					<td style="height:24px;"><input type="text" id="timepicker1" ></td>
					</tr><tr></tr>
					
					<td style="height:24px;">End Time: </td>
					<td style="height:24px;"><input type="text" id="timepicker2"/></td> 
					</tr><tr></tr>
					</table>
					<div class="clear"></div>  
					
				</div>
				<div class="modal-footer">
					<!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
					<a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Update Data</a>
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
				</div>
			</div>  
			
			
			<!--Pop up(Add event) End-->
			
			
			<!--Pop Up For Event Click-->
			<button id="BotonModal1" data-target="#header-modal0" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button> 
				<div id="header-modal0" class="modal fade hide" style="display: none;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">×</button>
						Choose Action
				</div>
				<div class="modal-body">
					<input type="button" class="btn btn-danger" data-dismiss="modal" value="Delete" style="" id="Delete">
			<!--		<input type="button" class="btn btn-primary" data-dismiss="modal" value="Edit" style="" id="Edit"> -->
				</div>
					
				<div class="modal-footer">
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModallink">Close</a>
				</div>
				</div>  
			<!--- Pop Up Event Click End  ---> 	
	 
			<!--Pop Up Configuration-->
			<button id="BotonModal2" data-target="#header-modal2" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button> 
				<div id="header-modal2" class="modal fade hide" style="display: none;" aria-hidden="true">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">×</button>
						Scheduler Configuration
				</div>
				<div class="modal-body">
					<table>
						<tr>
							<td>Monday :  </td>
							<td><input type = "text" id="timepickerMon">
							<td>  To  </td>
							<td><input type = "text" id="timepickerMonTo">
						</tr>
						<tr>
							<td>Tuesday :  </td>
							<td><input type = "text" id="timepickerTue">
							<td>  To  </td>
							<td><input type = "text" id="timepickerTueTo">
						</tr>
						<tr>
							<td>Wednesday :  </td>
							<td><input type = "text" id="timepickerWed">
							<td>  To  </td>
							<td><input type = "text" id="timepickerWedTo">
						</tr>
						<tr>
							<td>Thursday :  </td>
							<td><input type = "text" id="timepickerThu">
							<td>  To  </td>
							<td><input type = "text" id="timepickerThuTo">
						</tr>
						<tr>
							<td>Friday :  </td>
							<td><input type = "text" id="timepickerFri">
							<td>  To  </td>
							<td><input type = "text" id="timepickerFriTo">
						</tr>
						<tr>
							<td>Saturday :  </td>
							<td><input type = "text" id="timepickerSat">
							<td>  To  </td>
							<td><input type = "text" id="timepickerSatTo">
						</tr>
						<tr>
							<td>Sunday :  </td>
							<td><input type = "text" id="timepickerSun">
							<td>  To  </td>
							<td><input type = "text" id="timepickerSunTo">
						</tr>
					</table>
				</div>
					
				<div class="modal-footer">
					<a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModallink">Close</a>
				</div>
				</div>  
			<!--Pop Up End Configuration-->
	 
			<div class="grid" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">
				<span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;">Scheduling Calendar</span>     
				<div style="margin:15px; margin-top:5px;">
					<!--<div class="row-fluid" style="height:1000px; width:1000px; margin:0 auto;">	--->
						<div class="grid" style="padding:10px;  margin-left:10px;margin-right:40px">
					<!--		<input type="button" class="btn btn-primary" value="Quick Add" style="margin-left:50px;" id="QuickAdd"> -->
							Select User:<select style="margin-left:70px" id="user_event_config" onChange="user_click_event();" ></select>
							<a href="medicalConfiguration.php"><input type="button" class="btn btn-primary" value="Configuration" style="margin-left:220px;" id="Configuration" ></a>
							
					<!--		<input type="button" class="btn btn-primary" value="Print" style="margin-left:0px;" id="Print"> -->
							
							<div  style="width:100%; margin-bottom:10px;"></div>

					<!--		<input type="text" style = "margin-left:50px; width:100px;" name="quickadd" id="quickadd">
							<input type="button" value = "Add" onClick = "quickAdd();" style="margin-left:10px;"> -->
						</div>
					<!--</div>--->
					
					<div class="row-fluid" style="width:900px;height=900px margin: 0 auto; text-align:center;">	            
						<div class="grid" style="margin: 0 auto; margin-top:30px; text-align:center;">
							     
							<div id='calendar' style="width=870px;height=800px; margin:0 auto;"></div>
							<span id="fooBar" onClick="user_click_event();">&nbsp;</span>
						</div>
					</div>
				
				
			</div>	
		</div>
	</div>
	
	<script type="text/javascript" >
	
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
	

	
		$("#Configuration").click(function(event) {
			//alert('Clicked Config');
			//$('#BotonModal2').trigger('click');
			//header( 'Location: scheduler_config.php' ) ;
			window.location.replace("<?php echo $domain;?>/scheduler_config.php");
		});
		
		
		
		/*$(function() {
		
			//$( "#datepicker1" ).datepicker({z-index:-1});
			/*$('#datepicker1').datepicker({
				inline: true,
				nextText: '&rarr;',
				prevText: '&larr;',
				showOtherMonths: true,
				dateFormat: 'mm-dd-yy',
				dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
				showOn: "button",
				buttonImage: "images/calendar-blue.png",
				buttonImageOnly: true,
			});
			//$( "#datepicker1" ).datepicker();
			//$( "#datepicker2" ).datepicker();
		});*/
		$('#datepicker1').datepicker({
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
		$('#datepicker2').datepicker({
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
		var timeObject = new Date('2013-08-08 '+$('#timepicker1').val() );                
		var hours;
		var minutes;
		$('#timepicker1').timepicker({ 'timeFormat': 'h:i A' });
		$('#timepicker2').timepicker({ 'timeFormat': 'h:i A' });
		
		$('#timepickerMon').timepicker({ 'timeFormat': 'h:i:A' });
		$('#timepickerMonTo').timepicker({ 'timeFormat': 'h:i:A' });
		$('#timepickerTue').timepicker({ 'timeFormat': 'h:i:A' });
		$('#timepickerTueTo').timepicker({ 'timeFormat': 'h:i:A' });
		$('#timepickerWed').timepicker({ 'timeFormat': 'h:i:A' });
		$('#timepickerWedTo').timepicker({ 'timeFormat': 'h:i:A' });
		$('#timepickerThu').timepicker({ 'timeFormat': 'h:i:A' });
		$('#timepickerThuTo').timepicker({ 'timeFormat': 'h:i:A' });
		$('#timepickerFri').timepicker({ 'timeFormat': 'h:i:A' });
		$('#timepickerFriTo').timepicker({ 'timeFormat': 'h:i:A' });
		$('#timepickerSat').timepicker({ 'timeFormat': 'h:i:A' });
		$('#timepickerSatTo').timepicker({ 'timeFormat': 'h:i:A' });
		$('#timepickerSun').timepicker({ 'timeFormat': 'h:i:A' });
		$('#timepickerSunTo').timepicker({ 'timeFormat':'h:i:A' });
		
		
		//This is a function used to add events . It takes input as text boxes on the popup. So fill data in these textboxes before you call this function
		//$('#GrabaDatos').live('click',function() {
		
		$(document).on('click', '#GrabaDatos', function(){ 
			//alert('Here');
			
			//Write code for validations here(Pending..)
			
			
			//alert("here");
			//var title;
			//var title = $("#appointment").text();//change to type
			var title=$("#appointment option:selected").text();
			//alert(title);
			//return;
			var patient_id;
			if(title=='Non-Working Hours')
			{
				patient_id=-1;
			}
			else
			{
				var patient_name = $('#tags').val();
				if(patient_name=="")
				{
					patient_id=-1;
				}
				else
				{
					var index = pats.indexOf(patient_name);
					if(index==-1)
					{
						alert("Select valid patient");
						return;
					}
					else
					{
						patient_id = index;
					}
				}
			
			}
			
			var start = $("#datepicker1").val() + ' ' + $("#timepicker1").val();	
			//alert(start);
			var end = $("#datepicker2").val() + ' ' + $("#timepicker2").val();
			var userid = current_user;//get current user
			var allDay=false;
		//	alert(title);
			if (title=="")
			{
				alert("Enter Title for Event");
				return;
			}
			//alert(title + start+end);
			
			
			
			$.ajax({
				//url: 'add_events.php',
				url: 'add_events2.php',
				
				data: 'title='+ title+'&start='+ start+'&end='+ end+'&patient='+patient_id+' &userid='+userid,
				
				type: "GET",
				success: function(json) {
					if(json=="success")
					{
						
						//alert('Added Successfully');
						var clr='FFFFFF';
						for(var j=0;j<userEvent.length;j++)
						{
							if(userEvent[j].userid==userid && userEvent[j].title==title)
							{
								clr=userEvent[j].colour;
							}
						}
						
						var event = new Object();
						if(patient_id==-1)
						{
							event.title='' ;
							event.color = '#A4A4A4';
						}
						else
						{
							event.title=pats[patient_id]; 
							event.color=clr;
						}
						
						
						var Rectipo = LanzaAjax('get_last_event.php');
						//alert(Rectipo);
						
						event.id = parseInt(Rectipo);
						title = pats[patient_id];
						event.start=start;
						event.end=end;
						event.allDay = allDay;
						//event.allday=false;
						event_sources[userid].push(event);
						user_click_event();
						/*$('#calendar').fullCalendar('renderEvent',
						{
							title: title,
							start: start,
							end: end,
							allDay: allDay,
							month:'',
							week:'',
							day:'',
							color :"#"+clr
						},
						true // make the event "stick"
						);
						*/
						
						
					}	
					else
					{
						//alert(json);
						//var n = json.split('$');
						var str = 'Events coincides with the following events : \n';
						//alert(n);
						for(var j=0;j<json.length;j++)
						{
							if(json.charAt(j)=='$')
							{
								str+='\n';
							}
							else
							{
								str+=json.charAt(j);
							}
							//str = n[j] + '\n';
						}
						alert(str);
						//return;
					}
				}
				
			});
			
			/*$('#calendar').fullCalendar('renderEvent',
			{
				title: title,
				start: start,
				end: end,
				allDay: allDay
			},
			true // make the event "stick"
			);*/
		
		$('#calendar').fullCalendar('unselect');
		//$('#Refresh').trigger('click');
		});
		
		
		
		//This function deletes an event. Takes input from text box "eventid".
		$(document).on('click', '#Delete', function(){ 
			var eventid = $('#eventid').val();
			$('#calendar').fullCalendar('removeEvents' , eventid);
			$.ajax({
				url: 'delete_events.php',
				data: 'id='+ eventid ,
				type: "POST",
				success: function(json) {
					//alert("Deleted Successfully");
	 
				}
			});
		});
		
		$(document).on('click', '#Edit', function(){ 
			alert("Work in progress");
		});
		/*
		$('#appointment').blur(function() {
			calculate_end_time();
		});*/
		
		
		
		function calculate_end_time()
		{
			var e1 = document.getElementById("appointment");
			var title=$("#appointment option:selected").text();
			if(title=="Non-Working Hours")
			{
				$('#timepicker2').val('00:00:00');
				return;
			}
			if(e1.options.length > 0)
			{
				var timeObject = new Date('2013-08-08 '+$('#timepicker1').val() );                
				var hours;
				var minutes;
				for(var i=0;i<type_events.length;i++)
				{
					if(type_events[i].id==parseInt(e1.options[e1.selectedIndex].value))
					{
						hours = type_events[i].hours;
						minutes = type_events[i].minutes;
					}
				}
				//timeObject.setHours(timeObject.getHours() + hours);
				//timeObject.setMinutes(timeObject.getMinutes() + minutes);
				timeObject.setTime(timeObject.getTime() + hours*3600000 + minutes*60000);
				//alert(timeObject);
				
				hours = timeObject.getHours();
				minutes = timeObject.getMinutes();
				var seconds = timeObject.getSeconds();
				var timeOfDay;
				if(hours<10)
				{
					hours='0'+hours;
					timeOfDay = 'AM';
				}
				else if(hours>=10 && hours<12)
				{
					timeOfDay = 'AM';
				}
				else if(hours==12)
				{
					timeOfDay = 'PM';	
				}
				else if(hours>12)
				{
					hours='0'+(hours-12);
					timeOfDay = 'PM';	
				}
				if(minutes<10)
					minutes='0'+minutes;
				if(seconds<10)
					seconds='0'+seconds;
					
				
				$('#timepicker2').val(hours+':'+minutes+' '+timeOfDay);
			}
			
		}
		
		$('#appointment').change(function() {
			//alert('changed');
			var title=$("#appointment option:selected").text();
			if(title=="Non-Working Hours")
			 document.getElementById("tags").disabled = true;
			else
			 document.getElementById("tags").disabled = false;
			calculate_end_time();
			//alert('app change');
		}); 
		
		$('#timepicker1').change(function() {
			
			calculate_end_time();
			//alert('timepicker change');
		});
		
	/*	function quickAdd()
		{
			var element = document.getElementById('quickadd');	
			var input = element.value;
			//alert(input);
	
	
			//Find todays date
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1; //January is 0!
			var yyyy = today.getFullYear();
			if(dd<10)
			{
				dd='0'+dd
			} 
			if(mm<10)
			{
				mm='0'+mm
			} 
			today = yyyy+'-'+mm+'-'+dd;
	
	
			var start = today;
			var end = today;
			var n;
			if(input.toLowerCase().search('today') != -1)
			{
				start = today;
				end = today;
		
				//alert("Detected today");
			}
			else if(input.toLowerCase().search('tomorrow') != -1)
			{
				var today1 = new Date(); 
				var tomorrow = new Date(today1.getTime() + 24 * 60 * 60 * 1000);
				var dd1 = tomorrow.getDate();
				var mm1 = tomorrow.getMonth()+1; //January is 0!

				var yyyy1 = tomorrow.getFullYear();
	
				if(dd1<10)
				{
					dd1='0'+dd1
				} 
	
				if(mm1<10)
				{
					mm1='0'+mm1
				} 
				//today = mm+'/'+dd+'/'+yyyy;
				tomorrow = yyyy1+'-'+mm1+'-'+dd1;
				//alert(tomorrow);
				start=tomorrow;
				end=tomorrow;
			}
			else if((n= input.toLowerCase().search('on')) != -1)
			{
				var month;
				var inp = input.toLowerCase();
				if(inp.search('jan')!=-1 || inp.search('january')!=-1 )
				{
					month=1;
				}
				else if(inp.search('feb')!=-1 || inp.search('february')!=-1 )
				{
					month=2;
				}
				else if(inp.search('mar')!=-1 || inp.search('march')!=-1 )
				{
					month=3;
				}
				else if(inp.search('apr')!=-1 || inp.search('april')!=-1 )
				{
					month=4;
				}
				else if(inp.search('may')!=-1)
				{
					month=5;
				}
				else if(inp.search('jun')!=-1 || inp.search('june')!=-1 )
				{
					month=6;
				}
				else if(inp.search('jul')!=-1 || inp.search('july')!=-1 )
				{
					month=7;
				}
				else if(inp.search('aug')!=-1 || inp.search('august')!=-1 )
				{
					month=8;
				}
				else if(inp.search('sep')!=-1 || inp.search('september')!=-1 )
				{
					month=9;
				}
				else if(inp.search('oct')!=-1 || inp.search('october')!=-1 )
				{
					month=10;
				}
				else if(inp.search('nov')!=-1 || inp.search('november')!=-1 )
				{
					month=11;
				}
				else if(inp.search('dec')!=-1 || inp.search('december')!=-1 )
				{
					month=12;
				}
				else
				{
					month = mm;
				}
				//alert(month);
		
				var r = /\d+/;
				var s = inp.substring(n);
				var day = s.match(r);
		
				day=day.toString();
				if(day.indexOf(',')!==-1)
				{
					day=day.split(',')[0];
				}
			
				start = yyyy+'-'+month+'-'+day;
				end=start;
				//alert('Date Found : '+start);
		
			}
	
			var start_time = "12:00 AM";
			var end_time = "12:30 AM";
			//var k = input.search('at');
			if((k = input.search('at'))!==-1)
			{
				var time = find_time(input.substring(k));
				//alert(time);
				start_time = time;
				end_time = adjust_endtime(time);
			}
			else if((k = input.search('from'))!==-1)
			{
				var temp = input.substring(k);
				if(temp.search('to')==-1)
				{
					var time = find_time(input.substring(k));
					start_time = time;
					end_time = '24:00:00';
				}
				else
				{
			
					var h = temp.search('to');
					//alert(input.substring(k,k+h));
					start_time = find_time(input.substring(k,k+h));
					end_time = find_time(input.substring(k+h));
				}
			}
			//alert(start_time);
			//alert(end_time);
	
	
			var hr;
	
			hr = input.toLowerCase().search('tomorrow');
			if(hr==-1)
			{
				min=999;
			}
			else 
			{
				min=hr;
			}
	
			min = minimum(min,input.toLowerCase().search('today'));
			min = minimum(min,input.toLowerCase().search('on'));
			min = minimum(min,input.toLowerCase().search('at'));
			min = minimum(min,input.toLowerCase().search('from'));
	
			var title = input.substring(0,min);
	
			alert (title + '   ' + start + ' ' + start_time + '     ' + end + ' ' + end_time);
			$("#title").val(title);
			$("#datepicker1").val(start);
			$("#timepicker1").val(start_time);
			$("#datepicker2").val(end);
			$("#timepicker2").val(end_time);
			$('#GrabaDatos').trigger('click');
	
	
	
	
		}
*/

		function minimum(a,b)
		{
			if(a==-1)
			{
				a=999;
			}
		
			if(b==-1)
			{
				b=999;
			}
			if(a<b)
				return a;
			else 
				return b;
		}
		
		function adjust_endtime(input)
		{
			var str = input.split(':')[0];
			return ((parseInt(str)+1)%24+':'+input.split(':')[1]+':00');
		}

		function find_time(input)
		{
			input = input.toLowerCase();
			var timezone = 'pm';
			if(input.toLowerCase().search('pm') != -1)
			{
				timezone = 'pm';
			}
			else if(input.toLowerCase().search('am') != -1)
			{
				timezone = 'am';
			}
	
	
			var r = /\d+\.?\d*/g;
			//var s = input.substring(k);
			var time = input.match(r);
	

			var hour;
			var min='00';
	
			time = time.toString();
			
			if(time.indexOf(',') !== -1)
			{
				time = time.split(',')[0];
			}
			
			if(time.indexOf('.') !== -1)
			{
				//	alert('here');
				hour=time.split('.')[0];
				//alert(hour);
				min=time.split('.')[1];
			}
			else
			{
				hour = time;
		
			}
	
			if(timezone=='pm' && hour<12)
			{
				hour = parseInt(hour) + 12;
			}
	
			return( hour + ':' + min+':00');
	

		}

	 		
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