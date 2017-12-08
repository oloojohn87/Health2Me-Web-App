<?php

    require("../environment_detail.php");
    $dbhost = $env_var_db['dbhost'];
    $dbname = $env_var_db['dbname'];
    $dbuser = $env_var_db['dbuser'];
    $dbpass = $env_var_db['dbpass'];

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

    // SET THE RESERVATION STATUS TO 'WAITING'

    $today = date('Y-m-d');
    $update = $con->prepare("UPDATE reservation SET status = 'WAITING' WHERE provider_id = ? AND patient_id = ? AND date BETWEEN ? AND ?");
    $update->bindValue(1, $doc, PDO::PARAM_INT);
    $update->bindValue(2, $pat, PDO::PARAM_INT);
    $update->bindValue(3, $today.' 00:00:00', PDO::PARAM_STR);
    $update->bindValue(4, $today.' 23:59:59', PDO::PARAM_STR);
    $update->execute();

    // GET PATIENT'S PHR

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
        $file = decrypt_files($phr, $pat, $pass);
    }
    
    function decrypt_files($rawimage, $quePat, $pass)
    {
        $ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
        $extensionR = strtolower(substr($rawimage,strlen($rawimage)-3,3));
        $filename = '../'.$local.'temp/'.$quePat.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
        if (!file_exists($filename))  
        {
            $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in ../".$local."Packages_Encrypted/".$ImageRaiz.".".$extensionR." -out ../".$local."temp/".$quePat."/Packages_Encrypted/".$ImageRaiz.".".$extensionR);
        }

        return $filename;
    }
?>

<html>
    <head>
        <title>Catapult Telemedicine NP</title>
        
        <!-- HIDDEN INPUTS -->
        <input id="DOCNAME" type="hidden" value="<?php echo $doc_name; ?>" />
        <input id="PATNAME" type="hidden" value="<?php echo $pat_name; ?>" />
        <input id="PHR" type="hidden" value="<?php echo $phr; ?>" />
        
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
                border: 1px solid #EE3423;
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
                color: #EEA723;
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
                border: 1px solid #EE3423;
                border-radius: 5px;
                outline: none;
                color: #FFF;
                background-color: #EE3423;
                cursor: pointer;
                font-family: Helvetica, sans-serif;
                font-size: 14px;
            }
            #connection_window_buttons{
                width: 9%;
                height: 25px;
                float: right;
            }
            #report_frame{
                z-index: 0;
            }
            #report_blocker{
                width: 100%; 
                height: 770px;
                cursor: default;
                position: absolute;
                z-index: 1;
            }
            #report_editor{
                width: 100%; 
                height: 770px; 
                position: absolute; 
                z-index: 1; 
                cursor: none;
            }
            .connection_window_button{
                width: 25px;
                height: 25px;
                border-radius: 25px;
                outline: none;
                border: 1px solid #EE3423;
                background-color: #FFF;
                color: #EE3423;
                float: right;
                margin-left: 5px;
                cursor: pointer;
            }
            .connection_window_button:disabled{
                border: 1px solid #EEA39D;
                color: #EEA39D;
                cursor: default;
            }
            .connection_window_button_selected{
                background-color: #EE3423;
                color: #FFF;
            }
            .connection_window_button_alert{
                background-color: #EE3423;
                color: #FFF;
                -webkit-animation: glow 1.0s linear infinite;
            }
            .ui-dialog-titlebar {
                background-color: #EE3423;
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
                background-color: #EE3423;
                opacity: 0.3;
                position: absolute;
                display: none;
            }
            #report_pagination{
                width: 200px;
                height: 25px;
                margin: auto;
                margin-bottom: 5px;
                color: #555;
                text-align: center;
                margin-top: 5px;
            }
            .chat_entry_remote{
                width: 80%;
                padding: 4px;
                float: left;
                background-color: #EE3423;
                color: #FFF;
                border: 1px solid #EE3423;
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
        </style>
    </head>
    
    <body>
        <!-- Video Modal Window -->
        <div id="video_window" style="display: none; width: 500px; height: 400px;">
            <div id="remoteVideos" style="background-color: #555; width: 100%; height: 100%; border-radius: 7px; overflow: hidden;">
                <video id="remoteVideo" style="width: 100%;" autoplay></video>
                
            </div>
            <div style="float:left; margin-left: 20px; margin-top: -68px;">
                <video id="localVideo" width="70" style="mask-image: url(small_video_mask.png);" autoplay></video>
            </div>
        </div>
        
        <!-- Report Modal Window -->
        <div id="report_window" style="display: none; width: 500px; height: 800px; position: relative;">
            <div id="report_cursor"></div>
            <div id="report_blocker"></div>
            <div id="report_container" style="width: 100%; height: 700px; overflow: scroll; background-color: #FFF; border-radius: 8px; border: 1px solid #BBB;">
                <canvas id="report_frame" width="550" height="700"></canvas>
            </div>
            <div id="report_pagination">
                <span id="report_pagination_current">1</span> / <span id="report_pagination_total">1</span>
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
        
        <!-- Connection Toolbar -->
        <div id="connection_toolbar">
            <div id="connection_description">You are in a consultation with <span style="color: #EE3423">NP <?php echo $doc_name; ?>.</span></div>
            <div id="connection_status">
                Call Status: <span id="call_status" style="color: #AAA;">Not Connected</span>
            </div>
            <div id="connection_time_elapsed">Time Elapsed: <span id="time_elapsed_label" style="color: #EE3423;">00:00:00</span></div>
            <div id="connection_window_buttons">
                <button class="connection_window_button" id="connection_window_chat_button"><i class="icon-comments"></i></button>
                <button class="connection_window_button" id="connection_window_report_button" style="cursor: default;"><i class="icon-folder-open"></i></button>
                <button class="connection_window_button" id="connection_window_video_button" disabled><i class="icon-facetime-video"></i></button>
            </div>
        </div>
        
        <!-- Doctor Presence Notification -->
        
        <div id="doctor_presence_notification" style="width: 100%; text-align: center; font-size: 28px; font-family: Calibri, sans-serif; color: #BBB; margin-top: 240px;">
            NP has not yet entered the consultation<br/>Please Wait ...
        </div>
        
        <script src="../js/jquery.min.js"></script>
        <script src="../js/jquery-ui.min.js"></script>
        <script src="../PDFJS/pdf.js"></script>
        <script src="../PDFJS/compatibility.js"></script>
        <script src="../webrtc/draw.js"></script>
        <script src="../js/moment-with-locales.js"></script>
        <script src="../js/socket.io-1.3.5.js"></script>
        <script src="../push/push_client.js"></script>
        <script src="../js/sweet-alert.min.js"></script>
        <script src="../webrtc/webrtc.js"></script> 
        <script src="js/catapult_telemed_pat.js"></script>
        
        
    
    </body>

</html>