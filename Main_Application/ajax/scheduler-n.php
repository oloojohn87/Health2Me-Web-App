<?php
session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 $current_source = $_GET['curr_source'];
 
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
$privilege=$_SESSION['Previlege'];
if ($Acceso != '23432')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
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

var current_user=<?php echo $current_source; ?>;
var availablePatientTags = new Array();
var current_source = <?php echo $current_source; ?>;
var colours = ["red","green","blue","orange"];
var user_event = document.getElementById('user_event');
var update_flag=false;


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

function geteventdetails(serviceURL) 
{
   $.ajax(
   {
       url: serviceURL,
       dataType: "json",
       async: false,
       success: function(data)
       {
			//alert('Data Fetched');
           event_data = data.items;
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
	window.location='scheduler-n.php?curr_source='+current_user;
	
	/*window.event.srcElement.id;
	//alert('Current source = '+current_source);
	//alert(button_id);
	alert('Removing ' + current_source);
	$('#calendar').fullCalendar( 'removeEventSource', event_sources[current_source] );
	alert('Setting ' + current_user + '   ' + event_sources[current_user].length);
	$('#calendar').fullCalendar( 'addEventSource', event_sources[current_user] );
	//rrent_source = opts.selectedIndex;
	current_source = current_user;
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
		
	}*/
	//select.options[select.options.length] = new Option("Non-Working Hours", 0);
}
  
  
  
   $(document).ready(function() {
	
	
	//Step 0: Get all patients in users groups
	getpatients('getuserpatients.php');
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
	//alert(usr.length);
	getgroupevents('get_group_events.php?userid=<?php echo $_SESSION['MEDID'];?>');  //fills type_events
	//alert(type_events.length);
	getUserEvent('getusereventconfig.php?userid=<?php echo $_SESSION['MEDID'];?>');  //fills userEvent
	//alert(userEvent.length);
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
        document.getElementById("user_event_config").options.add(opt);
        // Assign text and value to Option object
     }
	var opts = document.getElementById('user_event_config');
	for(var i=0;i<usr.length;i++)
	{
		if(opts.options[i].value == <?php echo $current_source; ?>)
		 opts.selectedIndex = i;
	}
	
	
	
	var select = document.getElementById("appointment");
		
	
	for(var i=0;i<type_events.length;i++)
	{
		if(type_events[i].userid==current_source)
		{
			select.options[select.options.length] = new Option(type_events[i].title, type_events[i].id);
		}
		
	}
	
	
	event_sources['all'] = new Array();
	//refresh_data();
	
	//Step 3 : Get all events of the group
	geteventData('events.php?userid=<?php echo $_SESSION['MEDID'];?>');
	
	//Step 4 : Create data sources for each user and each 'All' 
		 
	for(var i=0;i<pines.length;i++)
	{
		var flag = false;
		var event = new Object();
		event.id = pines[i].id;
		event.start = pines[i].start; // this should be date object
		event.end = pines[i].end; // this should be date object
	
		
		for(var j=0;j<userEvent.length;j++)
		{
		 
		 
		 //alert(pines[i].title + ' Non-Working Hours');
		/* if(pines[i].title=='Non-Working Hours')
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
	 	 }*/ 
		 
		 if(pines[i].title==userEvent[j].title)
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
				event.title = pats[pines[i].patient]; // this should be string
				event.color = '#A4A4A4';
			}
			
			event.allDay = false;
			event_sources[pines[i].userid].push(event);
			event_sources['all'].push(event);
			flag = true;
		}
	}
	
	
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
   
		update_flag=false;
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
		//$('#timepicker1').val(hours+':'+minutes+' '+timeOfDay);
		$('#timepicker1').val('08:00 AM');
		calculate_end_time();
		$('#BotonModal').trigger('click');
		
	
   },
   
   editable: true,
   
   eventDrop: function(event, delta) {
   start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
   end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
   $.ajax({
   url: 'update_events.php',
   data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id +'&userid='+current_user,
   type: "GET",
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
   
   //alert('sending' + event.id + '  ' + start + '  ' + end);
   $.ajax({
    url: 'update_events.php',
    data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id+'&userid='+current_user,
    type: "GET",
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
	$('#eventtitle').val(calEvent.title);
	//alert(calEvent.id + '   ' + calEvent.title + '   ' + calEvent.start + '   ' + calEvent.end);
	$('#BotonModal1').trigger('click');
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
		<input type="hidden" id="eventtitle">

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
					<!--<tr>-->
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
					<!--</tr>--><tr></tr>
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
					<input type="button" class="btn btn-primary" data-dismiss="modal" value="Edit" style="" id="Edit"> 
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
							<!--<a href="medicalConfiguration.php"><input type="button" class="btn btn-primary" value="Configuration" style="margin-left:220px;" id="Configuration" ></a>-->
							
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
			
			/*if(update_flag==true)
			{
				$('#Delete').trigger('click');
			}
			*/
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
					alert("Select valid patient");
					return;
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
			var eve_id=0;
			if(update_flag==true)
			{
				eve_id = $('#eventid').val();
			}
			
			//alert('title='+ title+'&start='+ start+'&end='+ end+'&patient='+patient_id+' &userid='+userid+'&update_flag='+update_flag + '&event_id='+eve_id);
			//alert('flag='+update_flag);
			$.ajax({
				//url: 'add_events.php',
				url: 'add_events2.php',
				
				data: 'title='+ title+'&start='+ start+'&end='+ end+'&patient='+patient_id+' &userid='+userid+'&update_flag='+update_flag + '&event_id='+eve_id,
				
				type: "GET",
				success: function(json) {
					//alert(json)
					if(json=="success")
					{
						if(update_flag==true)
						{
							//alert('Triggering delete');
							$('#Delete').trigger('click');
						}
						else
						{
							user_click_event();
						}
						/*
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
						//alert('new event has id '+Rectipo);
						
						event.id = parseInt(Rectipo);
						title = pats[patient_id];
						event.start=start;
						event.end=end;
						event.allDay = allDay;
						//event.allday=false;
						//alert(event_sources[userid].length);
						event_sources[userid].push(event);
						//alert(event_sources[userid].length);
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
			//alert('Deleting' + eventid);
			$('#calendar').fullCalendar('removeEvents' , eventid);
			$.ajax({
				url: 'delete_events.php',
				data: 'id='+ eventid ,
				type: "GET",
				success: function(json) {
					//alert(json);
					user_click_event();
					//alert('Refreshing');
					/*var index;
					alert(event_sources[current_source].length);
					for(var i=0;i<event_sources[current_source].length;i++)
					{
						var event = event_sources[current_source][i];
						if(event.id == eventid)
						{
							index=i;
							break;
							//alert('deleted' + eventid);
						}
					}
					event_sources[current_source].splice(index, 1);
					alert('Refreshed');
					alert(event_sources[current_source].length);
					//window.location.reload(false);
					//$('#calendar').fullCalendar( 'refetchEvents' );
					$('#calendar').fullCalendar( 'removeEventSource', event_sources[current_source] );
					alert('removing source');
					$('#calendar').fullCalendar( 'addEventSource', event_sources[current_source-1] );
					alert('adding source');*/
					//user_click_event();
					//alert("Deleted Successfully");
	 
				}
			});
		});
		
		$(document).on('click', '#Edit', function(){ 
			//alert("Work in progress");
			update_flag=true;
			var eventid = $('#eventid').val();
			geteventdetails("get_event_details.php?id="+eventid);
			$('#tags').val($('#eventtitle').val());
			
			var select=document.getElementById('appointment');
			for(var i=0;i<select.options.length;i++)
			{
				if(select.options[i].text == event_data[0].title)
				{
					select.options[i].setAttribute('selected',true);
				}
			}
			//tz.options[parseInt(user_timezone[0].timez)-1].setAttribute('selected',true);
			//$('#appointment :selected').text(event_data[0].title);
			$('#datepicker1').val(event_data[0].start.substring(0,10));
			$('#datepicker2').val(event_data[0].end.substring(0,10));
			
			var timeObject = new Date(event_data[0].start);		
			var hours = timeObject.getHours();
			var minutes = timeObject.getMinutes();
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
			
			var timeObject1 = new Date(event_data[0].end);		
			var hours1 = timeObject1.getHours();
			var minutes1 = timeObject1.getMinutes();
			var seconds1 = timeObject1.getSeconds();
			var timeOfDay1;
				if(hours1<10)
				{
					hours1='0'+hours1;
					timeOfDay1 = 'AM';
				}
				else if(hours1>=10 && hours1<12)
				{
					timeOfDay1 = 'AM';
				}
				else if(hours1==12)
				{
					timeOfDay1 = 'PM';	
				}
				else if(hours1>12)
				{
					hours1='0'+(hours1-12);
					timeOfDay1 = 'PM';	
				}
				if(minutes1<10)
					minutes1='0'+minutes1;
				if(seconds<10)
					seconds1='0'+seconds1;
					
				
			$('#timepicker2').val(hours1+':'+minutes1+' '+timeOfDay1);
			
			
			
			
			//calculate_end_time();
			$('#BotonModal').trigger('click');
			
			//alert(event_data[0].title + '  ' + event_data[0].start);
			
			
			
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