//Public variable setters grabbing from HTML elements.  Those HTML elements were passed these variables from PHP...
var med_id = $("#MEDID").val();
var mem_id = $("#USERID").val();
var both_id = $("#BOTH_ID").val();
var get_idusu = $("#GET_IDUSU").val();
var open_modal = $("#OPENMODAL").val();
var telemed_on = $("#TELEMED_ON").val();
var telemed_value = $("#TELEMED_VALUE").val();
var start_time = $("#START_TIME").val();
var num_multireferral = $("#NUM_MULTIREFERRAL").val();
var idusfixed_holder = $("#IDUSFIXED").val();
var idusfixed_name = $("#IDUSFIXED_NAME").val();
var lifepin_canal = $("#CANAL").val();
var referral_id = $("#REFERRAL_ID").val();
var other_doc = $("#OTHERDOC").val();
var custom_look = $("#CUSTOM_LOOK").val();
var id_med_name = $("#IdMEDName").val();
var id_med_surname = $("#IdMEDSurname").val();
var med_user_name = $("#MEDUSERNAME").val();
var med_user_surname = $("#MEDUSERSURNAME").val();
var domain = $("#DOMAIN").val();

getFormattedTime = function (fourDigitTime) {
    var hours24 = parseInt(fourDigitTime.substring(0, 2),10);
    var hours = ((hours24 + 11) % 12) + 1;
    var amPm = hours24 > 11 ? 'pm' : 'am';
    var minutes = fourDigitTime.substring(3, fourDigitTime.length - 3);

    return hours + ':' + minutes + amPm;
};

function checkNPAvailability(){
        var np_date = $("#selectAppointmentDate").val();
        var np_time = $("#selectAppointmentTime").val();

        $.post("checkNPAvailability.php", {id: get_idusu, date: np_date, time: np_time}, function(data, status){
            var data = JSON.parse(data);
            console.log(data);
            if(data.items[0].DocID == 0 && (typeof data.items[1].Start != 'undefined')){
                var time_recs = '';
                if(data.items[1].Start.length > 0){
                    time_recs += '<p><b>Recommended available times:</b><p>'+getFormattedTime(data.items[1].Start)+' to '+getFormattedTime(data.items[1].End);
                }
                if(data.items[2].Start.length > 0 && (typeof data.items[2].Start != 'undefined')){
                    time_recs += '<p>'+getFormattedTime(data.items[2].Start)+' to '+getFormattedTime(data.items[2].End);
                }
                if(data.items[3].Start.length > 0 && (typeof data.items[3].Start != 'undefined')){
                    time_recs += '<p>'+getFormattedTime(data.items[3].Start)+' to '+getFormattedTime(data.items[3].End);
                }
                if(data.items[4].Start.length > 0 && (typeof data.items[4].Start != 'undefined')){
                    time_recs += '<p>'+getFormattedTime(data.items[4].Start)+' to '+getFormattedTime(data.items[4].End);
                }
                if(data.items[5].Start.length > 0 && (typeof data.items[5].Start != 'undefined')){
                    time_recs += '<p>'+getFormattedTime(data.items[5].Start)+' to '+getFormattedTime(data.items[5].End);
                }
                    
                
                $('#selectedNursePractitioner').html('There are not any available nurse practitioners available at this time.  <p>Please try another time.'+time_recs);
            }else{
                $('#selectAppointmentButton').hide();
                $('#selectedNursePractitioner').html('Your appointment has been set for '+data.items[0].Display_Time+' on '+data.items[0].Date_Appt+' <p>with <b>'+data.items[0].DocName+' '+data.items[0].DocSurname+'</b>.<p>Please be ready to connect at this time.');
                setTimeout(function(){
                    $("#connectMemberStep0").hide();
                    $("#connectMemberStep2").show();
                }, 7000);
            }
            
        });
    }


