var mysql = require('mysql');

// typical appointment insertion query: INSERT INTO ".$dbname.".appointments SET med_id=151, pat_id=1313, start_time='14:00:00', end_time='16:00:00', processed=0, time_called=null, video=0, date='2014-07-02', timezone='-05:00:00' 

var proc = require('child_process');
require('twilio');
 
var pool = mysql.createPool
({
    host     : 'www.health2.me',
    user     : 'monimed',
    password : 'ardiLLA98',
    database : '".$dbname."'
});

exports.pool = pool;

var accountSid = 'AC109c7554cf28cdfe596e4811c03495bd';
var authToken = "26b187fb3258d199a6d6edeb7256ecc1";
var client = require('twilio')(accountSid, authToken);

function process_appointment(appts, index, conn, patients_processed, doctors_processed, docs_in_consultation)
{
    var date = new Date();
    date = new Date(date.getTime() + (date.getTimezoneOffset() * 60000));
    var doc_id = appts[index]['med_id'];
    var pat_id = appts[index]['pat_id'];
    var app_month = (appts[index]['date'].getMonth() + 1).toString();
    var app_day = appts[index]['date'].getDate().toString();
    if(app_month.length < 2)
    {
        app_month = "0"+app_month;
    }
    if(app_day.length < 2)
    {
        app_day = "0"+app_day;
    }
    var app_start_date = new Date(appts[index]['date'].getFullYear()+"-"+app_month+"-"+app_day+"T"+appts[index]['start_time']);
    var app_end_date = new Date(appts[index]['date'].getFullYear()+"-"+app_month+"-"+app_day+"T"+appts[index]['end_time']);
    var timezone_pieces = appts[index]['timezone'].split(":");
    var timezone_hours = (parseInt(timezone_pieces[0]) * 3600000);
    var timezone_minutes = (parseInt(timezone_pieces[1]) * 60000);
    var timezone_seconds = (parseInt(timezone_pieces[2]) * 1000);
    if(timezone_pieces[0].charAt(0) == '-')
    {
        timezone_minutes *= -1;
        timezone_seconds *= -1;
    }
    var timezone_offset = timezone_hours + timezone_minutes + timezone_seconds;
    app_start_date = new Date(app_start_date.getTime() + (date.getTimezoneOffset() * 60000) - timezone_offset);
    app_end_date = new Date(app_end_date.getTime() + (date.getTimezoneOffset() * 60000) - timezone_offset);
    conn.query("SELECT * FROM doctors WHERE id="+doc_id, function(err, doctor)
    {
        conn.query("SELECT * FROM usuarios WHERE Identif="+pat_id, function(err, patient)
        {
            // if this doctor or patient where already processed in this iteration, skip
            var valid = true;
            for(var i = 0; i < doctors_processed.length; i++)
            {
                if(doctors_processed[i] == doctor[0]['id'])
                {
                    valid = false;
                }
            }
            for(var i = 0; i < patients_processed.length; i++)
            {
                if(patients_processed[i] == patient[0]['Identif'])
                {
                    valid = false;
                }
            }
            var in_consultation = false;
            if(docs_in_consultation.indexOf(doctor[0]['id']) >= 0)
            {
                in_consultation = true;
            }
            // handle appointment here
            if(date > app_start_date && date < app_end_date && doctor[0]['in_consultation'] != 1 && !in_consultation && valid)
            {
                var diff = -1;
                var sp_time_on = false;
                if(appts[index]['specific_time'] != null)
                {
                    var sp_time = appts[index]['specific_time'].split(":");
                    var mins_1 = (parseInt(sp_time[0]) * 60) + parseInt(sp_time[1]) - (timezone_offset / 60000);
                    var mins_2 = (date.getHours() * 60) + date.getMinutes();
                    diff = mins_2 - mins_1;
                    sp_time_on = true;
                }
                if(!sp_time_on || (diff >= 0 && diff <= 5))
                {
                    doctors_processed.push(doctor[0]['id']);
                    patients_processed.push(patient[0]['Identif']);
                    if(appts[index]['video'] == 0)
                    {
                        // phone appointment
                        proc.exec('curl --data "doc_phone='+doctor[0]['phone']+'&pat_phone='+patient[0]['telefono']+'&pat_id='+patient[0]['Identif']+'&doc_id='+doctor[0]['id']+'&pat_name='+patient[0]['Name']+'_'+patient[0]['Surname']+'&doc_name='+doctor[0]['Name']+'_'+doctor[0]['Surname']+'" https://www.health2.me/live/start_telemed_phonecall.php');
                        var called_time = ((date.getHours() * 60) + date.getMinutes()) - date.getTimezoneOffset();
                        var called_minutes = called_time % 60;
                        var called_hours = called_time / 60;
                        //conn.query("DELETE FROM appointments WHERE id="+appts[index]['id'], function(err, patient)
                        conn.query("UPDATE appointments SET processed=1, time_called='"+called_hours+":"+called_minutes+":00"+"' WHERE id="+appts[index]['id'], function(err, patient)
                        {
                            if(appts.length > (index + 1))
                            {
                                process_appointment(appts, index + 1, conn, patients_processed, doctors_processed, docs_in_consultation);
                            }
                            else
                            {
                                conn.release();
                            }
                        }); 
                    }
                    else
                    {
                        // video appointment
                        proc.exec('curl --data "doc_phone='+doctor[0]['phone']+'&pat_phone='+patient[0]['telefono']+'&pat_id='+patient[0]['Identif']+'&doc_id='+doctor[0]['id']+'&pat_name='+patient[0]['Name']+'_'+patient[0]['Surname']+'&doc_name='+doctor[0]['Name']+'_'+doctor[0]['Surname']+'" https://www.health2.me/live/start_telemed_videocall.php');
                        client.messages.create({body: "Your video consultation with Doctor "+doctor[0]['Name']+'_'+doctor[0]['Surname']+" is about to start. Please login to Health2Me to connect to your doctor.", to: "+"+patient[0]['telefono'], from: "+19034018888"}, function(err, message){});
                        
                        conn.query("UPDATE usuarios SET current_calling_doctor="+doctor[0]['id']+" WHERE Identif="+patient[0]['Identif'], function(err, rows)
                        {
                            var called_time = ((date.getHours() * 60) + date.getMinutes()) - date.getTimezoneOffset();
                            var called_minutes = called_time % 60;
                            var called_hours = called_time / 60;
                            //conn.query("DELETE FROM appointments WHERE id="+appts[index]['id'], function(err, patient)
                            conn.query("UPDATE appointments SET processed=1, time_called='"+called_hours+":"+called_minutes+":00"+"' WHERE id="+appts[index]['id'], function(err, patient)
                            {
                                if(appts.length > (index + 1))
                                {
                                    process_appointment(appts, index + 1, conn, patients_processed, doctors_processed, docs_in_consultation);
                                }
                                else
                                {
                                    conn.release();
                                }
                            }); 
                        });
                    }
                }
            }
            else
            {
                if(appts.length > (index + 1))
                {
                    process_appointment(appts, index + 1, conn, patients_processed, doctors_processed, docs_in_consultation);
                }
                else
                {
                    conn.release();
                }
            }

        });
    });
}

