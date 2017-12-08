
$(document).ready(function()
{
    var doc_id = 0;
    var pat_id = 0;
    var doc_name = $("#DOCNAME").val();
    var pat_name = $("#PATNAME").val();
    var webrtc = null;
    var dataChannelReady = false;
    var draw_mode = true;
    
    var url = window.location.href;
    var arguments_str = url.split('?')[1];
    var arguments_array = arguments_str.split(/=|&/);
    var arguments = {};
    for(var i = 0; i < arguments_array.length; i += 2)
    {
        arguments[arguments_array[i]] = arguments_array[i + 1];
    }
    
    if(arguments.hasOwnProperty('doc_id'))
    {
        doc_id = arguments['doc_id'];
    }
    if(arguments.hasOwnProperty('pat_id'))
    {
        pat_id = arguments['pat_id'];
    }
    
    //var phr = window.location.protocol + '//' + window.location.host + '/temp/'+pat_id+'/Packages_Encrypted/'+$("#PHR").val();
    var push = new Push(pat_id, window.location.host + ':3955');
    push.bind('new_message', function(data)
    {
        if(data.id == doc_id)
        {
            var date = moment(data.date, 'X');
            $("#chat_entries").prepend('<div class="chat_entry_remote" style="display: none;">' + data.msg + '<div style="width: 100%; font-size: 8px; text-align: center; padding-top: 4px;">' + date.format('h:mm A') + '</div></div>');
            $("#chat_entries").children().eq(0).slideDown();
            if(!$("#chat_window").dialog('isOpen'))
            {
                $("#connection_window_chat_button").addClass('connection_window_button_alert');
            }
        }
    });
    push.bind('doctor_in', function(data)
    {
        // time elapsed
        
        $("#doctor_presence_notification").css('color', '#54BC00');
        $("#doctor_presence_notification").html('Doctor has entered the consultation');
        $('body').css('background-color', '#FFF');
        var start = new Date();
        setInterval(function()
        {
            var now = new Date();

            var timeDiff = (now - start) / 1000;
            var seconds = Math.round(timeDiff % 60);
            timeDiff = Math.floor(timeDiff / 60);
            var minutes = Math.round(timeDiff % 60);
            timeDiff = Math.floor(timeDiff / 60);
            var hours = Math.round(timeDiff % 24);

            if(seconds < 10)
                seconds = '0' + seconds;
            if(minutes < 10)
                minutes = '0' + minutes;
            if(hours < 10)
                hours = '0' + hours;

            $("#time_elapsed_label").text(hours + ':' + minutes + ':' + seconds);
        }, 1000);
    });
    push.bind('consultation_closed', function(data)
    {
        swal({title: "Closing Consultation", text: "The doctor has closed the consultation.\nYou will now be redirected to your records page where you can see the doctors's notes", type: "warning", showCancelButton: false, confirmButtonText: "Ok", closeOnConfirm: true }, function()
        {   
            window.location.href = window.location.protocol + '//' + window.location.host + '/patientdetailMED-new.php?IdUsu=' + pat_id;
         });
    });
    
    

    push.send(doc_id, 'patient_waiting', {name: pat_name, id: pat_id});
    var video_dialog = $("#video_window").dialog(
    {
        bgiframe: false, 
        height: 500, 
        width: 700, 
        resizable: false, 
        title: 'Video', 
        autoOpen: false,
        position: [130, 120],
        dialogClass: 'window',
        open: function(event, ui)
        {
            
            push.send(doc_id, 'patient_waiting', {name: pat_name, id: pat_id});
        },
        close: function(event, ui)
        {
            //webrtc.leaveRoom();
            $("#connection_window_video_button").removeClass("connection_window_button_selected");
        }
    });
    var report_offset = {top: 0, left: 0};
    var report_dialog = $("#report_window").dialog(
    {
        bgiframe: false, 
        height: 825, 
        width: 600, 
        resizable: false, 
        title: 'Report', 
        autoOpen: false,
        position: [870, 120],
        dialogClass: 'window',
        close: function(event, ui)
        {
            $("#connection_window_report_button").removeClass("connection_window_button_selected");
        }
    });
    var chat_dialog = $("#chat_window").dialog(
    {
        bgiframe: false, 
        height: 400, 
        width: 300, 
        resizable: false, 
        title: 'Message', 
        autoOpen: false,
        position: [870, 120],
        dialogClass: 'window',
        close: function(event, ui)
        {
            $("#connection_window_chat_button").removeClass("connection_window_button_selected");
        }
    });
    
    push.bind('video_window', function(data)
    {
        if(data == '1')
        {
            $("#connection_window_video_button").addClass("connection_window_button_selected");
            video_dialog.dialog('open');
        }
        else
        {
            video_dialog.dialog('close');
            $("#connection_window_video_button").removeClass("connection_window_button_selected");
        }
    });

    $("#connection_window_video_button").on('click', function()
    {
        if($(this).hasClass("connection_window_button_selected"))
        {
            $(this).removeClass("connection_window_button_selected");
            video_dialog.dialog('close');
        }
        else
        {
            $(this).addClass("connection_window_button_selected");
            video_dialog.dialog('open');
        }
    });
    $("#connection_window_chat_button").on('click', function()
    {
        if($(this).hasClass("connection_window_button_selected"))
        {
            $(this).removeClass("connection_window_button_selected");
            chat_dialog.dialog('close');
        }
        else
        {
            if($(this).hasClass('connection_window_button_alert'))
            {
                $(this).removeClass('connection_window_button_alert');
            }
            $(this).addClass("connection_window_button_selected");
            chat_dialog.dialog('open');
        }
    });
    /*$("#connection_window_report_button").on('click', function()
    {
        if($(this).hasClass("connection_window_button_selected"))
        {
            $(this).removeClass("connection_window_button_selected");
            report_dialog.dialog('close');
        }
        else
        {
            $(this).addClass("connection_window_button_selected");
            report_dialog.dialog('open');
        }
    });*/
    
    // pdf
    
    var report_pdf = null;
    var report_pdf_page = 1;
    var report_pdf_num_pages = 1;
    var report_pdf_scale = 1.0;
    
    function renderPDF()
    {
        if(report_pdf != null)
        {
            report_pdf.getPage(report_pdf_page).then(function(page) 
            {
                var viewport = page.getViewport(report_pdf_scale);

                //
                // Prepare canvas using PDF page dimensions
                //
                var canvas = document.getElementById('report_frame');
                var context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                //
                // Render PDF page into canvas context
                //
                var renderContext = 
                {
                    canvasContext: context,
                    viewport: viewport
                };
                page.render(renderContext);
            });
        }
    }
    
    function load_report(report)
    {
        $.get('../DecryptFile.php?rawimage=' + report + '&queMed=' + pat_id, function(data, status)
        {
             PDFJS.getDocument(window.location.protocol + '//' + window.location.host + '/temp/' + pat_id + '/Packages_Encrypted/' + report).then(function(pdf) 
            {
                report_pdf = pdf;
                report_pdf_page = 1;
                report_pdf_scale = 1.0;
                report_pdf_num_pages = pdf.numPages;
                $("#report_pagination_current").text('1');
                $("#report_pagination_total").text(report_pdf_num_pages);
                renderPDF();

            });
        });
    }
    push.bind('load_report', function(data)
    {
        load_report(data.file);
    });
    
    // draw
    var draw = new Draw('report_frame');
    
    $("#report_container").scroll(function(e)
    {
        e.preventDefault();
    });
    $("#chat_text_box").keypress(function(e) 
    {
        if(e.which == 13) 
        {
            $("#chat_send_button").trigger('click');
        }
    });
    $("#chat_send_button").on('click', function()
    {
        if($("#chat_text_box").val().length > 0)
        {
            var date = moment();
            if(dataChannelReady)
            {
                webrtc.send({type: 'MSG', val: $("#chat_text_box").val(), date: date.format('X')});
            }
            else
                push.send(doc_id, 'new_message', {id: pat_id, msg: $("#chat_text_box").val(), date: date.format('X')});
            $("#chat_entries").prepend('<div class="chat_entry_local" style="display: none;">' + $("#chat_text_box").val() + '<div style="width: 100%; font-size: 8px; text-align: center; padding-top: 4px;">' + date.format('h:mm A') + '</div></div>');
            $("#chat_entries").children().eq(0).slideDown();
            $("#chat_text_box").val('');
        }
    });
    
    var webrtc_properties = {
        ID: pat_id,
        remoteID: doc_id,
        localVideo: 'localVideo',
        remoteVideo: 'remoteVideo',
        onReadyToCall: function()
        {
            $("#connection_window_video_button").removeAttr('disabled');
            dataChannelReady = false;
            webrtc.enterRoom();
        },
        onDataChannelReady: function()
        {
            dataChannelReady = true;
        },
        onDataChannelMessage: function(data)
        {
            /*var t = (data[0] & 0xFF000000) >> 24;
            var d1 = (data[0] & 0x00FFF000) >> 12;
            var d2 = (data[0] & 0x00000FFF);
            switch(t)
            {
                    
                case 0x01: // Mouse Over
                    $("#report_cursor").css({top: d2 + 5, left: d1 + 12});
                    $("#report_cursor").css('display', 'block');
                    break;
                case 0x02: // Mouse Move
                    $("#report_cursor").css({top: d2 + 5, left: d1 + 12});
                    if(draw_mode)
                        draw.move({x: d1, y: d2});
                    break;
                case 0x03: // Mouse Out
                    $("#report_cursor").css('display', 'none');
                    if(draw_mode)
                        draw.end({x: 0, y: 0});
                    break;
                case 0x04: // Mouse Down
                    $("#report_cursor").animate({opacity: 0.7}, 100, function(){});
                    if(draw_mode)
                        draw.begin({x: d1, y: d2});
                    break;
                case 0x05: // Mouse Up
                    $("#report_cursor").animate({opacity: 0.3}, 100, function(){});
                    if(draw_mode)
                        draw.end({x: 0, y: 0});
                    break;
                case 0x06: // Zoom
                    report_pdf_scale = (d1 / 100.0);
                    renderPDF();
                    break;
                case 0x07: // Page Change
                    report_pdf_page = d1;
                    $("#report_pagination_current").text(report_pdf_page);
                    renderPDF();
                    break;
                case 0x08: // Scroll
                    document.getElementById('report_container').scrollLeft = d1;
                    document.getElementById('report_container').scrollTop = d2;
                    break;
                case 0x09: // Open Report
                    report_dialog.dialog('open');
                    $("#connection_window_report_button").addClass("connection_window_button_selected");
                    break;
                case 0x0A: // Close Report
                    report_dialog.dialog('close');
                    $("#connection_window_report_button").removeClass("connection_window_button_selected");
                    break;
                case 0x0B: // Erase
                    renderPDF();
                    break;
                case 0x0C: // Draw Mode Changed
                    draw_mode = d1 === 1 ? true : false;
                    break;
                case 0x0D: // Instant Message
                    var date = moment(data[1].date, 'X');
                    $("#chat_entries").prepend('<div class="chat_entry_remote" style="display: none;">' + data[1].val + '<div style="width: 100%; font-size: 8px; text-align: center; padding-top: 4px;">' + date.format('h:mm A') + '</div></div>');
                    $("#chat_entries").children().eq(0).slideDown();
                    if(!$("#chat_window").dialog('isOpen'))
                    {
                        $("#connection_window_chat_button").addClass('connection_window_button_alert');
                    }
                    break;
            };*/
            if(data.type == 'MSOVR')
            {
                $("#report_cursor").css({top: data.y, left: data.x});
                $("#report_cursor").css('display', 'block');
            }
            else if(data.type == 'MSMV')
            {
                $("#report_cursor").css({top: data.y, left: data.x});
                if(draw_mode)
                    draw.move({x: data.drawX, y: data.drawY});
            }
            else if(data.type == 'MSOUT')
            {
                $("#report_cursor").css('display', 'none');
                if(draw_mode)
                    draw.end({x: 0, y: 0});
            }
            else if(data.type == 'MSDWN')
            {
                $("#report_cursor").animate({opacity: 0.7}, 100, function(){});
                if(draw_mode)
                    draw.begin({x: data.drawX, y: data.drawY});
            }
            else if(data.type == 'MSUP')
            {
                $("#report_cursor").animate({opacity: 0.3}, 100, function(){});
                if(draw_mode)
                    draw.end({x: 0, y: 0});
            }
            else if(data.type == 'ZM')
            {
                report_pdf_scale = data.z;
                renderPDF();
            }
            else if(data.type == 'PG')
            {
                report_pdf_page = data.page;
                $("#report_pagination_current").text(report_pdf_page);
                renderPDF();
            }
            else if(data.type == 'SCR')
            {
                document.getElementById('report_container').scrollTop = data.y;
                document.getElementById('report_container').scrollLeft = data.x;
            }
            else if(data.type == 'REP+')
            {
                report_dialog.dialog('open');
                $("#connection_window_report_button").addClass("connection_window_button_selected");
            }
            else if(data.type == 'REP-')
            {
                report_dialog.dialog('close');
                $("#connection_window_report_button").removeClass("connection_window_button_selected");
            }
            else if(data.type == 'ERS')
            {
                renderPDF();
            }
            else if(data.type == 'DRW')
            {
                draw_mode = data.val;
            }
            else if(data.type == 'MSG')
            {
                var date = moment(data.date, 'X');
                $("#chat_entries").prepend('<div class="chat_entry_remote" style="display: none;">' + data.val + '<div style="width: 100%; font-size: 8px; text-align: center; padding-top: 4px;">' + date.format('h:mm A') + '</div></div>');
                $("#chat_entries").children().eq(0).slideDown();
                if(!$("#chat_window").dialog('isOpen'))
                {
                    $("#connection_window_chat_button").addClass('connection_window_button_alert');
                }
            }
            else if(data.type == 'REPLD')
            {
                load_report(data.file);
            }
        },
        onConnecting: function()
        {
            $("#call_status").css('color', '#22AEFF');
            $("#call_status").text('Connecting...');
        },
        onConnected: function()
        {
            $("#call_status").css('color', '#54BC00');
            $("#call_status").text('Connected.');
        },
        onDisconnected: function()
        {
            $("#call_status").css('color', '#AAA');
            $("#call_status").text('Not Connected');
            dataChannelReady = false;
        },
        onFailed: function()
        {
            $("#call_status").css('color', '#EE3423');
            $("#call_status").text('Failed');
            dataChannelReady = false;
        }
    };
    
    webrtc = new WebRTC(webrtc_properties);
});