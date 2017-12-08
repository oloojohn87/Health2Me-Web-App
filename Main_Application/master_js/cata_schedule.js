var timearray = ['7am', '8am', '9am', '10am', '11am', '12pm', '1pm', '2pm', '3pm', '4pm', '5pm', '6pm'];

function draw_current_timebar(curTime) {  
    if($('#arrow')) $('#arrow').remove();
    
    var arrow = $('<div id="arraow"></div>').css({
        margin: '-10px 0 30px 22px',
        color: '#FFCC00',
        'font-size': '60px',
        'z-index': 0,
    }).html('&#8964;');
    
    var TimeBar = $('<div></div>').attr('id','curTimeLabel').css('color','#4EA8FC').html('It\'s <b style="color:#0265C0">'+curTime+'</b>').append(arrow);
    return TimeBar;
}

function id_formatter(id, time) {
    var updatedTime = '', patientID = 0, hour = '', minute = '', formattedTime = '', formattedID = '', slotInfo = '', return_array = new Array();
    patientID = id;
    if(typeof time != 'undefined') updatedTime = time.split(':'); 
    hour = (updatedTime[0] == '00' ? 12 : parseInt(updatedTime[0]));
    minute = (updatedTime[1] == '00' ? 0 : parseInt(updatedTime[1]));
    if(parseInt(hour) > 12) {
        hour = (hour - 12).toString();
        formattedTime = hour+':'+updatedTime[1]+'PM';
        hour += 'pm';
    }
    else if(parseInt(hour) == 12) {
        hour = hour.toString();
        formattedTime = hour+':'+updatedTime[1]+'PM';
        hour += 'pm';
    }
    else { 
        hour = hour.toString();
        formattedTime = hour+':'+updatedTime[1]+'AM';
        hour += 'am';
    }
    formattedID = formattedTime+'_'+patientID;
    return_array.push(formattedID, hour, minute);
    return return_array;
} 

