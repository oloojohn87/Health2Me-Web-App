<?php
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");
$id = 1;
$doc_id = -1;
$pat_id = -1;
$doc_name = '';
$pat_name = '';
if(isset($_GET['ID']))
{
    $id = $_GET['ID'];
    $result = mysql_query("SELECT med_id,pat_id FROM appointments where id=".$id);
    $count=mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    $doc_id = $row['med_id'];
    $pat_id = $row['pat_id'];
}
if(isset($_GET['MED']))
{
    $doc_id = $_GET['MED'];
    $result = mysql_query("SELECT name,surname FROM doctors where id=".$doc_id);
    $count=mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    $doc_name = $row['name'].' '.$row['surname'];
}
if(isset($_GET['PAT']))
{
    $pat_id = $_GET['PAT'];
    $result = mysql_query("SELECT Name,Surname FROM usuarios where Identif=".$pat_id);
    $count=mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    $pat_name = $row['Name'].' '.$row['Surname'];
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
    </head>
    <body>
        <input id="APP_ID" type="hidden" value="<?php echo $doc_id."_".$pat_id; ?>" />

        <div style="float: left; width: 300 px; margin-left: 20px; margin-top: 20px;">
            <textarea cols="40" rows="35" name="notes"  style="padding: 15px; border-radius: 6px; width:270px; resize: none; color: #747474;  border: 1px solid #ccc;">Notes...</textarea>
        
        </div>
        <div style="float: left; width: 700 px; height: 550px; margin-left: 20px; margin-top: 20px;">
            <div id="remoteVideo" style="background-color: #393939; width: 640px; height: 480px; border-radius: 6px;">
                
                
            </div>
            <div style="float:left; margin-left: 15px; margin-top: -90px;"><video id="localVideo" width="100" style="mask-image: url(small_video_mask.png);" autoplay></video></div>
            
        
        </div>
        
        
        
        <script src="js/jquery-1.10.2.min.js"></script>
        
        <script>
            
            var appointment_id = $("#APP_ID").val();
            var doctor_id = <?php echo $doc_id; ?>;
            
            var localStream, localPeerConnection, remotePeerConnection;
            
            var constraints = {video: true, audio: false};
            
            var local_ice_candidates = new Array ();
            var local_ice_candidates_count = 0;
            var global_candidate;

            var localVideo = document.getElementById("localVideo");
            var remoteVideo = document.getElementById("remoteVideo");
            
            // setup recorder to record local video stream
            var video_record_options = {type: 'video'};
            var audio_record_options = {type: 'audio', bufferSize: 16384};
            var video_recorder;
            var audio_recorder;
            var blobs_saved = 0;
            var formData;
            
              
            var teleurl='<?php echo $domain; ?>:8888';   
            
            var webrtc = new SimpleWebRTC({localVideoEl: 'localVideo',remoteVideosEl: 'remoteVideo',autoRequestMedia: true, url: teleurl});
            webrtc.on('readyToCall', function () 
            {
                video_recorder = RecordRTC(webrtc.webrtc.localStream, video_record_options);
                audio_recorder = RecordRTC(webrtc.webrtc.localStream, audio_record_options);
                call();
                $.post("update_webrtc.php", {doc_id: doctor_id, status: 1}, function(){});
                $(window).unbind("beforeunload");
                $(window).bind("beforeunload", function (e) 
                {
                   $.post("update_webrtc.php", {doc_id: doctor_id, status: 0}, function(){});
                });
            });
            
            $.post("update_appointments.php",{appointment_id: 1, type: 1, obj: "0", reset: 1}, 
                       function(data,status){});
            
            function gotStream(stream)
            {
                console.log("Got Stream");
                //localVideo.src = URL.createObjectURL(stream);
                localVideo.hidden = false;
                localStream = stream;
                
            }
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
            function save_blob(type)
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
                        fileName = appointment_id.toString() + "_doctor_video.webm";
                    }
                    else
                    {
                        fileType = 'audio';
                        fileName = appointment_id.toString() + "_doctor_audio.wav";
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
                        fileName = appointment_id.toString() + "_doctor_video.webm";
                    }
                    else
                    {
                        fileType = 'audio';
                        fileName = appointment_id.toString() + "_doctor_audio.wav";
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
                    formData.append('user_type', 'doctor');
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
                var servers = {iceServers: [{url: 'stun:stun.l.google.com:19302'}]};
                
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
                $("#call_button").attr("value", "Call");
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
                
                // Encode description and send it to the patient by
                // storing it in the appointment entry in the appointments
                // database. Then the patient will poll the data table
                // (like every 5 seconds) and when it sees my offer it
                // will handle it and respond with an answer with the 
                // same mechanism.
                //
                // So you update the database with your offer and poll it 
                // every 3 seconds until you get an answer from the patient.
                //
                // Encode description with following line: JSON.stringify(description)
                var json_desc = JSON.stringify(description);
                json_desc = json_desc.replace(/\\r\\n/g, "(SP)");
                $.post("update_appointments.php",{appointment_id: appointment_id, type: 1, obj: json_desc, reset: 0}, 
                       function(data,status){});
                console.log("Got local description");
                poll_appointment();
                //remotePeerConnection.createAnswer(gotRemoteDescription);
            }
            
            function gotRemoteDescription(description)
            {
                remotePeerConnection.setLocalDescription(description);
                localPeerConnection.setRemoteDescription(description);
                console.log("Got Remote Description");
                poll_candidates();
            }
            
            function gotRemoteStream(event)
            {
                console.log("Got Remote Stream");
                remoteVideo.hidden = false;
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
                    $.post("update_appointments.php",{appointment_id: appointment_id, type: 4, obj: str, reset: 0}, 
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
                console.log("Polling appointment");
                var inter = setInterval(function(){
                    $.post("poll_appointments.php", {appointment_id: appointment_id, type: 1}, function(data,status) // need to change the appointment id
                    {
                        if(data != '0')
                        {
                            // there is a description, handle it
                            var t = data.replace(/\(SP\)/g, "\\r\\n");
                            
                            var remoteDesc = new RTCSessionDescription(JSON.parse(t));
                            
                        
                            // handle offer answer here
                            gotRemoteDescription(remoteDesc);
                            
                            clearInterval(inter);
                            console.log("Received Patient Response");
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
                    $.post("poll_appointments.php", {appointment_id: appointment_id, type: 4}, function(data,status) // need to change the appointment id
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
            
        </script>
    </body>
</html>
