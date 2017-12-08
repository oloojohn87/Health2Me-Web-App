
$(document).ready(function()
{
    var private_notes = "";
    var public_notes = "";
    var doc_id = 0;
    var pat_id = 0;
    var next_pat_id = 0;
    var webrtc = null;
    var dataChannelReady = false;
    var draw_mode = true;
    var trifold_items = {};
    
    var url = window.location.href;
    var page = url.split('?')[0];
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
    
    var phr = window.location.protocol + '//' + window.location.host + '/temp/'+doc_id+'/Packages_Encrypted/'+$("#PHR").val();
    var push = new Push(doc_id, window.location.host + ':3955');
    
    push.bind('patient_waiting', function(data)
    {
        var info = JSON.parse(data);
        if(info.id != pat_id)
        {
            $("#patient_waiting").text(info.name);
            next_pat_id = info.id;
            $("#next_appointment_warning").slideDown();
        }
    });
    push.bind('new_message', function(data)
    {
        if(data.id == pat_id)
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
    push.send(pat_id, 'doctor_in', '');
    function close_consultation()
    {
        console.log('closed consultation');
        
        var action_items = new Array();
        for(var i = 0; i <= 10; i++)
        {
            if($("#" + i.toString()).is(":checked"))
                action_items.push(i);
        }
        
        push.send(pat_id, 'consultation_closed', '');
        
        $.post("generate_phr.php", {doc_id: doc_id, pat_id: pat_id, action_items: JSON.stringify(action_items), trifold: JSON.stringify(trifold_items)}, function(data, status)
        {
            console.log(action_items);
            console.log(trifold_items);
            window.location.href = window.location.protocol + '//' + window.location.host + '/Main_Application/provider/maindashboard/html/MainDashboardCATA.php';
            
        });
    }
    
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
            webrtc.enterRoom();
            push.send(pat_id, 'video_window', '1');
        },
        close: function(event, ui)
        {
            webrtc.leaveRoom();
            push.send(pat_id, 'video_window', '0');
            $("#connection_window_video_button").removeClass("connection_window_button_selected");
        }
    });
    var notes_dialog = $("#notes_window").dialog(
    {
        bgiframe: false, 
        height: 300, 
        width: 700, 
        resizable: true,
        minHeight: 300,
        minWidth: 700,
        title: 'Notes', 
        autoOpen: false,
        position: [130, 645],
        dialogClass: 'window',
        resize: function(event, ui)
        {
            $("#notes_text").css('height', (ui.size.height - 100).toString() + 'px');
        },
        close: function(event, ui)
        {

            $("#connection_window_notes_button").removeClass("connection_window_button_selected");
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
            if(dataChannelReady)
            {
                webrtc.send({type: 'REP-'});
            }
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
    
    $("#connection_window_notes_button").on('click', function()
    {
        if($(this).hasClass("connection_window_button_selected"))
        {
            $(this).removeClass("connection_window_button_selected");
            notes_dialog.dialog('close');
        }
        else
        {
            $(this).addClass("connection_window_button_selected");
            notes_dialog.dialog('open');
            //$(".notes_button").trigger('click');
            $("#notes_button_public").trigger('click');
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
    $("#connection_window_report_button").on('click', function()
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
            if(dataChannelReady)
            {
                webrtc.send({type: 'REP+'});
            }
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
    
    $("#accordion").multiOpenAccordion({ 
        active: 'none',
        autoHeight: false,
        navigation: true,
        collapsible: true,
        tabShown: function(e, ui){
            var textareaWidth = ui['content'].width();
            ui['content'].find('textarea').css({width: textareaWidth, height: '100px'});
            var textareaId = ui['content'].find('textarea').attr('id');
            trifold_items[textareaId] = '';
            //console.log(trifold_items);
            //console.log(JSON.stringify(ui['content']));
        },
        tabHidden: function(e, ui) {
            var index = ui['content'].find('textarea').attr('id');
            if(index in trifold_items) delete trifold_items[index];
        }
    });
    
    $(".notes_button").on('click', function()
    {
        if($("#notes_button_public").hasClass('notes_button_selected'))
            public_notes = $("#notes_text").val();
        else if($("#notes_button_private").hasClass('notes_button_selected'))
            private_notes = $("#notes_text").val();
        
        if($(this).text() == 'Private') {
            $("#action_items").css('display', 'none');
            $("#accordion").css('display', 'none');
            $("#notes_text").css('display','inline-block');
            $("#notes_text").val(private_notes);
        }
        else if($(this).text() == 'Public') {
            //$("#notes_text").val(public_notes);
            $("#action_items").css('display', 'none');
            $("#notes_text").css('display', 'none');
            $("#accordion").css('display', 'block');
        }
        else if($(this).text() == 'Action Items') {
            $("#notes_text").css('display', 'none');
            $("#action_items").css('display', 'block');
            $("#accordion").css('display', 'none');
        }
        else {
            $("#action_items").css('display', 'none');
            $("#notes_text").css('display', 'none');
            $("#accordion").css('display', 'none');
        }
    
        $(".notes_button").removeClass('notes_button_selected');
        $(this).addClass('notes_button_selected');
    });
    
    $("input[name='actionItems']").change(function () {
      
      //WHEN #0 HAS BEEN CHECKED THEN UNCHECK EVERYTHING ELSE AND VICE VERSA
      if($(this).attr('id') == 0) {
          $("input[name='actionItems']:checked").not('#0').each(function() {
             $(this).prop('checked', ''); 
          });
      }
      else $('#0').prop('checked','');
    
      var maxAllowed = 3;
      var cnt = $("input[name='actionItems']:checked").length;
        
      //CHECK THE MAXIMUM NUMBER OF CHECKED ITEMS
      if (cnt > maxAllowed)
      {
         $(this).prop("checked", "");
         alert('You checked the maximum: ' + maxAllowed + ' Action Items already!');
      }
    });
    
    $('span.public_notes textarea').keyup(function() {
        var TAID = $(this).attr('id');
        if(TAID in trifold_items) trifold_items[TAID] = $(this).val();
    });
    
    $("#start_consultation_button").on('click', function()
    {
        swal(
        {
            title: 'Start Consultation',
            text: 'Starting this consultation will end the current consultation.\nThis action cannot be undone. Do you wish to continue?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',  
            cancelButtonText: 'No',   
            closeOnConfirm: true,   
            closeOnCancel: true
        },
        function(isConfirm) 
        {
            if(isConfirm)
            {
                close_consultation();
                
                // start new consultation here
                window.location.href = page + '?doc_id=' + doc_id + '&pat_id=' + next_pat_id;
            }
            
        });
    });
    $("#close_consultation_button").on('click', function()
    {
        swal(
        {
            title: 'Close Consultation',
            text: 'Are you sure you would like to end the consultation?\nThis action cannot be undone.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',  
            cancelButtonText: 'No',   
            closeOnConfirm: true,   
            closeOnCancel: true
        },
        function(isConfirm) 
        {
            if(isConfirm)
                close_consultation();
        });
    });
    
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
    
    if(phr.length > 0)
    {
        PDFJS.getDocument(phr).then(function(pdf) 
        {
            report_pdf = pdf;
            report_pdf_num_pages = pdf.numPages;
            $("#report_pagination_total").text(report_pdf_num_pages);
            renderPDF();
        });
    }
    
    // draw
    var draw = new Draw('report_frame');
    
    $("#report_page_prev").on('click', function()
    {
        if(report_pdf_page > 1)
        {
            report_pdf_page -= 1;
            $("#report_pagination_current").text(report_pdf_page);
        }
        renderPDF();
        
        if(dataChannelReady)
        {
            webrtc.send({type: 'PG', page: report_pdf_page});
            //var code = (0x07 << 24) | ((report_pdf_page & 0x00000FFF) << 12);
            //webrtc.send([code]);
        }
    });
    $("#report_page_next").on('click', function()
    {
        if(report_pdf_page < report_pdf_num_pages)
        {
            report_pdf_page += 1;
            $("#report_pagination_current").text(report_pdf_page);
        }
        renderPDF();
        
        if(dataChannelReady)
        {
            webrtc.send({type: 'PG', page: report_pdf_page});
            //var code = (0x07 << 24) | ((report_pdf_page & 0x00000FFF) << 12);
            //webrtc.send([code]);
        }
    });
    
    $("#zoom_in").on('click', function()
    {
        if(report_pdf_scale < 2.0)
        {
            report_pdf_scale += 0.05;
        }
        renderPDF();
        
        if(dataChannelReady)
        {
            webrtc.send({type: 'ZM', z: report_pdf_scale});
            //var code = (0x06 << 24) | ((Math.floor(report_pdf_scale * 100) & 0x00000FFF) << 12);
            //webrtc.send([code]);
        }
        
    });
    $("#zoom_out").on('click', function()
    {
        if(report_pdf_scale > 0.5)
        {
            report_pdf_scale -= 0.05;
        }
        renderPDF();
        
        if(dataChannelReady)
        {
            webrtc.send({type: 'ZM', z: report_pdf_scale});
            //var code = (0x06 << 24) | ((Math.floor(report_pdf_scale * 100) & 0x00000FFF) << 12);
            //webrtc.send([code]);
        }
    });
    
    $("#report_container").scroll(function()
    {
        if(dataChannelReady)
        {
            webrtc.send({type: 'SCR', x: document.getElementById('report_container').scrollLeft, y: document.getElementById('report_container').scrollTop});
            /*var code = (0x08 << 24) | 
            ((document.getElementById('report_container').scrollLeft & 0x00000FFF) << 12) | 
            (document.getElementById('report_container').scrollTop & 0x00000FFF);
            webrtc.send([code]);*/
        }
    });
    
    $("#erase").on('click', function()
    {
        renderPDF();
        if(dataChannelReady)
        {
            webrtc.send({type: 'ERS'});
            //var code = (0x0B << 24);
            //webrtc.send([code]);
        }
    });
    
    $("#draw").on('click', function()
    {
        if($(this).hasClass('report_toolbar_button_off'))
        {
            $(this).removeClass('report_toolbar_button_off');
            draw_mode = true;
        }
        else
        {
            $(this).addClass('report_toolbar_button_off');
            draw_mode = false;
        }
        if(dataChannelReady)
        {
            webrtc.send({type: 'DRW', val: draw_mode});
            //var code = (0x0C << 24) | (((draw_mode ? 1 : 0) & 0x00000FFF) << 12);
            //webrtc.send([code]);
        }
    });

    $("#report_frame").on('mouseover', function(event)
    {
        
        report_offset = $("#report_window").dialog("open").offset();
        //$("#report_cursor").css('display', 'block');
        if(dataChannelReady)
        {
            webrtc.send({type: 'MSOVR', x: event.pageX - $("#report_container").offset().left + 12, y: event.pageY - $("#report_container").offset().top + 5});
            //var code = (0x01 << 24) | (((event.pageX - $("#report_frame").offset().left) & 0x00000FFF) << 12) | (((event.pageY - $("#report_frame").offset().top) & 0x00000FFF));
            //webrtc.send([code]);
        }
    });
    
    $("#report_frame").on('mousemove', function(event)
    {
        if(draw_mode)
            draw.move({x: event.pageX - $("#report_frame").offset().left, y: event.pageY - $("#report_frame").offset().top});
        //$("#report_cursor").css({top: event.pageY - report_offset.top, left: event.pageX - report_offset.left});
        
        if(dataChannelReady)
        {
            webrtc.send(
            {
                type: 'MSMV', 
                x: event.pageX - $("#report_container").offset().left + 12, 
                y: event.pageY - $("#report_container").offset().top + 5,
                drawX: event.pageX - $("#report_frame").offset().left,
                drawY: event.pageY - $("#report_frame").offset().top
            });
            /*var code = (0x02 << 24) | 
            (((event.pageX - $("#report_frame").offset().left) & 0x00000FFF) << 12) | 
            ((event.pageY - $("#report_frame").offset().top) & 0x00000FFF);
            webrtc.send([code]);*/
        }
    });
    
    $("#report_frame").on('mouseout', function(event)
    {
        //$("#report_cursor").css('display', 'none');
        if(draw_mode)
            draw.end({x: 0, y: 0});
        if(dataChannelReady)
        {
            webrtc.send({type: 'MSOUT'});
            //var code = (0x03 << 24);
            //webrtc.send([code]);
        }
    });
    $("#report_frame").on('mousedown', function(event)
    {
        //$("#report_cursor").animate({opacity: 0.7}, 100, function(){});
        if(draw_mode)
            draw.begin({x: event.pageX - $("#report_frame").offset().left, y: event.pageY - $("#report_frame").offset().top});
        if(dataChannelReady)
        {
            webrtc.send(
            {
                type: 'MSDWN', 
                x: event.pageX - $("#report_container").offset().left + 12, 
                y: event.pageY - $("#report_container").offset().top + 5,
                drawX: event.pageX - $("#report_frame").offset().left,
                drawY: event.pageY - $("#report_frame").offset().top
            });
            /*var code = (0x04 << 24) | 
            (((event.pageX - $("#report_frame").offset().left) & 0x00000FFF) << 12) | 
            ((event.pageY - $("#report_frame").offset().top) & 0x00000FFF);
            webrtc.send([code]);*/
        }
    });
    $("#report_frame").on('mouseup', function(event)
    {
        if(draw_mode)
            draw.end({x: 0, y: 0});
        //$("#report_cursor").animate({opacity: 0.3}, 100, function(){});
        if(dataChannelReady)
        {
            webrtc.send({type: 'MSUP'});
            //var code = (0x05 << 24);
            //webrtc.send([code]);
        }
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
                //var code = (0x0D << 24);
                webrtc.send({type: 'MSG', val: $("#chat_text_box").val(), date: date.format('X')});
            }
            else
                push.send(pat_id, 'new_message', {id: doc_id, msg: $("#chat_text_box").val(), date: date.format('X')});
            $("#chat_entries").prepend('<div class="chat_entry_local" style="display: none;">' + $("#chat_text_box").val() + '<div style="width: 100%; font-size: 8px; text-align: center; padding-top: 4px;">' + date.format('h:mm A') + '</div></div>');
            $("#chat_entries").children().eq(0).slideDown();
            $("#chat_text_box").val('');
        }
    });
    
    

    
    // webrtc
    
    var webrtc_properties = {
        ID: doc_id,
        remoteID: pat_id,
        localVideo: 'localVideo',
        remoteVideo: 'remoteVideo',
        onReadyToCall: function()
        {
            $("#connection_window_video_button").removeAttr('disabled');
            dataChannelReady = false;
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
            if(data.type == 'MSG')
            {
                
                var date = moment(data.date, 'X');
                $("#chat_entries").prepend('<div class="chat_entry_remote" style="display: none;">' + data.val + '<div style="width: 100%; font-size: 8px; text-align: center; padding-top: 4px;">' + date.format('h:mm A') + '</div></div>');
                $("#chat_entries").children().eq(0).slideDown();
                if(!$("#chat_window").dialog('isOpen'))
                {
                    $("#connection_window_chat_button").addClass('connection_window_button_alert');
                }
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
    
    // time elapsed
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