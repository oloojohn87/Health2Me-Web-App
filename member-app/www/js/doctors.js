//get variables from local storage set in index.js
var temp = localStorage.getItem('mydata');
var domain = '';
var user = '';
var num_reports = '';
        
if(temp != null) {
    var dataJSON = JSON.parse(temp);
	domain=dataJSON.domain;
  	user = dataJSON.user;
}


$( document ).bind( "mobileinit", function() {
    // Make your jQuery Mobile framework configuration changes here!
    $.support.cors ="true";
    $.mobile.allowCrossDomainPages = true;
});

$( document ).ready(function() { 
	

	
    $("#telemedicine_toggle_button").attr("data-on", "true");
    $('#search_speciality').val("Any");
    $('#country_search').val("-1");
    $('#region_search').val("-1");
    $('#region_search').html('');
    
    $(".doctor_search_nav_bar").children().eq(0).data("index", 1);
    $(".doctor_search_nav_bar").children().eq(1).data("index", 2);
    $(".doctor_search_nav_bar").children().eq(2).data("index", 3);
	
    get_doctors();
    $(".doctor_row").on('click', function()
	{	
        alert("hello");
        /*if ( $(this).height() != 58)
          $( this ).animate({ height: 100 }, 1000 );
        else
          $( this ).animate({ height: 58 }, 1000 );*/
    });
    
    $("#right-arrow").on('click', function()
    {
        doctors_page_memory.push(doctors_last_result);
        get_doctors();
    });
    
    $("#left-arrow").on('click', function()
    {
        doctors_next_result = doctors_page_memory.pop();
        get_doctors();
    });
	
	$("#call-now").click( function() 
	{
		pat_phone = "4697748178";
		doc_id = "288";
		doc_phone = "214-734-9964";
		doc_name="Kyle Austin";
		pat_name="Lori Green";
		
		$.post(domain+"mobile-member/start_telemed_phonecall.php", {pat_phone: pat_phone, doc_phone: doc_phone, doc_id: doc_id, pat_id: user, doc_name: doc_name, pat_name: pat_name}, function(data, status)
        {
            //console.log(data);
			alert("calling doctor now");
        });
	});
 	$("#my-doctors").click( function() 
	{
		alert("my-doctors");
	});  
  	$("#appointments").click( function() 
	{
		alert("appointments");
	}); 
    
	//doctor resize icon
    /*$(".doctor_row_resize").live('click', function()
    {
        if($(this).hasClass("icon-resize-full"))
        {
            $(".doctor_row_wrapper").each(function()
            {
                if($(this).css("height") == '176px')
                {
                    $(this).animate({height:'86px'}, {duration: 500, queue: false});
                }
            });
            $(".doctor_row").each(function()
            {
                if($(this).css("height") == '160px')
                {
                    $(this).animate({height:'70px'}, {duration: 500, queue: false});
                    $(this).children('span').eq(0).removeClass("icon-resize-small").addClass("icon-resize-full");
                }
            });
        
            $(this).removeClass("icon-resize-full").addClass("icon-resize-small");
            $(this).parent().animate({height:'160px'},  {duration: 500, queue: false});
            $(this).parent().parent().animate({height:'176px'}, {duration: 500, queue: false});
        }
        else
        {
            $(this).removeClass("icon-resize-small").addClass("icon-resize-full");
            $(this).parent().animate({height:'70px'},  {duration: 500, queue: false});
            $(this).parent().parent().animate({height:'86px'}, {duration: 500, queue: false});
        }
    });*/
});     



        /*$("#advanced_toggle_button").removeClass("doctor_search_advanced_toggle_button_selected").addClass("doctor_search_advanced_toggle_button");
        $("#doctor_rows").css("display", "block");
        $("#doctors_search_page_buttons").css("display", "block");
        $("#doctor_search_advanced").css("display", "none");
        doctors_page_memory.length = 0;
        doctors_last_result = 0;
        doctors_next_result = 0;
        get_doctors();
        $("#search_modal").dialog({bigframe: true, width: 902, height: 730, resize: false, modal: true, resizable: false});*/