setInterval(function() 
{
    pool.getConnection(function(err, conn) 
    {
        var date = new Date();
        var date_str = date.getFullYear()+"-";
        if(date.getMonth() + 1 < 10)
        {
            date_str += "0";
        }
        date_str += (date.getMonth() + 1).toString()+"-";
        if(date.getDate() < 10)
        {
            date_str += "0";
        }
        date_str += date.getDate().toString();
        conn.query("SELECT * FROM appointments WHERE date>='"+date_str+"' AND processed=0 ORDER BY date, start_time, id", function(err, appointments) 
        {
            if(appointments.length > 0)
            {
                var pat_proc = new Array();
                var doc_proc = new Array();
                var docs_in_consultation = new Array();
                client.conferences.list({ status: "in-progress"}, function(err, data) 
                {
                    data.conferences.forEach(function(conference) 
                    {
                        var name = conference.friendlyName;
                        var doc_id = parseInt(name.split("_")[0]);
                        if(docs_in_consultation.indexOf(doc_id) < 0)
                        {
                            docs_in_consultation.push(doc_id);
                        }
                    });
                    process_appointment(appointments, 0, conn, pat_proc, doc_proc, docs_in_consultation);
                });
                
            }
            else
            {
                conn.release();
            }

        });
    });
}, 60000 /* 1 minute */);