function showTempPassword(){
	$.post('getTempPassword.php', {id:mem_id})
	.done(function(data){
		swal('The temporary password for this account is... \n\n'+data);
		});
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

//THIS CREATES LIST OF LOCATIONS FOR E-PRESCRIBING
function createLocationsList() {
    $( "#add_locations" ).dialog({
        autoOpen: true,
        resizable:false,
        height: 550,
        width: 550,
        modal: true,

    });
}

function createPracticesList() {
    $( "#add_practices" ).dialog({
        autoOpen: true,
        resizable:false,
        height: 250,
        width: 550,
        modal: true,

    });
}
//THIS SETS LOCATION FOR E-PRESCRIBING
function setLocation(id) {
    var locationURL = 'setLocation.php?id='+id;
    $.ajax(
        {
            url: locationURL,
            dataType: "json",
            async: false,
            success: function(data)
            {
                locations = data.items;

            }
        });
    $('#cname2').val(locations[0].cname);
    $('#pname2').val(locations[0].pname);
    $('#address12').val(locations[0].address1);
    $('#address22').val(locations[0].address2);
    $('#city2').val(locations[0].city);
    $('#state2').val(locations[0].state);
    $('#zip2').val(locations[0].zip);
    $('#phone2').val(locations[0].phone);
    $('#fax2').val(locations[0].fax);
    $('#id2').val(locations[0].id);

    var cname2 = locations[0].cname;
    var address12 = locations[0].address1;
    var address22 = locations[0].address2;
    var city2 = locations[0].city;
    var state2 = locations[0].state;
    var zip2 = locations[0].zip;
    var phone2 = locations[0].phone;
    var fax2 = locations[0].fax;
    var id2 = locations[0].id;

    var myClass = $('#USERID').val();
    var myDoc = $('#MEDID').val();
    var myNPI = $('#NPI').val();
    var myDEA = $('#DEA').val();
    var practice_holder = $('#practice').val();
    var practiceid_holder = $('#id3').val();

    if(cname2 == ''){
        alert('Your clinic has not been named and therefore we cannot provide access to our e-prescription platform.');
    }else if(address12 == ''){
        alert('Your clinic does not have a street address assigned and therefore we cannot provide access to our e-prescription platform.');
    }else if(city2 == ''){
        alert('Your clinic does not have a city assigned and therefore we cannot provide access to our e-prescription platform.');
    }else if(state2 == '' | state2.length < 2 | state2.length > 2){
        alert('Your clinic does not have a state assigned and therefore we cannot provide access to our e-prescription platform.  Please use two character state format like TX.');
    }else if(zip2 == '' | zip2.length < 5 | zip2.length > 5){
        alert('Your clinic does not have a zip assigned and therefore we cannot provide access to our e-prescription platform.  Please use 5 digit format like 75287.');
    }else if(phone2 == '' | phone2.length < 10){
        alert('Your clinic does not have a phone number assigned and therefore we cannot provide access to our e-prescription platform.  Please include the area code like 555-555-5555.');
    }else if(fax2 == '' | fax2.length < 10){
        alert('Your clinic does not have a fax number assigned and therefore we cannot provide access to our e-prescription platform.  Please include the area code like 555-555-5555.');
    }else if(id2 == ''){
        alert('We cannot seem to find your account identifier.  Try logging into your account again to reset your account identifier.');
    }else{
        var myElement = document.querySelector("#add_location");
        myElement.style.display = "none";
        var myElement2 = document.querySelector("#location_table");
        myElement2.style.display = "none";


        $("#ePrescribe_Modal").html('<iframe src="mdtoolbox/index.php?modal=1&IdUsu='+myClass+'&medid='+myDoc+'&cname='+cname2+'&address1='+address12+'&address2='+address22+'&city='+city2+'&state='+state2+'&zip='+zip2+'&phone='+phone2+'&fax='+fax2+'&id='+id2+'&practice='+practice_holder+'&practiceid='+practiceid_holder+'" width="1000" height="660" scrolling="no" style="width:1000px;height:660px; margin: 0px; border: 0px solid #FFF; outline: 0px; padding: 0px; overflow: hidden;"></iframe>');
        //console.log('<iframe src="mdtoolbox/index.php?modal=1&IdUsu='+myClass+'&medid='+myDoc+'&cname='+cname2+'&address1='+address12+'&address2='+address22+'&city='+city2+'&state='+state2+'&zip='+zip2+'&phone='+phone2+'&fax='+fax2+'&id='+id2+'&practice='+practice_holder+'&practiceid='+practiceid_holder+'');
    }
}

//THIS SETS PRACTICE FOR E-PRESCRIBING
function setPractice(id) {
    var locationURL = 'setLocation.php?id='+id+'&show=practice';
    $.ajax(
        {
            url: locationURL,
            dataType: "json",
            async: false,
            success: function(data)
            {
                locations = data.items;

            }
        });
    $('#practice').val(locations[0].practice);
    $('#id3').val(locations[0].id);

    var practice2 = locations[0].practice;
    var id2 = locations[0].id;

    var myClass = $('#USERID').val();
    var myDoc = $('#MEDID').val();
    var myNPI = $('#NPI').val();
    var myDEA = $('#DEA').val();

    if(practice2 == ''){
        alert('Your practice has not been named and therefore we cannot provide access to our e-prescription platform.');
    }else if(id2 == ''){
        alert('We cannot seem to find your account identifier.  Try logging into your account again to reset your account identifier.');
    }else{
        var myElement = document.querySelector("#add_practice");
        myElement.style.display = "none";
        var myElement2 = document.querySelector("#practice_table");
        myElement2.style.display = "none";

        pullLocationsList(id2);

    }
}

function pullLocationsList(id) {
    var myDoc = $('#MEDID').val();
    var DirURL = 'getLocationsList.php?docid='+myDoc+'&id='+id;
    $.ajax(
        {
            url: DirURL,
            dataType: "html",
            complete: function(){ 
            },		
            success: function(data) 
            {

                //alert('produced');
                $("#ePrescribe_Modal").html(data);

            }
        });
}


$("input[type='image']").click(function() {
    $("input[id='fileToUpload2']").click();
});

//$("input[type='file']").click(function() {
//$("input[id='make_upload']").click();
//});

jQuery("input[id='fileToUpload2']").change(function () {
    $("input[id='make_upload']").click();
});

var list = new Array();
var curr_file=-1;
var timeoutTime = 18000000;
//var timeoutTime = 300000;  //5minutes
var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


/*$('#upload_avatar').uploadify({
		'method'   : 'post',
		'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
        'swf'      : 'js/uploadify/uploadify.swf',
        'uploader' : 'uploadify.php?pullfile=<?php echo $_GET['IdUsu']; ?>',
		'multi'    :  false,
        'onUploadSuccess' : function(file, data, response) {
		var split = data.split('|');
        //alert('The file was saved to: ' + split[0]);
		//if(split[1]=="1")
		//{
		//	alert("Kindly upload image of minimum dimensions 70X70");
		//	return;	
		//}
		$('#patientImageDiv').show();
		//if(split[0]=="fileError")
		//{
		//	alert("Please select a file belonging to one of the following types: jpeg,gif or png");
		//	$('#patientImage').attr('src','PatientImage/defaultDP.jpg');
		//	return;
		//}
		//else
		$('#patientImage').attr('src',split[0]);
		location.reload();
    }
    });*/

function fileSelected() {
    var file = document.getElementById('fileToUpload2').files[0];
    if (file) {
        var fileSize = 0;
        if (file.size > 1024 * 1024)
            fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
        else
            fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';

        document.getElementById('fileName').innerHTML = 'Name: ' + file.name;
        document.getElementById('fileSize').innerHTML = 'Size: ' + fileSize;
        document.getElementById('fileType').innerHTML = 'Type: ' + file.type;
    }
}

function uploadFile2() {
    var fd = new FormData();
    var uploader = "pullfile="+get_idusu;

    
    
    var xhr = new XMLHttpRequest();
    xhr.upload.addEventListener("progress", uploadProgress, false);
    /*xhr.addEventListener("load", uploadComplete, false);
    xhr.addEventListener("error", uploadFailed, false);
    xhr.addEventListener("abort", uploadCanceled, false);*/
    xhr.open("POST", "uploadifyPatient.php?"+uploader, true);
    //xhr.setRequestHeader('Content-type', 'multipart/form-data');
    fd.append("fileToUpload2", document.getElementById('fileToUpload2').files[0]);
    xhr.send(fd);
    xhr.onreadystatechange = function(e) {
        if (xhr.readyState == 4) {
            if(xhr.status == 200) {
                uploadComplete(xhr);
            }
            else {
                uploadFailed();
            }
        }
    };
    
}

function uploadProgress(evt) {
    if (evt.lengthComputable) {
        var percentComplete = Math.round(evt.loaded * 100 / evt.total);
        document.getElementById('progressNumber').innerHTML = percentComplete.toString() + '%';
    }
    else {
        document.getElementById('progressNumber').innerHTML = 'unable to compute';
    }
}

function uploadComplete(evt) {
    /* This event is raised when the server send back a response */
    if(evt.responseText == "File is uploaded successfully.") location.reload();
    else alert(evt.responseText);
}

function uploadFailed() {
    alert("There was an error attempting to upload the file.");
}

function uploadCanceled(evt) {
    alert("The upload has been canceled by the user or the browser dropped the connection.");
}


var active_session_timer = 60000; //1minute
var sessionTimer = setTimeout(inform_about_session, active_session_timer);

var offset1=0;    //used for tab scrolling
var last_pos=0;	 //used for tab scrolling
var num_reports=0; //used for tab scrolling
var jump=8;//used for tab scrolling
var timeline_loaded = false;
var oWindow=new Array();
var pages;

// commented out .datepicker for evolution_date since we are now using the html5 datepicker
/*$('#evolution_date').datepicker({
    inline: true,
    nextText: '&rarr;',
    prevText: '&larr;',
    showOtherMonths: true,
    dateFormat: 'mm-dd-yy',
    dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    showOn: "button",
    buttonImage: "images/calendar-blue.png",
    buttonImageOnly: true,
    changeYear: true ,
    changeMonth: true,
    yearRange: '1900:c',
});*/


/*    Commented out by Pallab as date is no longer required in notes pdf 
          $('#note_date').datepicker({
			inline: true,
			nextText: '&rarr;',
			prevText: '&larr;',
			showOtherMonths: true,
			dateFormat: 'mm-dd-yy',
			dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			showOn: "button",
			buttonImage: "images/calendar-blue.png",
			buttonImageOnly: true,
            changeYear: true ,
			changeMonth: true,
			yearRange: '1900:c',
		}); */



$('#classification_datepicker').datepicker({
    inline: true,
    nextText: '&rarr;',
    prevText: '&larr;',
    showOtherMonths: true,
    dateFormat: 'mm-dd-yy',
    dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    showOn: "button",
    buttonImage: "images/calendar-blue.png",
    buttonImageOnly: true,
    changeYear: true ,
    changeMonth: true,
    yearRange: '1900:c',
});




//This function is called at regular intervals and it updates ongoing_sessions lastseen time
function inform_about_session()
{
    if(both_id != ''){
        $.ajax({
            url: 'ongoing_sessions.php?userid='+both_id,
            success: function(data){
                //alert('done');
            }
        });
    } 
    clearTimeout(sessionTimer);
    sessionTimer = setTimeout(inform_about_session, active_session_timer);
}

function ShowTimeOutWarning()
{
    alert ('Session expired');
    var a=0;
    window.location = 'timeout.php';
}


function PrintAll(){
    for (var i = 0; i < pages.length; i++) {
        oWindow[i].print();
        oWindow[i].close();

    }
}





$(document).ready(function() {

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
        getScript('realtime-notifications/lib/gritter/js/jquery.gritter.min.js');
        var gritterOptions = {
            title: status,
            text: message,
            image:'images/Icono_H2M.png',
            sticky: false,
            time: '5000'
        };
        $.gritter.add(gritterOptions);

    }
    

    
    //FOR THE TIMEPICKERS
    $('#probe_time5').timepicker({ 'scrollDefaultNow': true });
    $('#connectMember_probe_time').timepicker({ 'scrollDefaultNow': true });

    // notifications
    //var pusher = new Pusher('d869a07d8f17a76448ed');
    //var channel_name=$('#BOTH_ID').val();
    //var channel = pusher.subscribe(channel_name);
    
    var push = new Push($("#BOTHID").val(), window.location.hostname + ':3955');
    
    push.bind('notification', function(data) 
    {
        displaynotification('New Message', data);
    });
    
    // share reports with member
    function share_reports ()
    {
        var ElementDOM ="All";
        var EntryTypegroup ="0";
        var Usuario = $("#USERID").val();
        var MedID = $("#MEDID").val();
        setTimeout(function(){
            var queUrl ="createAttachmentStreamNEWTEST.php?ElementDOM=na&EntryTypegroup="+EntryTypegroup+"&Usuario="+Usuario+"&MedID="+MedID+"&display_member=yes";
            $.get(queUrl, function(data, status)
            {
                $("#share_files_container").html(data);
                $("#share_modal").dialog({bgiframe: true, width: 900, height: 400});
                //$("#H2M_Spin_Stream").hide();
            });




        },200);
     }
    $("#shareFiles").on('click', function()
    {
        share_reports();
    });
    function loadScript(url, name, callback)
    {
        // Adding the script tag to the head as suggested before
        if(document.getElementById(name) != null)
        {
            document.getElementById(name).remove();
            
        }
        
        setTimeout(function()
        {
            var head = document.getElementsByTagName('head')[0];
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.id = name;
            script.src = url;

            // Then bind the event to the callback function.
            // There are several events for cross browser compatibility.
            //script.onreadystatechange = callback;
            script.onload = callback;

            // Fire the loading
            head.appendChild(script);
        }, 500);
    }
    function grabTimeline(id){
        $("#timeline").empty();
        
        if($('#BOTH_ID').val() == $("#USERID").val())
        {
            $.post("get_my_personal_doctors.php", {id: $("#USERID").val()}, function(data, status)
            {
                var doc = JSON.parse(data);
                $("#timeline").H2M_Timeline({doctor: doc['id'], patient: id, max: 50, minimized: 0});
            });
        }
        else
        {
            $("#timeline").H2M_Timeline({doctor: $("#MEDID").val(), patient: id, max: 50, minimized: 0});
        }

    }
    var tracking_probe_id = 0;
    $("#TrackingButton").on('click', function()
    {
        grabTimeline($("#USERID").val());
        var dctr = 0;
        if($('#BOTH_ID').val() != $("#USERID").val())
            dctr = $("#MEDID").val();
            
        $.post("get_probe.php", {doctor: dctr, patient: $("#USERID").val()}, function(data, status)
        {
            var d0 = JSON.parse(data);
            var probe = d0['probeID'];
            tracking_probe_id = probe;
            
            $.post("get_probe_protocols.php", {id: d0['protocolID']}, function(data, status)
            {
                var protocol_data = JSON.parse(data);
                $("button[id^='probebutton']").attr('disabled', 'disabled');
                if(protocol_data['question_title_1'].length > 0)
                    $("#probebutton_1").removeAttr('disabled');
                if(protocol_data['question_title_2'].length > 0)
                    $("#probebutton_2").removeAttr('disabled');
                if(protocol_data['question_title_3'].length > 0)
                    $("#probebutton_3").removeAttr('disabled');
                if(protocol_data['question_title_4'].length > 0)
                    $("#probebutton_4").removeAttr('disabled');
                if(protocol_data['question_title_5'].length > 0)
                    $("#probebutton_5").removeAttr('disabled');
                var question = 1;
                $('#probebutton_1').removeClass('probe_chart_button').addClass('probe_chart_button_selected');
                $('#probebutton_1').attr('disabled', 'disabled');
                //load_probe_graph(self, probe, question);
                $.post('get_probe_graph_data.php', {probeID: probe, question: question}, function(data, status)
                {
                    var d = JSON.parse(data);
                    //console.log('****************************************************');
                    if(d.data.length > 0 && d.protocol_name != null)
                    {
                        if($("#probe_graph_1").attr('loaded') != 1)
                        {
                            $("#probe_graph_1").html('<div style="width: 500px; height: 350px; text-align: center; margin: auto; margin-top: 125px;"><img src="images/load/29.gif" style="margin-top: 129px" height="42" width="42"/></div>');
                            //loadScript('js/h2m_probegraph-newdes.js', 'h2m_probegraph-newdes', function()
                            //{

                                setTimeout(function()
                                {
                                    $("#probe_graph_1").H2M_ProbeGraph({probe_id: probe , data: d.data, probe_alerts: d.probe_alerts,height: 312, width: 850, units: d.units, protocol_name: d.protocol_name, protocol_description: d.protocol_description, question_unit: d.question_unit, title: d.question_title, question_description: d.question_description, min_value: d.min_value, max_value: d.max_value, min_days: d.min_days, max_days: d.max_days, max_scale: d.max_scale, min_scale: d.min_scale, inverted: d.inverted});
                                    $("#probe_graph_1").css('display', 'block');
                                }, 200);
                                $("#probe_graph_1").attr('loaded', 1);
                                console.log('loading probe graph...');

                                $('#probe_question_1').text(d.question);
                                if(question == 1)
                                {
                                    $('#probe_question_1').css('display', 'block');
                                }
                            //});
                        }

                    }

                });
            });
        });
        $('.probe_chart_button').unbind('click');
        $('.probe_chart_button').on('click', function()
        {
            var info = $(this).attr('id').split('_');
            var question = info[1];
            $(this).siblings().each(function()
            {
                if($(this).hasClass('probe_chart_button_selected'))
                    $(this).removeAttr('disabled');
                $(this).removeClass('probe_chart_button_selected').addClass('probe_chart_button');
            });
            $(this).removeClass('probe_chart_button').addClass('probe_chart_button_selected');
            $(this).attr('disabled', 'disabled');

            var self = $(this).parent().parent().children().eq(0);
            $('div[id^="probe_question_"]').css('display', 'none');
            
            var probe = tracking_probe_id;
            $.post('get_probe_graph_data.php', {probeID: probe, question: question}, function(data, status)
            {
                var d = JSON.parse(data);
                if(d.data.length > 0 && d.protocol_name != null)
                {
                    if($("#probe_graph_"+question).attr('loaded') != 1)
                    {
                        $("#probe_graph_"+question).html('<div style="width: 500px; height: 350px; text-align: center; margin: auto; margin-top: 125px;"><img src="images/load/29.gif" style="margin-top: 129px" height="42" width="42"/></div>');
                        loadScript('js/h2m_probegraph-newdes.js', 'h2m_probegraph-newdes', function()
                        {

                            setTimeout(function()
                            {
                                $("#probe_graph_"+question).H2M_ProbeGraph({probe_id: probe , data: d.data, probe_alerts: d.probe_alerts,height: 312, width: 850, units: d.units, protocol_name: d.protocol_name, protocol_description: d.protocol_description, question_unit: d.question_unit, title: d.question_title, question_description: d.question_description, min_value: d.min_value, max_value: d.max_value, min_days: d.min_days, max_days: d.max_days, max_scale: d.max_scale, min_scale: d.min_scale, inverted: d.inverted});
                                $("#probe_graph_"+question).css('display', 'block');
                            }, 200);
                            $("#probe_graph_"+question).attr('loaded', 1);

                            $('#probe_question_'+question).text(d.question);
                            $('#probe_question_'+question).css('display', 'block');
                        });
                    }
                    $('div[id^="probe_graph"]').css('display', 'none');
                    $('#probe_graph_'+question).css('display', 'block');
                    $('#probe_question_'+question).text(d.question);
                    $('#probe_question_'+question).css('display', 'block');

                }

            });
        });
        $("#tracking_info_modal").dialog({bgiframe: true, width: 950, height: 600});
    });
    var medication_selected = '';
    var medication_selected_image = '';
    var medication_reminder_selected = 0;
    $("#reminder_starting_time").timepicker();
    function load_medication_reminders()
    {
        $("#reminders_container").empty();
        $.post("get_medication_reminders.php", {user: $("#USERID").val()}, function(data, status)
        {
            console.log(data);
            var d = JSON.parse(data);
            if(d.length > 0)
            {
                for(var i = 0; i < d.length; i++)
                {
                    var last_taken_color = '#D84840';
                    var name = d[i].name;
                    var id = d[i].id;
                    var last_taken = 'Never';
                    var response_array = new Array();
                    if(d[i].responses.length > 0)
                    {
                        var now = moment().utc();
                        var last_response = moment.unix(d[i].responses[0]).utc();
                        last_taken = last_response.from(now);
                        var duration = moment.duration(now.diff(last_response));
                        var hours = duration.asHours();
                        if(hours < d[i].frequency)
                            last_taken_color = '#54BC00';
                        var start = moment(d[i].start_utc+'+00:00');
                        for(var k = parseInt(start.format('X')); k <= parseInt(now.format('X')); k += (3600 * d[i].frequency))
                        {
                            var min = k - 300;
                            var max = k + 300;
                            var found = false;
                            console.log("K: " + k);
                            for(var m = 0; m < d[i].responses.length; m++)
                            {
                                console.log("RESPONSE: " + d[i].responses[m]);
                                if(d[i].responses[m] >= min && d[i].responses[m] <= max)
                                {
                                    response_array.push({
                                        date: moment.unix(d[i].responses[m]).tz(d[i].timezone).format('MMM D, YYYY h:mm A'),
                                        value: 1
                                    });
                                    found = true;
                                    d[i].responses.splice(m, 1);
                                    break;
                                }
                                console.log("");
                            }
                            if(!found)
                            {
                                response_array.push({
                                    date: moment.unix(k).tz(d[i].timezone).format('MMM D, YYYY h:mm A'),
                                    value: 0
                                });
                            }
                        }
                    }
                    
                    var html = '<div class="reminder_row" id="reminder_row_'+id+'">';
                    html += '<div style="float: left; width: 25%;">';
                    html += '<div class="reminder_name" style="width: 100%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-weight: bold; font-size: 16px; color: #555;">';
                    html += name;
                    html += '</div>'
                    html += '<div class="reminder_last_taken" style="width: 100%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; color: #777;">';
                    html += 'Last Taken:  <span style="color: '+last_taken_color+';">'+last_taken+'</span>';
                    html += '</div></div>';
                    html += '<div style="float: left; width: 65%; height: 30px; border: 1px solid #333; border-radius: 30px; margin-right: 10px; margin-top: 5px; overflow: hidden;" id="reminder_response_bar_'+id+'">';
                    html += '</div>';
                    html += '<div style="float: left; margin-top: 6px;">';
                    html += '<button class="reminders_edit_button" id="reminders_edit_button_'+id+'"><i class="icon-pencil"></i></button>';
                    html += '<button class="reminders_delete_button" id="reminders_delete_button_'+id+'">X</button>';
                    html += '</div>';
                    html += '</div>';
                    $("#reminders_container").append(html);
                    
                    var start = 0;
                    if(response_array.length > 25)
                        start = response_array - 25;
                    var count = 1;
                    for(var k = start; k < response_array.length; k++)
                    {
                        var color = '#D84840';
                        if(response_array[k].value == 1)
                            color = '#54BC00';
                        var cell = '<div id="reminder_response_cell_'+count+'_'+id+'" style="width: 4%; height: 30px; background-color: '+color+'; float: left;"></div>';
                        $("#reminder_response_bar_"+id).append(cell);
                        Tipped.create('#reminder_response_cell_'+count+'_'+id, response_array[k].date);
                        count++;
                    }
                }
            }
            else
            {
                var html = '<div class="reminder_row">';
                html += '<div style="float: left; width: 100%; height: 20px; margin-top: 8px; text-align: center; font-size: 16px; color: #999;">';
                html += 'No Medication Reminders';
                html += '</div>';
                html += '</div>';
                $("#reminders_container").append(html);
            }
        });
    }
    function update_medication_reminder()
    {
        //console.log("UPDATED: " + medication_reminder_selected);
        var on = 0;
        if($("#probeToggle_reminder").attr('checked'))
            on = 1;
        var time = $("#reminder_starting_time").val();
        var time_vals = time.split(":");
        var hour = parseInt(time_vals[0]);
        var minutes = time_vals[1].substr(0, 2);
        var ampm = time_vals[1].substr(2, 2);
        var date = $("#reminder_starting_date").val() + ' ';
        
        if(ampm == 'pm' && hour != 12)
            hour += 12;
        else if(ampm == 'am' && hour == 12)
            hour = 0;
        if(hour < 10)
            date += '0';
        date += hour + ':';
        date += minutes + ':00';
        
        $.post("update_medication_reminders.php", {start: date, timezone: $("#reminder_timezone").val(), alert: $("#reminder_alert").val(), frequency: $("#reminder_frequency").val(), unit: $("#reminder_frequency_unit").val(), on: on, id: medication_reminder_selected, edit: 1}, function(data, status)
        {
            console.log(data);
        });
    }
    $("#reminder_starting_date").on('change', function()
    {
        update_medication_reminder();
    });
    $("#reminder_starting_time").on('change', function()
    {
        update_medication_reminder();
    });
    $("#reminder_timezone").on('change', function()
    {
        update_medication_reminder();
    });
    $("#reminder_alert").on('change', function()
    {
        update_medication_reminder();
    });
    $("#reminder_frequency").on('change', function()
    {
        update_medication_reminder();
    });
    $("#reminder_frequency_unit").on('change', function()
    {
        update_medication_reminder();
    });
    $("#probeToggle_reminder").on('change', function()
    {
        if($(this).attr('checked'))
        {
            $("#probeToggleLabel_reminder").text('On');
            $("#probeToggleLabel_reminder").css('color', '#54BC00');
        }
        else
        {
            $("#probeToggleLabel_reminder").text('Off');
            $("#probeToggleLabel_reminder").css('color', '#D84840');
        }
        update_medication_reminder();
    });
    $(".reminders_edit_button").live('click', function()
    {
        var id = $(this).attr("id").split("_")[3];
        medication_reminder_selected = id;
        $.post("get_medication_reminders.php", {user: $("#USERID").val(), id: id}, function(data, status)
        {
            var d = JSON.parse(data);
            console.log(data);
            var frequency = d['frequency'];
            var unit = 'hours';
            if(frequency % 24 == 0)
            {
                unit = 'days';
                frequency /= 24;
            }
            if(d['start'] != null)
            {
                var date_vals = d['start'].split(" ");
                var ampm = 'am';
                var time_vals = date_vals[1].split(':');
                var hour = parseInt(time_vals[0]);
                if(hour >= 12)
                {
                    ampm = 'pm';
                    if(hour > 12)
                        hour -= 12;
                }
                var time = hour.toString() + ':' + time_vals[1] + ampm;
                $("#reminder_starting_date").val(date_vals[0]);
                $("#reminder_starting_time").val(time);
            }
            else
            {
                var today = moment().add(1, 'hours').set('minute', 0);
                $("#reminder_starting_date").val(today.format('YYYY-MM-DD'));
                $("#reminder_starting_time").val(today.format('h:mma'));
            }
            $("#reminder_alert").val(d['alert']);
            $("#reminder_frequency").val(frequency);
            $("#reminder_frequency_unit").val(unit);
            console.log(d['timezone']);
            if(d['timezone'] != null)
                $("#reminder_timezone").val(d['timezone']);
            else
                $("#reminder_timezone").val('America/Chicago');
            if(d['active'] == 1)
                $("#probeToggle_reminder").attr('checked', true);
            else
                $("#probeToggle_reminder").removeAttr('checked');
            if(d['active'] == 1)
            {
                $("#probeToggleLabel_reminder").text('On');
                $("#probeToggleLabel_reminder").css('color', '#54BC00');
            }
            else
            {
                $("#probeToggleLabel_reminder").text('Off');
                $("#probeToggleLabel_reminder").css('color', '#D84840');
            }
            $("#reminders_edit_modal").dialog({bgiframe: true, resizable: false, width: 400, height: 360});
        });
    });
    $(".reminders_delete_button").live('click', function()
    {
        var id = $(this).attr('id').split('_')[3];
        swal({title: "Delete Reminder",   
              text: "Are you sure you wish to delete this medication reminder? This action cannot be undone.",   
              type: "warning",   
              showCancelButton: true,   
              confirmButtonColor: "#DD6B55",   
              confirmButtonText: "Delete",   
              closeOnConfirm: true 
             }, function(isConfirm)
             {   
                if (isConfirm) 
                {
                    $.post("update_medication_reminders.php", {id: id, delete: 1}, function(data, status)
                    {
                        load_medication_reminders();
                    });
                }
            }
        );
        
    });
    $("#MedicationsButton").on('click', function()
    {
        load_medication_reminders();
        $("#medication_reminders_modal").dialog({bgiframe: true, width: 950, height: 600});
    });
    $("#add_medication_reminder_button").on('click', function()
    {
        $("#name").val('');
        $("#pill_shape").val('');
        $("#pill_color").val('');
        $("#imprint").val('');
        $("#imprint_color").val('');
        $("#medications_search_results_container").empty();
        $("#medication_select_button").attr('disabled', 'disabled');
        medication_selected_image = '';
        medication_selected = -1;
        $("#medication_search_modal").dialog({bgiframe: true, width: 970, height: 750});
    });
    $(".medication_search_result").live('click', function()
    {
        $("#medication_select_button").removeAttr('disabled');
        $(".medication_search_result").css('background-color', '#FFF');
        $(this).css('background-color', '#54BC00');
        medication_selected_image = $(this).children().eq(0).children().eq(0).attr('src');
        medication_selected = $(this).children().eq(1).text();
        
        console.log('IMAGE: ' + medication_selected_image);
        console.log('NAME: ' + medication_selected);
    });
    $("#medication_select_button").on('click', function()
    {
        $.post("update_medication_reminders.php", {user_id: $("#USERID").val(), name: medication_selected, image: medication_selected_image}, function(data, status)
        {
            $("#medication_search_modal").dialog('close');
            load_medication_reminders();
        });
    });
    $("#medication_search_button").on('click', function()
    {
        var url = "http://rximage.nlm.nih.gov/api/rximage/1/rxnav?";
        var count = 0;
        if($("#name").val().length > 0)
        {
            url += "name="+$("#name").val().toLowerCase();
            count++;
        }
        if($("#pill_shape").val() != 0)
        {
            if(count > 0)
                url += "&";
            url += "shape="+$("#pill_shape").val();
            count++;
        }
        if($("#pill_color").val() != 0)
        {
            if(count > 0)
                url += "&";
            url += "color="+$("#pill_color").val();
            count++;
        }
        if($("#imprint").val().length > 0)
        {
            if(count > 0)
                url += "&";
            url += "imprint="+$("#imprint").val().toLowerCase();
            count++;
        }
        if($("#imprint_color").val() != 0)
        {
            if(count > 0)
                url += "&";
            url += "imprintColor="+$("#imprint_color").val();
            count++;
        }
        if(count > 0)
        {
            $.get(url, function(data, status)
            {
                $("#medications_search_results_container").empty();

                var res = data;
                var imgs = res.nlmRxImages;
                if(imgs.length > 0)
                {
                    $("#medications_search_results_container").css('height', '328px');
                    $("#medications_search_results_container").css('width', (imgs.length * 290).toString() + 'px');
                    for(var i = 0; i < imgs.length; i++)
                    {
                        var html = '';
                        html += '<div class="medication_search_result">';
                        html += '<div style="width: 200px; height: 200px; overflow: hidden; margin: auto; border-radius: 8px;">';
                        html += '<img src="'+imgs[i].imageUrl+'" width="200" height="200" />';
                        html += '</div>';
                        html += '<div style="width: 200px; height: 42px; overflow: hidden; margin: auto; border-radius: 8px; text-align: center; margin-top: 25px; font-size: 18px;">';
                        html += imgs[i].name;
                        html += '</div>';
                        html += '</div>';
                        $("#medications_search_results_container").append(html);
                    }
                }
                else
                {
                    $("#medications_search_results_container").css('height', '30px');
                    $("#medications_search_results_container").append('<div style="width: 100%; height: 30px; text-align: center">No Results</div>');
                }
            });
        }
    });
    $("#shareFilesButton").on('click', function()
    {
        var reportcheck=new Array();
        reportids="";
        reportids_unchecked="";
        $("input[type=checkbox][id^=\"reportcol\"]").each(
            function () {
                var sThisVal = (this.checked ? "1" : "0");
                if(sThisVal==1)
                {
                    var idp=$(this).parents("div.attachments").attr("id");
                    reportcheck.push(this.id);
                    reportids=reportids+idp+" ";

                }
                else
                {
                    var idp=$(this).parents("div.attachments").attr("id");
                    reportids_unchecked=reportids_unchecked+idp+" ";
                }



            });
            $.post( "display_pin_for_member.php", { reports: reportids })
            .done(function( data ) {
                //$.post( "hide_from_member.php", { reports: reportids_unchecked }).done(function( data ) {
                    swal("Reports Shared!", "You have successfully shared the selected reports with this member.", "success");
                    $("#share_modal").dialog("close");
                //});
            });

            reportids="";
            reportcheck.length=0;
    });
    
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    
    function draw_probe_alert_graph()
    {
        var canvas = document.getElementById('probe_alert_graph');
        var context = canvas.getContext('2d');
        
        context.clearRect(0, 0, canvas.width, canvas.height);
        
        var min_day = 0;
        if($("#probe_alert_expected_day_1").val().length > 0)
            min_day = parseInt($("#probe_alert_expected_day_1").val());
        var max_day = min_day + 1;
        if($("#probe_alert_expected_day_2").val().length > 0)
            max_day = parseInt($("#probe_alert_expected_day_2").val());
        
        var start_value = 0;
        if($("#probe_alert_start_value").val().length > 0)
            start_value = parseInt($("#probe_alert_start_value").val());
        var expected_value = start_value + 1;
        if($("#probe_alert_expected_value").val().length > 0)
            expected_value = parseInt($("#probe_alert_expected_value").val());
        
        var tolerance = 0.5;
        if($("#probe_alert_tolerance").val().length > 0)
            tolerance = parseInt($("#probe_alert_tolerance").val()) / 100.0;
        
        var days = (1.0 - (min_day / max_day));
        var slope = expected_value / max_day;
        
        var start_x = 30;
        var end_x = canvas.width - 50;
        var x_diff = canvas.width - end_x;
        var start_y = 0;
        var end_y = canvas.height - 30;
        if(expected_value < start_value)
        {
            end_y = 0;
            start_y = canvas.height - 30;
        }

        context.beginPath();
        context.rect(start_x, 0, canvas.width - start_x - x_diff, canvas.height - 30);
        context.fillStyle = "rgba(226, 241, 251, 1.0)";
        context.fill();
        
        context.beginPath();
        context.moveTo(start_x, end_y);
        context.lineTo(canvas.width - x_diff, start_y);
        context.lineTo((canvas.width - start_x - x_diff) * (parseFloat(min_day) / parseFloat(max_day)) + start_x, start_y);
        context.lineTo(start_x, end_y);
        context.closePath();
        context.strokeStyle = 'rgba(255, 255, 255, 1.0)';
        context.fillStyle = 'rgba(255, 255, 255, 1.0)';
        context.setLineDash([0,0]);
        context.stroke();
        context.fill();
        
        context.beginPath();
        context.moveTo(start_x, end_y);
        context.lineTo(days * tolerance * (canvas.width - start_x - x_diff) + start_x, end_y);
        context.lineTo((canvas.width - start_x - x_diff) + (days * tolerance * canvas.width) + start_x, start_y);
        context.lineTo(canvas.width - x_diff, start_y);
        context.lineTo(start_x, end_y);
        context.closePath();
        context.strokeStyle = 'rgba(194, 206, 218, 1.0)';
        context.fillStyle = 'rgba(194, 206, 218, 1.0)';
        context.setLineDash([0,0]);
        context.stroke();
        context.fill();
        
        context.beginPath();
        context.moveTo(days * tolerance * (canvas.width - start_x - x_diff) + start_x, end_y);
        context.lineTo(canvas.width - x_diff, end_y);
        context.lineTo((canvas.width - start_x - x_diff) + (days * tolerance * canvas.width) + start_x, start_y);
        context.lineTo(days * tolerance * (canvas.width - start_x - x_diff) + start_x, end_y);
        context.closePath();
        context.strokeStyle = 'rgba(165, 175, 185, 1.0)';
        context.fillStyle = 'rgba(165, 175, 185, 1.0)';
        context.setLineDash([0,0]);
        context.stroke();
        context.fill();
        
        context.beginPath();
        context.rect(start_x, 0, canvas.width - start_x - x_diff, canvas.height - 30);
        context.strokeStyle = "rgba(226, 241, 251, 1.0)";
        context.lineWidth = 2;
        context.stroke();
        
        if(min_day > 0)
        {
            context.beginPath();
            context.moveTo((min_day / max_day) * (canvas.width - start_x - x_diff) + start_x, 0);
            context.lineTo((min_day / max_day) * (canvas.width - start_x - x_diff) + start_x, canvas.height - 30);
            context.strokeStyle = '#999';
            context.setLineDash([5,5]);
            context.lineWidth = 1;
            context.stroke();
        }
        
        context.clearRect(end_x, 0, canvas.width - end_x, canvas.height);
        
        context.font = "12px Helvetica";
        context.fillStyle = "#333";
        if(expected_value >= start_value)
        {
            context.fillText(start_value, 0, canvas.height - 30);
            context.fillText(expected_value, 0, 12);
        }
        else
        {
            context.fillText(expected_value, 0, canvas.height - 30);
            context.fillText(start_value, 0, 12);
        }
        
        context.fillText('0 Days', start_x - 7, canvas.height - 5);
        if(min_day > 0)
            context.fillText(min_day+' Days', (min_day / max_day) * (canvas.width - start_x - x_diff) + start_x - 20, canvas.height - 5);
        context.fillText(max_day+' Days', canvas.width - 70, canvas.height - 5);
        
        
    }
    var alerts = [{}, {}, {}, {}, {}];
    var probe_questions = [];
    $("#probe_alert_clear_button").on('click', function()
    {
        alerts = [{tolerance: 10}, {tolerance: 10}, {tolerance: 10}, {tolerance: 10}, {tolerance: 10}];
        $("#probe_alert_start_value").val("");
        $("#probe_alert_expected_value").val("");
        $("#probe_alert_expected_day_1").val("");
        $("#probe_alert_expected_day_2").val("");
        $("#probe_alert_tolerance").val(10);
    });
    $("#probe_protocols").data('prev', $("#probe_protocols").val());
    $("#probe_protocols").on('change', function()
    {
        var protocol = $(this).val();
        swal({title: "Probe Change",   
              text: "Switching probes will erase any probe alerts that you have defined for this user. Do you wish to continue?",   
              type: "warning",   
              showCancelButton: true,   
              confirmButtonColor: "#DD6B55",   
              confirmButtonText: "Change",   
              closeOnConfirm: true 
             }, function(isConfirm)
             {   
                if (isConfirm) 
                {
                    $.post("save_probe_alerts.php", {clear: true, doctor: $("#MEDID").val(), patient: selected_patient}, function(data, status)
                    {
                    });
                    save_prove();
                    if(protocol == 1 || protocol == 0)
                        $("#edit_probes_button").attr('disabled', 'disabled');
                    else
                        $("#edit_probes_button").removeAttr('disabled');
                } 
                else 
                {     
                    $("#probe_protocols").val($("#probe_protocols").data('prev'));
                    $("#probe_protocols").data('prev', $("#probe_protocols").val());
                }
        });
    });
    $("#probe_time5").on('change', function()
    {
        save_prove();
    });
    $("#probe_timezone").on('change', function()
    {
        save_prove();
    });
    $("#probe_interval").on('change', function()
    {
        save_prove();
    });
    $("#probe_method").on('change', function()
    {
        save_prove();
    });
    $("#probe_alert_button").on('click', function()
    {
        if($("#probe_protocols").val() == -1)
        {
            swal("No probe", "You do not have a probe defined.\nYou must have a probe to assign an alert to.", "error");
        }
        else
        {
            $.post("get_probe_protocols.php", {doctor: $("#MEDID").val()}, function(data, status)
            {
                var d = JSON.parse(data);
                probe_questions = d['questions'];
                var var_length = d['protocols'].length;
                $("#connectMember_probe_protocols").html('');
                $("#connectMember_probe_protocols").append('<option value="1">General Health</option>');
                for(var i = 0; i < var_length; i++)
                {
                    $("#connectMember_probe_protocols").append('<option value="'+d['protocols'][i].protocolID+'">'+d['protocols'][i].name+'</option>');
                }
                
                $.post('get_probe_alerts.php', {doctor: $("#MEDID").val(), patient: selected_patient}, function(data, status)
                {
                    console.log(data);
                    alerts = [{}, {}, {}, {}, {}];
                    var d = JSON.parse(data);
                    for(var k = 0; k < d.length; k++)
                    {
                        alerts[d[k].question - 1] = 
                        {
                             start_value: d[k].start_value, 
                             exp_value: d[k].exp_value, 
                             exp_day_1: d[k].exp_day_1,
                             exp_day_2: d[k].exp_day_2, 
                             tolerance: d[k].tolerance
                        };
                    }
                    if(alerts[0].hasOwnProperty('start_value'))
                    {
                        $("#probe_alert_start_value").val(alerts[0].start_value);
                        $("#probe_alert_expected_value").val(alerts[0].exp_value);
                        $("#probe_alert_expected_day_1").val(alerts[0].exp_day_1);
                        $("#probe_alert_expected_day_2").val(alerts[0].exp_day_2);
                        $("#probe_alert_tolerance").val(alerts[0].tolerance);
                    }
                    else
                    {
                        $("#probe_alert_start_value").val("");
                        $("#probe_alert_expected_value").val("");
                        $("#probe_alert_expected_day_1").val("");
                        $("#probe_alert_expected_day_2").val("");
                        $("#probe_alert_tolerance").val(10);
                        alerts[0].tolerance = 10;
                    }
                    $("#edit_probe_alerts").css("display", "block");
                    $("#manage_user_probe").css("display", "none");
                    $("#probe_alert_question").empty();
                    console.log("QUESTIONS: " + probe_questions[$("#probe_protocols").val()].length);
                    for(var i = 0; i < probe_questions[$("#probe_protocols").val()].length; i++)
                    {
                        $("#probe_alert_question").append('<option value="'+probe_questions[$("#probe_protocols").val()][i].index+'">'+probe_questions[$("#probe_protocols").val()][i].text+'</option>');
                    }
                    draw_probe_alert_graph();
                });
            });
            
        }
    });
    $("#probe_alert_question").on('change', function()
    {
        if(alerts[$(this).val() - 1].hasOwnProperty('start_value'))
        {
            $("#probe_alert_start_value").val(alerts[$(this).val() - 1].start_value);
            $("#probe_alert_expected_value").val(alerts[$(this).val() - 1].exp_value);
            $("#probe_alert_expected_day_1").val(alerts[$(this).val() - 1].exp_day_1);
            $("#probe_alert_expected_day_2").val(alerts[$(this).val() - 1].exp_day_2);
            $("#probe_alert_tolerance").val(alerts[$(this).val() - 1].tolerance);
        }
        else
        {
            $("#probe_alert_start_value").val("");
            $("#probe_alert_expected_value").val("");
            $("#probe_alert_expected_day_1").val("");
            $("#probe_alert_expected_day_2").val("");
            $("#probe_alert_tolerance").val(10);
            alerts[$(this).val() - 1].tolerance = 10;
        }
        draw_probe_alert_graph();
    });
    $("#probe_alert_start_value").on('change', function()
    {
        alerts[$("#probe_alert_question").val() - 1].start_value = $(this).val();
        draw_probe_alert_graph();
    });
    $("#probe_alert_expected_value").on('change', function()
    {
        alerts[$("#probe_alert_question").val() - 1].exp_value = $(this).val();
        draw_probe_alert_graph();
    });
    $("#probe_alert_expected_day_1").on('change', function()
    {
        alerts[$("#probe_alert_question").val() - 1].exp_day_1 = $(this).val();
        draw_probe_alert_graph();
    });
    $("#probe_alert_expected_day_2").on('change', function()
    {
        alerts[$("#probe_alert_question").val() - 1].exp_day_2 = $(this).val();
        draw_probe_alert_graph();
    });
    $("#probe_alert_tolerance").on('change', function()
    {
        alerts[$("#probe_alert_question").val() - 1].tolerance = $(this).val();
        draw_probe_alert_graph();
    });
    $('#probe_alerts_button_back').live('click', function()
    {
        $("#edit_probe_alerts").css("display", "none");
        $("#manage_user_probe").css("display", "block");
    });
    $('#probe_alerts_save_button').live('click', function()
    {
		var first_value = $("#probe_alert_expected_day_1").val();
		var second_value = $("#probe_alert_expected_day_2").val();
		
		var first_value_parsed = parseInt(first_value);
		var second_value_parsed = parseInt(second_value);
		
		if(first_value_parsed <= second_value_parsed){
			$.post("save_probe_alerts.php", {alerts: JSON.stringify(alerts), doctor: $("#MEDID").val(), patient: selected_patient}, function(data, status)
			{
				$("#edit_probe_alerts").css("display", "none");
				$("#manage_user_probe").css("display", "block");
			});
		}else{
			$("#probe_alert_expected_day_1").focus();
			swal("Your first day value must be greater than or equal to your second day value.");
		}
    });
    $("#save_probes_button").on('click', function()
    {
        if($("#probe_protocols").val() <= 0)
            swal("Error", "Please select a probe", "error");
        else if($("#probe_time5").val().length == 0)
            swal("Error", "Please select the probe's time", "error");
        else
        {
            $.post("toggle_probe.php", {doctor: $("#MEDID").val(), patient: selected_patient, status: 'null', protocol: $("#probe_protocols").val(), time: $("#probe_time5").val(), timezone: $("#probe_timezone").val(), interval: $("#probe_interval").val(), request: $("#probe_method").val(), save: 1}, function(data, status)
            {
                if(parseInt(data) == 1 || parseInt(data) == -2)
                {
                    swal("Saved", "The probe has been saved successfully.", "success");
                }
                else
                {
                    
                    swal("Error", "There was an error, please try again later", "error");
                }
            });
        }
    });
    $("#edit_probes_button").on('click', function()
    {
        //$("#manage_user_probe").css("display", "none");
        //$("#view_probes").css("display", "block");
        
        var probe_id = $("#probe_protocols").val();//$(this).attr("id").split("_")[3];
        selected_probe = probe_id;
        $.post("get_probe_protocols.php", {id: probe_id}, function(data, status)
        {
            console.log(data);
            var d = JSON.parse(data);
            probe_question_title = [d['question_title_1'], d['question_title_2'], d['question_title_3'], d['question_title_4'], d['question_title_5']];
            probe_question_en = [d['question_en_1'], d['question_en_2'], d['question_en_3'], d['question_en_4'], d['question_en_5']];
            probe_question_es = [d['question_es_1'], d['question_es_2'], d['question_es_3'], d['question_es_4'], d['question_es_5']];
            probe_question_unit = [d['question_unit_1'], d['question_unit_2'], d['question_unit_3'], d['question_unit_4'], d['question_unit_5']];
            probe_min = [d['answer_min_1'], d['answer_min_2'], d['answer_min_3'], d['answer_min_4'], d['answer_min_5']];
            probe_max = [d['answer_max_1'], d['answer_max_2'], d['answer_max_3'], d['answer_max_4'], d['answer_max_5']];
            probe_answer_type = [d['answer_type_1'], d['answer_type_2'], d['answer_type_3'], d['answer_type_4'], d['answer_type_5']];
            probe_units = [d['units_1'], d['units_2'], d['units_3'], d['units_4'], d['units_5']];
            range.setMax(d['answer_max_1']);
            range.setMin(d['answer_min_1']);
            range.setData(d['units_1']);
            $("#probe_question_label").text('Question 1');
            $("#probe_name_edit").val(d['name']);
            $("#probe_description").val(d['description']);
            $("#probe_question_title").val(d['question_title_1']);
            $("#probe_question_en").val(d['question_en_1']);
            $("#probe_question_es").val(d['question_es_1']);
            $("#probe_question_unit").val(d['question_unit_1']);
            $("#probe_question_min").val(d['answer_min_1']);
            $("#probe_question_max").val(d['answer_max_1']);
            $("#probe_question_answer_type").val(d['answer_type_1']);
            //$("#view_probes").css('display', 'none');
            $("#manage_user_probe").css("display", "none");
            $("#add_probe").css('display', 'block');
            $('canvas').css('box-shadow', '0px 0px 0px #FFF');
        });
    });
    $("#launch_probes_button").on('click', function()
    {
		probe_dialog.dialog('close');
        $.post('launch_probe.php', {doc: $("#MEDID").val(), pat: selected_patient}, function(data, status)
        {
            console.log(data);
            if(data == '1')
            {
                swal({title: "Sent", text: "The probe has been sent successfully.", type: "success", confirmButtonColor: "#22AEFF"});
            }
            else if(data == '-1')
            {
                swal("Unable To Send Probe", "This probe has not been activated.", "error");
            }
            else
            {
                swal("Unable To Send Probe", "This probe has not been properly defined.\n Please make sure that all the fields have been filled out and try again.", "error");
            }
        });
    });
    $('#add_probe_button').live('click', function()
    {
        selected_probe = -1;
        probe_question_title = ['', '', '', '', ''];
        probe_question_en = ['', '', '', '', ''];
        probe_question_es = ['', '', '', '', ''];
        probe_question_unit = ['', '', '', '', ''];
        probe_min = [1, 1, 1, 1, 1];
        probe_max = [5, 5, 5, 5, 5];
        probe_answer_type = [1, 1, 1, 1, 1];
        probe_units = [[], [], [], [], []];
        $("#probe_question_label").text('Question 1');
        $("#probe_name_edit").val('');
        $("#probe_description").val('');
        $("#probe_question_title").val('');
        $("#probe_question_en").val('');
        $("#probe_question_es").val('');
        $("#probe_question_unit").val('');
        $("#probe_question_min").val('1');
        $("#probe_question_max").val('5');
        range.setMin(1);
        range.setMax(5);
        range.setData([]);
        $("#probe_question_answer_type").val(1);
        $("#manage_user_probe").css('display', 'none');
        $("#add_probe").css('display', 'block');
    });
    $('#add_probe_button_back').live('click', function()
    {
        $("#manage_user_probe").css("display", "block");
        $("#view_probes").css("display", "none");
    });
    
    var range = $("#probe_range_selector").H2MRange({width: 572, min: 0, max: 100, data: [{value: 40, label: 'Bad'}, {value: 100, label: 'Good'}]});
    $('body').on('click', 'button[id^="probes_edit_button_"]', function()
    {
        
        var probe_id = $(this).attr("id").split("_")[3];
        selected_probe = probe_id;
        $.post("get_probe_protocols.php", {id: probe_id}, function(data, status)
        {
            console.log(data);
            var d = JSON.parse(data);
            probe_question_title = [d['question_title_1'], d['question_title_2'], d['question_title_3'], d['question_title_4'], d['question_title_5']];
            probe_question_en = [d['question_en_1'], d['question_en_2'], d['question_en_3'], d['question_en_4'], d['question_en_5']];
            probe_question_es = [d['question_es_1'], d['question_es_2'], d['question_es_3'], d['question_es_4'], d['question_es_5']];
            probe_question_unit = [d['question_unit_1'], d['question_unit_2'], d['question_unit_3'], d['question_unit_4'], d['question_unit_5']];
            probe_min = [d['answer_min_1'], d['answer_min_2'], d['answer_min_3'], d['answer_min_4'], d['answer_min_5']];
            probe_max = [d['answer_max_1'], d['answer_max_2'], d['answer_max_3'], d['answer_max_4'], d['answer_max_5']];
            probe_answer_type = [d['answer_type_1'], d['answer_type_2'], d['answer_type_3'], d['answer_type_4'], d['answer_type_5']];
            probe_units = [d['units_1'], d['units_2'], d['units_3'], d['units_4'], d['units_5']];
            range.setMax(d['answer_max_1']);
            range.setMin(d['answer_min_1']);
            range.setData(d['units_1']);
            $("#probe_question_label").text('Question 1');
            $("#probe_name_edit").val(d['name']);
            $("#probe_description").val(d['description']);
            $("#probe_question_title").val(d['question_title_1']);
            $("#probe_question_en").val(d['question_en_1']);
            $("#probe_question_es").val(d['question_es_1']);
            $("#probe_question_unit").val(d['question_unit_1']);
            $("#probe_question_min").val(d['answer_min_1']);
            $("#probe_question_max").val(d['answer_max_1']);
            $("#probe_question_answer_type").val(d['answer_type_1']);
            $("#view_probes").css('display', 'none');
            $("#add_probe").css('display', 'block');
        });
    });
    $('#probe_delete_button').live('click', function()
    {
        var probe_id = $("#probe_protocols").val();
        if(probe_id > 0)
        {
            swal({   title: "Are you sure?",   text: "Are you sure you want to delete this probe? This action cannot be undone.",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes",   cancelButtonText: "No",   closeOnConfirm: false,   closeOnCancel: false }, function(isConfirm)
            {   
                if (isConfirm) 
                {     
                    $.post("delete_probe_protocol.php", {id: probe_id}, function(d, status)
                    {
                        swal("Deleted!", "The probe has been deleted successfully.", "success");
                        load_probe_protocols();
                    });
                } 
                else 
                {     
                    swal("Cancelled", "Deletion Cancelled", "error");   
                } 
            });
        }
    });
    $("#probe_question_next").on('click', function()
    {
        if(probe_question < 5)
        {
            probe_units[probe_question - 1] = range.get();
            probe_question += 1;
            $("#probe_question_title").val(probe_question_title[probe_question - 1]);
            $("#probe_question_en").val(probe_question_en[probe_question - 1]);
            $("#probe_question_es").val(probe_question_es[probe_question - 1]);
            $("#probe_question_unit").val(probe_question_unit[probe_question - 1]);
            $("#probe_question_min").val(probe_min[probe_question - 1]);
            $("#probe_question_max").val(probe_max[probe_question - 1]);
            $("#probe_question_answer_type").val(probe_answer_type[probe_question - 1]);
            $("#probe_question_label").text("Question "+probe_question);
            range.setMax(probe_max[probe_question - 1]);
            range.setMin(probe_min[probe_question - 1]);
            range.setData(probe_units[probe_question - 1]);
        }
    });
    $("#probe_question_previous").on('click', function()
    {
        if(probe_question > 1)
        {
            probe_units[probe_question - 1] = range.get();
            probe_question -= 1;
            $("#probe_question_title").val(probe_question_title[probe_question - 1]);
            $("#probe_question_en").val(probe_question_en[probe_question - 1]);
            $("#probe_question_es").val(probe_question_es[probe_question - 1]);
            $("#probe_question_unit").val(probe_question_unit[probe_question - 1]);
            $("#probe_question_min").val(probe_min[probe_question - 1]);
            $("#probe_question_max").val(probe_max[probe_question - 1]);
            $("#probe_question_answer_type").val(probe_answer_type[probe_question - 1]);
            $("#probe_question_label").text("Question "+probe_question);
            range.setMax(probe_max[probe_question - 1]);
            range.setMin(probe_min[probe_question - 1]);
            range.setData(probe_units[probe_question - 1]);
        }
    });
    $("#probe_question_title").on('input', function()
    {
        setTimeout(function()
        {
            probe_question_title[probe_question - 1] = $("#probe_question_title").val();
        }, 100);
    });
    $("#probe_question_en").on('input', function()
    {
        setTimeout(function()
        {
            probe_question_en[probe_question - 1] = $("#probe_question_en").val();
        }, 100);
    });
    $("#probe_question_es").on('input', function()
    {
        setTimeout(function()
        {
            probe_question_es[probe_question - 1] = $("#probe_question_es").val();
        }, 100);
    });
    $("#probe_question_unit").on('input', function()
    {
        setTimeout(function()
        {
            probe_question_unit[probe_question - 1] = $("#probe_question_unit").val();
        }, 100);
    });
    $("#probe_question_min").on('change', function()
    {
        probe_min[probe_question - 1] = $(this).val();
        range.setMin($(this).val());
    });
    $("#probe_question_max").on('change', function()
    {
        probe_max[probe_question - 1] = $(this).val();
        range.setMax($(this).val());
    });
    $("#probe_question_answer_type").on('change', function()
    {
        probe_answer_type[probe_question - 1] = $(this).val();
    });
    $("#probe_add").on('click', function()
    {
        probe_units[probe_question - 1] = range.get();
        var e = 0;
        if(selected_probe > 0)
        {
            e = 1;
        }
        if($("#probe_name_edit").val().length == 0)
            swal("Error", "Please add a name for the probe", "error");
        else if($("#probe_description").val().length == 0)
            swal("Error", "Please add a description for the probe", "error");
        else if(probe_question_en[0].length == 0)
            swal("Error", "Please add at least one question for the probe", "error");
        else if(probe_question_title[0].length == 0)
            swal("Error", "Please add at least one question for the probe", "error");
        else
        {
            var cont = true;
            for(var i = 0; i < 5; i++)
            {
                if((probe_min[i] < 0 || probe_min[i] > 9 || probe_max[i] < 0 || probe_max[i] > 9) && probe_answer_type[i] == 1)
                {
                    swal("Error", "Probe question " + (i + 1) + " has min and max values that are outside the range of the answer type. Please adjust the values and try again.", "error");
                    cont = false;
                }
            }
            if(cont)
            {
                console.log(probe_units);
                $.post("add_probe_protocol.php", {doctor: $("#MEDID").val(), name: $("#probe_name_edit").val(), description: $("#probe_description").val(), questions_en: probe_question_en, questions_es: probe_question_es, min: probe_min, max: probe_max, titles: probe_question_title, question_units: probe_question_unit, answer_type: probe_answer_type, units: probe_units, edit: e, probe_id: selected_probe}, function(data, status)
                {
                    load_probe_protocols();
                    //$("#view_probes").css('display', 'block');
                    //$("#add_probe").css('display', 'none');
                    $("#manage_user_probe").css("display", "block");
                    $("#add_probe").css('display', 'none');
                });
            }
        }
    });
    $("#probe_cancel").on('click', function()
    {
        //$("#view_probes").css('display', 'block');
        //$("#add_probe").css('display', 'none');
        $("#manage_user_probe").css("display", "block");
        $("#add_probe").css('display', 'none');
    });

    
    if($("#CHECKOUT").val() == 1)
    {
        var ask_window = $("#checkout_ask_window").dialog({bgiframe: true, width: 400, height: 180, autoOpen: true, modal: true});
        $("#checkout_ask_yes_button").on("click", function()
        {
			//swal({   title: "This member is eligible for a health plan",   text: "Your patient is eligible for plan: \n\n Catapult Assist Diabetes Coaching \n\n because the system found the following inclusion criteria: \n\n Basal Glucose Level higher than 150 mg/dL (found 187 mg/dL)",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, apply plan!",   closeOnConfirm: false }, function(){   swal("Applied!", "This plan has been applied to this member.", "success"); });
            $('canvas').css('background-color', '#FFF');
            $('canvas').css('box-shadow', '0px 0px 0px #FFF');
            connectMemberSelected = $("#USERID").val();
            selected_patient = $("#USERID").val();
            console.log('share_reports.php?Usuario='+connectMemberSelected);
            $("#share_files_container2").html('<iframe id="share_reports" src="share_reports.php?Usuario='+connectMemberSelected+'" style="border: 0px solid #FFF; width: 100%; height: 490px;" />');
            var grant_access = $("#GRANTACCESS").val();
            if(grant_access == 'CATA'){
                $("#connectMemberStep0").show();
                $("#connectMemberStep2").hide();
                $("#selectAppointmentTime").timepicker({'step': 15});
            }else{
                $("#connectMemberStep2").show();
                $("#connectMemberStep0").hide();
            }

            $("#connectMemberStep1").hide();
            $("#connectMemberStep3").hide();
            $("#connectMemberStep4").hide();
            
            $.post("get_user_info.php", {id: selected_patient}, function(data, status)
            {
                var info = JSON.parse(data);

                $("#connectMemberEmail").val(info['email']);
                $("#connectMemberPhone").val('+'+info['phone']);
                if(info.hasOwnProperty('cards') && info['cards'].length > 0)
                {
                    load_credit_cards(info['cards']);
                }
            });
            connectMemberSubscribeButtonClicked = false;
            $("#connectMemberSubscribeButton").css('background-color', '#FFF');
            $("#probeToggleLabel").css("opacity", "0.5");
            $("#pTL").css("opacity", "0.5");
            $("#probeToggle").attr('disabled', 'disabled');
            
            $.post("get_user_info.php", {id: connectMemberSelected}, function(data, status)
            {
                var info = JSON.parse(data);
                
                $("#connectMemberEmail").val(info['email']);
                $("#connectMemberPhone").val('+'+info['phone']);
                if(info.hasOwnProperty('cards') && info['cards'].length > 0)
                {
                    load_credit_cards(info['cards']);
                }
            });
            connectMemberDialog.dialog('open');
            ask_window.dialog("close");
        });
        $("#checkout_ask_no_button").on("click", function()
        {
            ask_window.dialog("close");
        });
    }
    


    $('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });
    if(telemed_on){
        var telemedOn = true;
    } else{
        var telemedOn = false;
    }

    var telemedValue = telemed_value;

	
    var phoneTelemed = $("#phoneTelemed").dialog({bigframe: true, width: 550, height: 200, autoOpen: false});
    var videoTelemed = $("#videoTelemed").dialog({bigframe: true, /*open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog).hide(); },*/ width: 550, height: 440, minWidth: 550, minHeight: 440, sticky: false, modal: false, autoOpen: false, dialogClass: 'telemedModalClass', position: {my: "left+70", of: "#NombreComp"}, resize: function( event, ui ) 
                                                  {
                                                      $("#remoteVideo").children("video").each(function()
                                                                                               {
                                                          $(this).css("height", "100%");
                                                          $(this).css("width", "100%");
                                                      });

                                                  }, 
                                                  beforeClose: function( event, ui ) 
                                                  {
                                                      $.post("update_webrtc.php", {doc_id: $("#MEDID").val(), status: 0}, function(){});
                                                  }});

    if(telemedOn)
    {
        console.log("true");
        // the doctor has accepted a consultation with this user, show telemedicine section
        $.post("retrieveLatestConsultation.php", {doctor: $("#MEDID").val(), patient: $("#USERID").val()}, function(data, status)
               {
            var consult = JSON.parse(data);
            var desc = consult.description;
            console.log(data);
            if(desc != null && desc.length > 0)
            {
                $("#telemedicine_user_notes").text('"'+desc+'"');
                $("#telemedicine_user_notes").css('display', 'block');
                $("#telemedicine_user_notes_label").css('display', 'block');
            }
            else
            {
                $("#telemedicine_user_notes").css('display', 'none');
                $("#telemedicine_user_notes_label").css('display', 'none');
            }
            $("#telemedicine_start").css("display", "block");
        });
    }
    $("#start_telemedicine").on('click', function()
                                {
        //see if it is a phone consultation
        if (telemedOn && telemedValue == 2) 
        {
            //open the phone dialog
            phoneTelemed.show();
            phoneTelemed.dialog('open');


            var redTimer = 600000;
            var startTimer = start_time*1000;
            if (startTimer > redTimer) {
                redTimer=0;   
            } else {
                redTimer = redTimer-startTimer;   
            }    

            console.log(redTimer);
            setTimeout(function() {
                // turns red after 10 minutes
                $( ".timer" ).css( "border", "3px solid red" );
                $( ".timer" ).css( "color", "red" );
            }, redTimer);

            $('#startButton1').trigger('click');

        }    
        // or a video consultation
        else if(telemedOn && telemedValue == 1)

        {
			var teleurl = domain;    //Removed the hardcoded url 'http://dev.health2.me:8888' - Debraj
            var parseURL = document.createElement('a');
            parseURL.href = teleurl;
            teleurl = parseURL.protocol + '//' + parseURL.hostname + ':8888';
            
            $.post("https://api.xirsys.com/getIceServers", 
            {
                ident: "bombartier",
                secret: "4a213061-f2b0-4ec9-bd2a-0c5e0ce0c2e1",
                domain: "health2.me",
                application: "default",
                room: "default",
                secure: 1
            }, function(data, status)
            {
                var info = JSON.parse(data);
                var peerConnectionConfig = info.d;
                //var peerConnectionConfig = {"iceServers":[{"url":"stun:turn2.xirsys.com"},{"username":"24edf7ba-ed50-4a30-9e3c-2ad42780ac1b","url":"turn:turn2.xirsys.com:443?transport=udp","credential":"b62f6f45-db7a-45f7-a517-9c0e539ac82d"},{"username":"24edf7ba-ed50-4a30-9e3c-2ad42780ac1b","url":"turn:turn2.xirsys.com:443?transport=tcp","credential":"b62f6f45-db7a-45f7-a517-9c0e539ac82d"}]};
                //console.log(info);
                var webrtc = new SimpleWebRTC({localVideoEl: 'localVideo',remoteVideosEl: 'remoteVideo', url: teleurl, autoRequestMedia: true, peerConnectionConfig: peerConnectionConfig});

                webrtc.on('readyToCall', function () 
                {
                    $(window).on("unload", function () 
                                 {
                        $.post("update_webrtc.php", {doc_id: $("#MEDID").val(), status: 0}, function(){});

                    });
                    setTimeout(function() {
                        // turns red after 10 minutes
                        $( ".timer" ).css( "border", "3px solid red" );
                        $( ".timer" ).css( "color", "red" );
                    }, 600000);


                    $('#startButton').trigger('click');

                    //video_recorder = RecordRTC(webrtc.webrtc.localStream, video_record_options);
                    //audio_recorder = RecordRTC(webrtc.webrtc.localStream, audio_record_options);
                    webrtc.joinRoom('health2me_room_' + $("#MEDID").val() + '_' + $("#USERID").val());
                    /*
                    if(!webrtc.connection.DetectRTC.hasMicrophone && webrtc.connection.DetectRTC.hasWebcam) {
                        alert('It appears that you do not have a microphone connected.  Please connect a microphone to your computer and try again.  If this problem persists, your microphone may be being used by another application.  Restart your computer to reset your hardware.');
                    }

                    if(webrtc.connection.DetectRTC.hasMicrophone && !webrtc.connection.DetectRTC.hasWebcam) {
                        alert('It appears that you do not have a web camera connected.  Please connect a web camera to your computer and try again.  If this problem persists, your web camera may be being used by another application.  Restart your computer to reset your hardware.');
                    }

                    if(!webrtc.connection.DetectRTC.hasMicrophone && !webrtc.connection.DetectRTC.hasWebcam) {
                        alert('It appears that you do not have a web camera or microphone connected.  Please connect a web camera and microphone to your computer and try again.  If this problem persists, your web camera and microphone may be being used by another application.  Restart your computer to reset your hardware.');
                    }

                    /*if(!DetectRTC.load) {
                        alert('It appears that your webcam and microphone are being used by another application.  You can restart your computer to reset your computers hardware and try again.');
                        }*/

                    $("#remoteVideo").css("height", ($("#videoTelemed").css("width") * 0.6538461538 * 0.68).toString() + "px");
                    $("#remoteVideo").css("width", ($("#videoTelemed").css("width") * 0.68).toString() + "px");
                    $("#telemed_notes").attr("rows", (Math.floor($("#videoTelemed").css("width") * 0.6538461538 * 0.68) / 22));
                    var interval = setInterval(function()
                                               {

                        $("#remoteVideo").children("video").each(function()
                                                                 {
                            $(this).css("height", "100%");
                            $(this).css("width", "100%");
                            clearInterval(interval);
                        });
                    }, 200);
                    $.post("update_webrtc.php", {doc_id: $("#MEDID").val(), pat_id: $("#USERID").val(), status: 1, add_to_recent_doctors: 1}, function(){});
                    setInterval(function(){$.post("update_webrtc.php", {doc_id: $("#MEDID").val(), update_lastseen: true}, function(data, status){console.log(data);});}, 30000);
                    $("#remoteVideo").children("video").each(function()
                                                             {
                        $(this).css("height", "100%");
                        $(this).css("width", "100%");
                    });
                    $('a').click(function()
                                 {
                        $.post("update_webrtc.php", {doc_id: $("#MEDID").val(), status: 0}, function(){});
                    });
                });
            });

            




            videoTelemed.dialog('open');
            $(".telemedModalClass").children(".ui-dialog-titlebar").append('<span id="icon_float" class="icon-unlock" style="float: right; margin-right: 15px; margin-top: 3px; cursor: pointer;"></span>');
            $("#icon_float").on('click', function()
                                {
                if($(this).hasClass("icon-unlock"))
                {
                    videoTelemed.dialog({sticky: true});
                    $(this).removeClass("icon-unlock").addClass("icon-lock");
                }
                else
                {
                    videoTelemed.dialog({sticky: false});
                    $(this).removeClass("icon-lock").addClass("icon-unlock");
                }

            });

        }
    });

    //$("#progress-bar").hide();
    //$("div[class='bar']").width(100);

    //$('.tooltip').tooltipster();
    //$('.tooltip').
    //$('.tooltip').show();
    $("#tabsWithStyle").hide();
    //$("#referral_stage").hide();
    var ElementDOM ='#StreamContainerALL';
    var EntryTypegroup = 0 ;
    var Usuario = $('#userId').val();
    var MedID =$('#MEDID').val();
    var PrevElementDOM='';
    /*var queUrl ='CreateReportStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
      	//alert (queUrl);
      	$(ElementDOM).load(queUrl);
    	$(ElementDOM).trigger('update');*/

    //Changes for the multi-referral doctors screen
    var ismultireferral=0;
    var multireferral_num=parseInt(num_multireferral);
    //alert(multireferral_num);
    var referral_state_array=new Array();
    for (var i=0;i<multireferral_num;i++)
    { 
        //alert(i);
        ismultireferral=1;
        referral_state_array[i]=parseInt($('#referral_state'+i).val());
        //alert(referral_state_array[i]);
    }

    var referral_state;
    if(ismultireferral==0){
        referral_state = parseInt($('#referral_state').val());
        //alert(referral_state);
        if (isNaN(referral_state)){
            referral_state=0;
            //alert('The referral stages functionality is not working. Please contact Health2me!');
        }
    }




    $('.PrintHighlighted').live('click',function()	
                                {
        var myClass = $(this).attr("id");
        //alert('clicked' + myClass);
        //$("#H2M_Spin_Stream_Print").show();
        //setTimeout($("#H2M_Spin_Stream_Print").show(), 3000);
        var link = 'mergePDF.php?referralID='+myClass;
        $.ajax(
            {
                url: link,
                dataType: "html",
                async: true,
                success: function(data)
                {
                    //alert('Data Fetched');
                    RecTipo = data;
                    var fileURL = 'temp/' + $('#MEDID').val() + '/'+myClass+'.pdf';
                    //alert(fileURL);
                    if(checkFileExists(fileURL)==true)
                    {
                        //alert('done');

                        pages = [fileURL];

                        for (var i = 0; i < pages.length; i++) 
                        {
                            oWindow[i] = window.open(pages[i]);
                            /*var myWindow = window.open(pages[i]);;
						oWindow[i] = myWindow;
						$(myWindow.document).ready(function() {
							//alert('Window is open');
							//PrintAll();
						});*/
                        }
                        setTimeout("PrintAll()", 5000);
                        $("#H2M_Spin_Stream_Print").hide();	
                    }
                    else
                    {
                        displaynotification('Error','No highlighted reports to print');
                        $("#H2M_Spin_Stream_Print").hide();	
                    }
                },
                error:function(data){
                    displaynotification('Error','Error Printing Reports');
                    $("#H2M_Spin_Stream_Print").hide();	
                }
            });
        //$("#H2M_Spin_Stream_Print").hide();	

    });

    function checkFileExists(FileUrl)
    {
        var exists=false;
        $.ajax({
            url: FileUrl, 
            async: false,
            success: function(data){
                //alert('found');
                exists=true;
                //return true;
            },
            error: function(data){
                //alert('notfound');
                //return false;
            },
        })
        return exists;
    }

    $('#AddEvolution').live('click',function()	
    {
        $("#evolution_modal").dialog("close");
        var userid = get_idusu;
        var date = $('#evolution_date').val();
        var text = $('#evolution_text').val();

        if(date == '')
        {
            alert('Enter Valid Date');
            return;
        }

        if(text == '')
        {
            alert('Enter some text');
            return;
        }

        var cadena = 'add_evolutions.php?userid='+userid+'&date='+date+'&text='+text;
        
        var RecTipo = LanzaAjax (cadena);
        //alert(cadena);
        if(RecTipo=='success')
        {
            var cadena = 'EvolutionPDF.php?idusu='+userid;
            //alert(cadena);
            var RecTipo = LanzaAjax (cadena);
            window.location.reload();


        }
        else
        {
            alert("Error Adding Data");
        }
    });
    $('#CloseEvolution').live('click', function(e)
    {
        e.preventDefault();
        $("#evolution_modal").dialog("close");
    });



    //Start of ajax call for adding notes temporarily in temp directory using AddNote button    
    $('#AddNote').live('click',function()	
                       {
        var userId = get_idusu;
        var doctorId = med_id;
        // Commented out by Pallab as date is no longer required in notes pdf var date = $('#note_date').val();
        var findings = '"'+$('#note_findings').val()+'"'; //changed from note_text to note_findings
        var recommendations = '"'+$('#note_recommendations').val()+'"';

        $.post("storePatientNotesTemp.php",{doctorId: doctorId,findings: findings,recommendations: recommendations},function(data,success)
               {
            console.log(data);
        });


    });	
    //End of ajax call for adding notes using AddNote button	

    $(".nav-container").click(function()
                              {
        //alert("here");
        /*var left = 0;
		setTimeout(function ()
			{
				left = parseInt( $('.slider-container').css("left").replace(/px/,""));
				//alert(left);
				var sliderIndex;
				if(left>100)
				sliderIndex=-1;
				else
				sliderIndex=0;
				while(left<100)
				{
					if(left>100)
					 break;
					else 
					 {
						left+=1000;
						sliderIndex+=1;
					 }
				}
				if(sliderIndex>0)
				{
					var count =1;
					$( ".note2" ).each(function() 
					{
						// alert("count: "+count);
						if(count==sliderIndex)
						{
						  id = $( this ).attr('id');
						  //alert("note id: "+id);
						}  
						count++; 
					});
					//$('.note2').trigger('click',[id,false]);
				}
			},2000);
		//$('#'+id).scrollIntoView();
		//alert(left);*/
        $('#AreaConten').hide();
        $('#AreaTipo').innerHtml="";
        $('#AreaClas').innerHTML="";
    });



    /*$(".marker").live('click',function(event)
                      {
        //console.log("event.originalEvent:" +event.originalEvent);
        if( event.originalEvent !== undefined)
        {
            var divIndex = $("#"+this.id).index();
            // alert("divIndex: "+divIndex);
            var id=0;
            var count =1;
            $( ".note2" ).each(function() 
                               {
                // alert("count: "+count);
                if(count==divIndex)
                {
                    id = $( this ).attr('id');
                    //alert("note id: "+id);
                }  
                count++; 
            });
            //$('.note2').trigger('click',[id,false]);
            //alert("scroll id:"+id);

            //document.getElementById(id).focus();
            // $('html, body').animate({
            // scrollTop: $('#'+id).offset().top
            // }, 1000);
            // $('#tabsWithStyle').animate({
            // scrollTop: $('#'+id).offset().top
            // },{ duration:800,complete:function(){window.location.hash = '#'+id;}});
            location.href="#"+id;
            // $('#tabsWithStyle').animate({
            // scrollTop: $('#'+id).offset().top
            // },800);
            var windowHeight = $(window).height();
            var elementHeight = $("#"+id).height();

            var elementPosition = $("#"+id).position();
            var elementTop = elementPosition.top;
            var toScroll = (windowHeight / 2)-(elementHeight/30);
            window.scroll(0,elementTop-toScroll);
            //$("#"+id).css('border','solid 1px red');
            $("#"+id).effect('pulsate');
            $('#AreaConten').hide();
            $('#AreaTipo').html("");
            $('#AreaClas').html("");
        }			
    });*/

    // $('.timenav').children('.content').on('click',function(event,cascade,id)
    // {
    // if(cascade==false)
    // {
    // $('.timenav').children('.content:nth-child('+(parseInt(id)+1)+')').click();
    // }
    // else
    // {
    // var divIndex = $("#"+this.id).index();
    // alert("divIndex: "+divIndex);
    // var id=0;
    // var count =1;
    // $( ".note2" ).each(function() 
    // {
    // alert("count: "+count);
    // if(count==divIndex)
    // {
    // id = $( this ).attr('id');
    // alert("note id: "+id);
    // }  
    // count++; 
    // });
    // $('.note2').trigger('click',[id,true]);
    // }
    // });

    $('#NewMES').live('click',function(){
        var queUrl ='getInboxMessageUNREAD.php?IdMED='+med_id+'&patient='+mem_id+'&sendingdoc='+otherdocid[i];		

        $.ajax(
            {
                url: queUrl,
                dataType: "json",
                async: false,
                success: function(data)
                {
                    //alert('Data Fetched');
                    NewMES = data.items;
                }
            });

        Pa = NewMES.length;

        var n=0;
        MesTimeline = '';
        while (n < Pa)
        {
            var queini = NewMES[n].MessageINIT.charCodeAt(0); 
            var whatcolor = 'RGB()';
            switch (true)
            {
                case (queini == 0):
                    result = "Equals Zero.";
                    break;
                case ((queini >= 64) && (queini <= 78)):		
                    whatcolor = '#0066CC';
                    break;
                case ((queini >= 79) && (queini <= 100)):		
                    whatcolor = '#00CC66';
                    break;
                case ((queini >= 101) ):		
                    whatcolor = '#663300';
                    break;
            }
            var formatDateP = new Date(NewMES[n].MessageDATE);
            var formatDate = formatDateP.format('dddd mmmm dd, yyyy');
            MesTimeline = MesTimeline + '<div style="float:left; width:100%; margin-top:15px; ">'; 
            MesTimeline = MesTimeline + '<div style="float:left; width:10%; ">'; 
            //MesTimeline = MesTimeline + NewMES[n].MessageID;			
            if (n==2) 
            {
                MesTimeline = MesTimeline + '<img src="images/PersonalPicSample.jpeg" style="margin-left:0px; width: 40px; height: 40px; border:#cacaca;" class="img-circle">';
            }
            else
            {
                MesTimeline = MesTimeline + '<div class="LetterCircleON" style="width:40px; height:40px; font-size:12px; margin-left:0px; background-color:' + whatcolor + ';"><p style="margin:0px; padding:0px; margin-top:13px;">'+ NewMES[n].MessageINIT +'</p></div>	';
            }	
            MesTimeline = MesTimeline + '</div>'; 

            MesTimeline = MesTimeline + '<div style="float:left; width:87%; margin-left:3%; border-bottom:thin dotted #cacaca;">'; 
            MesTimeline = MesTimeline + '<p style="font-weight:bold; font-size:14px; ">' +  NewMES[n].MessageSEND + '<span style="color:#cacaca; float:right; font-size:12px; font-weight:normal;">    ' + formatDate + '</span> ' + '</p>' ;
            MesTimeline = MesTimeline +  '<p style="color:grey; font-weight:bold; font-size:12px; margin-bottom:-5px; margin-top:-10px;">' + NewMES[n].MessageSUBJ + '</p>';
            MesTimeline = MesTimeline +  '<p style="color:grey; margin-bottom:0px; ">' + NewMES[n].MessageCONT.replace(/sp0e/gi," ") + '</p>';

            var splitted = NewMES[n].MessageRIDS.split(" ");
            NumReports = splitted.length - 1;
            if (NumReports>0)
            {
                MesTimeline = MesTimeline + '<div style="margin:0 auto; width:95%; border:solid #cacaca; border-radius:5px; height:80px;">'; 
                //MesTimeline = MesTimeline + NumReports + ' Reports.  ';
                var rn = 0;
                while (rn < NumReports)
                {
                    var cadena = 'DecryptFileId.php?reportid='+splitted[rn]+'&queMed='+med_id;
                    var RecTipo = LanzaAjax (cadena);
                    var thumbnail = RecTipo.substr(0,RecTipo.indexOf("."))+'.png';
                    MesTimeline = MesTimeline + '<img class="ThumbTwitt" id="'+splitted[rn]+'" style="height:70px; margin:5px; border:solid 1px #cacaca;" src="temp/'+med_id+'/PackagesTH_Encrypted/'+thumbnail+'">';
                    rn++;
                }
                MesTimeline = MesTimeline + '</div>'; 
            }	

            MesTimeline = MesTimeline +  '<p style="color:#cacaca; font-size:10px; margin-left:50px;"> <span style="margin-right:15px;"><i style="font-size:10px; margin-right:5px;" class="icon-mail-reply"></i>Reply</span><span style="margin-right:15px;"><i style="font-size:10px; margin-right:5px;" class="icon-arrow-right"></i>Forward</span><span style="margin-right:15px;"><i style="font-size:10px; margin-right:5px;" class="icon-bookmark"></i>Mark</span></p>';
            MesTimeline = MesTimeline + '</div>'; 


            MesTimeline = MesTimeline + '</div>'; 

            n++;
        }
        $('#NewMES').html(MesTimeline);
        //$('#TextMES').html(MesTimeline);

    });

    $(".ThumbTwitt").live('click',function() {
        var myClass = $(this).attr("id");
        var cadena = 'DecryptFileId.php?reportid='+myClass+'&queMed='+med_id;
        var RecTipo = LanzaAjax (cadena);
        //var thumbnail = RecTipo.substr(0,RecTipo.indexOf("."))+'.png';
        var thumbnail = RecTipo;
        var src='temp/'+med_id+'/Packages_Encrypted/'+thumbnail;		
        $('#ZoomedIframe').attr('src',src);				
        $('#ZoomedIframe').css('display','inline');				
        //alert (src);					
    });

    $('body', $('#ZoomedIframe').contents()).click(function(event) {
        $('#ZoomedIframe').css('display','none');
    });


    $("#TLineScrollDown").live('click',function() {



        var elem = document.getElementById("TLineScrollDown");
        elem.style.display = 'none';

        var elem = document.getElementById("TLineScrollUp");
        elem.style.display = 'block';



        if(timeline_loaded==true)
        {
            $("#timeline-box").slideDown("slow");
            return;
        }
        loadTimeline();
        $("#timeline-box").slideDown("slow");
    });

    $('#TLineScrollUp').live('click',function(){
        var elem = document.getElementById("TLineScrollDown");
        elem.style.display = 'block';

        var elem = document.getElementById("TLineScrollUp");
        elem.style.display = 'none';

        $("#timeline-box").slideUp("slow");

    });


    function loadTimeline(){

        //$('#H2M_Timeline_Spin_Stream').show();
        //alert('displayed');
        /*var t = setTimeout(function(){
					$("#H2M_Timeline_Spin_Stream").hide();
					 },1000);
		*/


        var usuario = get_idusu;
        if(med_id > 0){
            var IdMed = med_id;
        }else{
            var IdMed = get_idusu;
        }
        //$('#H2M_Timeline_Spin_Stream').show();		
        //var DirURL = 'Timeline.php?usuario='+usuario+'&medid='+IdMed;
        var isDoctor = 1;
        if(usuario == IdMed)
        {
            isDoctor = 0;
        }
        var DirURL = 'TimelineNEW.php?usuario='+usuario+'&medid='+IdMed+'&isdoctor='+isDoctor;
console.log(usuario+IdMed+isDoctor);
        $.ajax(
            {
                url: DirURL,
                dataType: "html",
                complete: function(){ 
                },
                beforeSend: function(msg){

                },		
                success: function(data) 
                {

                    //alert('produced');
                    createTimeline('jsondata2.txt');
                    timeline_loaded = true;
                    //$('#H2M_Timeline_Spin_Stream').hide();

                }
            });


    }


    function createTimeline(data){
        createStoryJS({

            width: "100%",
            debug : false,
            height: "100%",
            source: data,
            type: 'timeline',
            embed_id: 'timeline-embed',
            start_at_end:true, 
            //hash_bookmark:true
        });
    }

    $("#Clases").live('change',function() {
        var doble = $(this).val();
        var newVal = String(doble).substr(0,1);
        var newVal2 = String(doble).substr(1,2);
        $("#SelecERU").val(newVal);		   
        $("#SelecEvento").val(newVal2);		   
        //do something
    });

    $("#Tipos").live('change',function() {
        var newVal = $(this).val();
        $("#SelecTipo").val(newVal);		   
        //alert (newVal);
        //do something
    });   

    $("#BotonMod").hide();

    $(".CFILAMODAL").live('click',function() {
		var geocoder;
		var map;
		var address;
        var ipadd=$('td', this).eq(3).text();
		console.log("IP Address : "+ipadd);
		//ip_address = "80.94.68.10";
		google.maps.event.addDomListener(window, 'load', 			 initialize_map(ipadd));
        //alert(ipadd);
        /*var id=$(this).attr("id");
		var url = 'map.php?ipaddress='+ipadd+'&id='+id;
		var RecTipo = LanzaAjax (url);

		var serviceUrl = '<?php echo $domain;?>/getReportLocation.php?id='+id;
		getreportLocation(serviceUrl);
		alert(geolocation[0].latitude);	

		var map = new GMap(document.getElementById("map"));
		var point = new GPoint(29.7397,-95.8302);
		map.centerAndZoom(point, 3);
		var marker = new GMarker(point);
		map.addOverlay(marker);
		google.maps.event.trigger(map, 'resize');
			*/
        $("#header-modalMap").html('<div id="googleMap" style="width:500px;height:380px;"></div><div style="text-align:center; margin-top:10px; width:100%"><button id="close-map" type="button" >Close</button></div>')	;	
		
        //$("#header-modalMap").load("maps.php?ipaddress="+ipadd);
        $('#BotonModalMap').trigger('click');
        $('#BotonModalMap').show();
       

    });

    $('#close-map').live('click',function(){

        //$('#BotonModalMap').trigger('click');
        //$('#BotonModalMap').hide();
		$('#header-modalMap').hide();
    });
    $('#CloseModal22').live('click',function(){

        //$('#BotonModalMap').trigger('click');
        $('#BotonModalMap').hide();
    });

    $('#BotonModalMap').live('click',function(){
        e.preventDefault();
        $('#BotonModalMap').show();

    });


    $('#PrintImage').live('click',function(){
        //var uniqueID="";
        var path = $('#ImagenN').attr("src");
        var rawimage = "";
        var idpin;
        if(path==null){
            alert('Select a Report');
            return;
        }
        else{
            rawimage=rawimage+ path.substr(path.lastIndexOf("/")+1,path.length);
        }

        //window.open('printimage.php?path='+path+'&IdUs=<?php echo $IdUsu ?>&MedID=<?php echo $MedID;?>;','','left=200,width=900,height=700,resizable=0,scrollbars=1');
        myWindow.focus();

        window.print();
        //document.getElementById(#DropBoxID).style.display="block";
    }); 



    //Function to delete report
    $(".icon-trash").live('click',function() {
        //alert('clicked' + this.parentNode.parentNode.parentNode.getAttribute('id'));
        var idpin = this.parentNode.parentNode.parentNode.getAttribute('id');
        //alert("ID is " + idpin);

        var cadena = 'getReportStatus.php?IdPin='+idpin;

        var packetstatus = LanzaAjax (cadena);
        //alert(packetstatus);
        if(packetstatus==2)
        {
            alert('This report contains Patients Basic EMR Data. It cannot be deleted !');
            return;
        }
        else if(packetstatus==1)
        {
            alert('This report has already been deleted !');
            return;
        }
        else if(packetstatus==3)
        {
            var del=confirm("Are You sure you want to delete this report.");
            if(!del)
                //alert("permanently deleted");
                return;

            var cadena = 'deleteReports.php?IdPin='+idpin+'&state='+packetstatus;

            //alert(cadena);
            var RecTipo = LanzaAjax (cadena);
            //alert(RecTipo);
            var Content='report marked deleted';
            var VIEWIdUser = 0;
            var VIEWIdMed = $("#MEDID").val();
            var MEDIO = 0;
            var cadena = 'LogEvent.php?IDPIN='+idpin+'&Content='+Content+'&VIEWIdUser='+VIEWIdUser+'&VIEWIdMed='+VIEWIdMed+'&MEDIO='+MEDIO;
            var RecTipo = LanzaAjax (cadena);
            displaynotification('status','Report Deleted');
            window.location.reload();
        }



    });



    function fileExists(url) {
        if(url){
            var req = new XMLHttpRequest();
            req.open('GET', url, false);
            req.send();
            return req.status==200;
        } else {
            return false;
        }
    }


    $("#EvolutionToggle").live('click',function() {

        //var contenURL='Evolution/'+<?php echo $IdUsu ?>+'.pdf';
        var contenURL='Packages_Encrypted/'+get_idusu+'.pdf';
        var fileFound = fileExists(contenURL);

        if(fileFound)
        {
            var rawimage = get_idusu+'.pdf';
            var cadena = 'DecryptFile.php?rawimage='+rawimage+'&queMed='+med_id;
            var RecTipo = LanzaAjax (cadena);

            var filepath='temp/'+med_id+'/Packages_Encrypted/'+rawimage;
            var conten =  '<iframe id="ImagenN1" style="border:1px solid #666CCC" title="PDF" src="'+filepath+'" alt="File Not Found" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
            $('#AreaConten2').html(conten);			
            $("#modal_evolution_display").trigger('click');
        }
        else
        {
            var url='Evolution/DataNotFound.jpg';
            var conten =  '<iframe id="ImagenN1" style="border:1px solid #666CCC" title="PDF" src="'+url+'" alt="File Not Found" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
            $('#AreaConten2').html(conten);			
            $("#modal_evolution_display").trigger('click');
            //alert("No Evolution Data Found");
        }

    });


    var evolution_modal = $("#evolution_modal").dialog({width: 531, height: 284, autoOpen: false, resizable: false, modal: true});
    $("#EvolutionButton").on('click',function(){
        // set the date of the evolution date picker to today's date first
        var date = new Date();

        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();

        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;

        var today = year + "-" + month + "-" + day;       
        $("#evolution_date").attr("value", today);
        
        $("#evolution_modal").dialog("open");
    });

    //Adding code for popping up modal window for PhoneNotes button

    $("#PhoneNotes").on('click',function(){
        $("#modal_phoneNotes").trigger('click');
    });

    var summary_modal = $("#summary_modal").dialog({bigframe: true, width: 1050, height: 690, resize: false, modal: true, autoOpen: false, close:
     function() {
        $.post("SavePatientSummaryAsPDF_table.php",{IdUsu: $('#USERID').val()},function(data,success)
        {
            location.reload();
        });

        }
    });
    $("#Summary_Button").on('click',function(e){
        e.preventDefault();
        var myClass = $('#USERID').val();
        //alert('reach here');
        $("#summary_modal").html('<iframe src="medicalPassport.php?modal=1&IdUsu='+myClass+'" width="1000" height="660" scrolling="no" style="width:1000px;height:660px; margin: 0px; border: 0px solid #FFF; outline: 0px; padding: 0px; overflow: hidden;"></iframe>');

        summary_modal.dialog('open');             

    });
    if(open_modal == 1)
    {
        var myClass = $('#USERID').val();
        //alert('reach here');
        $("#summary_modal").html('<iframe src="medicalPassport.php?modal=1&IdUsu='+myClass+'" width="1000" height="660" scrolling="no" style="width:1000px;height:660px; margin: 0px; border: 0px solid #FFF; outline: 0px; padding: 0px; overflow: hidden;"></iframe>');

        summary_modal.dialog('open');
    }


    ///E-PRESCRIBING//////////////////////////////////////////////////////////////////////////////////////////////////////////
    var eprescribe_modal = $("#ePrescribe_Modal").dialog({bigframe: true, width: 1050, height: 690, resize: false, modal: true, autoOpen: false});
    $("#ePrescribe").on('click',function(e){
        e.preventDefault();
        var myClass = $('#USERID').val();
        var myDoc = $('#MEDID').val();
        var myNPI = $('#NPI').val();
        var myDEA = $('#DEA').val();



        if(myNPI == '' | myNPI == 0){
            $( "#add_credentials" ).dialog({
                autoOpen: true,
                resizable:false,
                height: 250,
                width: 520,
                modal: true,

            });

        }else{
            pullPracticesList();

            //alert('reach here');

            eprescribe_modal.dialog('open');             
        }
    });



    function pullPracticesList() {
        var myDoc = $('#MEDID').val();
        var DirURL = 'getLocationsList.php?docid='+myDoc+'&show=practice';
        $.ajax(
            {
                url: DirURL,
                dataType: "html",
                complete: function(){ 
                },		
                success: function(data) 
                {

                    //alert('produced');
                    $("#ePrescribe_Modal").html(data);

                }
            });
    }

    $("#create_location").on('click', function()
                             {
        var clinic_name = $('#cname').val();
        var address1_holder = $('#address1').val();
        var address2_holder = $('#address2').val();
        var city_holder = $('#city_holder').val();
        var state_holder = $('#state_holder').val();
        var zip_holder = $('#zip_holder').val();
        var phone_holder = $('#phone_holder').val();
        var fax_holder = $('#fax_holder').val();
        var myDoc = $('#MEDID').val();
        var practice_id = $('#id3').val();

        //VALIDATION/////////////
        if(clinic_name == ''){
            alert('You must enter the full name of your clinic you will be prescribing from.');
        }else if(address1_holder == ''){
            alert('You must enter the address of the clinic you will be prescribing from.');
        }else if(city_holder == ''){
            alert('You must enter the city of the clinic you will be prescribing from.');
        }else if(state_holder == '' | state_holder.length < 2){
            alert('You must enter the state of the clinic you will be prescribing from.');
        }else if(zip_holder == '' | zip_holder.length < 5){
            alert('You must enter the state of the clinic you will be prescribing from.');
        }else if(phone_holder == '' | phone_holder.length < 10){
            alert('You must enter the phone number of the clinic you will be prescribing from.');
        }else if(fax_holder == '' | fax_holder.length < 10){
            alert('You must enter the fax number of the clinic you will be prescribing from.');
        }else{
            var url = 'createLocationsData.php?cname='+clinic_name+'&pid='+practice_id+'&address1='+address1_holder+'&address2='+address2_holder+'&city='+city_holder+'&state='+state_holder+'&zip='+zip_holder+'&phone='+phone_holder+'&fax='+fax_holder+'&docid='+myDoc;
            //console.log(url);
            var Rectipo = LanzaAjax(url);
            alert('Location has been added for e-prescribing.');
            $('#add_locations').dialog('close');

            clinic_name = $('#cname').val("");
            address1_holder = $('#address1').val("");
            address2_holder = $('#address2').val("");
            city_holder = $('#city_holder').val("");
            state_holder = $('#state_holder').val("");
            zip_holder = $('#zip_holder').val("");
            phone_holder = $('#phone_holder').val("");
            fax_holder = $('#fax_holder').val("");
            pullLocationsList(practice_id);
        }
    });

    $("#create_practice").on('click', function()
                             {
        var practice_name = $('#practice2').val();
        var myDoc = $('#MEDID').val();

        //VALIDATION/////////////
        if(practice_name == ''){
            alert('You must enter the name of your practice you will be prescribing from.');
        }else{
            var url = 'createLocationsData.php?practice='+practice_name+'&docid='+myDoc;
            //console.log(url);
            var Rectipo = LanzaAjax(url);
            alert('Practice has been added for e-prescribing.');
            $('#add_practices').dialog('close');

            practice_name = $('#practice2').val("");
            pullPracticesList();
        }
    });


    $("#create_credentials").on('click', function()
                                {
        var npi_number = $('#npi_number').val();
        var dea_number = $('#dea_number').val();
        var myDoc = $('#MEDID').val();

        if(npi_number == '' | npi_number.length < 10){
            alert('You must enter a valid NPI number to e-prescribe.');
        }else if(dea_number == ''){
            alert('You must enter a valid DEA number to e-prescribe.');
        }else{
            var url = 'createCredentials.php?docid='+myDoc+'&npi='+npi_number+'&dea='+dea_number;
            //console.log(url);
            var Rectipo = LanzaAjax(url);
            alert('Your credentials have been added.  Please fax a copy of your drivers license to XXX-XXX-XXXX to complete the verification process.');
            $('#add_credentials').dialog('close');
            $('#NPI').val(npi_number);
            $('#DEA').val(dea_number);
        }
    });
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $(".Telemed_Summary_Button").on('click',function(e)
                                    {
        var myClass = $('#USERID').val();
        $("#summary_modal").html('<iframe src="medicalPassport.php?modal=1&IdUsu='+myClass+'" width="1000" height="660" scrolling="no" style="width:1000px;height:660px; margin: 0px; border: 0px solid #FFF; outline: 0px; padding: 0px; overflow: hidden;"></iframe>');
        summary_modal.dialog('open');

    });
    $(".Telemed_Notes_Button").on('click',function(e)
                                  {
        $("#modal_phoneNotes").trigger('click');
    });

    var cost = 0;

    var please_wait_modal = $("#please_wait_modal").dialog({bgiframe: true, width: 300, height: 200, modal: true, autoOpen: false});
    $(".Telemed_Close_Button").on('click',function(e)

                                  {
        please_wait_modal.dialog("open");
        $.post("update_webrtc.php", {doc_id: $("#MEDID").val(), status: 0}, function(){});

        var doctorId = -1;
        if(both_id != ''){
            doctorId = both_id;
        }
        var userId = get_idusu;
        var json,findings,recommendations;

        phoneTelemed.dialog("close");
        videoTelemed.dialog("close");
        //Ajax call for retrieving the Notes from the temp file
        $.get("getNotes.php?doctorId="+doctorId,function(data,status)
              {
            console.log(data);
            json = JSON.parse(data);
            findings = json.findings;
            recommendations = json.recommendations;

            console.log("In Get Notes"+"findings is:"+findings+"reco is:"+recommendations);
            console.log(doctorId+userId+findings+recommendations);
            //Ajax call for creating PDF of notes and storing it in backend database
            $.post("addPatientNotes.php",{doctorId: doctorId,userId: userId,findings:findings,recommendations: recommendations},function(data,success)
                   {


                $.post("SavePatientSummaryAsPDF_table.php",{IdUsu:userId},function(data,status)
                       {
						   console.log(userId+doctorId)
                    console.log("Result Data for Summary: "+data);
                    $.post("charge_for_consultation.php", {user: userId, doctor: doctorId}, function(data, status)
                           {
                        cost = data;
                        
console.log(data);

                        if(telemedValue != 2)
                        {
                            $.post("video_appointment_callback.php", {doc_id: doctorId, pat_id: userId}, function(data, status)
                                   {
                                $.get("sendPatientNotesAndSummary.php?doctorId="+doctorId+"&userId="+userId+"&cost="+cost+"&lang="+language,function(data,status)
                                      {
                                    //console.log(data);
                                    please_wait_modal.dialog('close');
                                    window.location = "patientdetailMED-new.php?IdUsu="+userId;
                                });
                            });
                        }
                        else
                        {
                            $.get("sendPatientNotesAndSummary.php?doctorId="+doctorId+"&userId="+userId+"&cost="+cost+"&lang="+language,function(data,status)
                                  {
                                //console.log(data);
                                please_wait_modal.dialog('close');
                                window.location = "patientdetailMED-new.php?IdUsu="+userId;
                            });
                        }

                    });
                });
            });

        });

    });

    $("#History").live('click',function() {
        var path = $('#ImagenN').attr("src");
        var rawimage = "";
        var idpin;
        //alert(path);
        if(path == null)
        {
            alert('Select a Report');
            return;
        }
        else if(path == 'images/deletedfileTH.png')
        {
            //alert("Deleted Report");
            rawimage=$('#ImagenN').attr("alt");
            //alert('Idpin is '+idpin);
        }
        else
        {
            rawimage=rawimage+ path.substr(path.lastIndexOf("/")+1,path.length);
        }
		console.log("Raw Image "+rawimage);
        var serviceUrl = 'getReportData.php?rawimage='+rawimage;
        //alert(query);
        //var RecTipo = LanzaAjax (cadena);
        getreportData(serviceUrl);
        //alert(pines[0].idpin);
        IDEt ='<span class="label label-info" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;">ID:'+ pines[0].idpin+' </span>';
        var text="";
        //if(pines[0].orig_filename!= null)
        //{
        text = '<span class="label label-success" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">Uploaded By : ' + pines[0].idmedfixedname+'</span>';
        //}
        //text = text + '<br><br><span class="label label-info" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;">Filename :'+ pines[0].orig_filename+'</span>';
        $('#InfoIDPaciente').html(IDEt+text);

        var queUrl ='getReportHistory.php?id='+pines[0].idpin;
        var queUrl1 ='getReportViewers.php?id='+pines[0].idpin;

		console.log("URL 1 : "+queUrl);
		console.log("URL 2 : "+queUrl1);
		
		
		//change to an ajax call
        $('#TablaPacMODALViewers').load(queUrl1, function() {
  			$('#view-history-load').hide();
		});

        $('#TablaPacMODALViewers').trigger('update');
		
		//var RecTipo = LanzaAjax (queUrl1);
		//$('#view-history-load').html(RecTipo);

        $('#TablaPacMODAL').load(queUrl);
        $('#TablaPacMODAL').trigger('update');

        //alert("Here");
        $('#BotonModal1').trigger('click');    



    });


    function getreportData(serviceURL) {
        $.ajax(
            {
                url: serviceURL,
                dataType: "json",
                async: false,
                success: function(data)
                {
                    pines = data.items;
                }
            });
    }

    $("#BotonAddClase").live('click',function() {
        var name=prompt("Please enter new Episode (class)","");
        if (name!=null && name!="")
        {
            var queUsu = $("#queUsu").val();
            var queBlock = $("#queBlock").val();
            var UltimoEvento = $("#UltimoEvento").val();
            var cadena = 'AnadeClase.php?queBlock='+queBlock+'&queUser='+queUsu+'&UltimoEvento='+UltimoEvento+'&Nombre='+name;

            var RecTipo = LanzaAjax (cadena);
            $("#Episodios").append('<option value='+(UltimoEvento+1)+' selected="selected">'+name+'</option>');
            $("#UltimoEvento").val(1+parseInt(UltimoEvento));		   
        }
    });

    $("#BotonElimClase").live('click',function() {
        var name = $('#Episodios').find(":selected").text();
        var r=confirm('Confirm removal of episode ('+name+') ?');
        if (r==true)
        {
            var queUsu = $("#queUsu").val();
            var queBlock = $("#queBlock").val();
            var UltimoEvento = $("#UltimoEvento").val();
            var cadena = 'EliminaClase.php?queBlock='+queBlock+'&queUser='+queUsu+'&UltimoEvento='+UltimoEvento+'&Nombre='+name;
            var RecTipo = LanzaAjax (cadena);
            $('#Episodios option:selected').remove();
            $('#CloseModal').trigger('click');
        }
        else
        {
            //x="You pressed Cancel!";
        }
    });

    $("#GrabaDatos").live('click',function() {
        var name = $('#Episodios').find(":selected").text();
        var r=confirm('Confirm updating information for this block ?');
        if (r==true)
        {
            var queUsu = $("#USERID").val();
            var queBlock = $("#queBlock").val();
            var UltimoEvento = $("#UltimoEvento").val();

            var queERU = $("#SelecERU").val();
            var queEvento = $("#SelecEvento").val();
            var queTipo = $("#SelecTipo").val();
            var idusfixed='';
            if(idusfixed_holder != ''){
                idusfixed=idusfixed_holder;
            }
            var idusfixedname='';
            if(idusfixed_name != ''){
                idusfixedname= idusfixed_name;
            }
            var idmed = -1;
            if(both_id != ''){
                idmed = both_id;
            }
            var fecha=$('#classification_datepicker').val();

            var cadena = 'GrabaClasif.php?queBlock='+queBlock+'&queUser='+queUsu+'&queERU='+queERU+'&queEvento='+queEvento+'&queTipo='+queTipo+'&idusfixed='+idusfixed+'&idusfixedname='+idusfixedname+'&Idmed='+idmed+'&fecha='+fecha;
            //alert (cadena);

            var RecTipo = LanzaAjax (cadena);
            //alert (RecTipo);
            $('#Episodios option:selected').remove();
            $('#CloseModal').trigger('click');
            window.location.reload();
        }

    });

    $("#BotonRecords").live('click',function() {
        //if(list.length==0)
        //{
        var Usuario = $('#userId').val();
        //var MedID =$('#MEDIdecryptD').val();
        var doctorId = -1;
        if(both_id != ''){
            doctorId = both_id;
        }
        var url = 'get_file_list.php?ElementDOM=na&EntryTypegroup=0&Usuario='+Usuario+'&MedID='+doctorId;
        //alert(url);
        var RecTipo = LanzaAjax (url);
        //alert(RecTipo);
        list = RecTipo.split(';');
        //}
        curr_file=-1;
        list.pop();
        next_click();



    });

    jQuery.fn.outerHTML = function(s) {
        return (s) ? this.before(s).remove() : jQuery("&lt;p&gt;").append(this.eq(0).clone()).html();
    }


    /*$("#Telemedicine").on('click',function() {

	 	$.ajax({
		 url: '<?php echo $domain?>/weemo_test.php?calleeID=<?php echo $email?>',
		 method: 'get',
			success: function(data){
			var htmlContent = data;
			var e = document.createElement('div');
			e.setAttribute('style', 'display: none;');
			e.innerHTML = htmlContent;
			document.body.appendChild(e);
			eval(document.getElementById("runscript").innerHTML);
			 }
		 });
		// window.open('<?php echo $domain?>/weemo_test.php?calleeID=<?php echo $email?>','_newtab');
		 // window.open(document.URL,'_self');

	}); */

    $("#choose_report_button").on('click', function()
                                  {
        $("#fileselect").trigger('click');
        //$(upload_dialog).dialog("close");
    });
    $("#BotonUpload").live('click',function() {

        /*  Pruebas de la grabación del archivo para Timeline
	  	var queUsu = $("#IdUsuP").val();
	 	var cadena = '<?php $domain?>/UsuTimeline.php?Usuario='+queUsu+'&IdMed=0';
	 	var RecTipo = LanzaAjax (cadena);
	    alert (RecTipo);
	    */


        //alert (RecTipo);
        /*
	    var IDPIN = 0;
	    var Content = 0;
	    var VIEWIdUser = 0;
	    var VIEWIdMed = 0;
	    var VIEWIP = 0;
	    var MEDIO = 0;
	    var cadena = '<?php $domain?>/LogEvent.php?IDPIN='+IDPIN+'&Content='+Content+'&VIEWIdUser='+VIEWIdUser+'&VIEWIdMed='+VIEWIdMed+'&VIEWIP='+VIEWIP+'&MEDIO='+MEDIO;
	 	var RecTipo = LanzaAjax (cadena);
	 	//alert (RecTipo);
	 	*/
    });
    var eDOM = '';
    var flag=1;
    var Elementwidth=0;
    $(".TABES").live('click',function() {
        //alert('clicked');
        if ($('#ascroll').length){
            var element = document.getElementById("ascroll");
            element.parentNode.removeChild(element);
        }	
        $(this).addClass("active");
        var queid = $(this).attr("id");
        var ElementDOM="";
        $("#stream_indicator").css("display", "block");
        //alert(queid);
        //$("#ALL").hide();
        $("#StreamContainerALL").hide();
        $("#StreamContainerIMAG").hide();
        $("#StreamContainerLABO").hide();
        $("#StreamContainerDRRE").hide();
        $("#StreamContainerOTHE").hide();
        $("#StreamContainerNA").hide();
        $("#StreamContainerSUMM").hide();
        $("#StreamContainerPICT").hide();
        $("#StreamContainerPATN").hide();
        $("#StreamContainerSUPE").hide();
        $("#StreamContainerPICT").hide();
        //$(PrevElementDOM).hide();
        //alert(queid);
        switch (queid)
        {
            case '0': 	ElementDOM ='#StreamContainerALL';
                //$("#ALL").show();
                break;
            case '1': 	ElementDOM ='#StreamContainerIMAG';
                //$("#IMAG").show();
                break;
            case '2': 	ElementDOM ='#StreamContainerLABO';
                //$("#LABO").show();
                break;
            case '3': 	ElementDOM ='#StreamContainerDRRE';
                //$("#DRRE").show();
                break;
            case '4': 	ElementDOM ='#StreamContainerOTHE';
                //$("#OTHE").show();
                break;
            case '5': 	ElementDOM ='#StreamContainerNA';
                //$("#NA").show();
                break;
            case '6': 	ElementDOM ='#StreamContainerSUMM';
                //$("#SUMM").show();
                break;
            case '7': 	ElementDOM ='#StreamContainerPICT';
                //$("#PICT").show();
                break;
            case '8': 	ElementDOM ='#StreamContainerPATN';
                //$("#PATN").show();
                break;
            case '9': 	ElementDOM ='#StreamContainerSUPE';
                //$("#SUPE").show();
                break;
            case '10': 	ElementDOM ='#StreamContainerLCKR';
                //$("#SUPE").show();
                break;
            default: 	ElementDOM ='testDIV';
                //$("#DIV").show();
                break;

        }
        eDOM = ElementDOM;
        var EntryTypegroup =queid;
        var Usuario = $('#userId').val();
        var MedID =$('#MEDID').val();
        if(MedID < 0)
        {
            MedID = $("#USERID").val();
        }


        //$("#H2M_Spin_Stream").show();
        //if(EntryTypegroup!=10){
            queUrl1 = 'getNumThumbnails.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID; 
        //}
        //console.log("NUmber of Thumbnails"+queUrl); 
        num_reports = LanzaAjax (queUrl1);
        //alert(num_reports);
        flag=1;  //Reset the flag for createReportStreamDocGroup.php




        //var queUrl ='CreateReportStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
        var isDoctor = 1;
        if(Usuario == MedID)
        {
            isDoctor = 0;
        }

        var queUrl1='';
        if(EntryTypegroup==10){
            queUrl1 ='createReportStreamLocked.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&num_reports='+num_reports+'&isDoctor='+isDoctor;
        }else{
            queUrl1 ='createReportStreamNEWTEST.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&num_reports='+num_reports+'&isDoctor='+isDoctor;
            //console.log(queUrl);
        }

        //alert (queUrl);
        //$("#ALL").show();
        //PrevElementDOM=ElementDOM;
        //$(ElementDOM).load(queUrl,function() { alert( "Load was performed." );});
        var RecTipo='<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:14px; text-shadow:none; text-decoration:none;background-color:red">Could not load Reports due to Internet issues</span>';
        setTimeout(function(){ 

            $.ajax(
                {
                    url: queUrl1,
                    dataType: "html",
                    async: false,
                    complete: function(){ //alert('Completed');
                    },
                    success: function(data) {
                        if (typeof data == "string") {
                            RecTipo = data;
                            $(ElementDOM).html(RecTipo);
                            $(ElementDOM).scrollLeft(0);

                            //offset1=20;              //global var
                            offset1=10;
                            Elementwidth=(offset1*160) + 60;
                            last_pos = 0;			//global var
                            $("#stream_indicator").css("display", "none");
                            
                        }

                    },
                    error: function(data){
                        $(ElementDOM).html(RecTipo);
                    }

                });

            //var RecTipo = LanzaAjax (queUrl);
            //$(ElementDOM).html(RecTipo);
            //$(ElementDOM).load(queUrl);
            $(ElementDOM).trigger('click');
            //$(ElementDOM).trigger('update');
            //$("#H2M_Spin_Stream").hide();
        },1000);


        //alert('here');
        setTimeout(function() {highlightattachedreports();},1000);  
        $(ElementDOM).show();

    });


    /*$('#LessPage').live('click',function(){
		//alert(eDOM);
		var newVal = $(eDOM).scrollLeft()-600;
		//$("#StreamContainerALL").scrollLeft($("#StreamContainerALL").scrollLeft()-165);
		$(eDOM).animate({ scrollLeft: newVal}, "slow");
   });

   $('#MorePage').live('click',function(){
		//alert(eDOM);
		var newVal = $(eDOM).scrollLeft()+600;
		//$("#StreamContainerALL").scrollLeft($("#StreamContainerALL").scrollLeft()+165);
		$(eDOM).animate({ scrollLeft: newVal}, "slow");
   });*/


    $('#sliderleft').live('click',function(){

        var newVal = $(eDOM).scrollLeft()-600;
        $(eDOM).animate({ scrollLeft: newVal}, 800);
    });

    $('#sliderright').live('click',function(){

        var newVal = $(eDOM).scrollLeft()+600;
        $(eDOM).animate({ scrollLeft: newVal}, 800);
    });




    $('#sliderleft').hide();
    $('#sliderright').hide();

    $("#hscroll").hover(
        function() {
            //alert('entered');
            $('#sliderleft').fadeIn('fast');
            $('#sliderright').fadeIn('fast');

        }, function() {
            //alert('gone');
            // setTimeout(function(){
            $('#sliderleft').fadeOut('fast');
            $('#sliderright').fadeOut('fast');
            // },500);
        }
    );

    $("#StreamContainerALL").scroll(function() {
        scroller("#StreamContainerALL",0);

    });

    $("#StreamContainerIMAG").scroll(function() {
        scroller("#StreamContainerIMAG",1);
    });

    $("#StreamContainerLABO").scroll(function() {
        scroller("#StreamContainerLABO",2);
    });

    $("#StreamContainerDRRE").scroll(function() {
        scroller("#StreamContainerDRRE",3);
    });

    $("#StreamContainerOTHE").scroll(function() {
        scroller("#StreamContainerOTHE",4);
    });

    $("#StreamContainerNA").scroll(function() {
        scroller("#StreamContainerNA",5);
    });

    $("#StreamContainerSUMM").scroll(function() {
        scroller("#StreamContainerSUMM",6);
    });

    $("#StreamContainerPICT").scroll(function() {
        scroller("#StreamContainerPICT",7);
    });

    $("#StreamContainerPATN").scroll(function() {
        scroller("#StreamContainerPATN",8);
    });

    $("#StreamContainerSUPE").scroll(function() {
        scroller("#StreamContainerSUPE",9);
    });

    function countOccurences(str, value){
        var regExp = new RegExp(value, "gi");
        return str.match(regExp) ? str.match(regExp).length : 0;  
    }





    function scroller(ElementDOM,id)
    {
        //console.log('leftscrollingposition '+$(ElementDOM).scrollLeft());
        //console.log('lastpos '+last_pos);

        if(last_pos <= $(ElementDOM).scrollLeft())
        {	
            //if($(ElementDOM).scrollLeft() + $(ElementDOM).width() > $("#ascroll").width()-200) 
            //console.log('leftscrollingposition '+$(ElementDOM).scrollLeft());
            //console.log('lastpos '+last_pos);
            //console.log($(ElementDOM).scrollLeft() + $(ElementDOM).width());
            //console.log((offset1*160) + 60);

            if($(ElementDOM).scrollLeft() + $(ElementDOM).width() > Elementwidth) 
                //if($(ElementDOM).scrollLeft() + $(ElementDOM).width() > ((offset1*160) + 60)) 
            {
                //$("#H2M_Spin_Stream").show();
                var t = setTimeout(function(){

                },1000);

                last_pos=$(ElementDOM).scrollLeft();



                $(ElementDOM).children().eq(0).children().last().css("display", "block");

                // console.log("offset values "+offset1);
                var html = get_more_reports(id,offset1);       //get html data



                //$("#ascroll").append(html);				//append new html for reports




            }

        }
        else
        {
            //$("#H2M_Spin_Stream").hide();
        }
    }

    var lastRectipo='';
    function get_more_reports(queid,offset)
    {
        var EntryTypegroup =queid;
        var Usuario = $('#userId').val();
        var MedID =$('#MEDID').val();
        if(MedID < 0)
        {
            MedID = $("#USERID").val();
        }
        $("#stream_load_indicator").css("display", "block");
        //alert('before url ' + offset);
        var isDoctor = 1;
        if(Usuario == MedID)
        {
            isDoctor = 0;
        }


        var queUrl ='createReportStreamNEWTEST.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&num_reports='+num_reports+'&isDoctor='+isDoctor;
        
        

        //var queUrl ='CreateReportStreamChunk.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&offset='+offset+'&jump='+jump+'&isDoctor='+isDoctor;
        //alert(queUrl);
        //console.log(queUrl);
        //console.log(flag);
        var RecTipo='<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:14px; text-shadow:none; text-decoration:none;background-color:red">Could not load Reports due to Internet issues</span>';
        //$("#H2M_Spin_Stream").show();

        $.ajax(
            {
                url: queUrl,
                dataType: "html",

                complete: function(){ 
                },
                success: function(data) {
                    if (typeof data == "string") {

                        RecTipo = data;
                        //offset1=offset+Math.round(num_reports/2);
                        offset1=offset+jump;
                        //alert('new offset='+offset);	
                    }
                    //$("#H2M_Spin_Stream").hide();	
                    //console.log(RecTipo);
                    if(RecTipo != lastRectipo)
                    {

                        //console.log(queUrl);
                        //alert(lastRectipo);
                        //alert(RecTipo);
                        var count = countOccurences(RecTipo, "note2");  
                        //console.log("count: "+count);
                        var new_width=$("#ascroll").width()+(count * 160);			//set new width

                        Elementwidth=new_width;

                        console.log("new_width:25570pxstrea "+Elementwidth);

                        $("#ascroll").css("width", new_width+"px");

                        $('#ascroll').children().eq($('#ascroll').children().length - 2).after(RecTipo);
                        setTimeout(function() {highlightattachedreports();},1000);  
                        $("#stream_load_indicator").css("display", "none");
                        //alert(lastRectipo);

                        //$("#ascroll").append(RecTipo);
                        lastRectipo=RecTipo;
                        if(count < 8)    
                        { 
                            $('#ascroll').children().last().css("display", "none");
                            $('#ascroll').css("width", $('#ascroll').width() - 70);
                        }
                    }
                },
                error: function(data){
                    displaynotification('Failed to Load Data','');
                }

            });

        //return RecTipo;

    }    




    $("#BotonTestRS").live('click',function() {
        var ElementDOM ='testDIV';
        var EntryTypegroup ='3';
        var Usuario = $('#userId').val();
        var MedID =$('#MEDID').val();
        if(MedID < 0)
        {
            MedID = $("#USERID").val();
        }
        var isDoctor = 1;
        if(Usuario == MedID)
        {
            isDoctor = 0;
        }
        var queUrl ='CreateReportStream.php?ElementDOM='+ElementDOM+'&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&isDoctor='+isDoctor;

        $('#StreamContainer').load(queUrl);
        $('#StreamContainer').trigger('update');

    });

    //$("#attachments").live('click',function() {



    //});

    var DELAY = 400,clicks = 0, timer = null;

    $(".note2").live("click", function(event,index,cascade)
    {
        var doc_id = get_idusu;
        var is_doc = false;

        if(both_id.length > 0)
        {
            doc_id = both_id;
            is_doc = true;
        }

        //console.log("FILE CLICKED FOR DOCTOR");
        //var docviewer1 = document.getElementById('AreaConten');
        //docviewer1.style.display = 'block';
        // $('.TABES:eq(9)').click(); // commented out this line as because there was a bug earlier. When a thumbnail is clicked it was reverting back to reportstream fo superbill section

        var divIndex = $(this).index();
        //console.log("divIndex: "+divIndex);
        //alert("divIndex: "+divIndex);
        var count = -1;
        $( ".marker" ).each(function() 
                            {
            // alert("count: "+count);
            if(count==divIndex)
            {
                id = $( this ).attr('id');
                //alert("marker id: "+id);
            }  
            count++; 
        });
        //$("#"+id).trigger('click',[id,false]);	
        //$("#"+id).children('.flag').click();
        // ($('.timenav').children('.content')).trigger('click',[false,divIndex]);
        $('#AreaConten').show();

        //$('#media-active').hide();	//Commented by debraj for blind reports

        clicks++;  //count clicks  //Commented by debraj for blind reports

        //alert('event.originalEvent: '+event.originalEvent);
        if( event.originalEvent !== undefined)
        {
            if(clicks === 1) {
                var screen;
                window.location.href="#AreaConten";
                if(index!= undefined )
                    screen = document.getElementById(index);
                else
                    screen=this;

                var queBLD = $(".queBLD", screen).attr("id");
                console.log("queBLD"+queBLD);
                timer = setTimeout(function() {

                    //alert(this);

                    var queBLD = $(".queBLD", screen).attr("id");
                    //alert("queBLD"+queBLD);
                    var queId = $(screen).attr("id");
                    var quePEN=  $(".quePEN", screen).attr("id");
                    var queDEL=	 $(".queDEL", screen).attr("id");			
                    if(queBLD==null){

                        if(quePEN==null){

                            //var queId = $(this).attr("id");
                            var queTip = $(".queTIP", screen).attr("id");
                            var queClas = $(".queEVE", screen).attr("id");
                            var queFecha = $(".queFEC", screen).attr("id");
                            var queUsu = $("#IdUsuP").val();

                            if(queDEL==null){

                                var readwriteaccess=$(".queIMG", screen).children("img").attr("alt");
                                if(readwriteaccess==1){
                                    $("#BotonMod").show();
                                }else{
                                    $("#BotonMod").hide();
                                }


                                var med=doc_id;
                                var IDPIN = queId;
                                var Content = 'Report Viewed';
                                var VIEWIdUser = mem_id;
                                var VIEWIdMed = med;
                                var MEDIO = 0;
                                var cadena = 'LogEvent.php?IDPIN='+IDPIN+'&Content='+Content+'&VIEWIdUser='+VIEWIdUser+'&VIEWIdMed='+VIEWIdMed+'&MEDIO='+MEDIO;
                                var RecTipo = LanzaAjax (cadena);

                                //alert (RecTipo);
                                var queImg = $(".queIMG", screen).attr("id");
                                var imagename = queImg;
                                var extensionR = queImg.substr(queImg.length-3,3);
                                var ImagenRaiz = queImg.substr(0,queImg.length-4);
                                var subtipo = queImg.substr(3,2);  // Para los casos en que eMapLife+ (PROF) ya sube las imagenes a AMAZON y no a GODADDY
                                //$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
                                var isDicom = 0;
                                if(queImg.indexOf(".") == -1)
                                {
                                    isDicom = 1;
                                }

                                if (isDicom)
                                {
                                    // load Dicom URL Here
                                    $("#BotonMod").hide();
                                    $('#AreaConten').show();
                                    window.open("http://54.225.67.15/AlmaWebPlatform/WebViewer/webviewer.php?AccessionNumber="+IDPIN+"&PatientID="+queUsu+"&Width=1680&Height=911&Orientation=undefined&workerMode=2", "IsPacs","width=1680,height=911");
                                    return 0;
                                }
                                else if (extensionR=='pdf' || extensionR=='png')
                                {
                                    var cadena = 'DecryptFile.php?rawimage='+queImg+'&queMed='+doc_id;
                                    var RecTipo = LanzaAjax (cadena);

                                    var contenTHURL = 'temp/'+doc_id+'/PackagesTH_Encrypted/'+ImagenRaiz+'.png';  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
                                    var contenURL =   'temp/'+doc_id+'/Packages_Encrypted/'+ImagenRaiz+'.'+extensionR;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
                                    //var conten = '<img id="ImagenN" src="'+contenURL+'" alt="">';
                                    var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;" title="PDF" src="'+contenURL+'" alt="'+queId+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
                                    var contenTH = '<img id="ImagenTH"  src="'+contenTHURL+'" alt="" style="max-width:150px; max-height:200px;">';
                                }
                                else if(extensionR=='MOV')
                                {
                                    var cadena = 'DecryptFile.php?rawimage='+queImg+'&queMed='+doc_id;
                                    var RecTipo = LanzaAjax (cadena);


                                    $('#AreaConten').hide();
                                    //var docviewer = document.getElementById('AreaConten');
                                    //docviewer.style.display = 'none';
                                    //alert('here');		
                                    /*document.getElementById('media-active').innerHTML='';
                        document.getElementById('media-active').innerHTML='<embed src="video/abc.MOV" height="500" width="800" controller="true" autoplay="true" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/" ></embed>';   
                        document.getElementById('media-active').display='block';*/
                                    alert('Videos may take time to load depending on your internet speed .');
                                    var src = 'temp/'+doc_id+'/Packages_Encrypted/'+ImagenRaiz+'.'+extensionR;
                                    //alert(src);
                                    $('#media-active').html('<embed src="'+src+'" height="500" width="800" controller="true" autoplay="true" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/" >');
                                    $('#media-active').show();

                                    /*$('#videoplayer').show();


                        var vp = document.getElementById('videoplayer');
                        //vp.style.display = 'block';
                        vp.setAttribute("src","video/abc.MOV");
                        vp.setAttribute("autoplay","true");
                        //vp.style.display='block';
                        */




                                }
                                else if(extensionR=='jpg' || extensionR=='jpeg' || extensionR=='JPG' )
                                {
                                    //alert(queImg);
                                    var cadena = 'DecryptFile.php?rawimage='+queImg+'&queMed='+doc_id;
                                    var RecTipo = LanzaAjax (cadena);

                                    var contenTHURL = 'temp/'+doc_id+'/PackagesTH_Encrypted/'+queImg;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
                                    var contenURL =   'temp/'+doc_id+'/Packages_Encrypted/'+queImg;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
                                    //var conten = '<img id="ImagenN" src="'+contenURL+'" alt="">';
                                    //var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;" title="JPG" src="'+contenURL+'" alt="'+queId+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
                                    var conten = '<img id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;max-height:1500px;max-width:600px;"  src="'+contenURL+'" alt="'+queId+'">';
                                    var contenTH = '<img  id="ImagenTH"  src="'+contenTHURL+'" alt="" style="max-width:150px; max-height:200px;">';
                                }
                                else if(extensionR.toLowerCase() == 'gif' )
                                {
                                    //alert(queImg);
                                    var cadena = 'DecryptFile.php?rawimage='+queImg+'&queMed='+doc_id;
                                    var RecTipo = LanzaAjax (cadena);

                                    var contenTHURL = 'temp/'+doc_id+'/PackagesTH_Encrypted/'+queImg;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
                                    var contenURL =   'temp/'+doc_id+'/Packages_Encrypted/'+queImg;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
                                    //var conten = '<img id="ImagenN" src="'+contenURL+'" alt="">';
                                    //var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;" title="JPG" src="'+contenURL+'" alt="'+queId+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
                                    var conten = '<img id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;max-height:1500px;max-width:600px;"  src="'+contenURL+'" alt="'+queId+'">';
                                    var contenTH = '<img  id="ImagenTH"  src="'+contenTHURL+'" alt="" style="max-width:150px; max-height:200px;">';
                                }
                                else{
                                    var canal = null;

                                    if(lifepin_canal != '')
                                    {
                                        canal = lifepin_canal;
                                    }

                                    if(canal == '7' ||extensionR=='png'||extensionR=='PNG'){
                                        var cadena = 'DecryptFile.php?rawimage='+queImg+'&queMed='+doc_id;
                                        var RecTipo = LanzaAjax (cadena);

                                        var contenTHURL = 'temp/'+doc_id+'/PackagesTH_Encrypted/'+queImg; 
                                        var conten = '<img id="ImagenN" src="temp/'+doc_id+'/Packages_Encrypted/'+queImg+'" alt="'+queId+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
                                        //alert('here');
                                    }else{
                                        if (subtipo=='XX') { 
                                            var cadena = 'DecryptFile.php?rawimage='+queImg+'&queMed='+doc_id;
                                            var RecTipo = LanzaAjax (cadena);

                                            var contenTHURL = 'temp/'+doc_id+'/PackagesTH_Encrypted/'+queImg; 
                                            var conten = '<img id="ImagenN" src="temp/'+doc_id+'/Packages_Encrypted/'+queImg+'" alt="'+queId+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
                                        }
                                        else{ 
                                            var contenTHURL = 'eMapLife/PinImageSetTH/'+queImg; 
                                            var conten = '<img id="ImagenN" src="eMapLife/PinImageSet/'+queImg+'" alt="'+queId+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
                                        }  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS

                                        //if(urlExists(contenTHURL)) {}else { contenTHURL = '<?php $domain?>/eMapLife/PinImageSet/'+queImg;}
                                    }
                                    var contenTH = '<img id="ImagenTH"  src="'+contenTHURL+'" alt="'+queId+'" style="max-width:150px; max-height:200px;">';
                                }
                            }else{
                                //code for report deletion
                                var queImg1 = $(".queDEL", screen).attr("id");
                                var contenURL='images/deletedfileTH.png';
                                var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;" title="PDF" src="'+contenURL+'" alt="'+queImg1+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
                                var contenTH = '<img id="ImagenTH"  src="images/deletedfile.png" alt="" style="max-width:150px; max-height:200px;">';

                            }

                            //alert (queClas);

                            //$('div.grid-content').html(conten);
                            $('#AreaConten').html(conten);
                            $('#RepoThumb').html(contenTH);

                            //$('div.pull-left.a').html(queTip);
                            $('#AreaTipo').html(queTip);

                            //$('.ClClas').html(queClas);
                            $('#AreaClas').html(queClas);

                            //$('div.grid-title-label').html('<span class="label label-warning" style="font-size:16px;">'+queFecha+'</span>');
                            $('#AreaFecha').html('<span class="label label-warning" style="font-size:16px;">'+queFecha+'</span>');

                            var queUrl ='getTipoClase.php?BlockId='+queId;
							console.log("getTipoClase : "+queId);
                            $('.ContenDinamico').load(queUrl);
                            //$('#TablaPac').trigger('click');
                            $('.ContenDinamico').trigger('update');

                            //Here changes are required for the multiple referrals area
                            if(referral_state==2){

                                var cadena='getReportInformationReview.php?referralid='+referral_id;
                                //alert(cadena);
                                var status=LanzaAjax(cadena);
                                //alert(status);
                                if(parseInt(status)){
                                    $("div#infr").css("color","#3d93e0");
                                    $("div#infr").children("*").css("color","#3d93e0");
                                    $(screen).css("color","#3d93e0");

                                    var cadena='setReferralStage.php?referralid='+referral_id+'&stage=3';
                                    LanzaAjax(cadena);
                                    referral_state=3;
                                    $("div#infr_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
                                    var cadena='push_server.php?message="Referral stage information review is completed"&NotifType=2&IdUsu='+get_idusu+'&FromDoctorId='+doc_id+'&channel='+other_doc;
                                    var RecTipo=LanzaAjax(cadena);
                                    var content="referal stage information review is completed";
                                    var subject="Referral stage information";
                                    var reportids=0;
                                    var cadena='sendMessage.php?sender='+doc_id+'&receiver='+other_doc+'&patient='+mem_id+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id;
                                    var RecTipo=LanzaAjax(cadena);
                                    displaynotification('status','Referal stage information review is completed');
                                    setTimeout(function(){window.location.reload(true)},2000); 
                                }

                                //update referral table.
                            }

                        }else{


                            alert("An unlock request for the report(s) has already been send to screen user.Pending user confirmation!!");

                        }


                    }else{

                        //var queId = $(screen).attr("id");
                        var myClass = $(screen).attr("id");
                        $('#Idpin').attr("value",myClass);
                        //Adding the option of showing only "All reports incase the patient is not a valid user
                        var To= $('#userId').val();
                        getUserData(To);
                        $('#TextoSend').html("");
                        $("#ConfirmaLink,#ConfirmaLinkAll").show();
                        $('#Thisreport,#Allreport').show();
                        if (user[0].email==''){
                            //alert("Patient email not found. Request will be sent to reportcreator!");
                            // senderoption=2;
                            $('#BotonModal').trigger('click');
                            $('#ConfirmaLink').hide();
                            $('#Thisreport').hide();
                        }else{

                            $('#BotonModal').trigger('click');
                        }
                        //$('#header-modal0').show();

                        //alert("This is a blind report!!");
                    }
                    clicks = 0;  //after action performed, reset counter

                }, DELAY);

            } else {
                if(index!= undefined )
                    screen = document.getElementById(index);
                else
                    screen=this;
                clearTimeout(timer);  //prevent single-click action
                var queUsu = $("#IdUsuP").val();
                var idpin = $(screen).attr("id");
                //var med=<?php echo $IdMed ?>;
                //var privstate=$(screen).attr("privstate");
                var queBLD = $(".queBLD", screen).attr("id");
                //alert('queBLD: '+queBLD);
                var quePEN=  $(".quePEN", screen).attr("id");
                //alert("quePEN: "+quePEN);
                var readwriteaccess=$(".queIMG", screen).children("img").attr("alt");	
                //alert(readwriteaccess);
                if(queBLD==null){

                    if(quePEN==null){
                        if(readwriteaccess==1){
                            $("#BotonMod").show();
                            var cadena = 'getprivacystatus.php?Idpin='+idpin+'&state=0+&type=0';
                            //alert(cadena);
                            var RecTipo = LanzaAjax (cadena);
                            //alert(RecTipo);
                            var normalprivate="";
                            //var superprivate="";
                            var priv="";
                            //alert (RecTipo);
                            //alert('Double Click');  //perform double-click action
                            if(RecTipo=="normal"){
                                normalprivate=confirm('Please confirm that you want to make this report private!');
                                if(normalprivate==true){
                                    //alert('normal!');
                                    var cadena = 'getprivacystatus.php?Idpin='+idpin+'&state=1+&type=1';
                                    var RecTipo = LanzaAjax (cadena);
                                    //alert (RecTipo);
                                }
                            }else if(RecTipo=="private"){
                                priv=confirm('Please confirm that you want to remove the privacy of this report!');
                                if(priv==true){
                                    var cadena = 'getprivacystatus.php?Idpin='+idpin+'&state=1+&type=0';
                                    var RecTipo = LanzaAjax (cadena);
                                    //alert (RecTipo);
                                }
                            }else if(RecTipo=="superprivate"){
                                alert('Privacy of this report cannot be changed!');
                            }
                            var myClass = $(screen).attr("id");
                            //alert(privstate);
                        }else {
                            $("#BotonMod").hide();
                            alert("You don't have permissions to change the privacy of this file!");
                        }
                    }
                }
                //$('#BotonModal00').trigger('click');
                clicks = 0;  //after action performed, reset counter
            }
        }
    })
    .live("dblclick", function(e){
        e.preventDefault();  //cancel system double-click event
    });



    //Adding button action for blind reports

    $("#SendButton").live('click',function() {

        var option=$(this).attr("value");

        //if(option=="This report"){
        var IdPin=$('#Idpin').val();
        //alert("IdPin:"+IdPin);
        if(IdPin=="00A"){
            IdPin=-111;
            //alert("It works!");
        }
        // }else{
        //alert("Clicked on All reports!!");
        //var IdPin=-111;
        //alert("Clicked on All reports!! "+IdPin);
        // }
        var senderoption;
        if(option=="Request Patient"){
            //alert("Clicked on request Patient!!");
            senderoption=1;
        }else{
            //alert("Clicked on reuqest Doctor!!");
            senderoption=2;
        }
        var usephone;
        if ($('#c2').attr('checked')=='checked'){ 
            //subcadena =' (will call phone number also)';
            usephone = 1;
            //alert("Phone number option selected");
        }
        //return;

        var To= $('#userId').val();
        getUserData(To);


        //var IdDoc=$()
        var NameMed = $('#IdMEDName').val();
        var SurnameMed = $('#IdMEDSurname').val();
        var From = $('#MEDID').val();
        var FromEmail = $('#IdMEDEmail').val();
        if(IdPin==-111){						//Indicator whether to send for this report or for all reports.
            console.log('-111');
            getReportCreator(IdPin,From,To);
        }				
        else{
            getReportCreator(IdPin,0,0);
        }	
        var doc;
        //alert("Total number of report creator: "+reportcreator.length);
        
        if(reportcreator.length==0){

            //var option1=confirm("Reportcreator not found. Do you want to continue!!");

            var option1;	
            if(senderoption==2){
                alert("Reportcreator not found!");
                return;
            }else {
                option1=confirm("Reportcreator not found. Do you want to continue!!");
            }
            if(option1){
                reportcreator=user;
                doc=user;
            }else {
                return;
            }

        }


        for (var i = 0, len = reportcreator.length; i < len; ++i) {

            if(doc==user){
            }else{
                doc = reportcreator[i];
            }

            if (user[0].email==''){
                var IdCreador = user[0].IdCreator;
                getMedCreator(IdCreador);
                //alert ('orphan user . Patient Creator= '+IdCreador);
                if(doc==user){
                    alert("Both reportcreator and Patient details are not found in the system. Please contact support!!");
                    return;
                }
                //alert('Permission Request sent to '+doc.Name + '.'+doc.Surname + ' at ' + doc.IdMEDEmail);
                var Subject = 'Unlock report from Dr. '+NameMed+' '+SurnameMed;

                var Content = 'Dr. '+NameMed+' '+SurnameMed+' has requested to see reportID'+IdPin+ 'of your patient named: '+user[0].Name+' '+user[0].Surname+' (UserId:  '+To+'). Please confirm, or just close this message to reject.';

                //alert (Content);

                var destino = "Dr. "+doc.Name+" "+doc.Surname; 
                if(usephone==1){
                    var phone=doc.phone;
                    //alert(phone);
                    if(phone!=null){
                        phone = phone.replace(/[^0-9]/g, '');
                        if(phone.length == 10 || phone.length==11) { 

                            //alert("yup, its valid number digits");
                        } else {
                            //alert("not valid number");
                            phone='Null';
                        } 
                    }else{
                        alert("Health2me could not find a valid phone number!")
                    }
                    var cadena = 'MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doc.id+'&ToEmail='+doc.IdMEDEmail+'&NameDoctor='+doc.Name+'&SurnameDoctor='+doc.Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].surname+'&callphone='+phone+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
                }else{
                    var cadena = 'MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doc.id+'&ToEmail='+doc.IdMEDEmail+'&NameDoctor='+doc.Name+'&SurnameDoctor='+doc.Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].surname+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
                }


                //alert (cadena);
                var RecTipo = LanzaAjax (cadena);

                //$('#CloseModallink').trigger('click');
                //alert (RecTipo);
            } else{
                var NameMed = $('#IdMEDName').val();
                var SurnameMed = $('#IdMEDSurname').val();
                var From = $('#MEDID').val();
                var FromEmail = $('#IdMEDEmail').val();
                var Subject = 'Unlock report';
                var option;
                if(doc==user)
                    senderoption=1;


                //alert(senderoption);
                //Request should go to the patient
                if(senderoption==1) {
                    //alert('Permission Request sent to '+doc.Name + '.'+doc.Surname + ' at ' + doc.IdMEDEmail);

                    var Content = 'Dr. '+NameMed+' '+SurnameMed+' has requested to see your (UserId:  '+To+') reportID'+IdPin+ ' Please confirm, or just close this message to reject.';
                    //var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=0&IdPac=0&To='+To+'&ToEmail='+user[0].email+'&callphone='+user[0].telefone+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
                    if(usephone==1){
                        var phone=user[0].telefono;
                        //alert(phone);
                        if(phone!=null){
                            phone = phone.replace(/[^0-9]/g, '');
                            if(phone.length == 10 || phone.length==11) { 

                                //alert("yup, its valid number digits");
                            } else {
                                alert("Phone number is not valid!");
                                phone='Null';
                            } 
                        }else{
                            alert("Health2me could not find a valid phone number!");
                        }
                        var cadena = 'MsgInterno.php?Tipo=0&IdPac=0&To='+To+'&ToEmail='+user[0].email+'&NameDoctor='+user[0].Name+'&SurnameDoctor='+user[0].Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].Surname+'&callphone='+phone+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
                    }else{
                        var cadena = 'MsgInterno.php?Tipo=0&IdPac=0&To='+To+'&ToEmail='+user[0].email+'&NameDoctor='+user[0].Name+'&SurnameDoctor='+user[0].Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].Surname+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;;

                    }
                    //alert (cadena);
                    //alert('patient iteration:'+i);
                    if(i==1)
                        break;
                }else if(senderoption==2){           //request would go to doctors
                    if(Idpin!=-111){
                        var oktogo=confirm("You have selected doctor to send request.This request would be send to unlock all applicable reports and not limited to only this report!");
                        if(!oktogo)
                            return;
                    }
                    // alert('Permission Request sent to '+doc.Name + '.'+doc.Surname + ' at ' + doc.IdMEDEmail);

                    var Content = 'Dr. '+NameMed+' '+SurnameMed+' has requested to see your (UserId:  '+To+') reportID'+IdPin+ ' Please confirm, or just close this message to reject.';
                    if(usephone==1){
                        var phone=doc.phone;
                        //alert(phone);
                        if(phone!=null){
                            phone = phone.replace(/[^0-9]/g, '');
                            if(phone.length == 10 || phone.length==11) { 

                                //alert("yup, its valid number digits");
                            } else {
                                alert("Phone number is not valid!");
                                phone='Null';
                            } 
                        }else{
                            alert("Health2me could not find a valid phone number!")
                        }
                        var cadena = 'MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doc.id+'&ToEmail='+doc.IdMEDEmail+'&NameDoctor='+doc.Name+'&SurnameDoctor='+doc.Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].Surname+'&callphone='+phone+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
                    }else{
                        var cadena = 'MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doc.id+'&ToEmail='+doc.IdMEDEmail+'&NameDoctor='+doc.Name+'&SurnameDoctor='+doc.Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].Surname+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
                    }
                    //var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doc.id+'&ToEmail='+doc.IdMEDEmail+'&NameDoctor='+doc.Name+'&SurnameDoctor='+doc.Surname+'&NameDoctorOrigin='+NameMed+'&SurnameDoctorOrigin='+SurnameMed+'&NamePatient='+user[0].Name+'&SurnamePatient='+user[0].Surname+'&callphone='+doc.phone+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1&Idpin='+IdPin;
                }else{
                    alert("Incorrect option!");
                    return;
                }
                console.log(cadena);
                //alert (cadena);
                
                var RecTipo = 'Temporal';
                $.get(cadena, function(data, status)
                {
                    if (typeof data == "string") 
                    {
                        RecTipo = data;
                    }
                });
                /*$.ajax(
                    {
                        url: cadena,
                        dataType: "html",
                        async: false,
                        complete: function(){ 
                            //alert('Completed');
                        },
                        success: function(data)
                        {
                            if (typeof data == "string") {
                                RecTipo = data;
                            }
                        }
                    });*/

                //$('#CloseModal').trigger('click'); 

                //alert (RecTipo);	

            }

        }
        $('.modal').modal('hide');              
        //$('#CloseModalLink').trigger('click');
        //$('#BotonBusquedaPac').trigger('click');
        //location.reload(true);

    });

    //Adding changes for the send button 

    $("#ConfirmaLink,#ConfirmaLinkAll").live('click',function() {
        // Confirm
        var subcadena='';
        //var CallPhone = 0;
        /*if ($('#c2').attr('checked')=='checked'){ 
	     	subcadena =' (will call phone number also)';
		    CallPhone = 1; 
	     }*/
        var whichreport=$(this).attr("value");
        if(whichreport=="All reports"){
            $('#Idpin').attr("value","00A");
        }
        var To= $('#userId').val();
        getUserData(To);
        if (user[0].email==''){
            //alert("Patient email not found. Request will be sent to reportcreator!");
            var Text='<span>Patient email not found. Request will be sent to reportcreator!</span><br><br>';
            Text=Text+'<p><input type="button" class="btn btn-success" value="Request Doctor" id="SendButton" style="margin-left:10px; margin-top:-15px;"></p>';
            Text=Text+'<input type="checkbox" id="c2" name="cc"><label for="c2" style="margin-top:10px;"><span></span></label><i class="icon-phone"></i><span></span>Urgent(call phone) ';
            $('#TextoSend').html(Text);
            // return;
            // senderoption=2;
        }else{
            //Show the option to select either patient or doctor. Depending on the selection also show the details.
            var Text='<span>Please select "request Patient" or "request doctor".</span><span>The unlock request would be send accordingly.<span><br><br>';
            Text=Text+'<p><input type="button" class="btn btn-success" value="Request Patient" id="SendButton" style="margin-left:20px; margin-top:-15px;">';
            Text=Text+'<input type="button" class="btn btn-success" value="Request Doctor" id="SendButton" style="margin-left:25px; margin-top:-15px;"></p>';
            Text=Text+'<input type="checkbox" id="c2" name="cc"><label for="c2" style="margin-top:30px;margin-left:10px;"><span></span></label><i class="icon-phone"></i><span></span>Urgent(call phone)';
            $('#TextoSend').html(Text);
            //  return;
        }
        $("#ConfirmaLink,#ConfirmaLinkAll").hide();
        $('#Thisreport,#Allreport').hide();
    });


    //changes for the audio files
    $('#saveaudio').prop('disabled',true);	

    var audioContext = new AudioContext();
    var audioInput = null,
        realAudioInput = null,
        inputPoint = null,
        audioRecorder = null;
    var rafID = null;
    var analyserContext = null;
    var canvasWidth, canvasHeight;
    var recIndex = 0;


    function toggleRecording( e ) {
        if (e.classList.contains("recording")) {
            // stop recording
            window.clearInterval(audiotimer);
            audioRecorder.stop();
            e.classList.remove("recording");
            $('#record').val('Start');
            //audioRecorder.getBuffer( drawWave );
            $('#saveaudio').prop('disabled',false);	
        } else {
            // start recording
            if (!audioRecorder){
                alert("Error in capturing audio");
                return;
            }
            $('#saveaudio').prop('disabled',true);	
            e.classList.add("recording");
            $('#record').val('Stop');
            audioRecorder.clear();
            audioRecorder.record();
            var start = new Date;
            var i=0;
            audiotimer=setInterval(function() {
                $('.Timer').text((i++) + " Seconds");
            }, 1000);
        }
    }






    /*function drawWave( buffers ) {
				var canvas = document.getElementById( "wavedisplay" );

				drawBuffer( canvas.width, canvas.height, canvas.getContext('2d'), buffers[0] );
			   }*/

    function saveAudio() {
        audioRecorder.exportWAV( upload );
        // could get mono instead by saying
        // audioRecorder.exportMonoWAV( doneEncoding );
    }

    function convertToMono( input ) {
        var splitter = audioContext.createChannelSplitter(2);
        var merger = audioContext.createChannelMerger(2);

        input.connect( splitter );
        splitter.connect( merger, 0, 0 );
        splitter.connect( merger, 0, 1 );
        return merger;
    }

    function cancelAnalyserUpdates() {
        window.cancelAnimationFrame( rafID );
        rafID = null;
    }

    function updateAnalysers(time) {
        if (!analyserContext) {
            var canvas = document.getElementById("analyser");
            canvasWidth = canvas.width;
            canvasHeight = canvas.height;
            analyserContext = canvas.getContext('2d');
        }

        // analyzer draw code here
        {
            var SPACING = 3;
            var BAR_WIDTH = 1;
            var numBars = Math.round(canvasWidth / SPACING);
            var freqByteData = new Uint8Array(analyserNode.frequencyBinCount);

            analyserNode.getByteFrequencyData(freqByteData); 

            analyserContext.clearRect(0, 0, canvasWidth, canvasHeight);
            analyserContext.fillStyle = '#F6D565';
            analyserContext.lineCap = 'round';
            var multiplier = analyserNode.frequencyBinCount / numBars;

            // Draw rectangle for each frequency bin.
            for (var i = 0; i < numBars; ++i) {
                var magnitude = 0;
                var offset = Math.floor( i * multiplier );
                // gotta sum/average the block, or we miss narrow-bandwidth spikes
                for (var j = 0; j< multiplier; j++)
                    magnitude += freqByteData[offset + j];
                magnitude = magnitude / multiplier;
                var magnitude2 = freqByteData[i * multiplier];
                analyserContext.fillStyle = "hsl( " + Math.round((i*360)/numBars) + ", 100%, 50%)";
                analyserContext.fillRect(i * SPACING, canvasHeight, BAR_WIDTH, -magnitude);
            }
        }

        rafID = window.requestAnimationFrame( updateAnalysers );
    }

    function toggleMono() {
        if (audioInput != realAudioInput) {
            audioInput.disconnect();
            realAudioInput.disconnect();
            audioInput = realAudioInput;
        } else {
            realAudioInput.disconnect();
            audioInput = convertToMono( realAudioInput );
        }

        audioInput.connect(inputPoint);
    }

    function gotStream(stream) {
        inputPoint = audioContext.createGain();

        // Create an AudioNode from the stream.
        realAudioInput = audioContext.createMediaStreamSource(stream);
        audioInput = realAudioInput;
        audioInput.connect(inputPoint);

        //    audioInput = convertToMono( input );

        analyserNode = audioContext.createAnalyser();
        analyserNode.fftSize = 2048;
        inputPoint.connect( analyserNode );

        audioRecorder = new Recorder( inputPoint );

        zeroGain = audioContext.createGain();
        zeroGain.gain.value = 0.0;
        inputPoint.connect( zeroGain );
        zeroGain.connect( audioContext.destination );
        updateAnalysers();
    }

    var initFlag=0;
    function initAudio() {
        if (!navigator.getUserMedia)
            navigator.getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
        if (!navigator.cancelAnimationFrame)
            navigator.cancelAnimationFrame = navigator.webkitCancelAnimationFrame || navigator.mozCancelAnimationFrame;
        if (!navigator.requestAnimationFrame)
            navigator.requestAnimationFrame = navigator.webkitRequestAnimationFrame || navigator.mozRequestAnimationFrame;
        initFlag=1;
        navigator.getUserMedia({audio:true}, gotStream, function(e) {
            alert('Error getting audio');
            initFlag=0;
        });
    }

    //window.addEventListener('load', initAudio );


    function upload(blob) {
        var xhr=new XMLHttpRequest();
        $('#wait_audio').show();
        xhr.onload=function(e) {
            if(this.readyState === 4) {
                console.log("Server returned: ",e.target.responseText);
                //alert('Audio data successfully saved into Health2me Server.');
            }
        };
        xhr.onreadystatechange=function()
        {
            if (xhr.readyState==4 && xhr.status==200)
            {
                alert('Audio data successfully saved into Health2me Server.');
                $('#saveaudio').prop('disabled',true);
                $('#wait_audio').hide();
                $('.Timer').text("");
                $('#closeaudiotab').click();
            }
            if (xhr.readyState==4 && xhr.status!=200){

                alert('Audio data transfer error!');
                $('#saveaudio').prop('disabled',true);
                $('#wait_audio').hide();
                $('.Timer').text("");

            }
        }
        var fd=new FormData();
        fd.append("file",blob);

        var doc_id = 0;

        if(both_id != '')
        {
            doc_id = both_id;
        }
        
        xhr.open("POST","upload_file_audio.php?queId="+get_idusu+"&from="+doc_id,true);
        xhr.send(fd);
        
        //alert('Audio data successfully saved into Health2me Server');
    }
    
    


    $('#Dictate').live('click', function() {
        if(initFlag==0)
            initAudio();
        $('#message_modal_audio').click();
    });


    $('#record').live('click', function() { toggleRecording(this);}); 

    $('#saveaudio').live('click', function() { saveAudio(); }); 


    //Adding datepicker for the upload report
    $("#APP_Date" ).datepicker({
        inline: true,
        nextText: '&rarr;',
        prevText: '&larr;',
        showOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        showOn: "button",
        buttonImage: "images/calendar-blue.png",
        buttonImageOnly: true,
        changeYear: true ,
        changeMonth: true,
        yearRange: '1900:c',
    });

    var rep_date = new Date().toDateInputValue();
    $('#datepicker2').change(function() {

        //var idpin = $('#idpin').val();
        rep_date = $('#datepicker2').val();
        //alert('Report Date '+ rep_date);
        //$("report_date").attr('val',rep_date);
    });


    var types = 1;
    $("#report_type").val(1);
    $('#reptype').change(function() {

        //var idpin = parseInt($('#idpin').val());
        var report_type = document.getElementById("reptype");


        types = report_type.options[report_type.selectedIndex].value;



        //alert('changed '+types);
        $("#report_type").val(types);

        // Line added by Pallab   
        console.log("Report are of Type is "+$("#report_type").html); //Pallab

        //alert('changed1 '+document.getElementById.("report_date").getAttribute("value"));
        //alert('changed2 '+document.getElementById.("report_type").getAttribute("value"));


    });


    function $id(id) {
        return document.getElementById(id);
    }


    // output information
    function Output(msg) {
        var m = $id("messages");
        //m.innerHTML = msg + m.innerHTML;
        m.innerHTML = msg;
    }


    // file drag hover
    function FileDragHover(e) {
        e.stopPropagation();
        e.preventDefault();
        e.target.className = (e.type == "dragover" ? "hover" : "");
    }

    var files_upload;

    var isFileUploaded=0;

    // file selection
    function FileSelectHandler(e) {

        // cancel event and hover styling
        FileDragHover(e);

        // fetch FileList object
        var files = e.target.files || e.dataTransfer.files;

        files_upload=files;
        isFileUploaded==0;
        // process all File objects

        //Added code to check whether the date and report type has already been selected.

        /*var repdate=$id("report_date").getAttribute("value");
		var reptype=$id("report_type").getAttribute("value");*/


        if(types==0)
        {
            alert("Please select the type of the report");
            return;
        }

        if (files.length>1)
            alert("This feature only allows to upload single file at once");
        else{
            for (var i = 0, f; f = files[i]; i++) {
                //ParseFile(f);
                //UploadFile(f);
                var m = $id("messages");
                //m.innerHTML = msg + m.innerHTML;
                m.innerHTML = "Selected File: " + files[i].name;

            }
        }

    }


    // output file information
    function ParseFile(file) {

        /*Output(
			"<p>File information: <strong>" + file.name +
			"</strong> type: <strong>" + file.type +
			"</strong> size: <strong>" + file.size +
			"</strong> bytes</p>"
		);*/

        //alert("File src:"+file);

        // display an image
        if (file.type.indexOf("image") == 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
                Output(
                    "<p>File information: <strong>" + file.name +
                    "</strong> type: <strong>" + file.type +
                    "</strong> size: <strong>" + file.size +
                    "</strong> bytes</p>"+"<p><strong>" + file.name + 
                    ":</strong><br />" + '<img src="' + e.target.result + '" /></p>'
                );
            }
            reader.readAsDataURL(file);
        }

        // display text
        if (file.type.indexOf("text") == 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
                Output(
                    "<p><strong>" + file.name + ":</strong></p><pre>" +
                    e.target.result.replace(/</g, "&lt;").replace(/>/g, "&gt;") +
                    "</pre>"
                );
            }
            reader.readAsText(file);
        }


        if (file.type.indexOf("pdf") == 12) {
            var reader = new FileReader();
            reader.onload = function(e) {

                //alert("File src:"+e.target.result);

                Output(
                    "<p><strong>" + file.name + ":</strong></p>" +
                    '<br /><iframe style="border:1px solid #666CCC" title="PDF" src="'+ 
                    e.target.result+'" alt="Loading" frameborder="1" scrolling="auto" height="700" width="600" ></iframe>'

                );
            }
            reader.readAsDataURL(file);


        }

    }
    
    function uploadFailedbyExt(evt) {
        alert("The File Type is Not Allowed.");
    }   
        
    // upload JPEG files
    function UploadFile(file) {

        // following line is not necessary: prevents running on SitePoint servers
        if (location.host.indexOf("sitepointstatic") >= 0) return

        var xhr = new XMLHttpRequest();
        //alert("outside Upload");

        //if (xhr.upload && file.type == "image/jpeg" && file.size <= $id("MAX_FILE_SIZE").value) {
        if (xhr.upload) {
            isFileUploaded=1;          
            $("#H2M_Spin_upload").css("visibility", "visible");
            
            //alert("Inside Upload");
            //$id("submitbutton").style.display = "none";

            //alert("report date" + rep_date);

            // create progress bar
            /*var o = $id("progress");
			var progress = o.appendChild(document.createElement("p"));
			progress.appendChild(document.createTextNode("upload " + file.name));
            

			// progress bar
			xhr.upload.addEventListener("progress", function(e) {
				var pc = parseInt(100 - (e.loaded / e.total * 100));
				progress.style.backgroundPosition = pc + "% 0";
			}, false);*/
            
            xhr.onreadystatechange = function(e) {
                if (xhr.readyState == 4) {
                    progress.className = (xhr.status == 200 ? "success" : "failure");
                    if(xhr.status==200) {
                        $("#H2M_Spin_upload").css("visibility", "hidden");
                        //alert('Report created successfully');
                        //Init();
                        isFileUploaded=0;
                        
                        //THE MESSAGE OR WHATEVER MUST BE HERE FOR FILTERING OUT THE FILE EXTENSIONS
                        var response = xhr.responseText.trim();
                        console.log(response);
                        if(response.indexOf('Error') > -1) {
                            if(response.indexOf('Size') == 0) $('#H2M_filetype_error_upload').text('File size is larger than 25MB.');
                            else if(response.indexOf('Ext') == 0) $('#H2M_filetype_error_upload').text('File format is not supported.');
                            
                            $('#H2M_filetype_error_upload').css("visibility", "visible");
                            var elem = $('#H2M_filetype_error_upload');
                            var blinkcount = 0;
                            var intervalID = setInterval(function() {
                                if (elem.css('visibility') == 'hidden') {
                                    elem.css('visibility', 'visible');
                                } else {
                                    elem.css('visibility', 'hidden');
                                }    
                                if (++blinkcount === 5) window.clearInterval(intervalID);
                            }, 500);
                        }
                        //console.log('response: '+response);
                        else setTimeout(function(){window.location.reload()},100);
                    }
                    //$id("submitbutton").style.display = "block";
                }
            };

            // file received/failed
            
            // start upload
            var fd=new FormData();
            fd.append("file",file);

            fd.append("reportdate", rep_date);
            fd.append("reporttype",types);
            
            //alert (file+'   ----    '+rep_date+'   -  '+types);
            xhr.addEventListener("abort", uploadFailedbyExt, false);
            xhr.open("POST", $id("upload").action, true);
            //xhr.setRequestHeader("", file.name);
            xhr.send(fd);
            //alert("Inside Upload END");

        }

    }


    // initialize
    function Init() {

        var fileselect = $id("fileselect"),
            filedrag = $id("filedrag"),
            submitbutton = $id("submitbutton");

        // file select
        fileselect.addEventListener("change", FileSelectHandler, false);

        // is XHR2 available?
        var xhr = new XMLHttpRequest();

        //alert("Init function:"+xhr.upload);
        if (xhr.upload) {

            // file drop
            /*filedrag.addEventListener("dragover", FileDragHover, false);
			filedrag.addEventListener("dragleave", FileDragHover, false);
			filedrag.addEventListener("drop", FileSelectHandler, false);
			filedrag.style.display = "block";*/

            // remove submit button
            //submitbutton.style.display = "none";
        }

    }

    // call initialization file

    if (window.File && window.FileList && window.FileReader) {
        Init();
    }

    /*$("#upload_report").live('click', function(){


		if(rep_date==null || types==0)
			alert("Report Date or Type Missing. Enter these values before proceeding");

		else{

			if (files_upload.length>1)
				alert("This feature only allows to upload single file at once");
			else{
				for (var i = 0, f; f = files_upload[i]; i++) {
					//ParseFile(f);
					UploadFile(f);
				}
			}

		}
		//Init();


	});*/



    //This function will create dialog modal form



   $("#BotonUpload_New").live('click',function(){
        //$( "#dialog-form" ).dialog( "open" );

        // for the HTI version, there is a limit of 5 files. If the user already has 5 files, 
        // then they can't upload anymore and the following modal should not open
	var num = '';
        if (custom_look == "COL") {
		var url = "get_num_reports.php";
		$.ajax({
		  type: 'POST',
		  url: url,
		  data: {id: $("#USERID").val()},
		  success: function(data){
			num = parseInt(data);
		  },
		  async:false
		});
console.log('numrep:'+num);
        }
        if((num < 5 && custom_look == "COL") | custom_look != "COL"){
            var translation = '';
            var translation2 = '';

            if(language == 'th'){
                translation = 'Cargar Informe';
                var translation2 = 'Cancelar';
            }else if(language == 'en'){
                translation = 'Upload Report';
                var translation2 = 'Cancel';
            }

            $( "#upload_reports_form" ).dialog({
                autoOpen: true,
                resizable:false,
                height: 460,
                width: 700,
                modal: true,
                buttons: [{
                    text: translation, click: function() {
                        if(isFileUploaded==0){

                            if(rep_date==null || types==0)
                                alert("Report Date or Type Missing. Enter these values before proceeding");

                            else{
                                if (typeof(files_upload) === 'undefined')
                                    alert("Please select a file first.");
                                else if (files_upload.length>1)
                                    alert("This feature only allows to upload single file at once");
                                else{

                                    for (var i = 0, f; f = files_upload[i]; i++) {
                                        //ParseFile(f);
                                        UploadFile(f);
                                    }
                                }

                            }
                        }else
                            alert("Health2Me detected that you are pressing the Upload button Multiple times! Doesn't matter it will upload file only once");

                    }, style: "background-color: #5EB529; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 5px; width: 150px; font-size: 14px;"},
                          {text: translation2, click: function() {
                              if(isFileUploaded!=0)
                                  alert('Your report is being sent to Health2Me central repository. Please wait!');
                              $(this).dialog("close");
                          }, style: "background-color: #D84840; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 5px; width: 150px; font-size: 14px;"}]
                ,
                close: function() {
                    if(isFileUploaded!=0)
                        alert('Your report is being sent to Health2Me central repository. Please wait!');
                }
            });


        }else{
            alert("You have reached your limit of 5 uploads. To upload more records, you need to delete some records");
        }

    });

    //Changes for adding messagings and notification services 

    //Add changes for multi-referral area
    var referral_id_array=new Array();
    var referral_type_array=new Array();
    var otherdocid=new Array();
    if(ismultireferral==1){
        for (var i=0;i<multireferral_num;i++) { 
            //alert(i);
            //ismultireferral=1;
            referral_id_array[i]=parseInt($('#referral_id'+i).val());
            otherdocid[i]=parseInt($('#otherdocid'+i).val());
            referral_type_array[i]=parseInt($('#referraltype'+i).val());

            if(referral_state_array[i]>=1){
                $("div#ack"+i).css("color","#3d93e0");
                $("div#ack"+i).children("*").css("color","#3d93e0");
                $("div#ack_btn"+i+" a").css("color","#3d93e0");
                $("#ack_ok"+i).show();
                $("div#ack_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right" id="ack_arrow'+i+'"></i>');
                if (referral_state_array[i]>=2){

                    if(referral_type_array[i]==1){
                        $("div#infr_ref"+i).css("color","#3d93e0");
                        $("div#infr_ref"+i).children("*").css("color","#3d93e0");
                        $("div#infr_ref_btn"+i+" a").css("color","#3d93e0");
                        $("div#infr_ref_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
                        if (referral_state_array[i]==3){
                            $("div#cmnt_ref"+i).css("color","#3d93e0");
                            $("div#cmnt_ref"+i).children("*").css("color","#3d93e0");
                            $("div#cmnt_ref_btn"+i+" a").css("color","#3d93e0");
                        }				

                    }else{
                        //$("div#reject_btn").hide();
                        $("div#app"+i).css("color","#3d93e0");
                        $("div#app"+i).children("*").css("color","#3d93e0");
                        $("div#app_btn"+i+" a").css("color","#3d93e0");
                        $("div#app_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
                        if (referral_state_array[i]>=3){
                            $("div#infr"+i).css("color","#3d93e0");
                            $("div#infr"+i).children("*").css("color","#3d93e0");
                            $("div#infr_btn"+i+" a").css("color","#3d93e0");
                            $("div#infr_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
                            if (referral_state_array[i]==4){
                                $("div#inpa"+i).css("color","#3d93e0");
                                $("div#inpa"+i).children("*").css("color","#3d93e0");
                                $("div#inpa_btn"+i+" a").css("color","#3d93e0");

                            }
                        }
                    }			
                }
            }else if(referral_state_array[i]==-1){
                //Disable all ack button
                $("div#ack"+i).css("color","#F16C6C");
                $("div#ack"+i).children("*").css("color","#F16C6C");
                $("div#ack_btn"+i+" a").css("color","#F16C6C");
                $("#ack_ok"+i).show();
                $("div#ack_btn"+i+" a").attr('disabled', 'disabled');


                //Disable app button
                $("div#app"+i).css("color","#F16C6C");
                $("div#app"+i).children("*").css("color","#F16C6C");
                $("div#app_btn"+i+" a").css("color","#F16C6C");
                $("div#app_btn"+i+" a").attr('disabled', 'disabled');

                //Disable infr button
                $("div#infr"+i).css("color","#F16C6C");
                $("div#infr"+i).children("*").css("color","#F16C6C");
                $("div#infr_btn"+i+" a").css("color","#F16C6C");
                $("div#infr_btn"+i+" a").attr('disabled', 'disabled');

                //Disable interview patient button
                $("div#inpa"+i).css("color","#F16C6C");
                $("div#inpa"+i).children("*").css("color","#F16C6C");
                $("div#inpa_btn"+i+" a").css("color","#F16C6C");
                $("div#inpa_btn"+i+" a").attr('disabled', 'disabled');				
            }

            setTimeout(function(){
                $('#newinbox'+i).trigger('click');},1000);

        }} else {

            if(referral_state>=1){
                $("div#ack").css("color","#3d93e0");
                $("div#ack").children("*").css("color","#3d93e0");
                $("div#ack_btn a").css("color","#3d93e0");
                $("#ack_ok").show();
                $("div#ack_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
                if (referral_state>=2){
                    //$("div#reject_btn").hide();
                    $("div#app").css("color","#3d93e0");
                    $("div#app").children("*").css("color","#3d93e0");
                    $("div#app_btn a").css("color","#3d93e0");
                    $("div#app_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
                    if (referral_state>=3){
                        $("div#infr").css("color","#3d93e0");
                        $("div#infr").children("*").css("color","#3d93e0");
                        $("div#infr_btn a").css("color","#3d93e0");
                        $("div#infr_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
                        if (referral_state==4){
                            $("div#inpa").css("color","#3d93e0");
                            $("div#inpa").children("*").css("color","#3d93e0");
                            $("div#inpa_btn a").css("color","#3d93e0");

                        }
                    }
                }
            }

        }

    $("div[id^='reject_btn'] a").live('click',function(){

        var idstring=$(this).parents("div[id^='reject_btn']").attr("id");
        var i=idstring.substring(idstring.length,idstring.length-1);

        //alert (referral_state_array[i]);

        option1=confirm("Are you sure you want to reject this referred patient ?");
        if (option1)
        {

            $("#H2M_Spin").show();
            setTimeout(function(){
                //Disable all ack button
                $("div#ack"+i).css("color","#F16C6C");
                $("div#ack"+i).children("*").css("color","#F16C6C");
                $("div#ack_btn"+i+" a").css("color","#F16C6C");
                $("#ack_ok"+i).show();
                $("#ack_arrow"+i).hide();
                $("div#ack_btn"+i+" a").attr('disabled', 'disabled');


                //Disable app button
                $("div#app"+i).css("color","#F16C6C");
                $("div#app"+i).children("*").css("color","#F16C6C");
                $("div#app_btn"+i+" a").css("color","#F16C6C");
                $("div#app_btn"+i+" a").attr('disabled', 'disabled');

                //Disable infr button
                $("div#infr"+i).css("color","#F16C6C");
                $("div#infr"+i).children("*").css("color","#F16C6C");
                $("div#infr_btn"+i+" a").css("color","#F16C6C");
                $("div#infr_btn"+i+" a").attr('disabled', 'disabled');

                //Disable interview patient button
                $("div#inpa"+i).css("color","#F16C6C");
                $("div#inpa"+i).children("*").css("color","#F16C6C");
                $("div#inpa_btn"+i+" a").css("color","#F16C6C");
                $("div#inpa_btn"+i+" a").attr('disabled', 'disabled');						



                var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=-1';
                salida = LanzaAjax(cadena);

                referral_state_array[i]=-1;

                var cadena='push_server.php?message="Referral has been rejected!"&NotifType=2&IdUsu='+get_idusu+'&FromDoctorId='+both_id+'&channel='+otherdocid[i];
                var RecTipo=LanzaAjax(cadena);
                var content="The referral has been rejected";
                var subject="Referral stage information";
                var reportids=0;
                var cadena='sendMessage.php?sender='+both_id+'&receiver='+otherdocid[i]+'&patient='+mem_id+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
                var RecTipo=LanzaAjax(cadena);
                $("#H2M_Spin").hide();
                displaynotification('status','Referral rejection confirmed!');
                ("div#reject_btn"+i).hide();
            },1000);

        }

    });

    //Adding acknowledgement directly through the system

    $("div[id^='ack_btn_pending'] a").live('click',function(){

        var idstring=$(this).parents("div[id^='ack_btn_pending']").attr("id");
        var i=idstring.substring(idstring.length,idstring.length-1);

        $(this).parents("div.note2").attr('id')
        //alert (i);

        /*if(referral_state_array[i]==0){
				alert('The referral stages functionality is not working. Please contact Health2me!');
				}else{

					if(referral_state_array[i]>1)
						 displaynotification('status','This stage is already complete!');


				}*/

        //alert(referral_id_array[i]);

        var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=1&pending=1';
        //alert(cadena);
        var RecTipo=LanzaAjax(cadena);
        //alert(RecTipo);
        referral_state_array[i]=1;				
        displaynotification('status','You have acknowledged the referred patient!');
        var cadena='push_server.php?message="Referral stage Acknowledgement is complete!"&NotifType=2&IdUsu='+get_idusu+'&FromDoctorId='+both_id+'&channel='+otherdocid[i];
        var RecTipo=LanzaAjax(cadena);
        var content="referal stage Acknowledgement is completed";
        var subject="Referral stage information";
        var reportids=0;
        var cadena='sendMessage.php?sender='+both_id+'&receiver='+otherdocid[i]+'&patient='+mem_id+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
        var RecTipo=LanzaAjax(cadena);
        setTimeout(function(){window.location.reload()},100);

    });


    $("div[id^='ack_btn'] a").live('click',function(){

        var idstring=$(this).parents("div[id^='ack_btn']").attr("id");
        var i=idstring.substring(idstring.length,idstring.length-1);

        $(this).parents("div.note2").attr('id')
        //alert (i);

        if(referral_state_array[i]==0){
            alert('The referral stages functionality is not working. Please contact Health2me!');
        }else{

            if(referral_state_array[i]>=1)
                displaynotification('status','This stage is already complete!');


        }

    });


    $("div[id^='inpa_btn'] a").live('click',function(){
        var idstring=$(this).parents("div[id^='inpa_btn']").attr("id");
        var i=idstring.substring(idstring.length,idstring.length-1);
        //alert (i);
        if(referral_state_array[i]==0){
            alert('The referral stages functionality is not working. Please contact Health2me!');
        }else{

            if(referral_state_array[i]==4)
                displaynotification('status','This stage is already complete!');

            if(referral_state_array[i]<3 && referral_state_array[i]!=-1)
                alert('Previous Stages are not complete!');

            else if(referral_state_array[i]==3){
                $("div#inpa"+i).css("color","#3d93e0");
                $("div#inpa"+i).children("*").css("color","#3d93e0");
                $(this).css("color","#3d93e0");
                var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=4';
                LanzaAjax(cadena);
                referral_state_array[i]=4;
                
                var cadena='push_server.php?message="Referral stages has been completed"&NotifType=2&IdUsu='+get_idusu+'&FromDoctorId='+both_id+'&channel='+otherdocid[i];
                LanzaAjax(cadena);
                var content="referal stage visit is completed";
                var subject="Referral stage information";
                var reportids=0;
                var cadena='sendMessage.php?sender='+both_id+'&receiver='+otherdocid[i]+'&patient='+mem_id+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
                LanzaAjax(cadena);
                displaynotification('status','Referral stages for this patient has been completed!');
                //update referral table.
            }
        }

    });


    //Adding information review for new referrals
    $("div[id^='infr_ref_btn'] a").live('click',function(){
        var idstring=$(this).parents("div[id^='infr_ref_btn']").attr("id");
        var i=idstring.substring(idstring.length,idstring.length-1);
        //alert (i);
        if(referral_state_array[i]==0){
            alert('The referral stages functionality is not working. Please contact Health2me!');
        }else{
            if(referral_state_array[i]>=2)
                displaynotification('status','This stage is already complete!');
            else if(referral_state_array[i]<1 && referral_state_array[i]!=-1){
                alert('Previous Stages are not complete!');

            }else if(referral_state_array[i]==1){
                var cadena='getReportInformationReview.php?referralid='+referral_id_array[i];
                var status=LanzaAjax(cadena);
                var conf='false';
                if(status=='true'){
                    conf='true';
                }
                else {
                    conf=confirm("All the report information has not been reveiwed for this patient. Do you still want to confirm review stage?");
                }
                //var conf='true';
                if(conf){
                    $("div#infr_ref"+i).css("color","#3d93e0");
                    $("div#infr_ref"+i).children("*").css("color","#3d93e0");
                    $(this).css("color","#3d93e0");

                    var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=2';
                    LanzaAjax(cadena);
                    referral_state_array[i]=2;
                    $("div#infr_ref_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
                    var cadena='push_server.php?message="Referral stage information review is completed"&NotifType=2&IdUsu='+get_idusu+'&FromDoctorId='+both_id+'&channel='+otherdocid[i];
                    var RecTipo=LanzaAjax(cadena);
                    var content="referal stage information review is completed";
                    var subject="Referral stage information";
                    var reportids=0;
                    var cadena='sendMessage.php?sender='+both_id+'&receiver='+otherdocid[i]+'&patient='+mem_id+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
                    var RecTipo=LanzaAjax(cadena);
                    displaynotification('status','Referal stage information review is completed');
                    //update referral table.
                }
            }
        }
    });

    var hassentmessage=0;

    $("div[id^='cmnt_ref_btn'] a").live('click',function(){
        var idstring=$(this).parents("div[id^='cmnt_ref_btn']").attr("id");
        var i=idstring.substring(idstring.length,idstring.length-1);
        //alert (i);
        if(referral_state_array[i]==0){
            alert('The referral stages functionality is not working. Please contact Health2me!');
        }else{

            if(referral_state_array[i]==3)
                displaynotification('status','This stage is already complete!');

            if(referral_state_array[i]<2 && referral_state_array[i]!=-1)
                alert('Previous Stages are not complete!');

            else if(referral_state_array[i]==2){
                if(hassentmessage==0){
                    alert('Stage cannot be marked complete as there has not been any communication. Please send atleast one message to complete this stage!');
                }else {
                    $("div#cmnt_ref"+i).css("color","#3d93e0");
                    $("div#cmnt_ref"+i).children("*").css("color","#3d93e0");
                    $(this).css("color","#3d93e0");
                    var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=3';
                    LanzaAjax(cadena);
                    referral_state_array[i]=3;

                    displaynotification('status','Referral stages for this patient completed!');
                    var cadena='push_server.php?message="Referral stages has been completed"&NotifType=2&IdUsu='+get_idusu+'&FromDoctorId='+both_id+'&channel='+otherdocid[i];
                    var RecTipo=LanzaAjax(cadena);
                    var content="referal stage Comment is completed";
                    var subject="Referral stage information";
                    var reportids=0;
                    var cadena='sendMessage.php?sender='+both_id+'&receiver='+otherdocid[i]+'&patient='+mem_id+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
                    var RecTipo=LanzaAjax(cadena);
                    //update referral table.
                }
            }
        }

    });

    //Added for the new referral type-end

    $("div[id^='app_btn'] a").live('click',function(){
        var idstring=$(this).parents("div[id^='app_btn']").attr("id");
        var i=idstring.substring(idstring.length,idstring.length-1);
        //alert (i);
        if(referral_state_array[i]==0){
            alert('The referral stages functionality is not working. Please contact Health2me!');
        }else{
            if(referral_state_array[i]>=2)
                displaynotification('status','This stage is already complete!');


            else if(referral_state_array[i]==1){
                var conf=confirm("Health2me couldn't find any appointments for this patient. Do you confirm that patient meeting has happened?");
                if(conf){
                    $("div#app"+i).css("color","#3d93e0");
                    $("div#app"+i).children("*").css("color","#3d93e0");
                    $(this).css("color","#3d93e0");

                    var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=2';
                    LanzaAjax(cadena);
                    referral_state_array[i]=2;
                    $("div#app_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
                    var cadena='push_server.php?message="Referral stage appointment is completed"&NotifType=2&IdUsu='+get_idusu+'&FromDoctorId='+both_id+'&channel='+otherdocid[i];
                    LanzaAjax(cadena);

                    var content="referal stage appointment is completed";
                    var subject="Referral stage information";
                    var reportids=0;
                    var cadena='sendMessage.php?sender='+both_id+'&receiver='+otherdocid[i]+'&patient='+mem_id+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
                    LanzaAjax(cadena);
                    displaynotification('status','Referal stage Appointment is completed');
                    //update referral table.
                }
            }
        }
    });

    $("div[id^='infr_btn'] a").live('click',function(){
        var idstring=$(this).parents("div[id^='infr_btn']").attr("id");
        var i=idstring.substring(idstring.length,idstring.length-1);
        //alert (i);
        if(referral_state_array[i]==0){
            alert('The referral stages functionality is not working. Please contact Health2me!');
        }else{
            if(referral_state_array[i]>=3)
                displaynotification('status','This stage is already complete!');
            else if(referral_state_array[i]<2 && referral_state_array[i]!=-1){
                alert('Previous Stages are not complete!');

            }else if(referral_state_array[i]==2){
                var cadena='getReportInformationReview.php?referralid='+referral_id_array[i];
                var status=LanzaAjax(cadena);
                var conf='false';
                if(status=='true'){
                    conf='true';
                }
                else {
                    conf=confirm("All the report information has not been reveiwed for this patient. Do you still want to confirm review stage?");
                }
                //var conf='true';
                if(conf){
                    $("div#infr"+i).css("color","#3d93e0");
                    $("div#infr"+i).children("*").css("color","#3d93e0");
                    $(this).css("color","#3d93e0");

                    var cadena='setReferralStage.php?referralid='+referral_id_array[i]+'&stage=3';
                    LanzaAjax(cadena);
                    //$.post("addNotification.php", {type: "REFCNG", sender: both_id, is_sender_doctor: true, receiver: otherdocid[i], is_receiver_doctor: true, auxilary: mem_id}, function(data, status)
                    //{});
                    referral_state_array[i]=3;
                    $("div#infr_btn"+i).append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
                    var cadena='push_server.php?message="Referral stage information review is completed"&NotifType=2&IdUsu='+get_idusu+'&FromDoctorId='+both_id+'&channel='+otherdocid[i];
                    LanzaAjax(cadena);
                    var content="referal stage information review is completed";
                    var subject="Referral stage information";
                    var reportids=0;
                    var cadena='sendMessage.php?sender='+both_id+'&receiver='+otherdocid[i]+'&patient='+mem_id+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id_array[i];
                    LanzaAjax(cadena);
                    displaynotification('status','Referal stage information review is completed');
                    //update referral table.
                }
            }
        }
    });



    $("[id^='newinbox']").live('click',function(){


        // $('#datatable_3_'+i).load(queUrl);
        /* alert(queUrl);
			   $("#datatable_3_"+i).load(queUrl, function( response, status, xhr ) {  
					if ( status == "error" ) { alert('Error loading file'); }
					//if (status == "success") {
					alert (response);

				});*/
        var idstring=$(this).attr("id");
        var i=idstring.substring(idstring.length,idstring.length-1);
        whichdocidclicked=i;
        //alert (i);
        var queUrl ='getInboxMessage.php?IdMED='+both_id+'&patient='+mem_id+'&sendingdoc='+otherdocid[i];
        //var RecTipo=LanzaAjax(queUrl);
        //alert (RecTipo);
        $("#datatable_3_"+i).load(queUrl);
        $("#datatable_3_"+i).trigger('update');
        //alert('triggerend');   
    });

    $("[id^='newoutbox']").live('click',function(){
        //alert('trigger');
        var idstring=$(this).attr("id");
        var i=idstring.substring(idstring.length,idstring.length-1);
        whichdocidclicked=i;
        //alert (i);
        var queUrl ='getOutboxMessage.php?IdMED='+both_id+'&patient='+mem_id+'&receivingdoc='+otherdocid[i];
        $('#datatable_4_'+i).load(queUrl);
        $('#datatable_4_'+i).trigger('update');

    }); 


    setTimeout(function(){
        $("[id^='newinbox']").trigger('click');},1000);
    /*setTimeout(function(){
				$("[id^='newinbox1']").trigger('click');},1000);*/

    var whichdocidclicked=0;

    /*function setWhichDoctorClicked(in){
			whichdocidclicked=in;
		}*/

    $("[id^='referraldoctab']").live('click',function(){

        var idstring=$(this).attr("id");
        var i=idstring.substring(idstring.length,idstring.length-1);
        //This variable sets the global parameter to the doctor

        whichdocidclicked=i;

    });
    //Changes added for multireferral
    $("[id^='compose_message']").live('click',function(){

        var idstring=$(this).attr("id");
        var i=idstring.slice(-1);
        //This variable sets the global parameter to the doctor

        whichdocidclicked=i;
        //alert("In client "+whichdocidclicked);
        $('#messagecontent_inbox').attr('value','');
        $('#subjectname_inbox').attr('value','');
        $('#subjectname_inbox').removeAttr("readonly");   
        $('#messagedetails').hide();
        $('#replymessage').show();
        $("#attachments").empty();
        $('#attachments').hide();
        $('#Reply').hide();
        $("#CloseMessage").hide();
        $('#Attach').hide();
        $('#sendmessages_inbox').show();
        $('#attachreports').show();
        $('#message_modal').trigger('click');


    });

    var reportids='';
    var reportcheck = new Array();

    $('.CFILA').live('click',function(){
        var id = $(this).attr("id");
        //displaynotification('Message ID'+ id);
        $('input[type=checkbox][id^="reportcol"]').each(
            function () {
                $('input[type=checkbox][id^="reportcol"]').checked=false;
            });
        reportcheck.length=0;
        var content=$(this).find('span#'+id).text().replace(/br8k/g,"\n").replace(/sp0e/g," ");
        $(this).find('span').hide();
        var reportsattached=$(this).find('ul#'+id).text();
        //alert(reportsattached);
        $("#attachments").empty();
        if(reportsattached){
            //alert("into attachments");
            var ElementDOM ='All';
            var EntryTypegroup ='0';
            var Usuario = $('#userId').val();
            var MedID =$('#MEDID').val();

            var queUrl ='createAttachmentStreamNEWTEST.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports='+reportsattached;
            var RecTipo=LanzaAjax(queUrl);
            //alert(RecTipo);
            $("#attachments").append(RecTipo);
            /*$("#attachments").load(queUrl);
    	$("#attachments").trigger('update');*/
            $("#attachments").show();
        }else{
            $('#attachments').hide();
            //alert("no attachments");
        }
        //content.replace(/[break]/g,"\n").replace(/[space]/g," ");
        //alert($(this).find('a').text());
        //displaynotification('Message read');
        //$('#attachments').hide();
        $('#Attach').hide();
        $('#messagedetails').show();
        $('#replymessage').hide();
        $("#Reply").attr('value','Reply');
        $("#Reply").show();
        $("#CloseMessage").show();
        $('#messagedetails').val(content);
        $('#messagedetails').attr('readonly','readonly');
        $('#messagedetails,#subjectname_inbox').css("cursor","pointer");
        $('#subjectname_inbox').val($(this).find('a').text());
        $('#subjectname_inbox').attr('readonly','readonly');
        $('#replymessage').hide();
        $('#sendmessages_inbox').hide();
        $('#attachreports').hide();
        //$('#clearmessage').hide();
        $('#message_modal').trigger('click');
        var cadena='setMessageStatus.php?msgid='+id;
        var RecTipo=LanzaAjax(cadena);
        setTimeout(function(){
            $('#newinbox'+whichdocidclicked).trigger('click');},1000);

    });

    $('.CFILA_out').live('click',function(){
        var id = $(this).attr("id");
        //displaynotification('Message ID'+ id);
        $('input[type=checkbox][id^="reportcol"]').each(
            function () {
                $('input[type=checkbox][id^="reportcol"]').checked=false;
            });
        reportcheck.length=0;
        var content=$(this).find('span#'+id).text().replace(/br8k/g,"\n").replace(/sp0e/g," ");
        $(this).find('span').hide();
        var reportsattached=$(this).find('ul#'+id).text();
        //alert(reportsattached);
        $("#attachments").empty();
        if(reportsattached){
            //alert("into attachments");
            var ElementDOM ='All';
            var EntryTypegroup ='0';
            var Usuario = $('#userId').val();
            var MedID =$('#MEDID').val();

            var queUrl ='createAttachmentStreamNEWTEST.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports='+reportsattached;
            var RecTipo=LanzaAjax(queUrl);
            //alert(RecTipo);
            $("#attachments").append(RecTipo);
            /*$("#attachments").load(queUrl);
    	$("#attachments").trigger('update');*/
            $("#attachments").show();
        }else{
            $('#attachments').hide();
            //alert("no attachments");
        }
        //content.replace(/[break]/g,"\n").replace(/[space]/g," ");
        //alert($(this).find('a').text());
        //displaynotification('Message read');
        //$('#attachments').hide();
        $('#Attach').hide();
        $('#messagedetails').show();
        $('#replymessage').hide();
        $("#Reply").attr('value','Reply');
        $("#Reply").hide();
        $("#CloseMessage").show();
        $('#messagedetails').val(content);
        $('#messagedetails').attr('readonly','readonly');
        $('#messagedetails,#subjectname_inbox').css("cursor","pointer");
        $('#subjectname_inbox').val($(this).find('a').text());
        $('#subjectname_inbox').attr('readonly','readonly');
        $('#replymessage').hide();
        $('#sendmessages_inbox').hide();
        $('#attachreports').hide();
        //$('#clearmessage').hide();
        $('#message_modal').trigger('click');
        /*var cadena='setMessageStatus.php?msgid='+id;
	   var RecTipo=LanzaAjax(cadena);
	   setTimeout(function(){
	   $('#newoutbox').trigger('click');},1000);*/

    });

    var sendattachflag = false;
    
    $("#Reply").live('click',function(){

        var subject_name=(sendattachflag?'':'RE:')+($('#subjectname_inbox').val()).replace(/RE:/,'');
        $('#subjectname_inbox').val(subject_name);   
        $('#messagedetails').hide();
        $('#replymessage').show();
        $('#attachments').hide();
        $('#Attach').hide();
        $(this).hide();
        $("#CloseMessage").hide();
        $('#sendmessages_inbox').show();
        $('#attachreports').show();
        //$('#clearmessage').show();

    });


    $("#Attach").live('click',function(){
        reportids='';
        sendattachflag = true;
        //alert ('clicked');
        $('input[type=checkbox][id^="reportcol"]').each(
            function () {
                var sThisVal = (this.checked ? "1" : "0");

                //sList += (sList=="" ? sThisVal : "," + sThisVal);
                if(sThisVal==1){
                    var idp=$(this).parents("div.attachments").attr("id");
                    //alert("Id "+idp+" selected"); 
                    reportcheck.push(this.id);
                    //messageid=messageid+idp+' ,';
                    reportids=reportids+idp+' ';

                    /*var cadena='setMessageStatus.php?msgid='+idp+'&delete=1';
				 LanzaAjax(cadena);*/
                }


            });
        //alert(reportids);
        var conf=false;
        if(reportids>'')
            conf=confirm("Confirm Attachments");

        if(conf){
            $("#Reply").trigger('click');
            $("#attachreportdiv").append('<i id="attachment_icon" class="icon-paper-clip" style="margin-left:10px"></i>');
            //alert(reportids);
        }else{
            reportids='';
            for (i = 0 ; i < reportcheck.length; i++ ){

                document.getElementById(reportcheck[i]).checked = false;

            }
            reportcheck.length=0;
            $("#Reply").trigger('click');
        }

    });

    var isloaded=false;   //This variable is to make sure the page loads the report only once.

    $('#attachreports').live('click',function(){

        $('input[type=checkbox][id^="reportcol"]').each(
            function () {
                var sThisVal = (this.checked ? "1" : "0");
                if(sThisVal==1){
                    reportcheck.push(this.id);
                }

            });
        /*if(!isloaded){
	//$('#attachments').remove();*/
        $("#attachments").empty();
        createPatientReports();
        //isloaded=true;
        //}
        setTimeout(function(){
            for (i = 0 ; i < reportcheck.length; i++ ){

                document.getElementById(reportcheck[i]).checked = true;

            }},400);
        $("#attachment_icon").remove();
        $('#sendmessages_inbox').hide();
        $('#replymessage').hide();
        $(this).hide();   
        $('#attachments').show();
        $('#Attach').show();
        $("#Reply").attr('value','Back');
        $("#Reply").show();


    });


    $('#sendmessages').live('click',function(){

        //alert('sending multireferral message'+ whichdocidclicked);
        var sel=$('#doctorsdetails').find(":selected").attr('id');
        var content=$('#messagecontent').val().replace(/ /g,"sp0e").replace(/\r\n|\r|\n/g,"br8k");
        //boxText.replace(/<br\s?\/?>/g,"\n");
        var subject=$('#subjectname').val();
        if(subject==''||content=='')
            displaynotification('Error','Error sending message.Empty subject or content area!');
        else{
            var cadena='sendMessage.php?sender='+both_id+'&receiver='+otherdocid[whichdocidclicked]+'&patient='+mem_id+'&content='+content+'&subject='+subject;
            var RecTipo=LanzaAjax(cadena);
            //alert(RecTipo);
            $('#messagecontent').attr('value','');
            $('#subjectname').attr('value','');
            displaynotification('status',RecTipo);
            //$('#add-regular').trigger('click');
            var cadena='push_server.php?message="message from a doctor"&NotifType=1&IdUsu='+get_idusu+'&FromDoctorId='+both_id+'&channel='+otherdocid[whichdocidclicked];
            var RecTipo=LanzaAjax(cadena);
        }



    });

    $('#sendmessages_inbox').live('click',function(){

        var sel=$('#doctorsdetails').find(":selected").attr('id');
        var content=$('#messagecontent_inbox').val();
        //.replace(/ /g,"sp0e").replace(/\r\n|\r|\n/g,"br8k");
        //boxText.replace(/<br\s?\/?>/g,"\n");
        var subject=$('#subjectname_inbox').val();
        
        //WHEN A SUBJECT OR CONTENT IS EMPTY
        if(content.trim() == '' || subject.trim() == '') {
            swal('Empty Message', 'You should write down on subject and content', 'warning');
            return false;
        }
        //alert(reportids);
        reportids = reportids.replace(/\s+$/g,' ');
        /*if(subject==''||content=='')
	  displaynotification('Error','Error sending message.Empty subject or content area!');
	 else{*/
        //Added for updating comment stage for new referrral type
        if(referral_type_array[whichdocidclicked]==1){
            if(referral_state_array[whichdocidclicked]==2)
                hassentmessage=1;
        }


        var cadena='sendMessage.php?sender='+both_id+'&receiver='+otherdocid[whichdocidclicked]+'&patient='+mem_id+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+referral_id+'&type=patientdetailmed';
        

        var RecTipo=LanzaAjax(cadena);
        //alert(RecTipo);
        $('#messagecontent_inbox').attr('value','');
        $('#subjectname_inbox').attr('value','');
        displaynotification('status',RecTipo);
        //$('#add-regular').trigger('click');

        var cadena='push_server.php?FromDoctorName='+id_med_name+'&FromDoctorSurname='+id_med_surname+'&Patientname='+med_user_name+'&PatientSurname='+med_user_surname+'&IdUsu='+mem_id+'&message= New Message <br>From: Dr. '+id_med_name+' '+id_med_surname+'<br>Subject: '+(subject).replace(/RE:/,'')+'&NotifType=1&IdUsu='+get_idusu+'&FromDoctorId='+both_id+'&channel='+otherdocid[whichdocidclicked];


        //alert(cadena);
        var RecTipo=LanzaAjax(cadena);
        //}
        reportids='';
        $("#attachment_icon").remove();
        $('#message_modal').trigger('click');
    }); 


    $("[id^='delete_message']").live('click',function(){

        var idstring=$(this).attr("id");
        var i=idstring.substring(idstring.length,idstring.length-1);
        setWhichDoctorClicked=i;
        //alert (i);
        var num=0;
        var conf=confirm('The message will be deleted permanently!Press Ok to continue.');
        if(conf){
            $('input[type=checkbox][id^="checkcol"]').each(
                function () {
                    var sThisVal = (this.checked ? "1" : "0");

                    //sList += (sList=="" ? sThisVal : "," + sThisVal);
                    if(sThisVal==1){
                        var idp=$(this).parents("tr.CFILA_P").attr("id");
                        //alert("Id "+idp+" selected"); 
                        //messageid=messageid+idp+' ,';
                        num=num+1;
                        var cadena='setMessageStatus.php?msgid='+idp+'&delete=1';
                        LanzaAjax(cadena);
                    }


                });

            if(num)
            {
                setTimeout(function(){
                    $('#newinbox'+setWhichDoctorClicked).trigger('click');},50);
                displaynotification('status','Seleted Messages Deleted!');
            }else{
                displaynotification('status','No Messages Selected!');
            }
        }

    }); 







    /*$("button[id^='notif_btn']").live('click',function(){
   //alert('clicked closed'+$(this).parents("div[id^='notif_']").attr("id"));
   //$("div.ichat").show();

   var str=$(this).parents("div[id^='notif_']").attr("id");
   var id=/\d+/.exec(str);

   var cadena ='<?php echo $domain;?>/setNotificationStatus.php?Id='+id;
   var status=LanzaAjax(cadena);
   //alert(status);
   //$("div[id^='notif']").remove();  
   $(this).parents("div[id^='notif_']").remove();   
   e.stopPropagation(); // This is the preferred method.
   return false;    
   //$("div.ichat").show();
   });*/

    $("div[id^='notif_']").live('click',function(){
        //alert('clicked closed'+$(this).parents("div[id^='notif_']").attr("id"));
        //$("div.ichat").show();

        var str=$(this).attr("id");
        var id=/\d+/.exec(str);

        var cadena ='setNotificationStatus.php?Id='+id;
        var status=LanzaAjax(cadena);
        //alert(status);
        //$("div[id^='notif']").remove();  
        $(this).remove();   
        e.stopPropagation(); // This is the preferred method.
        return false;    
        //$("div.ichat").show();
    });

    $("div[id^='notif_']").live("mouseenter",function () {
        $(this).css("background","LightSteelBlue");
        $(this).css("cursor","pointer");
    });

    $("div[id^='notif_']").live("mouseleave",function () {
        $(this).css("background","");
    });

    /*$("div#app_btn a,div#infr_btn a").live('click',function(){

	if($(this).attr("title")=="Schedule"){
	$("div#app").css("color","#3d93e0");
	$("div#app").children("*").css("color","#3d93e0");
	$(this).css("color","#3d93e0");
	}else if($(this).attr("title")=="IReview"){
	$("div#infr").css("color","#3d93e0");
	$("div#infr").children("*").css("color","#3d93e0");
	$(this).css("color","#3d93e0");
	}

   });*/
    /*var queUrl ='<?php echo $domain;?>/getInboxMessage.php?IdMED=<?php echo $MedID;?>';
   $('#datatable_3').load(queUrl);
   $('#datatable_3').trigger('update');*/

    //Below code is written for status update on the notification window

    var cadena ='getNotificationCount.php?IdMED='+both_id;
    var getCount=LanzaAjax(cadena);
    if(parseInt(getCount)){
        $('#notificaton_num').text(getCount);
    }

    var num=parseInt($('#notificaton_num').text());
    if(!num){
        $('#notificaton_num').hide();
        $('#notification_triangle').hide();
    }

    setTimeout(function(){
        $('#newinbox').trigger('click');},1000);

    $(document).click(function() {

        //$('#notificaton_num').hide();
        //$('#notification_triangle').hide();
        $("#notification_window").find("*").hide(); 
    });

    $("a#notification_bar").live('click',function(e){

        //$("#notification_window").show();   
        $("a#notification_bar").toggle(
            function(){
                var notify_num=parseInt($('#notificaton_num').text());
                if(notify_num){
                    $('#notificaton_num').text(0);
                    $('#notificaton_num').hide();
                    $('#notification_triangle').hide();
                }
                //var notify_num=78;
                var queUrl ='getNotificationMessages.php?IdMED='+both_id;
                //

                setTimeout(function(){
                    $("#getnotificationmessages").load(queUrl);
                    $("#getnotificationmessages").trigger('update');
                },1); 
                $("#notification_window").find("*").show();	
                //$("#getnotificationmessages").find("*").show();
                //alert('here');
            }
            ,function(){  
                //alert('in hidden option');
                $("#notification_window").find("*").hide();   
            });

        e.stopPropagation(); // This is the preferred method.
        //return false;    

    });



    /*      
   $("div#ack_btn a").live('click',function(){
    if(referral_state==0){
	alert('The referral stages functionality is not working. Please contact Health2me!');
	}else{

		if(referral_state>1)
			 displaynotification('status','This stage is already complete!');


	}

	});


   $("div#inpa_btn a").live('click',function(){
    if(referral_state==0){
	alert('The referral stages functionality is not working. Please contact Health2me!');
	}else{

	    if(referral_state==4)
			 displaynotification('status','This stage is already complete!');

		if(referral_state<3)
			 alert('Previous Stages are not complete!');

		else if(referral_state==3){
		$("div#inpa").css("color","#3d93e0");
		$("div#inpa").children("*").css("color","#3d93e0");
		$(this).css("color","#3d93e0");
		var cadena='setReferralStage.php?referralid=<?php echo $referral_id;?>&stage=4';
		LanzaAjax(cadena);
		referral_state=4;
		displaynotification('status','Referral stages for this patient completed!');
		var cadena='push_server.php?message="Referral stages has been completed"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+<?php echo $otherdoc?>;
		var RecTipo=LanzaAjax(cadena);
		var content="referal stage visit is completed. This is a System Generated Message";
		var subject="Referral stage information";
		var reportids=0;
		var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php echo $referral_id;?>';
		var RecTipo=LanzaAjax(cadena);
		//update referral table.
		}
	}

	});

	$("div#app_btn a").live('click',function(){
	if(referral_state==0){
	alert('The referral stages functionality is not working. Please contact Health2me!');
	}else{
		if(referral_state>=2)
			 displaynotification('status','This stage is already complete!');


		else if(referral_state==1){
			var conf=confirm("Health2me couldn't find any appointments for this patient. Do you confirm that patient meeting has happened?");
			if(conf){
				$("div#app").css("color","#3d93e0");
				$("div#app").children("*").css("color","#3d93e0");
				$(this).css("color","#3d93e0");

				var cadena='setReferralStage.php?referralid=<?php echo $referral_id;?>&stage=2';
				LanzaAjax(cadena);
				referral_state=2;
				$("div#app_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
				var cadena='push_server.php?message="Referral stage appointment is completed"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+<?php echo $otherdoc?>;
				var RecTipo=LanzaAjax(cadena);

				var content="referal stage appointment is completed. This is a System Generated Message";
				var subject="Referral stage information";
				var reportids=0;
				var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php echo $referral_id;?>';
				var RecTipo=LanzaAjax(cadena);

				//update referral table.
			}
		 }
	 }
   });

   $("div#infr_btn a").live('click',function(){
   if(referral_state==0){
	alert('The referral stages functionality is not working. Please contact Health2me!');
	}else{
		if(referral_state>=3)
			 displaynotification('status','This stage is already complete!');
		else if(referral_state<2){
			 alert('Previous Stages are not complete!');

		}else if(referral_state==2){
			var cadena='getReportInformationReview.php?referralid=<?php echo $referral_id; ?>';
			var status=LanzaAjax(cadena);
			var conf='false';
			if(status=='true'){
			 conf='true';
			}
			else {
			 conf=confirm("All the report information has not been reveiwed for this patient. Do you still want to confirm review stage?");
			}
			//var conf='true';
			if(conf){
				$("div#infr").css("color","#3d93e0");
				$("div#infr").children("*").css("color","#3d93e0");
				$(this).css("color","#3d93e0");

				var cadena='setReferralStage.php?referralid=<?php echo $referral_id;?>&stage=3';
				LanzaAjax(cadena);
				referral_state=3;
				$("div#infr_btn").append('<i style="margin-left:10px;font-size:15px" class="icon-arrow-right"></i>');
				var cadena='push_server.php?message="Referral stage information review is completed"&NotifType=2&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+<?php echo $otherdoc?>;
				var RecTipo=LanzaAjax(cadena);
				var content="Referal stage information review is completed. This is a System Generated Message";
				var subject="Referral stage information";
				var reportids=0;
				var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php echo $referral_id;?>';
				var RecTipo=LanzaAjax(cadena);
				displaynotification('status','Referal stage information review is completed');
				//update referral table.
			}
		 }
	}
   });

   $('#newinbox').live('click',function(){
      //alert('trigger');
	 var queUrl ='<?php echo $domain;?>/getInboxMessage.php?IdMED=<?php echo $MedID;?>&patient=<?php echo $USERID;?>&sendingdoc=0';
	   $('#datatable_3').load(queUrl);
	   $('#datatable_3').trigger('update');

   });

   $('#newoutbox').live('click',function(){
      //alert('trigger');
	 var queUrl ='<?php echo $domain;?>/getOutboxMessage.php?IdMED=<?php echo $MedID;?>&patient=<?php echo $USERID;?>&receivingdoc=0';
	   $('#datatable_4').load(queUrl);
	   $('#datatable_4').trigger('update');

   });

   $('#compose_message').live('click',function(){

   $('#messagecontent_inbox').attr('value','');
   $('#subjectname_inbox').attr('value','');
   $('#subjectname_inbox').removeAttr("readonly");   
   $('#messagedetails').hide();
   $('#replymessage').show();
   $("#attachments").empty();
   $('#attachments').hide();
   $('#Reply').hide();
   $("#CloseMessage").hide();
   $('#Attach').hide();
   $('#sendmessages_inbox').show();
   $('#attachreports').show();
   $('#message_modal').trigger('click');


   });

   $('.CFILA').live('click',function(){
       var id = $(this).attr("id");
	   //displaynotification('Message ID'+ id);
	   $('input[type=checkbox][id^="reportcol"]').each(
        function () {
		 $('input[type=checkbox][id^="reportcol"]').checked=false;
		});
	   reportcheck.length=0;
	   var content=$(this).find('span#'+id).text().replace(/br8k/g,"\n").replace(/sp0e/g," ");
	   $(this).find('span').hide();
	   var reportsattached=$(this).find('ul#'+id).text();
	   //alert(reportsattached);
	   $("#attachments").empty();
	   if(reportsattached){
	   //alert("into attachments");
	   var ElementDOM ='All';
	   var EntryTypegroup ='0';
	   var Usuario = $('#userId').val();
	   var MedID =$('#MEDID').val();

		var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports='+reportsattached;
	    var RecTipo=LanzaAjax(queUrl);
		//alert(RecTipo);
		$("#attachments").append(RecTipo);
      	/*$("#attachments").load(queUrl);
    	$("#attachments").trigger('update');*/
    /*		$("#attachments").show();
		}else{
		$('#attachments').hide();
		//alert("no attachments");
		}
	   //content.replace(/[break]/g,"\n").replace(/[space]/g," ");
	   //alert($(this).find('a').text());
	   //displaynotification('Message read');
	   //$('#attachments').hide();
	   $('#Attach').hide();
	   $('#messagedetails').show();
       $('#replymessage').hide();
	   $("#Reply").attr('value','Reply');
       $("#Reply").show();
       $("#CloseMessage").show();
	   $('#messagedetails').val(content);
	   $('#messagedetails').attr('readonly','readonly');
	   $('#messagedetails,#subjectname_inbox').css("cursor","pointer");
	   $('#subjectname_inbox').val($(this).find('a').text());
	   $('#subjectname_inbox').attr('readonly','readonly');
	   $('#replymessage').hide();
	   $('#sendmessages_inbox').hide();
	   $('#attachreports').hide();
	   //$('#clearmessage').hide();
	   $('#message_modal').trigger('click');
	   var cadena='setMessageStatus.php?msgid='+id;
	   var RecTipo=LanzaAjax(cadena);
	   setTimeout(function(){
	   $('#newinbox').trigger('click');},1000);

   });

    $('.CFILA_out').live('click',function(){
       var id = $(this).attr("id");
	   //displaynotification('Message ID'+ id);
	   $('input[type=checkbox][id^="reportcol"]').each(
        function () {
		 $('input[type=checkbox][id^="reportcol"]').checked=false;
		});
	   reportcheck.length=0;
	   var content=$(this).find('span#'+id).text().replace(/br8k/g,"\n").replace(/sp0e/g," ");
	   $(this).find('span').hide();
	   var reportsattached=$(this).find('ul#'+id).text();
	   //alert(reportsattached);
	   $("#attachments").empty();
	   if(reportsattached){
	   //alert("into attachments");
	   var ElementDOM ='All';
	   var EntryTypegroup ='0';
	   var Usuario = $('#userId').val();
	   var MedID =$('#MEDID').val();

		var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports='+reportsattached;
	    var RecTipo=LanzaAjax(queUrl);
		//alert(RecTipo);
		$("#attachments").append(RecTipo);
      	/*$("#attachments").load(queUrl);
    	$("#attachments").trigger('update');*/
    /*		$("#attachments").show();
		}else{
		$('#attachments').hide();
		//alert("no attachments");
		}
	   //content.replace(/[break]/g,"\n").replace(/[space]/g," ");
	   //alert($(this).find('a').text());
	   //displaynotification('Message read');
	   //$('#attachments').hide();
	   $('#Attach').hide();
	   $('#messagedetails').show();
       $('#replymessage').hide();
	   $("#Reply").attr('value','Reply');
       $("#Reply").hide();
       $("#CloseMessage").show();
	   $('#messagedetails').val(content);
	   $('#messagedetails').attr('readonly','readonly');
	   $('#messagedetails,#subjectname_inbox').css("cursor","pointer");
	   $('#subjectname_inbox').val($(this).find('a').text());
	   $('#subjectname_inbox').attr('readonly','readonly');
	   $('#replymessage').hide();
	   $('#sendmessages_inbox').hide();
	   $('#attachreports').hide();
	   //$('#clearmessage').hide();
	   $('#message_modal').trigger('click');
	   /*var cadena='setMessageStatus.php?msgid='+id;
	   var RecTipo=LanzaAjax(cadena);
	   setTimeout(function(){
	   $('#newoutbox').trigger('click');},1000);*/
    /*	   
   });

   var reportids='';
   var reportcheck = new Array();



   $("#Reply").live('click',function(){
    //reportids='';
	/*$('input[type=checkbox][id^="reportcol"]').each(
     function () {
				var sThisVal = (this.checked ? "1" : "0");
				if(sThisVal==1){
				reportcheck.push(this.id);
				}

	});
	for (i = 0 ; i < reportcheck.length; i++ ){

		document.getElementById(reportcheck[i]).checked = false;

	}*/
    /*   var subject_name='RE:'+($('#subjectname_inbox').val()).replace(/RE:/,'');
   $('#subjectname_inbox').val(subject_name);   
   $('#messagedetails').hide();
   $('#replymessage').show();
   $('#attachments').hide();
   $('#Attach').hide();
   $(this).hide();
   $("#CloseMessage").hide();
   $('#sendmessages_inbox').show();
   $('#attachreports').show();
   //$('#clearmessage').show();

   });

   $("#Attach").live('click',function(){
     reportids='';
       alert ('clicked');
    $('input[type=checkbox][id^="reportcol"]').each(
     function () {
				var sThisVal = (this.checked ? "1" : "0");

				//sList += (sList=="" ? sThisVal : "," + sThisVal);
				if(sThisVal==1){
				 var idp=$(this).parents("div.attachments").attr("id");
				//alert("Id "+idp+" selected"); 
				reportcheck.push(this.id);
				 //messageid=messageid+idp+' ,';
				 reportids=reportids+idp+' ';

				 /*var cadena='setMessageStatus.php?msgid='+idp+'&delete=1';
				 LanzaAjax(cadena);*/
    /*				}


	});
	 //alert(reportids);
	var conf=false;
	if(reportids>'')
		conf=confirm("Confirm Attachments");

	if(conf){
	$("#Reply").trigger('click');
	$("#attachreportdiv").append('<i id="attachment_icon" class="icon-paper-clip" style="margin-left:10px"></i>');
	//alert(reportids);
	}else{
	reportids='';
	for (i = 0 ; i < reportcheck.length; i++ ){

		document.getElementById(reportcheck[i]).checked = false;

	}
	reportcheck.length=0;
	$("#Reply").trigger('click');
	}

   });

   var isloaded=false;   //This variable is to make sure the page loads the report only once.

   $('#attachreports').live('click',function(){

    $('input[type=checkbox][id^="reportcol"]').each(
     function () {
				var sThisVal = (this.checked ? "1" : "0");
				if(sThisVal==1){
				reportcheck.push(this.id);
				}

	});
	/*if(!isloaded){
	//$('#attachments').remove();*/
    /*	$("#attachments").empty();
	createPatientReports();
	//isloaded=true;
	//}
	setTimeout(function(){
	for (i = 0 ; i < reportcheck.length; i++ ){

		document.getElementById(reportcheck[i]).checked = true;

	}},400);
   $("#attachment_icon").remove();
   $('#sendmessages_inbox').hide();
   $('#replymessage').hide();
   $(this).hide();   
   $('#attachments').show();
   $('#Attach').show();
   $("#Reply").attr('value','Back');
   $("#Reply").show();


   });

  $('#sendmessages').live('click',function(){

	 var sel=$('#doctorsdetails').find(":selected").attr('id');
	 var content=$('#messagecontent').val().replace(/ /g,"sp0e").replace(/\r\n|\r|\n/g,"br8k");
	 //boxText.replace(/<br\s?\/?>/g,"\n");
	 var subject=$('#subjectname').val();
	 if(subject==''||content=='')
	  displaynotification('Error','Error sending message.Empty subject or content area!');
	 else{
	 var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject;
	 var RecTipo=LanzaAjax(cadena);
	 //alert(RecTipo);
	 $('#messagecontent').attr('value','');
	 $('#subjectname').attr('value','');
	 displaynotification('status',RecTipo);
	 //$('#add-regular').trigger('click');
	 var cadena='push_server.php?message="message from a doctor"&NotifType=1&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+<?php echo $otherdoc?>;
	 var RecTipo=LanzaAjax(cadena);
	 }



  });

  $('#sendmessages_inbox').live('click',function(){

	 var sel=$('#doctorsdetails').find(":selected").attr('id');
	 var content=$('#messagecontent_inbox').val().replace(/ /g,"sp0e").replace(/\r\n|\r|\n/g,"br8k");
	 //boxText.replace(/<br\s?\/?>/g,"\n");
	 var subject=$('#subjectname_inbox').val();
	 //alert(reportids);
	 reportids = reportids.replace(/\s+$/g,' ');
	 /*if(subject==''||content=='')
	  displaynotification('Error','Error sending message.Empty subject or content area!');
	 else{*/
    /*	 var cadena='sendMessage.php?sender=<?php echo $MedID;?>&receiver=<?php echo $otherdoc;?>&patient=<?php echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php echo $referral_id;?>';
	 var RecTipo=LanzaAjax(cadena);
	 //alert(RecTipo);
	 $('#messagecontent_inbox').attr('value','');
	 $('#subjectname_inbox').attr('value','');
	 displaynotification('status',RecTipo);
	 //$('#add-regular').trigger('click');
	 var cadena='push_server.php?FromDoctorName=<?php echo $IdMEDName;?>&FromDoctorSurname=<?php echo $IdMEDSurname;?>&Patientname=<?php echo $MedUserName; ?>&PatientSurname=<?php echo $MedUserSurname; ?>&IdUsu=<?php echo $USERID;?>&message= New Message <br>From: Dr. <?php echo $IdMEDName;?> <?php echo $IdMEDSurname;?><br>Subject: '+(subject).replace(/RE:/,'')+'&NotifType=1&IdUsu=<?php echo $IdUsu;?>&FromDoctorId=<?php echo $IdMed;?>&channel='+<?php echo $otherdoc?>;
	 //alert(cadena);
	 var RecTipo=LanzaAjax(cadena);
	 //}
	 reportids='';
	 $("#attachment_icon").remove();
	 $('#message_modal').trigger('click');
  });

  $("#delete_message").live('click',function(){
   var num=0;
   var conf=confirm('The message will be deleted permanently!Press Ok to continue.');
   if(conf){
   $('input[type=checkbox][id^="checkcol"]').each(
   function () {
				var sThisVal = (this.checked ? "1" : "0");

				//sList += (sList=="" ? sThisVal : "," + sThisVal);
				if(sThisVal==1){
				 var idp=$(this).parents("tr.CFILA_P").attr("id");
				 //alert("Id "+idp+" selected"); 
				 //messageid=messageid+idp+' ,';
				 num=num+1;
				 var cadena='setMessageStatus.php?msgid='+idp+'&delete=1';
				 LanzaAjax(cadena);
				}


	});

	if(num)
	{
	setTimeout(function(){
	   $('#newinbox').trigger('click');},50);
	displaynotification('status','Seleted Messages Deleted!');
	}else{
	displaynotification('status','No Messages Selected!');
	}
	}

   });

 */ 
    function createPatientReports(){
        var ElementDOM ='All';
        var EntryTypegroup ='0';
        var Usuario = $('#userId').val();
        var MedID =$('#MEDID').val();

        var queUrl ='createAttachmentStreamNEWTEST.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
        //var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports=1226';
        $("#attachments").load(queUrl);
        $("#attachments").trigger('update');

    }

    function getScriptsAsText() {
        var div = document.createElement('div');
        var scripts = [];
        var scriptNodes = document.getElementsByTagName('script');
        for (var i=0, iLen=scriptNodes.length; i<iLen; i++) {
            div.appendChild(scriptNodes[i].cloneNode(true));
            scripts.push(div.innerHTML);
            div.removeChild(div.firstChild);
        }
        return scripts;
    }
    function highlightattachedreports(){
        //alert(multireferral_num+"andar");
        for (var j=1;j<multireferral_num;j++)
        { 
            var attcolor=$('#referralcolor'+j).val();
            //alert(attcolor);
            var reportstobereviewed=$('#reportid_review'+j).val();
            //alert(reportstobereviewed);
            var reportstobereviewed_ids=reportstobereviewed.split(" "); 
            for (var i = 0, len = reportstobereviewed_ids.length; i < len; ++i)
            {
                if(reportstobereviewed_ids[i].length==0)
                {
                    continue; 
                }
                //id^="reportcol"]'
                $('i[id^="report-eye"]').each(function(){

                    var id=parseInt($(this).parents("div.note2").attr('id'));
                    //alert(id);
                    if(id==parseInt(reportstobereviewed_ids[i]))
                    {//alert('highlighting '+id);
                        $(this).css("color","#000000");
                        // $(this).parents("div.note2").css({"border": "3px solid blue"});

                        $(this).parents("div.note2").css({"border": "2px solid"});
                        $(this).parents("div.note2").css({"border-radius": "7px"});
                        $(this).parents("div.note2").css({"outline": "none"});
                        $(this).parents("div.note2").css({"border-color": attcolor});
                        $(this).parents("div.note2").css({"box-shadow": "0 0 10px "+attcolor});
                    }



                });


            } 	
        }

    }

    /*function displaynotification(status,message){

        var gritterOptions = {
            title: status,
            text: message,
            image:'images/Icono_H2M.png',
            sticky: false,
            time: '6000'
        };
        $.gritter.add(gritterOptions);

    }*/


    function getUserData(UserId) {
        //var cadenaGUD = '<?php echo $domain;?>/GetUserData.php?UserId='+UserId;
        var cadenaGUD = 'getUserData.php?UserId='+UserId;
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
        var cadenaGUD = 'getMedCreator.php?UserId='+UserId;
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

    function getReportCreator(Idpin,Iddoc,Idusu) {

        var cadenaGUD = 'getReportCreator.php?Idpin='+Idpin+'&Iddoc='+Iddoc+'&Idusu='+Idusu;
        //alert(cadenaGUD);
        $.ajax(
            {
                url: cadenaGUD,
                dataType: "json",
                async: false,
                success: function(data)
                {
                    //alert ('success');
                    reportcreator = data.items;
                }
            });
    }

    function urlExists(url, callback){
        $.ajax({
            type: 'HEAD',
            url: url,
            success: function(){
                callback(true);
            },
            error: function() {
                callback(false);
            }
        });
    }


}); 		

$(window).load(function() {
    //$(".loader_spinner").fadeOut("slow");
    //$("#tabsWithStyle").show();
    //Commented- Start

    //$('.tooltip').tooltipster('show');
    var progressbar = $( "#progressbar" ),
        progressLabel = $( ".progress-label" );
    //progressbar.width(1025);
    //progressbar.css({"margin-left":'125px'});
    progressbar.progressbar({
        value: false,
        change: function() {
            progressLabel.css({"color":'white'});
            progressLabel.css({"text-shadow":'none'});
            progressLabel.text( progressbar.progressbar( "value" ) + "%" );
        },
        complete: function() {
            progressLabel.css({"color":'white'});
            progressLabel.css({"font-size":'10px'});
            progressLabel.css({"text-shadow":'none'});
            progressLabel.css({"text-align":'center'});
            progressLabel.css({"margin-left":'-50px'});
            progressLabel.text( "Decryption Complete!" );
            setTimeout( function(){
                $("#progressstatus").hide();
                $("#progressbar").hide();
                $("#encryptbox").hide(); 
                $("#tabsWithStyle").show();
                //Commented-stop

                //highlightattachedreports();
                var multireferral_num=parseInt(num_multireferral);
                //alert(multireferral_num);
                /*for (var j=0;j<multireferral_num;j++)
		{ 
				var attcolor=$('#referralcolor'+j).val();
				//alert(attcolor);
			   var reportstobereviewed=$('#reportid_review'+j).val();
			   //alert(reportstobereviewed);
			   var reportstobereviewed_ids=reportstobereviewed.split(" "); 
			   //alert(reportstobereviewed_ids.length);
			   for (var i = 0, len = reportstobereviewed_ids.length; i < len; ++i)
			   {
				  if(reportstobereviewed_ids[i].length==0)
				  {
					continue;
				  }
				  //id^="reportcol"]'
				  $('i[id^="report-eye"]').each(function(){

				  var id=parseInt($(this).parents("div.note2").attr('id'));
				  //alert(id);
				  if(id==parseInt(reportstobereviewed_ids[i]))
				  {
				   //alert('highlighting' + id);
				   $(this).css("color","#000000");
				  // $(this).parents("div.note2").css({"border": "3px solid blue"});
					//alert(attcolor);
				   $(this).parents("div.note2").css({"border": "2px solid"});
				   $(this).parents("div.note2").css({"border-radius": "7px"});
				   $(this).parents("div.note2").css({"outline": "none"});
				   $(this).parents("div.note2").css({"border-color": attcolor});
				   $(this).parents("div.note2").css({"box-shadow": "0 0 10px "+attcolor});

				   //$(this).parents("div.note2").css({"border": "2px solid blue"});
				   //$(this).parents("div.note2").css({"border-radius": "7px"});
				   //$(this).parents("div.note2").css({"outline": "none"});
				   //$(this).parents("div.note2").css({"border-color": "blue"});
				   //$(this).parents("div.note2").css({"box-shadow": "0 0 10px blue"});
				  }



				  });


			   } 	
		}*/


            }, 500 );
        }
    });

    function progress() {
        var progressbarValue = progressbar.find( ".ui-progressbar-value" );
        var val = progressbar.progressbar( "value" ) || 0;
        progressbar.progressbar( "value", val + 10 );
        progressbar.css({"background": 'black'});
        progressbarValue.css({"background": '#4169E1'});    //#5882FA
        if ( val < 99 ) {
            setTimeout( progress, 50 );
        }
    } 
    setTimeout( progress, 500 );

    //Commented

    /*setTimeout(function(){
	 //$("#progressbar").hide();
	 //alert("triggered");
	 $('.TABES:eq(9)').trigger('update');

	 },3000);*/

});

function next_click()
{
    if(curr_file==-1)
    {
        curr_file=0;

    }
    else
    {
        curr_file = (curr_file + 1)%(list.length);
    }
    document.getElementById("verified_count_label").innerHTML = parseInt(curr_file)+1 + '/' + parseInt(list.length);
    var file_name = list[curr_file];
    var contenURL;
    //alert(file_name);



    if(file_name == 'lockedfile.png')
    {
        contenURL = 'images/'+file_name;
    }
    else if(file_name == 'deletedfile.png')
    {
        contenURL = 'images/'+file_name;
    }
    else
    {
        var cadena = 'DecryptFile.php?rawimage='+file_name+'&queMed='+both_id;
        //alert(cadena);
        var RecTipo = LanzaAjax (cadena);
        //alert(RecTipo);
        var doctorId = -1;
        if(both_id != ''){
            doctorId = both_id;
        }
        contenURL =   'temp/'+doctorId+'/Packages_Encrypted/'+file_name;
    }   	

    var conten =  '<iframe id="ImagenN1" style="border:1px solid #666CCC" title="PDF" src="'+contenURL+'" alt="Loading" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
    $('#AreaConten1').html(conten);			

}

function previous_click()
{
    //alert(curr_file);
    if(curr_file==0)
    {
        curr_file=list.length - 1;
    }
    else
    {
        curr_file=(curr_file-1)%(list.length);
    }

    //alert(curr_file + '   ' + list.length);
    //alert(curr_file);
    document.getElementById("verified_count_label").innerHTML = parseInt(curr_file)+1 + '/' + parseInt(list.length);
    var file_name = list[curr_file];
    //alert(file_name);


    var contenURL;
    //alert(file_name);
    if(file_name == 'lockedfile.png')
    {
        contenURL = 'images/'+file_name;
    }
    else if(file_name == 'deletedfile.png')
    {
        contenURL = 'images/'+file_name;
    }
    else
    {
        var cadena = 'DecryptFile.php?rawimage='+file_name+'&queMed='+both_id;
        //alert(cadena);
        var RecTipo = LanzaAjax (cadena);
        //alert(RecTipo);
        var doctorId = -1;
        if(both_id != ''){
            doctorId = both_id;
        }
        contenURL =   'temp/'+doctorId+'/Packages_Encrypted/'+file_name;
    }   	

    var conten =  '<iframe id="ImagenN1" style="border:1px solid #666CCC" title="PDF" src="'+contenURL+'" alt="Loading" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
    $('#AreaConten1').html(conten);			

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

/* TODO : I think this could be much simpler ... */		
function setAddress (city, region, country, lat, long){
	
	address = city+', '+region+', '+country;
	//console.log(address);
	//console.log("LAT & LONG : "+lat + " "+ long);
	geocoder = new google.maps.Geocoder();

    var mapProp = {
		center: new google.maps.LatLng(parseInt(lat), parseInt(long)),
        zoom:5,
        disableDefaultUI:true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
    geocoder.geocode({ 'address': address }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
 			map.setCenter({lat: parseInt(lat), lng: parseInt(long)});
            // To add a marker:
			//var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
			//var marker_shape = new google.maps.MarkerShape(); 
			//var marker_shape = {coords: [0,0,50,50], type: "rect"};
            /*var marker = new google.maps.Marker({
				fillColor:"#0000FF",
                fillOpacity:1,
				position: {lat: parseInt(lat), lng: parseInt(long)},
				map: map
				//icon: iconBase + 'schools_maps.png'
            });*/
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
    });
}		
function initialize_map(ip)
{

	var lat="";
	var long="";
	//get info based on the ip address
	$.get("http://ipinfo.io/"+ip, function(response) {
    	console.log(response.loc);
		console.log(response.city);
		console.log(response.region);
		console.log(response.country);
		city 	= response.city;
		region  = response.region;
		country = response.country;
		var loc = response.loc.split(",");	
		lat = loc[0];
		long = loc[1];

		address = city+', '+region+', '+country;
		setAddress(city, region, country, lat, long);
	}, "jsonp");

    
}

function convertDateFormat1(input)
{
    //Input : Date in yy-mm-dd Format
    //Output: Date in mm-dd-yy Format
    var str = input.split('-');
    return str[1] + '-' + str[2] + '-' + str[0];
}

    //TIMEZONE SETTING
    Date.prototype.toDateInputValue = (function() {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0,10);
}); 
$(function() {
    //SET #datepicker2 VALUE AS TODAY, ACCORDING TO THE BROWSER TIME ZONE.
    $("input#datepicker2").val(new Date().toDateInputValue());
    $("#classification_datepicker").val(convertDateFormat1(new Date().toDateInputValue()));
    
    //FOR fittext
    $('#myTabContent').on('.queTIP', function() {
        $(this).fitText(1.1, {
            maxFontSize: '9px'
        });
    });
    
});

