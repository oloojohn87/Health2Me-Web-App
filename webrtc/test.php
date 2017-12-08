<?php
    
    $my_id = 1;
    if(isset($_GET['my_id']))
    {
        $my_id = $_GET['my_id'];
    }

    $remote_id = 1;
    if(isset($_GET['remote_id']))
    {
        $remote_id = $_GET['remote_id'];
    }
    

?>

<html>
    <head>
        <title>WebRTC Test Page</title>
        
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="https://cdn.socket.io/socket.io-1.3.5.js"></script>
        <script src="webrtc.js"></script>
    </head>
    <body>
        <div style="float: left; width: 700 px; height: 550px; margin-left: 20px; margin-top: 20px;">
            <div id="remoteVideos" style="background-color: #393939; width: 640px; height: 480px; border-radius: 6px;">
                <video id="remoteVideo" width="100" autoplay></video>
                
            </div>
            <div style="float:left; margin-left: 15px; margin-top: -90px;"><video id="localVideo" width="100" autoplay></video></div>
            
        
        </div>
        <script>
            $(document).ready(function()
            {
                var properties = {
                    ID: <?php echo $my_id; ?>,
                    remoteID: <?php echo $remote_id; ?>,
                    localVideo: "localVideo",
                    remoteVideo: "remoteVideo",
                    onReadyToCall: function()
                    {
                        console.log('ready to call');
                        rtc.enterRoom();
                    },
                    onConnecting: function()
                    {
                        console.log('CONNECTING...');
                    },
                    onConnected: function()
                    {
                        console.log('CONNECTED!');
                    },
                    onFailed: function()
                    {
                        console.log('FAILED!');
                    },
                    onDisconnected: function()
                    {
                        console.log('DISCONNECTED!');
                    },
                };
                var rtc = new WebRTC(properties);
            });
        
        </script>
    </body>
    
    
</html>