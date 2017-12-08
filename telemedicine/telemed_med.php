<?php

    require 'telemedicineMedClass.php';

    $master = new telemedicineMedClass($_GET['pat_id']);
    /*
    require("../environment_detail.php");
    $dbhost = $env_var_db['dbhost'];
    $dbname = $env_var_db['dbname'];
    $dbuser = $env_var_db['dbuser'];
    $dbpass = $env_var_db['dbpass'];
    $domain = $env_var_db['hardcode'];
    $local = $env_var_db['local'];

    $tbl_name="messages"; // Table name

    $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    if (!$con)
    {
        die('Could not connect: ' . mysql_error());
    }	

    $enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
    $enc_result->execute();

    $row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);

    $pass = $row_enc['pass'];

    $doc = $_GET['doc_id'];
    $pat = $_GET['pat_id'];

    $doc_name = '';
    $pat_name = '';

    // GET DOCTOR NAME

    $doc_ = $con->prepare("SELECT CONCAT(name, ' ', surname) AS fullname FROM doctors WHERE id = ?");
    $doc_->bindValue(1, $doc, PDO::PARAM_INT);
    $doc_->execute();
    while($doc_row = $doc_->fetch(PDO::FETCH_ASSOC))
    {
        $doc_name = $doc_row['fullname'];
    }

    // GET PATIENT NAME

    $pat_ = $con->prepare("SELECT CONCAT(name, ' ', surname) AS fullname FROM usuarios WHERE Identif = ?");
    $pat_->bindValue(1, $pat, PDO::PARAM_INT);
    $pat_->execute();
    while($pat_row = $pat_->fetch(PDO::FETCH_ASSOC))
    {
        $pat_name = $pat_row['fullname'];
    }
    */
    // GET PATIENT'S PHR
    /*
    $phr = '';
    $phr_ = $con->prepare("SELECT RawImage FROM lifepin WHERE IdUsu = ? AND orig_filename LIKE 'PHR_%' ORDER BY FechaInput DESC LIMIT 1");
    $phr_->bindValue(1, $pat, PDO::PARAM_INT);
    $phr_->execute();
    while($phr_row = $phr_->fetch(PDO::FETCH_ASSOC))
    {
        $phr = $phr_row['RawImage'];
    }

    if(strlen($phr) > 0)
    {
        $file = decrypt_files($phr, $doc, $pass);
    }
    
    function decrypt_files($rawimage, $queMed, $pass)
    {
        $ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
        $extensionR = strtolower(substr($rawimage,strlen($rawimage)-3,3));
        $filename = '../'.$local.'temp/'.$queMed.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
        if (!file_exists($filename))  
        {
            $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in ../".$local."Packages_Encrypted/".$ImageRaiz.".".$extensionR." -out ../".$local."temp/".$queMed."/Packages_Encrypted/".$ImageRaiz.".".$extensionR);
        }

        return $filename;
    }*/
?>