$.fn.cata_schedule = function(dictionary) {
    var o = $(this[0]);
    var o_id = o.attr('id');

    var type = dictionary.type;
    var providerID = dictionary.PID;   
    var hourWidth = 0;   
    var returnedTime = refreshTimeSet(timearray);
    var actHour = 0, actMinute = 0, actDate = '';
    actHour = parseInt(returnedTime[0]);
    actMinute = parseInt(returnedTime[1]);
    actDate = returnedTime[2];
    
    $.post('../../../ajax/load_schedule.php', {type: type, PID: providerID, date: actDate}, function(data, status) {
        console.log(actDate);
        o.empty();
        var info = JSON.parse(data);
        var updatedTime;
        var hour = '', minute = '';
        var slotWidth = 0, slotInfo = 1;
        var patientID = 0;
        var NameInit = '', Surname = '', SurnameVert = '', SurnameArray;
        var status = '';
        var formattedTime = '';
        var formTimeArray;
        var cssClass = '';
        var Nextflag = false;
        var inMinutes = 0;
        var MinOrHour;
        var cancelArray = new Array();
        
        
        
        
        
        /********************/
        /*******TEST*********/
        /********************/
        
        
        //returnedTime = ['10','00'];
        
        
        /********************/
        /*******TEST*********/
        /********************/
        
        
        
        
        
        
        //console.log('type: '+type+', PID: '+providerID);
        //console.log('info: ');
        //console.log(info);
        
        //WALK THROUGH THE RECEIVED SCHEDULE DATA WITHIN CURRENT TIME SLOT IF IT APPLIES
        for(var time in timearray) {
            if (timearray.hasOwnProperty(time)  &&        // These are explained
        /^0$|^[1-9]\d*$/.test(time) &&    // and then hidden
        time <= 4294967294                // away below
        ) {
                //PUTTING HOUR SLOT
                o.append($('<div></div>').attr({id: timearray[time], class: "hourslot"}));
                hourWidth = parseInt($('#'+timearray[time]).css('width'));
                slotWidth = (hourWidth-3)/4;
                //PUTTING MINUTE SLOT
                for (var i = 1; i < 4; i++) {
                    $('#'+timearray[time]).append($('<div></div>').attr({id: timearray[time]+'_slot-'+i, class: 'minslot'}).css({width: slotWidth+'px', 'margin-right':'1px'}));
                }
                $('#'+timearray[time]).append($('<div></div>').attr({id: timearray[time]+'_slot-4', class: 'minslot'}).css({width: slotWidth+'px'}));
                
                
                /*$('#today').html(today);
                curTimeBar = draw_current_timebar(curTime);
                $('#todayBar').html(curTimeBar).css('margin-left', arrowMarginLeft);*/ 
                
                
                $.each(info, function(k, obj) {
                    updatedTime = obj.updated_time.split(':');
                    if(obj.Name !== null) NameInit = obj.Name.charAt(0).toUpperCase();
                    if(obj.Surname !== null) Surname = obj.Surname.capitalizeFirstLetter();
                    status = obj.status;
                    if(status == 'ACTIVE') {
                        status = 'ACTIVE';
                        cssClass = 'active';
                    }
                    else if (status == 'NEXT') {
                        status = 'ACTIVE';
                        cssClass = 'active';
                    }
                    else if (status == 'CANCELED') {
                        status = 'CANCELED';
                        cssClass = 'canceled';
                    }
                    else if (status == 'COMPLETED') {
                        status = 'COMPLETED';
                        cssClass = 'completed';
                    }
                    else if (status == 'WAITING') {
                        status = 'WAITING';
                        cssClass = 'waiting';
                    }
                    else {
                        status = 'MISSED';
                        cssClass = 'missed';
                    }
                    SurnameArray = Surname.slice(0,7).split("");
                    SurnameVert = '';
                    for(var j = 0; j < SurnameArray.length - 1; j++) {
                        SurnameVert += SurnameArray[j].toUpperCase()+'<br>';
                    }
                    SurnameVert += SurnameArray[j].toUpperCase();
                    var returnedFormattedInfos = id_formatter(obj.Identif, obj.updated_time);
                    formattedID = returnedFormattedInfos[0];
                    hour = returnedFormattedInfos[1];
                    minute = returnedFormattedInfos[2];
                    slotInfo = (minute == 0 ? 1 : (minute/15 + 1));
                    
                    if(hour == timearray[time]) { 
                        
                        /*console.log('min: '+minute);
                        console.log('actMin: '+actMinute);
                        console.log('hour: '+parseInt(updatedTime[0]));
                        console.log('actHour: '+actHour);
                        console.log('nextfflag: '+Nextflag);*/
                        
                        if((parseInt(minute) >= actMinute && parseInt(updatedTime[0]) == actHour) || (parseInt(updatedTime[0]) > actHour)) {
                            //IGNORING THE WAITING STATUS
                            if(status == 'NEXT' || status == 'COMPLETED' || status == 'MISSED' || status == 'CANCELED') Nextflag = true;
                            if(Nextflag == false ) {
                                inMinutes = parseInt(minute) - actMinute;
                                if(parseInt(updatedTime[0]) > actHour) inMinutes += (parseInt(updatedTime[0]) - actHour)*60;
                                if(inMinutes >= 120) MinOrHour = Math.ceil(inMinutes/60).toString()+' hours';                          
                                else MinOrHour = inMinutes.toString()+' minutes';
                                status = 'NEXT';
                                cssClass = 'next';
                                formTimeArray = formattedID.split('_');
                                formattedTime = formTimeArray[0];
                                $('#noticeNext').html('Next appointment in '+MinOrHour+' with<br><span style="color:#164F86;">'+obj.Name.capitalizeFirstLetter()+' '+Surname+'</span> (at '+formattedTime+')');
                                Nextflag = true;
                            }
                        }
                        else {
                            if(status != 'COMPLETED') {   
                                status = 'CANCELED';
                                cssClass = 'canceled';
                                cancelArray[obj.id] = {pid: obj.Identif, date: obj.updated_date+' '+obj.updated_time, status: status};
                            }
                        }
                        $('#'+timearray[time]+'_slot-'+slotInfo).append($('<div></div>').attr({id: formattedID, class: 'capsule '+cssClass, 'data-rid': obj.id, 'data-pname': NameInit+'. '+Surname, 'data-status': status}).css('width', (slotWidth-1)+'px').html('<span class="vertical_name">'+SurnameVert+'</span>'));
                    }
                    //console.log('#'+timearray[time]+'_slot-'+slotInfo);                 
                });  
                if(cancelArray.length > 0) {
                    var jsonC = JSON.stringify(cancelArray);
                    $.post('../../../ajax/update_reservation.php', {medID: $('#MEDID').val(), pInfos: jsonC }, function(data, status) {

                    });
                }
            }
        }     
    });
};
        