var order_column = 0; // 0 = Name, 1 = rating
var order_direction = 1; // 1 = ascending, 0 = descending
var doctors_last_result = 0;
var doctors_next_result = 0;
var doctors_page_memory = new Array();
var last_search_term = "";
var doctor_advanced_search = false;
var doctor_advanced_search_changed = false;
var next = false;


/*$("#available_toggle_button").on('click', function()
{
    if($(this).attr("data-on") == "true")
    {
        $(this).attr("data-on", "false");
        $(this).css("background-color", "#FFF");
    }
    else
    {
        $(this).attr("data-on", "true");
        $(this).css("background-color", "#22AEFF");
        if($("#telemedicine_toggle_button").attr("data-on") == "false")
        {
            $("#telemedicine_toggle_button").attr("data-on", "true");
            $("#telemedicine_toggle_button").css("background-color", "#22AEFF");
        }
    }
    doctors_page_memory.length = 0;
    doctors_last_result = 0;
    doctors_next_result = 0;
    get_doctors();
});

$("#telemedicine_toggle_button").on('click', function()
{
    if($(this).attr("data-on") == "true")
    {
        $(this).attr("data-on", "false");
        $(this).css("background-color", "#FFF");
        if($("#available_toggle_button").attr("data-on") == "true")
        {
            $("#available_toggle_button").attr("data-on", "false");
            $("#available_toggle_button").css("background-color", "#FFF");
        }
    }
    else
    {
        $(this).attr("data-on", "true");
        $(this).css("background-color", "#22AEFF");
    }
    doctors_page_memory.length = 0;
    doctors_last_result = 0;
    doctors_next_result = 0;
    get_doctors();
});

$("#advanced_toggle_button").on('click', function()
{
    if($(this).hasClass("doctor_search_advanced_toggle_button_selected"))
    {
        $(this).removeClass("doctor_search_advanced_toggle_button_selected").addClass("doctor_search_advanced_toggle_button");
        $("#doctor_rows").css("display", "block");
        $("#doctors_search_page_buttons").css("display", "block");
        $("#doctor_search_advanced").css("display", "none");
        doctor_advanced_search = false;
        if(doctor_advanced_search_changed == true)
        {
            doctors_page_memory.length = 0;
            doctors_last_result = 0;
            doctors_next_result = 0;
            get_doctors();
        }
    }
    else
    {
        $(this).removeClass("doctor_search_advanced_toggle_button").addClass("doctor_search_advanced_toggle_button_selected");
        $("#doctor_rows").css("display", "none");
        $("#doctors_search_page_buttons").css("display", "none");
        $("#doctor_search_advanced").css("display", "block");
        doctor_advanced_search = true;
        doctor_advanced_search_changed = false;
    }
});

$(".doctor_search_advanced_button").on('click', function()
{
    if($(this).text() == "Reset")
    {
        if($('#search_speciality').val() != "Any" || $('#country_search').val() != "-1" || ($('#region_search').val() != "-1" && $('#region_search').val() != null))
        {
            doctor_advanced_search_changed = true;
        }
        $('#search_speciality').val("Any");
        $('#country_search').val("-1");
        $('#region_search').val("-1");
        $('#region_search').html('');
    }
    else if($(this).text() == "Search")
    {
        doctors_page_memory.length = 0;
        doctors_last_result = 0;
        doctors_next_result = 0;
        get_doctors();
        $("#doctor_rows").css("display", "block");
        $("#doctors_search_page_buttons").css("display", "block");
        $("#doctor_search_advanced").css("display", "none");
        $("#advanced_toggle_button").removeClass("doctor_search_advanced_toggle_button_selected").addClass("doctor_search_advanced_toggle_button");
    }
    else
    {
        $("#advanced_toggle_button").trigger('click');
    }
});*/