<html>
    <head>
        <title>Catapult Telemedicine NP</title>
        
        <!-- HIDDEN INPUTS -->
        <input id="DOCNAME" type="hidden" value="<?php echo $master->doctor_name; ?>" />
        <input id="PATNAME" type="hidden" value="<?php echo $master->patient_name; ?>" />
        
        <link rel="stylesheet" href="../css/jquery-ui-1.8.16.custom.css" media="screen"  />
        <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
        <link rel='stylesheet' type='text/css' href='../css/sweet-alert.css'>
        <!-- CSS -->
        <style>
            @-webkit-keyframes glow {
                from { opacity: 0.5;}
                50% { opacity: 0.99; }
                to { opacity: 0.5; }
            }
            #connection_toolbar{
                width: 80%;
                min-width: 1050px;
                height: 27PX;
                margin: auto;
                border: 1px solid #22AEFF;
                border-radius: 5px;
                padding: 8px;
                font-family: Helvetica, sans-serif;
                color: #444;
            }
            #connection_description{
                height: 25px;
                margin-top: 3px;
                float: left;
                width: 36%;
            }
            #connection_status{
                width: 20%;
                height: 25px;
                margin-top: 3px;
                float: left;
            }
            #connection_time_elapsed{
                width: 17%;
                height: 25px;
                margin-top: 3px;
                color: #444;
                float: left;
            }
            #connection_close_consultation{
                width: 13%;
                height: 25px;
                float: right;
                margin-left: 8px;
            }
            #start_consultation_button{
                width: 13%;
                height: 25px;
                float: right;
                border: 1px solid #FFF;
                border-radius: 5px;
                outline: none;
                color: #54BC00;
                margin-top: -3px;
                background-color: #FFF;
                cursor: pointer;
                font-family: Helvetica, sans-serif;
                font-size: 14px;
            }
            #close_consultation_button{
                width: 100%;
                height: 25px;
                float: left;
                border: 1px solid #22AEFF;
                border-radius: 5px;
                outline: none;
                color: #FFF;
                background-color: #22AEFF;
                cursor: pointer;
                font-family: Helvetica, sans-serif;
                font-size: 14px;
            }
            #connection_window_buttons{
                width: 12%;
                height: 25px;
                float: right;
            }
            #next_appointment_warning{
                width: 80%;
                min-width: 1050px;
                height: 23px;
                margin: auto;
                margin-bottom: 7px;
                border: 1px solid #54BC00;
                background-color: #54BC00;
                border-radius: 5px;
                padding: 8px;
                padding-top: 12px;
                font-family: Helvetica, sans-serif;
                color: #FFF;
                -webkit-animation: glow 1.5s linear infinite;
                display: none;
            }
            #notes_window {
                font-family: Calibri, sans-serif;
            }
            #notes_text{
                width: 100%;
                height: 200px;
                margin-top: 15px;
                border: 1px solid #DDD;
                border-radius: 5px;
                outline: none;
                color: #666;
                padding: 10px;
                font-size: 12px;
                resize: none;
            }
            #notes_buttons{
                width: 280px;
                height: 30px;
                margin: auto;
            }
            .notes_button{
                width: 49%;
                height: 30px;
                border: 1px solid #22AEFF;
                outline: none;
                color: #22AEFF;
                background-color: #FFF;
                float: left;
                cursor: pointer;
            }
            .notes_button_selected{
                color: #FFF;
                background-color: #22AEFF;
            }
            #report_frame{
                z-index: 0;
                cursor: default;
            }
            #report_editor{
                width: 100%; 
                height: 770px;
                cursor: none;
                position: absolute;
            }
            .connection_window_button{
                width: 25px;
                height: 25px;
                border-radius: 25px;
                outline: none;
                border: 1px solid #22AEFF;
                background-color: #FFF;
                color: #22AEFF;
                float: right;
                margin-left: 5px;
                cursor: pointer;
            }
            .connection_window_button:disabled{
                border: 1px solid #C5E8FF;
                color: #C5E8FF;
                cursor: default;
            }
            .connection_window_button_selected{
                background-color: #22AEFF;
                color: #FFF;
            }
            .connection_window_button_alert{
                background-color: #22AEFF;
                color: #FFF;
                -webkit-animation: glow 1.0s linear infinite;
            }
            .ui-dialog-titlebar {
                background-color: #22AEFF;
                border: 0px solid #FFF;
                background-image: none;
                color: #000;
            }
            .window{
                background-color: #F8F8F8;
                box-shadow: 4px 4px 6px rgba(0, 0, 0, 0.05);
                color: #777;
            }
            #report_cursor{
                width: 12px;
                height: 12px;
                border-radius: 12px;
                background-color: #22AEFF;
                opacity: 0.3;
                z-index: 1;
                position: absolute;
                display: none;
            }
            #report_toolbar{
                width: 100%;
                height: 30px;
                margin-top: 10px;
            }
            .report_toolbar_button{
                width: 30px;
                height: 30px;
                float: right;
                border: 1px solid #22AEFF;
                border-radius: 30px;
                outline: none;
                color: #FFF;
                background-color: #81CDFE;
                cursor: pointer;
                font-family: Helvetica, sans-serif;
                font-size: 32px;
                float: left;
                margin-right: 7px;
            }
            
            .report_toolbar_button_alt{
                width: 30px;
                height: 30px;
                float: right;
                border: 1px solid #22AEFF;
                border-radius: 30px;
                outline: none;
                color: #FFF;
                background-color: #22AEFF;
                cursor: pointer;
                font-family: Helvetica, sans-serif;
                font-size: 32px;
                float: left;
                margin-right: 7px;
            }
            
            .report_toolbar_button_off{
                background-color: #FFF;
                color:#22AEFF;
            }
            .report_toolbar_button:hover{
                border: 1px solid #22AEFF;
                background-color: #22AEFF;
                color: #FFF;
            }
            
            #report_pagination{
                width: 200px;
                height: 25px;
                margin: auto;
                margin-bottom: 5px;
                color: #555;
            }
            
            .report_page_button{
                width: 25px;
                height: 25px;
                float: right;
                border: 1px solid #22AEFF;
                border-radius: 25px;
                outline: none;
                color: #FFF;
                background-color: #81CDFE;
                cursor: pointer;
                font-family: Helvetica, sans-serif;
                font-size: 12px;
                float: left;
            }
            .report_page_button:hover{
                border: 1px solid #22AEFF;
                background-color: #22AEFF;
                color: #FFF;
            }
            
            #report_pagination_text{
                width: 150px;
                height: 25px;
                text-align: center;
                float: left;
            }
            
            .chat_entry_remote{
                width: 80%;
                padding: 4px;
                float: left;
                background-color: #22AEFF;
                color: #FFF;
                border: 1px solid #22AEFF;
                border-radius: 5px;
                margin-top: 5px;
                margin-bottom: 5px;
                font-size: 12px;
            }
            .chat_entry_local{
                width: 80%;
                padding: 4px;
                float: right;
                background-color: #FFF;
                color: #777;
                border: 1px solid #D8D8D8;
                border-radius: 5px;
                margin-top: 5px;
                margin-bottom: 5px;
                font-size: 12px;
            }
            #chat_send_button{
                width: 25px; 
                height: 25px; 
                border-radius: 25px; 
                color: #FFF; 
                background-color: #54BC00; 
                outline: none; 
                border: 0px solid #FFF; 
                float: right;
                cursor: pointer;
            }
            #chat_text_box{
                width: 88%; 
                float: left; 
                font-size: 12px; 
                color: #777; 
                border-radius: 5px; 
                outline: none; 
                padding: 5px; 
                border: 1px solid #DDD;
            }
            
            .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
                color: #AAA;
            }
            .ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active {
                color: #22AEFF;
            }
            
            .report{
                float: left; 
                width: 150px; 
                height: 250px; 
                margin-right: 20px;
                margin-left: 20px;
                margin-top: 15px;
                background-color: #FFF; 
                -webkit-box-shadow: 1px 1px 8px rgba(25, 25, 25, 0.15); 
                -moz-box-shadow:1px 1px 8px rgba(25, 25, 25, 0.15); 
                box-shadow:1px 1px 8px rgba(25, 25, 25, 0.15);
                border-radius: 5px;
                border: 1px solid #DDD;
                overflow: hidden;

            }
            .report:hover{
                -webkit-box-shadow: 1px 1px 8px rgba(25, 25, 25, 0.3); 
                -moz-box-shadow:1px 1px 8px rgba(25, 25, 25, 0.3); 
                box-shadow:1px 1px 8px rgba(25, 25, 25, 0.3);
                border: 1px solid #C2C2C2;
            }
            .report_header{ 
                width: 150px; 
                height: 50px; 
                background-color: #EEE; 
                color: #999; 
            }
            .report_date{
                float: right; 

                font-family: Helvetica, sans-serif; 
                font-size: 12px; 
                margin-right: 10px; 
                margin-top: 10px;
            }
            .report_footer{
                width: 100%; 
                height: 23px; 
                padding-top: 2px; 
                opacity: 1,0;
                margin-top: -25px; 
                z-index: 2; 
                position: relative; 
                color: #FFF; 
                text-align: center; 
                font-family: Helvetica, sans-serif;
            }
                
        </style>
        <script src="../js/jquery.min.js"></script>
        <script src="../js/jquery-ui.min.js"></script>

        <!--<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->
    </head>
    
    <body>
        <!-- Video Modal Window -->
        <div id="video_window" style="display: none; width: 500px; height: 400px; position: relative;">
            <div id="remoteVideos" style="background-color: #555; width: 100%; height: 100%; border-radius: 7px; overflow: hidden; z-index: 0;">
                <video id="remoteVideo" style="width: 100%; z-index: 0;" autoplay></video>
                
            </div>
            <div style="float:left; margin-left: 20px; margin-top: -68px; z-index: 1;">
                <video id="localVideo" width="70" style="mask-image: url(small_video_mask.png); z-index: 1;" autoplay></video>
            </div>
        </div>
        
        <!-- Notes Modal Window -->
        <div id="notes_window" style="display: none; width: 700px; min-height: 300px; overflow:scroll;">
            <div id="notes_buttons">
                <button id="notes_button_public" class="notes_button notes_button_selected" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;">Public</button>
                <button id="notes_button_private" class="notes_button" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;">Private</button>
            </div>
            <textarea id="notes_text" style="display:none;"></textarea>
            
        </div>
        
        <!-- Report Modal Window -->
        <div id="report_window" style="display: none; width: 500px; height: 800px; position: relative;">
            <div id="report_pagination">
                <button class="report_page_button" id="report_page_prev"><i class="icon-chevron-left"></i></button>
                <div id="report_pagination_text"><span id="report_pagination_current">1</span> / <span id="report_pagination_total">1</span></div>
                <button class="report_page_button" id="report_page_next"><i class="icon-chevron-right"></i></button>
            </div>
            <div id="report_cursor"></div>
            <div id="report_container" style="width: 550px; height: 700px; overflow: hidden; background-color: #FFF; border-radius: 8px; border: 1px solid #BBB; z-index: 0; position: relative;">
                <div id="report_container" style="width: 550px; height: 700px; overflow: scroll; background-color: #FFF; border-radius: 8px; position: relative; z-index: 0;">
                    <!--<canvas id="report_editor"></canvas>-->
                    <canvas id="report_frame" width="550" height="700"></canvas>

                </div>
                <div id="report_stream" style="width: 100%; height: 300px; background-color: RGBA(248, 248, 248, 0.9); border-top: 1px solid #DDD;  margin-top: -300px; overflow-y: hidden; overflow-x: scroll; position: relative; z-index: 1; display: none;">
                    <div id="report_stream_container" style="height: 300px;"></div>
                </div>
            </div>
            <div id="report_toolbar">
                <button class="report_toolbar_button" id="zoom_in"><i class="icon-zoom-in"></i></button>
                <button class="report_toolbar_button" id="zoom_out"><i class="icon-zoom-out"></i></button>
                <button class="report_toolbar_button" id="erase"><i class="icon-eraser"></i></button>
                <button class="report_toolbar_button_alt" id="draw"><i class="icon-pencil"></i></button>
                <button class="report_toolbar_button_alt" id="pick_report"><i class="icon-file-text"></i></button>
            </div>
        </div>
        
        <!-- Chat Modal Window -->
        <div id="chat_window" style="display: none; width: 300px; height: 400px;">
            <div id="chat_entries" style="width: 100%; height: 310px; overflow-y: scroll; overflow-x: hidden;">
            </div>
            <div style="width: 100%; height: 28px; margin-top: 11px;">
                <button id="chat_send_button"><i class="icon-chevron-right"></i></button>
                <input type="text" id="chat_text_box" placeholder="message..." />
            </div>
        </div>
        
        <!-- Closing Consultation Modal -->
        <div id="please_wait_modal" title="Please Wait" style="width: 300px; height: 200px; display: none;">
            <img src="../images/load/29.gif" style="margin-top: 15px; margin-left: 124px;margin-bottom: 30px;"  alt="">
            <p style="text-align: center;">Closing Consultation.<br/>Please do not exit this page.</p>
        
        </div>
        
        <!-- Next Appointment Warning -->
        <div id="next_appointment_warning">
            Your next patient <span id="patient_waiting">Jane Doe</span> is awaiting.
            <button id="start_consultation_button">Start Consultation</button>
        </div>
        
        <!-- MODAL VIEW FOR SUMMARY -->
        <div id="summary_window" lang="en" title="Summary" style="display:none; text-align:center; width: 1000px; height: 660px; overflow: hidden;">
            <iframe src="../medicalPassport.php?modal=1&IdUsu=<?php echo $master->pat_id; ?>" width="1000" height="660" scrolling="no" style="width:1000px;height:660px; margin: 0px; border: 0px solid #FFF; outline: 0px; padding: 0px; overflow: hidden;"></iframe>
        </div>
        
        <!-- Connection Toolbar -->
        <div id="connection_toolbar">
            <div id="connection_description">You are in a consultation with <span style="color: #22AEFF"><?php echo $master->patient_name; ?>.</span></div>
            <div id="connection_status">
                Call Status: <span id="call_status" style="color: #AAA;">Not Connected</span>
            </div>
            <div id="connection_time_elapsed">Time Elapsed: <span id="time_elapsed_label" style="color: #22AEFF;">00:00:00</span></div>
            <div id="connection_close_consultation">
                <button id="close_consultation_button">Close Consultation</button>
            </div>
            <div id="connection_window_buttons">
                <button class="connection_window_button" id="connection_window_chat_button"><i class="icon-comments"></i></button>
                <button class="connection_window_button" id="connection_window_notes_button"><i class="icon-pencil"></i></button>
                <button class="connection_window_button" id="connection_window_summary_button"><i class="icon-user"></i></button>
                <button class="connection_window_button" id="connection_window_report_button"><i class="icon-folder-open"></i></button>
                <button class="connection_window_button" id="connection_window_video_button" disabled><i class="icon-facetime-video"></i></button>
            </div>
        </div>
        
        <script src="../PDFJS/pdf.js"></script>
        <script src="../PDFJS/compatibility.js"></script>
        <script src="../webrtc/draw.js"></script>
        <!--<script src="sizeof.compressed.js"></script>-->
        <script src="../js/moment-with-locales.js"></script>
        <script src="../js/socket.io-1.3.5.js"></script>
        <script src="../js/jquery.multi-open-accordion-1.5.3.min.js"></script>
        <script src="../push/push_client.js"></script>
        <script src="../js/sweet-alert.min.js"></script>
        <script src="../webrtc/webrtc.js"></script> 
        <script src="js/telemed_med.js"></script>
        
        
    
    </body>

</html>