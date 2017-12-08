<?php
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
$hardcode = $env_var_db['hardcode'];
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
$id = 1;
$doc_id = -1;
$pat_id = -1;
$doc_name = '';
$pat_name = '';
$show_notification = true;
if(isset($_GET['show']) && $_GET['show'] == '0')
{
    $show_notification = false;
}
if(isset($_GET['ID']))
{
    $id = $_GET['ID'];
    $result = $con->prepare("SELECT med_id,pat_id FROM appointments where id=?");
	$result->bindValue(1, $id, PDO::PARAM_INT);
	$result->execute();
	
    $count = $result->rowCount();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $doc_id = $row['med_id'];
    $pat_id = $row['pat_id'];
}
if(isset($_GET['MED']))
{
    $doc_id = $_GET['MED'];
    $result = $con->prepare("SELECT name,surname FROM doctors where id=?");
	$result->bindValue(1, $doc_id, PDO::PARAM_INT);
	$result->execute();
	
    $count = $result->rowCount();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $doc_name = $row['name'].' '.$row['surname'];
}
if(isset($_GET['PAT']))
{
    $pat_id = $_GET['PAT'];
    $result = $con->prepare("SELECT Name,Surname FROM usuarios where Identif=?");
	$result->bindValue(1, $pat_id, PDO::PARAM_INT);
	$result->execute();
	
    $count = $result->rowCount();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $pat_name = $row['Name'].' '.$row['Surname'];
    $resultU = $con->prepare("UPDATE usuarios SET current_calling_doctor=0 WHERE Identif=?");
	$resultU->bindValue(1, $pat_id, PDO::PARAM_INT);
	$resultU->execute();
}

?>