$(".sort_button").on('click', function()
{
    if($(this).attr("id") == "name_button")
    {
        order_column = 0;
    }
    else if($(this).attr("id") == "rating_button")
    {
        order_column = 1;
    }
    if($(this).children().eq(0).hasClass("icon-caret-up"))
    {
        $(this).children().eq(0).removeClass("icon-caret-up").addClass("icon-caret-down");
        order_direction = 0;
        
    }
    else if($(this).children().eq(0).hasClass("icon-caret-down"))
    {
        $(this).children().eq(0).removeClass("icon-caret-down").addClass("icon-caret-up");
        order_direction = 1;
    }
    else
    {
        $('.sort_button').each(function(index)
        {
            $(this).children().eq(0).removeClass("icon-caret-down");
            $(this).children().eq(0).removeClass("icon-caret-up");
        });
        $(this).children().eq(0).addClass("icon-caret-up");
        order_direction = 1;
    }
    doctors_page_memory.length = 0;
    doctors_last_result = 0;
    doctors_next_result = 0;
    get_doctors();
});


$("#search_bar_button").on('click', function()
{
    if(last_search_term != $("#search_bar").val())
    {
        last_search_term = $("#search_bar").val();
        doctors_page_memory.length = 0;
        doctors_last_result = 0;
        doctors_next_result = 0;
        get_doctors();
    }
});

$('#search_bar').keypress(function (e) 
{
    if (e.which == 13) 
    {
        $("#search_bar_button").trigger('click');
    }
});

$("#search_speciality").on('change', function()
{
    doctor_advanced_search_changed = true;
});
$("#country_search").on('change', function()
{
    doctor_advanced_search_changed = true;
});
$("#region_search").on('change', function()
{
    doctor_advanced_search_changed = true;
});

/*$(".doctor_search_nav_button").live('click', function()
{
    $(".doctor_search_nav_button_selected").removeClass("doctor_search_nav_button_selected").addClass("doctor_search_nav_button");
    $(this).removeClass("doctor_search_nav_button").addClass("doctor_search_nav_button_selected");
    if($(this).data('index') == 1)
    {
        $("#doctor_directory").css("display", "block");
        $("#my_doctors").css("display", "none");
        $("#personal_doctor").css("display", "none");
        console.log("Switching to: Directory");
    }
    else if($(this).data('index') == 2)
    {
        $("#doctor_directory").css("display", "none");
        $("#my_doctors").css("display", "block");
        $("#personal_doctor").css("display", "none");
        console.log("Switching to: My Doctors");
    }
    else if($(this).data('index') == 3)
    {
        $("#doctor_directory").css("display", "none");
        $("#my_doctors").css("display", "none");
        $("#personal_doctor").css("display", "block");
        console.log("Switching to: Personal Doctor");
    }
});

$(".my_doctors_add_button").live('click', function()
{
    $("#my_doctors_page_1").css("display", "none");
    $("#my_doctors_page_2").css("display", "block");
    
    $("#my_doctors_name").val("");
    $("#my_doctors_surname").val("");
    $("#my_doctors_hospital_name").val("");
    $("#my_doctors_hospital_street").val("");
    $("#my_doctors_hospital_zip").val("");
    $("#my_doctors_hospital_state").val("");
    $("#my_doctors_hospital_country").val("");
    $("#my_doctors_phone").val("");
    $("#my_doctors_email").val("");
    $("#my_doctors_speciality").val("General Practice");
});
$("#my_doctors_cancel_button").live('click', function()
{
    $("#my_doctors_page_1").css("display", "block");
    $("#my_doctors_page_2").css("display", "none");
});*/

function load_my_doctors()
{
    $.get("get_my_doctors.php", {idpac: $('#USERID').val()}, function(data, status)
    {
        
    });
}