function refreshTimeSet(timearray) {
    var curTimeBar;
    var arrowMarginLeft = 32;
    var objToday = new Date(),
    weekday = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
    dayOfWeek = weekday[objToday.getDay()],
    domEnder = new Array( 'th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th' ),
    dayOfMonth = today + (objToday.getDate() < 10) ? '0' + objToday.getDate() + domEnder[objToday.getDate()] : objToday.getDate() + domEnder[parseFloat(("" + objToday.getDate()).substr(("" + objToday.getDate()).length - 1))],
    months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
    curMonth = months[objToday.getMonth()],
    curYear = objToday.getFullYear(),
    curHour = objToday.getHours() > 12 ? objToday.getHours() - 12 : (objToday.getHours() < 10 ? "0" + objToday.getHours() : objToday.getHours()),
    
    curMinute = objToday.getMinutes() < 10 ? "0" + objToday.getMinutes() : objToday.getMinutes(),
    
    curSeconds = objToday.getSeconds() < 10 ? "0" + objToday.getSeconds() : objToday.getSeconds(),
    curMeridiem = objToday.getHours() >= 12 ? "PM" : "AM",
    exactHour = objToday.getHours();
    
    
    
    
    
    /********************/
    /*******TEST*********/
    /********************/

    /*curHour = '10';
    exactHour = '10';
    curMinute = '00';
    curMeridiem = "AM";*/

    /********************/
    /*******TEST*********/
    /********************/
    
     
    

    var today = "<i>" + dayOfWeek + "</i> " + curMonth + " " + dayOfMonth + ", " + curYear;
    var curTime = parseInt(curHour).toString() + ":" + curMinute + ' ' + curMeridiem;
    var cmpTime = parseInt(curHour).toString() + curMeridiem.toLowerCase();
    var curDate = curYear+'-'+((objToday.getMonth()+1) > 9 ? (objToday.getMonth()+1) : '0'+(objToday.getMonth()+1))+'-'+((objToday.getDate() < 10) ? '0' + objToday.getDate() : objToday.getDate());

    //WALK THROUGH TO CALCULATE THE MARGIN LEFT FOR THE TIME INDICATOR OF NOW
    for(var time in timearray) {
        if (timearray.hasOwnProperty(time)  &&        // These are explained
            /^0$|^[1-9]\d*$/.test(time) &&    // and then hidden
            time <= 4294967294                // away below
        ) {
            //console.log(cmpTime);
            if(timearray[time] == cmpTime) {
                
                //CALCULATE MARGIN LEFT WIDTH FOR THE CUR HOUR
                if(time != 0) arrowMarginLeft += (time * 804 / 12);
                //CALCULATE MARGIN LEFT WIDTH FOR THE CUR MINUTE
                if(parseInt(curMinute) != 0) arrowMarginLeft += (parseInt(curMinute) * 804 / 12 / 60);
            }
        }
    }
    //console.log(arrowMarginLeft);
    
    $('#today').html(today);
    $('#todayHidden').val(curDate);
    curTimeBar = draw_current_timebar(curTime);
    $('#todayBar').html(curTimeBar).css('margin-left', arrowMarginLeft);
    
    var returnTime = [exactHour, curMinute, curDate];
    //console.log('It\'s working');
    return returnTime;
} 

//FUNCTION FOR BLINKING FOR THE WAITING CLASS
function pulse() {
    $('.waiting').fadeIn(600);
    $('.waiting').fadeOut(500);
}
setInterval(pulse, 1000);

var idleInterval = setInterval(function() {refreshTimeSet(timearray)} , 15000);

String.prototype.capitalizeFirstLetter = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}