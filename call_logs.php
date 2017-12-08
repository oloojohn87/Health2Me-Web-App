<html>
    <head>
        <title>
        Health2Me Phone Call Log
        </title>
        <style>
            .log_item
            {
                width: 90%;
                height: 30px;
                background-color: #E6E6E6;
                margin: auto;
                padding-top: 10px;
                font-family: 'lato', sans-serif;
                color: #464646;
            }
        </style>
        <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />

    </head>
    <body>
        <div id="error_modal" title="Call Status Details" style="display:none; text-align:center; width: 700px; height: 500px;">
            <h4 id="error_type" style="width: 100%"></h4>
            <div style="text-align: left; width: 100%;">
                <p id="error_code_label" style="width: 100%; word-wrap: break-word;">Code: <span id="error_code" style="color: #444;"></span></p>
                <p id="error_message_label" style="width: 100%; color: #222; word-wrap: break-word;">Message: <span id="error_message" style="color: #444;"></span></p>
            </div>
        </div>
        <h1 style="text-align: center; color:#54bc00; font-family: 'lato', sans-serif;">
            <span style="color: #22aeff;">Health2Me</span> Phone Call Log
        </h1>
        <div id="loader" style="width: 220px; height: 19px; margin-left: auto; margin-right: auto; margin-top: 10px; margin-bottom: 10px; visibility: hidden;">
            <img src="images/load/8.gif"  alt="">
        </div>
        <div id="log_container">
        
            
        </div>
    </body>
    
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() 
        {
            function load_log()
            {
                $("#loader").css("visibility", "visible");
                $.get("get_call_logs.php", function(data,status)
                {
                    console.log(data);
                    var res = JSON.parse(data);
                    $("#log_container").html('');
                    var count = 0;
                    
                    var html = '<div class="log_item" ';
                    html += 'style="background-color: #22AEFF; color: #FFF; border-top-left-radius: 7px; border-top-right-radius: 7px;" ';
                    html += '>';
                    html += "<div style=\"width: 10%; text-align: center; float: left;\" >"
                    html += "Callee"
                    html += "</div>";
                    
                    html += "<div style=\"width: 10%; text-align: center; float: left;\" >"
                    html += "Callee Phone Number"
                    html += "</div>";
                    
                    html += "<div style=\"width: 10%; text-align: center; float: left;\" >"
                    html += "Caller"
                    html += "</div>";
                    
                    html += "<div style=\"width: 10%; text-align: center; float: left;\" >"
                    html += "Caller Phone Number"
                    html += "</div>";
                    
                    html += "<div style=\"width: 13%; text-align: center; float: left;\" >"
                    html += "Call Direction"
                    html += "</div>";
                    
                    html += "<div style=\"width: 10%; text-align: center; float: left;\" >"
                    html += "Call Status"
                    html += "</div>";
                    
                    html += "<div style=\"width: 18%; text-align: center; float: left;\" >"
                    html += "Start Time";
                    html += "</div>";
                    
                    html += "<div style=\"width: 18%; text-align: center; float: left;\" >"
                    html += "End Time";
                    html += "</div>";
                    
                    html += '</div>';
                    
                    $("#log_container").append(html);
                    for (var index in res)
                    {
                        
                        if (res.hasOwnProperty(index)) 
                        {
                            var arr = res[index];
                            var html = '<div class="log_item" ';
                            if(count % 2 == 1)
                            {
                                html += 'style="background-color: #F8F8F8;" ';
                            }
                            html += '>';
                            html += "<div style=\"width: 10%; text-align: center; float: left;\" >"
                            html += arr['to_name'];
                            html += "</div>";
                            
                            html += "<div style=\"width: 10%; text-align: center; float: left;\" >"
                            html += arr['to'];
                            html += "</div>";
                            
                            html += "<div style=\"width: 10%; text-align: center; float: left;\" >"
                            html += arr['from_name'];
                            html += "</div>";
                            
                            html += "<div style=\"width: 10%; text-align: center; float: left;\" >"
                            html += arr['from'];
                            html += "</div>";
                            
                            html += "<div style=\"width: 13%; text-align: center; float: left;\" >"
                            html += arr['direction'];
                            html += "</div>";
                            
                            $link_color = "#22aeff";
                            if(arr['status'] == 'queued' || arr['status'] == 'ringing')
                            {
                                $link_color = "#E07221";
                            }
                            else if(arr['status'] == 'no-answer' || arr['status'] == 'failed' || arr['status'] == 'busy' || arr['status'] == 'canceled')
                            {
                                $link_color = "#D84840";
                            }
                            else if(arr['status'] == 'completed')
                            {
                                $link_color = "#54bc00";
                            }
                            
                            html += "<div style=\"width: 10%; text-align: center; float: left;\" >";
                            html += "<input type=\"hidden\" value=\""+arr['sid']+"\" />";
                            html += "<a href=\"#\" class=\"status_button\" style=\"color: "+$link_color+"; text-decoration: none;\">";
                            html += arr['status'];
                            html += "</a></div>";
                            
                            html += "<div style=\"width: 18%; text-align: center; float: left;\" >"
                            html += arr['start_time'];
                            html += "</div>";
                            
                            html += "<div style=\"width: 18%; text-align: center; float: left;\" >"
                            html += arr['end_time'];
                            html += "</div>";
                            
                            html += '</div>';
                            
                            $("#log_container").append(html);
                            
                        }
                        count++;
                    }
                    $("#loader").css("visibility", "hidden");
                    $("#log_container").children().last().css("border-bottom-left-radius", "7px");
                    $("#log_container").children().last().css("border-bottom-right-radius", "7px");
                    $(".status_button").on('click', function(e)
                    {
                        e.preventDefault();
                        console.log("SID: " + $(this).parent().children().eq(0).val());
                        $.post("get_call_notification.php", {sid: $(this).parent().children().eq(0).val()}, function(data, status)
                        {
                            console.log(data);
                            var info = JSON.parse(data);
                            var error = "No Errors";
                            $("#error_type").css("color", "#54bc00");
                            if(info['error_type'] == "0")
                            {
                                error = "Error";
                                $("#error_type").css("color", "#D84840");
                            }
                            else if(info['error_type'] == "1")
                            {
                                error = "Warning";
                                $("#error_type").css("color", "#E0A419");
                            }
                            $("#error_type").text(error);
                            
                            $("#error_code").text(info['error_code']);
                            $("#error_message").text(info['error_message']);
                            
                            $("#error_modal").dialog({bigframe: true, width: 700, height: 500, resize: false, modal: true});
                        });
                    });
                        
                });
            }
            
            load_log();
            setInterval(function()
            {
                load_log();
            }, 30000);
        });
    </script>


</html>