/*$("#my_doctors_done_button").live('click', function()
{
    
    $.post("edit_my_doctor.php", {name: $("#my_doctors_name").val(), surname: $("#my_doctors_surname").val(), hospital: $("#my_doctors_hospital_name").val(), address: $("#my_doctors_hospital_street").val(), zip: $("#my_doctors_hospital_zip").val(), state: $("#my_doctors_hospital_state").val(), country: $("#my_doctors_hospital_country").val(), phone: $("#my_doctors_phone").val(), email: $("#my_doctors_email").val(), speciality: $("#my_doctors_speciality").val(), idpac: $('#USERID').val()}, function(data, status)
    {
        console.log(data);
        if(data.substr(0, 2) == 'NN')
        {
            alert("Please enter a name for your doctor");
        }
        $("#my_doctors_page_1").css("display", "block");
        $("#my_doctors_page_2").css("display", "none");
    });
});*/


/*
$(".doctor_action_button").live('click', function()
{
    // This function depends on the 'Talk' modal window in UserDashboard.php for calling or scheduling an appointment with the doctor.
    $("#step_bar_1").attr("class", "step_bar lit");
    $("#step_circle_1").attr("class", "step_circle lit");
    $("#step_circle_2").attr("class", "step_circle lit");
    $("#step_bar_2").attr("class", "step_bar lit");
    $("#step_circle_3").attr("class", "step_circle lit");
    $("#find_doctor_my_doctors_2").css("display", "block");
    $('#find_doctor_main').css("display", "none");
    $("#find_doctor_next_button").css("display", "block");
    $("#find_doctor_previous_button").css("display", "block");
    $("#find_doctor_cancel_button").css("display", "block");
    $("#find_doctor_close_button").css("display", "none");
    $('#find_doctor_my_doctors_1').css("display", "none");
    $('#find_doctor_my_doctors_3').css("display", "none");
    $('#find_doctor_appointment_1').css("display", "none");
    $('#find_doctor_appointment_2').css("display", "none");
    $('#find_doctor_time').css("display", "none");
    $('#find_doctor_receipt').css("display", "none");
    $('#find_doctor_confirmation').css("display", "none");
    $('#time_selector_1').css("display", "none");
    $('#day_selector_1').css("display", "none");
    $("#find_doctor_label").text("Select Type");
    talk_mode = 1;
    var doctor_name = $(this).parent().parent().children('.doctor_main_label').eq(0).children('.doctor_name').eq(0).text();
    var doctor_location = $(this).parent().parent().children('.doctor_main_label').eq(0).children('.doctor_location').eq(0).text();
    selected_doctor_info = "recdoc_"+$(this).parent().parent().children('input').eq(0).val()+"_"+$(this).parent().parent().children('input').eq(1).val()+"_"+doctor_name.replace(" ", "_")+"_"+doctor_location;
    $("#doctor_location_text").html("Doctor " + doctor_name + " is in <strong>" + doctor_location + "</strong>.<br/>Please confirm that you are in <strong>" + doctor_location + "</strong> as well.");
    find_doctor_page = 21;
    $("#find_doctor_modal").dialog({bgiframe: true, width: 550, height: 413, resize: false, modal: true});
    if($(this).text() == 'Call Now')
    {
        selected_doctor_available = 1;
    }
    else
    {
        selected_doctor_available = 0;
    }
    $('#in_location_checkbox').removeAttr("checked");
    $.post("getDoctorAvailableTimeranges.php", {id: $(this).parent().parent().children('input').eq(0).val()}, function(data, status)
    {
        var info = JSON.parse(data);
        for(var i = 0; i < 7; i++)
        {
            if(info['slots'][i].length == 0)
            {
                $("#"+getDayStr(i)).addClass("day_disabled");
                $("#"+getDayStr(i)).children("input").eq(1).val("[]");
                $("#"+getDayStr(i)).children("input").eq(2).val("");
            }
            else
            {
                $("#"+getDayStr(i)).removeClass("day_disabled");
                $("#"+getDayStr(i)).children("input").eq(1).val("["+info['slots'][i].toString()+"]");
                $("#"+getDayStr(i)).children("input").eq(2).val("["+info['zones'][i].toString()+"]");
            }
        }
    });
});

*/
function get_doctors()
{
    var online_only = 0;
    if($("#available_toggle_button").attr("data-on") == "true")
    {
        online_only = 1;
    }
    var telemed_only = 0;
    if($("#telemedicine_toggle_button").attr("data-on") == "true")
    {
        telemed_only = 1;
    }
    var loc = "";
    if($("#country_search").val() != -1)
    {
        loc = $("#country_search").val();
    }
    if($("#region_search").val() != -1 && $("#region_search").children("option").length > 0)
    {
        if($("#region_search").val().length > 0)
        {
            loc = $("#region_search").val() + ", " + loc;
        }
    }
    var sp = "";
    if($("#search_speciality").val() != "Any")
    {
        sp = $("#search_speciality").val();
    }
    $("#doctor_rows").html('<div id="inner-doc-rows" style=""></div><div id="load-image"><img src="img/29.gif"  alt=""></div>');
    $.post(domain+"mobile-member/getTelemedDoctorsMobile.php", {starting_point: doctors_next_result, order: order_column, asc: order_direction, timezone: get_timezone_offset(), online_only: online_only, telemed_only: telemed_only, search_term: $("#search_bar").val(), speciality: sp, location: loc}, function(data,status)
    {
        //console.log(data);
        var info = JSON.parse(data);
        doctors_last_result = doctors_next_result;
        doctors_next_result = info['next_result'];
        if(doctors_page_memory.length > 0)
        {
            $("#left-arrow").show();
            //#80d0ff
        }
        else
        {
            $("#left-arrow").show();
        }
        if(info['next'] == true)
        {
            $("#right-arrow").show();
        }
        else
        {
            $("#right-arrow").show();
        }
        //for ratings
        $("#doctor_rows").html('');
        var docs = info['data'];
        var master_html = '';
        
        for(var i = 0; i < docs.length; i++)
        {
            var rating = 0;
            var total_ratings = 0;
            if(docs[i]['rating'] != null)
            {
                for(var k = 0 ; k < 10; k++)
                {
                    total_ratings += docs[i]['rating'][k];
                    rating += (docs[i]['rating'][k] * (k + 1));
                }
                rating /= total_ratings;
            }
            var html = '';
            //if(i % 2 == 0)
            //{
                html += '<div class="doctor_row_wrapper">';
           // }
            html += '<div class="doctor_row">';
            html += '<input type="hidden" value="'+docs[i]['id']+'" />';
            html += '<input type="hidden" value="'+docs[i]['phone']+'" />';
            html += '<span class="icon-resize-full doctor_row_resize"></span>';
            //html += '<i id="" class="icon-eye-open icon-large" style="margin-top: 21px;color: RGB(191,191,191);font-size: 0.7em;position: relative;float: right;margin-right: -11px; cursor:pointer;" ></i>';          
            if(docs[i]['image'].substr(0, 6) == "Doctor")
            {
                html += '<img class="doctor_pic" src="'+docs[i]['image']+'" />';
            }
            else
            {
                html += '<img class="doctor_pic" src="'+domain+'identicon.php?size=25&hash='+docs[i]['image']+'" />';
            }
            html += '<div class="doctor_main_label"><div class="doctor_name"><span style="color: #22AEFF">'+docs[i]['name']+'</span> <span style="color: #00639A">'+docs[i]['surname']+'</span></div>';
            html += '<div class="doctor_speciality">';
            if(docs[i]['speciality'] != null && docs[i]['speciality'] != '-1')
            {
                html += docs[i]['speciality'];
            }
            else
            {
                html += 'No Speciality';
            }
            html += '</div>';
            html += '<div class="doctor_location">';
            if(docs[i]['location'] != null && docs[i]['location'] != '-1')
            {
                html += docs[i]['location'];
            }
            else
            {
                html += 'Location Not Specified';
            }
            html += '</div>';
            html += '</div><div class="doctor_hospital_info"><div class="doctor_stars">';
            while(rating >= 2.0)
            {
                html += '<i class="icon-star" style="float: left; font-size: 12px; color: #666;"></i>';
                rating -= 2.0;
            }
            while(rating >= 1.0)
            {
                html += '<i class="icon-star-half" style="float: left; font-size: 12px; color: #666;"></i>';
                rating -= 1.0;
            }
            html += '</div><div class="doctor_hospital_name">';
            if(docs[i]['hospital_name'] != null && docs[i]['hospital_name'].length > 0)
            {
                html += docs[i]['hospital_name'];
            }
            else
            {
                html += 'Hospital Not Specified';
            }
            html += '</div><div class="doctor_hospital_address">';
            if(docs[i]['hospital_addr'] != null && docs[i]['hospital_addr'].length > 0)
            {
                html += docs[i]['hospital_addr'];
            }
            html += '</div></div>';
            
            // extended part
            
            html += '<div class="doctor_certifications_box">';
            if(docs[i]['certifications'].length >= 1)
            {
                var found = false;
                var current_index = 0;
                while(!found && current_index < docs[i]['certifications'].length)
                {
                    if(docs[i]['certifications'][current_index]['isPrimary'] == '1')
                    {
                        found = true;
                        if(docs[i]['certifications'][current_index]['image'].length > 0)
                        {
                            html += '<img class="doctor_certification_icon" src="'+docs[i]['certifications'][current_index]['image']+'" />';
                        }
                        html += '<div class="doctor_certification_label">'+docs[i]['certifications'][current_index]['name']+'</div>';
                        if(docs[i]['certifications'][current_index]['start_date'].length > 0)
                        {
                            var year = parseInt(docs[i]['certifications'][current_index]['start_date'].split("-")[0]);
                            var currentYear = (new Date).getFullYear();
                            var duration = currentYear - year;
                            html += '<div class="doctor_certification_label_small">'+year+' ('+duration+' years)</div>';
                        }
                    }
                    current_index += 1;
                }
                current_index = 0;
                var total_count = 0;
                while(current_index < docs[i]['certifications'].length && total_count < 2)
                {
                
                    if(docs[i]['certifications'][current_index]['isPrimary'] == '0')
                    {
                        html += '<div class="doctor_certification_label_secondary">'+docs[i]['certifications'][current_index]['name']+'</div>';
                        total_count += 1;
                    }
                    current_index += 1;
                }
            }
            html += '</div>';
            html += '<div class="doctor_action_box">';
            html += '<div class="doctor_consultations_label"><em>'+docs[i]['consultations']+' consultations</em></div>';
			
			var id_holder = docs[i]['id'];
			//THIS CHECKS SCHEDULE
			var queUrl = domain+'scheduleCheck.php?id='+id_holder;
			$.ajax({
           url: queUrl,
           dataType: "json",
           async: false,
           success: function(data)
           {
			//console.log(queUrl);
			itemsCheck = data.items;  
           }
        });
		///////////////////////////////////////
		//console.log(itemsCheck[0].show);
		
            if(docs[i]['available'])
            {
                html += '<button class="doctor_action_button">Call Now</button>';
            }
            if(docs[i]['telemed'] == 1)
            {
			if(itemsCheck[0].show == 'no'){
			html += '<button class="doctor_action_button" disabled style="background-color:gray;">Schedule</button>';
			}else{
			
                html += '<button class="doctor_action_button">Schedule</button>';
				}
            }
            html += '</div>';
            
            html += '</div>';
            if(i % 2 == 1 || i == (docs.length - 1))
            {
                html += '</div>';
            }
            master_html += html;
        }
        $("#doctor_rows").html(master_html);
    });
}
function getDayStr(i)
{
    if(i == 0)
    {
        return "sun";
    }
    else if(i == 1)
    {
        return "mon";
    }
    else if(i == 2)
    {
        return "tues";
    }
    else if(i == 3)
    {
        return "wed";
    }
    else if(i == 4)
    {
        return "thur";
    }
    else if(i == 5)
    {
        return "fri";
    }
    else if(i == 6)
    {
        return "sat";
    }
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