<!DOCTYPE html>
<html>
    <head>
        <style>
            #call_button 
            { 
                border:1px solid #37943E;
                background-color: #37943E;
                padding:5px 15px; 
                -moz-border-radius: 5px; 
                -webkit-border-radius: 5px; 
                border-radius: 5px; 
                -webkit-box-shadow: 0 0 4px rgba(0,0,0, .75); 
                -moz-box-shadow: 0 0 4px rgba(0,0,0, .75); 
                box-shadow: 0 0 4px rgba(0,0,0, .75); 
                color:#f3f3f3; 
                font-size:1em; 
                cursor:pointer;
                width: 640px;
                height: 70px;
                margin-top: 15px;
            }
        </style>
        <script src="js/SimpleWebRTC/latest.js"></script> 
        <script src="js/SimpleWebRTC/RecordRTC.js"></script>
        <script src="js/socket.io-1.3.5.js"></script>
        <script src="push/push_client.js"></script>
    </head>
    <body>
        <input id="app_id" type="hidden" value="<?php echo $doc_id.'_'.$pat_id; ?>" />
        <div style="float: left; width: 700 px; height: 480px; margin-left: 20px; margin-top: 20px;">
            
            <div id="remoteVideo" style="background-color: #393939; width: 640px; height: 480px; border-radius: 6px;">
                
                
            </div>
            <div style="float:left; margin-left: 15px; margin-top: -90px;"><video id="localVideo" width="100" style="mask-image: url(small_video_mask.png);" autoplay></video></div>
            <style>
            .status{
                    width: 616px;
                    height: 56px;
                    background-color: #5A5A5A;
                    border-radius: 10px;
                    padding: 12px;
                    margin-top: 10px;
                }
            .status p{
                    color: #FFFFFF;
                    font-size: 18px;
                    text-align: center;
                    margin-top: 10px;
                    margin-bottom: -10px;
                    font-family: Arial, Helvetica, sans-serif;
                    width: 75%;
                    margin-right: auto;
                    margin-left: auto;
                }
            </style>
            <div class="status" id="status_box" <?php if(!$show_notification){echo 'style="display:none;"';} ?>>
                <p>Sending consultation request to doctor <?php if($doc_id >= 0){echo $doc_name;} ?> ...</p>
            
            </div>
        
        </div>
        <div id="text_div"></div>
        
        
        <script src="js/jquery-1.10.2.min.js"></script>
        <script>
        $(document).ready(function() {
            var pat_id = <?php echo $pat_id; ?>;
            var doctor_id = <?php echo $doc_id; ?>;
            
            $(window).on("unload", function () 
            {
                $.post("update_webrtc.php", {doc_id: doctor_id, status: 0}, function(){});
            });
            $('#remoteVideo').bind("DOMSubtreeModified",function()
            {
                $("#status_box").css("background-color", "#247F30");
                $("#status_box").children("p").text("Doctor <?php echo $doc_name; ?> is now connected");
            });
            if(pat_id >= 0)
            {
                var push = new Push(pat_id.toString(), window.location.hostname + ':3955');
                push.bind('doc_response', function(data) 
                {
                    if(data == 'n')
                    {
                        $("#status_box").css("background-color", "#AE2122");
                        $("#status_box").children("p").html("Doctor <?php echo $doc_name; ?> is not available right now.<br/>Please try another doctor, or try again later.");
                    }
                    else
                    {
                        $("#status_box").css("background-color", "#247F30");
                        $("#status_box").children("p").html("Doctor <?php echo $doc_name; ?> accepted your request and will be connecting soon.<br/>please wait ...");
                    }
                });
            }
            var appointment_id = $("#app_id").val();
            
            var localStream, localPeerConnection, remotePeerConnection;

            var localVideo = document.getElementById("localVideo");
            var remoteVideo = document.getElementById("remoteVideo");
            
            var constraints = {video: true, audio: true};
            
            var video_record_options = {type: 'video'};
            var audio_record_options = {type: 'audio', bufferSize: 16384};
            var video_recorder;
            var audio_recorder;
            var blobs_saved = 0;
            var formData;
            
            var teleurl='<?php echo $hardcode; ?>';    //Removed the hardcoded url 'http://dev.health2.me:8888' - Debraj
            var parseURL = document.createElement('a');
            parseURL.href = teleurl;
            teleurl = parseURL.protocol + '//' + parseURL.hostname + ':8888';
            
            console.log("SIGNALING SERVER: " + teleurl);
            
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
                console.log(data);
                var info = JSON.parse(data);
                var peerConnectionConfig = info.d;
                //var peerConnectionConfig = {"iceServers":[{"url":"stun:turn2.xirsys.com"},{"username":"24edf7ba-ed50-4a30-9e3c-2ad42780ac1b","url":"turn:turn2.xirsys.com:443?transport=udp","credential":"b62f6f45-db7a-45f7-a517-9c0e539ac82d"},{"username":"24edf7ba-ed50-4a30-9e3c-2ad42780ac1b","url":"turn:turn2.xirsys.com:443?transport=tcp","credential":"b62f6f45-db7a-45f7-a517-9c0e539ac82d"}]};
                var webrtc = new SimpleWebRTC({localVideoEl: 'localVideo',remoteVideosEl: 'remoteVideo', url: teleurl, autoRequestMedia: true, peerConnectionConfig: peerConnectionConfig});
                webrtc.on('readyToCall', function () 
                {
                    //video_recorder = RecordRTC(webrtc.webrtc.localStream, video_record_options);
                    //audio_recorder = RecordRTC(webrtc.webrtc.localStream, audio_record_options);
                    webrtc.joinRoom('health2me_room_' + doctor_id + '_' + pat_id);
                    //call();
                    //$.post("update_webrtc.php", {doc_id: doctor_id, status: 1}, function(data, status){console.log(data);});
                    //setInterval(function(){$.post("update_webrtc.php", {doc_id: doctor_id, update_lastseen: true}, function(data, status){console.log(data);});}, 30000);
                    //$(window).unbind("beforeunload");
                });
            });
            
            
            function gotStream(stream)
            {
                //localVideo.src = URL.createObjectURL(stream);
                localStream = stream;
                alert(URL.createObjectURL(stream));
                
                var servers = {iceServers: [{ url: 'stun:stun.l.google.com:19302' }]};
                
                localPeerConnection = new webkitRTCPeerConnection(servers);
                localPeerConnection.addStream(localStream);
                localPeerConnection.onicecandidate = gotLocalIceCandidate;
                console.log("Got Stream");
                
                remotePeerConnection = new webkitRTCPeerConnection(servers);
                remotePeerConnection.onicecandidate = gotRemoteIceCandidate;
                remotePeerConnection.onaddstream = gotRemoteStream;
                
                //poll_appointment();
                
            }
            $("#remoteVideo").click(function(){ alert($("#remoteVideo > video").length);});
            
            /*
            navigator.getUserMedia = navigator.getUserMedia ||
            navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
            navigator.getUserMedia(constraints, gotStream,
            function(error) 
            {
                console.log("navigator.getUserMedia error");
            });
            */
            
            // only call this function for RecordRTC inside a stopRecording() function
            function save_blob(type, blob)
            {
                blobs_saved += 1;
                var fileType;
                var fileName;
                if (blobs_saved == 1)
                {
                    formData = new FormData();
                    if (type == 'video')
                    {
                        fileType = 'video';
                        fileName = appointment_id.toString() + "_patient_video.webm";
                    }
                    else
                    {
                        fileType = 'audio';
                        fileName = appointment_id.toString() + "_patient_audio.wav";
                    }
                    formData.append(fileType + '-filename', fileName);
                    if (type == 'video')
                    {
                        formData.append(fileType + '-blob', video_recorder.getBlob());
                    }
                    else
                    {
                        formData.append(fileType + '-blob', audio_recorder.getBlob());
                    }
                    
                }
                else if(blobs_saved == 2)
                {
                    if (type == 'video')
                    {
                        fileType = 'video';
                        fileName = appointment_id.toString() + "_patient_video.webm";
                    }
                    else
                    {
                        fileType = 'audio';
                        fileName = appointment_id.toString() + "_patient_audio.wav";
                    }
                    
                    formData.append(fileType + '-filename', fileName);
                    if (type == 'video')
                    {
                        formData.append(fileType + '-blob', video_recorder.getBlob());
                    }
                    else
                    {
                        formData.append(fileType + '-blob', audio_recorder.getBlob());
                    }
                    formData.append('id', appointment_id.toString());
                    formData.append('user_type', 'patient');
                    function xhr(url, data, callback) 
                    {
                        var request = new XMLHttpRequest();
                        request.onreadystatechange = function () 
                        {
                            if (request.readyState == 4 && request.status == 200) 
                            {
                                callback(location.href + request.responseText);
                            }
                        };
                        request.open('POST', url);
                        request.send(data);
                    }
                    xhr('save_rtc.php', formData, function (fileURL)
                    {
                        console.log(fileURL);
                        blobs_saved = 0;
                        console.log('DONE!');
                    });
                }
            }
            function call() 
            {
                var servers = {iceServers: [{url: 'stun:stun.l.google.com:19302'},
 {"url": "turn:ITGroup@54.225.226.163:3478?transport=udp", "credential": "a0e828972364d59b03024eb38837fb86"},
 {"url": "turn:ITGroup@54.225.226.163:3478?transport=tcp", "credential": "a0e828972364d59b03024eb38837fb86"}]};
                
                /*
                localPeerConnection = new webkitRTCPeerConnection(servers);
                localPeerConnection.addStream(localStream);
                localPeerConnection.onicecandidate = gotLocalIceCandidate;
                
                remotePeerConnection = new webkitRTCPeerConnection(servers);
                remotePeerConnection.onicecandidate = gotRemoteIceCandidate;
                remotePeerConnection.onaddstream = gotRemoteStream;
                
                localPeerConnection.createOffer(gotLocalDescription);
                */
                
                webrtc.joinRoom('health2me_room_' + appointment_id);
                // update button to handle hang up instead of call
                $("#call_button").css("background-color", "#C92910");
                $("#call_button").css("border", "1px solid #C92910");
                $("#call_button").attr("value", "Leave Appointment");
                video_recorder.startRecording();
                audio_recorder.startRecording();
            }
            function hangup() 
            {
                /*
                localPeerConnection.close();
                remotePeerConnection.close();
                localPeerConnection = null;
                remotePeerConnection = null;
                */
                webrtc.leaveRoom();
                //localVideo.hidden = true;
                //remoteVideo.hidden = true;
                //$.post("update_appointments.php",{appointment_id: appointment_id, type: 1, obj: "0", reset: 1}, 
                //       function(data,status){});
                
                // clear the description objects from the appointments data table here
                
                // update button to handle call instead of hang up
                $("#call_button").css("background-color", "#37943E");
                $("#call_button").css("border", "1px solid #37943E");
                $("#call_button").attr("value", "Enter Appointment");
                blobs_saved = 0;
                video_recorder.stopRecording(function (Blob)
                {
                    save_blob('video');
                });
                audio_recorder.stopRecording(function (Blob)
                {
                    save_blob('audio');
                });
                
            }
            
            function gotLocalDescription(description)
            {
                localPeerConnection.setLocalDescription(description);
                remotePeerConnection.setRemoteDescription(description);
                
                var json_desc = JSON.stringify(description);
                json_desc = json_desc.replace(/\\r\\n/g, "(SP)");
                $.post("update_appointments.php",{appointment_id: appointment_id, type: 2, obj: json_desc, reset: 0}, 
                       function(data,status){});
                console.log("Got local description");
                
            }
            
            function gotRemoteDescription(description)
            {
                remotePeerConnection.setLocalDescription(description);
                localPeerConnection.setRemoteDescription(description);
                
                // create answer here, add it locally by calling getLocalDescription,
                // and update the appointments date table with it
                localPeerConnection.createAnswer(gotLocalDescription);
                console.log("Got Remote Description");
                poll_candidates();
            }
            
            function gotRemoteStream(event)
            {
                console.log("Got Remote Stream");
                remoteVideo.src = URL.createObjectURL(event.stream);
            }
            
            function gotLocalIceCandidate(event)
            {
                if (event.candidate) 
                {
                    global_candidate = event.candidate;
                    var iceCandidate = new RTCIceCandidate(event.candidate);
                    console.log("Got Local Ice Candidate: " + iceCandidate.candidate);
                    remotePeerConnection.addIceCandidate(iceCandidate);
                    
                    // send new local candidate to patient
                    var cand = "";
                    cand += iceCandidate.candidate;
                    cand += "$";
                    cand += iceCandidate.sdpMLineIndex.toString();
                    cand += "$";
                    cand += iceCandidate.sdpMid;
                    local_ice_candidates[local_ice_candidates_count] = cand;
                    local_ice_candidates_count += 1;
                    var str = "";
                    for(var i = 0; i < local_ice_candidates_count; i++)
                    {
                        if(i != 0)
                        {
                            str += "#";
                        }
                        str += local_ice_candidates[i];
                    }
                    $.post("update_appointments.php",{appointment_id: appointment_id, type: 3, obj: str, reset: 0}, 
                       function(data,status){});
                }
            }
            
            function gotRemoteIceCandidate(event)
            {
                if (event.candidate) 
                {
                    var iceCandidate = new RTCIceCandidate(event.candidate);
                    console.log("Got Remote Ice Candidate: " + iceCandidate.candidate);
                    localPeerConnection.addIceCandidate(iceCandidate);
                }
            }
            
            // this function is used to check if the patient has accepted the offer and answered
            function poll_appointment()
            {
                var inter = setInterval(function(){
                    $.post("poll_appointments.php", {appointment_id: appointment_id, type: 2}, function(data,status)
                    {
                        if(data != '0')
                        {
                            // there is a description, handle it
                            console.log("Received Doctor Description");
                            clearInterval(inter);
                            
                            var t = data.replace(/\(SP\)/g, "\\r\\n");
                            
                            var remoteDesc = new RTCSessionDescription(JSON.parse(t));
                        
                            // handle offer answer here
                            gotRemoteDescription(remoteDesc);
                            
                        }
                    });
                }, 3000);
            }
            // this function is used to check for new remote ice candidates after the remote description has been set
            function poll_candidates()
            {
                num_candidates = 0;
                console.log("Polling candidates");
                var inter2 = setInterval(function(){
                    $.post("poll_appointments.php", {appointment_id: appointment_id, type: 3}, function(data,status) // need to change the appointment id
                    {
                        if(data != '0')
                        {
                            // there are some candidates, handle them
                            var cands = data.split("#");
                            for(var i = 0; i < cands.length; i++)
                            {
                                if(i >= num_candidates)
                                {
                                    var attrbs = cands[i].split("$");
                                    var candidate = new RTCIceCandidate(global_candidate);
                                    candidate.candidate = attrbs[0];
                                    candidate.sdpMLineIndex = parseInt(attrbs[1]);
                                    candidate.sdpMid = attrbs[2];
                                    localPeerConnection.addIceCandidate(candidate);
                                    console.log("Received new remote candidate");
                                }
                            }
                            num_candidates = cands.length;
                            
                        }
                    });
                }, 3000);
            }
        });
        </script>
    </body>
</html>
