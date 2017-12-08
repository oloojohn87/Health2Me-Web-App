   
$( document ).ready(function() {  
	
	$(".clicker").click(function() {
  		alert( "Handler for .click() called." );
	});
	
	//get local storage data
	var temp = localStorage.getItem('mydata');
    var domain = '';
    var user = '';
    var num_reports = '';
        
    if(temp != null) {
		
            var dataJSON = JSON.parse(temp);
            domain = dataJSON.domain;
            user = dataJSON.user;
		    firstname = dataJSON.firstname;
			surname = dataJSON.surname;
			family = dataJSON.family;
			name = firstname +" "+ surname;
		    console.log("Name" + name);
    }
    
    h2m_header();
    h2m_footer();
    function h2m_header() {

        var js = document.createElement("script");

        js.type = "text/javascript";
        js.src = 'js/responsivemobilemenu.js';

        document.body.appendChild(js);
		$("#header").replaceWith("\
			<link rel='stylesheet' href='css/responsivemobilemenu.css' type='text/css'>\
    		<link rel='stylesheet' type='text/css' href='css/h2m.css'>\
    		<link rel='stylesheet' href='css/font-awesome-4.1.0/css/font-awesome.min.css'>\
			<div class='rmm'>   \
                <ul>\
                    <li><a href='userdashboard.html'>Dashboard</a></li>\
                    <li><a href='talk.html'>Talk</a></li>\
                    <li><a href='request.html'>Request</a></li>\
                    <li><a href='upload.html'>Upload</a></li>\
                    <li><a href='send.html'>Send</a></li>\
                    <li><a href='records.html'>History</a></li>\
                    <li><a href='settings.html'>Settings</a></li>\
                </ul>\
            </div>\
			<div class='h2mnav'>\
            </div>    \
		<!-- Clickable Nav -->\
		<div class='click-nav'>\
			<ul class='no-js'>\
				<li>\
					<a class='clicker'>&#xf007; "+name+" &#xf0d7;</a> \
					</li>\
				"+h2m_settings()+
			"</ul>\
		</div>\
			<div class='secondnav'>\
				<span id='notifications'><i class='fa fa-envelope-o'></i></span>\
            </div>\
			");
    }

   function h2m_settings() {
	    dropdown = "";
		for ( var i = 0; i < family.members.length; i++) {
				console.log("Fam Name : "+family.members[i].fname);
				console.log("Fam Id : "+family.members[i].fid);
			
			dropdown+="<li><a href='#'>&#xf007; "+family.members[i].fname+"</a>";
		}
	   members = "<ul>\
						"+dropdown
					+"</ul>\
				";

   
     return members;
    }
	
    
    function h2m_footer() {
     $("#footer").replaceWith("\
        <link rel='stylesheet' type='text/css' href='css/h2m.css'>\
            <div id='bottom-nav'>\
                <div id='talk_button' class='h2m-button'> \
                <a target='_parent' href='talk.html'><img id='talk' src='img/Talk_svg.png' alt='Talk to Doctor'></a>\
            </div>    \
            <div id='request_button' class='h2m-button'> \
                <a target='_parent' href='request.html'><img id='request' src='img/Request_svg.png' alt='Request Records'></a>\
            </div> \
            <div id='upload_button' class='h2m-button'> \
                <a target='_parent' href='upload.html'><img id='upload' width='35px' height='40px' src='img/upload.svg' alt='Upload Records'></a>\
            </div>   \
            <div id='send_button' class='h2m-button'> <a target='_parent' href='send.html'><img id='send' src='img/Send_svg.png' alt='Send Records to Doctor'></a>\
            </div>    \
            <div target='_parent' id='records_button' class='h2m-button'> \
                <a target='_parent' href='records.html'><img id='records' width='60px' src='img/records_svg.png' alt='View Records'></a>\
    </div>\
</div>");
 
        //set the current frame's image to white
        locationObj=location.href;
        var filename = locationObj.split("/").pop();  
        if (filename=="talk.html"){
            document.getElementById("talk").src="img/Talk_white_svg.png";
        }  
        if (filename=="request.html"){
            document.getElementById("request").src="img/Request_white_svg.png";
        }  
        if (filename=="upload.html"){
            document.getElementById("upload").src="img/upload_white.svg";
        }   
        if (filename=="send.html"){
            document.getElementById("send").src="img/Send_white_svg.png";
        }  
        if (filename=="records.html"){
            document.getElementById("records").src="img/records_white_svg.png";
        }   
     
    }    
});  
 